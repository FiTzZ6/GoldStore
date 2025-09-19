<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Struk Cuci Sepuh</title>
    <style>
        body { font-family: 'Courier New', monospace; font-size: 14px; }
        .center { text-align: center; }
        .line { border-top: 1px dashed #000; margin: 8px 0; }
        .total { font-weight: bold; }
    </style>
</head>
<body>
    <div class="center">
        <h2>Toko Emas Abadi</h2>
        <p>Struk Cuci Sepuh</p>
        <div class="line"></div>
    </div>

    <p>No Transaksi: #CS-{{ $cuci->id_cuci }}</p>
    <p>Tanggal    : {{ \Carbon\Carbon::parse($cuci->tanggal_cuci)->format('d-m-Y') }}</p>
    <p>Pelanggan  : {{ $cuci->nama_pelanggan }}</p>
    <p>Jenis Brg  : {{ $cuci->jenis_barang }}</p>
    <p>Berat/Karat: {{ $cuci->berat }} gr / {{ $cuci->karat }}K</p>
    <div class="line"></div>
    <p>Ongkos     : Rp {{ number_format($cuci->ongkos_cuci, 0, ',', '.') }}</p>
    <p class="total">Total Bayar: Rp {{ number_format($cuci->total_bayar, 0, ',', '.') }}</p>
    <p>Metode     : {{ ucfirst($cuci->metode_bayar) }}</p>
    <div class="line"></div>

    <div class="center">
        <p>Terima kasih 🙏</p>
    </div>
</body>
</html>
