<?php
	$conn = mysql_connect("localhost", "root", "root") or die(mysql_error());
	mysql_select_db("meharban", $conn);
?>

<?php
	if(isset($_GET["id"])) {
		$q = mysql_query("DELETE from editabletable where SerialNumber = $_GET[id]") or die("Error deleting record! : " . mysql_error());

		if($q){ 
			?>

			<script>window.location.href = 'editabletable.php';</script>

			<?php
		}

	}
?>