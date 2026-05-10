<html>
<body>
 
<?php
$servername = "localhost";
$database = "db_enrollment";
$username = "root";
$password = "";
 
$conn = mysqli_connect($servername, $username, $password, $database);
 
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
 
<form name="formsearch" action="students.php" method="post">
    <label>Input Query: </label>
    <input type="text" name="txtsearch" id="txtsearch" value="" />
    <input type="submit" value="      Search      " />
    <button type="button" onClick="window.location.href='students.php'">    Display All    </button>
</form>
 
<table cellspacing=2 border=1>
    <tr>
        <th>Student Number</th>
        <th>Last Name</th>
        <th>First Name</th>
        <th>Middle Name</th>
        <th>Gender</th>
        <th bgcolor="#FFFF00">Action</th> 
    </tr>

<?php
$vsearch = isset($_POST['txtsearch']) ? $_POST['txtsearch'] : "";
 
if($vsearch != "") {
    $sql = "SELECT * FROM tb_students where gender='$vsearch' || last_name='$vsearch' order by `index`";
} else {
    $sql = "SELECT * FROM tb_students order by `index`";
}
 
$result = $conn->query($sql);
if($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $vstudent_number = $row['student_number'];
        $vlast_name = $row['last_name'];
        $vfirst_name = $row['first_name'];
        $vmiddle_name = $row['middle_name'];
        $vgender = $row['gender'];
?>
    <tr>
        <td><?php echo $vstudent_number; ?></td>
        <td><?php echo $vlast_name; ?></td>
        <td><?php echo $vfirst_name; ?></td>
        <td><?php echo $vmiddle_name; ?></td>
        <td><?php echo $vgender; ?></td>
        <td align="center">
            <a href="student_update.php?vid=<?php echo $vstudent_number; ?>">UPDATE</a> | 
            <a href="student_delete.php?vid=<?php echo $vstudent_number; ?>">DELETE</a>
        </td>
    </tr>
<?php   
    }
}
?>
</table>

<br>
<button type="button" onClick="window.location.href='student_insert.php'">&nbsp;&nbsp;&nbsp;&nbsp;ADD NEW&nbsp;&nbsp;&nbsp;&nbsp;</button>
&nbsp;
<button type="button" onClick="window.location.href='rep_students.php'">&nbsp;&nbsp;&nbsp;&nbsp;PRINT&nbsp;&nbsp;&nbsp;&nbsp;</button>
&nbsp;
<button type="button" onClick="window.location.href='index.php'">&nbsp;&nbsp;&nbsp;&nbsp;BACK&nbsp;&nbsp;&nbsp;&nbsp;</button>
 
</body>
</html>