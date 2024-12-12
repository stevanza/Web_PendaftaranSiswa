<?php
require_once 'config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        // Validasi NISN (10 digit)
        if (!preg_match("/^[0-9]{10}$/", $_POST['nisn'])) {
            header("Location: index.php?error=nisn_invalid");
            exit();
        }

        // Validasi email
        if (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
            header("Location: index.php?error=email_invalid");
            exit();
        }

        // Validasi nomor telepon (minimal 10 digit, maksimal 13 digit)
        if (!preg_match("/^[0-9]{10,13}$/", $_POST['no_telp'])) {
            header("Location: index.php?error=phone_invalid");
            exit();
        }

        $sql = "INSERT INTO siswa (nisn, nama_lengkap, jenis_kelamin, alamat, no_telp, email) 
                VALUES (:nisn, :nama_lengkap, :jenis_kelamin, :alamat, :no_telp, :email)";
        
        $stmt = $pdo->prepare($sql);
        
        $stmt->bindParam(':nisn', $_POST['nisn']);
        $stmt->bindParam(':nama_lengkap', $_POST['nama_lengkap']);
        $stmt->bindParam(':jenis_kelamin', $_POST['jenis_kelamin']);
        $stmt->bindParam(':alamat', $_POST['alamat']);
        $stmt->bindParam(':no_telp', $_POST['no_telp']);
        $stmt->bindParam(':email', $_POST['email']);
        
        $stmt->execute();
        
        header("Location: data_siswa.php");
        exit();
        
    } catch (PDOException $e) {
        if ($e->errorInfo[1] === 1062) { // Duplicate entry error
            if (strpos($e->getMessage(), 'nisn')) {
                header("Location: index.php?error=nisn_exists");
            } else if (strpos($e->getMessage(), 'email')) {
                header("Location: index.php?error=email_exists");
            } else {
                header("Location: index.php?error=duplicate_entry");
            }
            exit();
        }
        die("Error database: " . $e->getMessage());
    }
}
?>