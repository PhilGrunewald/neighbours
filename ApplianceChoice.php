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
$idStreet = $_GET[sid];
$ht = $_GET[ht];
$sql="SELECT idHouse FROM House WHERE Street_idStreet = $idStreet AND HouseType = $_GET[ht]";
$result = mysqli_query($db,$sql);
if (mysqli_num_rows($result)) {
	// a house like this exists (could be a page reload or two people choosing the same houseType
	$row = mysqli_fetch_assoc($result);
	if ($_GET[hid] == 0) {
		// arriving from (or reloading from) HouseChoice.php
		//
		// OPTION 1 : everyone lives in the same house
		// XXX $idHouse = $row['idHouse'];
		//
		//
		// OPTION 2: create a house EVERY TIME
		$sql="INSERT INTO House 
			(`Street_idStreet`,`HouseType`,`Round`) 
			VALUES ('$_GET[sid]',$_GET[ht],'0')";
		mysqli_query($db,$sql);
		$idHouse = mysqli_insert_id($db);
		$_GET[hid] = $idHouse;
		// pick the default values for this house type (street = 0)
		$sqlq = "SELECT Appliance_idAppliance,Name, Power, Minutes,icon,x,y FROM Choices Join Appliances On idAppliances = Appliance_idAppliance WHERE Street_idStreet = 0 AND House_idHouse = $_GET[ht];";
		$Round=0;
	}
	else {
		// repeat round
		$idHouse = $_GET[hid];
	// find out which round this house is on
	// $sqlq = "SELECT max(House_Round),max(Street_idStreet) FROM Choices WHERE House_idHouse = $idHouse";
	$sqlq = "SELECT Round FROM House WHERE Street_idStreet = $idStreet AND idHouse = $idHouse";
	$result = mysqli_query($db,$sqlq);
	$row = mysqli_fetch_assoc($result);
	$Round = $row['Round'];

	// get the entries from the last round as default
	$sqlq = "SELECT Appliance_idAppliance,Name, Power, Minutes,icon,x,y FROM Choices Join Appliances On idAppliances = Appliance_idAppliance WHERE House_idHouse = $idHouse AND House_Round = $Round;";
	}

	}
	else {
		// no house like this exists - create house
		$sql="INSERT INTO House 
			(`Street_idStreet`,`HouseType`,`Round`) 
			VALUES ('$_GET[sid]',$_GET[ht],'0')";
		mysqli_query($db,$sql);
		$idHouse = mysqli_insert_id($db);
		$_GET[hid] = $idHouse;
		// pick the default values for this house type (street = 0)
		$sqlq = "SELECT Appliance_idAppliance,Name, Power, Minutes,icon,x,y FROM Choices Join Appliances On idAppliances = Appliance_idAppliance WHERE Street_idStreet = 0 AND House_idHouse = $_GET[ht];";
		$Round=0;
}

$sqlqm="SELECT Message FROM Story WHERE Street_House_Round = $Round AND House_HouseType = $_GET[ht]";
$result = mysqli_query($db,$sqlqm);
$row = mysqli_fetch_assoc($result);
$message = $row[Message];
?>

<div class="container">
<form method="get" action="commit.php">
<div class="row">
	<div class="col-xs-6 col-xs-push-1" style="background-color: transparent;">
	<?php 
	if ($Round == 0) { 
	echo "<h2>It is Wednesday, 6pm</h2>";
	echo "<p>Here is what you used yesterday at this time. What could you do without in the next hour? Click an appliance to make changes. Double click to turn on/off</p>";
	} else
	if ($Round == 1) { 
	echo "<h2>Now it is Thursday, 6pm</h2>";
	echo "<p>What changes could you make?</p>";
	} else
	if ($Round == 2) { 
	echo "<h2>Friday - the final day.</h2>";
	echo "<p>Last chance. Try to get the balance right.</p>";
	}
	?>
	</div>
</div>
<div class="row">
	<div class="col-xs-12 col-sm-8 col-sm-push-1" style="background-color: transparent;"> <!-- appliance settings -->
		<div class="houseRooms" style='background-image: url("img/house_<?php echo $ht; ?>a.png")'>
		<?php
		$result = mysqli_query($db,$sqlq);
		while($appliance = mysqli_fetch_assoc($result)) {
			$id         = $appliance['Appliance_idAppliance'];
			$power      = $appliance['Power'];
			$minutes	= $appliance['Minutes'];
			$left		= $appliance['x']."px";
			$top		= $appliance['y']."px";
		
			$parameters = $id.",\"".$appliance['Name']."\",".$power;
			$dblclick   = "ondblclick='toggleAppliance(".$parameters.")'";
			$click      = "onclick=     'showAppliance(".$parameters.")'";
			$icon		= $appliance['icon'];
			$img        = "src=\"img/app_$icon.png\"";
		
			if ($minutes > 0) {
				$opacity    = " opacity: 1;";
			} else {
				$opacity    = " opacity: 0.3;";
			}
			$position	= "style=\"top: $top; left: $left; $opacity\"; ";
			echo "<img class=\"appliance\" id=\"icon_$id\" $position $click $dblclick $img>";
		
		
			echo '<input type  ="hidden" 
				id    ="'.$appliance['Appliance_idAppliance'].'" 
				name  ="'.$appliance['Appliance_idAppliance'].'" 
				value ="'.intval($minutes).'">';
		
		
		 	if ($Round == 0) {
		 		// create the reference case
		 		$sqlq="INSERT INTO Choices (`House_Round`,`Street_idStreet`,`House_idHouse`,`Appliance_idAppliance`,`Minutes`) VALUES ('$Round','$idStreet','$idHouse','$id','$minutes')";
		 		mysqli_query($db,$sqlq);
		 	}
		}
		$NextRound = intval($Round)+1;
		// $sqlq="UPDATE House SET Round = $NextRound WHERE idHouse = $idHouse";
		// mysqli_query($db,$sqlq);
		$sqlq="SELECT House_Round FROM Street WHERE idStreet = $idStreet";
		$result = mysqli_query($db,$sqlq);
		$row = mysqli_fetch_assoc($result);
		if ($row[HouseRound] < $NextRound) { 
			$sqlq="UPDATE Street SET House_Round = $NextRound WHERE idStreet = $idStreet";
			mysqli_query($db,$sqlq);
		}
		echo "<div class='message' id='Message' onclick='hideMessage()'><img src='img/house_speech.png'><div class='messageText'>$message</div></div>";
		?>
		</div> <!-- houseRooms  -->
	</div> <!-- col house  -->

	<div class="col-xs-12 col-sm-3 col-sm-push-1 col-md-pull-1" style="background-color: transparent;"> <!-- appliance settings -->
		<h1 id="ApplianceName"></h1>
		<img id="ApplianceIcon">
		<h3><span id="AppliancePower"></span> Watt </h3>
		<h3 id="ApplianceMinutes"></h3>
		<img id="ApplianceSwitch" onclick='switchAppliance(this.name)'>
		<div id="powerBox"> </div>
		<input id="ApplianceRange" type="range" max="60" min="0"  onchange='updateBox(this.name, this.value)'>
		<p>How much do you need this in the next hour?</p>
	</div> <!-- appliance settings -->
</div> <!-- row  -->
<div class="row">
</br>
			<input type="hidden" name="hid" value="<?php echo $idHouse; ?>">
			<input type="hidden" name="ht" value="<?php echo $_GET[ht]; ?>">
			<input class="btn-success btn-lg center" type="submit" value="Let's see what happens..." >
		</form>
	</div> <!-- r2 -->

</div> <!-- container  -->

<script>
function hideMessage() {
	document.getElementById("Message").style.visibility = "hidden";
}

function switchAppliance(id) {
	var oldPower = document.getElementById(id).value;
	var Name = document.getElementById("ApplianceName").innerHTML;
	if (oldPower > 0) {
		updateBox(id,0);
		var Power = 0;
	} else {
		updateBox(id,60);
		var Power = 60;
	}
	showAppliance(id,Name,Power);
}

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
	document.getElementById("ApplianceSwitch").name = id;
	updateBox(id,Minutes);
}

function updateBox(id,minutes) {
	document.getElementById(id).value = minutes;  // the hidden input value where id="Appliance_idAppliance"
	// document.getElementById("powerBox").innerHTML = minutes;
	// var w = parseInt(document.getElementById("ApplianceRange").style.width);
	var w = 200*minutes/60;
	val = w.toString();
	var width = val.concat("px");
	document.getElementById("powerBox").style.width = width;
	var iconID = "icon_";
	iconID = iconID.concat(id);
	var minStr = "On for ";
	if (minutes == 0) {
		minStr = "Off";
		document.getElementById("ApplianceSwitch").src = "img/meter_switch_off.png";
		document.getElementById(iconID).style.opacity = 0.3;
	} else {
		minStr = minStr.concat(minutes);
		minStr = minStr.concat(" min");
		var opacity = 0.5 + (0.7 * (minutes/60));
		document.getElementById(iconID).style.opacity = opacity;
		// document.getElementById(iconID).style.opacity = 1;
		document.getElementById("ApplianceSwitch").src = "img/meter_switch_on.png";
	}
	document.getElementById("ApplianceMinutes").innerHTML = minStr;
}
</script>

	</body>
</html>
