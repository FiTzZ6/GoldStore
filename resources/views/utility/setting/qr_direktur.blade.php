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

        <!-- Notifikasi -->
        @if(session('success'))
            <div class="alert alert-success">
                <i class="fas fa-check-circle"></i> {{ session('success') }}
            </div>
        @endif
        @if($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach($errors->all() as $err)
                        <li>{{ $err }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- Main Content -->
        <div class="content-card">
            <form class="qr-form" id="direkturForm" method="POST" action="{{ route('qr_direktur.store') }}">
                @csrf
                <div class="form-container">
                    <!-- Informasi Direktur -->
                    <div class="info-section">
                        <div class="section-header">
                            <i class="fas fa-user-tie"></i>
                            <h2>Informasi Direktur</h2>
                        </div>

                        <div class="form-group">
                            <label for="namaDirektur">Nama Direktur</label>
                            <input type="text" id="namaDirektur" name="nama" class="form-control"
                                value="{{ old('nama', $direktur->nama ?? '') }}" required>
                        </div>

                        <div class="form-group">
                            <label for="jabatan">Jabatan</label>
                            <input type="text" id="jabatan" name="jabatan" class="form-control"
                                value="{{ old('jabatan', $direktur->jabatan ?? 'Direktur Operasional') }}" readonly>
                        </div>
                    </div>

                    <!-- QR Code Section -->
                    <div class="qr-section">
                        <div class="section-header">
                            <i class="fas fa-qrcode"></i>
                            <h2>QR Code</h2>
                        </div>

                        <div class="qr-container">
                            @if(!empty($direktur->qrcode) && file_exists(public_path($direktur->qrcode)))
                                <div class="qr-display">
                                    <img src="{{ asset($direktur->qrcode) }}" alt="QR Code Direktur" class="qr-image">
                                    <p class="qr-description">QR Code aktif untuk {{ $direktur->nama }}</p>
                                </div>
                            @else
                                <p class="qr-description">
                                    <i class="fas fa-info-circle"></i>
                                    QR Code belum tersedia, silakan isi data lalu simpan.
                                </p>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="action-buttons">
                    <button type="button" class="btn btn-secondary" onclick="confirmCancel()">
                        <i class="fas fa-times"></i> CANCEL
                    </button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> SIMPAN
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
                        <i class="fas fa-arrow-left"></i> Kembali
                    </button>
                    <button class="btn btn-danger" onclick="confirmExit()">
                        <i class="fas fa-check"></i> Ya, Keluar
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script>
        function confirmCancel() {
            document.getElementById('confirmationModal').style.display = 'flex';
        }

        function closeModal() {
            document.getElementById('confirmationModal').style.display = 'none';
        }

        function confirmExit() {
            window.location.href = "{{ route('qr_direktur') }}";
        }

        document.addEventListener('DOMContentLoaded', () => {
            const modal = document.getElementById('confirmationModal');
            const namaInput = document.getElementById('namaDirektur');
            if (namaInput) namaInput.focus();

            window.addEventListener('click', e => {
                if (e.target === modal) {
                    closeModal();
                }
            });
        });
    </script>
</body>

</html>