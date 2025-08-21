<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="{{ asset('css/datamaster/kategori.css') }}">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.5/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.1/css/buttons.dataTables.min.css">
    <link href="https://fonts.googleapis.com/css?family=Montserrat:400,800" rel="stylesheet">
    <title>Data Supplier</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>

<body>
    @include('partials.navbar')

    <h1>DATA SUPPLIER</h1>

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
                    <option value="print">Export Print</option>
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
                </div>
                <input type="text" placeholder="Search">
            </div>
        </div>

        <table id="supplierTable">
            <thead>
                <tr>
                    <th>Kode Supplier</th>
                    <th>Nama Supplier</th>
                    <th>Alamat</th>
                    <th>Kontak</th>
                    <th>Email</th>
                    <th>Keterangan</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($suppliers as $s)
                    <tr>
                        <td>{{ $s->kdsupplier }}</td>
                        <td>{{ $s->namasupplier }}</td>
                        <td>{{ $s->alamat }}</td>
                        <td>{{ $s->hp }}</td>
                        <td>{{ $s->email }}</td>
                        <td>{{ $s->ket }}</td>
                        <td>
                            <button class="btn-edit" onclick="openEditModal(
                                        '{{ $s->kdsupplier }}',
                                        '{{ $s->namasupplier }}',
                                        '{{ $s->alamat }}',
                                        '{{ $s->hp }}',
                                        '{{ $s->email }}',
                                        '{{ $s->ket }}'
                                    )"><i class="fa-solid fa-pen-to-square"></i></button>

                                <button class="fas fa-trash" onclick="openDeleteModal('{{ $s->kdsupplier }}')"></button>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>


    {{-- Modal Tambah --}}
    <div id="modalTambah" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeModal('modalTambah')">&times;</span>
            <h2>Tambah Supplier</h2>
            <form action="{{ route('supplier.store') }}" method="POST">
                @csrf
                <label>Kode Supplier</label>
                <input type="text" name="kdsupplier" required>

                <label>Nama Supplier</label>
                <input type="text" name="namasupplier">

                <label>Alamat</label>
                <textarea name="alamat"></textarea>

                <label>Kontak (HP)</label>
                <input type="text" name="hp">

                <label>Email</label>
                <input type="email" name="email">

                <label>Keterangan</label>
                <textarea name="ket"></textarea>

                <button type="submit" class="btn-primary">Simpan</button>
            </form>
        </div>
    </div>

    {{-- Modal Edit --}}
    <div id="modalEdit" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeModal('modalEdit')">&times;</span>
            <h2>Edit Supplier</h2>
            <form id="formEdit" method="POST">
                @csrf
                @method('PUT')

                <label>Kode Supplier</label>
                <input type="text" name="kdsupplier" id="editKdsupplier" required>

                <label>Nama Supplier</label>
                <input type="text" name="namasupplier" id="editNama">

                <label>Alamat</label>
                <textarea name="alamat" id="editAlamat"></textarea>

                <label>Kontak (HP)</label>
                <input type="text" name="hp" id="editHp">

                <label>Email</label>
                <input type="email" name="email" id="editEmail">

                <label>Keterangan</label>
                <textarea name="ket" id="editKet"></textarea>

                <button type="submit" class="btn-primary">Update</button>
            </form>
        </div>
    </div>

    {{-- Modal Hapus --}}
    <div id="modalHapus" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeModal('modalHapus')">&times;</span>
            <h2>Hapus Supplier</h2>
            <form id="formHapus" method="POST">
                @csrf
                @method('DELETE')
                <p>Apakah anda yakin ingin menghapus supplier ini?</p>
                <button type="submit" class="btn-primary">Ya, Hapus</button>
            </form>
        </div>
    </div>


    <script>
        function openModal(id) {
            document.getElementById(id).classList.add('show');
        }

        function closeModal(id) {
            document.getElementById(id).classList.remove('show');
        }

        function openEditModal(kdsupplier, nama, alamat, hp, email, ket) {
            let modal = document.getElementById('modalEdit');
            modal.classList.add('show');

            document.getElementById('formEdit').action = "/update-supplier/" + kdsupplier;

            document.getElementById('editKdsupplier').value = kdsupplier;
            document.getElementById('editNama').value = nama;
            document.getElementById('editAlamat').value = alamat;
            document.getElementById('editHp').value = hp;
            document.getElementById('editEmail').value = email;
            document.getElementById('editKet').value = ket;
        }

        function openDeleteModal(kdsupplier) {
            let modal = document.getElementById('modalHapus');
            modal.classList.add('show');
            document.getElementById('formHapus').action = "/hapus-supplier/" + kdsupplier;
        }

        // Search filter
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
            link.setAttribute("download", "kategori.csv");
            link.click();
        }

        // Fungsi export ke Excel (sederhana)
        function exportExcel() {
            let table = document.querySelector("table").outerHTML;
            let data = new Blob([table], { type: "application/vnd.ms-excel" });
            let link = document.createElement("a");
            link.href = URL.createObjectURL(data);
            link.download = "kategori.xls";
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