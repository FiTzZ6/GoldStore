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

        {{-- Pagination --}}
        <div class="pagination">
            @if ($redeems instanceof \Illuminate\Pagination\LengthAwarePaginator)
                        <div class="pagination-info">
                            Menampilkan {{ $redeems->firstItem() }} – {{ $redeems->lastItem() }}
                            dari {{ $redeems->total() }} entri
                        </div>
                        <div class="pagination-controls">
                            {{-- Tombol Previous --}}
                            @if ($redeems->onFirstPage())
                                <button class="pagination-btn" disabled>&laquo; Prev</button>
                            @else
                                <a href="{{ $redeems->previousPageUrl() }}" class="pagination-btn">&laquo; Prev</a>
                            @endif

                            {{-- Nomor Halaman Maks 5 --}}
                            @php
                                $start = max($redeems->currentPage() - 2, 1);
                                $end = min($start + 4, $redeems->lastPage());
                                if ($end - $start < 4) {
                                    $start = max($end - 4, 1);
                                }
                            @endphp

                            @if ($start > 1)
                                <a href="{{ $redeems->url(1) }}" class="pagination-btn">1</a>
                                @if ($start > 2)
                                    <span class="pagination-btn">...</span>
                                @endif
                            @endif

                            @for ($page = $start; $page <= $end; $page++)
                                @if ($page == $redeems->currentPage())
                                    <button class="pagination-btn active">{{ $page }}</button>
                                @else
                                    <a href="{{ $redeems->url($page) }}" class="pagination-btn">{{ $page }}</a>
                                @endif
                            @endfor

                            @if ($end < $redeems->lastPage())
                                @if ($end < $redeems->lastPage() - 1)
                                    <span class="pagination-btn">...</span>
                                @endif
                                <a href="{{ $redeems->url($redeems->lastPage()) }}"
                                    class="pagination-btn">{{ $redeems->lastPage() }}</a>
                            @endif

                            {{-- Tombol Next --}}
                            @if ($redeems->hasMorePages())
                                <a href="{{ $redeems->nextPageUrl() }}" class="pagination-btn">Next &raquo;</a>
                            @else
                                <button class="pagination-btn" disabled>Next &raquo;</button>
                            @endif
                        </div>
            @else
                <div class="pagination-info">
                    Menampilkan {{ count($redeems) }} data hasil filter
                </div>
            @endif
        </div>
    </div>
</body>

</html>