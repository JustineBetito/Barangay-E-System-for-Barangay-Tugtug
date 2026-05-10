<?php
require("include/conn.php");
$vid = $_REQUEST['vid'];

// Fetch course details to show the user what they are deleting
$sql = "SELECT * FROM tb_courses WHERE course_code='$vid'";
$result = $conn->query($sql);
$row = $result->fetch_assoc();
?>
<html>
<body>
    <h3>Delete Course Confirmation</h3>
    <p>Are you sure you want to delete the following course?</p>
    <table border="1">
        <tr><td>Course Code:</td><td><?php echo $row['course_code']; ?></td></tr>
        <tr><td>Description:</td><td><?php echo $row['course_description']; ?></td></tr>
    </table>
    <br>
    <form action="course_delete_save.php" method="post">
        <input type="hidden" name="txtcoursecode" value="<?php echo $vid; ?>">
        <input type="submit" value="Yes, Delete it" />
        <button type="button" onClick="window.location.href='courses.php'">No, Go Back</button>
    </form>
</body>
</html>