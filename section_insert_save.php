<?php
require("include/conn.php");

// 1. Get only the relevant POST data
$vsection = $_POST['txtsection'];
$vyear = $_POST['txtyearlevel'];

// 2. Remove the $vindex variable and the $check query 
// because course_index no longer exists in tb_section.

// 3. Simplified INSERT query
$sql = "INSERT INTO tb_section (section, year_level) VALUES ('$vsection', '$vyear')";

if ($conn->query($sql) === TRUE) {
    echo "<script>alert('Section Added Successfully.'); window.location.href='section.php';</script>";
} else {
    // Adding an error display just in case something goes wrong
    echo "Error: " . $conn->error;
}
?>