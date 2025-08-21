<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Daftar Baki</title>
    <link rel="stylesheet" href="{{ asset('css/datamaster/bakibarang.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.3.6/css/buttons.dataTables.min.css">

</head>
<body>
@include('partials.navbar')

<h1>DAFTAR BAKI</h1>

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
                <button class="btn-primary" onclick="openModal('modalTambah')">+ Tambah Baki</button>
            </div>
            <div style="display:flex; align-items:center; gap:6px;">
                <div class="icon-group">
                    <button title="Sorting" onclick="sortTable()"><i class="fas fa-sort"></i></button>
                    <button title="Refresh" onclick="refreshPage()"><i class="fas fa-sync"></i></button>
                </div>
                <input type="text" placeholder="Search">
            </div>
        </div>

    <table id="bakiTable" class="display" style="width:100%">
        <thead>
            <tr>
                <th><input type="checkbox"></th>
                <th>Kode Baki</th>
                <th>Nama Baki</th>
                <th>Kode Kategori</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            @foreach($baki as $item)
            <tr>
                <td><input type="checkbox"></td>
                <td>{{ $item->kdbaki }}</td>
                <td>{{ $item->namabaki }}</td>
                <td>{{ $item->kdkategori }}</td>
                <td>
                    <button class="action-btn"><i class="fa-solid fa-pen-to-square"></i></button>
                    <form action="{{ route('bakibarang.destroy', $item->kdbaki) }}" method="POST" style="display:inline;">
                        @csrf
                        @method('DELETE')
                        <button class="action-btn" onclick="return confirm('Yakin hapus?')"><i class="fas fa-trash"></i></button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>



<!-- Form Tambah Data -->
    <div id="form-tambah" style="display:none; margin-top:20px;">
        <form method="POST" action="{{ route('bakibarang.store') }}">
            @csrf
            <label>Kode Baki</label>
            <input type="text" name="kdbaki" required>
            <label>Nama Baki</label>
            <input type="text" name="namabaki" required>
            <label>Kode Kategori</label>
            <input type="text" name="kdkategori">
            <button type="submit" class="btn-primary">Simpan</button>
        </form>
    </div>


<!-- Modal Edit -->
    <div id="modalEdit" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeModal('modalEdit')">&times;</span>
            <h2>Edit Baki</h2>
            <form id="formEdit" method="POST">
                @csrf
                @method('PUT')
                <label>Kode Baki</label>
                <input type="text" name="kdbaki" id="editkdbaki" required>
                <label>Nama Baki</label>
                <input type="text" name="namabaki" id="editNamaBaki">
                <button type="submit">Update</button>
            </form>
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

    <script>
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
    </script>
</body>
</html>
