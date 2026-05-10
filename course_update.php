<?php
require("include/conn.php");
$vid = $_REQUEST['vid']; // This gets the course_code from the URL

$sql = "SELECT * FROM tb_courses WHERE course_code='$vid'";
$result = $conn->query($sql);
if($row = $result->fetch_assoc()) {
    $vdesc = $row['course_description'];
    $vunits = $row['units'];
}
?>
<html>
<body>
    <h3>Update Course</h3>
    <form action="course_update_save.php" method="post">
        <table border="1">
            <tr>
                <td>Course Code:</td>
                <td><input type="text" name="txtcoursecode" value="<?php echo $vid; ?>">
                    <input type="hidden" name="txtoldid" value="<?php echo $vid; ?>"></td>
            </tr>
            <tr>
                <td>Description:</td>
                <td><input type="text" name="txtdescription" value="<?php echo $vdesc; ?>"></td>
            </tr>
            <tr>
                <td>Units:</td>
                <td><input type="number" name="txtunits" value="<?php echo $vunits; ?>"></td>
            </tr>
            <tr>
                <td colspan="2" align="center">
                    <input type="submit" value="Update Course" />
                    <button type="button" onClick="window.location.href='courses.php'">Back</button>
                </td>
            </tr>
        </table>
    </form>
</body>
</html>