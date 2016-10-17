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

<?php

$StreetReference = 0;
$StreetEnergy    = 0;

$sql="SELECT idHouse,HouseType From House WHERE Street_idStreet = $idStreet";
$result = mysqli_query($db,$sql);

while ($row = mysqli_fetch_assoc($result)) {
	$idStreetHouse = $row['idHouse'];
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
		$HouseEnergy = 0;
		while ($pt = mysqli_fetch_assoc($PowerTime)) {
			$HouseEnergy = $HouseEnergy + intval(intval($pt['Power'])*intval($pt['Minutes'])/60000);
			}
		if ($Round == 0) {
			echo "<div class='col-xs-12 col-sm-12 top-buffer nopadding'>";
			if ($row['idHouse'] == $idHouse) {
				echo "My House<br>";	
			}
        	echo '<img class="houseicon" id="house_'.$row['HouseType'].'" src="img/house_'.$row['HouseType'].'.png">';
        	echo '<div class="powerbar" style="height:'.intval(15*$HouseEnergy).'px;"></div>';
		} else {
			$sql="SELECT result,target FROM Round WHERE Street_idStreet = $idStreet AND Street_Round = $Round";
			$round = mysqli_query($db,$sql);
			$r = mysqli_fetch_assoc($round);
			if ($r[result] >= $r[target]) {
					$HouseEnergy = 0;
				} 
            echo '<div class="powerbar green" style="height:'.intval(15*$HouseEnergy).'px;"></div>';
        	// echo '<div>'.$HouseEnergy.' kWh</div>';
		}
		if ($Round == 3) {
			echo "</div>"; 
		}
		$StreetEnergy = $StreetEnergy + $HouseEnergy;
	}
}


$sql="SELECT result,target,Street_Round FROM Round WHERE Street_idStreet = $idStreet ORDER BY Street_Round";
$result = mysqli_query($db,$sql);
$PowerPoints = 0;
while ($row = mysqli_fetch_assoc($result)) {
	$used = $row[result];
	$target = $row[target];
	echo "Round: $row[Street_Round] </br>";
	echo "Result: $used </br>";
	echo "Target: $target </br>";
	if ($used < $target) {
		$PowerPoints = $PowerPoints + intval($used/$target*100);
	}
}
echo "Your street scored $PowerPoints Power Points!";
$sql="UPDATE Street SET Power_Points = $PowerPoints WHERE idStreet = $idStreet";
mysqli_query($db,$sql);

$sql="SELECT StreetName,Power_Points FROM Street ORDER BY Power_Points DESC LIMIT 10";
$result = mysqli_query($db,$sql);
while ($row = mysqli_fetch_assoc($result)) {
	$Name = $row[StreetName];
	$points = $row[Power_Points];
	echo "$Name has $points<br>";
}
?>
</div>
</div>
</div>
</body>
</html>
