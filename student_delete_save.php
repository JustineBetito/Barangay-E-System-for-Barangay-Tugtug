<?php
require("include/conn.php");
$vsection = $_POST['txtsection']; // This is the section name

// 1. Get the section_index first
$sec_res = $conn->query("SELECT section_index FROM tb_section WHERE section='$vsection'");
$sec_data = $sec_res->fetch_assoc();
$vsec_index = $sec_data['section_index'];

// 2. Check if this index is used in tb_classlist
$check_classlist = $conn->query("SELECT * FROM tb_classlist WHERE section_index = '$vsec_index'");

if ($check_classlist->num_rows > 0) {
    echo "<script>alert('Cannot Delete: This section has an active Class List!'); window.location.href='section.php';</script>";
    exit();
}

$sql = "DELETE FROM tb_section WHERE section='$vsection'";
if ($conn->query($sql) === TRUE) {
    echo "<script>alert('Section Deleted Successfully.'); window.location.href='section.php';</script>";
}
?>