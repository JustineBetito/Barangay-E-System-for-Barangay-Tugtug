<?php
error_reporting(E_ERROR | E_PARSE);
ob_start();

require_once "tcpdf_include.php";
require "include/conn.php";

// Get the Faculty ID from the URL
$id_no = $_GET['id_no'];

// Fetch Faculty Name for the title
$fac_query = $conn->query("SELECT first_name, last_name FROM tb_faculty WHERE id_no = '$id_no'");
$fac = $fac_query->fetch_assoc();
$faculty_name = $fac['last_name'] . ", " . $fac['first_name'];

// Create new PDF document
$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, "UTF-8", false);

// Set document information
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor("De La Salle Lipa");
$pdf->SetTitle("Faculty Loading - " . $faculty_name);

// --- START DLSL CUSTOMIZATION ---
$header_string = "1962 J.P. Laurel National Highway, Mataas na Lupa, Lipa City, Batangas, 4217 Philippines.\ndlsl.edu.ph\n+63 43 756 5555";

// Set the DLSL logo and header text
$pdf->SetHeaderData('dlsl logo.png', 15, "DLSL - De La Salle Lipa", $header_string);

// Push content down to avoid overlapping the logo
$pdf->SetMargins(PDF_MARGIN_LEFT, 40, PDF_MARGIN_RIGHT);
// --- END DLSL CUSTOMIZATION ---

$pdf->setHeaderFont([PDF_FONT_NAME_MAIN, "", PDF_FONT_SIZE_MAIN]);
$pdf->setFooterFont([PDF_FONT_NAME_DATA, "", PDF_FONT_SIZE_DATA]);
$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);
$pdf->SetAutoPageBreak(true, PDF_MARGIN_BOTTOM);
$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
$pdf->SetFont("helvetica", "", 9);

$pdf->AddPage("p");

// Report Title
$pdf->Cell(0, 4, "Faculty Loading Report", 0, 1, "L");
$pdf->SetFont("helvetica", "B", 10);
$pdf->Cell(0, 7, "Instructor: " . $faculty_name . " (" . $id_no . ")", 0, 1, "L");
$pdf->SetFont("helvetica", "", 9);

// Table Structure
$tbl_header = '<table border="1" cellpadding="4">';
$tbl_footer = '</table>';

// Table Header Row (Yellow with Blue text per your TCPDF guide)
$tbl_contents = '<tr style="background-color:#FFFF00; color:#0000FF; font-weight:bold;">
    <td width="150">Section</td>
    <td width="280">Course Description</td>
</tr>';

// Join loading table with section and courses to get labels
$sql = "SELECT s.section, c.course_description 
        FROM tb_faculty_loading fl
        JOIN tb_section s ON fl.section_index = s.section_index
        JOIN tb_courses c ON fl.course_index = c.course_index
        WHERE fl.id_no = '$id_no'
        ORDER BY s.section ASC";

$result = $conn->query($sql);

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $tbl_contents .= '<tr>
            <td>' . $row["section"] . '</td>
            <td>' . $row["course_description"] . '</td>
        </tr>';
    }
} else {
    $tbl_contents .= '<tr><td colspan="2" align="center">No teaching load assigned yet.</td></tr>';
}

$pdf->writeHTML($tbl_header . $tbl_contents . $tbl_footer, true, false, false, false, "");

// Output PDF
$pdf->Output("Faculty_Load_" . $id_no . ".pdf", "I");
?>