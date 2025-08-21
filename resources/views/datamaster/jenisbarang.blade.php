<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="{{ asset('css/datamaster/kategori.css') }}">
    <link href="https://fonts.googleapis.com/css?family=Montserrat:400,800" rel="stylesheet">
    <title>Halaman Jenis Barang</title>
</head>

<body>
    @include('partials.navbar')

        <h1>JENIS BARANG</h1>

    <div class="container">
        {{-- Alert success/error --}}
        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        @if (session('error'))
            <div class="alert alert-error">{{ session('error') }}</div>
        @endif

        <div class="top-bar">
            <div class="left-controls">
                <select onchange="handleExport(this.value)">
                    <option value="">Pilih Export</option>
                    <option value="pdf">Export PDF</option>
                    <option value="csv">Export CSV</option>
                    <option value="excel">Export Excel</option>
                </select>
                <button class="btn-primary" onclick="openModal('modalTambah')">+ Tambah Jenis</button>
                <form method="GET" action="{{ route('jenisbarang') }}">
                    <select name="per_page" onchange="this.form.submit()">
                        <option value="10" {{ $perPage == 10 ? 'selected' : '' }}>10</option>
                        <option value="20" {{ $perPage == 20 ? 'selected' : '' }}>20</option>
                        <option value="25" {{ $perPage == 25 ? 'selected' : '' }}>25</option>
                        <option value="50" {{ $perPage == 50 ? 'selected' : '' }}>50</option>
                        <option value="75" {{ $perPage == 75 ? 'selected' : '' }}>75</option>
                        <option value="100" {{ $perPage == 100 ? 'selected' : '' }}>100</option>
                        <option value="all" {{ $perPage == 'all' ? 'selected' : '' }}>All</option>
                    </select>
                </form>
            </div>
            <div style="display:flex; align-items:center; gap:6px;">
                <div class="icon-group">
                    <button title="Sorting" onclick="sortTable()"><i class="fas fa-sort"></i></button>
                    <button title="Refresh" onclick="refreshPage()"><i class="fas fa-sync"></i></button>
                </div>
                <input type="text" placeholder="Search">
            </div>
        </div>

        <table>
            <thead>
                <tr>
                    <th><input type="checkbox"></th>
                    <th>Kode Jenis</th>
                    <th>Nama Jenis</th>
                    <th>Kategori</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach($jenis as $row)
                    <tr>
                        <td><input type="checkbox"></td>
                        <td>{{ $row->kdjenis }}</td>
                        <td>{{ $row->namajenis }}</td>
                        <td>{{ $row->kategori?->kdkategori }}</td>
                        <td>
                            <button class="action-btn"
                                onclick="openEditModal('{{ $row->kdjenis }}', '{{ $row->namajenis }}', '{{ $row->kdkategori }}')">
                                <i class="fa-solid fa-pen-to-square"></i>
                            </button>
                            <button class="action-btn" onclick="openDeleteModal('{{ $row->kdjenis }}')">
                                <i class="fas fa-trash"></i>
                            </button>
                        </td>
                    </tr>
                @endforeach
            </tbody>

        </table>

        <div class="footer">SKIBIDI</div>
    </div>

    {{-- Modal Tambah --}}
    <div id="modalTambah" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeModal('modalTambah')">&times;</span>
            <h2>Tambah Jenis Barang</h2>
            <form action="{{ route('jenis.store') }}" method="POST">
                @csrf
                <label>Kode Jenis</label>
                <input type="text" name="kdjenis" required>
                <label>Nama Jenis</label>
                <input type="text" name="namajenis" required>
                <label>Kategori</label>
                <select name="kdkategori" required>
                    <option value="">-- Pilih Kategori --</option>
                    @foreach($kategori as $kat)
                        <option value="{{ $kat->kdkategori }}">{{ $kat->kdkategori }}</option>
                    @endforeach
                </select>
                <button type="submit" class="btn-primary">Simpan</button>
            </form>
        </div>
    </div>

    {{-- Modal Edit --}}
    <div id="modalEdit" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeModal('modalEdit')">&times;</span>
            <h2>Edit Jenis Barang</h2>
            <form id="formEdit" method="POST">
                @csrf
                @method('PUT')
                <label>Kode Jenis</label>
                <input type="text" name="kdjenis" id="editJenis" readonly>
                <label>Nama Jenis</label>
                <input type="text" name="namajenis" id="editNama" required>
                <label>Kategori</label>
                <select name="kdkategori" id="editKategori" required>
                    @foreach($kategori as $kat)
                        <option value="{{ $kat->kdkategori }}">{{ $kat->kdkategori }}</option>
                    @endforeach
                </select>
                <button type="submit" class="btn-primary">Update</button>
            </form>
        </div>
    </div>

    {{-- Modal Hapus --}}
    <div id="modalHapus" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeModal('modalHapus')">&times;</span>
            <h2>Hapus Jenis Barang</h2>
            <form id="formHapus" method="POST">
                @csrf
                @method('DELETE')
                <p>Apakah anda yakin ingin menghapus jenis barang ini?</p>
                <button type="submit" class="btn-primary">Ya, Hapus</button>
            </form>
        </div>
    </div>

    <script>
        function openModal(id) {
            document.getElementById(id).classList.add('show');
        }
        function closeModal(id) {
            document.getElementById(id).classList.remove('show');
        }

        function openEditModal(kdjenis, namajenis, kdkategori) {
            let modal = document.getElementById('modalEdit');
            modal.classList.add('show');

            document.getElementById('editJenis').value = kdjenis;
            document.getElementById('editNama').value = namajenis;
            document.getElementById('editKategori').value = kdkategori;

            document.getElementById('formEdit').action = '/update-jenis/' + kdjenis;
        }

        function openDeleteModal(kdjenis) {
            let modal = document.getElementById('modalHapus');
            modal.classList.add('show');
            document.getElementById('formHapus').action = '/hapus-jenis/' + kdjenis;
        }

        // Search filter
        document.querySelector("input[placeholder='Search']").addEventListener("keyup", function () {
            let value = this.value.toLowerCase();
            document.querySelectorAll("table tbody tr").forEach(function (row) {
                row.style.display = row.innerText.toLowerCase().includes(value) ? "" : "none";
            });
        });

        // Export functions
        function exportCSV() {
            let table = document.querySelector("table");
            let rows = table.querySelectorAll("tr");
            let csv = [];
            rows.forEach(row => {
                let cols = row.querySelectorAll("td, th");
                let rowData = [];
                cols.forEach(col => rowData.push(col.innerText));
                csv.push(rowData.join(","));
            });
            let csvContent = "data:text/csv;charset=utf-8," + csv.join("\n");
            let link = document.createElement("a");
            link.setAttribute("href", csvContent);
            link.setAttribute("download", "jenis.csv");
            link.click();
        }

        function exportExcel() {
            let table = document.querySelector("table").outerHTML;
            let data = new Blob([table], { type: "application/vnd.ms-excel" });
            let link = document.createElement("a");
            link.href = URL.createObjectURL(data);
            link.download = "jenis.xls";
            link.click();
        }

        function exportPDF() { window.print(); }
        function refreshPage() { location.reload(); }

        function sortTable() {
            let table = document.querySelector("table");
            let rows = Array.from(table.rows).slice(1);
            rows.sort((a, b) => a.cells[1].innerText.localeCompare(b.cells[1].innerText));
            rows.forEach(row => table.appendChild(row));
        }

        function handleExport(value) {
            if (value === "print") window.print();
            if (value === "pdf") exportPDF();
            if (value === "csv") exportCSV();
            if (value === "excel") exportExcel();
        }
    </script>
</body>

</html>