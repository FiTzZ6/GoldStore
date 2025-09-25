<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Transaksi Kas</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/laporan/laporanpenjualan/laporanpenjualan.css') }}">
</head>

<body>
    @include('partials.navbar')
    <div class="container">
        <div class="header">
            <h1><i class="fas fa-boxes"></i> Laporan Transaksi Kas</h1>
        </div>

        <div class="filters">
            <div class="filter-group">
                <label for="type">Jenis Transaksi</label>
                <select id="type" name="type">
                    <option value="">SEMUA</option>
                    <option value="masuk" {{ request('type') == 'masuk' ? 'selected' : '' }}>MASUK</option>
                    <option value="keluar" {{ request('type') == 'keluar' ? 'selected' : '' }}>KELUAR</option>
                </select>
            </div>
        </div>

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

        <div class="table-container mt-4">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th><input type="checkbox" id="selectAll"></th>
                        <th>Tanggal</th>
                        <th>Jenis</th>
                        <th>Kategori</th>
                        <th>Jumlah</th>
                        <th>Keterangan</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($kas as $row)
                        <tr>
                            <td><input type="checkbox" class="rowCheckbox"></td>
                            <td>{{ \Carbon\Carbon::parse($row->tanggal)->format('d-m-Y') }}</td>
                            <td>{{ strtoupper($row->type) }}</td>
                            <td>{{ $row->parameterKas->parameterkas ?? '-' }}</td>
                            <td>{{ number_format($row->jumlahkas, 0, ',', '.') }}</td>
                            <td>{{ $row->keterangan }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>


        <div class="pagination">
            <div class="pagination-info">
                Menampilkan {{ $kas->firstItem() }} hingga {{ $kas->lastItem() }} dari {{ $kas->total() }} entri
            </div>
            <div class="pagination-controls">
                {{-- Tombol Previous --}}
                @if ($kas->onFirstPage())
                    <button class="pagination-btn" disabled>&laquo; Previous</button>
                @else
                    <a href="{{ $kas->previousPageUrl() }}" class="pagination-btn">&laquo; Previous</a>
                @endif

                {{-- Tombol Halaman Maksimal 5 --}}
                @php
                    $start = max($kas->currentPage() - 2, 1);
                    $end = min($start + 4, $kas->lastPage());
                    if ($end - $start < 4) {
                        $start = max($end - 4, 1);
                    }
                @endphp

                @if ($start > 1)
                    <a href="{{ $kas->url(1) }}" class="pagination-btn">1</a>
                    @if ($start > 2)
                        <span class="pagination-btn">...</span>
                    @endif
                @endif

                @for ($page = $start; $page <= $end; $page++)
                    @if ($page == $kas->currentPage())
                        <button class="pagination-btn active">{{ $page }}</button>
                    @else
                        <a href="{{ $kas->url($page) }}" class="pagination-btn">{{ $page }}</a>
                    @endif
                @endfor

                @if ($end < $kas->lastPage())
                    @if ($end < $kas->lastPage() - 1)
                        <span class="pagination-btn">...</span>
                    @endif
                    <a href="{{ $kas->url($kas->lastPage()) }}" class="pagination-btn">{{ $kas->lastPage() }}</a>
                @endif

                {{-- Tombol Next --}}
                @if ($kas->hasMorePages())
                    <a href="{{ $kas->nextPageUrl() }}" class="pagination-btn">Next &raquo;</a>
                @else
                    <button class="pagination-btn" disabled>Next &raquo;</button>
                @endif
            </div>
        </div>

    </div>

    <script>
        document.getElementById('type').addEventListener('change', function () {
            const type = this.value;
            const url = `?type=${type}`;
            window.location.href = url;
        });

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
                        tanggal: cols[1].innerText,
                        type: cols[2].innerText,
                        kategori: cols[3].innerText,
                        jumlah: cols[4].innerText,
                        keterangan: cols[5].innerText
                    });
                }
            });
            return selected;
        }

        // Export CSV
        function exportCSV() {
            const rows = getSelectedRows();
            if (!rows.length) return alert('Tidak ada data untuk diexport!');
            let csv = 'Tanggal,Jenis,Kategori,Jumlah,Keterangan\n';
            rows.forEach(r => csv += `${r.tanggal},${r.type},${r.kategori},${r.jumlah},${r.keterangan}\n`);
            const blob = new Blob([csv], { type: 'text/csv' });
            const link = document.createElement('a');
            link.href = URL.createObjectURL(blob);
            link.download = 'transaksi_kas.csv';
            link.click();
        }

        // Export Excel
        function exportExcel() {
            const rows = getSelectedRows();
            if (!rows.length) return alert('Tidak ada data untuk diexport!');
            let table = `<table><tr><th>Tanggal</th><th>Jenis</th><th>Kategori</th><th>Jumlah</th><th>Keterangan</th></tr>`;
            rows.forEach(r => table += `<tr><td>${r.tanggal}</td><td>${r.type}</td><td>${r.kategori}</td><td>${r.jumlah}</td><td>${r.keterangan}</td></tr>`);
            table += '</table>';
            const blob = new Blob([table], { type: 'application/vnd.ms-excel' });
            const link = document.createElement('a');
            link.href = URL.createObjectURL(blob);
            link.download = 'transaksi_kas.xls';
            link.click();
        }

        // Export PDF
        function exportPDF() {
            const rows = getSelectedRows();
            if (!rows.length) return alert('Tidak ada data untuk dicetak!');
            let tableRows = '';
            rows.forEach(r => tableRows += `<tr><td>${r.tanggal}</td><td>${r.type}</td><td>${r.kategori}</td><td>${r.jumlah}</td><td>${r.keterangan}</td></tr>`);
            const surat = `
        <h2 style="text-align:center;">SURAT RESMI LAPORAN TRANSAKSI KAS</h2>
        <p>Kepada Yth,</p>
        <p><b>Pimpinan Perusahaan</b></p>
        <p>di Tempat</p><br>
        <p>Dengan hormat,</p>
        <p>Bersama ini kami sampaikan daftar transaksi kas:</p>
        <table border="1" cellspacing="0" cellpadding="5" width="100%">
            <thead>
                <tr><th>Tanggal</th><th>Jenis</th><th>Kategori</th><th>Jumlah</th><th>Keterangan</th></tr>
            </thead>
            <tbody>${tableRows}</tbody>
        </table>
        <br><br><p>Hormat kami,</p><br><br>
        <p><b>(................................)</b></p>
    `;
            const win = window.open("", "", "width=900,height=600");
            win.document.write(`<html><head><title>Surat Resmi Transaksi Kas</title></head><body>${surat}</body></html>`);
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