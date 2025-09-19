<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Detail Cuci Sepuh</title>
    <link rel="stylesheet" href="{{ asset('css/barang/cucisepuh/detailcuci.css') }}">
</head>

<body>
    <h1>Detail Cuci Sepuh #CS-{{ $cuci->id_cuci }}</h1>

    <div class="detail-container">
        <p><strong>Pelanggan:</strong> {{ $cuci->nama_pelanggan }}</p>
        <p><strong>No HP:</strong> {{ $cuci->no_hp }}</p>
        <p><strong>Alamat:</strong> {{ $cuci->alamat }}</p>
        <p><strong>Jenis Barang:</strong> {{ $cuci->jenis_barang }}</p>
        <p><strong>Berat:</strong> {{ $cuci->berat }} gr</p>
        <p><strong>Karat:</strong> {{ $cuci->karat }} K</p>
        <p><strong>Tanggal Cuci:</strong> {{ \Carbon\Carbon::parse($cuci->tanggal_cuci)->format('d-m-Y') }}</p>
        <p><strong>Tanggal Selesai:</strong>
            {{ $cuci->tanggal_selesai ? \Carbon\Carbon::parse($cuci->tanggal_selesai)->format('d-m-Y') : '-' }}</p>
        <p><strong>Ongkos Cuci:</strong> Rp {{ number_format($cuci->ongkos_cuci, 0, ',', '.') }}</p>
        <p><strong>Total Bayar:</strong> Rp {{ number_format($cuci->total_bayar, 0, ',', '.') }}</p>
        <p><strong>Metode Bayar:</strong> {{ ucfirst($cuci->metode_bayar) }}</p>
        <p>
            <strong>Status:</strong>
            <span class="status-badge status-{{ $cuci->status }}">
                {{ ucfirst($cuci->status) }}
            </span>
        </p>
        <p><strong>Catatan:</strong> {{ $cuci->catatan ?? '-' }}</p>

        @if($cuci->foto_barang)
            <p><strong>Foto Barang:</strong></p>
            <img src="{{ asset('storage/cuci_sepuh/' . $cuci->foto_barang) }}" alt="Foto Barang" width="250">
        @endif

        <a href="{{ route('riwayatcuci') }}">← Kembali ke Riwayat</a>
    </div>
</body>

</html>