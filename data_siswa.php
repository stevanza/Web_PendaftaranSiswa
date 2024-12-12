<?php
require_once 'config.php';

try {
    $sql = "SELECT * FROM siswa ORDER BY nama_lengkap ASC";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $siswa_list = $stmt->fetchAll();
} catch(PDOException $e) {
    die("Error: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Siswa</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.1/font/bootstrap-icons.css">
</head>
<body>
    <div class="container mt-5">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2>Data Siswa</h2>
            <a href="index.php" class="btn btn-primary">
                <i class="bi bi-plus-circle"></i> Tambah Siswa
            </a>
        </div>

        <?php if ($stmt->rowCount() > 0): ?>
            <div class="table-responsive">
                <table class="table table-striped table-hover">
                    <thead class="table-dark">
                        <tr>
                            <th>No.</th>
                            <th>NISN</th>
                            <th>Nama Lengkap</th>
                            <th>Jenis Kelamin</th>
                            <th>Alamat</th>
                            <th>No. Telepon</th>
                            <th>Email</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        $no = 1;
                        foreach($siswa_list as $siswa): 
                        ?>
                        <tr>
                            <td><?= $no++ ?></td>
                            <td><?= htmlspecialchars($siswa['nisn']) ?></td>
                            <td><?= htmlspecialchars($siswa['nama_lengkap']) ?></td>
                            <td><?= $siswa['jenis_kelamin'] == 'L' ? 'Laki-laki' : 'Perempuan' ?></td>
                            <td><?= htmlspecialchars($siswa['alamat']) ?></td>
                            <td><?= htmlspecialchars($siswa['no_telp']) ?></td>
                            <td><?= htmlspecialchars($siswa['email']) ?></td>
                            <td>
                                <div class="btn-group" role="group">
                                    <a href="edit.php?id=<?= $siswa['id'] ?>" class="btn btn-warning btn-sm" title="Edit">
                                        <i class="bi bi-pencil-square"></i>
                                    </a>
                                    <button type="button" class="btn btn-danger btn-sm" title="Hapus" 
                                            onclick="confirmDelete(<?= $siswa['id'] ?>, '<?= htmlspecialchars($siswa['nama_lengkap']) ?>')">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php else: ?>
            <div class="alert alert-info">
                Belum ada data siswa yang terdaftar.
            </div>
        <?php endif; ?>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
    function confirmDelete(id, nama) {
        if (confirm(`Apakah Anda yakin ingin menghapus data siswa ${nama}?`)) {
            window.location.href = `hapus.php?id=${id}`;
        }
    }
    </script>
</body>
</html>