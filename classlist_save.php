<?php
require("include/conn.php");

$mode = $_POST['mode'];
$sec_index = $_POST['section_index'];
$course_index = $_POST['course_index'];

if ($mode == "add_course") {
    // REQUIREMENT: Validate if course already exists in the section
    $check = $conn->query("SELECT * FROM tb_classlist WHERE section_index = '$sec_index' AND course_index = '$course_index'");
    
    if ($check->num_rows > 0) {
        echo "<script>alert('Validation Error: This course is already in this section!'); window.history.back();</script>";
    } else {
        // Insert as a course placeholder (student_number is NULL initially)
        $conn->query("INSERT INTO tb_classlist (section_index, course_index, student_number) VALUES ('$sec_index', '$course_index', NULL)");
        header("Location: classlist_manage.php?sel_section=$sec_index");
    }
}

if ($mode == "add_student") {
    $student_no = $_POST['student_number']; //

    // REQUIREMENT: Validate if student already inserted in this section and course
    $check = $conn->query("SELECT * FROM tb_classlist WHERE section_index = '$sec_index' AND course_index = '$course_index' AND student_number = '$student_no'");

    if ($check->num_rows > 0) {
        echo "<script>alert('Validation Error: This student is already enrolled in this specific class!'); window.history.back();</script>";
    } else {
        // Find if there is an empty slot for this course (student_number IS NULL)
        $placeholder = $conn->query("SELECT classlist_id FROM tb_classlist WHERE section_index = '$sec_index' AND course_index = '$course_index' AND student_number IS NULL LIMIT 1");
        
        if ($placeholder->num_rows > 0) {
            $row = $placeholder->fetch_assoc();
            $id = $row['classlist_id'];
            $conn->query("UPDATE tb_classlist SET student_number = '$student_no' WHERE classlist_id = '$id'");
        } else {
            $conn->query("INSERT INTO tb_classlist (section_index, course_index, student_number) VALUES ('$sec_index', '$course_index', '$student_no')");
        }
        header("Location: classlist_manage.php?sel_section=$sec_index");
    }
}
?>