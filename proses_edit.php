<?php
require_once 'config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $id = $_POST['id'];
        $foto_lama = $_POST['foto_lama'];
        $foto_path = $foto_lama; // Default ke foto yang sudah ada

        // Tambahkan sebelum proses upload file
        if (!file_exists('uploads')) {
            if (!mkdir('uploads', 0777)) {
                throw new Exception("Gagal membuat direktori uploads");
            }
        }

        // Cek jika ada upload foto baru
        if (isset($_FILES['foto']) && $_FILES['foto']['error'] == 0) {
            $allowed = ['jpg', 'jpeg', 'png'];
            $filename = $_FILES['foto']['name'];
            $file_ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));

            // Validasi ekstensi file
            if (!in_array($file_ext, $allowed)) {
                throw new Exception("Format file tidak valid! Gunakan JPG, JPEG, atau PNG.");
            }

            // Validasi ukuran file (max 2MB)
            if ($_FILES['foto']['size'] > 2 * 1024 * 1024) {
                throw new Exception("Ukuran file terlalu besar! Maksimal 2MB.");
            }

            // Generate unique filename
            $new_filename = uniqid() . '.' . $file_ext;
            $upload_path = 'uploads/' . $new_filename;

            // Pindahkan file
            if (move_uploaded_file($_FILES['foto']['tmp_name'], $upload_path)) {
                // Hapus foto lama jika ada
                if ($foto_lama && file_exists('uploads/' . $foto_lama)) {
                    unlink('uploads/' . $foto_lama);
                }
                $foto_path = $new_filename;
            } else {
                throw new Exception("Gagal mengupload foto");
            }
        }

        // Update data siswa
        $sql = "UPDATE siswa SET 
                nisn = :nisn,
                nama_lengkap = :nama_lengkap,
                jenis_kelamin = :jenis_kelamin,
                alamat = :alamat,
                no_telp = :no_telp,
                email = :email,
                foto = :foto
                WHERE id = :id";

        $stmt = $pdo->prepare($sql);
        
        $stmt->execute([
            ':id' => $id,
            ':nisn' => $_POST['nisn'],
            ':nama_lengkap' => $_POST['nama_lengkap'],
            ':jenis_kelamin' => $_POST['jenis_kelamin'],
            ':alamat' => $_POST['alamat'],
            ':no_telp' => $_POST['no_telp'],
            ':email' => $_POST['email'],
            ':foto' => $foto_path
        ]);

        header("Location: data_siswa.php?status=updated");
        exit();

    } catch (Exception $e) {
        // Jika ada error dan foto baru sudah diupload, hapus foto tersebut
        if (isset($new_filename) && file_exists('uploads/' . $new_filename)) {
            unlink('uploads/' . $new_filename);
        }
        header("Location: edit.php?id=$id&error=" . urlencode($e->getMessage()));
        exit();
    }
}
?>