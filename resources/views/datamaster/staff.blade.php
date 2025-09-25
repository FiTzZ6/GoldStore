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
                    <th><input type="checkbox" id="checkAll"></th>
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
                        <td><input type="checkbox" class="row-check"></td>
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
        function openModal(id) { document.getElementById(id).style.display = "block"; }
        function closeModal(id) { document.getElementById(id).style.display = "none"; }

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

        // === Checkbox Select All ===
        document.getElementById("checkAll").addEventListener("change", function () {
            let checked = this.checked;
            document.querySelectorAll(".row-check").forEach(cb => cb.checked = checked);
        });

        // === Ambil baris terpilih (kalau kosong → ambil semua) ===
        function getSelectedRows() {
            let selected = [];
            let checkboxes = document.querySelectorAll(".row-check:checked");

            let rows = (checkboxes.length > 0)
                ? Array.from(checkboxes).map(cb => cb.closest("tr"))
                : Array.from(document.querySelectorAll("table tbody tr"));

            rows.forEach(row => {
                let cols = row.querySelectorAll("td");
                selected.push({
                    kode: cols[1].innerText,
                    nama: cols[2].innerText,
                    posisi: cols[3].innerText,
                    kdtoko: cols[4].innerText,
                    namatoko: cols[5].innerText
                });
            });

            return selected;
        }

        // === Export CSV ===
        function exportCSV() {
            let rows = getSelectedRows();
            let csv = "Kode Staff,Nama Staff,Posisi,Kode Toko,Nama Toko\n";
            rows.forEach(r => {
                csv += `${r.kode},${r.nama},${r.posisi},${r.kdtoko},${r.namatoko}\n`;
            });
            let blob = new Blob([csv], { type: "text/csv" });
            let link = document.createElement("a");
            link.href = URL.createObjectURL(blob);
            link.download = "staff.csv";
            link.click();
        }

        // === Export Excel ===
        function exportExcel() {
            let rows = getSelectedRows();
            let table = `<table><tr><th>Kode Staff</th><th>Nama Staff</th><th>Posisi</th><th>Kode Toko</th><th>Nama Toko</th></tr>`;
            rows.forEach(r => {
                table += `<tr><td>${r.kode}</td><td>${r.nama}</td><td>${r.posisi}</td><td>${r.kdtoko}</td><td>${r.namatoko}</td></tr>`;
            });
            table += `</table>`;
            let blob = new Blob([table], { type: "application/vnd.ms-excel" });
            let link = document.createElement("a");
            link.href = URL.createObjectURL(blob);
            link.download = "staff.xls";
            link.click();
        }

        // === Export PDF (pakai window.print surat resmi sederhana) ===
        function exportPDF() {
            let rows = getSelectedRows();
            let tableRows = "";
            rows.forEach(r => {
                tableRows += `<tr>
            <td>${r.kode}</td>
            <td>${r.nama}</td>
            <td>${r.posisi}</td>
            <td>${r.kdtoko}</td>
            <td>${r.namatoko}</td>
        </tr>`;
            });

            let surat = `
        <h2 style="text-align:center;">SURAT RESMI DATA STAFF</h2>
        <p>Kepada Yth,</p>
        <p><b>Pimpinan Perusahaan</b></p>
        <p>di Tempat</p><br>
        <p>Dengan hormat,</p>
        <p>Bersama ini kami sampaikan daftar staff sebagai berikut:</p>
        <table border="1" cellspacing="0" cellpadding="5" width="100%">
            <thead>
                <tr>
                    <th>Kode Staff</th>
                    <th>Nama Staff</th>
                    <th>Posisi</th>
                    <th>Kode Toko</th>
                    <th>Nama Toko</th>
                </tr>
            </thead>
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


        // Handle export
        function handleExport(value) {
            if (value === "pdf") exportPDF();
            if (value === "csv") exportCSV();
            if (value === "excel") exportExcel();
        }

        // Search sederhana
        document.querySelector("input[placeholder='Search']").addEventListener("keyup", function () {
            let value = this.value.toLowerCase();
            document.querySelectorAll("table tbody tr").forEach(function (row) {
                row.style.display = row.innerText.toLowerCase().includes(value) ? "" : "none";
            });
        });
    </script>
</body>

</html>