<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Tukar Poin</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/laporan/laporantukarpoin/laporanpoin.css') }}">
</head>

<body>
    @include('partials.navbar')
    <div class="container">
        <div class="header">
            <h1><i class="fas fa-gift"></i> Laporan Tukar Poin</h1>
        </div>

        {{-- Filter Tanggal --}}
        <form method="GET" action="{{ route('laporantukarpoin') }}">
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

            {{-- Filter Merchandise --}}
            <div class="filters">
                <div class="filter-group">
                    <label for="merch">Merchandise</label>
                    <select id="merch" name="merch">
                        <option value="">-- Semua --</option>
                        @foreach($merchList as $m)
                            <option value="{{ $m->id }}" {{ $merch == $m->id ? 'selected' : '' }}>
                                {{ $m->nama_merch }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>

            <button type="submit" class="btn btn-primary">
                <i class="fas fa-filter"></i> Filter
            </button>
        </form>

        {{-- Pencarian Global --}}
        <div class="search-container">
            <div class="search-box">
                <i class="fas fa-search"></i>
                <input type="text" id="global-search" placeholder="Cari semua riwayat redeem">
            </div>
        </div>

        {{-- Tools --}}
        <div class="tools">
            <select onchange="handleExport(this.value)">
                <option value="">Pilih Export</option>
                <option value="csv">Export CSV</option>
                <option value="excel">Export Excel</option>
                <option value="pdf">Export PDF</option>
            </select>
        </div>

        {{-- Tabel Riwayat Redeem --}}
        <div class="table-container">
            <table id="redeemTable">
                <thead>
                    <tr>
                        <th><input type="checkbox" id="selectAll"></th>
                        <th>Tanggal</th>
                        <th>Nama Pelanggan</th>
                        <th>Merchandise</th>
                        <th>Poin Digunakan</th>
                        <th>Stok Sisa</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($redeems as $r)
                        <tr>
                            <td><input type="checkbox" class="rowCheckbox"></td>
                            <td>{{ \Carbon\Carbon::parse($r->created_at)->format('d-m-Y H:i') }}</td>
                            <td>{{ $r->namapelanggan }}</td>
                            <td>{{ $r->nama_merch }}</td>
                            <td>{{ number_format($r->poin_digunakan, 0, ',', '.') }}</td>
                            <td>{{ $r->stok_sisa }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <script>
        // Pencarian Global
        document.getElementById('global-search').addEventListener('keyup', function () {
            const searchText = this.value.toLowerCase();
            const rows = document.querySelectorAll('#redeemTable tbody tr');
            rows.forEach(row => {
                row.style.display = Array.from(row.cells).some(td => td.innerText.toLowerCase().includes(searchText)) ? '' : 'none';
            });
        });

        // Select All Checkbox
        document.getElementById('selectAll').addEventListener('change', function () {
            const checked = this.checked;
            document.querySelectorAll('.rowCheckbox').forEach(cb => cb.checked = checked);
        });

        // Ambil baris terpilih
        function getSelectedRows() {
            const rows = document.querySelectorAll('#redeemTable tbody tr');
            let selected = [];
            const anyChecked = document.querySelectorAll('.rowCheckbox:checked').length > 0;
            rows.forEach(row => {
                const cb = row.querySelector('.rowCheckbox');
                if (!anyChecked || cb.checked) { // jika ada yang dicentang ambil hanya yang dicentang, jika tidak ambil semua
                    const cols = row.querySelectorAll('td');
                    selected.push({
                        tanggal: cols[1].innerText,
                        pelanggan: cols[2].innerText,
                        merch: cols[3].innerText,
                        poin: cols[4].innerText,
                        stok: cols[5].innerText
                    });
                }
            });
            return selected;
        }

        // Export CSV
        function exportCSV() {
            const rows = getSelectedRows();
            if (!rows.length) return alert('Tidak ada data untuk diexport!');
            let csv = 'Tanggal,Nama Pelanggan,Merchandise,Poin Digunakan,Stok Sisa\n';
            rows.forEach(r => csv += `${r.tanggal},${r.pelanggan},${r.merch},${r.poin},${r.stok}\n`);
            const blob = new Blob([csv], { type: 'text/csv' });
            const link = document.createElement('a');
            link.href = URL.createObjectURL(blob);
            link.download = 'laporan_tukar_poin.csv';
            link.click();
        }

        // Export Excel
        function exportExcel() {
            const rows = getSelectedRows();
            if (!rows.length) return alert('Tidak ada data untuk diexport!');
            let table = `<table><tr><th>Tanggal</th><th>Nama Pelanggan</th><th>Merchandise</th><th>Poin Digunakan</th><th>Stok Sisa</th></tr>`;
            rows.forEach(r => table += `<tr><td>${r.tanggal}</td><td>${r.pelanggan}</td><td>${r.merch}</td><td>${r.poin}</td><td>${r.stok}</td></tr>`);
            table += '</table>';
            const blob = new Blob([table], { type: 'application/vnd.ms-excel' });
            const link = document.createElement('a');
            link.href = URL.createObjectURL(blob);
            link.download = 'laporan_tukar_poin.xls';
            link.click();
        }

        // Export PDF versi surat resmi
        function exportPDF() {
            const rows = getSelectedRows();
            if (!rows.length) return alert('Tidak ada data untuk dicetak!');

            // Buat baris tabel
            let tableRows = '';
            rows.forEach(r => {
                tableRows += `<tr>
            <td>${r.tanggal}</td>
            <td>${r.barcode || r.pelanggan}</td>
            <td>${r.namabarang || r.merch}</td>
            <td>${r.kategori || r.poin}</td>
            <td>${r.baki || r.stok}</td>
            <td>${r.berat || ''}</td>
            <td>${r.kadar || ''}</td>
        </tr>`;
            });

            // Format surat resmi
            const surat = `
        <h2 style="text-align:center;">SURAT RESMI LAPORAN</h2>
        <p>Kepada Yth,</p>
        <p><b>Pimpinan Perusahaan</b></p>
        <p>di Tempat</p><br>
        <p>Dengan hormat,</p>
        <p>Bersama ini kami sampaikan laporan sebagai berikut:</p>
        <table border="1" cellspacing="0" cellpadding="5" width="100%">
            <thead>
                <tr>
                    <th>Tanggal</th>
                    <th>Barcode / Pelanggan</th>
                    <th>Nama Barang / Merchandise</th>
                    <th>Kategori / Poin</th>
                    <th>Baki / Stok</th>
                    <th>Berat</th>
                    <th>Kadar</th>
                </tr>
            </thead>
            <tbody>${tableRows}</tbody>
        </table>
        <br><br><p>Hormat kami,</p><br><br>
        <p><b>(................................)</b></p>
    `;

            // Buka window baru dan cetak
            const win = window.open("", "", "width=900,height=600");
            win.document.write(`
        <html>
            <head>
                <title>Surat Resmi Laporan</title>
            </head>
            <body>
                ${surat}
            </body>
        </html>
    `);
            win.document.close();
            win.print();
            win.close();
        }

        // Handle Export Dropdown
        function handleExport(value) {
            if (value === 'csv') exportCSV();
            if (value === 'excel') exportExcel();
            if (value === 'pdf') exportPDF();
        }
    </script>
</body>

</html>