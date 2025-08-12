<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="{{ asset('css/utility/permintaan_pembelian/formulir_pp.css') }}">
    <title>Formulir PP</title>
</head>
<body>
@include('partials.navbar')

<div class="container">
    {{-- Header Form --}}
    <h3 class="mb-4">Form Permintaan Pembelian</h3>
    
    <div class="card mb-4">
        <div class="card-body">
            <p><strong>No. PP:</strong> {{ $nopp }}</p>
            <p><strong>Nama Toko:</strong> {{ $detailToko->nama_toko ?? '-' }}</p>
            <p><strong>Alamat:</strong> {{ $detailToko->alamat ?? '-' }}</p>
        </div>
    </div>

    {{-- Form --}}
    <form action="{{ route('kirimPP') }}" method="POST">
        @csrf
        <input type="hidden" name="no_pp" value="{{ $nopp }}">
        <input type="hidden" name="id_toko" value="{{ $detailToko->id_toko ?? '' }}">

        {{-- Tanggal --}}
        <div class="mb-3">
            <label class="form-label">Tanggal PP</label>
            <input type="date" name="tgl_pp" class="form-control" required>
        </div>

        {{-- Tabel Barang --}}
        <h5>Daftar Barang</h5>
        <table class="table table-bordered" id="table-barang">
            <thead>
                <tr>
                    <th>Nama Barang</th>
                    <th>Qty</th>
                    <th>Satuan</th>
                    <th>Keterangan</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td><input type="text" name="barang[0][nama]" class="form-control" required></td>
                    <td><input type="number" name="barang[0][qty]" class="form-control" min="1" required></td>
                    <td><input type="text" name="barang[0][satuan]" class="form-control" required></td>
                    <td><input type="text" name="barang[0][keterangan]" class="form-control"></td>
                    <td><button type="button" class="btn btn-danger btn-sm remove-row">Hapus</button></td>
                </tr>
            </tbody>
        </table>
        <button type="button" id="add-row" class="btn btn-primary btn-sm mb-3">Tambah Barang</button>

        {{-- Tombol Submit --}}
        <div>
            <button type="submit" class="btn btn-success">Kirim PP</button>
            <a href="{{ route('pp.index') }}" class="btn btn-secondary">Batal</a>
        </div>
    </form>
</div>

{{-- Script Tambah & Hapus Row --}}
<script>
    let rowIndex = 1;
    document.getElementById('add-row').addEventListener('click', function() {
        let tableBody = document.querySelector('#table-barang tbody');
        let newRow = `
            <tr>
                <td><input type="text" name="barang[${rowIndex}][nama]" class="form-control" required></td>
                <td><input type="number" name="barang[${rowIndex}][qty]" class="form-control" min="1" required></td>
                <td><input type="text" name="barang[${rowIndex}][satuan]" class="form-control" required></td>
                <td><input type="text" name="barang[${rowIndex}][keterangan]" class="form-control"></td>
                <td><button type="button" class="btn btn-danger btn-sm remove-row">Hapus</button></td>
            </tr>
        `;
        tableBody.insertAdjacentHTML('beforeend', newRow);
        rowIndex++;
    });

    document.addEventListener('click', function(e) {
        if (e.target.classList.contains('remove-row')) {
            e.target.closest('tr').remove();
        }
    });
</script>
</body>
</html>