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
                    <th><input type="checkbox" id="selectAll"></th>
                    <th>Kode Toko</th>
                    <th>Nama Toko</th>
                    <th>Ongkos</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach($ongkos as $row)
                    <tr>
                        <td><input type="checkbox" class="rowCheckbox"></td>
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
        // === Export CSV ===
        function exportCSV() {
            let rows = getSelectedRows();
            let csv = "Kode Toko,Nama Toko,Ongkos\n";
            rows.forEach(r => {
                csv += `${r.kdtoko},${r.namatoko},${r.ongkos}\n`;
            });
            let blob = new Blob([csv], { type: "text/csv" });
            let link = document.createElement("a");
            link.href = URL.createObjectURL(blob);
            link.download = "ongkos.csv";
            link.click();
        }

        // === Export Excel ===
        function exportExcel() {
            let rows = getSelectedRows();
            let table = `<table><tr><th>Kode Toko</th><th>Nama Toko</th><th>Ongkos</th></tr>`;
            rows.forEach(r => {
                table += `<tr><td>${r.kdtoko}</td><td>${r.namatoko}</td><td>${r.ongkos}</td></tr>`;
            });
            table += `</table>`;
            let blob = new Blob([table], { type: "application/vnd.ms-excel" });
            let link = document.createElement("a");
            link.href = URL.createObjectURL(blob);
            link.download = "ongkos.xls";
            link.click();
        }

        // === Export PDF (Surat) ===
        function exportPDF() {
            let rows = getSelectedRows();
            let tableRows = "";
            rows.forEach(r => {
                tableRows += `<tr><td>${r.kdtoko}</td><td>${r.namatoko}</td><td>${r.ongkos}</td></tr>`;
            });

            let surat = `
            <h2 style="text-align:center;">SURAT RESMI</h2>
            <p>Kepada Yth,</p>
            <p><b>Pimpinan Perusahaan</b></p>
            <p>di Tempat</p><br>
            <p>Dengan hormat,</p>
            <p>Bersama ini kami sampaikan daftar ongkos:</p>
            <table border="1" cellspacing="0" cellpadding="5" width="100%">
                <thead>
                    <tr><th>Kode Toko</th><th>Nama Toko</th><th>Ongkos</th></tr>
                </thead>
                <tbody>${tableRows}</tbody>
            </table>
            <br><br><p>Hormat kami,</p><br><br>
            <p><b>(................................)</b></p>
        `;

            let win = window.open("", "", "width=800,height=600");
            win.document.write(`
            <html>
                <head>
                    <title>Surat Resmi Ongkos</title>
                    <style>
                        body { font-family: Arial, sans-serif; margin: 20px; }
                        table { border-collapse: collapse; width: 100%; }
                        th, td { border: 1px solid #000; padding: 6px; }
                        h2 { text-align: center; }
                    </style>
                </head>
                <body>${surat}</body>
            </html>
        `);
            win.document.close();
            win.print();
            win.close();
        }

        // === Handle Export ===
        function handleExport(value) {
            if (value === "pdf") exportPDF();
            if (value === "csv") exportCSV();
            if (value === "excel") exportExcel();
        }

        
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
                    kdtoko: cols[1].innerText,
                    namatoko: cols[2].innerText,
                    ongkos: cols[3].innerText
                });
            });

            return selected;
        }

    </script>
</body>

</html>