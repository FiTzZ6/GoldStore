<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="{{ asset('css/laporan/laporanbarang/stokbarang.css') }}">
    <title>Modern Inventory Display</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

</head>

<body>
    @include('partials.navbar')
    <div class="container">
        <div class="header">
            <h1><i class="fas fa-boxes"></i> Sistem Manajemen Inventori</h1>
            <div class="header-info">
                <span>Tanggal: 07/08/2025 - 07/08/2028</span>
            </div>
        </div>

        <div class="filters">
            <div class="filter-group">
                <label for="toko">Toko:</label>
                <select id="toko">
                    <option>SEMUA TOKO</option>
                    <option>Toko A</option>
                    <option>Toko B</option>
                    <option>Toko C</option>
                </select>
            </div>

            <div class="filter-group">
                <label for="jenis">Jenis:</label>
                <select id="jenis">
                    <option>SEMUA JENIS</option>
                    <option>Elektronik</option>
                    <option>Pakaian</option>
                    <option>Makanan</option>
                </select>
            </div>

            <div class="filter-group">
                <label for="baki">Baki:</label>
                <select id="baki">
                    <option>SEMUA Baki</option>
                    <option>Baki 1</option>
                    <option>Baki 2</option>
                    <option>Baki 3</option>
                </select>
            </div>
        </div>

        <div class="search-container">
            <div class="search-box">
                <i class="fas fa-search"></i>
                <input type="text" id="global-search" placeholder="Cari semua data...">
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
                        <th>Baki <i class="fas fa-sort"></i></th>
                        <th>Jml Asal Ptg <i class="fas fa-sort"></i></th>
                        <th>Vate <i class="fas fa-sort"></i></th>
                        <th>Stock Ptg <i class="fas fa-sort"></i></th>
                        <th>Stock Berat <i class="fas fa-sort"></i></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($stokjual as $item)
                        <tr>
                            <td>{{ $item->barcode }}</td>
                            <td>{{ $item->stok }}</td>
                            <td>{{ $item->nofaktur }}</td>
                            <td>{{ $item->stok }}</td>
                            <td>{{ $item->berat }} gr</td>
                        </tr>
                    @endforeach
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