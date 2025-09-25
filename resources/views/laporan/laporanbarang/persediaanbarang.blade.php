<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Stok Kosong</title>
    <link rel="stylesheet" href="{{ asset('css/laporan/laporanbarang/persediabarang.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

</head>

<body>
    @include('partials.navbar')
    <div class="container">
        <div class="header">
            <h1><i class="fas fa-boxes"></i> Laporan Persediaan Barang</h1>
            <div class="header-info">
                <span>Tanggal: {{ $tanggal }}</span>
            </div>
        </div>

        <div class="filters">
            <form method="GET" action="{{ route('persediaanbarang') }}" class="filter-form">
                <div class="filter-group">
                    <label for="kategori">Kategori:</label>
                    <select name="kategori" id="kategori">
                        <option value="all">SEMUA KATEGORI</option>
                        @foreach($kategoriList as $k)
                            <option value="{{ $k->kdkategori }}" {{ $kategori == $k->kdkategori ? 'selected' : '' }}>
                                {{ $k->namakategori }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="filter-group">
                    <label for="jenis">Jenis:</label>
                    <select name="jenis" id="jenis">
                        <option value="all">SEMUA JENIS</option>
                        @foreach($jenisList as $j)
                            <option value="{{ $j->kdjenis }}" {{ $jenis == $j->kdjenis ? 'selected' : '' }}>
                                {{ $j->namajenis }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="filter-group">
                    <label for="baki">Baki:</label>
                    <select name="baki" id="baki">
                        <option value="all">SEMUA BAKI</option>
                        @foreach($bakiList as $b)
                            <option value="{{ $b->kdbaki }}" {{ $baki == $b->kdbaki ? 'selected' : '' }}>
                                {{ $b->namabaki }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <button type="submit" class="btn-filter">Filter</button>
            </form>
        </div>


        <div class="search-container">
            <div class="search-box">
                <i class="fas fa-search"></i>
                <input type="text" id="global-search" placeholder="Cari semua data...">
            </div>
        </div>

        <div class="tools">
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
                        <th>Kode Barang</th>
                        <th>Nama Barang</th>
                        <th>Kategori</th>
                        <th>Jenis</th>
                        <th>Baki</th>
                        <th>Stok</th>
                        <th>Berat (gr)</th>
                        <th>Harga Beli</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($barang as $b)
                        <tr>
                            <td><input type="checkbox" class="rowCheckbox"></td>
                            <td>{{ $b->kdbarang }}</td>
                            <td>{{ $b->namabarang }}</td>
                            <td>{{ $b->KategoriBarang->namakategori ?? '-' }}</td>
                            <td>{{ $b->JenisBarang->namajenis ?? '-' }}</td>
                            <td>{{ $b->baki->namabaki ?? '-' }}</td>
                            <td>{{ $b->stok ?? 0 }}</td>
                            <td>{{ $b->berat ?? 0 }}</td>
                            <td>Rp {{ number_format($b->hargabeli, 0, ',', '.') }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" style="text-align:center;">Tidak ada barang tersedia</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="pagination">
            <div class="pagination-info">
                Menampilkan {{ $barang->firstItem() }} hingga {{ $barang->lastItem() }} dari {{ $barang->total() }}
                entri
            </div>
            <div class="pagination-controls">
                {{-- Tombol Previous --}}
                @if ($barang->onFirstPage())
                    <button class="pagination-btn" disabled>&laquo; Previous</button>
                @else
                    <a href="{{ $barang->previousPageUrl() }}" class="pagination-btn">&laquo; Previous</a>
                @endif

                {{-- Tombol Halaman Maksimal 5 --}}
                @php
                    $start = max($barang->currentPage() - 2, 1);
                    $end = min($start + 4, $barang->lastPage());
                    if ($end - $start < 4) {
                        $start = max($end - 4, 1);
                    }
                @endphp

                @if ($start > 1)
                    <a href="{{ $barang->url(1) }}" class="pagination-btn">1</a>
                    @if ($start > 2)
                        <span class="pagination-btn">...</span>
                    @endif
                @endif

                @for ($page = $start; $page <= $end; $page++)
                    @if ($page == $barang->currentPage())
                        <button class="pagination-btn active">{{ $page }}</button>
                    @else
                        <a href="{{ $barang->url($page) }}" class="pagination-btn">{{ $page }}</a>
                    @endif
                @endfor

                @if ($end < $barang->lastPage())
                    @if ($end < $barang->lastPage() - 1)
                        <span class="pagination-btn">...</span>
                    @endif
                    <a href="{{ $barang->url($barang->lastPage()) }}" class="pagination-btn">{{ $barang->lastPage() }}</a>
                @endif

                {{-- Tombol Next --}}
                @if ($barang->hasMorePages())
                    <a href="{{ $barang->nextPageUrl() }}" class="pagination-btn">Next &raquo;</a>
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

        // Select All
        document.getElementById("selectAll").addEventListener("change", function () {
            let checked = this.checked;
            document.querySelectorAll(".rowCheckbox").forEach(cb => cb.checked = checked);
        });

        // Ambil data baris terpilih
        function getSelectedRows() {
            let selected = [];
            let checkboxes = document.querySelectorAll(".rowCheckbox:checked");
            let rows = (checkboxes.length > 0)
                ? Array.from(checkboxes).map(cb => cb.closest("tr"))
                : Array.from(document.querySelectorAll("table tbody tr"));

            rows.forEach(row => {
                let cols = row.querySelectorAll("td");
                selected.push({
                    kode: cols[1].innerText,
                    nama: cols[2].innerText,
                    kategori: cols[3].innerText,
                    jenis: cols[4].innerText,
                    baki: cols[5].innerText,
                    stok: cols[6].innerText,
                    berat: cols[7].innerText,
                    harga: cols[8].innerText
                });
            });

            return selected;
        }

        // Export CSV
        function exportCSV() {
            let rows = getSelectedRows();
            let csv = "Kode Barang,Nama Barang,Kategori,Jenis,Baki,Stok,Berat (gr),Harga Beli\n";
            rows.forEach(r => {
                csv += `${r.kode},${r.nama},${r.kategori},${r.jenis},${r.baki},${r.stok},${r.berat},${r.harga}\n`;
            });
            let blob = new Blob([csv], { type: "text/csv" });
            let link = document.createElement("a");
            link.href = URL.createObjectURL(blob);
            link.download = "persediaanbarang.csv";
            link.click();
        }

        // Export Excel
        function exportExcel() {
            let rows = getSelectedRows();
            let table = `<table><tr><th>Kode Barang</th><th>Nama Barang</th><th>Kategori</th><th>Jenis</th><th>Baki</th><th>Stok</th><th>Berat (gr)</th><th>Harga Beli</th></tr>`;
            rows.forEach(r => {
                table += `<tr><td>${r.kode}</td><td>${r.nama}</td><td>${r.kategori}</td><td>${r.jenis}</td><td>${r.baki}</td><td>${r.stok}</td><td>${r.berat}</td><td>${r.harga}</td></tr>`;
            });
            table += `</table>`;
            let blob = new Blob([table], { type: "application/vnd.ms-excel" });
            let link = document.createElement("a");
            link.href = URL.createObjectURL(blob);
            link.download = "persediaanbarang.xls";
            link.click();
        }

        // Export PDF (Surat Resmi)
        function exportPDF() {
            let rows = getSelectedRows();
            let tableRows = "";
            rows.forEach(r => {
                tableRows += `<tr>
                    <td>${r.kode}</td>
                    <td>${r.nama}</td>
                    <td>${r.kategori}</td>
                    <td>${r.jenis}</td>
                    <td>${r.baki}</td>
                    <td>${r.stok}</td>
                    <td>${r.berat}</td>
                    <td>${r.harga}</td>
                </tr>`;
            });

            let surat = `
            <h2 style="text-align:center;">SURAT RESMI LAPORAN PERSEDIAAN BARANG</h2>
            <p>Kepada Yth,</p>
            <p><b>Pimpinan Perusahaan</b></p>
            <p>di Tempat</p><br>
            <p>Dengan hormat,</p>
            <p>Bersama ini kami sampaikan daftar persediaan barang:</p>
            <table border="1" cellspacing="0" cellpadding="5" width="100%">
                <thead><tr><th>Kode Barang</th><th>Nama Barang</th><th>Kategori</th><th>Jenis</th><th>Baki</th><th>Stok</th><th>Berat (gr)</th><th>Harga Beli</th></tr></thead>
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