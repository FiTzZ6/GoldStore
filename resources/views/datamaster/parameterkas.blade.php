<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="{{ asset('css/datamaster/bakibarang.css') }}">
    <link href="https://fonts.googleapis.com/css?family=Montserrat:400,800" rel="stylesheet">
    <title>Halaman Area</title>
</head>
@include('partials.navbar')

<body>
    <h1 class="page-title">Parameter Kas</h1>

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
                <button class="btn-primary" onclick="openModal('modalTambah')">+ Tambah Potongan</button>
            </div>
            <div style="display:flex; align-items:center; gap:6px;">
                <div class="icon-group">
                    <button title="Sorting"><i class="fas fa-sort"></i></button>
                    <button title="Refresh" onclick="location.reload()"><i class="fas fa-sync"></i></button>
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
                    <th>Kode Parameter</th>
                    <th>Paramater Kas</th>
                    <th>Kode Toko</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach($parameterkas as $pk)
                    <tr>
                        <td><input type="checkbox"></td>
                        <td>{{ $pk->kdparameterkas }}</td>
                        <td>{{ $pk->parameterkas }}</td>
                        <td>{{ $pk->cabang->namatoko ?? '-' }}</td>
                        <td>
                            <!-- Tombol Edit -->
                            <button class="action-btn"
                                onclick="openEdit('{{ $pk->kdparameterkas }}', '{{ $pk->parameterkas }}', '{{ $pk->kdtoko }}')">
                                <i class="fa-solid fa-pen-to-square"></i>
                            </button>

                            <!-- Tombol Hapus -->
                            <form action="{{ route('parameterkas.destroy', $pk->kdparameterkas) }}" method="POST"
                                style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <button class="action-btn" onclick="return confirm('Yakin hapus?')">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <!-- Modal Tambah Data -->
        <div id="modalTambah" class="modal">
            <div class="modal-content">
                <span class="close">&times;</span>
                <h2>Tambah Data Parameter Kas</h2>
                <form action="{{ route('parameterkas.store') }}" method="POST">
                    @csrf
                    <label for="kdparameterkas">Kode:</label>
                    <input type="text" name="kdparameterkas" required>

                    <label for="parameterkas">Parameter Kas:</label>
                    <input type="text" name="parameterkas" required>

                    <label for="kdtoko">Pilih Toko:</label>
                    <select name="kdtoko" required>
                        @foreach($cabang as $c)
                            <option value="{{ $c->kdtoko }}">{{ $c->namatoko }}</option>
                        @endforeach
                    </select>

                    <button type="submit" class="add-btn">Simpan</button>
                </form>
            </div>
        </div>

        <!-- Modal Edit -->
        <div id="modalEdit" class="modal">
            <div class="modal-content">
                <span class="close" onclick="closeModal('modalEdit')">&times;</span>
                <h2>Edit Parameter Kas</h2>
                <form id="formEdit" method="POST">
                    @csrf
                    @method('PUT')

                    <label>Kode Parameter Kas</label>
                    <input type="text" name="kdparameterkas" id="editKdParameterKas" required>

                    <label>Nama Parameter Kas</label>
                    <input type="text" name="parameterkas" id="editParameterKas" required>

                    <label>Toko</label>
                    <select name="kdtoko" id="editToko" required>
                        @foreach($cabang as $c)
                            <option value="{{ $c->kdtoko }}">{{ $c->namatoko }}</option>
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
                <h2>Hapus Parameter Kas</h2>
                <p>Yakin ingin menghapus data ini?</p>
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

        function openEdit(id, parameterkas, toko) {
            document.getElementById("formEdit").action = "/parameter-kas/" + id;
            document.getElementById("editKdParameterKas").value = id;   // <--- isi primary key
            document.getElementById("editParameterKas").value = parameterkas;
            document.getElementById("editToko").value = toko;
            openModal("modalEdit");
        }

        function openHapus(id) {
            document.getElementById("formHapus").action = "/parameter-kas/" + id;
            openModal("modalHapus");
        }
        // Search sederhana
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