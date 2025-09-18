<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Transaksi Kas</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/laporan/laporancucisepuh.css') }}">
</head>

<body>
    @include('partials.navbar')
    <div class="container">
        <div class="header">
            <h1><i class="fas fa-boxes"></i> Laporan Transaksi Kas</h1>
            <div class="date-section">
                <div class="date-item">
                    <span>Tanggal</span>
                    <input type="date" id="start_date" name="start_date" class="date-input"
                        value="{{ $start ?? date('Y-m-d') }}">
                </div>
                <div class="date-item">
                    <span>s/d</span>
                    <input type="date" id="end_date" name="end_date" class="date-input"
                        value="{{ $end ?? date('Y-m-d') }}">
                </div>
            </div>

        </div>


        <div class="filters">
            <div class="filter-group">
                <label for="baki">Barang</label>
                <form method="GET" action="{{ route('laporancucisepuh') }}">
                    <select id="baki" name="barang">
                        <option value="">SEMUA BARANG</option>
                        @foreach(\App\Models\Barang::all() as $b)
                            <option value="{{ $b->namabarang }}" {{ request('barang') == $b->namabarang ? 'selected' : '' }}>
                                {{ $b->namabarang }}
                            </option>
                        @endforeach
                    </select>
                    <button type="submit">Filter</button>
                </form>
            </div>
        </div>

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
                        <th>Nama Pelanggan <i class="fas fa-sort"></i></th>
                        <th>Jenis Barang <i class="fas fa-sort"></i></th>
                        <th>Berat <i class="fas fa-sort"></i></th>
                        <th>Karat <i class="fas fa-sort"></i></th>
                        <th>Tanggal Cuci <i class="fas fa-sort"></i></th>
                        <th>Status <i class="fas fa-sort"></i></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($cucisepuhs as $item)
                        <tr>
                            <td>{{ $item->nama_pelanggan }}</td>
                            <td>{{ $item->jenis_barang }}</td>
                            <td>{{ $item->berat }}</td>
                            <td>{{ $item->karat }}</td>
                            <td>{{ $item->tanggal_cuci }}</td>
                            <td>{{ ucfirst($item->status) }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" style="text-align:center">Tidak ada data</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="pagination">
            <div class="pagination-info">
                Menampilkan 1 hingga 5 dari 50 entri
            </div>
            <div class="pagination-controls">
                <button class="pagination-btn">&laquo; Previous</button>
                <button class="pagination-btn active">1</button>
                <button class="pagination-btn">2</button>
                <button class="pagination-btn">3</button>
                <button class="pagination-btn">4</button>
                <button class="pagination-btn">5</button>
                <button class="pagination-btn">Next &raquo;</button>
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