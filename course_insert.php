<html>
<body>
    <h3>Add New Course</h3>
    <form action="course_insert_save.php" method="post">
        <table border="1">
            <tr>
                <td>Course Code:</td>
                <td><input type="text" name="txtcoursecode" required></td>
            </tr>
            <tr>
                <td>Description:</td>
                <td><input type="text" name="txtdescription" required></td>
            </tr>
            <tr>
                <td>Units:</td>
                <td><input type="number" name="txtunits" required></td>
            </tr>
            <tr>
                <td colspan="2" align="center">
                    <input type="submit" value="Save Course" />
                    <button type="button" onClick="window.location.href='courses.php'">Back</button>
                </td>
            </tr>
        </table>
    </form>
</body>
</html>