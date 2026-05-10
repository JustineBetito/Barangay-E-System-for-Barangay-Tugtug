<?php
require("include/conn.php");
$vcode = $_POST['txtcoursecode'];
$vdesc = $_POST['txtdescription'];
$vunits = $_POST['txtunits'];

$check = $conn->query("SELECT * FROM tb_courses WHERE course_code = '$vcode'");
if ($check->num_rows > 0) {
    echo "<script>alert('Error: Course Code already exists!'); window.history.back();</script>";
    exit();
}

$sql = "INSERT INTO tb_courses (course_code, course_description, units) VALUES ('$vcode', '$vdesc', '$vunits')";
if ($conn->query($sql) === TRUE) {
    echo "<script>alert('Course Saved.'); window.location.href='courses.php';</script>";
}
?>