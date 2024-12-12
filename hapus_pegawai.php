<?php
require_once 'config.php';

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    
    try {
        // Cek apakah data pegawai ada
        $check_sql = "SELECT id FROM pegawai WHERE id = :id";
        $check_stmt = $pdo->prepare($check_sql);
        $check_stmt->bindParam(':id', $id);
        $check_stmt->execute();
        
        if ($check_stmt->rowCount() > 0) {
            // Hapus data pegawai
            $delete_sql = "DELETE FROM pegawai WHERE id = :id";
            $delete_stmt = $pdo->prepare($delete_sql);
            $delete_stmt->bindParam(':id', $id);
            $delete_stmt->execute();
            
            header("Location: data_pegawai.php?status=deleted");
            exit();
        } else {
            die("Data pegawai tidak ditemukan");
        }
    } catch(PDOException $e) {
        die("Error: " . $e->getMessage());
    }
} else {
    header("Location: data_pegawai.php");
    exit();
}
?>