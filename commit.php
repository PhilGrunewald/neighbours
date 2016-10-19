<!DOCTYPE HTML>
<html lang="en">
<head>
<?php
	include 'db_neighbours.php';
	$db = mysqli_connect($server,$dbUserName,$dbUserPass,$dbName);

	$idHouse  = $_GET['hid'];
	$hellPowerBase = 10;  // kWh per hour


	// get Round for this house
	$sqlq   = "SELECT Round,Street_idStreet FROM House WHERE idHouse = $idHouse";
	$result = mysqli_query($db,$sqlq);
	$row    = mysqli_fetch_assoc($result);
	$House_Round = $row['Round'];
	$idStreet = $row['Street_idStreet'];
	// get where the street is (this pre-empts the houses
	$sqlq   = "SELECT House_Round FROM Street WHERE idStreet = $idStreet";
	$result = mysqli_query($db,$sqlq);
	$row    = mysqli_fetch_assoc($result);
	$Street_Round = $row['House_Round'];

	if ($Street_Round > $House_Round) {
	$sql="UPDATE House SET Round = $Street_Round WHERE idHouse = $idHouse";
	mysqli_query($db,$sql);
	$House_Round = $Street_Round;
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
	
	if ($House_Round > $minRound) {
		echo '<meta http-equiv="refresh" content="5" />';
	}
	include("header.php"); 
?>

</head>
<body>
<script>
function growBar(energy,ref,height) {
	var barHeight = height*energy/ref;
    heightStr = barHeight.toString();
    var heightStr = heightStr.concat("px");

    document.getElementById("transformerBar").style.height = heightStr;
	if (energy > ref) {
		var delta = Math.round( (energy - ref ) * 10 ) / 10
		var message = "Ouch, your street used ";
		message = message.concat(delta);
		message = message.concat("kWh too much.");
		
		document.getElementById("transformer").onload = '';
		document.getElementById("transformer").src = 'img/transformer_blowup.gif';
    	document.getElementById("tryAgainText").innerHTML = message;
		setTimeout(blackout, 4000)
	} else {
		var delta = Math.round( (ref - energy) * 10 ) / 10
		// var delta = parseInt(1000*ref - 1000*energy);
		var message = "Well done. You have ";
		message = message.concat(delta);
		message = message.concat("kWh spare.");
		document.getElementById("transformer").onload = '';
    	document.getElementById("transformer").src = 'img/transformer_ok.gif';
    	document.getElementById("tryAgainText").innerHTML = message;
		setTimeout(tryAgain, 4000)
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
		// houses[i].src = 'img/house_4_.png';
		houses[i].src = src;
		setTimeout(tryAgain, 2000)
	}
}

function tryAgain() {
	document.getElementById("tryAgain").style.display = 'block';
}
</script>


<?php include("_nav_bar_neighbours.php"); ?>

<div class="container">
<div class="row">
<div class="col-xs-10 col-xs-push-1" style="background-color: transparent;">

<?php

	if ($Street_Round == 1) { 
	echo "<h2>Wednesday's verdict</h2>";
	} else
	if ($Street_Round == 2) { 
	echo "<h2>Thursday's results</h2>";
	} else
	if ($Street_Round == 3) { 
	echo "<h2>Friday - the finale.</h2>";
	}

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
		$HouseEnergy = $HouseEnergy + (intval($pt['Power'])*intval($pt['Minutes'])/60000);
		}

	$HouseEnergy = round($HouseEnergy,1);
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
		$RefEnergy = $RefEnergy + (intval($pt['Power'])*intval($pt['Minutes'])/60000);
	}
	$RefEnergy = round($RefEnergy,1);
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
		$StreetReference = $StreetReference + (intval($pt['Power'])*intval($pt['Minutes'])/60000);
	}

	echo "<div class='col-xs-4 col-sm-3 top-buffer nopadding'>";
        if ($row['Round'] >= $House_Round) {
			$delta = $HouseEnergy - $RefEnergy;
			if ($row['idHouse'] == $idHouse) {
				echo "My House<br>";	
			}
            echo '<img class="houseicon" id="house_'.$row['HouseType'].'" src="img/house_'.$row['HouseType'].'.png">';
            echo '<div class="powerbar" style="height:'.intval(15*$RefEnergy).'px;"></div>';
            if ($delta > 0.1) {
                echo '<div class="powerbar red" style="height:'.intval(15*$HouseEnergy).'px;"></div>';
            	echo '<div>'.$HouseEnergy.' kWh ('.($HouseEnergy-$RefEnergy).' kWh more than before)</div>';
            }
            else if ($delta < -0.1) {
                echo '<div class="powerbar green" style="height:'.intval(15*$HouseEnergy).'px;"></div>';
            	echo '<div>'.$HouseEnergy.' kWh ('.($RefEnergy-$HouseEnergy).' kWh less than before)</div>';
            }
            else {
                echo '<div class="powerbar red" style="height:'.intval(15*$HouseEnergy).'px;"></div>';
            	echo '<div>'.$HouseEnergy.' kWh (no big change)</div>';
            }
        }
        else {
            echo '<img class="houseicon dim" src="img/house_'.$row['HouseType'].'_.png">';
            echo '<div>Waiting to commit</div>';
        }
        echo "</div>"; 
}

$hellPower = $hellPowerBase + $House_Round;
$StreetReference = round($StreetReference+$hellPowerBase,1);
$StreetEnergy = $StreetEnergy + $hellPower;
?>

<div class='col-xs-4 col-sm-3 top-buffer nopadding'>
Neighbours from Hell
    <img class="houseicon" id="house_1" src="img/house_1.png">
    <div class="powerbar" style="height:150px;"></div>
	<div class="powerbar red" style="height:<?php echo 15*$hellPower; ?>px;"></div>
	<div><?php echo $hellPower; ?> kWh (1 kWh more than before)</div>
</div> 
<?php
		if ($Street_Round == $minRound) {
			$go = 'go';
			$height = 150;
			$challenge = $StreetReference;
			$growBar =  "growBar($StreetEnergy,$challenge,$height)";


			$sql="SELECT idRound FROM Round WHERE Street_idStreet = $idStreet AND Street_Round = $Street_Round";
			$result = mysqli_query($db,$sql);
			if (mysqli_fetch_assoc($result)) {
				$sql="UPDATE Round SET result = $StreetEnergy, target = $challenge WHERE Street_idStreet = $idStreet AND Street_Round = $Street_Round";
			} else {
				$sql="INSERT INTO Round (Street_idStreet, Street_Round, result, target) VALUES ($idStreet,$Street_Round,$StreetEnergy,$challenge)";
			}
			mysqli_query($db,$sql);

		} else {
			$go = 'nogo';
			$growBar =  "";
		}
?>

<div class='col-xs-4 col-sm-3 top-buffer nopadding'>
	<table>
	<tr><td>
	<img class="housebutton" id="transformer" onload="<?php echo $growBar; ?>" src="img/transformer_static.gif">
	</td> <td class="bars">
	<div class="powerbar feeder" style="height:150px;"></div>
	</td> <td class="bars">
	<div class="powerbar feeder red" id="transformerBar" style="height:0px;"></div>
	</td> </tr> </table> 
</div>

<div id="tryAgain" class="tryAgain">
<?php
if ($Street_Round < 3) {
	$label = "Next day...";
	$action = "ApplianceChoice.php";
} else {
	$label = "See results";
	$action = "Results.php";
}
?>
	<form method="get" action="<?php echo $action; ?>">
	<input type="hidden" name="go" value="<?php echo $go; ?>">
	<input type="hidden" name="ht" value="<?php echo $_GET[ht]; ?>">
	<input type="hidden" name="hid" value="<?php echo $idHouse; ?>">
	<input type="hidden" name="sid" value="<?php echo $idStreet; ?>">
	<div id="tryAgainText"> </div> 
	<input type="submit" id="tryAgainBtn" class="btn-success tryAgainBtn" value="<?php echo $label; ?>" >
</form> 
</div> 

</div>
</div>
</body>
</html>
