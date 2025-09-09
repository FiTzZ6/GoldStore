<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Struk Service</title>
    <style>
        body {
            font-family: monospace;
            font-size: 12px;
            width: 260px; /* 58mm thermal printer */
            margin: auto;
        }
        .center { text-align: center; }
        .line { border-top: 1px dashed #000; margin: 5px 0; }
        table { width: 100%; font-size: 12px; }
        td { vertical-align: top; }
        .right { text-align: right; }
        .total { font-weight: bold; font-size: 13px; }
        @media print {
            button { display: none; }
            body { margin: 0; }
        }
    </style>
</head>
<body>
    <div class="center">
        <h3>=== TOKO SERVICE ===</h3>
        <p>No Faktur: {{ $trans->fakturservice }}</p>
        <p>{{ $trans->namapelanggan }}</p>
        <p>Tgl Servis: {{ $trans->tanggalservice }}</p>
        <p>Tgl Ambil : {{ $trans->tanggalambil }}</p>
    </div>

    <div class="line"></div>

    <table>
        @foreach($trans->namabarang as $i => $barang)
            <tr>
                <td colspan="2">{{ $barang }} ({{ $trans->jenis[$i] ?? '-' }})</td>
            </tr>

            {{-- Harga barang --}}
            <tr>
                <td>{{ $trans->qty[$i] ?? 1 }} x Rp{{ number_format($trans->harga[$i] ?? 0, 0, ',', '.') }} (barang)</td>
                <td class="right">Rp{{ number_format(($trans->qty[$i] ?? 1) * ($trans->harga[$i] ?? 0), 0, ',', '.') }}</td>
            </tr>

            {{-- Ongkos jasa --}}
            <tr>
                <td>{{ $trans->qty[$i] ?? 1 }} x Rp{{ number_format($trans->ongkos[$i] ?? 0, 0, ',', '.') }} (ongkos)</td>
                <td class="right">Rp{{ number_format(($trans->qty[$i] ?? 1) * ($trans->ongkos[$i] ?? 0), 0, ',', '.') }}</td>
            </tr>

            {{-- Garis pembatas --}}
            <tr>
                <td colspan="2" class="right">--------------------------------</td>
            </tr>

            {{-- Subtotal --}}
            <tr>
                <td>Subtotal</td>
                <td class="right">
                    Rp{{ number_format(
                        (($trans->qty[$i] ?? 1) * ($trans->harga[$i] ?? 0)) +
                        (($trans->qty[$i] ?? 1) * ($trans->ongkos[$i] ?? 0)),
                        0, ',', '.'
                    ) }}
                </td>
            </tr>
        @endforeach
    </table>

    <div class="line"></div>

    @php
        // hitung total aman
        $totalHarga = 0;
        $totalOngkos = 0;
        foreach($trans->namabarang as $i => $b) {
            $totalHarga += ($trans->qty[$i] ?? 1) * ($trans->harga[$i] ?? 0);
            $totalOngkos += ($trans->qty[$i] ?? 1) * ($trans->ongkos[$i] ?? 0);
        }
        $grandTotal = $totalHarga + $totalOngkos;
    @endphp

    <table>
        <tr>
            <td>Total Barang</td>
            <td class="right">Rp{{ number_format($totalHarga, 0, ',', '.') }}</td>
        </tr>
        <tr>
            <td>Total Ongkos</td>
            <td class="right">Rp{{ number_format($totalOngkos, 0, ',', '.') }}</td>
        </tr>
        <tr>
            <td class="total">GRAND TOTAL</td>
            <td class="right total">Rp{{ number_format($grandTotal, 0, ',', '.') }}</td>
        </tr>
    </table>

    <div class="line"></div>

    <div class="center">
        <p>Terima Kasih 🙏</p>
        <p>Barang yang sudah diambil <br>tidak dapat dikembalikan</p>
    </div>

    {{-- Tombol print --}}
    <div class="center" style="margin-top:10px;">
        <button onclick="window.print()" style="padding:5px 15px; cursor:pointer;">
            🖨️ Cetak Struk
        </button>
    </div>
</body>
</html>
