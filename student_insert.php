<html>
<body>
    <form action="student_insert_save.php" method="post">
        <table border="1">
            <tr><td colspan="2" align="center"><b>Add New Student</b></td></tr>
            <tr>
                <td>Student Number:</td>
                <td><input type="text" name="txtstudentnumber"></td>
            </tr>
            <tr>
                <td>Last Name:</td>
                <td><input type="text" name="txtlastname"></td>
            </tr>
            <tr>
                <td>First Name:</td>
                <td><input type="text" name="txtfirstname"></td>
            </tr>
            <tr>
                <td>Middle Name:</td>
                <td><input type="text" name="txtmiddlename"></td>
            </tr>
            <tr>
                <td>Gender:</td>
                <td>
                    <select name="txtgender">
                        <option value="Male">Male</option>
                        <option value="Female">Female</option>
                    </select>
                </td>
            </tr>
            <tr>
                <td colspan="2" align="center">
                    <input type="submit" value="Save Record" />
                    <button type="button" onClick="window.location.href='students.php'">Back</button>
                </td>
            </tr>
        </table>
    </form>
</body>
</html>