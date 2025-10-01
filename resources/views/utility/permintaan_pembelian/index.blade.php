<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Riwayat Permintaan Pembelian</title>
    <link rel="stylesheet" href="{{ asset('css/utility/permintaan_pembelian/index.css') }}">
</head>

<body>
    @include('partials.navbar')

    <div class="container">
        <div class="header" style="display:flex; justify-content:space-between; align-items:center;">
            <h1>Riwayat Permintaan Pembelian</h1>
            <a href="{{ route('formulir_pp') }}">
                <button style="padding:8px 14px; border:none; border-radius:6px; background:#6c757d; color:white;">
                    ⬅ Kembali
                </button>
            </a>
        </div>

        <div class="search-box" style="margin:15px 0;">
            <form method="GET" action="{{ route('formulir_pp.riwayat') }}" style="display:flex; gap:10px;">
                <input type="text" name="q" value="{{ request('q') }}" placeholder="Cari No. PP / Nama Barang..."
                    style="flex:1; padding:8px; border:1px solid #ccc; border-radius:6px;">
                <button type="submit"
                    style="padding:8px 14px; border:none; border-radius:6px; background:#0d6efd; color:white;">
                    🔍 Cari
                </button>
            </form>
        </div>

        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th>No. PP</th>
                        <th>Toko</th>
                        <th>Nama Barang</th>
                        <th>Jumlah</th>
                        <th>Satuan</th>
                        <th>Supplier</th>
                        <th>Tanggal Permintaan</th>
                        <th>Tanggal Dibutuhkan</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($riwayat as $pp)
                        <tr>
                            <td>{{ $pp->nopp }}</td>
                            <td>{{ $pp->toko->namatoko ?? '-' }}</td>
                            <td>{{ $pp->namabarang }}</td>
                            <td>{{ $pp->jumlah }}</td>
                            <td>{{ $pp->satuan }}</td>
                            <td>{{ $pp->supplier_pilih}}</td>
                            <td>{{ \Carbon\Carbon::parse($pp->tanggal_permintaan)->format('d-m-Y H:i') }}</td>
                            <td>{{ \Carbon\Carbon::parse($pp->tanggal_dibutuhkan)->format('d-m-Y') }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" style="text-align:center;">Belum ada data riwayat</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        <div class="pagination" style="margin-top:15px;">
            @if ($riwayat instanceof \Illuminate\Pagination\LengthAwarePaginator)
                        <div class="pagination-info">
                            Menampilkan {{ $riwayat->firstItem() }} hingga {{ $riwayat->lastItem() }} dari
                            {{ $riwayat->total() }} entri
                        </div>
                        <div class="pagination-controls"
                            style="display:flex; gap:6px; flex-wrap:wrap; justify-content:center; margin-top:10px;">
                            {{-- Tombol Previous --}}
                            @if ($riwayat->onFirstPage())
                                <button class="pagination-btn" disabled>&laquo; Previous</button>
                            @else
                                <a href="{{ $riwayat->appends(request()->query())->previousPageUrl() }}" class="pagination-btn">&laquo;
                                    Previous</a>
                            @endif

                            {{-- Tombol Halaman Maksimal 5 --}}
                            @php
                                $start = max($riwayat->currentPage() - 2, 1);
                                $end = min($start + 4, $riwayat->lastPage());
                                if ($end - $start < 4) {
                                    $start = max($end - 4, 1);
                                }
                            @endphp

                            @if ($start > 1)
                                <a href="{{ $riwayat->appends(request()->query())->url(1) }}" class="pagination-btn">1</a>
                                @if ($start > 2)
                                    <span class="pagination-btn">...</span>
                                @endif
                            @endif

                            @for ($page = $start; $page <= $end; $page++)
                                @if ($page == $riwayat->currentPage())
                                    <button class="pagination-btn active">{{ $page }}</button>
                                @else
                                    <a href="{{ $riwayat->appends(request()->query())->url($page) }}" class="pagination-btn">{{ $page }}</a>
                                @endif
                            @endfor

                            @if ($end < $riwayat->lastPage())
                                @if ($end < $riwayat->lastPage() - 1)
                                    <span class="pagination-btn">...</span>
                                @endif
                                <a href="{{ $riwayat->appends(request()->query())->url($riwayat->lastPage()) }}"
                                    class="pagination-btn">{{ $riwayat->lastPage() }}</a>
                            @endif

                            {{-- Tombol Next --}}
                            @if ($riwayat->hasMorePages())
                                <a href="{{ $riwayat->appends(request()->query())->nextPageUrl() }}" class="pagination-btn">Next
                                    &raquo;</a>
                            @else
                                <button class="pagination-btn" disabled>Next &raquo;</button>
                            @endif
                        </div>
            @else
                <div class="pagination-info">
                    Menampilkan {{ count($riwayat) }} data hasil filter
                </div>
            @endif
        </div>

    </div>
</body>

</html>