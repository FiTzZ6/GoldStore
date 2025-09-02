<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>QR Code Direktur - Sistem Manajemen</title>
    <link rel="stylesheet" href="{{ asset('css/utility/setting/qr-direktur.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
@include('partials.navbar')
    <div class="container">
        <!-- Header -->
        <div class="header">
            <h1><i class="fas fa-qrcode"></i> QR Code Direktur Operasional</h1>
            <p>Kelola informasi dan QR code direktur operasional</p>
        </div>

        <!-- Main Content -->
        <div class="content-card">
            <form class="qr-form" id="direkturForm">
                <div class="form-container">
                    <!-- Informasi Direktur -->
                    <div class="info-section">
                        <div class="section-header">
                            <i class="fas fa-user-tie"></i>
                            <h2>Informasi Direktur</h2>
                        </div>
                        
                        <div class="form-group">
                            <label for="namaDirektur">
                                <i class="fas fa-signature"></i>
                                Nama Direktur
                            </label>
                            <input type="text" id="namaDirektur" name="nama" class="form-control" 
                                   value="Muhammad Sodik" placeholder="Masukkan nama direktur" required>
                        </div>

                        <div class="form-group">
                            <label for="jabatan">
                                <i class="fas fa-briefcase"></i>
                                Jabatan
                            </label>
                            <input type="text" id="jabatan" name="jabatan" class="form-control" 
                                   value="Direktur Operasional" readonly>
                            <span class="readonly-note">Jabatan tidak dapat diubah</span>
                        </div>
                    </div>

                    <!-- QR Code Section -->
                    <div class="qr-section">
                        <div class="section-header">
                            <i class="fas fa-qrcode"></i>
                            <h2>QR Code</h2>
                        </div>
                        
                        <div class="qr-container">
                            <div class="qr-display">
                                <img src="assets/img/barcode/MuhammadSodik.png" 
                                     alt="QR Code Direktur" class="qr-image" id="qrImage">
                                <div class="qr-overlay" id="qrOverlay">
                                    <i class="fas fa-sync fa-spin"></i>
                                    <p>Memperbarui QR Code...</p>
                                </div>
                            </div>
                            
                            <div class="qr-info">
                                <p class="qr-description">
                                    <i class="fas fa-info-circle"></i>
                                    QR code akan otomatis diperbarui ketika nama direktur diubah
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="action-buttons">
                    <button type="button" class="btn btn-secondary" onclick="confirmCancel()">
                        <i class="fas fa-times"></i>
                        CANCEL
                    </button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i>
                        SIMPAN
                    </button>
                </div>
            </form>
        </div>

        <!-- Confirmation Modal -->
        <div class="modal" id="confirmationModal">
            <div class="modal-content">
                <div class="modal-header">
                    <h3><i class="fas fa-exclamation-triangle"></i> Konfirmasi</h3>
                    <span class="close" onclick="closeModal()">&times;</span>
                </div>
                <div class="modal-body">
                    <p>Apakah Anda yakin ingin keluar dari halaman ini?</p>
                    <p>Perubahan yang belum disimpan akan hilang.</p>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-outline" onclick="closeModal()">
                        <i class="fas fa-arrow-left"></i>
                        Kembali
                    </button>
                    <button class="btn btn-danger" onclick="confirmExit()">
                        <i class="fas fa-check"></i>
                        Ya, Keluar
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Fungsi untuk menangani submit form
        document.getElementById('direkturForm').addEventListener('submit', function(e) {
            e.preventDefault();
            saveData();
        });

        // Fungsi untuk menangani perubahan nama
        document.getElementById('namaDirektur').addEventListener('input', function(e) {
            updateQRPreview(e.target.value);
        });

        // Fungsi untuk memperbarui preview QR code
        function updateQRPreview(nama) {
            const qrImage = document.getElementById('qrImage');
            const qrOverlay = document.getElementById('qrOverlay');
            
            if (nama.trim() === '') {
                return;
            }

            // Tampilkan loading overlay
            qrOverlay.style.display = 'flex';
            
            // Simulasi pembuatan QR code (dalam real implementation, 
            // ini akan memanggil API atau generate QR code)
            setTimeout(() => {
                const formattedName = nama.replace(/\s+/g, '');
                qrImage.src = `assets/img/barcode/${formattedName}.png?t=${new Date().getTime()}`;
                qrImage.alt = `QR Code ${nama}`;
                
                // Sembunyikan loading overlay
                qrOverlay.style.display = 'none';
            }, 1000);
        }

        // Fungsi untuk menyimpan data
        function saveData() {
            const formData = new FormData(document.getElementById('direkturForm'));
            const submitBtn = document.querySelector('button[type="submit"]');
            
            // Tampilkan loading state
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Menyimpan...';
            submitBtn.disabled = true;

            // Simulasi proses penyimpanan
            setTimeout(() => {
                // Dalam implementasi nyata, ini akan mengirim data ke server
                console.log('Data disimpan:', Object.fromEntries(formData));
                
                // Kembalikan state normal
                submitBtn.innerHTML = '<i class="fas fa-save"></i> SIMPAN';
                submitBtn.disabled = false;
                
                // Tampilkan notifikasi sukses
                showNotification('Data berhasil disimpan!', 'success');
            }, 1500);
        }

        // Fungsi untuk konfirmasi cancel
        function confirmCancel() {
            document.getElementById('confirmationModal').style.display = 'flex';
        }

        // Fungsi untuk menutup modal
        function closeModal() {
            document.getElementById('confirmationModal').style.display = 'none';
        }

        // Fungsi untuk konfirmasi keluar
        function confirmExit() {
            window.location.href = 'dashboard.html';
        }

        // Fungsi untuk menampilkan notifikasi
        function showNotification(message, type) {
            // Implementasi notifikasi sederhana
            alert(`${type === 'success' ? 'SUKSES' : 'ERROR'}: ${message}`);
        }

        // Inisialisasi
        document.addEventListener('DOMContentLoaded', function() {
            // Fokus ke input nama saat halaman dimuat
            document.getElementById('namaDirektur').focus();
            
            // Close modal ketika klik di luar konten modal
            window.addEventListener('click', function(e) {
                const modal = document.getElementById('confirmationModal');
                if (e.target === modal) {
                    closeModal();
                }
            });
        });
    </script>
</body>
</html>