<?php
require("include/conn.php");

$id = $_GET['id'];
$section = $_GET['section'];

$sql = "DELETE FROM tb_classlist WHERE classlist_id = '$id'";

if($conn->query($sql)) {
    header("Location: classlist_manage.php?sel_section=$section");
} else {
    echo "Error deleting record: " . $conn->error;
}
?>