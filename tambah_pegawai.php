<?php
require_once 'config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        // Validasi NIP (18-20 digit)
        if (!preg_match("/^[0-9]{18,20}$/", $_POST['nip'])) {
            header("Location: form_pegawai.php?error=nip_invalid");
            exit();
        }

        // Validasi email
        if (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
            header("Location: form_pegawai.php?error=email_invalid");
            exit();
        }

        // Validasi nomor telepon (10-15 digit)
        if (!preg_match("/^[0-9]{10,15}$/", $_POST['no_telp'])) {
            header("Location: form_pegawai.php?error=phone_invalid");
            exit();
        }

        $sql = "INSERT INTO pegawai (nip, nama_lengkap, jenis_kelamin, jabatan, alamat, no_telp, email, tanggal_masuk, status, created_at) 
                VALUES (:nip, :nama_lengkap, :jenis_kelamin, :jabatan, :alamat, :no_telp, :email, :tanggal_masuk, :status, CURRENT_TIMESTAMP)";
        
        $stmt = $pdo->prepare($sql);
        
        $stmt->bindParam(':nip', $_POST['nip']);
        $stmt->bindParam(':nama_lengkap', $_POST['nama_lengkap']);
        $stmt->bindParam(':jenis_kelamin', $_POST['jenis_kelamin']);
        $stmt->bindParam(':jabatan', $_POST['jabatan']);
        $stmt->bindParam(':alamat', $_POST['alamat']);
        $stmt->bindParam(':no_telp', $_POST['no_telp']);
        $stmt->bindParam(':email', $_POST['email']);
        $stmt->bindParam(':tanggal_masuk', $_POST['tanggal_masuk']);
        $stmt->bindParam(':status', $_POST['status']);
        
        $stmt->execute();
        
        header("Location: data_pegawai.php");
        exit();
        
    } catch (PDOException $e) {
        if ($e->errorInfo[1] === 1062) { // Duplicate entry error
            if (strpos($e->getMessage(), 'nip')) {
                header("Location: form_pegawai.php?error=nip_exists");
            } else if (strpos($e->getMessage(), 'email')) {
                header("Location: form_pegawai.php?error=email_exists");
            } else {
                header("Location: form_pegawai.php?error=duplicate_entry");
            }
            exit();
        }
        die("Error database: " . $e->getMessage());
    }
}
?>