<?php
require("include/conn.php");
$vsection = $_POST['txtsection']; // Section Name

// 1. Get the section_index first
$sec_res = $conn->query("SELECT section_index FROM tb_section WHERE section='$vsection'");
$sec_data = $sec_res->fetch_assoc();
$vsec_index = $sec_data['section_index'];

// 2. Check Classlist
$check_classlist = $conn->query("SELECT * FROM tb_classlist WHERE section_index = '$vsec_index'");

// 3. Check Faculty Loading
$check_loading = $conn->query("SELECT * FROM tb_faculty_loading WHERE section_index = '$vsec_index'");

if ($check_classlist->num_rows > 0) {
    echo "<script>alert('Cannot Delete: This section has an active Class List!'); window.location.href='section.php';</script>";
    exit();
}

if ($check_loading->num_rows > 0) {
    echo "<script>alert('Cannot Delete: This section is currently assigned to a Faculty Member!'); window.location.href='section.php';</script>";
    exit();
}

// If both checks pass, proceed with deletion
$sql = "DELETE FROM tb_section WHERE section='$vsection'";
if ($conn->query($sql) === TRUE) {
    echo "<script>alert('Section Deleted Successfully.'); window.location.href='section.php';</script>";
}
?>