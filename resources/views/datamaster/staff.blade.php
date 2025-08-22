<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="{{ asset('css/datamaster/bakibarang.css') }}">
    <link href="https://fonts.googleapis.com/css?family=Montserrat:400,800" rel="stylesheet">
    <title>Halaman Staff</title>
</head>

<body>
    @include('partials.navbar')
    <h1 class="page-title">STAFF</h1>

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
                <button class="btn-primary" onclick="openModal('modalTambah')">+ Tambah Staff</button>
            </div>
            <div style="display:flex; align-items:center; gap:6px;">
                <div class="icon-group">
                    <button title="Sorting" onclick="sortTable()"><i class="fas fa-sort"></i></button>
                    <button title="Refresh" onclick="refreshPage()"><i class="fas fa-sync"></i></button>
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
                    <th>Kode Staff</th>
                    <th>Nama Staff</th>
                    <th>Posisi</th>
                    <th>Kode Toko</th>
                    <th>Nama Toko</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach($staff as $row)
                    <tr>
                        <td><input type="checkbox"></td>
                        <td>{{ $row->kdstaff }}</td>
                        <td>{{ $row->nama }}</td>
                        <td>{{ $row->posisi }}</td>
                        <td>{{ $row->kdtoko }}</td>
                        <td>{{ $row->cabang->namatoko ?? '-' }}</td>
                        <td>
                            <button class="action-btn"
                                onclick="openEdit('{{ $row->kdstaff }}', '{{ $row->nama }}', '{{ $row->posisi }}', '{{ $row->kdtoko }}')">
                                <i class="fa-solid fa-pen-to-square"></i>
                            </button>
                            <button class="action-btn" onclick="openHapus('{{ $row->kdstaff }}')">
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
                <h2>Tambah Staff</h2>
                <form action="{{ route('staff.store') }}" method="POST">
                    @csrf
                    <label>Kode Staff</label>
                    <input type="text" name="kdstaff" required>
                    <label>Nama Staff</label>
                    <input type="text" name="nama" required>
                    <label>Posisi</label>
                    <select name="posisi" required>
                        <option value="KASIR">KASIR</option>
                        <option value="FRONTLINE">FRONTLINE</option>
                    </select>
                    <label>Kode Toko</label>
                    <select name="kdtoko" required>
                        <option value="">--Pilih Toko--</option>
                        @foreach(\App\Models\Cabang::all() as $toko)
                            <option value="{{ $toko->kdtoko }}">{{ $toko->kdtoko }} - {{ $toko->namatoko }}</option>
                        @endforeach
                    </select>
                    <button type="submit">Simpan</button>
                </form>
            </div>
        </div>

        <!-- Modal Edit -->
        <div id="modalEdit" class="modal">
            <div class="modal-content">
                <span class="close" onclick="closeModal('modalEdit')">&times;</span>
                <h2>Edit Staff</h2>
                <form id="formEdit" method="POST">
                    @csrf
                    @method('PUT')
                    <label>Kode Staff</label>
                    <input type="text" name="kdstaff" id="editkdstaff" required>
                    <label>Nama Staff</label>
                    <input type="text" name="nama" id="editnama" required>
                    <label>Posisi</label>
                    <select name="posisi" id="editposisi" required>
                        <option value="KASIR">KASIR</option>
                        <option value="FRONTLINE">FRONTLINE</option>
                    </select>
                    <label>Kode Toko</label>
                    <select name="kdtoko" id="editkdtoko" required>
                        @foreach(\App\Models\Cabang::all() as $toko)
                            <option value="{{ $toko->kdtoko }}">{{ $toko->kdtoko }} - {{ $toko->namatoko }}</option>
                        @endforeach
                    </select>
                    <button type="submit">Update</button>
                </form>
            </div>
        </div>

        <!-- Modal Hapus -->
        <div id="modalHapus" class="modal">
            <div class="modal-content">
                <span class="close" onclick="closeModal('modalHapus')">&times;</span>
                <h2>Hapus Staff</h2>
                <p>Yakin ingin menghapus data staff ini?</p>
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

        function openEdit(kdstaff, nama, posisi, kdtoko) {
            document.getElementById("formEdit").action = "/staff-update/" + kdstaff;
            document.getElementById("editkdstaff").value = kdstaff;
            document.getElementById("editnama").value = nama;
            document.getElementById("editposisi").value = posisi;
            document.getElementById("editkdtoko").value = kdtoko;
            openModal("modalEdit");
        }

        function openHapus(kdstaff) {
            document.getElementById("formHapus").action = "/staff-destroy/" + kdstaff;
            openModal("modalHapus");
        }

        function refreshPage() { location.reload(); }

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
            link.setAttribute("download", "staff.csv");
            link.click();
        }

        function exportExcel() {
            let table = document.querySelector("table").outerHTML;
            let data = new Blob([table], { type: "application/vnd.ms-excel" });
            let link = document.createElement("a");
            link.href = URL.createObjectURL(data);
            link.download = "staff.xls";
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