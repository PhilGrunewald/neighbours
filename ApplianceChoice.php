<!DOCTYPE HTML>
<html lang="en">
<head>
<?php include("header.php"); ?>
<style>
input[type=range][orient=vertical]
{
	writing-mode: bt-lr; /* IE */
	-webkit-appearance: slider-vertical; /* WebKit */
	width: 8px;
	height: 150px;
	padding: 0 5px;
}
</style>
</head>
<body>

<?php
include("_nav_bar_neighbours.php"); 
include 'db_neighbours.php';
$db = mysqli_connect($server,$dbUserName,$dbUserPass,$dbName);
?>

<?php
if (isset($_GET[sid])) {
	// first round
	$sql="INSERT INTO House 
		(`Street_idStreet`,`HouseType`,`Round`) 
		VALUES ('$_GET[sid]',$_GET[ht],'0')";
	mysqli_query($db,$sql);
	$idHouse = mysqli_insert_id($db);
	$_GET[hid] = $idHouse;
	// pick the default values for this house type (street = 0)
	$sqlq = "SELECT Appliance_idAppliance,Name, Power, Minutes,icon FROM Choices Join Appliances On idAppliances = Appliance_idAppliance WHERE Street_idStreet = 0 AND House_idHouse = $_GET[ht];";
	$idStreet = $_GET[sid];
	$Round=0;
}
else { // repeat round
	$idHouse = $_GET[hid];
	// find out which round this house is on
	$sqlq = "SELECT max(House_Round),max(Street_idStreet) FROM Choices WHERE House_idHouse = $idHouse";
	$result = mysqli_query($db,$sqlq);
	$row = mysqli_fetch_assoc($result);
	$Round = $row['max(House_Round)'];
	$idStreet = $row['Street_idStreet'];

	// get the entries from the last round as default
	$sqlq = "SELECT Appliance_idAppliance,Name, Power, Minutes,icon FROM Choices Join Appliances On idAppliances = Appliance_idAppliance WHERE House_idHouse = $idHouse AND House_Round = $Round;";
}

?>

<div class="container">
<h2>Your House</h2>
	<?php if ($Round == 0) { 
	echo '<p>You can give your house a name, if you like</p>';
	echo '<input id="HouseName" type="text" name="name" value="My House"></br>';

	} ?>

<div class="row">
	<form method="get" action="commit.php">
		<div class="col-xs-6 col-xs-push-1" style="background-color: transparent;">
		<div class="houseRooms" style='background-image: url("img/house_0.png")'>
<?php
//echo "<table>";
$result = mysqli_query($db,$sqlq);
$lshift = 0;
$tshift = 0;
$room   = 0;
while($appliance = mysqli_fetch_assoc($result)) {
	$id         = $appliance['Appliance_idAppliance'];
	$power      = $appliance['Power'];
	$minutes	= $appliance['Minutes'];

	$parameters = $id.",\"".$appliance['Name']."\",".$power;
	$dblclick   = "ondblclick='toggleAppliance(".$parameters.")'";
	$click      = "onclick=     'showAppliance(".$parameters.")'";
	$icon		= $appliance['icon'];
	$img        = "src=\"img/app_$icon.png\"";
	$oldroom = $room;
	if ($id < 10) {
		// Attic
		$top = 80;
		$left = 36;
		$room = 1;
	} 
	elseif ($id < 30) {
		// bathroom
		$top = 130;
		$left = 20;
		$room = 2;
	} 
	else if ($id < 50) {
		// bathroom
		$top = 130;
		$left = 50;
		$room = 3;
	}
	else if ($id < 70) {
		// Living room
		$top = 260;
		$left = 50;
		$room = 5;
	}
	else if ($id < 90) {
		// kitchen
		$top = 260;
		$left = 20;
		$room = 4;
	}
	else {
		// 
		$top = 260;
		$left = 80;
		$room = 6;
	}

	if ($room == $oldroom) {
		$top = $top + $tshift;
		$left= $left+ $lshift;
	} else {
		$tshift = 0;
		$lshift = 0;
	}
	$leftpx = 5*$left."px";
	$toppx = $top."px";
	$position	= "style=\"top: $toppx; left: $leftpx\";";
	echo "<img class=\"appliance\" id=\"icon_$id\" $position $click $dblclick $img>";

	$lshift = $lshift + 7;
	if ($lshift > 21) {
		$lshift = 0;
		$tshift = $tshift + 50;
	}


	echo '<input type  ="hidden" 
		id    ="'.$appliance['Appliance_idAppliance'].'" 
		name  ="'.$appliance['Appliance_idAppliance'].'" 
		value ="'.intval($minutes).'">';
	if ($Round == 0) {
		$sqlq="INSERT INTO Choices (`House_Round`,`Street_idStreet`,`House_idHouse`,`Appliance_idAppliance`,`Minutes`) VALUES ('$Round','$idStreet','$idHouse','$id','$minutes')";
		mysqli_query($db,$sqlq);
	}
}
$NextRound = intval($Round)+1;
$sqlq="UPDATE House SET Round = $NextRound WHERE idHouse = $idHouse";
mysqli_query($db,$sqlq);
$sqlq="UPDATE Street SET House_Round = $NextRound WHERE idStreet = $idStreet";
mysqli_query($db,$sqlq);
?>
</div> <!-- houseRooms  -->
</div> <!-- col house  -->

	<div class="col-xs-12 col-sm-3 col-sm-push-2" style="background-color: transparent;"> <!-- appliance settings -->
		<h1 id="ApplianceName">Pick an appliance to change its use</h1>
		<img id="ApplianceIcon">
		<h3>Power: <span id="AppliancePower"></span> Watt </h3>
		<h3 id="ApplianceMinutes"></h3>
		<div id="powerBox"> </div>
		<input id="ApplianceRange" type="range" max="60" min="0"  onchange='updateBox(this.name, this.value)'>
		<p>For how much time do you need this between 5:30 and 6:30pm?</p>
	</div> <!-- appliance settings -->
</div> <!-- row  -->
<div class="row">
</br>
			<input type="hidden" name="hid" value="<?php echo $idHouse; ?>">
			<input class="btn-success btn-lg center" type="submit" value="Let's see what happens..." >
		</form>
	</div> <!-- r2 -->

</div> <!-- container  -->

<script>
function toggleAppliance(id,Name,Power) {
	var oldPower = document.getElementById(id).value;
	if (oldPower > 0) {
		document.getElementById(id).value = 0;
	} else {
		document.getElementById(id).value = 60;
	}
	showAppliance(id,Name,Power);
}

function showAppliance(id,Name,Power) {
	var Minutes =  document.getElementById(id).value;
	var iconID = "icon_";
	iconID = iconID.concat(id);
	var iconSrc = document.getElementById(iconID).src;
	document.getElementById("ApplianceIcon").src = iconSrc;
	document.getElementById("ApplianceName").innerHTML = Name;
	document.getElementById("AppliancePower").innerHTML = Power;
	document.getElementById("ApplianceRange").value = Minutes;
	document.getElementById("ApplianceRange").name = id;
	updateBox(id,Minutes);
}

function updateBox(id,minutes) {
	document.getElementById(id).value = minutes;  // the hidden input value where id="Appliance_idAppliance"
	document.getElementById("powerBox").innerHTML = minutes;
	// var w = parseInt(document.getElementById("ApplianceRange").style.width);
	var w = 200*minutes/60;
	val = w.toString();
	var width = val.concat("px");
	document.getElementById("powerBox").style.width = width;

	var iconID = "icon_";
	iconID = iconID.concat(id);

	var minStr = "On for: ";
	if (minutes == 0) {
		minStr = "Switched off";
		document.getElementById(iconID).style.opacity = 0.3;
	} else {
		minStr = minStr.concat(minutes);
		minStr = minStr.concat(" minutes");
		document.getElementById(iconID).style.opacity = 1;
	}

	document.getElementById("ApplianceMinutes").innerHTML = minStr;
	document.getElementById("ApplianceMinutes").innerHTML = w;
}
</script>

	</body>
</html>
