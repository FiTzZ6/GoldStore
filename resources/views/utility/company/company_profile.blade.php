<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="{{ asset('css/utility/company/company_profile.css') }}">
    <link href="https://fonts.googleapis.com/css?family=Montserrat:400,600,700&display=swap" rel="stylesheet">
    <title>Halaman Utility</title>
</head>
<body>
    @include('partials.navbar')
    <div class="form-container">
        <form action="{{ route('utility.company.set_up_company') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="form-grid">
                <!-- Kolom Kiri -->
                <div class="form-left">
                    <div class="form-group">
                        <label>Nama Perusahaan</label>
                        <input type="text" name="nama" value="{{ old('nama', 'PT. ELING SAMBAS GRUP') }}">
                    </div>
                    <div class="form-group">
                        <label>Alamat</label>
                        <input type="text" name="alamat" value="Purbalingga">
                    </div>
                    <div class="form-group">
                        <label>Kota</label>
                        <input type="text" name="kota" value="Purbalingga">
                    </div>
                    <div class="form-group">
                        <label>Telp.</label>
                        <input type="text" name="kontak">
                    </div>
                </div>

                <!-- Kolom Kanan -->
                <div class="form-right">
                    <div class="logo-preview">
                        <label>Logo</label>
                        <img src="{{ asset('img/logo esgpbg.png') }}" alt="Logo Perusahaan">
                    </div>
                    <div class="form-group file-upload">
                        <label>Gambar</label>
                        <input type="file" name="logo" id="file-upload">
                    </div>
                </div>
            </div>

            <div class="form-actions">
                <button type="button" class="btn btn-cancel">CANCEL</button>
                <button type="submit" class="btn btn-save">SIMPAN</button>
            </div>
        </form>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const fileInput = document.getElementById('file-upload');
            const fileNameDisplay = document.createElement('span');
            fileNameDisplay.className = 'file-name';
            
            // Cari tempat yang tepat untuk menampilkan nama file
            const fileUploadContainer = fileInput.parentElement;
            const fileInfo = fileUploadContainer.querySelector('.file-info');
            
            // Sisipkan elemen untuk menampilkan nama file setelah info
            if (fileInfo) {
                fileInfo.parentNode.insertBefore(fileNameDisplay, fileInfo.nextSibling);
            } else {
                fileUploadContainer.appendChild(fileNameDisplay);
            }
            
            // Event listener untuk perubahan file
            fileInput.addEventListener('change', function() {
                if (this.files && this.files.length > 0) {
                    fileNameDisplay.textContent = this.files[0].name;
                } else {
                    fileNameDisplay.textContent = '';
                }
            });
        });
    </script>
</body>
</html>

