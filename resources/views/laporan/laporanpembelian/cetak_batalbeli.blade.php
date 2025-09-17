<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cetak Laporan Batal Beli</title>
    <style>
        body {
            font-family: "Times New Roman", Times, serif;
            margin: 40px;
            font-size: 14px;
        }

        /* Kop Surat */
        .kop-surat {
            text-align: center;
            margin-bottom: 20px;
        }

        .kop-surat img {
            float: left;
            width: 80px;
            height: auto;
        }

        .kop-surat h1, .kop-surat h2, .kop-surat p {
            margin: 0;
            line-height: 1.2;
        }

        .garis {
            border-top: 3px solid #000;
            margin: 10px 0 20px 0;
        }

        h2.judul-laporan {
            text-align: center;
            margin-bottom: 20px;
            text-decoration: underline;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 30px;
        }

        table, th, td {
            border: 1px solid #000;
        }

        th, td {
            padding: 6px;
            text-align: left;
            font-size: 12px;
        }

        th {
            background-color: #f2f2f2;
        }

        .ttd {
            width: 100%;
            margin-top: 50px;
            display: flex;
            justify-content: flex-end;
        }

        .ttd div {
            text-align: center;
        }

        .ttd div p {
            margin: 0;
            line-height: 1.5;
        }

        @media print {
            button {
                display: none;
            }
        }

        .btn-print {
            display: inline-block;
            padding: 8px 20px;
            background-color: #2c3e50;
            color: #fff;
            text-decoration: none;
            border-radius: 5px;
            margin-bottom: 20px;
        }

        .btn-print:hover {
            background-color: #34495e;
        }
    </style>
</head>
<body>

    <!-- Kop Surat -->
    <div class="kop-surat">
        <img src="{{ asset('images/logo.png') }}" alt="Logo">
        <h1>PT. Contoh Perusahaan</h1>
        <p>Jl. Contoh Alamat No.123, Kota Contoh, Indonesia</p>
        <p>Telepon: (021) 1234567 | Email: info@contoh.com</p>
    </div>

    <div class="garis"></div>

    <!-- Judul Laporan -->
    <h2 class="judul-laporan">LAPORAN BATAL BELI</h2>

    <button class="btn-print" onclick="window.print()">Cetak Laporan</button>

    <!-- Tabel Data -->
    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>No Faktur Beli</th>
                <th>No Faktur Batal</th>
                <th>Staff</th>
                <th>Nama Penjual</th>
                <th>Nama Barang</th>
                <th>Berat</th>
                <th>Harga Beli</th>
                <th>Harga Batal</th>
                <th>Keterangan</th>
                <th>Tanggal</th>
            </tr>
        </thead>
        <tbody>
            @forelse($transaksi as $item)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $item->nofakturbeli }}</td>
                <td>{{ $item->nofakturbatalbeli }}</td>
                <td>{{ $item->namastaff }}</td>
                <td>{{ $item->namapenjual }}</td>
                <td>{{ $item->namabarang }}</td>
                <td>{{ $item->berat }}</td>
                <td>{{ number_format($item->hargabeli, 0, ',', '.') }}</td>
                <td>{{ number_format($item->hargabatalbeli, 0, ',', '.') }}</td>
                <td>{{ $item->keterangan }}</td>
                <td>{{ $item->created_at->format('d-m-Y') }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="11" style="text-align:center;">Tidak ada data</td>
            </tr>
            @endforelse
        </tbody>
    </table>

    <!-- Tanda Tangan -->
    <div class="ttd">
        <div>
            <p>Mengetahui,</p>
            <p>Manager</p>
            <br><br><br>
            <p><u>Nama Manager</u></p>
        </div>
    </div>

</body>
</html>
