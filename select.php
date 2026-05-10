<html>
<body>

<?php
$servername = "localhost";
$database = "db_enrollment";
$username = "root";
$password = "";

// Create connection

$conn = mysqli_connect($servername, $username, $password, $database);


// Check connection

if ($conn->connect_error) {
die("Connection failed: " . $conn->connect_error);
}
?>
<form name="form1" action="result_enrolled.php" method="post">
<label>
Select Student:
</label>

<select name="txtstudent" id="txtstudent" onchange="form.submit()">

<option value="">Select a Student</option>
<?php
$sql1 = "SELECT * FROM tb_students order by `index`";
$result1 = $conn->query($sql1);
if($result1->num_rows > 0) 
{
	while($row1 = $result1->fetch_assoc())
	{
?>
		<option value="<?php echo $row1['index']; ?>"><?php echo $row1['last_name'].", ".$row1['first_name']; ?></option>
<?php
	}
}
?>

</select>

</form>
<br>
<button type="reset"  onClick="window.location.href='index.php'">&nbsp;&nbsp;&nbsp;&nbsp;Back&nbsp;&nbsp;&nbsp;&nbsp; </button>

</body>
</html>
