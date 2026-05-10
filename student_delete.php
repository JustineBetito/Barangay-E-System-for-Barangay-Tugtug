<?php
require("include/conn.php");
$vstudentnumber = $_REQUEST['vid'];

$sql = "SELECT * FROM tb_students WHERE student_number='$vstudentnumber'";
$result = $conn->query($sql);
if($row = $result->fetch_assoc()) {
    $vname = $row['first_name'] . " " . $row['last_name'];
}
?>
<html><body>
    <form action="student_delete_save.php" method="post">
        <table border="1">
            <tr><td colspan="2" align="center"><b>Delete Student</b></td></tr>
            <tr><td colspan="2" align="center">Are you sure you want to delete <b><?php echo $vname; ?></b>?</td></tr>
            <input type="hidden" name="txtstudentnumber" value="<?php echo $vstudentnumber; ?>">
            <tr>
                <td colspan="2" align="center">
                    <input type="submit" value="Yes, Delete It" />
                    <button type="button" onClick="window.location.href='students.php'">Cancel</button>
                </td>
            </tr>
        </table>
    </form>
</body></html>