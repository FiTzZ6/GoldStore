<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Riwayat Pembelian - TANGCAL</title>
    <link rel="stylesheet" href="{{ asset('css/beli/riwayat_beli.css') }}">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>

<body>
    @include('partials.navbar')
    <div class="container">
        <div class="header">
            <h1><i class="fas fa-history"></i> Riwayat Pembelian</h1>
            <div class="date-filter">
                <form method="GET" action="{{ route('riwayatbeli') }}"
                    style="display: flex; gap: 10px; align-items: center;">
                    <input type="date" name="start_date" id="start-date" value="{{ request('start_date') }}">
                    <span>s/d</span>
                    <input type="date" name="end_date" id="end-date" value="{{ request('end_date') }}">

                    <input type="text" name="q" placeholder="Cari nama, faktur, barcode, dll"
                        value="{{ request('q') }}">

                    <button type="submit" class="filter-btn"><i class="fas fa-filter"></i> Filter</button>
                </form>
            </div>
        </div>

        <div class="content">
            @forelse($riwayat->groupBy(function ($item) {
                    return \Carbon\Carbon::parse($item->created_at)->format('d/m/Y');
                }) as $tanggal => $transaksiPerTanggal)

                            <div class="date-header">
                                <h2>{{ $tanggal }}</h2>
                            </div>

                            @foreach($transaksiPerTanggal as $trx)
                                <div class="transaction-card">
                                    <div class="transaction-header">
                                        <span class="transaction-id">#{{ $trx->nofaktur }}</span>
                                        <span class="transaction-status completed">Selesai</span>
                                    </div>
                                    <div class="transaction-details">
                                        <div class="item">
                                            <div class="item-info">
                                                <h3>{{ $trx->deskripsi }}</h3>
                                                <p>{{ $trx->beratbaru }} gram x Rp {{ number_format($trx->hargabaru, 0, ',', '.') }}</p>
                                                <p><strong>Penjual:</strong> {{ $trx->namapenjual }}</p>
                                                <p><strong>Tanggal:</strong>
                                                    {{ \Carbon\Carbon::parse($trx->created_at)->format('d-m-Y H:i') }}
                                                </p>
                                            </div>
                                            <div class="item-total">
                                                Rp {{ number_format($trx->total, 0, ',', '.') }}
                                            </div>
                                        </div>
                                    </div>
                                    <div class="transaction-footer">
                                        <div class="transaction-total">
                                            Total: Rp {{ number_format($trx->total, 0, ',', '.') }}
                                        </div>
                                        <button class="detail-btn">Lihat Detail</button>
                                    </div>
                                </div>
                            @endforeach

            @empty
                <p>Tidak ada data pembelian.</p>
            @endforelse
        </div>

    </div>
    </div>

    <script>
        // Set tanggal hari ini sebagai default
        document.addEventListener('DOMContentLoaded', function () {
            const today = new Date().toISOString().substr(0, 10);
            document.getElementById('start-date').value = today;
            document.getElementById('end-date').value = today;
        });
    </script>
</body>

</html>