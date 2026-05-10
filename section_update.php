<?php
require("include/conn.php");
$vid = $_REQUEST['vid'];

// Fetch current data
$sql = "SELECT * FROM tb_section WHERE section='$vid'";
$result = $conn->query($sql);
$row = $result->fetch_assoc();
?>
<html>
<head>
    <title>Update Section</title>
</head>
<body>
    <h3>Update Section Details</h3>
    <form action="section_update_save.php" method="post">
        <input type="hidden" name="txtoldid" value="<?php echo $row['section']; ?>">

        <table border="1" cellpadding="10">
            <tr>
                <td>Section Name:</td>
                <td><input type="text" name="txtsection" value="<?php echo $row['section']; ?>" required></td>
            </tr>
            <tr>
                <td>Year Level:</td>
                <td><input type="number" name="txtyearlevel" value="<?php echo $row['year_level']; ?>" min="1" max="4" required></td>
            </tr>
            <tr>
                <td colspan="2" align="center">
                    <input type="submit" value="Update Record">
                    <button type="button" onClick="window.location.href='section.php'">Back to List</button>
                </td>
            </tr>
        </table>
    </form>
</body>
</html>