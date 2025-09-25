
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

    <h1>DATA CABANG</h1>

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
                    <th><input type="checkbox" id="selectAll"></th>
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
                        <td><input type="checkbox" class="rowCheckbox"></td>
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
                            <button class="fa-solid fa-pen-to-square" onclick="openEditModal(
                                                            '{{ $toko->kdtoko }}',
                                                            '{{ $toko->namatoko }}',
                                                            '{{ $toko->alamattoko }}',
                                                            '{{ $toko->area }}',
                                                            '{{ $toko->logo }}'
                                                        )"></button>

                            <button class="fas fa-trash" onclick="openDeleteModal('{{ $toko->kdtoko }}')"></button>
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
            let rows = getSelectedRows();
            if (rows.length === 0) return alert("Pilih data dulu!");

            let csv = "Kode Cabang,Nama Cabang,Alamat,Area\n";
            rows.forEach(r => {
                csv += `${r.kdtoko},${r.namatoko},${r.alamattoko},${r.area}\n`;
            });

            let blob = new Blob([csv], { type: "text/csv" });
            let link = document.createElement("a");
            link.href = URL.createObjectURL(blob);
            link.download = "cabang.csv";
            link.click();
        }

        function exportExcel() {
            let rows = getSelectedRows();
            if (rows.length === 0) return alert("Pilih data dulu!");

            let table = `<table><tr><th>Kode Cabang</th><th>Nama Cabang</th><th>Alamat</th><th>Area</th></tr>`;
            rows.forEach(r => {
                table += `<tr><td>${r.kdtoko}</td><td>${r.namatoko}</td><td>${r.alamattoko}</td><td>${r.area}</td></tr>`;
            });
            table += `</table>`;

            let blob = new Blob([table], { type: "application/vnd.ms-excel" });
            let link = document.createElement("a");
            link.href = URL.createObjectURL(blob);
            link.download = "cabang.xls";
            link.click();
        }

        function exportPDF() {
            let rows = getSelectedRows();
            if (rows.length === 0) return alert("Pilih data dulu!");

            // Buat konten surat resmi hanya dari data yang dipilih
            let tableRows = "";
            rows.forEach(r => {
                tableRows += `
                <tr>
                    <td>${r.kdtoko}</td>
                    <td>${r.namatoko}</td>
                    <td>${r.alamattoko}</td>
                    <td>${r.area}</td>
                </tr>
            `;
            });

            let surat = `
            <h2 style="text-align:center;">SURAT RESMI</h2>
            <p>Kepada Yth,</p>
            <p><b>Pimpinan Perusahaan</b></p>
            <p>di Tempat</p>

            <br>
            <p>Dengan hormat,</p>
            <p>Bersama ini kami sampaikan daftar cabang yang ada dalam sistem:</p>

            <table border="1" cellspacing="0" cellpadding="5" width="100%">
                <thead>
                    <tr>
                        <th>Kode Cabang</th>
                        <th>Nama Cabang</th>
                        <th>Alamat</th>
                        <th>Area</th>
                    </tr>
                </thead>
                <tbody>
                    ${tableRows}
                </tbody>
            </table>

            <br><br>
            <p>Hormat kami,</p>
            <br><br>
            <p><b>(................................)</b></p>
        `;

            let win = window.open("", "", "width=800,height=600");
            win.document.write(`
            <html>
                <head>
                    <title>Surat Resmi Cabang</title>
                    <style>
                        body { font-family: Arial, sans-serif; margin: 20px; }
                        table { border-collapse: collapse; width: 100%; }
                        th, td { border: 1px solid #000; padding: 6px; text-align: left; }
                        h2 { text-align: center; }
                    </style>
                </head>
                <body>
                    ${surat}
                </body>
            </html>
        `);
            win.document.close();
            win.focus();
            win.print();
            win.close();
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
            if (value === "pdf") exportPDF();
            if (value === "csv") exportCSV();
            if (value === "excel") exportExcel();
        }

        // Ambil baris yang dicentang
        function getSelectedRows() {
            let selected = [];
            document.querySelectorAll(".rowCheckbox:checked").forEach(cb => {
                let row = cb.closest("tr");
                let cols = row.querySelectorAll("td");
                selected.push({
                    kdtoko: cols[1].innerText,
                    namatoko: cols[2].innerText,
                    alamattoko: cols[3].innerText,
                    area: cols[4].innerText
                });
            });
            return selected;
        }

        // Select All
        document.getElementById("selectAll").addEventListener("change", function () {
            let checkboxes = document.querySelectorAll(".rowCheckbox");
            checkboxes.forEach(cb => cb.checked = this.checked);
        });
    </script>
</body>

</html>