<?php
// Include the FPDF library
require('fpdf/fpdf.php');

// Retrieve the student_id and certificate_id from the form submission
$student_id = $_POST['student_id'];
$certificate_id = $_POST['certificate_id'];

// You can fetch additional details about the student or certificate from the database here if needed
include('admin/db.php');

try {
    $db = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Fetch student and certificate information from the database
    $query = "SELECT students.student_name, students.course_name, certificates.issue_date
              FROM students 
              JOIN certificates ON students.student_id = certificates.student_id 
              WHERE students.student_id = :student_id AND certificates.certificate_id = :certificate_id";
    
    $stmt = $db->prepare($query);
    $stmt->bindParam(':student_id', $student_id);
    $stmt->bindParam(':certificate_id', $certificate_id);
    $stmt->execute();
    $data = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$data) {
        die("No certificate or student found with the given details.");
    }

    $student_name = $data['student_name'];
    $course_name = $data['course_name'];
    $issue_date = $data['issue_date'];

} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}

// Create a new PDF document using FPDF
$pdf = new FPDF();
$pdf->AddPage();
// Add logo image (corrected parameters)
$pdf->Image('images/logo.jpg', 75, 10, 60); // Adjust x, y, and width as needed
$pdf->Ln(50);
// Set font and size for the PDF
$pdf->SetFont('Arial', 'B', 16);

// Certificate Title
$pdf->Cell(0, 10, 'Certificate of Completion', 0, 1, 'C');

// Line break
$pdf->Ln(10);

// Add student name
$pdf->SetFont('Arial', '', 12);
$pdf->Cell(0, 10, 'This is to certify that', 0, 1, 'C');
$pdf->SetFont('Arial', 'B', 14);
$pdf->Cell(0, 10, $student_name, 0, 1, 'C');

// Line break
$pdf->Ln(5);

// Add course name
$pdf->SetFont('Arial', '', 12);
$pdf->Cell(0, 10, 'has successfully completed the course', 0, 1, 'C');
$pdf->SetFont('Arial', 'B', 14);
$pdf->Cell(0, 10, $course_name, 0, 1, 'C');

// Line break
$pdf->Ln(5);

// Add issue date
$pdf->SetFont('Arial', '', 12);
$pdf->Cell(0, 10, 'Date of Issue: ' . date('F d, Y', strtotime($issue_date)), 0, 1, 'C');

// Line break for signature
$pdf->Ln(10);

// Add signature field
$pdf->SetFont('Arial', '', 12);
$pdf->Cell(0, 8, 'Authorized Signature', 0, 1, 'R');
$pdf->Ln(-5);
// Add signature image (adjust the width and height as necessary)
$pdf->Image('images/signature.png', 150, $pdf->GetY(), 60);

// Move the cursor down for the signature line
$pdf->Ln(11); // Adjust this value to control space between image and line

// Add signature line
$pdf->Cell(0, 10, '___________________', 0, 1, 'R');


// Output the generated PDF
$pdf->Output('I', 'Certificate_' . $certificate_id . '.pdf'); // This will display the PDF in the browser

?>
