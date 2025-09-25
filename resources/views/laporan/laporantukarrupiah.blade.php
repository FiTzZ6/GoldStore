<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Tukar Rupiah</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/laporan/laporantukarrp.css') }}">
</head>

<body>
    @include('partials.navbar')
    <div class="container">
        <div class="header">
            <h1><i class="fas fa-boxes"></i> Laporan Tukar Rupiah</h1>
        </div>

        <form method="GET" action="{{ route('laporantukarrupiah') }}">
            <div class="date-section">
                <div class="date-item">
                    <span>Tanggal</span>
                    <input type="date" name="start_date" value="{{ $start }}">
                </div>
                <div class="date-item">
                    <span>s/d</span>
                    <input type="date" name="end_date" value="{{ $end }}">
                </div>
            </div>

            <div class="filters">
                <div class="filter-group">
                    <label for="mata_uang">Mata Uang</label>
                    <select id="mata_uang" name="mata_uang">
                        <option value="">-- Semua --</option>
                        @foreach($mataUangList as $mu)
                            <option value="{{ $mu }}" {{ $mataUang == $mu ? 'selected' : '' }}>
                                {{ $mu }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>

            <button type="submit" class="btn btn-primary">
                <i class="fas fa-filter"></i> Filter
            </button>
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
                        <th>Tanggal</th>
                        <th>Mata Uang</th>
                        <th>Jumlah</th>
                        <th>Total Rupiah</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($transaksi as $t)
                        <tr>
                            <td><input type="checkbox" class="rowCheckbox"></td>
                            <td>{{ $t->created_at }}</td>
                            <td>{{ $t->mata_uang }}</td>
                            <td>{{ number_format($t->jumlah, 2) }}</td>
                            <td>{{ number_format($t->total_rupiah, 0, ',', '.') }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="pagination">
            @if ($transaksi instanceof \Illuminate\Pagination\LengthAwarePaginator)
                        <div class="pagination-info">
                            Menampilkan {{ $transaksi->firstItem() }} hingga {{ $transaksi->lastItem() }} dari
                            {{ $transaksi->total() }} entri
                        </div>
                        <div class="pagination-controls">
                            {{-- Tombol Previous --}}
                            @if ($transaksi->onFirstPage())
                                <button class="pagination-btn" disabled>&laquo; Previous</button>
                            @else
                                <a href="{{ $transaksi->previousPageUrl() }}" class="pagination-btn">&laquo; Previous</a>
                            @endif

                            {{-- Tombol Halaman Maksimal 5 --}}
                            @php
                                $start = max($transaksi->currentPage() - 2, 1);
                                $end = min($start + 4, $transaksi->lastPage());
                                if ($end - $start < 4) {
                                    $start = max($end - 4, 1);
                                }
                            @endphp

                            @if ($start > 1)
                                <a href="{{ $transaksi->url(1) }}" class="pagination-btn">1</a>
                                @if ($start > 2)
                                    <span class="pagination-btn">...</span>
                                @endif
                            @endif

                            @for ($page = $start; $page <= $end; $page++)
                                @if ($page == $transaksi->currentPage())
                                    <button class="pagination-btn active">{{ $page }}</button>
                                @else
                                    <a href="{{ $transaksi->url($page) }}" class="pagination-btn">{{ $page }}</a>
                                @endif
                            @endfor

                            @if ($end < $transaksi->lastPage())
                                @if ($end < $transaksi->lastPage() - 1)
                                    <span class="pagination-btn">...</span>
                                @endif
                                <a href="{{ $transaksi->url($transaksi->lastPage()) }}"
                                    class="pagination-btn">{{ $transaksi->lastPage() }}</a>
                            @endif

                            {{-- Tombol Next --}}
                            @if ($transaksi->hasMorePages())
                                <a href="{{ $transaksi->nextPageUrl() }}" class="pagination-btn">Next &raquo;</a>
                            @else
                                <button class="pagination-btn" disabled>Next &raquo;</button>
                            @endif
                        </div>
            @else
                <div class="pagination-info">
                    Menampilkan {{ count($transaksi) }} data hasil filter
                </div>
            @endif
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
        function exportCSV() {
            const rows = getAllRows();
            if (!rows.length) return alert('Tidak ada data untuk diexport!');
            let csv = 'Tanggal,Mata Uang,Jumlah,Total Rupiah\n';
            rows.forEach(r => csv += `${r.tanggal},${r.mata_uang},${r.jumlah},${r.total_rupiah}\n`);
            const blob = new Blob([csv], { type: 'text/csv' });
            const link = document.createElement('a');
            link.href = URL.createObjectURL(blob);
            link.download = 'laporan_tukar_rupiah.csv';
            link.click();
        }

        function exportExcel() {
            const rows = getAllRows();
            if (!rows.length) return alert('Tidak ada data untuk diexport!');
            let table = `<table><tr><th>Tanggal</th><th>Mata Uang</th><th>Jumlah</th><th>Total Rupiah</th></tr>`;
            rows.forEach(r => {
                table += `<tr><td>${r.tanggal}</td><td>${r.mata_uang}</td><td>${r.jumlah}</td><td>${r.total_rupiah}</td></tr>`;
            });
            table += '</table>';
            const blob = new Blob([table], { type: 'application/vnd.ms-excel' });
            const link = document.createElement('a');
            link.href = URL.createObjectURL(blob);
            link.download = 'laporan_tukar_rupiah.xls';
            link.click();
        }

        function exportPDF() {
            const rows = getAllRows();
            if (!rows.length) return alert('Tidak ada data untuk dicetak!');
            let tableRows = '';
            rows.forEach(r => {
                tableRows += `<tr>
                    <td>${r.tanggal}</td>
                    <td>${r.mata_uang}</td>
                    <td>${r.jumlah}</td>
                    <td>${r.total_rupiah}</td>
                </tr>`;
            });

            const surat = `
                <h2 style="text-align:center;">SURAT RESMI LAPORAN TUKAR RUPIAH</h2>
                <p>Kepada Yth,</p>
                <p><b>Pimpinan Perusahaan</b></p>
                <p>di Tempat</p><br>
                <p>Dengan hormat,</p>
                <p>Bersama ini kami sampaikan laporan transaksi tukar rupiah:</p>
                <table border="1" cellspacing="0" cellpadding="5" width="100%">
                    <thead>
                        <tr><th>Tanggal</th><th>Mata Uang</th><th>Jumlah</th><th>Total Rupiah</th></tr>
                    </thead>
                    <tbody>${tableRows}</tbody>
                </table>
                <br><br><p>Hormat kami,</p><br><br>
                <p><b>(................................)</b></p>
            `;

            const win = window.open("", "", "width=900,height=600");
            win.document.write(`<html><head><title>Surat Resmi Tukar Rupiah</title></head><body>${surat}</body></html>`);
            win.document.close();
            win.print();
            win.close();
        }

        function handleExport(value) {
            if (value === 'csv') exportCSV();
            if (value === 'excel') exportExcel();
            if (value === 'pdf') exportPDF();
        }
        
    </script>
</body>

</html>