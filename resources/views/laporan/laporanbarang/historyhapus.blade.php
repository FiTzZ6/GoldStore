<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan histori hapus</title>
    <link rel="stylesheet" href="{{ asset('css/laporan/laporanbarang/laporan.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">


</head>

<body>
    @include('partials.navbar')
    <div class="container">
        <div class="header">
            <h1><i class="fas fa-boxes"></i> Laporan Persediaan Barang</h1>
            <div class="header-info">
                <div>Tanggal: 07/08/2025 - 07/08/2028</div>
            </div>
        </div>

        <div class="filters">
            <div class="filter-group">
                <label for="baki">Baki:</label>
                <select id="baki">
                    <option value="">SEMUA Baki</option>
                    @foreach($bakis as $baki)
                        <option value="{{ $baki->kdbaki }}">{{ $baki->kdbaki }}</option>
                    @endforeach
                </select>
            </div>

            <div class="filter-group">
                <label for="toko">Toko:</label>
                <select id="toko">
                    <option value="">SEMUA TOKO</option>
                    @foreach($tokos as $toko)
                        <option value="{{ $toko->kdtoko }}">{{ $toko->kdtoko }}</option>
                    @endforeach
                </select>
            </div>

            <div class="filter-group">
                <button id="btn-cari" class="btn btn-primary"><i class="fas fa-search"></i> Cari</button>
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
                        <th>kd Barang</th>
                        <th>Barcode</th>
                        <th>Nama Barang</th>
                        <th>Kd Kategori</th>
                        <th>Kd Baki</th>
                        <th>Berat</th>
                        <th>Kadar</th>
                        <th>Kd Toko</th>
                        <th>Faktur Barang Hapus</th>
                        <th>Tgl Hapus</th>
                    </tr>
                </thead>
                <tbody id="table-body">
                    @foreach($barangTerhapus as $bt)
                        <tr>
                            <td>{{ $bt->kdbarang }}</td>
                            <td>{{ $bt->barcode }}</td>
                            <td>{{ $bt->namabarang }}</td>
                            <td>{{ $bt->kdkategori }}</td>
                            <td>{{ $bt->kdbaki }}</td>
                            <td>{{ $bt->berat }}</td>
                            <td>{{ $bt->kadar }}</td>
                            <td>{{ $bt->kdtoko }}</td>
                            <td>{{ $bt->fakturbaranghapus }}</td>
                            <td>{{ $bt->tanggalhapus }}</td>
                        </tr>
                    @endforeach
                    <tr id="no-data" style="display:none;">
                        <td colspan="10" style="text-align:center; font-weight:bold;">BARANG TIDAK TERSEDIA</td>
                    </tr>
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
        document.addEventListener('DOMContentLoaded', function () {
            const bakiSelect = document.getElementById('baki');
            const tokoSelect = document.getElementById('toko'); // harus sesuai id HTML (toko kecil)
            const btnCari = document.getElementById('btn-cari');
            const tableBody = document.getElementById('table-body');
            const noDataRow = document.getElementById('no-data');

            function filterTable() {
                const selectedBaki = bakiSelect.value;
                const selectedToko = tokoSelect.value;
                const rows = tableBody.querySelectorAll('tr:not(#no-data)');
                let foundAny = false;

                rows.forEach(row => {
                    const rowBaki = row.cells[4].textContent.trim();
                    const rowToko = row.cells[7].textContent.trim();

                    const matchBaki = selectedBaki === '' || selectedBaki === rowBaki;
                    const matchToko = selectedToko === '' || selectedToko === rowToko;

                    if (matchBaki && matchToko) {
                        row.style.display = '';
                        foundAny = true;
                    } else {
                        row.style.display = 'none';
                    }
                });

                noDataRow.style.display = foundAny ? 'none' : '';
            }

            // Filter saat tombol Cari ditekan
            btnCari.addEventListener('click', filterTable);

            // Filter live saat dropdown berubah
            bakiSelect.addEventListener('change', filterTable);
            tokoSelect.addEventListener('change', filterTable);

            // Simple sort functionality
            document.querySelectorAll('th').forEach(header => {
                header.addEventListener('click', function () {
                    const columnIndex = Array.from(this.parentElement.children).indexOf(this);
                    const rowsArray = Array.from(tableBody.querySelectorAll('tr:not(#no-data)'));
                    const isNumeric = !isNaN(parseFloat(rowsArray[0].cells[columnIndex].textContent));

                    rowsArray.sort((a, b) => {
                        const aValue = a.cells[columnIndex].textContent;
                        const bValue = b.cells[columnIndex].textContent;

                        if (isNumeric) {
                            return parseFloat(aValue) - parseFloat(bValue);
                        } else {
                            return aValue.localeCompare(bValue);
                        }
                    });

                    if (this.getAttribute('data-sorted') === 'asc') {
                        rowsArray.reverse();
                        this.setAttribute('data-sorted', 'desc');
                    } else {
                        this.setAttribute('data-sorted', 'asc');
                    }

                    rowsArray.forEach(row => tableBody.appendChild(row));
                });
            });
        });
    </script>


</body>

</html>