<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="{{ asset('css/cmpny_prfl.css') }}">
    <link href="https://fonts.googleapis.com/css?family=Montserrat:400,800" rel="stylesheet">
    <title>Halaman Utility</title>
</head>
<body>
    @include('partials.navbar')

    <div class="breadcrumb">
        <a href="{{ route('laporan.dashboard') }}">Home</a> &gt; 
        <a href="#">Utility</a> &gt; 
        <span>Setting Profil Perusahaan</span>
    </div>

    <div class="form-container">
        <form action="{{ route('utility.company_profile') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="form-grid">
                <!-- Kolom Kiri -->
                <div class="form-left">
                    <div class="form-group">
                        <label>Nama Perusahaan</label>
                        <input type="text" name="nama_perusahaan" value="{{ old('nama_perusahaan', 'PT. ELING SAMBAS GRUP') }}">
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
                        <input type="text" name="telp">
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
</body>
</html>

