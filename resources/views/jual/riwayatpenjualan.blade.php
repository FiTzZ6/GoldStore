<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Riwayat Penjualan - TANGCAL</title>
    <link rel="stylesheet" href="{{ asset('css/jual/riwayatjual.css') }}">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>

<body>
    @include('partials.navbar')
    <div class="container">
        <div class="header">
            <h1><i class="fas fa-history"></i> Riwayat Penjualan</h1>
            <form method="GET" action="{{ route('riwayatpenjualan') }}" class="date-filter">
                <input type="date" name="start_date" value="{{ request('start_date') }}">
                <span>s/d</span>
                <input type="date" name="end_date" value="{{ request('end_date') }}">

                <input type="text" name="search" placeholder="Cari nama barang / pelanggan"
                    value="{{ request('search') }}">

                <button type="submit" class="filter-btn">
                    <i class="fas fa-filter"></i> Filter
                </button>
            </form>
        </div>

        <div class="content">
            @forelse($transaksi as $nofaktur => $items)
                        <div class="transaction-card">
                            <div class="transaction-header">
                                <span class="transaction-id">#{{ $nofaktur }}</span>
                                <span class="transaction-customer">Pelanggan: {{ $items->first()->namapelanggan ?? '-' }}</span>
                                <span class="transaction-status completed">Selesai</span>
                            </div>
                            
                            <div class="transaction-meta">
                                @php
                                    $tanggal = \Carbon\Carbon::parse($items->first()->created_at);
                                @endphp
                                <span class="transaction-date">
                                    {{ $tanggal->translatedFormat('l, d F Y H:i') }}
                                </span>
                            </div>

                            <div class="transaction-details">
                                @foreach($items as $item)
                                    <div class="item">
                                        <div class="item-info">
                                            <h3>{{ $item->namabarang }}</h3>
                                            <p>{{ $item->quantity ?? 1 }} x Rp {{ number_format($item->harga, 0, ',', '.') }}</p>
                                        </div>
                                        <div class="item-total">
                                            Rp {{ number_format($item->total, 0, ',', '.') }}
                                        </div>
                                    </div>
                                @endforeach
                            </div>

                            <div class="transaction-footer">
                                <div class="transaction-total">
                                    Total: Rp {{ number_format($items->sum('total'), 0, ',', '.') }}
                                </div>
                                <a href="{{ route('transpenjualan.struk', $nofaktur) }}" target="_blank" class="detail-btn">
                                    Lihat Detail
                                </a>
                            </div>
                        </div>
            @empty
                <p>Tidak ada transaksi ditemukan.</p>
            @endforelse
        </div>
    </div>
</body>

</html>