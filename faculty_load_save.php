<?php
require("include/conn.php");

$id_no = $_POST['id_no'];
$section_index = $_POST['section_index'];
$course_index = $_POST['course_index'];

// 1. VALIDATION: Check if this Section + Course combination is already assigned to ANY faculty
$check_sql = "SELECT fl.*, f.last_name, f.first_name 
              FROM tb_faculty_loading fl
              JOIN tb_faculty f ON fl.id_no = f.id_no
              WHERE fl.section_index = '$section_index' 
              AND fl.course_index = '$course_index'";

$check_res = $conn->query($check_sql);

if($check_res->num_rows > 0) {
    // Fetch the name of the faculty who already has this load
    $existing_load = $check_res->fetch_assoc();
    $faculty_name = $existing_load['first_name'] . " " . $existing_load['last_name'];
    
    echo "<script>
            alert('Error: This Course and Section is already assigned to $faculty_name!'); 
            window.history.back();
          </script>";
    exit();
}

// 2. SAVE: If no duplicate is found, proceed with the assignment
$sql = "INSERT INTO tb_faculty_loading (id_no, section_index, course_index) 
        VALUES ('$id_no', '$section_index', '$course_index')";

if($conn->query($sql)) {
    header("Location: faculty_load_manage.php?sel_faculty=$id_no");
} else {
    echo "Error: " . $conn->error;
}
?>