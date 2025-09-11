<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Surat Kirim Barang {{ $surat->nokirim }}</title>
    <style>
        body { 
            font-family: Arial, sans-serif; 
            font-size: 12px; 
            margin: 40px;
        }
        header {
            text-align: center;
            margin-bottom: 30px;
        }
        header h1 {
            margin: 0;
            font-size: 20px;
        }
        header p {
            margin: 2px 0;
        }
        table { 
            width: 100%; 
            border-collapse: collapse; 
            margin-top: 20px; 
        }
        th, td { 
            border: 1px solid #000; 
            padding: 6px; 
            text-align: left; 
        }
        .section-title {
            margin-top: 30px;
            font-weight: bold;
        }
        .footer {
            margin-top: 50px;
            width: 100%;
            display: flex;
            justify-content: space-between;
        }
        .signature {
            text-align: center;
            margin-top: 60px;
        }
    </style>
</head>
<body>
    <header>
        <h1>PT. Toko Goldstore</h1>
        <p>Jl. Contoh Alamat No.123, Jakarta</p>
        <p>Telp: (021) 12345678 | Email: info@goldstore.co.id</p>
        <hr>
        <h2>SURAT KIRIM BARANG</h2>
    </header>

    <p><b>No Surat:</b> {{ $surat->nokirim }}</p>
    <p><b>Tanggal:</b> {{ \Carbon\Carbon::parse($surat->tanggal)->format('d-m-Y') }}</p>
    <p><b>Dari Toko:</b> {{ $surat->kdtokokirim }}</p>
    <p><b>Ke Toko:</b> {{ $surat->kdtokoterima }}</p>
    <p><b>Status:</b> {{ $surat->status }}</p>

    <div class="section-title">Detail Barang</div>
    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Barcode</th>
                <th>Nama Barang</th>
                <th>Jenis</th>
                <th>Baki</th>
                <th>Berat</th>
                <th>Qty</th>
            </tr>
        </thead>
        <tbody>
            @foreach($surat->detail as $index => $d)
                <tr>
                    <td>{{ $index + 1 }}</td>
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
        <div class="section-title">Surat Terima Barang</div>
        <p><b>No Terima:</b> {{ $surat->terimaBarang->noterima }}</p>
        <p><b>Tanggal Terima:</b> {{ \Carbon\Carbon::parse($surat->terimaBarang->tanggalterima)->format('d-m-Y') }}</p>
        <p><b>PIC:</b> {{ $surat->terimaBarang->picterima }}</p>
    @endif

    <div class="footer">
        <div class="signature">
            <p>Dikirim Oleh,</p>
            <p>______________</p>
        </div>
        <div class="signature">
            <p>Diterima Oleh,</p>
            <p>______________</p>
        </div>
    </div>
</body>
</html>
