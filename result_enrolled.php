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
<table cellspacing=2 border=1>
<tr>
<th>
Course Code
</th>
<th>
Course Description
</th>
<th>
Units
</th>
</tr>
<?php
$vstudent_number="";
$vstudentname="";
$vcourse_code="";
$vcourse_description="";
$vunits="";
$vsearch="";
$vsearch=$_POST['txtstudent'];

$sql1 = "SELECT * FROM tb_students where `index`='$vsearch' order by `index`";
$result1 = $conn->query($sql1);
if($result1->num_rows > 0) 
{
	while($row1 = $result1->fetch_assoc())
        {
        	$vstudent_number=$row1['student_number'];
		$vstudentname=$row1['last_name'].", ".$row1['first_name'];
        }
}

echo "Subjects Enrolled by ".$vstudentname;

$sql = "SELECT * FROM courses_enrolled where student_index='$vsearch' order by coursesenrolled_index";
$result = $conn->query($sql);
if($result->num_rows > 0) 
{
	while($row = $result->fetch_assoc())
        {
        	$vcourseindex=$row['course_index'];

		$sql1 = "SELECT * FROM tb_courses where course_index='$vcourseindex' order by course_index";
		$result1 = $conn->query($sql1);
		if($result1->num_rows > 0) 
		{
			while($row1 = $result1->fetch_assoc())
		        {
		        	$vcourse_code=$row1['course_code'];
				$vcourse_description=$row1['course_description'];
				$vunits=$row1['units'];
		        }
		}
		?>
		<tr>
		<td>
		<?php
		echo $vcourse_code;
		?>
		</td>
		<td>
		<?php
		echo $vcourse_description;
		?>
		</td>
		<td>
		<?php
		echo $vunits;
		?>
		</td>
		</tr>
		<?php	
	}
}
?>
</table>
<br>
<button type="reset"  onClick="window.location.href='select.php'">&nbsp;&nbsp;&nbsp;&nbsp;Back&nbsp;&nbsp;&nbsp;&nbsp; </button>

</body>
</html>
