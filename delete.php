<!DOCTYPE HTML>
<html lang="en">
<head>
<?php include("header.php"); ?>
<style>
</style>
</head>
<body>

<?php
include("_nav_bar_neighbours.php"); 
include 'db_neighbours.php';
$db = mysqli_connect($server,$dbUserName,$dbUserPass,$dbName);
?>

<div class="container">
<form method="get" action="commit.php">
<div class="row">
	<div class="col-xs-6 col-xs-push-1" style="background-color: transparent;">
<h3> This house has been deleted </h3>
<?php
	echo '<input type="hidden" value="'.$_GET[hid].'" name="hid">';
	echo '<input type="hidden" value="'.$_GET[ht].'" name="ht">';
	$sql="DELETE FROM House WHERE idHouse = $_GET[deleteid]";
	mysqli_query($db,$sql);
?>
<input class="btn-success" type="submit" value="Now, see what happens...">
</div> 
</div> 
</form> 

	</body>
</html>
