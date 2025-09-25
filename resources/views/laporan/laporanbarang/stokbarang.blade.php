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

        <form method="GET" action="{{ route('stokbarang') }}">
            <div class="filters">
                <div class="filter-group">
                    <label for="toko">Toko:</label>
                    <select id="toko" name="toko">
                        <option value="SEMUA TOKO" {{ request('toko') == 'SEMUA TOKO' ? 'selected' : '' }}>SEMUA TOKO
                        </option>
                        @foreach($toko as $t)
                            <option value="{{ $t->kdtoko }}" {{ request('toko') == $t->kdtoko ? 'selected' : '' }}>
                                {{ $t->namatoko }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="filter-group">
                    <label for="jenis">Jenis:</label>
                    <select id="jenis" name="jenis">
                        <option value="SEMUA JENIS" {{ request('jenis') == 'SEMUA JENIS' ? 'selected' : '' }}>SEMUA JENIS
                        </option>
                        @foreach($jenis as $j)
                            <option value="{{ $j->kdjenis }}" {{ request('jenis') == $j->kdjenis ? 'selected' : '' }}>
                                {{ $j->namajenis }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="filter-group">
                    <label for="baki">Baki:</label>
                    <select id="baki" name="baki">
                        <option value="SEMUA Baki" {{ request('baki') == 'SEMUA Baki' ? 'selected' : '' }}>SEMUA Baki
                        </option>
                        @foreach($baki as $b)
                            <option value="{{ $b->kdbaki }}" {{ request('baki') == $b->kdbaki ? 'selected' : '' }}>
                                {{ $b->namabaki }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <button type="submit">Filter</button>
            </div>
        </form>


        <div class="search-container">
            <div class="search-box">
                <i class="fas fa-search"></i>
                <input type="text" id="global-search" placeholder="Cari semua data...">
            </div>
        </div>

        <div class="tools">
            <select onchange="handleExport(this.value)">
                <option value="">Pilih Export</option>
                <option value="pdf">Export PDF (Surat Resmi)</option>
                <option value="csv">Export CSV</option>
                <option value="excel">Export Excel</option>
            </select>
        </div>

        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th><input type="checkbox" id="selectAll"></th>
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
                            <td><input type="checkbox" class="rowCheckbox"></td>
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


        // Select All
        document.getElementById("selectAll").addEventListener("change", function () {
            let checked = this.checked;
            document.querySelectorAll(".rowCheckbox").forEach(cb => cb.checked = checked);
        });

        // Ambil baris terpilih
        function getSelectedRows() {
            let selected = [];
            let checkboxes = document.querySelectorAll(".rowCheckbox:checked");
            let rows = (checkboxes.length > 0)
                ? Array.from(checkboxes).map(cb => cb.closest("tr"))
                : Array.from(document.querySelectorAll("table tbody tr"));

            rows.forEach(row => {
                let cols = row.querySelectorAll("td");
                selected.push({
                    baki: cols[1].innerText,
                    jml: cols[2].innerText,
                    vate: cols[3].innerText,
                    stock: cols[4].innerText,
                    berat: cols[5].innerText
                });
            });

            return selected;
        }

        // Export CSV
        function exportCSV() {
            let rows = getSelectedRows();
            let csv = "Baki,Jml Asal Ptg,Vate,Stock Ptg,Stock Berat\n";
            rows.forEach(r => {
                csv += `${r.baki},${r.jml},${r.vate},${r.stock},${r.berat}\n`;
            });
            let blob = new Blob([csv], { type: "text/csv" });
            let link = document.createElement("a");
            link.href = URL.createObjectURL(blob);
            link.download = "stokbarang.csv";
            link.click();
        }

        // Export Excel
        function exportExcel() {
            let rows = getSelectedRows();
            let table = `<table><tr><th>Baki</th><th>Jml Asal Ptg</th><th>Vate</th><th>Stock Ptg</th><th>Stock Berat</th></tr>`;
            rows.forEach(r => {
                table += `<tr><td>${r.baki}</td><td>${r.jml}</td><td>${r.vate}</td><td>${r.stock}</td><td>${r.berat}</td></tr>`;
            });
            table += `</table>`;
            let blob = new Blob([table], { type: "application/vnd.ms-excel" });
            let link = document.createElement("a");
            link.href = URL.createObjectURL(blob);
            link.download = "stokbarang.xls";
            link.click();
        }

        // Export PDF (Surat Resmi)
        function exportPDF() {
            let rows = getSelectedRows();
            let tableRows = "";
            rows.forEach(r => {
                tableRows += `<tr>
                <td>${r.baki}</td>
                <td>${r.jml}</td>
                <td>${r.vate}</td>
                <td>${r.stock}</td>
                <td>${r.berat}</td>
            </tr>`;
            });

            let surat = `
        <h2 style="text-align:center;">SURAT RESMI LAPORAN STOK BARANG</h2>
        <p>Kepada Yth,</p>
        <p><b>Pimpinan Perusahaan</b></p>
        <p>di Tempat</p><br>
        <p>Dengan hormat,</p>
        <p>Bersama ini kami sampaikan daftar stok barang:</p>
        <table border="1" cellspacing="0" cellpadding="5" width="100%">
            <thead><tr><th>Baki</th><th>Jml Asal Ptg</th><th>Vate</th><th>Stock Ptg</th><th>Stock Berat</th></tr></thead>
            <tbody>${tableRows}</tbody>
        </table>
        <br><br><p>Hormat kami,</p><br><br>
        <p><b>(................................)</b></p>
        `;

            let win = window.open("", "", "width=800,height=600");
            win.document.write(`<html><head><title>Surat Resmi</title></head><body>${surat}</body></html>`);
            win.document.close();
            win.print();
            win.close();
        }

        // Handle Export
        function handleExport(value) {
            if (value === "pdf") exportPDF();
            if (value === "csv") exportCSV();
            if (value === "excel") exportExcel();
        }

    </script>
</body>

</html>