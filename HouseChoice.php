<!DOCTYPE HTML>
<html lang="en">
<head>
<?php include("header.php"); ?>
</head>
<body>
<?php include("_nav_bar_neighbours.php"); ?>

<?php
	include 'db_neighbours.php';
	$db = mysqli_connect($server,$dbUserName,$dbUserPass,$dbName);
	if (mysqli_connect_errno()) {
	    print '<p class="alert alert-error">Error: ' . mysqli_connect_error() . '. Please email philipp.grunewald@ouce.ox.ac.uk about it.</p>';
		    exit();
		}
	else {
		$sn = $_GET[sn];
		$sql="SELECT idStreet 
			From Street 
			WHERE House_Round < 2
			AND StreetName = '$sn'";
		$result = mysqli_query($db,$sql);
		if (mysqli_num_rows($result) != 0)
			{
				// StreetName exists - will join this street
				$row = mysqli_fetch_assoc($result);
				$idStreet = $row['idStreet'];
			}
			else
			{
				// StreetName is new - create this street
				$sql="INSERT INTO Street (`StreetName`) VALUE ('$sn')";
				mysqli_query($db,$sql);
				$idStreet = mysqli_insert_id($db);
			}
		// $_GET[sid] = $idStreet;
	}
?>

<script type="text/javascript">
	function pickHouse(thisHouse) {
		var thisValue = thisHouse.value;
		var parameter = document.getElementById('ht');
		parameter.value = thisValue;
	}
</script> 

<form method="get" action="ApplianceChoice.php">
	<input type="hidden" id="ht"  name="ht">
	<input type="hidden" id="hid" name="hid" value="0">
	<input type="hidden" id="sid" name="sid" value="<?php echo $idStreet; ?>" >


<div class="container">
<div class="row">
	<div class='col-md-4 col-md-offset-4'>
</br>
	<h3>Choose your house</h3>
	</div>
</div> 
<div class="row">
<div class='col-xs-6 col-sm-4 col-md-3 top-buffer nopadding'>
            <button class="houseChoice" id='$HouseType' value='2' onClick='pickHouse(this)'><h3>Prime of life</h3> 
                <img class="housebutton" src="img/house_2.png">
<p>An elderly and very contented couple. So long as there is a hot cup of tea and some music - all is fine.</p>
            </button> 
        </div>
	<div class='col-xs-6 col-sm-4 col-md-3 top-buffer nopadding'>
            <button class="houseChoice" id='$HouseType' value='3' onClick='pickHouse(this)'><h3>Generation X</h3> 
                <img class="housebutton" src="img/house_3.png">
<p>Games, games, games, and all the gadgets. This house is full of technology - does that make it a smart home?</p>
            </button>
        </div>
	<div class='col-xs-6 col-sm-4 col-md-3 top-buffer nopadding'>
                                
			<button class="houseChoice" id='$HouseType' value='4' onClick='pickHouse(this)'>
<h3>Young family</h3> 
                <img class="housebutton" src="img/house_4.png">
<p>A young and caring family. The children are well looked after. Dinner time is a highlight of the day.</p>
            </button>
        </div>
	<div class='col-xs-6 col-sm-4 col-md-3 top-buffer nopadding'>
			<button class="houseChoice" id='$HouseType' value='5' onClick='pickHouse(this)'><h3>Student pad</h3> 
                <img class="housebutton" src="img/house_5.png">
<p>The party house. Work hard and play hard. These students have a lot of fun - and a lot of stuff.</p>
            </button>
        </div>
	<div class='col-xs-6 col-sm-4 col-md-3 top-buffer nopadding'>
			<div class="houseChoice" id='$HouseType' value='1'>
<h3>The Neighbours from Hell</h3> 
                <img class="housebutton" src="img/house_1.png">
<p>This family has everything, apart from a communal sense. You cannot choose to be these people - they are outside our control...</p>
            </div> 
        </div>
        </div>
</div>
</form>
<div class="row">
	<div class='col-md-4 col-md-offset-4'>
	</br>
	<div class="roadsign"><div class="StreetName"><?php echo $sn?></div></div>
	<p>To invite friends send them this link: </br>
	<?php 
	$StreetStr = urlencode($sn);
	echo "http://www.energy-use.org/neighbours/HouseChoice.php?sn=$StreetStr";
	?>
</p>
	</div>
</div> 

</body>
</html>
