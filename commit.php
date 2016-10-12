<!DOCTYPE HTML>
<html lang="en">
<head>
<!--
<meta http-equiv="refresh" content="10" >
-->
<?php include("header.php"); ?>
</head>
<body>

<?php include("_nav_bar_neighbours.php"); ?>
<div class="container">
	<div class="row">
<?php

	include 'db_neighbours.php';
	$db = mysqli_connect($server,$dbUserName,$dbUserPass,$dbName);

	$idHouse  = $_GET['hid'];

	// get Round
	$sqlq   = "SELECT Round,Street_idStreet FROM House WHERE idHouse = $idHouse";
	$result = mysqli_query($db,$sqlq);
	$row    = mysqli_fetch_assoc($result);
	$House_Round = $row['Round'];
	$idStreet = $row['Street_idStreet'];

	// get highest commited round
	$sqlq   = "SELECT max(House_Round) FROM Choices WHERE House_idHouse = $idHouse";
	$result = mysqli_query($db,$sqlq);
	$row    = mysqli_fetch_assoc($result);
	$Commited_Round = $row['max(House_Round)'];

	echo "<h1>Results after round $Commited_Round</h1>";


if ($House_Round > $Commited_Round) {
      foreach($_GET as $key => $value) {
	if (($key != 'sid') && ($key != 'hid')) {
	$sql="INSERT INTO Choices (`House_Round`,`Street_idStreet`,`House_idHouse`,`Appliance_idAppliance`,`Minutes`) VALUES ('$House_Round','$idStreet','$idHouse','$key','$value')";
	mysqli_query($db,$sql);
	mysqli_commit($db);
	}
      }
}

// get Round of the 'remainng House' to commit
$sql="SELECT min(Round) From House WHERE Street_idStreet = $idStreet";
$result = mysqli_query($db,$sql);
$row = mysqli_fetch_assoc($result);
$minRound = $row['min(Round)'];

$StreetReference = 0;
$StreetEnergy    = 0;

$sql="SELECT idHouse,HouseType,Round From House WHERE Street_idStreet = $idStreet";
$result = mysqli_query($db,$sql);
while ($row = mysqli_fetch_assoc($result)) {
	$idStreetHouse = $row['idHouse'];
	$sql="SELECT Power, Minutes
	  FROM Choices 
	  Join Appliances
	  On idAppliances = Appliance_idAppliance
	  WHERE Street_idStreet = $idStreet 
	  AND House_idHouse = $idStreetHouse
	  AND House_Round = $House_Round
	  ";
	$PowerTime = mysqli_query($db,$sql);
	$HouseEnergy = 0;
	while ($pt = mysqli_fetch_assoc($PowerTime)) {
		$HouseEnergy = $HouseEnergy + intval(intval($pt['Power'])*intval($pt['Minutes'])/60000);
		}
	$StreetEnergy = $StreetEnergy + $HouseEnergy;
        //
	// Reference House Energy
	$LastRound = $House_Round-1;
	$sql="SELECT Power, Minutes
	  FROM Choices 
	  Join Appliances
	  On idAppliances = Appliance_idAppliance
	  WHERE Street_idStreet = $idStreet 
	  AND House_idHouse = $idStreetHouse
	  AND House_Round = $LastRound
	  ";
	$PowerTime = mysqli_query($db,$sql);
	$RefEnergy = 0;
	while ($pt = mysqli_fetch_assoc($PowerTime)) {
		$RefEnergy = $RefEnergy + intval(intval($pt['Power'])*intval($pt['Minutes'])/60000);
	}
	// Reference Street Energy
	$sql="SELECT Power, Minutes
	  FROM Choices 
	  Join Appliances
	  On idAppliances = Appliance_idAppliance
	  WHERE Street_idStreet = $idStreet 
	  AND House_idHouse = $idStreetHouse
	  AND House_Round = 0
	  ";
	$PowerTime = mysqli_query($db,$sql);
	while ($pt = mysqli_fetch_assoc($PowerTime)) {
		$StreetReference = $StreetReference + intval(intval($pt['Power'])*intval($pt['Minutes'])/60000);
	}

	echo "<div class='col-xs-4 col-sm-3 top-buffer nopadding'>";
        if ($row['Round'] >= $House_Round) {
            echo '<img class="houseicon" id="house_'.$row['HouseType'].'" src="img/house_'.$row['HouseType'].'">';
            echo '<div class="powerbar" style="height:'.intval(15*$RefEnergy).'px;"></div>';
            if ($HouseEnergy > $RefEnergy) {
                echo '<div class="powerbar red" style="height:'.intval(15*$HouseEnergy).'px;"></div>';
            	echo '<div>'.$HouseEnergy.' kWh ('.intval($HouseEnergy-$RefEnergy).' kWh more than before)</div>';
            }
            else if ($HouseEnergy == $RefEnergy) {
                echo '<div class="powerbar red" style="height:'.intval(15*$HouseEnergy).'px;"></div>';
            	echo '<div>'.$HouseEnergy.' kWh (same as before)</div>';
            }
            else {
                echo '<div class="powerbar green" style="height:'.intval(15*$HouseEnergy).'px;"></div>';
            	echo '<div>'.$HouseEnergy.' kWh ('.intval($HouseEnergy-$RefEnergy).' kWh less than before)</div>';
            }
        }
        else {
            echo '<img class="houseicon dim" src="img/house_'.$row['HouseType'].'_">';
            echo '<div>Waiting to commit</div>';
        }
        echo "</div>"; 


		$benchmark = 0.9*$StreetReference;
}
?>
<div class='col-xs-4 col-sm-3 top-buffer nopadding'>
<table onclick="growBar(<?php echo intval(150*$StreetEnergy/$StreetReference);?>)">
<tr><td>
<img class="housebutton" id="transformer" onclick="growBar(<?php echo intval(150*$StreetEnergy/$StreetReference); ?>)" src="img/transformer_static.gif">
</td> <td class="bars">
<div class="powerbar feeder" style="height:150px;"></div>
</td> <td class="bars">
<div class="powerbar feeder red" id="transformerBar" style="height:0px;"></div>
</td> </tr> </table> 

</div>

<?php
if ($House_Round == $minRound) {
	echo "<br><br>Your street used $StreetEnergy Watt hours of electricity";
} else {
	echo "waiting $myRound  xx";
   //  echo "	and $minRound";
}
?>

<form method="get" action="ApplianceChoice.php">
	<input type="hidden" name="hid" value="<?php echo $idHouse; ?>">
	<input type="submit" value="Try again" >
</form> 

</div>
</div>
<script>
function growBar(h) {
    height = h.toString();
    var heightStr = height.concat("px");
    document.getElementById("transformerBar").style.height = heightStr;
	if (h > 150) {
		document.getElementById("transformer").src = 'img/transformer_blowup.gif';
		setTimeout(blackout, 4000)
	} else {
    	document.getElementById("transformer").src = 'img/transformer_ok.gif';
	}
}

function blackout() {
		document.body.style.backgroundColor = "black";
	houses = document.getElementsByClassName("houseicon");
	for (var i = 0; i < houses.length; i++) {
		var id = houses[i].id;
		var file = "img/";
		var src  = file.concat(id);
		src = src.concat("_.png")
		houses[i].src = 'img/house_4_.png';
		houses[i].src = src;
	}
}
</script>
</body>
</html>
