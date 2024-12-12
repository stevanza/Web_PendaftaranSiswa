<?php
// proses_tambah_pegawai.php
require_once 'config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        // Validasi input
        $nip = $_POST['nip'];
        $nama_lengkap = $_POST['nama_lengkap'];
        $jenis_kelamin = $_POST['jenis_kelamin'];
        $jabatan = $_POST['jabatan'];
        $alamat = $_POST['alamat'];
        $no_telp = $_POST['no_telp'];
        $email = $_POST['email'];
        $tanggal_masuk = $_POST['tanggal_masuk'];
        $status = $_POST['status'];

        // Validasi NIP
        if (!preg_match("/^[0-9]{18,20}$/", $nip)) {
            throw new Exception("NIP harus 18-20 digit angka");
        }

        // Validasi email
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new Exception("Format email tidak valid");
        }

        // Validasi nomor telepon
        if (!preg_match("/^[0-9]{10,15}$/", $no_telp)) {
            throw new Exception("Nomor telepon harus 10-15 digit");
        }

        // Simpan ke database
        $sql = "INSERT INTO pegawai (nip, nama_lengkap, jenis_kelamin, jabatan, alamat, no_telp, email, tanggal_masuk, status) 
                VALUES (:nip, :nama_lengkap, :jenis_kelamin, :jabatan, :alamat, :no_telp, :email, :tanggal_masuk, :status)";
        
        $stmt = $pdo->prepare($sql);
        
        $stmt->execute([
            ':nip' => $nip,
            ':nama_lengkap' => $nama_lengkap,
            ':jenis_kelamin' => $jenis_kelamin,
            ':jabatan' => $jabatan,
            ':alamat' => $alamat,
            ':no_telp' => $no_telp,
            ':email' => $email,
            ':tanggal_masuk' => $tanggal_masuk,
            ':status' => $status
        ]);

        // Redirect ke halaman data pegawai dengan pesan sukses
        header("Location: data_pegawai.php?status=success");
        exit();

    } catch (PDOException $e) {
        if ($e->errorInfo[1] === 1062) { // Duplicate entry error
            if (strpos($e->getMessage(), 'nip')) {
                $error = "NIP sudah terdaftar";
            } else if (strpos($e->getMessage(), 'email')) {
                $error = "Email sudah terdaftar";
            } else {
                $error = "Data duplikat ditemukan";
            }
        } else {
            $error = "Terjadi kesalahan database";
        }
        header("Location: form_pegawai.php?error=" . urlencode($error));
        exit();
    } catch (Exception $e) {
        header("Location: form_pegawai.php?error=" . urlencode($e->getMessage()));
        exit();
    }
}

// Update data pegawai
if (isset($_POST['update'])) {
    try {
        $id = $_POST['id'];
        $nip = $_POST['nip'];
        $nama_lengkap = $_POST['nama_lengkap'];
        $jenis_kelamin = $_POST['jenis_kelamin'];
        $jabatan = $_POST['jabatan'];
        $alamat = $_POST['alamat'];
        $no_telp = $_POST['no_telp'];
        $email = $_POST['email'];
        $tanggal_masuk = $_POST['tanggal_masuk'];
        $status = $_POST['status'];

        // Validasi input sama seperti di atas

        $sql = "UPDATE pegawai SET 
                nip = :nip,
                nama_lengkap = :nama_lengkap,
                jenis_kelamin = :jenis_kelamin,
                jabatan = :jabatan,
                alamat = :alamat,
                no_telp = :no_telp,
                email = :email,
                tanggal_masuk = :tanggal_masuk,
                status = :status
                WHERE id = :id";

        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ':id' => $id,
            ':nip' => $nip,
            ':nama_lengkap' => $nama_lengkap,
            ':jenis_kelamin' => $jenis_kelamin,
            ':jabatan' => $jabatan,
            ':alamat' => $alamat,
            ':no_telp' => $no_telp,
            ':email' => $email,
            ':tanggal_masuk' => $tanggal_masuk,
            ':status' => $status
        ]);

        header("Location: data_pegawai.php?status=updated");
        exit();

    } catch (Exception $e) {
        header("Location: edit_pegawai.php?id=$id&error=" . urlencode($e->getMessage()));
        exit();
    }
}

// Hapus data pegawai
if (isset($_GET['delete'])) {
    try {
        $id = $_GET['id'];
        
        $sql = "DELETE FROM pegawai WHERE id = :id";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([':id' => $id]);

        header("Location: data_pegawai.php?status=deleted");
        exit();

    } catch (Exception $e) {
        header("Location: data_pegawai.php?error=" . urlencode($e->getMessage()));
        exit();
    }
}

// Ambil semua data pegawai
function getAllPegawai($pdo) {
    try {
        $stmt = $pdo->query("SELECT * FROM pegawai ORDER BY nama_lengkap ASC");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        throw new Exception("Error mengambil data pegawai: " . $e->getMessage());
    }
}

// Ambil data pegawai berdasarkan ID
function getPegawaiById($pdo, $id) {
    try {
        $stmt = $pdo->prepare("SELECT * FROM pegawai WHERE id = :id");
        $stmt->execute([':id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        throw new Exception("Error mengambil data pegawai: " . $e->getMessage());
    }
}
?>