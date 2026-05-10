<?php
require("include/conn.php");
$vid = $_REQUEST['vid']; // This is the id_no from the URL link

// Fetch the faculty details to show on screen
$sql = "SELECT * FROM tb_faculty WHERE id_no='$vid'";
$result = $conn->query($sql);
$row = $result->fetch_assoc();
?>
<html>
<body>
    <h3>Delete Faculty Confirmation</h3>
    <p>Are you sure you want to delete this faculty member?</p>
    
    <table border="1" cellpadding="10">
        <tr>
            <td><b>ID Number:</b></td>
            <td><?php echo $row['id_no']; ?></td>
        </tr>
        <tr>
            <td><b>Name:</b></td>
            <td><?php echo $row['first_name'] . " " . $row['last_name']; ?></td>
        </tr>
    </table>
    
    <br>
    <form action="faculty_delete_save.php" method="post">
        <input type="hidden" name="txtidno" value="<?php echo $vid; ?>">
        
        <input type="submit" value="Yes, Delete Record" />
        <button type="button" onClick="window.location.href='faculty.php'">No, Cancel</button>
    </form>
</body>
</html>