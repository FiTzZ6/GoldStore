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
    <h1 class="page-title">DATA POTONGAN</h1>

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
                    <th>Nomor</th>
                    <th>Kode Kategori</th>
                    <th>Jumlah Potongan / Gram</th>
                    <th>Jenis Potongan</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach($potongan as $p)
                    <tr>
                        <td><input type="checkbox" class="rowCheckbox"></td>
                        <td>{{ $p->kdpotongan }}</td>
                        <td>{{ $p->kategori->namakategori ?? '-' }}</td>
                        <td>{{ $p->jumlahpotongan }}</td>
                        <td>{{ $p->jenispotongan }}</td>
                        <td>
                            <!-- Tombol Edit -->
                            <button class="action-btn"
                                onclick="openEdit('{{ $p->kdpotongan }}','{{ $p->kdkategori }}','{{ $p->jumlahpotongan }}','{{ $p->jenispotongan }}','{{ $p->kdtoko }}')">
                                <i class="fa-solid fa-pen-to-square"></i>
                            </button>

                            <!-- Tombol Hapus -->
                            <button class="action-btn" onclick="openHapus('{{ $p->kdpotongan }}')">
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
                <h2>Tambah Potongan</h2>
                <form action="{{ route('potongan.store') }}" method="POST">
                    @csrf
                    <label>Kode Potongan</label>
                    <input type="text" name="kdpotongan" required>

                    <label>Kategori</label>
                    <select name="kdkategori" required>
                        @foreach($kategori as $k)
                            <option value="{{ $k->kdkategori }}">{{ $k->namakategori }}</option>
                        @endforeach
                    </select>

                    <label>Jumlah Potongan</label>
                    <input type="number" step="0.01" name="jumlahpotongan" required>

                    <label>Jenis Potongan</label>
                    <select name="jenispotongan" required>
                        <option value="PROSENTASE">PROSENTASE</option>
                        <option value="RUPIAH">RUPIAH</option>
                    </select>

                    <label>Toko</label>
                    <select name="kdtoko" required>
                        @foreach($toko as $t)
                            <option value="{{ $t->kdtoko }}">{{ $t->namatoko }}</option>
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
                <h2>Edit Potongan</h2>
                <form id="formEdit" method="POST">
                    @csrf
                    @method('PUT')

                    <label>Kategori</label>
                    <select name="kdkategori" id="editKategori" required>
                        @foreach($kategori as $k)
                            <option value="{{ $k->kdkategori }}">{{ $k->namakategori }}</option>
                        @endforeach
                    </select>

                    <label>Jumlah Potongan</label>
                    <input type="number" step="0.01" name="jumlahpotongan" id="editJumlah" required>

                    <label>Jenis Potongan</label>
                    <select name="jenispotongan" id="editJenis" required>
                        <option value="PROSENTASE">PROSENTASE</option>
                        <option value="RUPIAH">RUPIAH</option>
                    </select>

                    <label>Toko</label>
                    <select name="kdtoko" id="editToko" required>
                        @foreach($toko as $t)
                            <option value="{{ $t->kdtoko }}">{{ $t->namatoko }}</option>
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
                <h2>Hapus Potongan</h2>
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

        function openEdit(id, kategori, jumlah, jenis, toko) {
            document.getElementById("formEdit").action = "/potongan/" + id;
            document.getElementById("editKategori").value = kategori;
            document.getElementById("editJumlah").value = jumlah;
            document.getElementById("editJenis").value = jenis;
            document.getElementById("editToko").value = toko;
            openModal("modalEdit");
        }

        function openHapus(id) {
            document.getElementById("formHapus").action = "/potongan/" + id;
            openModal("modalHapus");
        }

        // Search sederhana
        document.querySelector("input[placeholder='Search']").addEventListener("keyup", function () {
            let value = this.value.toLowerCase();
            document.querySelectorAll("table tbody tr").forEach(function (row) {
                row.style.display = row.innerText.toLowerCase().includes(value) ? "" : "none";
            });
        });

        // === Export CSV ===
        function exportCSV() {
            let rows = getSelectedRows();
            let csv = "Nomor,Kategori,Jumlah Potongan,Jenis Potongan\n";
            rows.forEach(r => {
                csv += `${r.nomor},${r.kategori},${r.jumlah},${r.jenis}\n`;
            });
            let blob = new Blob([csv], { type: "text/csv" });
            let link = document.createElement("a");
            link.href = URL.createObjectURL(blob);
            link.download = "potongan.csv";
            link.click();
        }

        // === Export Excel ===
        function exportExcel() {
            let rows = getSelectedRows();
            let table = `<table><tr><th>Nomor</th><th>Kategori</th><th>Jumlah Potongan</th><th>Jenis Potongan</th></tr>`;
            rows.forEach(r => {
                table += `<tr><td>${r.nomor}</td><td>${r.kategori}</td><td>${r.jumlah}</td><td>${r.jenis}</td></tr>`;
            });
            table += `</table>`;
            let blob = new Blob([table], { type: "application/vnd.ms-excel" });
            let link = document.createElement("a");
            link.href = URL.createObjectURL(blob);
            link.download = "potongan.xls";
            link.click();
        }

        // === Export PDF (Surat) ===
        function exportPDF() {
            let rows = getSelectedRows();
            let tableRows = "";
            rows.forEach(r => {
                tableRows += `<tr><td>${r.nomor}</td><td>${r.kategori}</td><td>${r.jumlah}</td><td>${r.jenis}</td></tr>`;
            });

            let surat = `
            <h2 style="text-align:center;">SURAT RESMI DATA POTONGAN</h2>
            <p>Kepada Yth,</p>
            <p><b>Pimpinan Perusahaan</b></p>
            <p>di Tempat</p><br>
            <p>Dengan hormat,</p>
            <p>Bersama ini kami sampaikan daftar potongan:</p>
            <table border="1" cellspacing="0" cellpadding="5" width="100%">
                <thead>
                    <tr><th>Nomor</th><th>Kategori</th><th>Jumlah Potongan</th><th>Jenis Potongan</th></tr>
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
                    <title>Surat Resmi Potongan</title>
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

        // === Handle Export ===
        function handleExport(value) {
            if (value === "pdf") exportPDF();
            if (value === "csv") exportCSV();
            if (value === "excel") exportExcel();
        }
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
                    nomor: cols[1].innerText,
                    kategori: cols[2].innerText,
                    jumlah: cols[3].innerText,
                    jenis: cols[4].innerText
                });
            });

            return selected;
        }

    </script>
</body>

</html>