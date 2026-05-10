<?php
require("include/conn.php");
$sec_index = $_GET['section_index'];

$sec_info = $conn->query("SELECT section FROM tb_section WHERE section_index = '$sec_index'")->fetch_assoc();
?>
<html>
<head><title>Enroll Student</title></head>
<body>
    <h2>Enroll Student in Section: <?php echo $sec_info['section']; ?></h2>
    
    <form action="classlist_save.php" method="POST">
        <input type="hidden" name="section_index" value="<?php echo $sec_index; ?>">
        <input type="hidden" name="mode" value="add_student">

        <p><strong>Step 1: Select the Course</strong></p>
        <select name="course_index" required>
            <option value="">-- Select Course --</option>
            <?php
            // Only show courses that are already assigned to THIS section in tb_classlist
            $assigned = $conn->query("SELECT DISTINCT c.course_index, c.course_description 
                                    FROM tb_classlist cl 
                                    JOIN tb_courses c ON cl.course_index = c.course_index 
                                    WHERE cl.section_index = '$sec_index'");
            while($a = $assigned->fetch_assoc()) {
                echo "<option value='".$a['course_index']."'>".$a['course_description']."</option>";
            }
            ?>
        </select>

        <p><strong>Step 2: Select the Student</strong></p>
        <select name="student_number" required>
            <option value="">-- Select Student --</option>
            <?php
            // Pulling from tb_students
            $students = $conn->query("SELECT * FROM tb_students ORDER BY last_name");
            while($s = $students->fetch_assoc()) {
                echo "<option value='".$s['student_number']."'>".$s['last_name'].", ".$s['first_name']."</option>";
            }
            ?>
        </select>
        <br><br>
        <input type="submit" value="Enroll Student">
        <button type="button" onclick="history.back()">Cancel</button>
    </form>
</body>
</html>