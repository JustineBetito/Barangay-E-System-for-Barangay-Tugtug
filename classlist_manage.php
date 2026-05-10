<?php
require("include/conn.php");
?>
<html>
<head><title>Manage Class List</title></head>
<body>
    <a href="index.php" style="text-decoration: none; color: #1565c0;">&larr; Back to Main Menu</a>
    <br><br>

    <h2>Class List Management</h2>
    <form method="GET">
        Select Section: 
        <select name="sel_section" onchange="this.form.submit()">
            <option value="">-- Select Section --</option>
            <?php
            $sec_query = $conn->query("SELECT * FROM tb_section ORDER BY section ASC");
            while($s = $sec_query->fetch_assoc()){
                $selected = (isset($_GET['sel_section']) && $_GET['sel_section'] == $s['section_index']) ? "selected" : "";
                echo "<option value='".$s['section_index']."' $selected>".$s['section']."</option>";
            }
            ?>
        </select>
    </form>

    <?php if(isset($_GET['sel_section']) && $_GET['sel_section'] != ""): 
        $current_section = $_GET['sel_section'];
    ?>
        <hr>
        <button onclick="window.location.href='classlist_add_course.php?section_index=<?php echo $current_section; ?>'">+ Add Course to this Section</button>
        <button onclick="window.location.href='classlist_add_student.php?section_index=<?php echo $current_section; ?>'">+ Add Student to Section/Course</button>
        
        <button onclick="window.open('print_classlist.php?section_index=<?php echo $current_section; ?>', '_blank')">
            Print Class List for this Section
        </button>
        
        <h3>Current Class List for this Section</h3>

        <?php
        // One clean query ordered by course first
        $list_sql = "SELECT cl.classlist_id, c.course_description, cl.student_number, s.last_name, s.first_name 
                     FROM tb_classlist cl
                     LEFT JOIN tb_courses c ON cl.course_index = c.course_index
                     LEFT JOIN tb_students s ON cl.student_number = s.student_number
                     WHERE cl.section_index = '$current_section'
                     ORDER BY c.course_description ASC, s.last_name ASC";
        
        $list_res = $conn->query($list_sql);
        
        $current_course = ""; 
        $first_course = true;

        if ($list_res->num_rows > 0) {
            while ($row = $list_res->fetch_assoc()) {
                // If we hit a new course, start a new table
                if ($current_course != $row['course_description']) {
                    
                    if (!$first_course) {
                        echo "</table><br>"; // Close previous table
                    }

                    $current_course = $row['course_description'];
                    $first_course = false;

                    echo "<h3 style='background-color: #e3f2fd; padding: 10px; border-left: 5px solid #1565c0; margin-bottom:0;'>
                            Course: " . $current_course . "
                          </h3>";
                    
                    echo '<table border="1" cellpadding="10" width="100%" style="border-collapse: collapse;">
                            <tr bgcolor="#FFFF00">
                                <th width="30%">Student ID</th>
                                <th width="50%">Student Name</th>
                                <th width="20%">Action</th>
                            </tr>';
                }

                $st_id = $row['student_number'] ?? "---";
                $st_name = ($row['last_name']) ? $row['last_name'] . ", " . $row['first_name'] : "<i>No Students Enrolled</i>";
                
                echo "<tr>
                        <td>" . $st_id . "</td>
                        <td>" . $st_name . "</td>
                        <td align='center'>
                            <a href='classlist_delete.php?id=" . $row['classlist_id'] . "&section=" . $current_section . "' 
                               onclick='return confirm(\"Remove this student?\")' 
                               style='color: red; text-decoration: none;'>
                               Delete
                            </a>
                        </td>
                      </tr>";
            }
            echo "</table>"; 
        } else {
            echo "<p align='center'>No courses or students assigned yet.</p>";
        }
        ?>
    <?php endif; ?>
</body>
</html>