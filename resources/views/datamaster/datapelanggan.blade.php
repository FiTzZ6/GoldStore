<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="{{ asset('css/datamaster/cabang.css') }}">
    <link href="https://fonts.googleapis.com/css?family=Montserrat:400,800" rel="stylesheet">
    <script src="https://kit.fontawesome.com/a076d05399.js"></script>
    <title>Halaman Area</title>
</head>
<body>
@include('partials.navbar')

    <h1 class="page-title">DATA PELANGGAN</h1>

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
            <button class="btn-tb" onclick="openModal('modalTambah')">+ Tambah Pelanggan</button>
            <button class="btn-pm"><i class="fas fa-cogs"></i> Pengaturan Member</button>
        </div>
        <div style="display:flex; align-items:center; gap:6px;">
            <div class="icon-group">
                <button title="Sorting" onclick="sortTable()"><i class="fas fa-sort"></i></button>
                <button title="Refresh" onclick="refreshPage()"><i class="fas fa-sync"></i></button>
            </div>
            <input type="text" placeholder="Search">
        </div>
    </div>

    <table>
        <thead>
            <tr>
                <th><input type="checkbox"></th>
                <th>Kode</th>
                <th>Nama</th>
                <th>Alamat</th>
                <th>Kontak</th>
                <th>Point</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
        @foreach($pelanggan as $p)
            <tr>
                <td><input type="checkbox"></td>
                <td>{{ $p->kdpelanggan }}</td>
                <td>{{ $p->namapelanggan }}</td>
                <td>{{ $p->alamatpelanggan }}</td>
                <td>{{ $p->notelp }}</td>
                <td>{{ $p->poin }}</td>
                <td>
                    <!-- Edit -->
                    <button type="button" class="action-btn"
                        onclick="openEditModal('{{ $p->id }}','{{ $p->kdpelanggan }}','{{ $p->namapelanggan }}','{{ $p->alamatpelanggan }}','{{ $p->notelp }}','{{ $p->poin }}')">
                        <i class="fa-solid fa-pen-to-square"></i>
                    </button>

                    <button class="action-btn"><i class="fa-solid fa-file-export"></i></button> 
                    <button class="action-btn"><i class="fa-solid fa-file"></i></button>

                    <!-- Delete -->
                    <form action="{{ route('pelanggan.destroy', $p->id) }}" method="POST" style="display:inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="action-btn" onclick="return confirm('Hapus pelanggan ini?')">
                            <i class="fas fa-trash"></i>
                        </button>
                    </form>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
    <div id="modalTambah" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeModal('modalTambah')">&times;</span>
            <h2>Tambah Pelanggan</h2>
            <form action="{{ route('pelanggan.store') }}" method="POST">
                @csrf
                <label>Kode</label>
                <input type="text" name="kdpelanggan" required>
                <label>Nama</label>
                <input type="text" name="namapelanggan" required>
                <label>Alamat</label>
                <input type="text" name="alamatpelanggan">
                <label>Kontak</label>
                <input type="text" name="notelp">
                <button type="submit" class="btn-tb">Simpan</button>
            </form>
        </div>
    </div>

    <div id="modalEdit" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeModal('modalEdit')">&times;</span>
            <h2>Edit Pelanggan</h2>
            <form id="formEdit" method="POST">
                @csrf
                @method('PUT')

                <label>Kode Pelanggan</label>
                <input type="text" name="kdpelanggan" id="edit-kd" readonly>

                <label>Nama</label>
                <input type="text" name="namapelanggan" id="edit-nama" required>

                <label>Alamat</label>
                <input type="text" name="alamatpelanggan" id="edit-alamat">

                <label>Kontak</label>
                <input type="text" name="notelp" id="edit-telp">

                <label>Poin</label>
                <input type="number" name="poin" id="edit-poin">

                <button type="submit" class="btn-tb">Simpan</button>
            </form>
        </div>
    </div>
</div>
<script>
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
        link.setAttribute("download", "data_pelanggan.csv");
        link.click();
        }

        // Fungsi export ke Excel (sederhana)
    function exportExcel() {
        let table = document.querySelector("table").outerHTML;
        let data = new Blob([table], { type: "application/vnd.ms-excel" });
        let link = document.createElement("a");
        link.href = URL.createObjectURL(data);
        link.download = "data_pelanggan.xls";
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

    function openEditModal(id, kd, nama, alamat, telp, poin) {
        // set action form
        document.getElementById('formEdit').action = "/pelanggan/" + id;

        // isi field modal
        document.getElementById('edit-kd').value = kd;
        document.getElementById('edit-nama').value = nama;
        document.getElementById('edit-alamat').value = alamat;
        document.getElementById('edit-telp').value = telp;
        document.getElementById('edit-poin').value = poin;

        // tampilkan modal (pakai style custom)
        openModal('modalEdit');
    }

    function openModal(id) {
        document.getElementById(id).classList.add('show');
    }
    function closeModal(id) {
        document.getElementById(id).classList.remove('show');
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