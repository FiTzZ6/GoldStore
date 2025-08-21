<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="{{ asset('css/datamaster/area.css') }}">
    <link href="https://fonts.googleapis.com/css?family=Montserrat:400,800" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" />
    <title>Halaman Area</title>

    <!-- DataTables CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.2/css/buttons.dataTables.min.css">

    <script src="https://kit.fontawesome.com/a076d05399.js"></script>
</head>

<body>
    @include('partials.navbar')

    <h1>DAFTAR AREA</h1>

    <div class="container">

         <div class="top-bar">
            <div class="left-controls">
                <select onchange="handleExport(this.value)">
                    <option value="">Pilih Export</option>
                    <option value="print">Export Print</option>
                    <option value="pdf">Export PDF</option>
                    <option value="csv">Export CSV</option>
                    <option value="excel">Export Excel</option>
                </select>
                <button class="btn-primary" onclick="openModal('modalTambah')">+ Tambah Kategori</button>
            </div>
            <div style="display:flex; align-items:center; gap:6px;">
                <div class="icon-group">
                    <button title="Sorting" onclick="sortTable()"><i class="fas fa-sort"></i></button>
                    <button title="Refresh" onclick="refreshPage()"><i class="fas fa-sync"></i></button>
                </div>
                <input type="text" placeholder="Search">
            </div>
        </div>

         <form method="GET" action="{{ route('area') }}">
            <label for="per_page">Tampilkan:</label>
            <select name="per_page" id="per_page" onchange="this.form.submit()">
                <option value="10" {{ $perPage == 10 ? 'selected' : '' }}>10</option>
                <option value="25" {{ $perPage == 25 ? 'selected' : '' }}>25</option>
                <option value="30" {{ $perPage == 30 ? 'selected' : '' }}>30</option>
                <option value="50" {{ $perPage == 50 ? 'selected' : '' }}>50</option>
                <option value="75" {{ $perPage == 75 ? 'selected' : '' }}>75</option>
                <option value="200" {{ $perPage == 200 ? 'selected' : '' }}>200</option>
                <option value="{{ $areas->total() }}" {{ $perPage == $areas->total() ? 'selected' : '' }}>All
                </option>
            </select>
        </form>

        <!-- Alert sukses -->
        @if(session('success'))
            <div style="padding:10px; background:#d4edda; color:#155724; margin-bottom:15px; border-radius:5px;">
                {{ session('success') }}
            </div>
        @endif

        <table id="tabelArea" class="display">
            <thead>
                <tr>
                    <th><input type="checkbox"></th>
                    <th>Kode Area</th>
                    <th>Nama Area</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($areas as $area)
                    <tr>
                        <td><input type="checkbox"></td>
                        <td>{{ $area->kdarea }}</td>
                        <td>{{ $area->namaarea }}</td>
                        <td>
                            <button class="action-btn" onclick="editArea('{{ $area->kdarea }}', '{{ $area->namaarea }}')"><i class="fa-solid fa-pen-to-square"></i></button>
                            <button class="action-btn" onclick="hapusArea('{{ $area->kdarea }}')"><i class="fas fa-trash"></i></button>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <!-- Modal Tambah -->
    <div id="modalTambah" class="modal" style="display:none;">
        <div class="modal-content">
            <span class="close" onclick="closeModal('modalTambah')">&times;</span>
            <h2>Tambah Area</h2>

            @if ($errors->any())
                <div style="background:#f8d7da; color:#721c24; padding:10px; margin-bottom:10px; border-radius:5px;">
                    <ul style="margin:0; padding-left:20px;">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('area.store') }}" method="POST" id="formTambahArea">
                @csrf
                <label>Kode Area</label>
                <input type="text" name="kdarea" value="{{ old('kdarea') }}" required>

                <label>Nama Area</label>
                <input type="text" name="namaarea" value="{{ old('namaarea') }}">

                <button type="submit">Simpan</button>
            </form>
        </div>
    </div>

    <!-- Modal Edit -->
    <div id="modalEdit" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeModal('modalEdit')">&times;</span>
            <h2>Edit Area</h2>
            <form id="formEdit" method="POST">
                @csrf
                @method('PUT')
                <label>Kode Area</label>
                <input type="text" name="kdarea" id="editkdarea" required>
                <label>Nama Area</label>
                <input type="text" name="namaarea" id="editNamaArea">
                <button type="submit">Update</button>
            </form>
        </div>
    </div>

    <!-- Modal Hapus -->
    <div id="modalHapus" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeModal('modalHapus')">&times;</span>
            <h2>Hapus Area</h2>
            <p>Yakin ingin menghapus area ini?</p>
            <form id="formHapus" method="POST">
                @csrf
                @method('DELETE')
                <button type="submit">Hapus</button>
            </form>
        </div>
    </div>

    <script>

        function openModal(id) { document.getElementById(id).style.display = 'block'; }
        function closeModal(id) { document.getElementById(id).style.display = 'none'; }

        function editArea(kd, nama) {
            document.getElementById('formEdit').action = '/update-area/' + kd;
            document.getElementById('editkdarea').value = kd;
            document.getElementById('editNamaArea').value = nama;
            openModal('modalEdit');
        }

        function hapusArea(kd) {
            document.getElementById('formHapus').action = '/hapus-area/' + kd;
            openModal('modalHapus');
        }

         document.querySelector("input[placeholder='Search']").addEventListener("keyup", function () {
            let value = this.value.toLowerCase();
            document.querySelectorAll("table tbody tr").forEach(function (row) {
                row.style.display = row.innerText.toLowerCase().includes(value) ? "" : "none";
            });
        });
                // Fungsi export ke CSV
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
            link.setAttribute("download", "area.csv");
            link.click();
        }

        // Fungsi export ke Excel (sederhana)
        function exportExcel() {
            let table = document.querySelector("table").outerHTML;
            let data = new Blob([table], { type: "application/vnd.ms-excel" });
            let link = document.createElement("a");
            link.href = URL.createObjectURL(data);
            link.download = "area.xls";
            link.click();
        }

        // Fungsi export ke PDF (pakai print)
        function exportPDF() {
            window.print();
        }

        // Fungsi refresh halaman
        function refreshPage() {
            location.reload();
        }

        // Fungsi sorting (contoh sorting alfabet kolom pertama)
        function sortTable() {
            let table = document.querySelector("table");
            let rows = Array.from(table.rows).slice(1); // skip header
            rows.sort((a, b) => a.cells[0].innerText.localeCompare(b.cells[0].innerText));
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