<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="{{ asset('css/barang/cucisepuh/formpenyimpanan.css') }}">
</head>

<body>
    @include('partials.navbar')
    <div class="container">
        <div class="header">
            <h1><i class="bi bi-archive"></i> Formulir Penyimpanan Mutu</h1>
        </div>

        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <div class="card">
            <div class="card-header">
                <h3><i class="bi bi-pencil-square"></i> Isi Formulir</h3>
            </div>
            <div class="card-body">
                <form action="{{ route('formpenyimpanan.store') }}" method="POST">
                    @csrf

                    <div class="form-group">
                        <label>Barang</label>
                        <select name="kdbarang" class="form-control" required>
                            <option value="">-- Pilih Barang --</option>
                            @foreach($barang as $b)
                                <option value="{{ $b->kdbarang }}">{{ $b->namabarang }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group">
                        <label>Pelanggan</label>
                        <select name="kdpelanggan" class="form-control">
                            <option value="">-- Pilih Pelanggan --</option>
                            @foreach($pelanggan as $p)
                                <option value="{{ $p->kdpelanggan }}">{{ $p->namapelanggan }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group">
                        <label>Kondisi Penyimpanan</label>
                        <input type="text" name="kondisi" class="form-control" required>
                    </div>

                    <div class="form-group">
                        <label>Suhu</label>
                        <input type="text" name="suhu" class="form-control">
                    </div>

                    <div class="form-group">
                        <label>Kelembaban</label>
                        <input type="text" name="kelembaban" class="form-control">
                    </div>

                    <div class="form-group">
                        <label>Deskripsi</label>
                        <textarea name="deskripsi" class="form-control"></textarea>
                    </div>

                    <div class="action-buttons">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-save"></i> Simpan
                        </button>
                        <a href="{{ route('riwayatpenyimpanan') }}" class="btn btn-secondary">
                            <i class="bi bi-clock-history"></i> Lihat Riwayat
                        </a>
                    </div>
                </form>
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