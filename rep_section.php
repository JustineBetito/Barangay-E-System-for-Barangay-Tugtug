<?php
error_reporting(E_ERROR | E_PARSE);
ob_start();

require_once "tcpdf_include.php";
require "include/conn.php";

// create new PDF document
$pdf = new TCPDF(
    PDF_PAGE_ORIENTATION,
    PDF_UNIT,
    PDF_PAGE_FORMAT,
    true,
    "UTF-8",
    false,
);

// set document information
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor("De La Salle Lipa");
$pdf->SetSubject("Section List");

// --- START DLSL CUSTOMIZATION ---
$header_string = "1962 J.P. Laurel National Highway, Mataas na Lupa, Lipa City, Batangas, 4217 Philippines.\ndlsl.edu.ph\n+63 43 756 5555";

// Header with the logo
$pdf->SetHeaderData('dlsl logo.png', 15, "DLSL - De La Salle Lipa", $header_string);

// Set Top Margin to 40 for the tall logo
$pdf->SetMargins(PDF_MARGIN_LEFT, 40, PDF_MARGIN_RIGHT);
// --- END DLSL CUSTOMIZATION ---

$pdf->setHeaderFont([PDF_FONT_NAME_MAIN, "", PDF_FONT_SIZE_MAIN]);
$pdf->setFooterFont([PDF_FONT_NAME_DATA, "", PDF_FONT_SIZE_DATA]);
$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
$pdf->SetAutoPageBreak(true, PDF_MARGIN_BOTTOM);
$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
$pdf->SetFont("helvetica", "", 7);

$pdf->AddPage("p");
$pdf->Cell(0, 4, "List of Sections", 0, 1, "L");

$tbl_header = '<table border="1" cellpadding="2">';
$tbl_footer = "</table>";
$tbl = "";

// Yellow header row - matching your old look
$tbl_header_row = '<tr style="background-color:#FFFF00;color:#000000;font-weight:bold;">
<td width="70">Section</td>
<td width="70">Year Level</td>

</tr>';

// FIXED SQL: We only select from tb_section now since course_index is gone
$sql = "SELECT section, year_level FROM tb_section ORDER BY year_level, section";

$result = $conn->query($sql);

if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $vsection = $row["section"];
        $vyear = $row["year_level"];
        
    

        $tbl .= '<tr>
        <td width="70">' . $vsection . '</td>
        <td width="70">' . $vyear . '</td>
        </tr>';
    }
} else {
    $tbl .= '<tr><td colspan="2" align="center">No sections found in database.</td></tr>';
}

$pdf->writeHTML(
    $tbl_header . $tbl_header_row . $tbl . $tbl_footer,
    true,
    false,
    false,
    false,
    "",
);

// output PDF
$pdf->Output("rep_section.pdf", "I");
?>