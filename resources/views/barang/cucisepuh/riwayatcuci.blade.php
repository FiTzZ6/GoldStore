<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Riwayat Cuci Sepuh</title>
    <link rel="stylesheet" href="{{ asset('css/barang/cucisepuh/riwayatcuci.css') }}">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>

<body>
    @include('partials.navbar')

    <div class="container">
        <div class="header">
            <h1>Riwayat Cuci Sepuh</h1>
            <form method="GET" action="{{ route('riwayatcuci') }}" class="date-filter">
                <input type="date" name="start_date" value="{{ request('start_date') }}">
                <span>s/d</span>
                <input type="date" name="end_date" value="{{ request('end_date') }}">
                <button type="submit" class="filter-btn">
                    <i class="fas fa-filter"></i> Filter
                </button>
            </form>
        </div>

        <div class="content">
            <div class="transaction-list">
                @forelse($riwayats as $r)
                    <div class="transaction-card">
                        <div class="transaction-header">
                            <span class="transaction-id">#CS-{{ $r->id_cuci }}</span>
                            <span
                                class="transaction-status 
                                    {{ $r->status === 'selesai' ? 'completed' : ($r->status === 'proses' ? 'in-progress' : 'pending') }}">
                                {{ ucfirst($r->status) }}
                            </span>
                        </div>

                        <div class="transaction-details">
                            <div class="item">
                                <div class="item-info">
                                    <h3>{{ $r->jenis_barang }}</h3>
                                    <p>{{ $r->nama_pelanggan }} | {{ $r->berat }} gr | {{ $r->karat }} K</p>
                                </div>
                                <div class="item-total">
                                    {{ \Carbon\Carbon::parse($r->tanggal_cuci)->format('d-m-Y') }}
                                </div>
                            </div>
                        </div>

                        <div class="transaction-footer">
                            <div class="transaction-total">Status: {{ ucfirst($r->status) }}</div>

                            <a href="{{ route('cucisepuh.show', $r->id_cuci) }}" class="detail-btn">
                                Lihat Detail
                            </a>

                            <a href="{{ route('cucisepuh.struk', $r->id_cuci) }}" target="_blank" class="print-btn">
                                Cetak Struk
                            </a>
                        </div>
                    </div>
                @empty
                    <p class="no-data">Belum ada riwayat.</p>
                @endforelse
            </div>
        </div>
    </div>
</body>

</html>