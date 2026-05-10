<html>
<body>
<?php
require("include/conn.php");
$vsearch = isset($_POST['txtsearch']) ? $_POST['txtsearch'] : "";
?>

<form action="courses.php" method="post">
    <label>Search Course: </label>
    <input type="text" name="txtsearch" value="<?php echo $vsearch; ?>" />
    <input type="submit" value="Search" />
    <button type="button" onClick="window.location.href='courses.php'">Display All</button>
</form>

<table border="1" cellspacing="0" cellpadding="5">
    <tr bgcolor="#cccccc">
        <th>Course Code</th>
        <th>Course Description</th>
        <th>Units</th>
        <th bgcolor="yellow">Action</th>
    </tr>
<?php
$sql = ($vsearch != "") 
    ? "SELECT * FROM tb_courses WHERE course_description LIKE '%$vsearch%' OR course_code LIKE '%$vsearch%'" 
    : "SELECT * FROM tb_courses";

$result = $conn->query($sql);
if($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $id = $row['course_code'];
        echo "<tr>
                <td>".$row['course_code']."</td>
                <td>".$row['course_description']."</td>
                <td>".$row['units']."</td>
                <td>
                    <a href='course_update.php?vid=$id'>UPDATE</a> | 
                    <a href='course_delete.php?vid=$id'>DELETE</a>
                </td>
              </tr>";
    }
}
?>
</table>
<br>
<button type="button" onClick="window.location.href='course_insert.php'">&nbsp;&nbsp;&nbsp;&nbsp;ADD NEW&nbsp;&nbsp;&nbsp;&nbsp;</button>
&nbsp;
<button type="button" onClick="window.location.href='rep_courses.php'">&nbsp;&nbsp;&nbsp;&nbsp;PRINT&nbsp;&nbsp;&nbsp;&nbsp;</button>
&nbsp;
<button type="button" onClick="window.location.href='index.php'">&nbsp;&nbsp;&nbsp;&nbsp;BACK&nbsp;&nbsp;&nbsp;&nbsp;</button>
</body>
</html>