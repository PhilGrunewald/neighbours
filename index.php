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
<!-- 
  <div class="col-xs-12 col-sm-6 col-sm-push-3" style="background-color: transparent;">
 -->
  <div class="col-xs-12 col-sm-5 col-sm-push-1" style="background-color: transparent;">
</br>
<p>Your neighbours need your help. Ever since the family from hell has moved in next door, things have gone bad. They use so much electricity that the transformer in the road no longer copes.</p>
<p>Early evenings are the worst. Between 6 and 7pm everyone seems to be using all their appliances. The guy from the Central Power Networks says:</br>

</p>

<div class="col-xs-12">
	<img src='img/house_dno.png' class="img-responsive">
</div> 


<p>Be a good neighbour and help.</p>

</div>
  <div class="col-xs-12 col-sm-5 col-sm-push-1" style="background-color: transparent;">
	<form id="StreetForm" class="form-inline" role="form" action="HouseChoice.php" method="get">
		<div class="form-group">
			<br>
			<h3>Give your street a name</h3>
			<div class="roadsign">
			<input class="StreetName" id="StreetName" name="sn" type="text" placeholder="Street name">
			</div>
		<div>
		</div>
	</div> 
</form>
<?php
	include 'db_neighbours.php';
	$db = mysqli_connect($server,$dbUserName,$dbUserPass,$dbName);

	$sqlq = "SELECT StreetName
			 FROM Street
			 WHERE House_Round < 2
			 ORDER BY started DESC";
			 // WHERE started >= CURDATE()
	$result = mysqli_query($db,$sqlq);
	if (mysqli_num_rows($result) > 0) {
		echo "<h3>Or join an existing street</h3>";
	}
    while($street = mysqli_fetch_assoc($result)) {
		$sn = $street[StreetName];
		echo '<div class="roadsign"><div class="StreetName" onclick="pickStreet(\''.$sn.'\')" type="submit" value="'.$sn.'" />'.$sn.'</div></div></br>';
	}
?>

</div>
<script>
function pickStreet(id) {
	document.getElementById("StreetName").value = id;
	document.getElementById("StreetForm").submit();
}
</script>
</div>
</div>
</div>
</body>
</html>
