<?php
require("include/conn.php");
$vid = $_POST['txtidno'];

// Check if faculty has any assigned loads
$check_load = $conn->query("SELECT * FROM tb_faculty_loading WHERE id_no = '$vid'");

if ($check_load->num_rows > 0) {
    echo "<script>alert('Cannot Delete: This faculty member still has assigned teaching loads! Remove the loads first.'); window.location.href='faculty.php';</script>";
    exit();
}

$sql = "DELETE FROM tb_faculty WHERE id_no='$vid'";
if ($conn->query($sql) === TRUE) {
    echo "<script>alert('Faculty Member Deleted Successfully.'); window.location.href='faculty.php';</script>";
}
?>