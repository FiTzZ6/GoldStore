<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Laporan Stok Opname</title>
    <link rel="stylesheet" href="{{ asset('css/laporan/laporanopname/lpopname.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        /* ====== CSS modern yang kamu kasih ====== */
        {{ file_get_contents(public_path('css/laporan/laporanopname/lpopname.css')) }}
    </style>
</head>

<body>
    @include('partials.navbar')

    <div class="container">
        <div class="header">
            <h1><i class="fas fa-clipboard-list"></i> LAPORAN STOK OPNAME</h1>
        </div>

        <!-- Filter -->
        <form method="POST" action="{{ route('laporan.stokopname.filter') }}">
            @csrf
            <div class="date-section">
                <div class="date-item">
                    <span>Dari</span>
                    <input type="date" name="start_date" value="{{ $start }}">
                </div>
                <div class="date-item">
                    <span>Sampai</span>
                    <input type="date" name="end_date" value="{{ $end }}">
                </div>
            </div>
            <button type="submit" class="btn btn-primary">
                <i class="fas fa-search"></i> Tampilkan
            </button>
        </form>

        <!-- Export -->
        <div class="tools">
            <select onchange="handleExport(this.value)">
                <option value="">Pilih Export</option>
                <option value="csv">Export CSV</option>
                <option value="excel">Export Excel</option>
                <option value="pdf">Export PDF (Surat Resmi)</option>
            </select>
        </div>

        <!-- Search -->
        <div class="search-container">
            <div class="search-box">
                <i class="fas fa-search"></i>
                <input type="text" id="searchInput" placeholder="Cari barang...">
            </div>
        </div>

        <!-- Tabel -->
        <div class="table-container" style="{{ $isFilter ? 'max-height:400px; overflow-y:auto;' : '' }}">
            <table id="reportTable">
                <thead>
                    <tr>
                        <th><input type="checkbox" id="selectAll"></th>
                        <th>Tanggal</th>
                        <th>Barcode</th>
                        <th>Nama Barang</th>
                        <th>Kategori</th>
                        <th>Baki</th>
                        <th>Berat</th>
                        <th>Kadar</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($reports as $r)
                        <tr>
                            <td><input type="checkbox" class="rowCheckbox"></td>
                            <td>{{ $r->tanggal }}</td>
                            <td>{{ $r->barcode }}</td>
                            <td>{{ $r->namabarang }}</td>
                            <td>{{ $r->kategori ?? '-' }}</td>
                            <td>{{ $r->baki ?? '-' }}</td>
                            <td>{{ $r->berat }} gr</td>
                            <td>{{ $r->kadar }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center">Tidak ada data</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            {{-- Pagination --}}
            @if (!$isFilter)
                    <div class="pagination">
                        @if ($reports instanceof \Illuminate\Pagination\LengthAwarePaginator)
                                    <div class="pagination-info">
                                        Menampilkan {{ $reports->firstItem() }} hingga {{ $reports->lastItem() }} dari
                                        {{ $reports->total() }} entri
                                    </div>
                                    <div class="pagination-controls">
                                        {{-- Tombol Previous --}}
                                        @if ($reports->onFirstPage())
                                            <button class="pagination-btn" disabled>&laquo; Previous</button>
                                        @else
                                            <a href="{{ $reports->previousPageUrl() }}" class="pagination-btn">&laquo; Previous</a>
                                        @endif

                                        {{-- Tombol Halaman Maksimal 5 --}}
                                        @php
                                            $start = max($reports->currentPage() - 2, 1);
                                            $end = min($start + 4, $reports->lastPage());
                                            if ($end - $start < 4) {
                                                $start = max($end - 4, 1);
                                            }
                                        @endphp

                                        @if ($start > 1)
                                            <a href="{{ $reports->url(1) }}" class="pagination-btn">1</a>
                                            @if ($start > 2)
                                                <span class="pagination-btn">...</span>
                                            @endif
                                        @endif

                                        @for ($page = $start; $page <= $end; $page++)
                                            @if ($page == $reports->currentPage())
                                                <button class="pagination-btn active">{{ $page }}</button>
                                            @else
                                                <a href="{{ $reports->url($page) }}" class="pagination-btn">{{ $page }}</a>
                                            @endif
                                        @endfor

                                        @if ($end < $reports->lastPage())
                                            @if ($end < $reports->lastPage() - 1)
                                                <span class="pagination-btn">...</span>
                                            @endif
                                            <a href="{{ $reports->url($reports->lastPage()) }}"
                                                class="pagination-btn">{{ $reports->lastPage() }}</a>
                                        @endif

                                        {{-- Tombol Next --}}
                                        @if ($reports->hasMorePages())
                                            <a href="{{ $reports->nextPageUrl() }}" class="pagination-btn">Next &raquo;</a>
                                        @else
                                            <button class="pagination-btn" disabled>Next &raquo;</button>
                                        @endif
                                    </div>
                        @else
                            <div class="pagination-info">
                                Menampilkan {{ count($reports) }} data hasil filter
                            </div>
                        @endif
                    </div>
            @endif

        </div>
    </div>

    <script>
        // Fitur pencarian realtime
        document.getElementById('searchInput').addEventListener('keyup', function () {
            let filter = this.value.toLowerCase();
            let rows = document.querySelectorAll("#reportTable tbody tr");

            rows.forEach(row => {
                let text = row.innerText.toLowerCase();
                row.style.display = text.includes(filter) ? "" : "none";
            });
        });

        // Select All
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
                        barcode: cols[2].innerText,
                        namabarang: cols[3].innerText,
                        kategori: cols[4].innerText,
                        baki: cols[5].innerText,
                        berat: cols[6].innerText,
                        kadar: cols[7].innerText
                    });
                }
            });
            return selected;
        }

        // Export CSV
        function exportCSV() {
            const rows = getSelectedRows();
            if (!rows.length) return alert('Tidak ada data untuk diexport!');
            let csv = 'Tanggal,Barcode,Nama Barang,Kategori,Baki,Berat,Kadar\n';
            rows.forEach(r => csv += `${r.tanggal},${r.barcode},${r.namabarang},${r.kategori},${r.baki},${r.berat},${r.kadar}\n`);
            const blob = new Blob([csv], { type: 'text/csv' });
            const link = document.createElement('a');
            link.href = URL.createObjectURL(blob);
            link.download = 'stok_opname.csv';
            link.click();
        }

        // Export Excel
        function exportExcel() {
            const rows = getSelectedRows();
            if (!rows.length) return alert('Tidak ada data untuk diexport!');
            let table = `<table><tr><th>Tanggal</th><th>Barcode</th><th>Nama Barang</th><th>Kategori</th><th>Baki</th><th>Berat</th><th>Kadar</th></tr>`;
            rows.forEach(r => table += `<tr><td>${r.tanggal}</td><td>${r.barcode}</td><td>${r.namabarang}</td><td>${r.kategori}</td><td>${r.baki}</td><td>${r.berat}</td><td>${r.kadar}</td></tr>`);
            table += '</table>';
            const blob = new Blob([table], { type: 'application/vnd.ms-excel' });
            const link = document.createElement('a');
            link.href = URL.createObjectURL(blob);
            link.download = 'stok_opname.xls';
            link.click();
        }

        // Export PDF
        function exportPDF() {
            const rows = getSelectedRows();
            if (!rows.length) return alert('Tidak ada data untuk dicetak!');
            let tableRows = '';
            rows.forEach(r => tableRows += `<tr><td>${r.tanggal}</td><td>${r.barcode}</td><td>${r.namabarang}</td><td>${r.kategori}</td><td>${r.baki}</td><td>${r.berat}</td><td>${r.kadar}</td></tr>`);
            const surat = `
        <h2 style="text-align:center;">SURAT RESMI LAPORAN STOK OPNAME</h2>
        <p>Kepada Yth,</p>
        <p><b>Pimpinan Perusahaan</b></p>
        <p>di Tempat</p><br>
        <p>Dengan hormat,</p>
        <p>Bersama ini kami sampaikan daftar stok opname:</p>
        <table border="1" cellspacing="0" cellpadding="5" width="100%">
            <thead>
                <tr><th>Tanggal</th><th>Barcode</th><th>Nama Barang</th><th>Kategori</th><th>Baki</th><th>Berat</th><th>Kadar</th></tr>
            </thead>
            <tbody>${tableRows}</tbody>
        </table>
        <br><br><p>Hormat kami,</p><br><br>
        <p><b>(................................)</b></p>
    `;
            const win = window.open("", "", "width=900,height=600");
            win.document.write(`<html><head><title>Surat Resmi Stok Opname</title></head><body>${surat}</body></html>`);
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