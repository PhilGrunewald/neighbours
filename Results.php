<!DOCTYPE HTML>
<html lang="en">
<head>
<?php
	include("header.php"); 
	include 'db_neighbours.php';
	$db = mysqli_connect($server,$dbUserName,$dbUserPass,$dbName);
	$idHouse  = $_GET['hid'];
	$idStreet  = $_GET['sid'];
?>

</head>
<body>
<?php include("_nav_bar_neighbours.php"); ?>

<div class="container">
<div class="row">
<div class="col-xs-10 col-xs-push-1" style="background-color: transparent;">
<h1>How did you do?</h1>
</div> <!-- col -->
</div> <!-- row -->
<?php

$StreetReference = 0;
$StreetEnergy    = 0;

$sql="SELECT idHouse,HouseType From House WHERE Street_idStreet = $idStreet";
$result = mysqli_query($db,$sql);

while ($row = mysqli_fetch_assoc($result)) {
	$idStreetHouse = $row['idHouse'];
	$HouseEnergySum = 0;
	for ($Round = 0; $Round <= 3; $Round++) {
		$sql="SELECT Power, Minutes
		  FROM Choices 
		  Join Appliances
		  On idAppliances = Appliance_idAppliance
		  WHERE Street_idStreet = $idStreet 
		  AND House_idHouse = $idStreetHouse
		  AND House_Round = $Round
		  ";
		$PowerTime = mysqli_query($db,$sql);
		$HouseEnergy    = 0;
		while ($pt = mysqli_fetch_assoc($PowerTime)) {
				$HouseEnergy = $HouseEnergy + (intval($pt['Power'])*intval($pt['Minutes'])/60000);
			}
		if ($Round == 0) {
			echo "<div class='row'>";
			echo "<div class='col-xs-6 col-xs-push-2 col-sm-4 col-sm-push-2 top-buffer nopadding'>";
			if ($row['idHouse'] == $idHouse) {
				echo "My House<br>";	
			}
        	echo '<img class="houseicon" id="house_'.$row['HouseType'].'" src="img/house_'.$row['HouseType'].'.png">';
			echo "</div><div class='col-xs-6 col-sm-2 top-buffer nopadding'>";
        	echo '<div class="powerbar" style="height:'.intval(15*$HouseEnergy).'px;"></div>_';
			$HouseRef = $HouseEnergy;
		} else {
			$sql="SELECT result,target FROM Round WHERE Street_idStreet = $idStreet AND Street_Round = $Round";
			$round = mysqli_query($db,$sql);
			$r = mysqli_fetch_assoc($round);
			if ($r[result] > $r[target]) {
            	echo '<div class="powerbar faint" style="height:'.intval(15*$HouseEnergy).'px;"></div>_';
				$HouseEnergy = 0;
				if ($Round == 1) {$r1="faint";}
				if ($Round == 2) {$r2="faint";}
				if ($Round == 3) {$r3="faint";}
			} else {
				if ($Round == 1) {$r1="green";}
				if ($Round == 2) {$r2="green";}
				if ($Round == 3) {$r3="green";}
            	echo '<div class="powerbar green" style="height:'.intval(15*$HouseEnergy).'px;"></div>_';
			}
			
			$HouseEnergySum = $HouseEnergySum + $HouseEnergy;
		}
		if ($Round == 3) {
			$supplyRate = intval(100 * $HouseEnergySum / (3*$HouseRef));
			echo "</div><div class='col-xs-6 col-sm-3 top-buffer nopadding'>";
			echo "Needs met</br><h3> $supplyRate % </h3>";
			echo "</div>"; 
			echo "</div><hr>"; 
		}
		$StreetEnergy = $StreetEnergy + $HouseEnergy;
	}
}
?>
<div class='row'>
  <div class='col-xs-6 col-xs-push-2 col-sm-4 col-sm-push-2 top-buffer nopadding'>
Neighbours from Hell<br>
	  <img class="houseicon" id="house_1" src="img/house_1.png">
  </div><div class='col-xs-6 col-sm-2 top-buffer nopadding'>
  <div class="powerbar" style="height:150px;"></div>_<div class="powerbar <?php echo $r1; ?>" style="height:165px;"></div>_<div class="powerbar <?php echo $r2; ?>" style="height:180px;"></div>_<div class="powerbar <?php echo $r3; ?>" style="height:195px;"></div>_</div><div class='col-xs-6 col-sm-3 top-buffer nopadding'>
  </div> <!-- col -->
</div> <!-- row -->
<hr>

<div class="row">
<div class="col-xs-8 col-xs-push-2" style="background-color: transparent;">
<h3>Your street</h3>
<table>
<?php

$sql="SELECT result,target,Street_Round FROM Round WHERE Street_idStreet = $idStreet ORDER BY Street_Round";
$result = mysqli_query($db,$sql);
$PowerPoints = 0;
$days = array('Wednesday','Thursday','Friday');
while ($row = mysqli_fetch_assoc($result)) {
	echo '<tr><td>';
	$used = $row[result];
	$target = $row[target];
	$Round = $row[Street_Round];
	$points = intval($used/$target*100);
	$day = $days[intval($Round-1)];
	if ($used < $target) {
		$PowerPoints = $PowerPoints + $points;
		echo "$day </td><td> $points power points</br>";
	} else {
		echo "$day </td><td> Blackout</br>";
	}
	echo '</td></tr>';
}
echo "</table>";
echo "<b>Your street scored $PowerPoints Power Points!</b></br><hr>";
$sql="UPDATE Street SET Power_Points = $PowerPoints WHERE idStreet = $idStreet";
mysqli_query($db,$sql);

$sql="SELECT StreetName,Power_Points FROM Street ORDER BY Power_Points DESC LIMIT 10";
$result = mysqli_query($db,$sql);
$rank = 1;
echo "<h3>Power Neighbour Top 10</h3><table class='ranking'>";
echo "<tr class='line'><td>Rank</td><td class='centerCell'>Street</td><td>Power points</td></tr>";
while ($row = mysqli_fetch_assoc($result)) {
	$Name = $row[StreetName];
	$points = $row[Power_Points];
	echo "<tr><td class='centerCell'>$rank</td><td><div class='roadsign'><div class='StreetName'> $Name</div></div></td><td class='centerCell'><div class='points'>$points</div></td></tr>";
	$rank = $rank +1;
}
?>
</div>
</div>
</div>
</body>
</html>
