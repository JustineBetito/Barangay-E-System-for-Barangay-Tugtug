<?php
require("include/conn.php");

$id = $_GET['id'];
$faculty = $_GET['faculty'];

$sql = "DELETE FROM tb_faculty_loading WHERE load_id = '$id'";

if($conn->query($sql)) {
    header("Location: faculty_load_manage.php?sel_faculty=$faculty");
} else {
    echo "Error deleting load: " . $conn->error;
}
?>