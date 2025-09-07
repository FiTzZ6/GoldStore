<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Riwayat Pindah Barang - Sistem Manajemen Barang</title>
    <link rel="stylesheet" href="{{ asset('css/barang//pindahbarang/riwayatpindahbarang.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>

<body>
    @include('partials.navbar')
    <div class="container">
        <div class="header">
            <h1><i class="fas fa-history"></i> Riwayat Pindah Barang</h1>
            <div class="header-actions">
                <button class="btn btn-primary" onclick="window.location.href='{{ route('pindahbaki') }}'">
                    <i class="fas fa-exchange-alt"></i> Pindah Barang Baru
                </button>
            </div>
        </div>

        <div class="content-card">
            <div class="table-controls">
                <div class="controls-left">
                    <div class="show-entries">
                        <label for="show-entries">Show</label>
                        <select id="show-entries">
                            <option value="10">10</option>
                            <option value="25">25</option>
                            <option value="50">50</option>
                            <option value="100">100</option>
                        </select>
                        <span>entries</span>
                    </div>

                    <div class="action-buttons">
                        <button class="btn-icon" title="Copy">
                            <i class="fas fa-copy"></i>
                        </button>
                        <button class="btn-icon" title="CSV">
                            <i class="fas fa-file-csv"></i>
                        </button>
                        <button class="btn-icon" title="Excel">
                            <i class="fas fa-file-excel"></i>
                        </button>
                        <button class="btn-icon" title="PDF">
                            <i class="fas fa-file-pdf"></i>
                        </button>
                        <button class="btn-icon" title="Print">
                            <i class="fas fa-print"></i>
                        </button>
                    </div>
                </div>

                <div class="controls-right">
                    <div class="search-box">
                        <i class="fas fa-search"></i>
                        <input type="text" id="global-search" placeholder="Search...">
                    </div>
                </div>
            </div>

            <div class="table-responsive">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>
                                <div class="th-content">
                                    <span>Tanggal</span>
                                    <div class="search-field">
                                        <input type="text" placeholder="Search Tanggal">
                                    </div>
                                </div>
                            </th>
                            <th>
                                <div class="th-content">
                                    <span>No. Pindah</span>
                                    <div class="search-field">
                                        <input type="text" placeholder="Search No. Pindah">
                                    </div>
                                </div>
                            </th>
                            <th>
                                <div class="th-content">
                                    <span>Barcode</span>
                                    <div class="search-field">
                                        <input type="text" placeholder="Search Barcode">
                                    </div>
                                </div>
                            </th>
                            <th>
                                <div class="th-content">
                                    <span>Nama Barang</span>
                                    <div class="search-field">
                                        <input type="text" placeholder="Search Nama Barang">
                                    </div>
                                </div>
                            </th>
                            <th>
                                <div class="th-content">
                                    <span>Baki Asal</span>
                                    <div class="search-field">
                                        <input type="text" placeholder="Search Baki Asal">
                                    </div>
                                </div>
                            </th>
                            <th>
                                <div class="th-content">
                                    <span>Baki Tujuan</span>
                                    <div class="search-field">
                                        <input type="text" placeholder="Search Baki Tujuan">
                                    </div>
                                </div>
                            </th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($riwayat as $item)
                            <tr>
                                <td>{{ \Carbon\Carbon::parse($item->created_at)->format('d.m.Y H:i') }}</td>
                                <td>{{ $item->fakturpindahbaki }}</td>
                                <td>{{ $item->barcode }}</td>
                                <td>{{ $item->namabarang }}</td>
                                <td>{{ $item->kdbaki_asal }}</td>
                                <td>{{ $item->kdbaki_tujuan }}</td>
                                <td>
                                    <button class="btn-action" title="Cetak Bukti">
                                        <i class="fas fa-print"></i>
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" style="text-align:center;">Belum ada data pindah barang</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="table-footer">
                <div class="footer-info">
                    Showing 1 to 5 of 5 entries
                </div>
                <div class="pagination">
                    <button class="pagination-btn disabled">
                        <i class="fas fa-chevron-left"></i>
                    </button>
                    <button class="pagination-btn active">1</button>
                    <button class="pagination-btn">
                        <i class="fas fa-chevron-right"></i>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Fungsi untuk fitur pencarian
        document.getElementById('global-search').addEventListener('input', function (e) {
            const searchTerm = e.target.value.toLowerCase();
            const rows = document.querySelectorAll('.data-table tbody tr');

            rows.forEach(row => {
                let rowText = '';
                row.querySelectorAll('td').forEach(cell => {
                    rowText += cell.textContent.toLowerCase() + ' ';
                });

                if (rowText.includes(searchTerm)) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });
        });

        // Fungsi untuk filter per kolom
        document.querySelectorAll('.search-field input').forEach(input => {
            input.addEventListener('input', function (e) {
                const columnIndex = this.closest('th').cellIndex;
                const searchTerm = e.target.value.toLowerCase();
                const rows = document.querySelectorAll('.data-table tbody tr');

                rows.forEach(row => {
                    const cell = row.cells[columnIndex];
                    if (cell.textContent.toLowerCase().includes(searchTerm)) {
                        row.style.display = '';
                    } else {
                        row.style.display = 'none';
                    }
                });
            });
        });

        // Fungsi untuk mengubah jumlah entri yang ditampilkan
        document.getElementById('show-entries').addEventListener('change', function (e) {
            // Simulasi perubahan jumlah entri
            console.log('Menampilkan ' + e.target.value + ' entri');
        });
    </script>
</body>

</html>