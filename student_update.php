<?php
require("include/conn.php");
$vstudentnumber = $_REQUEST['vid'];

$sql = "SELECT * FROM tb_students WHERE student_number='$vstudentnumber'";
$result = $conn->query($sql);
if($row = $result->fetch_assoc()) {
    $vlastname = $row['last_name'];			
    $vfirstname = $row['first_name'];
    $vmiddlename = $row['middle_name'];
    $vgender = $row['gender']; // Fetch the current gender
}
?>
<html><body>
    <form action="student_update_save.php" method="post">
        <input type="hidden" name="txtoldid" value="<?php echo $vstudentnumber; ?>">
        
        <table border=1>
            <tr><td>Student Number:</td><td><input type="text" name="txtstudentnumber" value="<?php echo $vstudentnumber; ?>"></td></tr>
            <tr><td>Last Name:</td><td><input type="text" name="txtlastname" value="<?php echo $vlastname; ?>"></td></tr>
            <tr><td>First Name:</td><td><input type="text" name="txtfirstname" value="<?php echo $vfirstname; ?>"></td></tr>
            <tr><td>Middle Name:</td><td><input type="text" name="txtmiddlename" value="<?php echo $vmiddlename; ?>"></td></tr>
            <tr>
                <td>Gender:</td>
                <td>
                    <select name="txtgender">
                        <option value="Male" <?php echo ($vgender == 'Male') ? 'selected' : ''; ?>>Male</option>
                        <option value="Female" <?php echo ($vgender == 'Female') ? 'selected' : ''; ?>>Female</option>
                    </select>
                </td>
            </tr>
            <tr>
                <td colspan="2" align="center">
                    <input type="submit" value="Update Record" />
                    <button type="button" onClick="window.location.href='students.php'">Back</button>
                </td>
            </tr>
        </table>
    </form>
</body></html>