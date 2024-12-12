<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistem Informasi Sekolah</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.1/font/bootstrap-icons.css">
    <style>
        .feature-card {
            transition: transform 0.3s;
        }
        .feature-card:hover {
            transform: translateY(-5px);
        }
    </style>
</head>
<body>
    <div class="container mt-5">
        <h1 class="text-center mb-5">Sistem Informasi Sekolah</h1>
        
        <div class="row justify-content-center g-4">
            <!-- Kartu Menu Siswa -->
            <div class="col-md-6 col-lg-4">
                <div class="card feature-card h-100">
                    <div class="card-body text-center">
                        <i class="bi bi-mortarboard fs-1 text-primary mb-3"></i>
                        <h3 class="card-title">Manajemen Siswa</h3>
                        <p class="card-text">Kelola data siswa termasuk pendaftaran, edit, dan hapus data siswa.</p>
                        <div class="d-grid gap-2">
                            <a href="form_siswa.php" class="btn btn-primary">
                                <i class="bi bi-plus-circle"></i> Tambah Siswa
                            </a>
                            <a href="data_siswa.php" class="btn btn-outline-primary">
                                <i class="bi bi-table"></i> Lihat Data Siswa
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Kartu Menu Pegawai -->
            <div class="col-md-6 col-lg-4">
                <div class="card feature-card h-100">
                    <div class="card-body text-center">
                        <i class="bi bi-people fs-1 text-success mb-3"></i>
                        <h3 class="card-title">Manajemen Pegawai</h3>
                        <p class="card-text">Kelola data pegawai termasuk pendaftaran, edit, dan hapus data pegawai.</p>
                        <div class="d-grid gap-2">
                            <a href="form_pegawai.php" class="btn btn-success">
                                <i class="bi bi-plus-circle"></i> Tambah Pegawai
                            </a>
                            <a href="data_pegawai.php" class="btn btn-outline-success">
                                <i class="bi bi-table"></i> Lihat Data Pegawai
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>