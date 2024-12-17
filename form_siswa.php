<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Form Pendaftaran Siswa</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        #errorMessage {
            display: none;
            margin-top: 10px;
            padding: 10px;
            border-radius: 4px;
            color: #721c24;
            background-color: #f8d7da;
            border: 1px solid #f5c6cb;
        }
        .preview-image {
            max-width: 200px;
            max-height: 200px;
            margin-top: 10px;
        }
    </style>
</head>
<body>
    <div class="container mt-5">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2>Form Pendaftaran Siswa</h2>
            <div>
                <a href="data_siswa.php" class="btn btn-primary me-2">Lihat Data Siswa</a>
                <a href="index.php" class="btn btn-secondary">Kembali ke Menu Utama</a>
            </div>
        </div>

        <div class="card">
            <div class="card-body">
                <div id="errorMessage"></div>

                <form id="formSiswa" action="proses_tambah.php" method="POST" onsubmit="return validateForm()" enctype="multipart/form-data">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="nisn" class="form-label">NISN</label>
                            <input type="text" class="form-control" id="nisn" name="nisn" required>
                        </div>
                        <div class="col-md-6">
                            <label for="nama_lengkap" class="form-label">Nama Lengkap</label>
                            <input type="text" class="form-control" id="nama_lengkap" name="nama_lengkap" required>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="jenis_kelamin" class="form-label">Jenis Kelamin</label>
                            <select class="form-select" id="jenis_kelamin" name="jenis_kelamin" required>
                                <option value="">Pilih Jenis Kelamin</option>
                                <option value="L">Laki-laki</option>
                                <option value="P">Perempuan</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label for="no_telp" class="form-label">Nomor Telepon</label>
                            <input type="tel" class="form-control" id="no_telp" name="no_telp" required>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="alamat" class="form-label">Alamat Lengkap</label>
                        <textarea class="form-control" id="alamat" name="alamat" rows="3" required></textarea>
                    </div>

                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" class="form-control" id="email" name="email" required>
                    </div>

                    <div class="mb-3">
                        <label for="foto" class="form-label">Foto Siswa</label>
                        <input type="file" class="form-control" id="foto" name="foto" accept="image/*" onchange="previewImage(this)">
                        <small class="text-muted">Format yang diizinkan: JPG, JPEG, PNG. Maksimal 2MB</small>
                        <div id="imagePreview"></div>
                    </div>

                    <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                        <button type="reset" class="btn btn-secondary me-md-2" onclick="clearImagePreview()">Reset</button>
                        <button type="submit" class="btn btn-primary">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function showError(message) {
            const errorDiv = document.getElementById('errorMessage');
            errorDiv.textContent = message;
            errorDiv.style.display = 'block';
            
            setTimeout(() => {
                errorDiv.style.display = 'none';
            }, 3000);
        }

        function validateForm() {
            const nisn = document.getElementById('nisn').value;
            const email = document.getElementById('email').value;
            const noTelp = document.getElementById('no_telp').value;
            const foto = document.getElementById('foto').files[0];
            let isValid = true;

            // Validasi NISN
            if (!/^\d{10}$/.test(nisn)) {
                showError('NISN harus 10 digit angka!');
                isValid = false;
            }

            // Validasi Email
            const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (!emailPattern.test(email)) {
                showError('Format email tidak valid!');
                isValid = false;
            }

            // Validasi Nomor Telepon
            if (!/^[0-9]{10,13}$/.test(noTelp)) {
                showError('Nomor telepon harus 10-13 digit angka!');
                isValid = false;
            }

            // Validasi Foto
            if (foto) {
                const fileSize = foto.size / 1024 / 1024; // dalam MB
                const validExtensions = ['jpg', 'jpeg', 'png'];
                const fileExtension = foto.name.split('.').pop().toLowerCase();

                if (!validExtensions.includes(fileExtension)) {
                    showError('Format file tidak valid! Gunakan JPG, JPEG, atau PNG.');
                    isValid = false;
                }

                if (fileSize > 2) {
                    showError('Ukuran file terlalu besar! Maksimal 2MB.');
                    isValid = false;
                }
            }

            return isValid;
        }

        function previewImage(input) {
            const preview = document.getElementById('imagePreview');
            preview.innerHTML = '';

            if (input.files && input.files[0]) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    const img = document.createElement('img');
                    img.src = e.target.result;
                    img.className = 'preview-image';
                    preview.appendChild(img);
                }
                reader.readAsDataURL(input.files[0]);
            }
        }

        function clearImagePreview() {
            document.getElementById('imagePreview').innerHTML = '';
            document.getElementById('errorMessage').style.display = 'none';
        }

        document.getElementById('formSiswa').addEventListener('reset', function() {
            document.getElementById('errorMessage').style.display = 'none';
            clearImagePreview();
        });
    </script>
</body>
</html>