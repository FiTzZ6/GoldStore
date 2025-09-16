<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Penjualan Staff</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/laporan/laporanpenjualan/laporanpenjualanstaff.css') }}">
</head>

<body>
    @include('partials.navbar')
    <div class="container">
        <!-- Header -->
        <div class="header">
            <h1><i class="fas fa-boxes"></i> Laporan Penjualan Staff</h1>
            <div class="header-info">
                <span>Tanggal: {{ $tanggal }}</span>
            </div>
        </div>

        <!-- Filter -->
        <form method="GET" action="{{ route('penjualanstaff') }}">
            <div class="filters">
                <div class="filter-group">
                    <label for="staff">STAFF</label>
                    <select name="staff" id="staff">
                        <option value="SEMUA STAFF">SEMUA STAFF</option>
                        @foreach($listStaff as $s)
                            <option value="{{ $s }}" {{ $staff == $s ? 'selected' : '' }}>{{ $s }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="filter-group">
                    <label for="start_date">Tanggal</label>
                    <input type="date" id="start_date" name="start_date" value="{{ $start }}">
                </div>
                <div class="filter-group">
                    <label for="end_date">s/d</label>
                    <input type="date" id="end_date" name="end_date" value="{{ $end }}">
                </div>

                <button type="submit" class="btn btn-primary"><i class="fas fa-filter"></i> Filter</button>
            </div>
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
                        <th>Staff</th>
                        <th>No Faktur</th>
                        <th>Barang</th>
                        <th>Jumlah</th>
                        <th>Total</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($data as $row)
                        <tr>
                            <td>{{ $row->created_at->format('Y-m-d') }}</td>
                            <td>{{ $row->namastaff }}</td>
                            <td>{{ $row->nofaktur }}</td>
                            <td>{{ $row->namabarang }}</td>
                            <td>{{ $row->quantity }}</td>
                            <td>{{ number_format($row->total, 0, ',', '.') }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" style="text-align:center;">Tidak ada data</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="pagination">
            <div class="pagination-info">
                Menampilkan {{ $data->firstItem() }} hingga {{ $data->lastItem() }} dari {{ $data->total() }} entri
            </div>
            <div class="pagination-controls">
                {{-- Tombol Previous --}}
                @if ($data->onFirstPage())
                    <button class="pagination-btn" disabled>&laquo; Previous</button>
                @else
                    <a href="{{ $data->previousPageUrl() }}" class="pagination-btn">&laquo; Previous</a>
                @endif

                {{-- Tombol Halaman Maksimal 5 --}}
                @php
                    $start = max($data->currentPage() - 2, 1);
                    $end = min($start + 4, $data->lastPage());
                    if ($end - $start < 4) {
                        $start = max($end - 4, 1);
                    }
                @endphp

                @if ($start > 1)
                    <a href="{{ $data->url(1) }}" class="pagination-btn">1</a>
                    @if ($start > 2)
                        <span class="pagination-btn">...</span>
                    @endif
                @endif

                @for ($page = $start; $page <= $end; $page++)
                    @if ($page == $data->currentPage())
                        <button class="pagination-btn active">{{ $page }}</button>
                    @else
                        <a href="{{ $data->url($page) }}" class="pagination-btn">{{ $page }}</a>
                    @endif
                @endfor

                @if ($end < $data->lastPage())
                    @if ($end < $data->lastPage() - 1)
                        <span class="pagination-btn">...</span>
                    @endif
                    <a href="{{ $data->url($data->lastPage()) }}" class="pagination-btn">{{ $data->lastPage() }}</a>
                @endif

                {{-- Tombol Next --}}
                @if ($data->hasMorePages())
                    <a href="{{ $data->nextPageUrl() }}" class="pagination-btn">Next &raquo;</a>
                @else
                    <button class="pagination-btn" disabled>Next &raquo;</button>
                @endif
            </div>
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