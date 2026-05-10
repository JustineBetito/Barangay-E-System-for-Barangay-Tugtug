<?php
require("include/conn.php"); 

// Capture the selected faculty ID safely
$current_faculty = isset($_GET['sel_faculty']) ? $_GET['sel_faculty'] : "";
?>
<html>
<head><title>Faculty Loading Management</title></head>
<body>
    <a href="index.php" style="text-decoration: none; color: #1565c0;">&larr; Back to Main Menu</a>
    <br><br>
    
    <h2>Faculty Loading Management</h2>
    <form method="GET">
        Select Faculty: 
        <select name="sel_faculty" onchange="this.form.submit()">
            <option value="">-- Select Faculty --</option>
            <?php
            $fac_query = $conn->query("SELECT * FROM tb_faculty ORDER BY last_name ASC");
            while($f = $fac_query->fetch_assoc()){
                $selected = ($current_faculty == $f['id_no']) ? "selected" : "";
                echo "<option value='".$f['id_no']."' $selected>".$f['last_name'].", ".$f['first_name']."</option>";
            }
            ?>
        </select>
    </form>

    <?php if($current_faculty != ""): ?>
        <hr>
        <button onclick="window.location.href='faculty_load_add.php?id_no=<?php echo $current_faculty; ?>'">
            + Assign New Load
        </button>
        
        <button onclick="window.open('print_faculty_load.php?id_no=<?php echo $current_faculty; ?>', '_blank')">
            Print Faculty Load
        </button>
        
        <h3>Current Schedule</h3>
        <table border="1" cellpadding="10" width="80%">
            <tr bgcolor="#FFFF00">
                <th>Section</th>
                <th>Course Description</th>
                <th>Action</th>
            </tr>
            <?php
            // Querying the current schedule for the selected faculty
            $load_sql = "SELECT fl.load_id, s.section, c.course_description 
                         FROM tb_faculty_loading fl
                         JOIN tb_section s ON fl.section_index = s.section_index
                         JOIN tb_courses c ON fl.course_index = c.course_index
                         WHERE fl.id_no = '$current_faculty'
                         ORDER BY s.section";
            
            $load_res = $conn->query($load_sql);
            if($load_res && $load_res->num_rows > 0){
                while($row = $load_res->fetch_assoc()){
                    echo "<tr>
                            <td>".$row['section']."</td>
                            <td>".$row['course_description']."</td>
                            <td align='center'>
                                <a href='faculty_load_delete.php?id=".$row['load_id']."&faculty=".$current_faculty."' 
                                   onclick='return confirm(\"Are you sure?\")' 
                                   style='color: red; font-weight: bold;'>
                                   Delete
                                </a>
                            </td>
                          </tr>";
                }
            } else {
                echo "<tr><td colspan='3' align='center'>No load assigned to this faculty yet.</td></tr>";
            }
            ?>
        </table>
    <?php endif; ?>
</body>
</html>