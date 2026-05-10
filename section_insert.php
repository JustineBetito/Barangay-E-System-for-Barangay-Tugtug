<html>
<body>

<form name="forminsert" action="section_insert_save.php" method="post">
    <table border="1">
        <tr>
            <th colspan="2">Add New Section</th>
        </tr>
        <tr>
            <td>Section Name (e.g. IT-1A):</td>
            <td><input type="text" name="txtsection" required></td>
        </tr>
        <tr>
            <td>Year Level:</td>
            <td><input type="number" name="txtyearlevel" min="1" max="4" required></td>
        </tr>
        <tr>
            <td colspan="2" align="center">
                <input type="submit" value="Save Section">
                <button type="button" onClick="window.location.href='section.php'">Back</button>
            </td>
        </tr>
    </table>
</form>

</body>
</html>