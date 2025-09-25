<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Struk #{{ $nofaktur }}</title>
    <style>
        @media print {
            body {
                width: 58mm; /* Bisa diganti ke 80mm sesuai printer */
                font-family: monospace;
                font-size: 12px;
                margin: 0;
                padding: 0;
            }
            .no-print { display: none; }
        }
        body {
            font-family: monospace;
            font-size: 12px;
            width: 58mm;
            margin: auto;
        }
        .center { text-align: center; }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        td {
            padding: 2px 0;
            vertical-align: top;
        }
        hr {
            border: none;
            border-top: 1px dashed #000;
            margin: 5px 0;
        }
        .btn {
            display: inline-block;
            margin: 5px 2px;
            padding: 5px 10px;
            background: #333;
            color: #fff;
            font-size: 12px;
            text-decoration: none;
            cursor: pointer;
        }
    </style>
</head>
<body>
    <div class="center">
        <h3>Toko PT EMAS</h3>
        <p>
            No Faktur: {{ $nofaktur }}<br>
            {{ $tanggal->format('d-m-Y H:i') }}
        </p>
    </div>

    <p>
        <strong>Pelanggan:</strong> {{ $pelanggan ?? '-' }}<br>
        <strong>Staff:</strong> {{ $staff ?? '-' }}<br>
        <strong>Bayar:</strong> {{ $pembayaran ?? '-' }}
    </p>

    <hr>

    <table>
        <thead>
            <tr>
                <td>Barang</td>
                <td style="text-align:right;">Subtotal</td>
            </tr>
        </thead>
        <tbody>
            @foreach($items as $i)
            <tr>
                <td>
                    {{ $i->namabarang }}<br>
                    {{ $i->quantity ?? 1 }} x Rp{{ number_format($i->harga, 0, ',', '.') }}
                </td>
                <td style="text-align:right;">
                    Rp{{ number_format($i->total, 0, ',', '.') }}
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <hr>

    <table>
        <tr>
            <td><strong>Total</strong></td>
            <td style="text-align:right;"><strong>Rp{{ number_format($total, 0, ',', '.') }}</strong></td>
        </tr>
    </table>

    <div class="center">
        <p>
            -- Terima Kasih --<br>
            Barang yang sudah dibeli<br>
            tidak dapat dikembalikan
        </p>
    </div>

    <div class="center no-print">
        <button onclick="window.print()" class="btn">Cetak Ulang</button>
    </div>
</body>
</html>
