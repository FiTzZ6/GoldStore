<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Tukar Rupiah</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/laporan/laporantukarrp.css') }}">
</head>

<body>
    @include('partials.navbar')
    <div class="container">
        <div class="header">
            <h1><i class="fas fa-boxes"></i> Laporan Tukar Rupiah</h1>
        </div>

        <form method="GET" action="{{ route('laporantukarrupiah') }}">
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

            <div class="filters">
                <div class="filter-group">
                    <label for="mata_uang">Mata Uang</label>
                    <select id="mata_uang" name="mata_uang">
                        <option value="">-- Semua --</option>
                        @foreach($mataUangList as $mu)
                            <option value="{{ $mu }}" {{ $mataUang == $mu ? 'selected' : '' }}>
                                {{ $mu }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>

            <button type="submit" class="btn btn-primary">
                <i class="fas fa-filter"></i> Filter
            </button>
        </form>

        <div class="search-container">
            <div class="search-box">
                <i class="fas fa-search"></i>
                <input type="text" id="global-search" placeholder="Cari semua penjualan">
            </div>
        </div>

        <div class="tools">
            <button class="btn btn-primary"><i class="fas fa-eye"></i> Show 10 rows</button>
            <button class="btn btn-secondary"><i class="fas fa-copy"></i> Copy</button>
            <button class="btn btn-secondary"><i class="fas fa-file-csv"></i> CSV</button>
            <button class="btn btn-secondary"><i class="fas fa-file-excel"></i> Excel</button>
            <button class="btn btn-danger"><i class="fas fa-file-pdf"></i> PDF</button>
            <button class="btn btn-success"><i class="fas fa-print"></i> Print</button>
        </div>

        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th>Tanggal</th>
                        <th>Mata Uang</th>
                        <th>Jumlah</th>
                        <th>Total Rupiah</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($transaksi as $t)
                        <tr>
                            <td>{{ $t->created_at }}</td>
                            <td>{{ $t->mata_uang }}</td>
                            <td>{{ number_format($t->jumlah, 2) }}</td>
                            <td>{{ number_format($t->total_rupiah, 0, ',', '.') }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="pagination">
            @if ($transaksi instanceof \Illuminate\Pagination\LengthAwarePaginator)
                        <div class="pagination-info">
                            Menampilkan {{ $transaksi->firstItem() }} hingga {{ $transaksi->lastItem() }} dari
                            {{ $transaksi->total() }} entri
                        </div>
                        <div class="pagination-controls">
                            {{-- Tombol Previous --}}
                            @if ($transaksi->onFirstPage())
                                <button class="pagination-btn" disabled>&laquo; Previous</button>
                            @else
                                <a href="{{ $transaksi->previousPageUrl() }}" class="pagination-btn">&laquo; Previous</a>
                            @endif

                            {{-- Tombol Halaman Maksimal 5 --}}
                            @php
                                $start = max($transaksi->currentPage() - 2, 1);
                                $end = min($start + 4, $transaksi->lastPage());
                                if ($end - $start < 4) {
                                    $start = max($end - 4, 1);
                                }
                            @endphp

                            @if ($start > 1)
                                <a href="{{ $transaksi->url(1) }}" class="pagination-btn">1</a>
                                @if ($start > 2)
                                    <span class="pagination-btn">...</span>
                                @endif
                            @endif

                            @for ($page = $start; $page <= $end; $page++)
                                @if ($page == $transaksi->currentPage())
                                    <button class="pagination-btn active">{{ $page }}</button>
                                @else
                                    <a href="{{ $transaksi->url($page) }}" class="pagination-btn">{{ $page }}</a>
                                @endif
                            @endfor

                            @if ($end < $transaksi->lastPage())
                                @if ($end < $transaksi->lastPage() - 1)
                                    <span class="pagination-btn">...</span>
                                @endif
                                <a href="{{ $transaksi->url($transaksi->lastPage()) }}"
                                    class="pagination-btn">{{ $transaksi->lastPage() }}</a>
                            @endif

                            {{-- Tombol Next --}}
                            @if ($transaksi->hasMorePages())
                                <a href="{{ $transaksi->nextPageUrl() }}" class="pagination-btn">Next &raquo;</a>
                            @else
                                <button class="pagination-btn" disabled>Next &raquo;</button>
                            @endif
                        </div>
            @else
                <div class="pagination-info">
                    Menampilkan {{ count($transaksi) }} data hasil filter
                </div>
            @endif
        </div>
    </div>

    <script>
        // Simple search functionality
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

        // Simple sort functionality
        document.querySelectorAll('th').forEach(header => {
            header.addEventListener('click', function () {
                const columnIndex = Array.from(this.parentElement.children).indexOf(this);
                const rows = Array.from(document.querySelectorAll('tbody tr'));
                const isNumeric = !isNaN(parseFloat(rows[0].querySelectorAll('td')[columnIndex].textContent));

                rows.sort((a, b) => {
                    const aValue = a.querySelectorAll('td')[columnIndex].textContent;
                    const bValue = b.querySelectorAll('td')[columnIndex].textContent;

                    if (isNumeric) {
                        return parseFloat(aValue) - parseFloat(bValue);
                    } else {
                        return aValue.localeCompare(bValue);
                    }
                });

                // Reverse if already sorted
                if (this.getAttribute('data-sorted') === 'asc') {
                    rows.reverse();
                    this.setAttribute('data-sorted', 'desc');
                } else {
                    this.setAttribute('data-sorted', 'asc');
                }

                // Append sorted rows
                const tbody = document.querySelector('tbody');
                tbody.innerHTML = '';
                rows.forEach(row => tbody.appendChild(row));
            });
        });
    </script>
</body>

</html>