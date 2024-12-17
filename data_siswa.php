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
    <style>
        .student-photo {
            width: 50px;
            height: 50px;
            object-fit: cover;
            border-radius: 50%;
            border: 2px solid #dee2e6;
            cursor: pointer;
            transition: transform 0.2s;
        }

        .student-photo:hover {
            transform: scale(1.1);
        }

        .no-photo {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            background-color: #e9ecef;
            border: 2px solid #dee2e6;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #6c757d;
        }

        .photo-modal-image {
            max-width: 100%;
            max-height: 80vh;
            object-fit: contain;
        }

        .table-responsive {
            border-radius: 8px;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.1);
        }

        .table {
            margin-bottom: 0;
        }

        .btn-group .btn {
            padding: 0.25rem 0.5rem;
        }

        .alert {
            border-radius: 8px;
        }
    </style>
</head>
<body>
    <div class="container mt-5">
        <?php if (isset($_GET['status'])): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <?php
                switch ($_GET['status']) {
                    case 'success':
                        echo 'Data siswa berhasil disimpan.';
                        break;
                    case 'updated':
                        echo 'Data siswa berhasil diperbarui.';
                        break;
                    case 'deleted':
                        echo 'Data siswa berhasil dihapus.';
                        break;
                }
                ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>

        <!-- In data_siswa.php, find the div with class "d-flex justify-content-between align-items-center mb-4" -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2>Data Siswa</h2>
            <div>
            <a href="cetak_siswa.php" class="btn btn-info me-2">
                <i class="bi bi-printer"></i> Cetak PDF
            </a>
                <a href="form_siswa.php" class="btn btn-primary">
                    <i class="bi bi-plus-circle"></i> Tambah Siswa
                </a>
            </div>
        </div>

        <?php if ($stmt->rowCount() > 0): ?>
            <div class="table-responsive">
                <table class="table table-striped table-hover">
                    <thead class="table-dark">
                        <tr>
                            <th>No.</th>
                            <th>Foto</th>
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
                            <td>
                                <?php if ($siswa['foto'] && file_exists(__DIR__ . DIRECTORY_SEPARATOR . 'uploads' . DIRECTORY_SEPARATOR . $siswa['foto'])): ?>
                                    <img src="uploads/<?= htmlspecialchars($siswa['foto']) ?>" 
                                         class="student-photo" 
                                         alt="Foto <?= htmlspecialchars($siswa['nama_lengkap']) ?>"
                                         onclick="showPhotoModal('<?= htmlspecialchars($siswa['foto']) ?>', '<?= htmlspecialchars($siswa['nama_lengkap']) ?>')"
                                         onerror="this.onerror=null; this.parentElement.innerHTML='<div class=\'no-photo\'><i class=\'bi bi-person\'></i></div>';">
                                <?php else: ?>
                                    <div class="no-photo">
                                        <i class="bi bi-person"></i>
                                    </div>
                                <?php endif; ?>
                            </td>
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

        <div class="mt-3">
            <a href="index.php" class="btn btn-secondary">
                <i class="bi bi-arrow-left"></i> Kembali ke Menu Utama
            </a>
        </div>
    </div>

    <!-- Modal untuk menampilkan foto full size -->
    <div class="modal fade" id="photoModal" tabindex="-1" aria-labelledby="photoModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="photoModalLabel">Foto Siswa</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body text-center">
                    <img id="modalImage" src="" alt="Foto Siswa" class="photo-modal-image">
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
    function confirmDelete(id, nama) {
        if (confirm(`Apakah Anda yakin ingin menghapus data siswa ${nama}?`)) {
            window.location.href = `hapus.php?id=${id}`;
        }
    }

    function showPhotoModal(foto, nama) {
        const modal = new bootstrap.Modal(document.getElementById('photoModal'));
        const modalImage = document.getElementById('modalImage');
        const modalTitle = document.getElementById('photoModalLabel');
        
        modalImage.src = `uploads/${foto}`;
        modalImage.onerror = function() {
            this.parentElement.innerHTML = '<div class="no-photo"><i class="bi bi-person"></i></div>';
        };
        modalTitle.textContent = `Foto ${nama}`;
        modal.show();
    }
    </script>
</body>
</html> 