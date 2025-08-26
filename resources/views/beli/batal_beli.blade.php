<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Transaksi Batal Beli</title>
    <link rel="stylesheet" href="{{ asset('css/beli/batal_beli.css') }}">
</head>
<body>
@include('partials.navbar')
<div class="container">
    <div class="header">
        <div class="left">
            <label>No.Faktur</label>
            <input type="text">
        </div>
        <div class="center">
            <h3>Transaksi Batal Beli</h3>
            <p>SAMBAS HOLDING</p>
        </div>
        <div class="right">
            <label>Toko</label>
            <input type="text">
        </div>
    </div>

    <hr>

    <div class="form-section">
        <div class="form-row">
            <label>KD Staff</label>
            <input type="text" placeholder="masukkan kode staff">
            <span class="note">* tekan Enter untuk mencari data staff</span>
        </div>
        <div class="form-row">
            <label>No. Faktur Beli</label>
            <input type="text" placeholder="Masukkan No Faktur">
            <span class="note">* scan/tekan Enter untuk mencari data berdasarkan nomor faktur beli</span>
        </div>
    </div>

    <table>
        <thead>
            <tr>
                <th>NO FAKTUR BELI</th>
                <th>KONDISI</th>
                <th>NAMA BARANG</th>
                <th>BERAT</th>
                <th>HARGA BELI</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td colspan="5" class="empty-row"></td>
            </tr>
        </tbody>
    </table>

    <div class="form-section">
        <div class="form-row">
            <label>KONDISI BARANG</label>
            <select>
                <option>Pilih Kondisi</option>
                <option>Baik</option>
                <option>Rusak</option>
            </select>
        </div>
        <div class="form-row">
            <label>KETERANGAN BATAL</label>
            <textarea placeholder=""></textarea>
        </div>
    </div>

    <div class="buttons">
        <button class="cancel">CANCEL</button>
        <button class="clear">CLEAR</button>
        <div class="right-btn">
            <button class="save">SIMPAN</button>
            <label><input type="checkbox" checked> Cetak Struk Batal Beli</label>
        </div>
    </div>
</div>
</body>
</html>
