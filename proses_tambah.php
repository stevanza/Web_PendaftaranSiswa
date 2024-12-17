<?php
require_once 'config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        // Validasi NISN (10 digit)
        if (!preg_match("/^[0-9]{10}$/", $_POST['nisn'])) {
            throw new Exception("NISN harus 10 digit angka");
        }

        // Validasi email
        if (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
            throw new Exception("Format email tidak valid");
        }

        // Validasi nomor telepon (10-13 digit)
        if (!preg_match("/^[0-9]{10,13}$/", $_POST['no_telp'])) {
            throw new Exception("Nomor telepon harus 10-13 digit angka");
        }

        // Tambahkan sebelum proses upload file
        if (!file_exists('uploads')) {
            if (!mkdir('uploads', 0777)) {
                throw new Exception("Gagal membuat direktori uploads");
            }
        }

        // Handle foto upload
        $foto_path = null;
        if (isset($_FILES['foto']) && $_FILES['foto']['error'] == 0) {
            $allowed = ['jpg', 'jpeg', 'png'];
            $filename = $_FILES['foto']['name'];
            $file_ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
            $filesize = $_FILES['foto']['size'];

            // Validasi ekstensi file
            if (!in_array($file_ext, $allowed)) {
                throw new Exception("Format file tidak valid! Gunakan JPG, JPEG, atau PNG.");
            }

            // Validasi ukuran file (max 2MB)
            if ($filesize > 2 * 1024 * 1024) {
                throw new Exception("Ukuran file terlalu besar! Maksimal 2MB.");
            }

            // Generate unique filename
            $new_filename = uniqid('IMG_') . '.' . $file_ext;
            $upload_path = 'uploads/' . $new_filename;

            // Buat direktori uploads jika belum ada
            if (!file_exists('uploads')) {
                mkdir('uploads', 0777, true);
            }

            // Pindahkan file
            if (!move_uploaded_file($_FILES['foto']['tmp_name'], $upload_path)) {
                throw new Exception("Gagal mengupload foto");
            }
            $foto_path = $new_filename;
        }

        // Insert data ke database
        $sql = "INSERT INTO siswa (nisn, nama_lengkap, jenis_kelamin, alamat, no_telp, email, foto) 
                VALUES (:nisn, :nama_lengkap, :jenis_kelamin, :alamat, :no_telp, :email, :foto)";
        
        $stmt = $pdo->prepare($sql);
        
        $stmt->execute([
            ':nisn' => $_POST['nisn'],
            ':nama_lengkap' => $_POST['nama_lengkap'],
            ':jenis_kelamin' => $_POST['jenis_kelamin'],
            ':alamat' => $_POST['alamat'],
            ':no_telp' => $_POST['no_telp'],
            ':email' => $_POST['email'],
            ':foto' => $foto_path
        ]);
        
        header("Location: data_siswa.php?status=success");
        exit();
        
    } catch (PDOException $e) {
        // Hapus foto yang sudah diupload jika ada error
        if (isset($foto_path) && file_exists('uploads/' . $foto_path)) {
            unlink('uploads/' . $foto_path);
        }

        if ($e->errorInfo[1] === 1062) { // Duplicate entry error
            if (strpos($e->getMessage(), 'nisn')) {
                $error = "NISN sudah terdaftar";
            } elseif (strpos($e->getMessage(), 'email')) {
                $error = "Email sudah terdaftar";
            } else {
                $error = "Data duplikat ditemukan";
            }
        } else {
            $error = "Terjadi kesalahan database: " . $e->getMessage();
        }
        
        header("Location: form_siswa.php?error=" . urlencode($error));
        exit();
    } catch (Exception $e) {
        // Hapus foto yang sudah diupload jika ada error
        if (isset($foto_path) && file_exists('uploads/' . $foto_path)) {
            unlink('uploads/' . $foto_path);
        }
        
        header("Location: form_siswa.php?error=" . urlencode($e->getMessage()));
        exit();
    }
}
?>