<?php
require("include/conn.php");
$vnum = $_POST['txtstudentnumber'];
$vlast = $_POST['txtlastname'];
$vfirst = $_POST['txtfirstname'];
// ADD THESE TWO LINES:
$vmiddle = $_POST['txtmiddlename']; 
$vgender = $_POST['txtgender'];

// Validation: Duplicate Check
$check = $conn->query("SELECT * FROM tb_students WHERE student_number = '$vnum'");
if ($check->num_rows > 0) {
    echo "<script>alert('Error: Student Number $vnum already exists!'); window.history.back();</script>";
    exit();
}

// Get the next index number
$res = $conn->query("SELECT MAX(`index`) as maxidx FROM tb_students");
$row = $res->fetch_assoc();
$vindex = $row['maxidx'] + 1;

// UPDATE THIS SQL: Add middle_name and gender to the columns and values
$sql = "INSERT INTO tb_students (`index`, student_number, last_name, first_name, middle_name, gender) 
        VALUES ('$vindex', '$vnum', '$vlast', '$vfirst', '$vmiddle', '$vgender')";

if ($conn->query($sql) === TRUE) {
    echo "<script>alert('Record Saved.'); window.location.href='students.php';</script>";
}
?>