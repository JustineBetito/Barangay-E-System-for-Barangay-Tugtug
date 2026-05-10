<?php
require("include/conn.php");
$sec_index = $_GET['section_index'];

// Get section name for the header
$sec_info = $conn->query("SELECT section FROM tb_section WHERE section_index = '$sec_index'")->fetch_assoc();
?>
<html>
<head><title>Add Course to Section</title></head>
<body>
    <h2>Add Course to Section: <?php echo $sec_info['section']; ?></h2>
    
    <form action="classlist_save.php" method="POST">
        <input type="hidden" name="section_index" value="<?php echo $sec_index; ?>">
        <input type="hidden" name="mode" value="add_course">
        
        Select Course:
        <select name="course_index" required>
            <option value="">-- Select Course --</option>
            <?php
            // Pulling from tb_courses
            $courses = $conn->query("SELECT * FROM tb_courses ORDER BY course_description");
            while($c = $courses->fetch_assoc()) {
                echo "<option value='".$c['course_index']."'>".$c['course_description']."</option>";
            }
            ?>
        </select>
        <br><br>
        <input type="submit" value="Assign Course">
        <button type="button" onclick="history.back()">Cancel</button>
    </form>
</body>
</html>