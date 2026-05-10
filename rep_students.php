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
$pdf->SetSubject("Student Report");

// --- START DLSL CUSTOMIZATION ---
$header_string = "1962 J.P. Laurel National Highway, Mataas na Lupa, Lipa City, Batangas, 4217 Philippines.\ndlsl.edu.ph\n+63 43 756 5555";

// Reduced logo width to 15 to account for its tall height
$pdf->SetHeaderData('dlsl logo.png', 15, "DLSL - De La Salle Lipa", $header_string);

// Set Top Margin to 40 (This pushes the table down so the tall logo fits)
$pdf->SetMargins(PDF_MARGIN_LEFT, 40, PDF_MARGIN_RIGHT);
// --- END DLSL CUSTOMIZATION ---

// set header and footer fonts
$pdf->setHeaderFont([PDF_FONT_NAME_MAIN, "", PDF_FONT_SIZE_MAIN]);
$pdf->setFooterFont([PDF_FONT_NAME_DATA, "", PDF_FONT_SIZE_DATA]);

// set default monospaced font
$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

// REMOVED THE SECOND SETMARGINS CALL THAT WAS RESETTING YOUR CHANGES

$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

// set auto page breaks
$pdf->SetAutoPageBreak(true, PDF_MARGIN_BOTTOM);

// set image scale factor
$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

// set font
$pdf->SetFont("helvetica", "", 7);

// add a page
$pdf->AddPage("p");

// print title
$pdf->Cell(0, 4, "List of Students", 0, 1, "L");

// set table content
$tbl_header = '<table border="1" cellpadding="2">';
$tbl_footer = "</table>";
$tbl = "";
$tbl1 = "";

$tbl1 .= '<tr style="background-color:#FFFF00;color:#0000FF;">
<td width="55">Student No.</td>
<td width="100">Last Name</td>
<td width="100">First Name</td>
<td width="100">Middle Name</td>
<td width="60">Gender</td>
</tr>';

$sql = "SELECT * FROM tb_students ORDER BY `index`";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $vstudent_number = $row["student_number"];
        $vlastname = $row["last_name"];
        $vfirstname = $row["first_name"];
        $vmiddlename = $row["middle_name"];
        $vgender = $row["gender"];

        $tbl .= '<tr>
        <td width="55">' . $vstudent_number . '</td>
        <td width="100">' . $vlastname . '</td>
        <td width="100">' . $vfirstname . '</td>
        <td width="100">' . $vmiddlename . '</td>
        <td width="60">' . $vgender . '</td>
        </tr>';
    }
}

$pdf->writeHTML(
    $tbl_header . $tbl1 . $tbl . $tbl_footer,
    true,
    false,
    false,
    false,
    "",
);

// output PDF
$pdf->Output("rep_students.pdf", "I");
?>