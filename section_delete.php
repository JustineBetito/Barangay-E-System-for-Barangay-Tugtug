<?php
require("include/conn.php");
$vid = $_REQUEST['vid']; // This is the section name from the URL

// Fetch the data to show the user what they are deleting
$sql = "SELECT * FROM tb_section WHERE section='$vid'";
$result = $conn->query($sql);
$row = $result->fetch_assoc();
?>
<html>
<body>
    <h3>Delete Section Confirmation</h3>
    <p>Are you sure you want to delete the following section?</p>
    
    <table border="1" cellpadding="10">
        <tr>
            <td><b>Section Name:</b></td>
            <td><?php echo $row['section']; ?></td>
        </tr>
        <tr>
            <td><b>Year Level:</b></td>
            <td><?php echo $row['year_level']; ?></td>
        </tr>
    </table>
    
    <br>
    <form action="section_delete_save.php" method="post">
        <input type="hidden" name="txtsection" value="<?php echo $vid; ?>">
        
        <input type="submit" value="Yes, Delete Section" />
        <button type="button" onClick="window.location.href='section.php'">No, Cancel</button>
    </form>
</body>
</html>