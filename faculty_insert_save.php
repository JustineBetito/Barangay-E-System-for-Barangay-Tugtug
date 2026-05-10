<?php
require("include/conn.php");
$v_id = $_POST['txtidno'];
$v_last = $_POST['txtlast'];
$v_first = $_POST['txtfirst'];
$v_mid = $_POST['txtmiddle'];

$check = $conn->query("SELECT * FROM tb_faculty WHERE id_no = '$v_id'");
if ($check->num_rows > 0) {
    echo "<script>alert('Error: Faculty ID $v_id already exists!'); window.history.back();</script>";
    exit();
}

$sql = "INSERT INTO tb_faculty (id_no, last_name, first_name, middle_name) VALUES ('$v_id', '$v_last', '$v_first', '$v_mid')";
if ($conn->query($sql) === TRUE) {
    echo "<script>alert('Faculty Added Successfully.'); window.location.href='faculty.php';</script>";
}
?>