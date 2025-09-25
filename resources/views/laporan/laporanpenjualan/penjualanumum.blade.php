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
            <select onchange="handleExport(this.value)">
                <option value="">Pilih Export</option>
                <option value="csv">Export CSV</option>
                <option value="excel">Export Excel</option>
                <option value="pdf">Export PDF</option>
            </select>
        </div>

        <!-- Table -->
        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th><input type="checkbox" id="selectAll"></th>
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
                            <td><input type="checkbox" class="rowCheckbox"></td>
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
document.getElementById('global-search').addEventListener('keyup', function() {
    const searchText = this.value.toLowerCase();
    document.querySelectorAll('tbody tr').forEach(row => {
        row.style.display = Array.from(row.cells).some(td => td.innerText.toLowerCase().includes(searchText)) ? '' : 'none';
    });
});

// Sort
document.querySelectorAll('th').forEach((th, index) => {
    if(index === 0) return; // skip checkbox
    th.addEventListener('click', function() {
        const tbody = th.closest('table').querySelector('tbody');
        Array.from(tbody.querySelectorAll('tr'))
            .sort((a, b) => {
                let aText = a.cells[index].innerText;
                let bText = b.cells[index].innerText;
                if(!isNaN(aText) && !isNaN(bText)) return aText - bText;
                return aText.localeCompare(bText);
            })
            .forEach(tr => tbody.appendChild(tr));
    });
});

// Checkbox Select All
document.getElementById('selectAll').addEventListener('change', function() {
    const checked = this.checked;
    document.querySelectorAll('.rowCheckbox').forEach(cb => cb.checked = checked);
});

// Ambil baris terpilih
function getSelectedRows() {
    const rows = document.querySelectorAll('tbody tr');
    let selected = [];
    rows.forEach(row => {
        if(row.querySelector('.rowCheckbox:checked') || document.querySelectorAll('.rowCheckbox:checked').length === 0){
            const cols = row.querySelectorAll('td');
            selected.push({
                no: cols[1].innerText,
                nama: cols[2].innerText,
                terjual: cols[3].innerText
            });
        }
    });
    return selected;
}

// Export CSV
function exportCSV() {
    const rows = getSelectedRows();
    if(!rows.length) return alert('Tidak ada data untuk diexport!');
    let csv = 'No,Nama Barang,Jumlah Terjual\n';
    rows.forEach(r => csv += `${r.no},${r.nama},${r.terjual}\n`);
    const blob = new Blob([csv], {type: 'text/csv'});
    const link = document.createElement('a');
    link.href = URL.createObjectURL(blob);
    link.download = 'cepat_laku.csv';
    link.click();
}

// Export Excel
function exportExcel() {
    const rows = getSelectedRows();
    if(!rows.length) return alert('Tidak ada data untuk diexport!');
    let table = `<table><tr><th>No</th><th>Nama Barang</th><th>Jumlah Terjual</th></tr>`;
    rows.forEach(r => table += `<tr><td>${r.no}</td><td>${r.nama}</td><td>${r.terjual}</td></tr>`);
    table += '</table>';
    const blob = new Blob([table], {type:'application/vnd.ms-excel'});
    const link = document.createElement('a');
    link.href = URL.createObjectURL(blob);
    link.download = 'cepat_laku.xls';
    link.click();
}

// Export PDF (Surat Resmi)
function exportPDF() {
    const rows = getSelectedRows();
    if(!rows.length) return alert('Tidak ada data untuk dicetak!');
    let tableRows = '';
    rows.forEach(r => tableRows += `<tr><td>${r.no}</td><td>${r.nama}</td><td>${r.terjual}</td></tr>`);
    const surat = `
        <h2 style="text-align:center;">SURAT RESMI LAPORAN CEPAT LAKU</h2>
        <p>Kepada Yth,</p>
        <p><b>Pimpinan Perusahaan</b></p>
        <p>di Tempat</p><br>
        <p>Dengan hormat,</p>
        <p>Bersama ini kami sampaikan daftar barang cepat laku:</p>
        <table border="1" cellspacing="0" cellpadding="5" width="100%">
            <thead><tr><th>No</th><th>Nama Barang</th><th>Jumlah Terjual</th></tr></thead>
            <tbody>${tableRows}</tbody>
        </table>
        <br><br><p>Hormat kami,</p><br><br>
        <p><b>(................................)</b></p>
    `;
    const win = window.open("", "", "width=800,height=600");
    win.document.write(`<html><head><title>Surat Resmi</title></head><body>${surat}</body></html>`);
    win.document.close();
    win.print();
    win.close();
}

// Handle Export
function handleExport(value) {
    if(value==='csv') exportCSV();
    if(value==='excel') exportExcel();
    if(value==='pdf') exportPDF();
}
</script>
</body>

</html>