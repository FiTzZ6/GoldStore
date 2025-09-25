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
            <input type="text" id="global-search" placeholder="Cari semua data...">
            <select onchange="handleExport(this.value)">
                <option value="">Pilih Export</option>
                <option value="csv">Export CSV</option>
                <option value="excel">Export Excel</option>
                <option value="pdf">Export PDF (Surat Resmi)</option>
            </select>
        </div>

        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th><input type="checkbox" id="selectAll"></th>
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
                            <td><input type="checkbox" class="rowCheckbox"></td>
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
            const tokoSelect = document.getElementById('toko');
            const btnCari = document.getElementById('btn-cari');
            const tableBody = document.getElementById('table-body');
            const noDataRow = document.getElementById('no-data');

            // Filter Table
            function filterTable() {
                const selectedBaki = bakiSelect.value;
                const selectedToko = tokoSelect.value;
                const rows = tableBody.querySelectorAll('tr:not(#no-data)');
                let foundAny = false;

                rows.forEach(row => {
                    const rowBaki = row.cells[5].textContent.trim();
                    const rowToko = row.cells[8].textContent.trim();

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

            btnCari.addEventListener('click', filterTable);
            bakiSelect.addEventListener('change', filterTable);
            tokoSelect.addEventListener('change', filterTable);

            // Global Search
            document.getElementById('global-search').addEventListener('keyup', function () {
                const searchText = this.value.toLowerCase();
                const rows = tableBody.querySelectorAll('tr:not(#no-data)');
                let foundAny = false;

                rows.forEach(row => {
                    let match = Array.from(row.cells).some(td => td.textContent.toLowerCase().includes(searchText));
                    row.style.display = match ? '' : 'none';
                    if (match) foundAny = true;
                });
                noDataRow.style.display = foundAny ? 'none' : '';
            });

            // Sorting
            document.querySelectorAll('th').forEach(header => {
                header.addEventListener('click', function () {
                    const columnIndex = Array.from(this.parentElement.children).indexOf(this);
                    const rowsArray = Array.from(tableBody.querySelectorAll('tr:not(#no-data)'));
                    if (rowsArray.length === 0) return;
                    const isNumeric = !isNaN(parseFloat(rowsArray[0].cells[columnIndex].textContent));

                    rowsArray.sort((a, b) => {
                        const aValue = a.cells[columnIndex].textContent;
                        const bValue = b.cells[columnIndex].textContent;
                        return isNumeric ? parseFloat(aValue) - parseFloat(bValue) : aValue.localeCompare(bValue);
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

            // Select All
            document.getElementById("selectAll").addEventListener("change", function () {
                let checked = this.checked;
                document.querySelectorAll(".rowCheckbox").forEach(cb => cb.checked = checked);
            });

            // Get selected rows
            function getSelectedRows() {
                let selected = [];
                let checkboxes = document.querySelectorAll(".rowCheckbox:checked");
                let rows = (checkboxes.length > 0)
                    ? Array.from(checkboxes).map(cb => cb.closest("tr"))
                    : Array.from(tableBody.querySelectorAll("tr:not(#no-data)"));

                rows.forEach(row => {
                    let cols = row.cells;
                    selected.push(Array.from(cols).slice(1).map(td => td.innerText)); // skip checkbox
                });
                return selected;
            }

            // Export CSV
            function exportCSV() {
                let rows = getSelectedRows();
                if (rows.length === 0) return alert('Tidak ada data dipilih!');
                let csv = rows.map(r => r.join(',')).join('\n');
                let blob = new Blob([csv], { type: 'text/csv' });
                let link = document.createElement('a');
                link.href = URL.createObjectURL(blob);
                link.download = 'histori_hapus.csv';
                link.click();
            }

            // Export Excel
            function exportExcel() {
                let rows = getSelectedRows();
                if (rows.length === 0) return alert('Tidak ada data dipilih!');
                let table = '<table><tr><th>Kd Barang</th><th>Barcode</th><th>Nama Barang</th><th>Kd Kategori</th><th>Kd Baki</th><th>Berat</th><th>Kadar</th><th>Kd Toko</th><th>Faktur Barang Hapus</th><th>Tgl Hapus</th></tr>';
                rows.forEach(r => { table += `<tr>${r.map(c => `<td>${c}</td>`).join('')}</tr>`; });
                table += '</table>';
                let blob = new Blob([table], { type: 'application/vnd.ms-excel' });
                let link = document.createElement('a');
                link.href = URL.createObjectURL(blob);
                link.download = 'histori_hapus.xls';
                link.click();
            }

            // Export PDF (Surat)
            function exportPDF() {
                let rows = getSelectedRows();
                if (rows.length === 0) return alert('Tidak ada data dipilih!');
                let tableRows = '';
                rows.forEach(r => tableRows += `<tr>${r.map(c => `<td>${c}</td>`).join('')}</tr>`);
                let surat = `
                    <h2 style="text-align:center;">SURAT RESMI LAPORAN BARANG TERHAPUS</h2>
                    <table border="1" cellspacing="0" cellpadding="5" width="100%">
                        <thead><tr><th>Kd Barang</th><th>Barcode</th><th>Nama Barang</th><th>Kd Kategori</th><th>Kd Baki</th><th>Berat</th><th>Kadar</th><th>Kd Toko</th><th>Faktur Barang Hapus</th><th>Tgl Hapus</th></tr></thead>
                        <tbody>${tableRows}</tbody>
                    </table>`;
                let win = window.open("", "", "width=800,height=600");
                win.document.write(`<html><head><title>Surat Resmi</title></head><body>${surat}</body></html>`);
                win.document.close();
                win.print();
                win.close();
            }

            function handleExport(value) {
                if (value === 'csv') exportCSV();
                if (value === 'excel') exportExcel();
                if (value === 'pdf') exportPDF();
            }

            window.handleExport = handleExport;
        });
    </script>


</body>

</html>