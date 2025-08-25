<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Transaksi Pembelian Umum</title>
    <link rel="stylesheet" href="{{ asset('css/beli/transaksi_beli.css') }}">
</head>
<body>
@include('partials.navbar')
<div class="container">
    <div class="header">
        <div class="left">
            <label>No. Faktur</label>
            <input type="text" value="FB-1250807-0001" readonly>
        </div>
        <div class="center">
            <h3>TRANSAKSI PEMBELIAN UMUM</h3>
            <p>SAMBAS HOLDING</p>
        </div>
        <div class="right">
            <label>TOKO</label>
            <input type="text" value="HLD" readonly>
        </div>
    </div>

    <hr>

    <div class="form-section">
        <div class="form-row">
            <label>STAFF</label>
            <input type="text">
        </div>
        <div class="form-row">
            <label>SCAN BARCODE</label>
            <input type="text" placeholder="Masukkan kode barang">
            <span class="note">* tekan Enter untuk mencari data</span>
        </div>
        <div class="form-row">
            <label>NAMA PENJUAL</label>
            <input type="text" placeholder="Nama Penjual">
        </div>
        <div class="form-row">
            <label>ALAMAT</label>
            <input type="text" placeholder="Alamat">
        </div>
        <div class="form-row">
            <label>No. Telp</label>
            <input type="text" placeholder="No. Telp">
        </div>
    </div>

    <table>
        <thead>
            <tr>
                <th>Faktur</th>
                <th>Barcode</th>
                <th>Deskripsi</th>
                <th>Jenis</th>
                <th>Kondisi</th>
                <th>Berat Nota</th>
                <th>Berat (Gram)</th>
                <th>Harga Nota</th>
                <th>Harga Beli</th>
                <th>Harga Rata</th>
                <th>Potongan</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td>
                    <input type="text" class="table-input" readonly>
                </td>
                <td></td>
                <td></td>
            </tr>
        </tbody>
    </table>

    <div class="total">TOTAL</div>

    <div class="buttons">
        <button class="cancel">CANCEL</button>
        <button class="reset">RESET</button>
        <button class="save">💾 SIMPAN</button>
    </div>
</div>
</body>
</html>
