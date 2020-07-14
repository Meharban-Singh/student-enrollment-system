<?php
	$conn = mysql_connect("localhost", "root", "root") or die(mysql_error());
	mysql_select_db("meharban", $conn);

	if(isset($_GET["id"]) && isset($_GET["name"]) && isset($_GET["phno"]) && isset($_GET["gender"])) {
		$q = mysql_query("UPDATE editabletable SET Name = '$_GET[name]', PhoneNumber = '$_GET[phno]', Gender = '$_GET[gender]' WHERE SerialNumber = $_GET[id]") or die("Error Updating record! : " . mysql_error());
		
		if($q){ 
			?>

			<script>window.location.href = 'editabletable.php';</script>

			<?php
		}
	
	}
?>
