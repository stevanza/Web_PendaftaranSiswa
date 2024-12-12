<?php
require_once 'config.php';

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    
    try {
        // Cek apakah data siswa ada
        $check_sql = "SELECT id FROM siswa WHERE id = :id";
        $check_stmt = $pdo->prepare($check_sql);
        $check_stmt->bindParam(':id', $id);
        $check_stmt->execute();
        
        if ($check_stmt->rowCount() > 0) {
            // Hapus data siswa
            $delete_sql = "DELETE FROM siswa WHERE id = :id";
            $delete_stmt = $pdo->prepare($delete_sql);
            $delete_stmt->bindParam(':id', $id);
            $delete_stmt->execute();
            
            // Redirect ke halaman index setelah berhasil hapus
            header("Location: index.php");
            exit();
        } else {
            // Jika data tidak ditemukan
            die("Data siswa tidak ditemukan");
        }
    } catch (PDOException $e) {
        die("Error: " . $e->getMessage());
    }
} else {
    // Jika tidak ada ID yang diberikan
    header("Location: index.php");
    exit();
}
?>