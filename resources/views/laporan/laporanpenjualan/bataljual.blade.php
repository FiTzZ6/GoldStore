<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Batal Jual</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/laporan/laporanpenjualan/laporanbataljual.css') }}">
</head>

<body>
    @include('partials.navbar')
    <div class="container">
        <!-- Header -->
        <div class="header">
            <h1><i class="fas fa-boxes"></i> Laporan Batal Jual</h1>
            <div class="header-info">
                <span>Tanggal: {{ $tanggal }}</span>
            </div>
        </div>

        <!-- Filter -->
        <form method="GET" action="{{ route('penjualanbataljual') }}">
            <div class="filter-section">
                <div class="filter-item">
                    <label for="start_date">Tanggal</label>
                    <input type="date" id="start_date" name="start_date" value="{{ $start }}">
                </div>
                <div class="filter-item">
                    <label for="end_date">s/d</label>
                    <input type="date" id="end_date" name="end_date" value="{{ $end }}">
                </div>
                <div class="filter-item">
                    <label for="barang">Barang</label>
                    <select id="barang" name="barang">
                        <option value="SEMUA BARANG">SEMUA BARANG</option>
                        @foreach($listBarang as $b)
                            <option value="{{ $b }}" {{ $barang == $b ? 'selected' : '' }}>
                                {{ $b }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="filter-item">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-filter"></i> Filter
                    </button>
                </div>
            </div>
        </form>

        <div class="search-container">
            <div class="search-box">
                <i class="fas fa-search"></i>
                <input type="text" id="global-search" placeholder="Cari semua penjualan">
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
                        <th>Faktur Batal</th>
                        <th>Faktur Jual</th>
                        <th>Barang</th>
                        <th>Staff</th>
                        <th>Qty</th>
                        <th>Harga</th>
                        <th>Tanggal</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($data as $d)
                        <tr>
                            <td><input type="checkbox" class="rowCheckbox"></td>
                            <td>{{ $d->fakturbataljual }}</td>
                            <td>{{ $d->fakturjual }}</td>
                            <td>{{ $d->namabarang }}</td>
                            <td>{{ $d->namastaff }}</td>
                            <td>{{ $d->quantity }}</td>
                            <td>{{ number_format($d->harga, 0, ',', '.') }}</td>
                            <td>{{ $d->created_at->format('d-m-Y H:i') }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" style="text-align:center;">Tidak ada data</td>
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
                    $startPage = max($data->currentPage() - 2, 1);
                    $endPage = min($startPage + 4, $data->lastPage());
                    if ($endPage - $startPage < 4) {
                        $startPage = max($endPage - 4, 1);
                    }
                @endphp

                @if ($startPage > 1)
                    <a href="{{ $data->url(1) }}" class="pagination-btn">1</a>
                    @if ($startPage > 2)
                        <span class="pagination-btn">...</span>
                    @endif
                @endif

                @for ($page = $startPage; $page <= $endPage; $page++)
                    @if ($page == $data->currentPage())
                        <button class="pagination-btn active">{{ $page }}</button>
                    @else
                        <a href="{{ $data->url($page) }}" class="pagination-btn">{{ $page }}</a>
                    @endif
                @endfor

                @if ($endPage < $data->lastPage())
                    @if ($endPage < $data->lastPage() - 1)
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

        // Checkbox Select All
        document.getElementById('selectAll').addEventListener('change', function () {
            const checked = this.checked;
            document.querySelectorAll('.rowCheckbox').forEach(cb => cb.checked = checked);
        });

        // Ambil baris terpilih
        function getSelectedRows() {
            const rows = document.querySelectorAll('tbody tr');
            let selected = [];
            rows.forEach(row => {
                const cb = row.querySelector('.rowCheckbox');
                if (cb.checked || document.querySelectorAll('.rowCheckbox:checked').length === 0) {
                    const cols = row.querySelectorAll('td');
                    selected.push({
                        fakturBatal: cols[1].innerText,
                        fakturJual: cols[2].innerText,
                        barang: cols[3].innerText,
                        staff: cols[4].innerText,
                        qty: cols[5].innerText,
                        harga: cols[6].innerText,
                        tanggal: cols[7].innerText
                    });
                }
            });
            return selected;
        }

        // Export CSV
        function exportCSV() {
            const rows = getSelectedRows();
            if (!rows.length) return alert('Tidak ada data untuk diexport!');
            let csv = 'Faktur Batal,Faktur Jual,Barang,Staff,Qty,Harga,Tanggal\n';
            rows.forEach(r => csv += `${r.fakturBatal},${r.fakturJual},${r.barang},${r.staff},${r.qty},${r.harga},${r.tanggal}\n`);
            const blob = new Blob([csv], { type: 'text/csv' });
            const link = document.createElement('a');
            link.href = URL.createObjectURL(blob);
            link.download = 'batal_jual.csv';
            link.click();
        }

        // Export Excel
        function exportExcel() {
            const rows = getSelectedRows();
            if (!rows.length) return alert('Tidak ada data untuk diexport!');
            let table = `<table><tr><th>Faktur Batal</th><th>Faktur Jual</th><th>Barang</th><th>Staff</th><th>Qty</th><th>Harga</th><th>Tanggal</th></tr>`;
            rows.forEach(r => table += `<tr><td>${r.fakturBatal}</td><td>${r.fakturJual}</td><td>${r.barang}</td><td>${r.staff}</td><td>${r.qty}</td><td>${r.harga}</td><td>${r.tanggal}</td></tr>`);
            table += '</table>';
            const blob = new Blob([table], { type: 'application/vnd.ms-excel' });
            const link = document.createElement('a');
            link.href = URL.createObjectURL(blob);
            link.download = 'batal_jual.xls';
            link.click();
        }

        // Export PDF (Surat Resmi)
        function exportPDF() {
            const rows = getSelectedRows();
            if (!rows.length) return alert('Tidak ada data untuk dicetak!');
            let tableRows = '';
            rows.forEach(r => tableRows += `<tr><td>${r.fakturBatal}</td><td>${r.fakturJual}</td><td>${r.barang}</td><td>${r.staff}</td><td>${r.qty}</td><td>${r.harga}</td><td>${r.tanggal}</td></tr>`);
            const surat = `
        <h2 style="text-align:center;">SURAT RESMI LAPORAN BATAL JUAL</h2>
        <p>Kepada Yth,</p>
        <p><b>Pimpinan Perusahaan</b></p>
        <p>di Tempat</p><br>
        <p>Dengan hormat,</p>
        <p>Bersama ini kami sampaikan daftar penjualan batal:</p>
        <table border="1" cellspacing="0" cellpadding="5" width="100%">
            <thead>
                <tr><th>Faktur Batal</th><th>Faktur Jual</th><th>Barang</th><th>Staff</th><th>Qty</th><th>Harga</th><th>Tanggal</th></tr>
            </thead>
            <tbody>${tableRows}</tbody>
        </table>
        <br><br><p>Hormat kami,</p><br><br>
        <p><b>(................................)</b></p>
    `;
            const win = window.open("", "", "width=900,height=600");
            win.document.write(`<html><head><title>Surat Resmi Batal Jual</title></head><body>${surat}</body></html>`);
            win.document.close();
            win.print();
            win.close();
        }

        // Handle Export
        function handleExport(value) {
            if (value === 'csv') exportCSV();
            if (value === 'excel') exportExcel();
            if (value === 'pdf') exportPDF();
        }
        
    </script>
</body>

</html>