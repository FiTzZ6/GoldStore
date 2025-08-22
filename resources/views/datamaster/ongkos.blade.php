<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="{{ asset('css/datamaster/bakibarang.css') }}">
    <link href="https://fonts.googleapis.com/css?family=Montserrat:400,800" rel="stylesheet">
    <title>Halaman Area</title>
</head>

<body>
    @include('partials.navbar') <h1 class="page-title">ONGKOS</h1>


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
                <button class="btn-primary" onclick="openModal('modalTambah')">+ Tambah Area</button>
            </div>
            <div style="display:flex; align-items:center; gap:6px;">
                <div class="icon-group">
                    <button title="Sorting" onclick="sortTable()"><i class="fas fa-sort"></i></button>
                    <button title="Refresh" onclick="refreshPage()"><i class="fas fa-sync"></i></button>
                    <button title="Tampilan List"><i class="fas fa-list"></i></button>
                    <button title="Tampilan Grid"><i class="fas fa-th"></i></button>
                    <button title="Export"><i class="fas fa-file-export"></i></button>
                </div>
                <input type="text" placeholder="Search">
            </div>
        </div>

        @if(session('success'))
            <div style="padding:10px; background:#d4edda; color:#155724; margin-bottom:15px; border-radius:5px;">
                {{ session('success') }}
            </div>
        @endif
        <table>
            <thead>
                <tr>
                    <th><input type="checkbox"></th>
                    <th>Kode Toko</th>
                    <th>Nama Toko</th>
                    <th>Ongkos</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach($ongkos as $row)
                    <tr>
                        <td><input type="checkbox"></td>
                        <td>{{ $row->kdtoko }}</td>
                        <td>{{ $row->toko->namatoko ?? '-' }}</td>
                        <td>{{ number_format($row->ongkos, 2) }}</td>
                        <td>
                            <button class="action-btn"
                                onclick="openEdit({{ $row->id }}, '{{ $row->kdtoko }}', '{{ $row->ongkos }}')">
                                <i class="fa-solid fa-pen-to-square"></i>
                            </button>

                            <!-- Tombol Hapus -->
                            <button class="action-btn" onclick="openHapus({{ $row->id }})">
                                <i class="fas fa-trash"></i>
                            </button>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <!-- Modal Tambah -->
        <div id="modalTambah" class="modal">
            <div class="modal-content">
                <span class="close" onclick="closeModal('modalTambah')">&times;</span>
                <h2>Tambah Ongkos</h2>
                <form action="{{ route('ongkos.store') }}" method="POST">
                    @csrf
                    <label>Kode Toko</label>
                    <select name="kdtoko" required>
                        <option value="">--Pilih Toko--</option>
                        @foreach(\App\Models\Cabang::all() as $toko)
                            <option value="{{ $toko->kdtoko }}">{{ $toko->kdtoko }} - {{ $toko->namatoko }}</option>
                        @endforeach
                    </select>
                    <label>Ongkos</label>
                    <input type="number" step="0.01" name="ongkos" required>
                    <button type="submit">Simpan</button>
                </form>
            </div>
        </div>

        <!-- Modal Edit -->
        <div id="modalEdit" class="modal">
            <div class="modal-content">
                <span class="close" onclick="closeModal('modalEdit')">&times;</span>
                <h2>Edit Ongkos</h2>
                <form id="formEdit" method="POST">
                    @csrf
                    @method('PUT')
                    <label>Kode Toko</label>
                    <select name="kdtoko" id="editkdtoko" required>
                        @foreach(\App\Models\Cabang::all() as $toko)
                            <option value="{{ $toko->kdtoko }}">{{ $toko->kdtoko }} - {{ $toko->namatoko }}</option>
                        @endforeach
                    </select>
                    <label>Ongkos</label>
                    <input type="number" step="0.01" name="ongkos" id="editongkos" required>
                    <button type="submit">Update</button>
                </form>
            </div>
        </div>

        <!-- Modal Hapus -->
        <div id="modalHapus" class="modal">
            <div class="modal-content">
                <span class="close" onclick="closeModal('modalHapus')">&times;</span>
                <h2>Hapus Ongkos</h2>
                <p>Yakin ingin menghapus data ongkos ini?</p>
                <form id="formHapus" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="submit">Hapus</button>
                </form>
            </div>
        </div>

        <div class="footer">
            SKIBIDI
        </div>
    </div>

    <script>
        function openModal(id) {
            document.getElementById(id).style.display = "block";
        }
        function closeModal(id) {
            document.getElementById(id).style.display = "none";
        }

        function openEdit(id, kdtoko, ongkos) {
            document.getElementById("formEdit").action = "/ongkos-update/" + id;
            document.getElementById("editkdtoko").value = kdtoko;
            document.getElementById("editongkos").value = ongkos;
            openModal("modalEdit");
        }

        function openHapus(id) {
            document.getElementById("formHapus").action = "/ongkos-destroy/" + id;
            openModal("modalHapus");
        }

        function refreshPage() {
            location.reload();
        }
        function sortTable() {
            let table = document.querySelector("table");
            let rows = Array.from(table.rows).slice(1);
            rows.sort((a, b) => a.cells[1].innerText.localeCompare(b.cells[1].innerText));
            rows.forEach(row => table.appendChild(row));
        }
        function handleExport(value) {
            if (value === "print") window.print();
            if (value === "pdf") window.print();
            if (value === "csv") exportCSV();
            if (value === "excel") exportExcel();
        }
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
            link.setAttribute("download", "ongkos.csv");
            link.click();
        }
        function exportExcel() {
            let table = document.querySelector("table").outerHTML;
            let data = new Blob([table], { type: "application/vnd.ms-excel" });
            let link = document.createElement("a");
            link.href = URL.createObjectURL(data);
            link.download = "ongkos.xls";
            link.click();
        }
        document.querySelector("input[placeholder='Search']").addEventListener("keyup", function () {
            let value = this.value.toLowerCase();
            document.querySelectorAll("table tbody tr").forEach(function (row) {
                row.style.display = row.innerText.toLowerCase().includes(value) ? "" : "none";
            });
        });
    </script>
</body>

</html>