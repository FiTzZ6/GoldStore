<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="{{ asset('css/datamaster/kategori.css') }}">
    <link href="https://fonts.googleapis.com/css?family=Montserrat:400,800" rel="stylesheet">
    <title>Halaman Area</title>
</head>

<body>

    @include('partials.navbar')
    <h1 class="page-title">KATEGORI BARANG</h1>


    <div class="container">

        {{-- Alert success/error --}}
        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        @if (session('error'))
            <div class="alert alert-error">
                {{ session('error') }}
            </div>
        @endif

        <div class="top-bar">
            <div class="left-controls">
                <select onchange="handleExport(this.value)">
                    <option value="">Pilih Export</option>
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
                    <button title="Tampilan List"><i class="fas fa-list"></i></button>
                    <button title="Tampilan Grid"><i class="fas fa-th"></i></button>
                </div>
                <input type="text" placeholder="Search">
            </div>
        </div>

        <form method="GET" action="{{ route('kategoribarang') }}">
            <label for="per_page">Tampilkan:</label>
            <select name="per_page" id="per_page" onchange="this.form.submit()">
                <option value="10" {{ $perPage == 10 ? 'selected' : '' }}>10</option>
                <option value="25" {{ $perPage == 25 ? 'selected' : '' }}>25</option>
                <option value="30" {{ $perPage == 30 ? 'selected' : '' }}>30</option>
                <option value="50" {{ $perPage == 50 ? 'selected' : '' }}>50</option>
                <option value="75" {{ $perPage == 75 ? 'selected' : '' }}>75</option>
                <option value="200" {{ $perPage == 200 ? 'selected' : '' }}>200</option>
                <option value="{{ $kategori->total() }}" {{ $perPage == $kategori->total() ? 'selected' : '' }}>All
                </option>
            </select>
        </form>

        <table>
            <thead>
                <tr>
                    <th><input type="checkbox" id="selectAll"></th>
                    <th>Kode Kategori</th>
                    <th>Nama Kategori</th>
                    <th>Harga/gr</th>
                    <th>Jumlah Kadar</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach($kategori as $row)
                    <tr>
                        <td><input type="checkbox" class="rowCheckbox"></td>
                        <td>{{ $row->kdkategori }}</td>
                        <td>{{ $row->namakategori }}</td>
                        <td>{{ number_format($row->hargapergr, 0, ',', '.') }}</td>
                        <td>{{ $row->jumlahkadar }}%</td>
                        <td>
                            <button class="action-btn"
                                onclick="openEditModal('{{ $row->id }}', '{{ $row->kdkategori }}', '{{ $row->namakategori }}', '{{ $row->hargapergr }}', '{{ $row->jumlahkadar }}')">
                                <i class="fa-solid fa-pen-to-square"></i>
                            </button>
                            <button class="action-btn" onclick="openDeleteModal('{{ $row->kdkategori }}')">
                                <i class="fas fa-trash"></i>
                            </button>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <div class="footer">
            SKIBIDI
        </div>
    </div>

    {{-- Modal Tambah --}}
    <div id="modalTambah" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeModal('modalTambah')">&times;</span>
            <h2>Tambah Kategori</h2>
            <form action="{{ route('kategori.store') }}" method="POST">
                @csrf
                <label>Kode Kategori</label>
                <input type="text" name="kdkategori" required>
                <label>Nama Kategori</label>
                <input type="text" name="namakategori" required>
                <label>Harga per gr</label>
                <input type="number" step="0.01" name="hargapergr" required>
                <label>Jumlah Kadar</label>
                <input type="number" step="0.01" name="jumlahkadar" required>
                <button type="submit" class="btn-primary">Simpan</button>
            </form>
        </div>
    </div>

    {{-- Modal Edit --}}
    <div id="modalEdit" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeModal('modalEdit')">&times;</span>
            <h2>Edit Kategori</h2>
            <form id="formEdit" method="POST">
                @csrf
                @method('PUT')
                <label>Kode Kategori</label>
                <input type="text" name="kdkategori" id="editkategori" required>
                <label>Nama Kategori</label>
                <input type="text" name="namakategori" id="editNama" required>
                <label>Harga per gr</label>
                <input type="number" step="0.01" name="hargapergr" id="editHarga" required>
                <label>Jumlah Kadar</label>
                <input type="number" step="0.01" name="jumlahkadar" id="editKadar" required>
                <button type="submit" class="btn-primary">Update</button>
            </form>
        </div>
    </div>

    {{-- Modal Hapus --}}
    <div id="modalHapus" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeModal('modalHapus')">&times;</span>
            <h2>Hapus Kategori</h2>
            <form id="formHapus" method="POST">
                @csrf
                @method('DELETE')
                <p>Apakah anda yakin ingin menghapus kategori ini?</p>
                <button type="submit" class="btn-primary">Ya, Hapus</button>
            </form>
        </div>
    </div>

    <script>
        // === Modal ===
        function openModal(id) { document.getElementById(id).classList.add('show'); }
        function closeModal(id) { document.getElementById(id).classList.remove('show'); }
        function openEditModal(id, kdkategori, nama, harga, kadar) {
            let modal = document.getElementById('modalEdit');
            modal.classList.add('show');
            document.getElementById('editkategori').value = kdkategori;
            document.getElementById('editNama').value = nama;
            document.getElementById('editHarga').value = harga;
            document.getElementById('editKadar').value = kadar;
            document.getElementById('formEdit').action = '/update-kategori/' + kdkategori;
        }
        function openDeleteModal(kdkategori) {
            let modal = document.getElementById('modalHapus');
            modal.classList.add('show');
            document.getElementById('formHapus').action = '/hapus-kategori/' + kdkategori;
        }

        // === Checkbox pilih baris ===
        function getSelectedRows() {
            let selected = [];
            document.querySelectorAll(".rowCheckbox:checked").forEach(cb => {
                let row = cb.closest("tr");
                let cols = row.querySelectorAll("td");
                selected.push({
                    kdkategori: cols[1].innerText,
                    namakategori: cols[2].innerText,
                    hargapergr: cols[3].innerText,
                    kadar: cols[4].innerText
                });
            });
            return selected;
        }

        // === Export CSV ===
        function exportCSV() {
            let rows = getSelectedRows();
            if (rows.length === 0) return alert("Pilih data dulu!");
            let csv = "Kode, Nama, Harga/gr, Kadar\n";
            rows.forEach(r => {
                csv += `${r.kdkategori},${r.namakategori},${r.hargapergr},${r.kadar}\n`;
            });
            let blob = new Blob([csv], { type: "text/csv" });
            let link = document.createElement("a");
            link.href = URL.createObjectURL(blob);
            link.download = "kategori.csv";
            link.click();
        }

        // === Export Excel ===
        function exportExcel() {
            let rows = getSelectedRows();
            if (rows.length === 0) return alert("Pilih data dulu!");
            let table = `<table><tr><th>Kode</th><th>Nama</th><th>Harga/gr</th><th>Kadar</th></tr>`;
            rows.forEach(r => {
                table += `<tr><td>${r.kdkategori}</td><td>${r.namakategori}</td><td>${r.hargapergr}</td><td>${r.kadar}</td></tr>`;
            });
            table += `</table>`;
            let blob = new Blob([table], { type: "application/vnd.ms-excel" });
            let link = document.createElement("a");
            link.href = URL.createObjectURL(blob);
            link.download = "kategori.xls";
            link.click();
        }

        // === Export PDF (Surat Resmi) ===
        function exportPDF() {
            let rows = getSelectedRows();
            if (rows.length === 0) return alert("Pilih data dulu!");
            let tableRows = "";
            rows.forEach(r => {
                tableRows += `<tr>
                    <td>${r.kdkategori}</td>
                    <td>${r.namakategori}</td>
                    <td>${r.hargapergr}</td>
                    <td>${r.kadar}</td>
                </tr>`;
            });
            let surat = `
                <h2 style="text-align:center;">SURAT RESMI</h2>
                <p>Kepada Yth,</p>
                <p><b>Pimpinan Perusahaan</b></p>
                <p>di Tempat</p><br>
                <p>Dengan hormat,</p>
                <p>Bersama ini kami sampaikan daftar kategori barang:</p>
                <table border="1" cellspacing="0" cellpadding="5" width="100%">
                    <thead>
                        <tr><th>Kode</th><th>Nama</th><th>Harga/gr</th><th>Kadar</th></tr>
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
                        <title>Surat Resmi Kategori</title>
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

        // === Lain-lain ===
        function refreshPage() { location.reload(); }
        function sortTable() {
            let table = document.querySelector("#kategoriTable");
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

        // Select All
        document.getElementById("selectAll").addEventListener("change", function () {
            document.querySelectorAll(".rowCheckbox").forEach(cb => cb.checked = this.checked);
        });

        // Search
        document.querySelector("input[placeholder='Search']").addEventListener("keyup", function () {
            let value = this.value.toLowerCase();
            document.querySelectorAll("#kategoriTable tbody tr").forEach(row => {
                row.style.display = row.innerText.toLowerCase().includes(value) ? "" : "none";
            });
        });
    </script>


</body>

</html>