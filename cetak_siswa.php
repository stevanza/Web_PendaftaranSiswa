<?php
require_once 'config.php';
require_once 'vendor/tecnickcom/tcpdf/tcpdf.php';

try {
    // Make sure no output has been sent before this point
    if(ob_get_length()) ob_clean();
    
    // Fetch student data
    $sql = "SELECT * FROM siswa ORDER BY nama_lengkap ASC";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $siswa_list = $stmt->fetchAll();

    // Extend TCPDF class to customize header/footer if needed
    class MYPDF extends TCPDF {
        // Page header
        public function Header() {
            // Set font
            $this->SetFont('helvetica', 'B', 16);
            // Title
            $this->Cell(0, 15, 'DATA SISWA', 0, true, 'C');
        }
    }

    // Create new PDF document
    $pdf = new MYPDF('P', 'mm', 'A4', true, 'UTF-8');

    // Set document information
    $pdf->SetCreator('Sistem Informasi Sekolah');
    $pdf->SetAuthor('Admin Sekolah');
    $pdf->SetTitle('Data Siswa');

    // Set margins
    $pdf->SetMargins(15, 25, 15);
    $pdf->SetHeaderMargin(10);
    $pdf->SetFooterMargin(10);

    // Add a page
    $pdf->AddPage();

    // Set font
    $pdf->SetFont('helvetica', '', 12);
    $pdf->Cell(0, 10, 'Dicetak pada: ' . date('d-m-Y H:i:s'), 0, 1, 'C');
    $pdf->Ln(10);

    // Table header
    $pdf->SetFont('helvetica', 'B', 10);
    $pdf->SetFillColor(220, 220, 220);
    $pdf->Cell(10, 8, 'No', 1, 0, 'C', true);
    $pdf->Cell(30, 8, 'NISN', 1, 0, 'C', true);
    $pdf->Cell(50, 8, 'Nama Lengkap', 1, 0, 'C', true);
    $pdf->Cell(25, 8, 'Gender', 1, 0, 'C', true);
    $pdf->Cell(40, 8, 'No. Telepon', 1, 0, 'C', true);
    $pdf->Cell(35, 8, 'Email', 1, 1, 'C', true);

    // Table data
    $pdf->SetFont('helvetica', '', 10);
    $pdf->SetFillColor(255, 255, 255);
    $no = 1;
    foreach($siswa_list as $siswa) {
        $pdf->Cell(10, 8, $no++, 1, 0, 'C', true);
        $pdf->Cell(30, 8, $siswa['nisn'], 1, 0, 'C', true);
        $pdf->Cell(50, 8, $siswa['nama_lengkap'], 1, 0, 'L', true);
        $pdf->Cell(25, 8, ($siswa['jenis_kelamin'] == 'L' ? 'Laki-laki' : 'Perempuan'), 1, 0, 'C', true);
        $pdf->Cell(40, 8, $siswa['no_telp'], 1, 0, 'C', true);
        $pdf->Cell(35, 8, $siswa['email'], 1, 1, 'L', true);
    }

    // Clear any output that might have been created
    ob_end_clean();

    // Output PDF
    $pdf->Output('data_siswa_'.date('Ymd_His').'.pdf', 'D');
    exit();

} catch(Exception $e) {
    die("Error: " . $e->getMessage());
}
?>