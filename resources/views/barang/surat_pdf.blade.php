<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Surat Barang {{ $surat->nokirim }}</title>
    <style>
        body { font-family: sans-serif; font-size: 12px; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th, td { border: 1px solid #000; padding: 5px; text-align: left; }
    </style>
</head>
<body>
    <h2>Surat Kirim Barang</h2>
    <p><b>No Surat:</b> {{ $surat->nokirim }}</p>
    <p><b>Tanggal:</b> {{ $surat->tanggal }}</p>
    <p><b>Dari Toko:</b> {{ $surat->kdtokokirim }}</p>
    <p><b>Ke Toko:</b> {{ $surat->kdtokoterima }}</p>
    <p><b>Status:</b> {{ $surat->status }}</p>

    <h3>Detail Barang</h3>
    <table>
        <thead>
            <tr>
                <th>Barcode</th>
                <th>Nama</th>
                <th>Jenis</th>
                <th>Baki</th>
                <th>Berat</th>
                <th>Qty</th>
            </tr>
        </thead>
        <tbody>
            @foreach($surat->detail as $d)
                <tr>
                    <td>{{ $d->barcode }}</td>
                    <td>{{ $d->namabarang }}</td>
                    <td>{{ $d->kdjenis }}</td>
                    <td>{{ $d->kdbaki }}</td>
                    <td>{{ $d->berat }}</td>
                    <td>{{ $d->qty }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    @if($surat->terimaBarang)
        <h3>Surat Terima Barang</h3>
        <p><b>No Terima:</b> {{ $surat->terimaBarang->noterima }}</p>
        <p><b>Tanggal Terima:</b> {{ $surat->terimaBarang->tanggalterima }}</p>
        <p><b>PIC:</b> {{ $surat->terimaBarang->picterima }}</p>
    @endif
</body>
</html>
