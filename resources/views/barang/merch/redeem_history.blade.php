<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Riwayat Redeem Merchandise</title>
    <link rel="stylesheet" href="{{ asset('css/barang/merch/merch.css') }}">
</head>

<body>
    @include('partials.navbar')

    <div class="container">
        <div class="header" style="display:flex; justify-content:space-between; align-items:center;">
            <h1>Riwayat Redeem Merchandise</h1>
            <a href="{{ route('merch.index') }}">
                <button
                    style="padding:8px 14px; border:none; border-radius:6px; background:linear-gradient(135deg,#6c757d,#495057); color:white; cursor:pointer;">
                    ⬅ Kembali
                </button>
            </a>
        </div>

        @if(session('success'))
            <div style="color:green; margin:10px 0; font-weight:bold;">
                {{ session('success') }}
            </div>
        @endif

        <div class="search-box" style="margin:15px 0;">
            <form action="{{ route('merch.redeemHistory') }}" method="GET" style="display:flex; gap:10px;">
                <input type="text" name="q" value="{{ request('q') }}"
                    placeholder="Cari nama pelanggan / merchandise..."
                    style="flex:1; padding:8px; border:1px solid #ccc; border-radius:6px;">
                <button type="submit"
                    style="padding:8px 14px; border:none; border-radius:6px; background:#0d6efd; color:white; cursor:pointer;">
                    🔍 Cari
                </button>
            </form>
        </div>

        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th>Tanggal</th>
                        <th>Pelanggan</th>
                        <th>Merchandise</th>
                        <th>Poin Digunakan</th>
                        <th>Stok Setelah</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($redeems as $r)
                        <tr>
                            <td>{{ \Carbon\Carbon::parse($r->created_at)->format('d-m-Y H:i') }}</td>
                            <td>{{ $r->namapelanggan }}</td>
                            <td>{{ $r->nama_merch }}</td>
                            <td>{{ $r->poin_digunakan }}</td>
                            <td>{{ $r->stok_sisa }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" style="text-align:center;">Belum ada transaksi redeem</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</body>

</html>