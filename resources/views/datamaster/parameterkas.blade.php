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
                    <th><input type="checkbox" id="selectAll"></th>
                    <th>Kode Parameter</th>
                    <th>Paramater Kas</th>
                    <th>Kode Toko</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach($parameterkas as $pk)
                    <tr>
                        <td><input type="checkbox" class="rowCheckbox"></td>
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

        // === Checkbox Select All ===
        document.getElementById("selectAll").addEventListener("change", function () {
            let checked = this.checked;
            document.querySelectorAll(".rowCheckbox").forEach(cb => cb.checked = checked);
        });

        // === Ambil baris terpilih, kalau kosong → ambil semua ===
        function getSelectedRows() {
            let selected = [];
            let checkboxes = document.querySelectorAll(".rowCheckbox:checked");

            let rows = (checkboxes.length > 0)
                ? Array.from(checkboxes).map(cb => cb.closest("tr"))
                : Array.from(document.querySelectorAll("table tbody tr"));

            rows.forEach(row => {
                let cols = row.querySelectorAll("td");
                selected.push({
                    kode: cols[1].innerText,
                    nama: cols[2].innerText,
                    toko: cols[3].innerText
                });
            });

            return selected;
        }

        // === Export CSV ===
        function exportCSV() {
            let rows = getSelectedRows();
            let csv = "Kode Parameter,Parameter Kas,Kode Toko\n";
            rows.forEach(r => {
                csv += `${r.kode},${r.nama},${r.toko}\n`;
            });
            let blob = new Blob([csv], { type: "text/csv" });
            let link = document.createElement("a");
            link.href = URL.createObjectURL(blob);
            link.download = "parameter_kas.csv";
            link.click();
        }

        // === Export Excel ===
        function exportExcel() {
            let rows = getSelectedRows();
            let table = `<table><tr><th>Kode Parameter</th><th>Parameter Kas</th><th>Kode Toko</th></tr>`;
            rows.forEach(r => {
                table += `<tr><td>${r.kode}</td><td>${r.nama}</td><td>${r.toko}</td></tr>`;
            });
            table += `</table>`;
            let blob = new Blob([table], { type: "application/vnd.ms-excel" });
            let link = document.createElement("a");
            link.href = URL.createObjectURL(blob);
            link.download = "parameter_kas.xls";
            link.click();
        }

        // === Export PDF (pakai window.print surat resmi sederhana) ===
        function exportPDF() {
            let rows = getSelectedRows();
            let tableRows = "";
            rows.forEach(r => {
                tableRows += `<tr><td>${r.kode}</td><td>${r.nama}</td><td>${r.toko}</td></tr>`;
            });

            let surat = `
            <h2 style="text-align:center;">SURAT RESMI DATA PARAMETER KAS</h2>
            <p>Kepada Yth,</p>
            <p><b>Pimpinan Perusahaan</b></p>
            <p>di Tempat</p><br>
            <p>Dengan hormat,</p>
            <p>Bersama ini kami sampaikan daftar parameter kas:</p>
            <table border="1" cellspacing="0" cellpadding="5" width="100%">
                <thead><tr><th>Kode Parameter</th><th>Parameter Kas</th><th>Kode Toko</th></tr></thead>
                <tbody>${tableRows}</tbody>
            </table>
            <br><br><p>Hormat kami,</p><br><br>
            <p><b>(................................)</b></p>
        `;

            let win = window.open("", "", "width=800,height=600");
            win.document.write(`<html><head><title>Surat Resmi</title></head><body>${surat}</body></html>`);
            win.document.close();
            win.print();
            win.close();
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
            if (value === "pdf") exportPDF();
            if (value === "csv") exportCSV();
            if (value === "excel") exportExcel();
        }
    </script>
</body>

</html>