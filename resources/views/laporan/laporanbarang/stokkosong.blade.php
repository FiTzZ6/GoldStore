<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Stok Kosong</title>
    <link rel="stylesheet" href="{{ asset('css/laporan/laporanbarang/laporan.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>

<body>
    @include('partials.navbar')

    <div class="container">
        <!-- Header -->
        <div class="header">
            <h1><i class="fas fa-boxes"></i> Laporan Stok Kosong</h1>
            <div class="header-info">
                <span>Tanggal: {{ $tanggal }}</span>
            </div>
        </div>

        <!-- Search -->
        <div class="search-container">
            <div class="search-box">
                <i class="fas fa-search"></i>
                <input type="text" id="global-search" placeholder="Cari semua data...">
            </div>
        </div>

        <!-- Tools -->
        <div class="tools">
            <select onchange="handleExport(this.value)">
                <option value="">Pilih Export</option>
                <option value="csv">Export CSV</option>
                <option value="excel">Export Excel</option>
                <option value="pdf">Export PDF (Surat Resmi)</option>
            </select>
        </div>

        <!-- Table -->
        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th><input type="checkbox" id="selectAll"></th>
                        <th>Jenis <i class="fas fa-sort"></i></th>
                        <th>Jml PTG <i class="fas fa-sort"></i></th>
                        <th>Jml Stok <i class="fas fa-sort"></i></th>
                        <th>Jml Berat Asli <i class="fas fa-sort"></i></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($stokKosong as $item)
                        <tr>
                            <td><input type="checkbox" class="rowCheckbox"></td>
                            <td>{{ $item->barcode }}</td>
                            <td>{{ $item->namabarang }}</td>
                            <td>{{ $item->stok }}</td>
                            <td>{{ $item->berat }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" style="text-align:center;">Tidak ada stok kosong</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
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
        // Pencarian sederhana
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

        // Sorting sederhana
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

                // Balikkan urutan jika sudah asc
                if (this.getAttribute('data-sorted') === 'asc') {
                    rows.reverse();
                    this.setAttribute('data-sorted', 'desc');
                } else {
                    this.setAttribute('data-sorted', 'asc');
                }

                // Render ulang
                const tbody = document.querySelector('tbody');
                tbody.innerHTML = '';
                rows.forEach(row => tbody.appendChild(row));
            });
        });

        // Checkbox Select All
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
                    jenis: cols[1].innerText,
                    jmlptg: cols[2].innerText,
                    jmlstok: cols[3].innerText,
                    berat: cols[4].innerText
                });
            });
            return selected;
        }

        // Export CSV
        function exportCSV() {
            let rows = getSelectedRows();
            let csv = "Jenis,Jml PTG,Jml Stok,Jml Berat Asli\n";
            rows.forEach(r => {
                csv += `${r.jenis},${r.jmlptg},${r.jmlstok},${r.berat}\n`;
            });
            let blob = new Blob([csv], { type: "text/csv" });
            let link = document.createElement("a");
            link.href = URL.createObjectURL(blob);
            link.download = "stok_kosong.csv";
            link.click();
        }

        // Export Excel
        function exportExcel() {
            let rows = getSelectedRows();
            let table = `<table><tr><th>Jenis</th><th>Jml PTG</th><th>Jml Stok</th><th>Jml Berat Asli</th></tr>`;
            rows.forEach(r => {
                table += `<tr><td>${r.jenis}</td><td>${r.jmlptg}</td><td>${r.jmlstok}</td><td>${r.berat}</td></tr>`;
            });
            table += `</table>`;
            let blob = new Blob([table], { type: "application/vnd.ms-excel" });
            let link = document.createElement("a");
            link.href = URL.createObjectURL(blob);
            link.download = "stok_kosong.xls";
            link.click();
        }

        // Export PDF (Surat Resmi)
        function exportPDF() {
            let rows = getSelectedRows();
            let tableRows = "";
            rows.forEach(r => {
                tableRows += `<tr>
                    <td>${r.jenis}</td>
                    <td>${r.jmlptg}</td>
                    <td>${r.jmlstok}</td>
                    <td>${r.berat}</td>
                </tr>`;
            });

            let surat = `
            <h2 style="text-align:center;">SURAT RESMI LAPORAN STOK KOSONG</h2>
            <p>Kepada Yth,</p>
            <p><b>Pimpinan Perusahaan</b></p>
            <p>di Tempat</p><br>
            <p>Dengan hormat,</p>
            <p>Bersama ini kami sampaikan daftar stok kosong:</p>
            <table border="1" cellspacing="0" cellpadding="5" width="100%">
                <thead><tr><th>Jenis</th><th>Jml PTG</th><th>Jml Stok</th><th>Jml Berat Asli</th></tr></thead>
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
            if (value === "csv") exportCSV();
            if (value === "excel") exportExcel();
            if (value === "pdf") exportPDF();
        }
    </script>
</body>

</html>