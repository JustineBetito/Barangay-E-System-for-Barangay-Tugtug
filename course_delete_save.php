<?php
require("include/conn.php");
$vcode = $_POST['txtcoursecode'];

// 1. Get the course_index first
$course_res = $conn->query("SELECT course_index FROM tb_courses WHERE course_code='$vcode'");
$course_data = $course_res->fetch_assoc();
$vindex = $course_data['course_index'];

// 2. Check Classlist
$check_classlist = $conn->query("SELECT * FROM tb_classlist WHERE course_index = '$vindex'");

// 3. Check Faculty Loading
$check_loading = $conn->query("SELECT * FROM tb_faculty_loading WHERE course_index = '$vindex'");

if ($check_classlist->num_rows > 0) {
    echo "<script>alert('Cannot Delete: This course is currently part of a Student Class List!'); window.location.href='courses.php';</script>";
    exit();
}

if ($check_loading->num_rows > 0) {
    echo "<script>alert('Cannot Delete: This course is currently assigned to a Faculty Member\'s Load!'); window.location.href='courses.php';</script>";
    exit();
}

// If both checks pass, proceed with deletion
$sql = "DELETE FROM tb_courses WHERE course_code='$vcode'";
if ($conn->query($sql) === TRUE) {
    echo "<script>alert('Course Deleted.'); window.location.href='courses.php';</script>";
}
?>