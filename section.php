<html>
<body>
 
<?php
$servername = "localhost";
$database = "db_enrollment";
$username = "root";
$password = "";
 
$conn = mysqli_connect($servername, $username, $password, $database);
 
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
 
<form name="formsearch" action="section.php" method="post">
    <label>Input Query: </label>
    <input type="text" name="txtsearch" id="txtsearch" value="" />
    <input type="submit" value="      Search      " />
    <button type="button" onClick="window.location.href='section.php'">    Display All    </button>
</form>
 
<table cellspacing=2 border=1>
    <tr>
        <th>Section</th>
        <th>Year Level</th>
        <th bgcolor="#FFFF00">Action</th>
    </tr>

<?php
$vsearch = isset($_POST['txtsearch']) ? $_POST['txtsearch'] : "";
 
// NEW: Simplified SQL query fetching only from tb_section
$sql_base = "SELECT section, year_level FROM tb_section";

if($vsearch != "") {
    // Search only in section or year_level
    $sql = $sql_base . " WHERE section='$vsearch' || year_level='$vsearch' ORDER BY section";
} else {
    $sql = $sql_base . " ORDER BY section";
}
 
$result = $conn->query($sql);
if($result && $result->num_rows > 0) 
{
    while($row = $result->fetch_assoc())
    {
        $vsection = $row['section'];
        $vyear_level = $row['year_level'];
?>
    <tr>
        <td><?php echo $vsection; ?></td>
        <td><?php echo $vyear_level; ?></td>
        <td align="center">
            <a href="section_update.php?vid=<?php echo $vsection; ?>">UPDATE</a> | 
            <a href="section_delete.php?vid=<?php echo $vsection; ?>">DELETE</a>
        </td>
    </tr>
<?php   
    }
}
?>
</table>

<br>
<button type="button" onClick="window.location.href='section_insert.php'">&nbsp;&nbsp;&nbsp;&nbsp;ADD NEW&nbsp;&nbsp;&nbsp;&nbsp;</button>
&nbsp;
<button type="button" onClick="window.location.href='rep_section.php'">&nbsp;&nbsp;&nbsp;&nbsp;PRINT&nbsp;&nbsp;&nbsp;&nbsp;</button>
&nbsp;
<button type="button" onClick="window.location.href='index.php'">&nbsp;&nbsp;&nbsp;&nbsp;BACK&nbsp;&nbsp;&nbsp;&nbsp;</button>
 
</body>
</html>