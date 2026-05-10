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
$pdf->SetSubject("Faculty Assignment List");
$pdf->SetKeywords("TCPDF, PDF, example, test, guide");

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

// set margins
$pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
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
$pdf->Cell(0, 4, "List of Faculty Assignments", 0, 1, "L");

// set table content
$tbl_header = '<table border="1" cellpadding="2">';
$tbl_footer = "</table>";
$tbl = "";
$tbl1 = "";

$tbl1 .= '<tr style="background-color:#FFFF00;color:#0000FF;">
<td width="100">Faculty Name</td>
<td width="60">Course Code</td>
<td width="130">Course Description</td>
<td width="40">Section</td>
<td width="30">Year</td>
<td width="60">School Year</td>
<td width="60">Semester</td>
</tr>';

$sql = "SELECT * FROM tb_faculty_assignments ORDER BY assignment_index";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $vfaculty_id = $row["faculty_id"];
        $vcourse_index = $row["course_index"];
        $vsection_index = $row["section_index"];
        $vschool_year = $row["school_year"];
        $vsemester = $row["semester"];

        // get faculty name
        $vfaculty_name = "";
        $sql1 = "SELECT * FROM tb_faculty WHERE id_no='$vfaculty_id'";
        $result1 = $conn->query($sql1);
        if ($result1->num_rows > 0) {
            $row1 = $result1->fetch_assoc();
            $vfaculty_name = $row1["last_name"] . ", " . $row1["first_name"];
        }

        // get course details
        $vcourse_code = "";
        $vcourse_description = "";
        $sql1 = "SELECT * FROM tb_courses WHERE course_index='$vcourse_index'";
        $result1 = $conn->query($sql1);
        if ($result1->num_rows > 0) {
            $row1 = $result1->fetch_assoc();
            $vcourse_code = $row1["course_code"];
            $vcourse_description = $row1["course_description"];
        }

        // get section details
        $vsection = "";
        $vyear_level = "";
        $sql1 = "SELECT * FROM tb_section WHERE section_index='$vsection_index'";
        $result1 = $conn->query($sql1);
        if ($result1->num_rows > 0) {
            $row1 = $result1->fetch_assoc();
            $vsection = $row1["section"];
            $vyear_level = $row1["year_level"];
        }

        $tbl .= '<tr>
        <td width="100">' . $vfaculty_name . '</td>
        <td width="60">' . $vcourse_code . '</td>
        <td width="130">' . $vcourse_description . '</td>
        <td width="40">' . $vsection . '</td>
        <td width="30">' . $vyear_level . '</td>
        <td width="60">' . $vschool_year . '</td>
        <td width="60">' . $vsemester . '</td>
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
$pdf->Output("rep_faculty_assignments", "I");
?>
