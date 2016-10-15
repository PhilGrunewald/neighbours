<!DOCTYPE HTML>
<html lang="en">
<head>
<?php include("header.php"); ?>
</head>
<body>
<?php include("_nav_bar_neighbours.php"); ?>

<div class="container">
 <div class="row">
  <div class="col-xs-12 col-sm-8 col-sm-push-2" style="background-color: transparent;">
   <img class="img-responsive center-block" src="img/house_banner.png" alt="Power Neighbours"/>
  </div>
 </div>

<div class="row">
  <div class="col-xs-12 col-sm-6 col-sm-push-3" style="background-color: transparent;">
</br>
	<h3>Play Neighbours</h3>
<p>Your neighbours need your help. Ever since the family from hell has moved in next door, things have gone bad. They use so much electricity that the transformer in the road no longer copes.</p>
<p>Early evenings are the worst. Between 6 and 7pm everyone seems to be using all their appliances. The guy from the Central Power Networks says:</br> "<i>If you don't reduce electricity use between 6 and 7, the transformer might fail and you will sit in the dark.</i>"</p>
<p>Be a good neighbour and help.</p>


<form class="form-inline" role="form" action="HouseChoice.php" method="post">
	<div class="form-group">
		<label for="name">Name of your street</label><br/>
<div class="roadsign">
			<input class="StreetName" id="StreetName" name="StreetName" type="text" placeholder="Street name">
</div>
		<div>
		<input hidden id="play" name="play" type="submit" value="Play" />
	</div>
	</div> 
	</div>
</div>
 <div class="row">
  <div class="col-xs-12 col-sm-push-3" style="background-color: transparent;">
<h2>Or join an existing street</h2>
<?php
	include 'db_neighbours.php';
	$db = mysqli_connect($server,$dbUserName,$dbUserPass,$dbName);
	$sqlq = "SELECT DISTINCT Street_idStreet, StreetName 
			 FROM House
			 JOIN Street
			 ON Street_idStreet = idStreet
			 WHERE Round <2";

	$sqlq = "SELECT DISTINCT StreetName
			 FROM Street
			 WHERE started >= CURDATE()
			 AND House_Round < 2";
	$result = mysqli_query($db,$sqlq);
    while($street = mysqli_fetch_assoc($result)) {
		echo '<button class="roadsign" onclick="pickStreet(this.value)" name="'.$street['idStreet'].'" type="submit" value="'.$street['StreetName'].'" />'.$street['StreetName'].'</button></br>';
	}
?>

</form>
<script>
function pickStreet(id) {
	document.getElementById("StreetName").value = id;
}
</script>
</div>
</div>
</div>
</body>
</html>
