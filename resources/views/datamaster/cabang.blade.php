{{-- resources/views/datamaster/cabang.blade.php --}}
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="{{ asset('css/datamaster/kategori.css') }}">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.5/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.1/css/buttons.dataTables.min.css">
    <link href="https://fonts.googleapis.com/css?family=Montserrat:400,800" rel="stylesheet">
    <title>Data Cabang</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>

<body>
    @include('partials.navbar')

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

        <center>
            <h2>DATA CABANG</h2>
        </center>

        <div class="top-bar">
            <div class="left-controls">
                <select onchange="handleExport(this.value)">
                    <option value="">Pilih Export</option>
                    <option value="pdf">Export PDF</option>
                    <option value="csv">Export CSV</option>
                    <option value="excel">Export Excel</option>
                </select>
                <button class="btn-primary" onclick="openModal('modalTambah')">+ Tambah Cabang</button>
            </div>
            <div style="display:flex; align-items:center; gap:6px;">
                <div class="icon-group">
                    <button title="Sorting" onclick="sortTable()"><i class="fas fa-sort"></i></button>
                    <button title="Refresh" onclick="refreshPage()"><i class="fas fa-sync"></i></button>
                </div>
                <input type="text" placeholder="Search">
            </div>
        </div>

        <table id="cabangTable">
            <thead>
                <tr>
                    <th>Kode Cabang</th>
                    <th>Nama Cabang</th>
                    <th>Alamat</th>
                    <th>Area</th>
                    <th>Logo</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($tokos as $toko)
                    <tr>
                        <td>{{ $toko->kdtoko }}</td>
                        <td>{{ $toko->namatoko }}</td>
                        <td>{{ $toko->alamattoko }}</td>
                        <td>{{ $toko->area }}</td>
                        <td>
                            @if($toko->logo)
                                <img src="{{ asset('storage/cabang_foto/' . $toko->logo) }}" alt="Logo" width="50">
                            @endif
                        </td>
                        <td>
                            <button class="btn-edit" onclick="openEditModal(
                                    '{{ $toko->kdtoko }}',
                                    '{{ $toko->namatoko }}',
                                    '{{ $toko->alamattoko }}',
                                    '{{ $toko->area }}',
                                    '{{ $toko->logo }}'
                                )">Edit</button>

                            <button class="btn-delete" onclick="openDeleteModal('{{ $toko->kdtoko }}')">Hapus</button>
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
            <h2>Tambah Cabang</h2>
            <form action="{{ route('store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <label>Kode Cabang</label>
                <input type="text" name="kdtoko" required>

                <label>Nama Cabang</label>
                <input type="text" name="namatoko">

                <label>Alamat</label>
                <input type="text" name="alamattoko">

                <label>Area</label>
                <input type="text" name="area">

                <label>Logo</label>
                <input type="file" name="logo">

                <button type="submit" class="btn-primary">Simpan</button>
            </form>
        </div>
    </div>

    {{-- Modal Edit --}}
    <div id="modalEdit" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeModal('modalEdit')">&times;</span>
            <h2>Edit Cabang</h2>
            <form id="formEdit" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <label>Kode Cabang</label>
                <input type="text" name="kdtoko" id="editKdtoko" required>

                <label>Nama Cabang</label>
                <input type="text" name="namatoko" id="editNama">

                <label>Alamat</label>
                <input type="text" name="alamattoko" id="editAlamat">

                <label>Area</label>
                <input type="text" name="area" id="editArea">

                <label>Logo</label>
                <input type="file" name="logo" id="editLogo">
                <div id="previewLogo"></div>

                <button type="submit" class="btn-primary">Update</button>
            </form>
        </div>
    </div>

    {{-- Modal Hapus --}}
    <div id="modalHapus" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeModal('modalHapus')">&times;</span>
            <h2>Hapus Cabang</h2>
            <form id="formHapus" method="POST">
                @csrf
                @method('DELETE')
                <p>Apakah anda yakin ingin menghapus cabang ini?</p>
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

        function openEditModal(kdtoko, nama, alamat, area, logo) {
            let modal = document.getElementById('modalEdit');
            modal.classList.add('show');

            document.getElementById('formEdit').action = "/update-cabang/" + kdtoko;
            document.getElementById('editKdtoko').value = kdtoko;
            document.getElementById('editNama').value = nama;
            document.getElementById('editAlamat').value = alamat;
            document.getElementById('editArea').value = area;

            let preview = document.getElementById('previewLogo');
            preview.innerHTML = logo ? `<img src="/storage/cabang_foto/${logo}" width="80">` : '';
        }

        function openDeleteModal(kdtoko) {
            let modal = document.getElementById('modalHapus');
            modal.classList.add('show');
            document.getElementById('formHapus').action = "/hapus-cabang/" + kdtoko;
        }

        // Search filter
        document.querySelector("input[placeholder='Search']").addEventListener("keyup", function () {
            let value = this.value.toLowerCase();
            document.querySelectorAll("#cabangTable tbody tr").forEach(function (row) {
                row.style.display = row.innerText.toLowerCase().includes(value) ? "" : "none";
            });
        });

        // Kolom yang ingin diexport (0=Kode, 1=Nama, 2=Alamat, 3=Area)
        const exportColumns = [0, 1, 2, 3];

        function exportCSV() {
            let table = document.querySelector("#cabangTable");
            let rows = table.querySelectorAll("tr");
            let csv = [];

            rows.forEach(row => {
                let cols = row.querySelectorAll("td, th");
                let rowData = [];
                exportColumns.forEach(i => rowData.push(cols[i]?.innerText || ""));
                csv.push(rowData.join(","));
            });

            let csvContent = "data:text/csv;charset=utf-8," + csv.join("\n");
            let link = document.createElement("a");
            link.setAttribute("href", csvContent);
            link.setAttribute("download", "cabang.csv");
            link.click();
        }

        function exportExcel() {
            let table = document.querySelector("#cabangTable");
            let rows = table.querySelectorAll("tr");
            let html = "<table border='1'><tr>";

            rows.forEach(row => {
                let cols = row.querySelectorAll("td, th");
                html += "<tr>";
                exportColumns.forEach(i => {
                    html += "<td>" + (cols[i]?.innerText || "") + "</td>";
                });
                html += "</tr>";
            });

            html += "</table>";

            let data = new Blob([html], { type: "application/vnd.ms-excel" });
            let link = document.createElement("a");
            link.href = URL.createObjectURL(data);
            link.download = "cabang.xls";
            link.click();
        }

        function exportPDF() {
            let table = document.querySelector("#cabangTable");
            let rows = table.querySelectorAll("tr");
            let printWindow = window.open("", "", "height=600,width=800");

            printWindow.document.write("<html><head><title>Export PDF</title></head><body>");
            printWindow.document.write("<h2>DATA CABANG</h2>");
            printWindow.document.write("<table border='1' style='border-collapse: collapse; width:100%'><tr>");

            rows.forEach(row => {
                let cols = row.querySelectorAll("td, th");
                printWindow.document.write("<tr>");
                exportColumns.forEach(i => {
                    printWindow.document.write("<td style='padding:5px'>" + (cols[i]?.innerText || "") + "</td>");
                });
                printWindow.document.write("</tr>");
            });

            printWindow.document.write("</table></body></html>");
            printWindow.document.close();
            printWindow.print();
        }


        function refreshPage() {
            location.reload();
        }

        function sortTable() {
            let table = document.querySelector("#cabangTable");
            let rows = Array.from(table.rows).slice(1);
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