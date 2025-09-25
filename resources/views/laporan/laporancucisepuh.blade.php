<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Transaksi Kas</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/laporan/laporancucisepuh.css') }}">
</head>

<body>
    @include('partials.navbar')
    <div class="container">
        <div class="header">
            <h1><i class="fas fa-boxes"></i> Laporan Transaksi Kas</h1>
        </div>


        <div class="filters">
            <form method="GET" action="{{ route('laporancucisepuh') }}" class="filter-form">
                <div class="filter-group">
                    <label for="barang">Barang</label>
                    <select id="barang" name="barang">
                        <option value="">SEMUA BARANG</option>
                        @foreach(\App\Models\Barang::all() as $b)
                            <option value="{{ $b->namabarang }}" {{ request('barang') == $b->namabarang ? 'selected' : '' }}>
                                {{ $b->namabarang }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="filter-group">
                    <label for="start_date">Tanggal</label>
                    <input type="date" id="start_date" name="start_date" value="{{ request('start_date') }}">
                </div>

                <div class="filter-group">
                    <label for="end_date">s/d</label>
                    <input type="date" id="end_date" name="end_date" value="{{ request('end_date') }}">
                </div>

                <button type="submit">Filter</button>
            </form>
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

        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th><input type="checkbox" id="selectAll"></th>
                        <th>Nama Pelanggan <i class="fas fa-sort"></i></th>
                        <th>Jenis Barang <i class="fas fa-sort"></i></th>
                        <th>Berat <i class="fas fa-sort"></i></th>
                        <th>Karat <i class="fas fa-sort"></i></th>
                        <th>Tanggal Cuci <i class="fas fa-sort"></i></th>
                        <th>Status <i class="fas fa-sort"></i></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($cucisepuhs as $item)
                        <tr>
                            <td><input type="checkbox" class="rowCheckbox"></td>
                            <td>{{ $item->nama_pelanggan }}</td>
                            <td>{{ $item->jenis_barang }}</td>
                            <td>{{ $item->berat }}</td>
                            <td>{{ $item->karat }}</td>
                            <td>{{ $item->tanggal_cuci }}</td>
                            <td>{{ ucfirst($item->status) }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" style="text-align:center">Tidak ada data</td>
                        </tr>
                    @endforelse
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

        // Checkbox Select All
        document.getElementById('selectAll').addEventListener('change', function () {
            const checked = this.checked;
            document.querySelectorAll('.rowCheckbox').forEach(cb => cb.checked = checked);
        });

        // Ambil baris terpilih
        function getSelectedRows() {
            const rows = document.querySelectorAll('#reportTable tbody tr');
            let selected = [];
            rows.forEach(row => {
                const cb = row.querySelector('.rowCheckbox');
                if (cb.checked || document.querySelectorAll('.rowCheckbox:checked').length === 0) {
                    const cols = row.querySelectorAll('td');
                    selected.push({
                        nama: cols[1].innerText,
                        jenis: cols[2].innerText,
                        berat: cols[3].innerText,
                        karat: cols[4].innerText,
                        tanggal: cols[5].innerText,
                        status: cols[6].innerText
                    });
                }
            });
            return selected;
        }

        // Export CSV
        function exportCSV() {
            const rows = getSelectedRows();
            if (!rows.length) return alert('Tidak ada data untuk diexport!');
            let csv = 'Nama Pelanggan,Jenis Barang,Berat,Karat,Tanggal Cuci,Status\n';
            rows.forEach(r => csv += `${r.nama},${r.jenis},${r.berat},${r.karat},${r.tanggal},${r.status}\n`);
            const blob = new Blob([csv], { type: 'text/csv' });
            const link = document.createElement('a');
            link.href = URL.createObjectURL(blob);
            link.download = 'laporan_transaksi_kas.csv';
            link.click();
        }

        // Export Excel
        function exportExcel() {
            const rows = getSelectedRows();
            if (!rows.length) return alert('Tidak ada data untuk diexport!');
            let table = `<table><tr><th>Nama Pelanggan</th><th>Jenis Barang</th><th>Berat</th><th>Karat</th><th>Tanggal Cuci</th><th>Status</th></tr>`;
            rows.forEach(r => table += `<tr><td>${r.nama}</td><td>${r.jenis}</td><td>${r.berat}</td><td>${r.karat}</td><td>${r.tanggal}</td><td>${r.status}</td></tr>`);
            table += '</table>';
            const blob = new Blob([table], { type: 'application/vnd.ms-excel' });
            const link = document.createElement('a');
            link.href = URL.createObjectURL(blob);
            link.download = 'laporan_transaksi_kas.xls';
            link.click();
        }

        // Export PDF (Surat Resmi)
        function exportPDF() {
            const rows = getSelectedRows();
            if (!rows.length) return alert('Tidak ada data untuk dicetak!');
            let tableRows = '';
            rows.forEach(r => tableRows += `<tr><td>${r.nama}</td><td>${r.jenis}</td><td>${r.berat}</td><td>${r.karat}</td><td>${r.tanggal}</td><td>${r.status}</td></tr>`);
            const surat = `
                <h2 style="text-align:center;">SURAT RESMI LAPORAN TRANSAKSI KAS</h2>
                <p>Kepada Yth,</p>
                <p><b>Pimpinan Perusahaan</b></p>
                <p>di Tempat</p><br>
                <p>Dengan hormat,</p>
                <p>Bersama ini kami sampaikan daftar transaksi kas:</p>
                <table border="1" cellspacing="0" cellpadding="5" width="100%">
                    <thead>
                        <tr><th>Nama Pelanggan</th><th>Jenis Barang</th><th>Berat</th><th>Karat</th><th>Tanggal Cuci</th><th>Status</th></tr>
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