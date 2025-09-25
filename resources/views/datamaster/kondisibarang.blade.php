<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="{{ asset('css/datamaster/bakibarang.css') }}">
    <link href="https://fonts.googleapis.com/css?family=Montserrat:400,800" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" />
    <title>Halaman Area</title>

    <!-- DataTables CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.2/css/buttons.dataTables.min.css">

    <script src="https://kit.fontawesome.com/a076d05399.js"></script>
</head>

<body>
    @include('partials.navbar') <h1 class="page-title">KONDISI BARANG</h1>


    <div class="container">

        <div class="top-bar">
            <div class="left-controls">
                <select onchange="handleExport(this.value)">
                    <option value="">Pilih Export</option>
                    <option value="pdf">Export PDF</option>
                    <option value="csv">Export CSV</option>
                    <option value="excel">Export Excel</option>
                </select>

                <button class="btn-primary" onclick="openModal('modalTambah')">+ Tambah Kondisi</button>
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

        <table id="tabelKondisi" class="display">
            <thead>
                <tr>
                    <th><input type="checkbox" id="selectAll"></th>
                    <th>Nomor</th>
                    <th>Kondisi</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach($kondisi as $index => $row)
                    <tr>
                        <td><input type="checkbox" class="rowCheckbox"></td>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $row->kondisibarang }}</td>
                        <td>
                            <!-- Tombol Edit -->
                            <button class="action-btn"
                                onclick="openEdit({{ $row->kdkondisi }}, '{{ $row->kondisibarang }}')">
                                <i class="fa-solid fa-pen-to-square"></i>
                            </button>

                            <!-- Tombol Hapus -->
                            <form action="{{ route('kondisi.destroy', $row->kdkondisi) }}" method="POST"
                                style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <button class="action-btn" onclick="return confirm('Yakin ingin hapus?')">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <!-- Modal Tambah -->
        <div id="modalTambah" class="modal">
            <div class="modal-content">
                <span class="close" onclick="closeModal('modalTambah')">&times;</span>
                <h2>Tambah Kondisi</h2>
                <form action="{{ route('kondisi.store') }}" method="POST">
                    @csrf
                    <label>Nama Kondisi</label>
                    <input type="text" name="kondisibarang" required>
                    <button type="submit">Simpan</button>
                </form>
            </div>
        </div>

        <!-- Modal Edit -->
        <div id="modalEdit" class="modal">
            <div class="modal-content">
                <span class="close" onclick="closeModal('modalEdit')">&times;</span>
                <h2>Edit Kondisi</h2>
                <form id="formEdit" method="POST">
                    @csrf
                    @method('PUT')
                    <label>Kondisi</label>
                    <input type="text" name="kondisibarang" id="editkondisi">
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
        function openEdit(id, nama) {
            document.getElementById("formEdit").action = "/kondisi-update/" + id;
            document.getElementById("editkondisi").value = nama;
            openModal("modalEdit");
        }

        // === Export CSV ===
        function exportCSV() {
            let rows = getSelectedRows();
            if (rows.length === 0) return alert("Pilih data dulu!");
            let csv = "Nomor,Kondisi\n";
            rows.forEach(r => {
                csv += `${r.nomor},${r.kondisi}\n`;
            });
            let blob = new Blob([csv], { type: "text/csv" });
            let link = document.createElement("a");
            link.href = URL.createObjectURL(blob);
            link.download = "kondisi.csv";
            link.click();
        }

        // === Export Excel ===
        function exportExcel() {
            let rows = getSelectedRows();
            if (rows.length === 0) return alert("Pilih data dulu!");
            let table = `<table><tr><th>Nomor</th><th>Kondisi</th></tr>`;
            rows.forEach(r => {
                table += `<tr><td>${r.nomor}</td><td>${r.kondisi}</td></tr>`;
            });
            table += `</table>`;
            let blob = new Blob([table], { type: "application/vnd.ms-excel" });
            let link = document.createElement("a");
            link.href = URL.createObjectURL(blob);
            link.download = "kondisi.xls";
            link.click();
        }

        // === Export PDF (Surat Resmi) ===
        function exportPDF() {
            let rows = getSelectedRows();
            if (rows.length === 0) return alert("Pilih data dulu!");

            let tableRows = "";
            rows.forEach(r => {
                tableRows += `<tr>
                <td>${r.nomor}</td>
                <td>${r.kondisi}</td>
            </tr>`;
            });

            let surat = `
            <h2 style="text-align:center;">SURAT RESMI</h2>
            <p>Kepada Yth,</p>
            <p><b>Pimpinan Perusahaan</b></p>
            <p>di Tempat</p><br>
            <p>Dengan hormat,</p>
            <p>Bersama ini kami sampaikan daftar kondisi barang:</p>
            <table border="1" cellspacing="0" cellpadding="5" width="100%">
                <thead>
                    <tr><th>Nomor</th><th>Kondisi</th></tr>
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
                    <title>Surat Resmi Kondisi</title>
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
            win.focus();
            win.print();
            win.close();
        }

        // === Select All ===
        document.getElementById("selectAll").addEventListener("change", function () {
            document.querySelectorAll(".rowCheckbox").forEach(cb => cb.checked = this.checked);
        });

        // === Handle Export ===
        function handleExport(value) {
            if (value === "pdf") exportPDF();
            if (value === "csv") exportCSV();
            if (value === "excel") exportExcel();
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

        document.querySelector("input[placeholder='Search']").addEventListener("keyup", function () {
            let value = this.value.toLowerCase();
            document.querySelectorAll("table tbody tr").forEach(function (row) {
                row.style.display = row.innerText.toLowerCase().includes(value) ? "" : "none";
            });
        });

        // === Ambil baris yang dipilih ===
        function getSelectedRows() {
            let selected = [];
            document.querySelectorAll(".rowCheckbox:checked").forEach(cb => {
                let row = cb.closest("tr");
                let cols = row.querySelectorAll("td");
                selected.push({
                    nomor: cols[1].innerText,
                    kondisi: cols[2].innerText
                });
            });
            return selected;
        }

    </script>

</body>

</html>