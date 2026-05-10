<?php
error_reporting(E_ERROR | E_PARSE);
ob_start();

require_once "tcpdf_include.php";
require "include/conn.php";

$sid = $_GET['section_index'];
$sec_query = $conn->query("SELECT section FROM tb_section WHERE section_index = '$sid'");
$sec_info = $sec_query->fetch_assoc();

// Initialize TCPDF
$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, "UTF-8", false);

// Document Settings
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor("De La Salle Lipa");
$pdf->SetTitle("Class List - " . $sec_info['section']);

// --- DLSL Header Setup ---
$header_string = "1962 J.P. Laurel National Highway, Mataas na Lupa, Lipa City, Batangas, 4217 Philippines.\ndlsl.edu.ph\n+63 43 756 5555";
$pdf->SetHeaderData('dlsl logo.png', 15, "DLSL - De La Salle Lipa", $header_string);
$pdf->SetMargins(PDF_MARGIN_LEFT, 40, PDF_MARGIN_RIGHT);
$pdf->setHeaderFont([PDF_FONT_NAME_MAIN, "", PDF_FONT_SIZE_MAIN]);
$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
$pdf->SetAutoPageBreak(true, PDF_MARGIN_BOTTOM);
$pdf->SetFont("helvetica", "", 9);

$pdf->AddPage("p");

// Section Title
$pdf->SetFont("helvetica", "B", 11);
$pdf->Cell(0, 10, "List of Students: Section " . $sec_info['section'], 0, 1, "L");
$pdf->SetFont("helvetica", "", 9);

// Fetch Data - Ordered by Course Description then Last Name
$sql = "SELECT s.student_number, s.last_name, s.first_name, c.course_description 
        FROM tb_classlist cl 
        JOIN tb_students s ON cl.student_number = s.student_number 
        JOIN tb_courses c ON cl.course_index = c.course_index
        WHERE cl.section_index = '$sid' 
        ORDER BY c.course_description ASC, s.last_name ASC";

$result = $conn->query($sql);

$full_html = "";
$current_course = "";
$first_course = true;

if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        
        // If course name changes, handle the table separation
        if ($current_course != $row['course_description']) {
            
            // Close previous table if it's not the first course
            if (!$first_course) {
                $full_html .= '</table><br><br>';
            }

            $current_course = $row['course_description'];
            $first_course = false;

            // Add Course Title Header
            $full_html .= '<h3 style="background-color: #e3f2fd; color: #1565c0; padding: 5px;"> Course: ' . $current_course . '</h3>';
            
            // Start the Table for this specific course
            $full_html .= '<table border="1" cellpadding="4">
                <tr style="background-color:#FFFF00; font-weight:bold;">
                    <th width="100">Student No.</th>
                    <th width="165">Last Name</th>
                    <th width="165">First Name</th>
                </tr>';
        }

        // Add the student data row
        $full_html .= '<tr>
            <td>' . $row["student_number"] . '</td>
            <td>' . $row["last_name"] . '</td>
            <td>' . $row["first_name"] . '</td>
        </tr>';
    }
    // Close the very last table
    $full_html .= '</table>';
} else {
    $full_html = '<p style="text-align:center;">No records found for this section.</p>';
}

// Write HTML content to PDF
$pdf->writeHTML($full_html, true, false, false, false, "");

// Output PDF to Browser
$pdf->Output("ClassList_" . $sec_info['section'] . ".pdf", "I");
?>