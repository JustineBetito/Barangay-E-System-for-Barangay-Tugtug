<?php
require("include/conn.php");
$vcode = $_POST['txtcoursecode'];
$vold = $_POST['txtoldid'];
$vdesc = $_POST['txtdescription'];
$vunits = $_POST['txtunits'];

$check = $conn->query("SELECT * FROM tb_courses WHERE course_code = '$vcode' AND course_code != '$vold'");
if ($check->num_rows > 0) {
    echo "<script>alert('Error: Course Code already assigned to another course!'); window.history.back();</script>";
    exit();
}

$sql = "UPDATE tb_courses SET course_code='$vcode', course_description='$vdesc', units='$vunits' WHERE course_code='$vold'";
if ($conn->query($sql) === TRUE) {
    echo "<script>alert('Course Updated.'); window.location.href='courses.php';</script>";
}
?>