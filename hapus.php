<?php
require_once 'config.php';

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    
    try {
        // Ambil informasi foto sebelum menghapus data
        $check_sql = "SELECT foto FROM siswa WHERE id = :id";
        $check_stmt = $pdo->prepare($check_sql);
        $check_stmt->bindParam(':id', $id);
        $check_stmt->execute();
        
        if ($check_stmt->rowCount() > 0) {
            $siswa = $check_stmt->fetch();
            
            // Hapus file foto jika ada
            if ($siswa['foto'] && file_exists('uploads/' . $siswa['foto'])) {
                unlink('uploads/' . $siswa['foto']);
            }
            
            // Hapus data siswa
            $delete_sql = "DELETE FROM siswa WHERE id = :id";
            $delete_stmt = $pdo->prepare($delete_sql);
            $delete_stmt->bindParam(':id', $id);
            $delete_stmt->execute();
            
            header("Location: data_siswa.php?status=deleted");
            exit();
        } else {
            die("Data siswa tidak ditemukan");
        }
    } catch (PDOException $e) {
        die("Error: " . $e->getMessage());
    }
} else {
    header("Location: data_siswa.php");
    exit();
}
?>