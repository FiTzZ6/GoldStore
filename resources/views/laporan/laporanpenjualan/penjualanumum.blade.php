<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Penjualan Umum</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/laporan/laporanpenjualan/laporanpenjualan.css') }}">
</head>

<body>
    @include('partials.navbar')

    <div class="container">
        <!-- Header -->
        <div class="header">
            <h1><i class="fas fa-boxes"></i> Laporan Penjualan Umum</h1>
            <div class="header-info">
                <span>Tanggal: {{ $tanggal }}</span>
            </div>
        </div>

        <!-- Filter -->
        <form method="GET" action="{{ route('penjualanumum') }}">
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
                    <label for="kategori">Kategori</label>
                    <select name="kategori" id="kategori">
                        <option value="SEMUA KATEGORI">SEMUA KATEGORI</option>
                        @foreach(\App\Models\KategoriBarang::all() as $kat)
                            <option value="{{ $kat->kdkategori }}" {{ $kategori == $kat->kdkategori ? 'selected' : '' }}>
                                {{ $kat->namakategori }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="filter-item">
                    <label for="jenis">Jenis</label>
                    <select name="jenis" id="jenis">
                        <option value="SEMUA JENIS">SEMUA JENIS</option>
                        @foreach(\App\Models\JenisBarang::all() as $j)
                            <option value="{{ $j->kdjenis }}" {{ $jenis == $j->kdjenis ? 'selected' : '' }}>
                                {{ $j->namajenis }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="filter-item">
                    <label for="barang">Barang</label>
                    <select name="barang" id="barang">
                        <option value="SEMUA BARANG" {{ ($barang ?? '') === 'SEMUA BARANG' ? 'selected' : '' }}>SEMUA
                            BARANG</option>
                        @foreach($barangList as $b)
                            <option value="{{ $b->kdbarang }}" {{ isset($barang) && $barang == $b->kdbarang ? 'selected' : '' }}>
                                {{ $b->kdbarang }} - {{ $b->namabarang }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="filter-item">
                    <button type="submit" class="btn btn-primary">Tampilkan</button>
                </div>
            </div>
        </form>


        <!-- Search -->
        <div class="search-container">
            <div class="search-box">
                <i class="fas fa-search"></i>
                <input type="text" id="global-search" placeholder="Cari semua penjualan">
            </div>
        </div>

        <!-- Tools -->
        <div class="tools">
            <button class="btn btn-primary"><i class="fas fa-eye"></i> Show 10 rows</button>
            <button class="btn btn-secondary"><i class="fas fa-copy"></i> Copy</button>
            <button class="btn btn-secondary"><i class="fas fa-file-csv"></i> CSV</button>
            <button class="btn btn-secondary"><i class="fas fa-file-excel"></i> Excel</button>
            <button class="btn btn-danger"><i class="fas fa-file-pdf"></i> PDF</button>
            <button class="btn btn-success"><i class="fas fa-print"></i> Print</button>
        </div>

        <!-- Table -->
        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th>No Faktur <i class="fas fa-sort"></i></th>
                        <th>Nama Barang <i class="fas fa-sort"></i></th>
                        <th>Kategori <i class="fas fa-sort"></i></th>
                        <th>Jenis <i class="fas fa-sort"></i></th>
                        <th>Qty <i class="fas fa-sort"></i></th>
                        <th>Harga <i class="fas fa-sort"></i></th>
                        <th>Total <i class="fas fa-sort"></i></th>
                        <th>Tanggal <i class="fas fa-sort"></i></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($penjualan as $jual)
                        <tr>
                            <td>{{ $jual->nofaktur }}</td>
                            <td>{{ $jual->barang->namabarang ?? $jual->namabarang }}</td>
                            <td>{{ $jual->barang->KategoriBarang->namakategori ?? '-' }}</td>
                            <td>{{ $jual->barang->JenisBarang->namajenis ?? '-' }}</td>
                            <td>{{ $jual->quantity }}</td>
                            <td>{{ number_format($jual->harga, 0, ',', '.') }}</td>
                            <td>{{ number_format($jual->total, 0, ',', '.') }}</td>
                            <td>{{ $jual->created_at->format('d-m-Y H:i') }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center">Tidak ada data penjualan</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="pagination">
            <div class="pagination-info">
                Menampilkan {{ $penjualan->firstItem() }} hingga {{ $penjualan->lastItem() }} dari
                {{ $penjualan->total() }} entri
            </div>
            <div class="pagination-controls">
                {{-- Tombol Previous --}}
                @if ($penjualan->onFirstPage())
                    <button class="pagination-btn" disabled>&laquo; Previous</button>
                @else
                    <a href="{{ $penjualan->previousPageUrl() }}" class="pagination-btn">&laquo; Previous</a>
                @endif

                {{-- Tombol Halaman Maksimal 5 --}}
                @php
                    $start = max($penjualan->currentPage() - 2, 1);
                    $end = min($start + 4, $penjualan->lastPage());
                    if ($end - $start < 4) {
                        $start = max($end - 4, 1);
                    }
                @endphp

                @if ($start > 1)
                    <a href="{{ $penjualan->url(1) }}" class="pagination-btn">1</a>
                    @if ($start > 2)
                        <span class="pagination-btn">...</span>
                    @endif
                @endif

                @for ($page = $start; $page <= $end; $page++)
                    @if ($page == $penjualan->currentPage())
                        <button class="pagination-btn active">{{ $page }}</button>
                    @else
                        <a href="{{ $penjualan->url($page) }}" class="pagination-btn">{{ $page }}</a>
                    @endif
                @endfor

                @if ($end < $penjualan->lastPage())
                    @if ($end < $penjualan->lastPage() - 1)
                        <span class="pagination-btn">...</span>
                    @endif
                    <a href="{{ $penjualan->url($penjualan->lastPage()) }}"
                        class="pagination-btn">{{ $penjualan->lastPage() }}</a>
                @endif

                {{-- Tombol Next --}}
                @if ($penjualan->hasMorePages())
                    <a href="{{ $penjualan->nextPageUrl() }}" class="pagination-btn">Next &raquo;</a>
                @else
                    <button class="pagination-btn" disabled>Next &raquo;</button>
                @endif
            </div>
        </div>
    </div>

    <script>
        // Search
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

        // Sort
        document.querySelectorAll('th').forEach(header => {
            header.addEventListener('click', function () {
                const columnIndex = Array.from(this.parentElement.children).indexOf(this);
                const rows = Array.from(document.querySelectorAll('tbody tr'));
                const isNumeric = !isNaN(parseFloat(rows[0].querySelectorAll('td')[columnIndex].textContent));

                rows.sort((a, b) => {
                    const aValue = a.querySelectorAll('td')[columnIndex].textContent;
                    const bValue = b.querySelectorAll('td')[columnIndex].textContent;

                    return isNumeric
                        ? parseFloat(aValue) - parseFloat(bValue)
                        : aValue.localeCompare(bValue);
                });

                if (this.getAttribute('data-sorted') === 'asc') {
                    rows.reverse();
                    this.setAttribute('data-sorted', 'desc');
                } else {
                    this.setAttribute('data-sorted', 'asc');
                }

                const tbody = document.querySelector('tbody');
                tbody.innerHTML = '';
                rows.forEach(row => tbody.appendChild(row));
            });
        });
    </script>
</body>

</html>