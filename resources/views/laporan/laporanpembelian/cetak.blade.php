<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Surat Laporan Pembelian</title>
    <style>
        body {
            font-family: "Times New Roman", serif;
            margin: 40px;
        }

        .kop {
            text-align: center;
            border-bottom: 2px solid black;
            margin-bottom: 20px;
        }

        .kop h2 {
            margin: 0;
        }

        .kop p {
            margin: 0;
            font-size: 14px;
        }

        .judul {
            text-align: center;
            margin: 30px 0;
            font-weight: bold;
            font-size: 18px;
            text-decoration: underline;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }

        table th,
        table td {
            border: 1px solid black;
            padding: 6px;
            text-align: center;
            font-size: 14px;
        }

        .ttd {
            margin-top: 40px;
            width: 100%;
            display: flex;
            justify-content: flex-end;
        }

        .ttd div {
            text-align: center;
        }
    </style>
</head>

<body>
    <div class="kop">
        <h2>PT. TOKO SEPATU AJANYEKER</h2>
        <p>Jl. Contoh No.123, Purwokerto</p>
        <p>Telp: (0281) 123456</p>
    </div>

    <div class="judul">SURAT LAPORAN PEMBELIAN</div>

    <p>
        Dengan ini kami laporkan data pembelian periode
        <b>{{ $start ? date('d-m-Y', strtotime($start)) : '-' }}</b>
        s/d
        <b>{{ $end ? date('d-m-Y', strtotime($end)) : '-' }}</b>
        sebagai berikut:
    </p>

    <table>
        <thead>
            <tr>
                <th>No Faktur</th>
                <th>Staff</th>
                <th>Barcode</th>
                <th>Nama Penjual</th>
                <th>Tanggal</th>
                <th>Total</th>
            </tr>
        </thead>
        <tbody>
            @forelse($pembelian as $row)
                <tr>
                    <td>{{ $row->nofakturbeli }}</td>
                    <td>{{ $row->staff }}</td>
                    <td>{{ $row->barcode }}</td>
                    <td>{{ $row->namapenjual }}</td>
                    <td>{{ \Carbon\Carbon::parse($row->created_at)->format('d-m-Y') }}</td>
                    <td>{{ number_format($row->total, 0, ',', '.') }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="6">Tidak ada data pembelian</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="ttd">
        <div>
            <p>Purwokerto, {{ date('d-m-Y') }}</p>
            <p>Hormat Kami,</p>
            <br><br><br>
            <p><b>(________________)</b></p>
        </div>
    </div>
</body>

</html>