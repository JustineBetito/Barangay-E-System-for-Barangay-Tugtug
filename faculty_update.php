<?php
require("include/conn.php");
$vid = $_REQUEST['vid'];
$sql = "SELECT * FROM tb_faculty WHERE id_no='$vid'";
$result = $conn->query($sql);
$row = $result->fetch_assoc();
?>
<form action="faculty_update_save.php" method="post">
    <input type="hidden" name="txtoldid" value="<?php echo $row['id_no']; ?>">

    <h3>Update Faculty Member</h3>
    ID Number: <input type="text" name="txtidno" value="<?php echo $row['id_no']; ?>"><br>
    
    Last Name: <input type="text" name="txtlast" value="<?php echo $row['last_name']; ?>"><br>
    First Name: <input type="text" name="txtfirst" value="<?php echo $row['first_name']; ?>"><br>
    Middle Name: <input type="text" name="txtmiddle" value="<?php echo $row['middle_name']; ?>"><br>
    
    <input type="submit" value="Update Record">
    <button type="button" onclick="window.location.href='faculty.php'">Back</button>
</form>