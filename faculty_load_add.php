<?php
require("include/conn.php");

// 1. Get the ID from the URL link
$id_no = $_GET['id_no']; 

// 2. Fetch the faculty name to display in the header
$fac_res = $conn->query("SELECT first_name, last_name FROM tb_faculty WHERE id_no = '$id_no'");
$fac = $fac_res->fetch_assoc();
?>
<html>
<head><title>Assign Faculty Load</title></head>
<body>
    <a href="faculty_load_manage.php?sel_faculty=<?php echo $id_no; ?>" style="text-decoration: none;">&larr; Back to Management</a>
    
    <h2>Assign Load for: <?php echo $fac['first_name'] . " " . $fac['last_name']; ?></h2>
    
    <form action="faculty_load_save.php" method="POST">
        <input type="hidden" name="id_no" value="<?php echo $id_no; ?>">
        
        <label>Select Section:</label><br>
        <select name="section_index" required>
            <option value="">-- Choose Section --</option>
            <?php
            $sections = $conn->query("SELECT * FROM tb_section ORDER BY section ASC");
            while($s = $sections->fetch_assoc()) {
                echo "<option value='".$s['section_index']."'>".$s['section']."</option>";
            }
            ?>
        </select>
        <br><br>

        <label>Select Course:</label><br>
        <select name="course_index" required>
            <option value="">-- Choose Course --</option>
            <?php
            $courses = $conn->query("SELECT * FROM tb_courses ORDER BY course_description ASC");
            while($c = $courses->fetch_assoc()) {
                echo "<option value='".$c['course_index']."'>".$c['course_description']."</option>";
            }
            ?>
        </select>
        <br><br>

        <input type="submit" value="Confirm Assignment">
    </form>
</body>
</html>