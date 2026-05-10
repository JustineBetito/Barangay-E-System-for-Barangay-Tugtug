<?php
require("include/conn.php");

$vnum = $_POST['txtstudentnumber'];
$vold = $_POST['txtoldid']; // The original ID from the hidden field
$vlast = $_POST['txtlastname'];
$vfirst = $_POST['txtfirstname'];
$vmiddle = $_POST['txtmiddlename'];
$vgender = $_POST['txtgender']; // Capture the gender from the form

// Validation: Check if the new ID belongs to someone else
$check = $conn->query("SELECT * FROM tb_students WHERE student_number = '$vnum' AND student_number != '$vold'");
if ($check->num_rows > 0) {
    echo "<script>alert('Error: Student Number $vnum is already taken!'); window.history.back();</script>";
    exit();
}

// Perform the Update including Middle Name and Gender
$sql = "UPDATE tb_students SET 
        student_number='$vnum', 
        last_name='$vlast', 
        first_name='$vfirst', 
        middle_name='$vmiddle', 
        gender='$vgender' 
        WHERE student_number='$vold'";

if ($conn->query($sql) === TRUE) {
    echo "<script>alert('Record Updated.'); window.location.href='students.php';</script>";
} else {
    echo "Error updating record: " . $conn->error;
}
?>