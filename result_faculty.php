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
Section
</th>
<th>
Year Level
</th>
<th>
School Year
</th>
<th>
Semester
</th>
</tr>
<?php
$vfaculty_name="";
$vcourse_code="";
$vcourse_description="";
$vsection="";
$vyear_level="";
$vschool_year="";
$vsemester="";
$vsearch="";
$vsearch=$_POST['txsfaculty'];

$sql1 = "SELECT * FROM tb_faculty where id_no='$vsearch'";
$result1 = $conn->query($sql1);
if($result1->num_rows > 0) 
{
	while($row1 = $result1->fetch_assoc())
        {
        	$vfaculty_name=$row1['last_name'].", ".$row1['first_name'];
        }
}

echo "Assignments of ".$vfaculty_name;

$sql = "SELECT * FROM tb_faculty_assignments where faculty_id='$vsearch' order by assignment_index";
$result = $conn->query($sql);
if($result->num_rows > 0) 
{
	while($row = $result->fetch_assoc())
        {
        	$vcourse_index=$row['course_index'];
		$vsection_index=$row['section_index'];
		$vschool_year=$row['school_year'];
		$vsemester=$row['semester'];

		$sql1 = "SELECT * FROM tb_courses where course_index='$vcourse_index'";
		$result1 = $conn->query($sql1);
		if($result1->num_rows > 0) 
		{
			while($row1 = $result1->fetch_assoc())
		        {
		        	$vcourse_code=$row1['course_code'];
				$vcourse_description=$row1['course_description'];
		        }
		}

		$sql1 = "SELECT * FROM tb_section where section_index='$vsection_index'";
		$result1 = $conn->query($sql1);
		if($result1->num_rows > 0) 
		{
			while($row1 = $result1->fetch_assoc())
		        {
		        	$vsection=$row1['section'];
				$vyear_level=$row1['year_level'];
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
		echo $vsection;
		?>
		</td>
		<td>
		<?php
		echo $vyear_level;
		?>
		</td>
		<td>
		<?php
		echo $vschool_year;
		?>
		</td>
		<td>
		<?php
		echo $vsemester;
		?>
		</td>
		</tr>
		<?php	
	}
}
?>
</table>
<br>
<button type="reset"  onClick="window.location.href='select_faculty.php'">&nbsp;&nbsp;&nbsp;&nbsp;Back&nbsp;&nbsp;&nbsp;&nbsp; </button>

</body>
</html>
