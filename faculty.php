<html>
<body>
 
<?php
require("include/conn.php"); 
$vsearch = isset($_POST['txtsearch']) ? $_POST['txtsearch'] : "";
?>
 
<form name="formsearch" action="faculty.php" method="post">
    <label>Input Query: </label>
    <input type="text" name="txtsearch" id="txtsearch" value="<?php echo $vsearch; ?>" />
    <input type="submit" value="      Search      " />
    <button type="button" onClick="window.location.href='faculty.php'">    Display All    </button>
</form>
 
<table cellspacing=2 border=1>
    <tr>
        <th>ID Number</th>
        <th>Last Name</th>
        <th>First Name</th>
        <th>Middle Name</th> <th bgcolor="#FFFF00">Action</th>
    </tr>

<?php
// Fixed search query to use 'id_no' and 'last_name'
if($vsearch != "") {
    $sql = "SELECT * FROM tb_faculty WHERE last_name LIKE '%$vsearch%' OR id_no LIKE '%$vsearch%' ORDER BY last_name";
} else {
    $sql = "SELECT * FROM tb_faculty ORDER BY last_name";
}
 
$result = $conn->query($sql);
if($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $id = $row['id_no']; // Use id_no from your screenshot
?>
    <tr>
        <td><?php echo $row['id_no']; ?></td>
        <td><?php echo $row['last_name']; ?></td>
        <td><?php echo $row['first_name']; ?></td>
        <td><?php echo $row['middle_name']; ?></td>
        <td align="center">
            <a href="faculty_update.php?vid=<?php echo $id; ?>">UPDATE</a> | 
            <a href="faculty_delete.php?vid=<?php echo $id; ?>">DELETE</a>
        </td>
    </tr>
<?php   
    }
}
?>
</table>

<br>
<button type="button" onClick="window.location.href='faculty_insert.php'">ADD NEW</button>
&nbsp;
<button type="button" onClick="window.location.href='rep_faculty.php'">PRINT</button>
&nbsp;
<button type="button" onClick="window.location.href='index.php'">BACK</button>
 
</body>
</html>