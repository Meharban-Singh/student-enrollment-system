<!-- Connecting to Database -->
<?php
	$conn = mysql_connect("localhost", "root", "root") or die(mysql_error());
	mysql_select_db("meharban", $conn);
?>



<!DOCTYPE html>
<html>
<head>
	<title>Editable Table</title>

	<style>
		table, tr, td, th {
			border: 2px black solid;
			border-collapse: collapse;
			font-size: 20px;
			padding: 5px;
			text-align: center;
		}

		table {
			margin : auto;
		}

		thead > tr > th {
			background-color : lightgray; 
		}

		input {
			text-align: center;
		}

		input[type = "button"], input[type = "submit"] {
			font-size: 20px;
			cursor: pointer;
			background-color: white;
			border: 2px dashed black;
		}
		
		input:disabled {
			cursor:not-allowed;
		}

		input[type = "button"]:hover:enabled, input[type = "submit"]:hover:enabled {
			border: 2px dashed red;
			color: red;
		}

		input[type = "text"], input[type = "number"], select {
			font-size: 20px;
		}

		div>input {
			font-size: 25px;
		}

		.editable {
			animation: blinker 1s linear infinite;
		}

		@keyframes blinker {  
  		50% { opacity: 0; }
		}

	</style>

	<script src = "jquery-3.2.1.min.js"></script>

	<script>
		$(document).ready(function() {
			var newSer = parseInt($("tbody>tr:nth-last-child(1)").attr('id')) + 1;
			document.getElementById('newSerial').value = newSer;
			
			$(".edt").click(function() {
				$id = "#" + $(this).attr('name').split(":")[1];
				$(this).siblings()[0].disabled = false;
				$(this).attr('disabled', true);
				$($id + " td").not($id + '+td').prop('contenteditable',true);
				$($id + " td").not($id + '+td').addClass("editable");
			});
			
			$(".sv").click(function() {
				$rowId = "#" + $(this).attr('name').split(":")[1];
				$(this).siblings()[0].disabled = false;
				$(this).attr('disabled', true);
				$($id + " td").not($id + '+td').prop('contenteditable',false);
				$($id + " td").not($id + '+td').removeClass("editable");
				
				var rowId3 = $rowId.substring(1);
				var name = $.trim($(this).parent().parent().children()[1].innerHTML);
				var phno = $.trim($(this).parent().parent().children()[2].innerHTML);
				var gender = $.trim($(this).parent().parent().children()[3].innerHTML);
				
				window.location.href = 'saveRecord.php?id=' + rowId3 + '&name=' + name + '&phno=' + phno + '&gender=' + gender;
	
			});
			
		});

		function delRecord(tempVar) {
			var rowId = tempVar.substring(7);
			
			if(confirm("Do you want to delete record : " + rowId + "?")) {
				window.location.href = 'deleteRecord.php?id=' + rowId;
				$("#" + rowId).remove();
			}
		}
	</script>

		
	<!-- FILTER RECORDS -->
	<script>
		$(document).ready(function(){
  		$("#filterRecords").on("keyup", function() {
    		var value = $(this).val().toLowerCase();
    		$("tbody tr").filter(function() {
					$(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
				});
			});
		});
	</script>
	
	
	<script>

	</script>
</head>
<body>
  
	<h3>
		<input id = "filterRecords" style = "font-size : 20px; text-align : left;margin-left: 400px; width : 40%;" type = "search" placeholder = "Filter Records" autofocus />
	</h3>
		
	<table>
		<thead>
			<tr>
				<th>SerialNumber</th>
				<th width = "350px">Name</th>
				<th width = "230px">PhoneNumber</th>
				<th width = "150px">Gender</th>
				<th width = "150px">EditRecord</th>
				<th width = "150px">DeleteRecord</th>
			</tr>
		</thead>
			
		<tbody>
				
		<!-- FETCH DATA IN FORM OF TABLE -->
		<?php
			$query = mysql_query("SELECT * from editabletable ORDER BY SerialNumber") or die("Unable to execute query : " . mysql_error());
			$rows = mysql_num_rows($query);
			
			if($rows > 0) {
				while($record = mysql_fetch_assoc($query))
					echo "<tr id = $record[SerialNumber]> 
									<th class = 'srno'>
										$record[SerialNumber]
									</th> 
									
									<td class = 'nm'>
										$record[Name]
									</td> 
									
									<td class = 'phno'>
										$record[PhoneNumber]
									</td> 
									
									<td class = 'gen'>
										$record[Gender]
									</td> 
									
									<th>
										<input class = 'edt' type = 'button' value = 'Edit' name = 'editBtn:$record[SerialNumber] '/>&nbsp;&nbsp;
										<input class = 'sv' type = 'button' value = 'Save' name = 'saveBtn:$record[SerialNumber]' disabled />
									</th> 
									
									<th>
										<input class = 'del' type = 'button' value = 'Delete' name = 'delBtn:$record[SerialNumber]' onclick = 'delRecord(this.name)' />
									</th> 
								
								</tr>";
			}
			
		?>
		
		</tbody>
			
		<tr><form method = "POST">
			<th><input type="number" id = "newSerial" name = "newSerial2" style="width:80px;" required max = "999" min = "1"/></th>
			<th width = "300px"><input type="text" name="insertName" required /></th>
			<th><input style = "width :200px;" type="text" name="insertPhoneNumber" minlength = "8" maxlength="16" required /></th>
			<th width = "100px">
				<select name = "insertGender">
					<option value = "Male">Male</option>
					<option value = "Female">Female</option>
					<option value = "Others">Others</option>
				</select>
			</th>
			<th colspan = "2" width = "100px"><input type="submit" name="add" value = "Add record" /></th>
		</form></tr>

		<!-- INSERT RECORD -->
		<?php
			if(isset($_POST['add'])) {
				$srnum = $_POST['newSerial2'];
				$insnm = $_POST['insertName'];
				$insphno = checkPhoneNumber($_POST['insertPhoneNumber']);
				$insgen = $_POST['insertGender'];

				$insertStuff = mysql_query("INSERT INTO editabletable VALUES('$srnum','$insnm', '$insphno', '$insgen')");

				if($insertStuff) {

			?>

			<script>window.location.href = "editabletable.php";</script>

			<?php 

				} else 
						die("ERROR!"); 
				}

				function checkPhoneNumber($tempVar) {
					if(is_numeric($tempVar))
						return $tempVar;
					else
						die("Invalid Phone Number!"); 
				}
			?>

	</table>

</body>
</html>