<!DOCTYPE HTML>
<html lang="en">
<head>
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
	
	include("header.php"); 
	if ($House_Round > $minRound) {
		echo '<meta http-equiv=”refresh” content=”5" />';
	}
?>

</head>
<body>
<script>
function growBar(h,ref) {
    height = h.toString();
    var heightStr = height.concat("px");
    document.getElementById("transformerBar").style.height = heightStr;
	if (h > ref) {
		var delta = h- ref;
		var message = "Ouch, your street used ";
		message = message.concat(delta);
		message = message.concat("kWh too much.");
		
		document.getElementById("transformer").onload = '';
		document.getElementById("transformer").src = 'img/transformer_blowup.gif';
    	document.getElementById("tryAgainText").innerHTML = message;
		setTimeout(blackout, 4000)
	} else {
		var delta = ref - h;
		var message = "Well done. You have ";
		message = message.concat(delta);
		message = message.concat("kWh spare.");
		document.getElementById("transformer").onload = '';
    	document.getElementById("transformer").src = 'img/transformer_ok.gif';
    	document.getElementById("tryAgainText").innerHTML = message;
    	document.getElementById("tryAgainBtn").value = "Next day...";
		setTimeout(tryAgain, 2000)
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
		setTimeout(tryAgain, 2000)
	}
}

function tryAgain() {
	document.getElementById("tryAgain").style.display = 'block';
}
</script>





<?php
include("_nav_bar_neighbours.php");
echo "<h1>Results after round $Commited_Round</h1>";

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
            echo '<img class="houseicon" id="house_'.$row['HouseType'].'" src="img/house_'.$row['HouseType'].'.png">';
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
}


		$benchmark = intval(150*0.9);
		$barHeight = intval(150*$StreetEnergy/$StreetReference);
		if ($House_Round == $minRound) {
			$go = 'go';
			$growBar =  "growBar($barHeight,$benchmark)";
		} else {
			$go = 'nogo';
			$growBar =  "";
			echo "waiting $myRound  xx";
		   //  echo "	and $minRound";
		}
?>

<div class="container">
	<div class="row">
<div class='col-xs-4 col-sm-3 top-buffer nopadding'>
	<table>
	<tr><td>
	<img class="housebutton" id="transformer" onload="<?php echo $growBar; ?>" src="img/transformer_static.gif">
	</td> <td class="bars">
	<div class="powerbar feeder" style="height:<?php echo $benchmark;?>px;"></div>
	</td> <td class="bars">
	<div class="powerbar feeder red" id="transformerBar" style="height:0px;"></div>
	</td> </tr> </table> 
</div>

<div id="tryAgain" class="tryAgain">
<form method="get" action="ApplianceChoice.php">
	<input type="hidden" name="go" value="<?php echo $go; ?>">
	<input type="hidden" name="ht" value="<?php echo $_GET[ht]; ?>">
	<input type="hidden" name="hid" value="<?php echo $idHouse; ?>">
	<input type="hidden" name="sid" value="<?php echo $idStreet; ?>">
	<div id="tryAgainText"> </div> 
	<input type="submit" id="tryAgainBtn" class="btn-success tryAgainBtn" value="Try again" >
</form> 
</div> 

</div>
</div>
</body>
</html>
