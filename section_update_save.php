<?php
require("include/conn.php");

$vsection = $_POST['txtsection']; // New name from text box
$vold = $_POST['txtoldid'];       // Original name from hidden field
$vyear = $_POST['txtyearlevel'];

// 1. Validation: Check if the NEW section name is already taken by a DIFFERENT record
$check = $conn->query("SELECT * FROM tb_section WHERE section = '$vsection' AND section != '$vold'");
if ($check->num_rows > 0) {
    echo "<script>alert('Error: Section name $vsection is already in use!'); window.history.back();</script>";
    exit();
}

// 2. Perform the update using $vold in the WHERE clause
$sql = "UPDATE tb_section SET 
        section='$vsection', 
        year_level='$vyear' 
        WHERE section='$vold'";

if ($conn->query($sql) === TRUE) {
    echo "<script>alert('Section Updated.'); window.location.href='section.php';</script>";
} else {
    echo "Error: " . $conn->error;
}
?>