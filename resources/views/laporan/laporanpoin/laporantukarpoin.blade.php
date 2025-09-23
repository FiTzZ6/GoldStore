<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Tukar Poin</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/laporan/laporantukarpoin/laporanpoin.css') }}">
</head>

<body>
    @include('partials.navbar')
    <div class="container">
        <div class="header">
            <h1><i class="fas fa-gift"></i> Laporan Tukar Poin</h1>
        </div>

        {{-- Filter Tanggal --}}
        <form method="GET" action="{{ route('laporantukarpoin') }}">
            <div class="date-section">
                <div class="date-item">
                    <span>Tanggal</span>
                    <input type="date" name="start_date" value="{{ $start }}">
                </div>
                <div class="date-item">
                    <span>s/d</span>
                    <input type="date" name="end_date" value="{{ $end }}">
                </div>
            </div>

            {{-- Filter Merchandise --}}
            <div class="filters">
                <div class="filter-group">
                    <label for="merch">Merchandise</label>
                    <select id="merch" name="merch">
                        <option value="">-- Semua --</option>
                        @foreach($merchList as $m)
                            <option value="{{ $m->id }}" {{ $merch == $m->id ? 'selected' : '' }}>
                                {{ $m->nama_merch }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>

            <button type="submit" class="btn btn-primary">
                <i class="fas fa-filter"></i> Filter
            </button>
        </form>

        {{-- Pencarian Global --}}
        <div class="search-container">
            <div class="search-box">
                <i class="fas fa-search"></i>
                <input type="text" id="global-search" placeholder="Cari semua riwayat redeem">
            </div>
        </div>

        {{-- Tools --}}
        <div class="tools">
            <button class="btn btn-primary"><i class="fas fa-eye"></i> Show 10 rows</button>
            <button class="btn btn-secondary"><i class="fas fa-copy"></i> Copy</button>
            <button class="btn btn-secondary"><i class="fas fa-file-csv"></i> CSV</button>
            <button class="btn btn-secondary"><i class="fas fa-file-excel"></i> Excel</button>
            <button class="btn btn-danger"><i class="fas fa-file-pdf"></i> PDF</button>
            <button class="btn btn-success"><i class="fas fa-print"></i> Print</button>
        </div>

        {{-- Tabel Riwayat Redeem --}}
        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th>Tanggal</th>
                        <th>Nama Pelanggan</th>
                        <th>Merchandise</th>
                        <th>Poin Digunakan</th>
                        <th>Stok Sisa</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($redeems as $r)
                        <tr>
                            <td>{{ \Carbon\Carbon::parse($r->created_at)->format('d-m-Y H:i') }}</td>
                            <td>{{ $r->namapelanggan }}</td>
                            <td>{{ $r->nama_merch }}</td>
                            <td>{{ number_format($r->poin_digunakan, 0, ',', '.') }}</td>
                            <td>{{ $r->stok_sisa }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        <div class="pagination">
            @if ($redeems instanceof \Illuminate\Pagination\LengthAwarePaginator)
                        <div class="pagination-info">
                            Menampilkan {{ $redeems->firstItem() }} hingga {{ $redeems->lastItem() }} dari
                            {{ $redeems->total() }} entri
                        </div>
                        <div class="pagination-controls">
                            {{-- Tombol Previous --}}
                            @if ($redeems->onFirstPage())
                                <button class="pagination-btn" disabled>&laquo; Previous</button>
                            @else
                                <a href="{{ $redeems->previousPageUrl() }}" class="pagination-btn">&laquo; Previous</a>
                            @endif

                            {{-- Tombol Halaman Maksimal 5 --}}
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

    <script>
        // Pencarian Global
        document.getElementById('global-search').addEventListener('keyup', function () {
            const searchText = this.value.toLowerCase();
            const rows = document.querySelectorAll('tbody tr');

            rows.forEach(row => {
                let found = false;
                const cells = row.querySelectorAll('td');
                cells.forEach(cell => {
                    if (cell.textContent.toLowerCase().includes(searchText)) {
                        found = true;
                    }
                });
                row.style.display = found ? '' : 'none';
            });
        });

        // Sort Kolom
        document.querySelectorAll('th').forEach(header => {
            header.addEventListener('click', function () {
                const columnIndex = Array.from(this.parentElement.children).indexOf(this);
                const rows = Array.from(document.querySelectorAll('tbody tr'));
                const isNumeric = !isNaN(parseFloat(rows[0].querySelectorAll('td')[columnIndex].textContent));

                rows.sort((a, b) => {
                    const aValue = a.querySelectorAll('td')[columnIndex].textContent;
                    const bValue = b.querySelectorAll('td')[columnIndex].textContent;

                    return isNumeric
                        ? parseFloat(aValue.replace(/\./g, '')) - parseFloat(bValue.replace(/\./g, ''))
                        : aValue.localeCompare(bValue);
                });

                if (this.getAttribute('data-sorted') === 'asc') {
                    rows.reverse();
                    this.setAttribute('data-sorted', 'desc');
                } else {
                    this.setAttribute('data-sorted', 'asc');
                }

                const tbody = document.querySelector('tbody');
                tbody.innerHTML = '';
                rows.forEach(row => tbody.appendChild(row));
            });
        });
    </script>
</body>

</html>