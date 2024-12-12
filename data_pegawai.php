<?php
require_once 'config.php';

try {
    $sql = "SELECT * FROM pegawai ORDER BY nama_lengkap ASC";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $pegawai_list = $stmt->fetchAll();
} catch(PDOException $e) {
    die("Error: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Pegawai</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.1/font/bootstrap-icons.css">
</head>
<body>
    <div class="container mt-5">
        <?php
        if (isset($_GET['status'])) {
            if ($_GET['status'] == 'deleted') {
                echo '<div class="alert alert-success alert-dismissible fade show" role="alert">
                        Data pegawai berhasil dihapus.
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                      </div>';
            } else if ($_GET['status'] == 'updated') {
                echo '<div class="alert alert-success alert-dismissible fade show" role="alert">
                        Data pegawai berhasil diperbarui.
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                      </div>';
            }
        }
        ?>
        
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2>Data Pegawai</h2>
            <a href="form_pegawai.php" class="btn btn-success">
                <i class="bi bi-plus-circle"></i> Tambah Pegawai
            </a>
        </div>

        <?php if ($stmt->rowCount() > 0): ?>
            <div class="table-responsive">
                <table class="table table-striped table-hover">
                    <thead class="table-dark">
                        <tr>
                            <th>No.</th>
                            <th>NIP</th>
                            <th>Nama Lengkap</th>
                            <th>Jabatan</th>
                            <th>Jenis Kelamin</th>
                            <th>Alamat</th>
                            <th>No. Telepon</th>
                            <th>Email</th>
                            <th>Tanggal Masuk</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        $no = 1;
                        foreach($pegawai_list as $pegawai): 
                        ?>
                        <tr>
                            <td><?= $no++ ?></td>
                            <td><?= htmlspecialchars($pegawai['nip']) ?></td>
                            <td><?= htmlspecialchars($pegawai['nama_lengkap']) ?></td>
                            <td><?= htmlspecialchars($pegawai['jabatan']) ?></td>
                            <td><?= $pegawai['jenis_kelamin'] == 'L' ? 'Laki-laki' : 'Perempuan' ?></td>
                            <td><?= htmlspecialchars($pegawai['alamat']) ?></td>
                            <td><?= htmlspecialchars($pegawai['no_telp']) ?></td>
                            <td><?= htmlspecialchars($pegawai['email']) ?></td>
                            <td><?= htmlspecialchars($pegawai['tanggal_masuk']) ?></td>
                            <td>
                                <span class="badge <?= $pegawai['status'] == 'aktif' ? 'bg-success' : 'bg-danger' ?>">
                                    <?= ucfirst($pegawai['status']) ?>
                                </span>
                            </td>
                            <td>
                                <div class="btn-group" role="group">
                                    <a href="edit_pegawai.php?id=<?= $pegawai['id'] ?>" class="btn btn-warning btn-sm" title="Edit">
                                        <i class="bi bi-pencil-square"></i>
                                    </a>
                                    <button type="button" class="btn btn-danger btn-sm" title="Hapus" 
                                            onclick="confirmDelete(<?= $pegawai['id'] ?>, '<?= htmlspecialchars($pegawai['nama_lengkap']) ?>')">
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
                Belum ada data pegawai yang terdaftar.
            </div>
        <?php endif; ?>

        <div class="mt-3">
            <a href="index.php" class="btn btn-secondary">
                <i class="bi bi-arrow-left"></i> Kembali ke Menu Utama
            </a>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
    function confirmDelete(id, nama) {
        if (confirm(`Apakah Anda yakin ingin menghapus data pegawai ${nama}?`)) {
            window.location.href = `hapus_pegawai.php?id=${id}`;
        }
    }
    </script>
</body>
</html>