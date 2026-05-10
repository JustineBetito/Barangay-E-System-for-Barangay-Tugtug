<?php
require("include/conn.php");
$v_id = $_POST['txtidno'];
$vold = $_POST['txtoldid'];
$v_last = $_POST['txtlast'];
$v_first = $_POST['txtfirst'];
$v_mid = $_POST['txtmiddle'];

$check = $conn->query("SELECT * FROM tb_faculty WHERE id_no = '$v_id' AND id_no != '$vold'");
if ($check->num_rows > 0) {
    echo "<script>alert('Error: Faculty ID $v_id is already assigned to another record!'); window.history.back();</script>";
    exit();
}

$sql = "UPDATE tb_faculty SET id_no='$v_id', last_name='$v_last', first_name='$v_first', middle_name='$v_mid' WHERE id_no='$vold'";
if ($conn->query($sql) === TRUE) {
    echo "<script>alert('Faculty Updated Successfully.'); window.location.href='faculty.php';</script>";
}
?>