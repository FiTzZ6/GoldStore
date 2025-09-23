<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Laporan Stok Opname</title>
    <link rel="stylesheet" href="{{ asset('css/laporan/laporanopname/lpopname.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        /* ====== CSS modern yang kamu kasih ====== */
        {{ file_get_contents(public_path('css/laporan/laporanopname/lpopname.css')) }}
    </style>
</head>

<body>
    @include('partials.navbar')

    <div class="container">
        <div class="header">
            <h1><i class="fas fa-clipboard-list"></i> LAPORAN STOK OPNAME</h1>
        </div>

        <!-- Filter -->
        <form method="POST" action="{{ route('laporan.stokopname.filter') }}">
            @csrf
            <div class="date-section">
                <div class="date-item">
                    <span>Dari</span>
                    <input type="date" name="start_date" value="{{ $start }}">
                </div>
                <div class="date-item">
                    <span>Sampai</span>
                    <input type="date" name="end_date" value="{{ $end }}">
                </div>
            </div>
            <button type="submit" class="btn btn-primary">
                <i class="fas fa-search"></i> Tampilkan
            </button>
        </form>

        <!-- Search -->
        <div class="search-container">
            <div class="search-box">
                <i class="fas fa-search"></i>
                <input type="text" id="searchInput" placeholder="Cari barang...">
            </div>
        </div>

        <!-- Tabel -->
        <div class="table-container" style="{{ $isFilter ? 'max-height:400px; overflow-y:auto;' : '' }}">
            <table id="reportTable">
                <thead>
                    <tr>
                        <th>Tanggal</th>
                        <th>Barcode</th>
                        <th>Nama Barang</th>
                        <th>Kategori</th>
                        <th>Baki</th>
                        <th>Berat</th>
                        <th>Kadar</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($reports as $r)
                        <tr>
                            <td>{{ $r->tanggal }}</td>
                            <td>{{ $r->barcode }}</td>
                            <td>{{ $r->namabarang }}</td>
                            <td>{{ $r->kategori ?? '-' }}</td>
                            <td>{{ $r->baki ?? '-' }}</td>
                            <td>{{ $r->berat }} gr</td>
                            <td>{{ $r->kadar }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center">Tidak ada data</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            {{-- Pagination --}}
            @if (!$isFilter)
                    <div class="pagination">
                        @if ($reports instanceof \Illuminate\Pagination\LengthAwarePaginator)
                                    <div class="pagination-info">
                                        Menampilkan {{ $reports->firstItem() }} hingga {{ $reports->lastItem() }} dari
                                        {{ $reports->total() }} entri
                                    </div>
                                    <div class="pagination-controls">
                                        {{-- Tombol Previous --}}
                                        @if ($reports->onFirstPage())
                                            <button class="pagination-btn" disabled>&laquo; Previous</button>
                                        @else
                                            <a href="{{ $reports->previousPageUrl() }}" class="pagination-btn">&laquo; Previous</a>
                                        @endif

                                        {{-- Tombol Halaman Maksimal 5 --}}
                                        @php
                                            $start = max($reports->currentPage() - 2, 1);
                                            $end = min($start + 4, $reports->lastPage());
                                            if ($end - $start < 4) {
                                                $start = max($end - 4, 1);
                                            }
                                        @endphp

                                        @if ($start > 1)
                                            <a href="{{ $reports->url(1) }}" class="pagination-btn">1</a>
                                            @if ($start > 2)
                                                <span class="pagination-btn">...</span>
                                            @endif
                                        @endif

                                        @for ($page = $start; $page <= $end; $page++)
                                            @if ($page == $reports->currentPage())
                                                <button class="pagination-btn active">{{ $page }}</button>
                                            @else
                                                <a href="{{ $reports->url($page) }}" class="pagination-btn">{{ $page }}</a>
                                            @endif
                                        @endfor

                                        @if ($end < $reports->lastPage())
                                            @if ($end < $reports->lastPage() - 1)
                                                <span class="pagination-btn">...</span>
                                            @endif
                                            <a href="{{ $reports->url($reports->lastPage()) }}"
                                                class="pagination-btn">{{ $reports->lastPage() }}</a>
                                        @endif

                                        {{-- Tombol Next --}}
                                        @if ($reports->hasMorePages())
                                            <a href="{{ $reports->nextPageUrl() }}" class="pagination-btn">Next &raquo;</a>
                                        @else
                                            <button class="pagination-btn" disabled>Next &raquo;</button>
                                        @endif
                                    </div>
                        @else
                            <div class="pagination-info">
                                Menampilkan {{ count($reports) }} data hasil filter
                            </div>
                        @endif
                    </div>
            @endif

        </div>
    </div>

    <script>
        // Fitur pencarian realtime
        document.getElementById('searchInput').addEventListener('keyup', function () {
            let filter = this.value.toLowerCase();
            let rows = document.querySelectorAll("#reportTable tbody tr");

            rows.forEach(row => {
                let text = row.innerText.toLowerCase();
                row.style.display = text.includes(filter) ? "" : "none";
            });
        });
    </script>
</body>

</html>