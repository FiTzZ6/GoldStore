<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="{{ asset('css/barang/cucisepuh/formpenyimpanan.css') }}">
</head>
<body>
    <div class="container">
        
        <div class="main-content">
            <div class="card">
                <div class="card-header">
                    <h2 class="card-title">Data Barang</h2>
                </div>

                <div class="form-group">
                    <label for="toko">PILIH TOKO</label>
                    <select id="toko">
                        <option value="">-- Pilih Toko --</option>
                        <option value="toko1">Jakarta</option>
                        <option value="toko2">Bandung</option>
                        <option value="toko3">Tangerang</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="tanggal">Tanggal Kirim</label>
                    <input type="date" id="tanggal" value="2025-08-28">
                </div>

                <div class="form-group">
                    <label for="No-Surat">Nomor Surat</label>
                    <input type="text" id="No-Surat" placeholder="No.Surat">
                </div>

                <div class="form-group">
                    <label for="nama-barang">Nama Barang</label>
                    <input type="text" id="nama-barang" placeholder="Masukkan nama barang">
                </div>
                
                <div class="form-group">
                    <label for="spesifikasi">Spesifikasi Barang</label>
                    <input type="text" id="spesifikasi" placeholder="Masukkan spesifikasi barang">
                </div>
                
                <div class="form-group">
                    <label for="Note">Note</label>
                    <textarea id="Note" placeholder="Deskripsi Note"></textarea>
                </div>
            </div>
            
            <div class="card">
                <div class="card-header">
                    <h2 class="card-title">Tindak Lanjut</h2>
                </div>
                
                
                <div class="form-group">
                    <label for="tindakan">Tindak Lanjut</label>
                    <input type="text" id="tindakan" placeholder="Jenis tindak lanjut">
                </div>
                
                <div class="form-group">
                    <label for="keterangan-tindakan">Keterangan Tindak Lanjut</label>
                    <textarea id="keterangan-tindakan" placeholder="Penjelasan tindak lanjut"></textarea>
                </div>
                
                <div class="form-group">
                    <label for="keterangan">Keterangan</label>
                    <textarea id="keterangan" placeholder="Keterangan tambahan"></textarea>
                </div>
                
                <div class="card">
                <div class="card-header">
                    <h2 class="card-title">Lampiran File</h2>
                </div> 
                <div class="file-upload" id="file-upload-area">
                    <div class="file-icon">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" viewBox="0 0 16 16">
                            <path d="M.5 9.9a.5.5 0 0 1 .5.5v2.5a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1v-2.5a.5.5 0 0 1 1 0v2.5a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2v-2.5a.5.5 0 0 1 .5-.5z"/>
                            <path d="M7.646 1.146a.5.5 0 0 1 .708 0l3 3a.5.5 0 0 1-.708.708L8.5 2.707V11.5a.5.5 0 0 1-1 0V2.707L5.354 4.854a.5.5 0 1 1-.708-.708l3-3z"/>
                        </svg>
                    </div>
                    <div class="file-text">Klik untuk mengunggah file atau drop file di sini</div>
                    <input type="file" style="display: none;" id="file-input">
                </div>
            </div>

                <div class="action-buttons">
                    <button class="btn btn-primary">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                            <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14zm0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16z"/>
                            <path d="M8 4a.5.5 0 0 1 .5.5v3h3a.5.5 0 0 1 0 1h-3v3a.5.5 0 0 1-1 0v-3h-3a.5.5 0 0 1 0-1h3v-3A.5.5 0 0 1 8 4z"/>
                        </svg>
                        Simpan Data
                    </button>
                    <button class="btn btn-outline">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                            <path d="M5 1a2 2 0 0 0-2 2v1h10V3a2 2 0 0 0-2-2H5zm6 8H5a1 1 0 0 0-1 1v3a1 1 0 0 0 1 1h6a1 1 0 0 0 1-1v-3a1 1 0 0 0-1-1z"/>
                            <path d="M0 7a2 2 0 0 1 2-2h12a2 2 0 0 1 2 2v3a2 2 0 0 1-2 2h-1v-2a2 2 0 0 0-2-2H5a2 2 0 0 0-2 2v2H2a2 2 0 0 1-2-2V7zm2.5 1a.5.5 0 1 0 0-1 .5.5 0 0 0 0 1z"/>
                        </svg>
                        Reset Form
                    </button>
                </div>
            </div>
            
        </div>
        
    </div>
    
    <script>
        // Set current date
        const now = new Date();
        const options = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' };
        document.getElementById('current-date').textContent = now.toLocaleDateString('id-ID', options);
        
        // File upload functionality
        const fileUploadArea = document.getElementById('file-upload-area');
        const fileInput = document.getElementById('file-input');
        
        fileUploadArea.addEventListener('click', () => {
            fileInput.click();
        });
        
        fileUploadArea.addEventListener('dragover', (e) => {
            e.preventDefault();
            fileUploadArea.style.borderColor = '#2563eb';
            fileUploadArea.style.backgroundColor = 'rgba(37, 99, 235, 0.05)';
        });
        
        fileUploadArea.addEventListener('dragleave', () => {
            fileUploadArea.style.borderColor = '#e2e8f0';
            fileUploadArea.style.backgroundColor = 'transparent';
        });
        
        fileUploadArea.addEventListener('drop', (e) => {
            e.preventDefault();
            fileUploadArea.style.borderColor = '#e2e8f0';
            fileUploadArea.style.backgroundColor = 'transparent';
            
            if (e.dataTransfer.files.length) {
                fileInput.files = e.dataTransfer.files;
                alert('File siap diunggah: ' + e.dataTransfer.files[0].name);
            }
        });
    </script>
</body>
</html>