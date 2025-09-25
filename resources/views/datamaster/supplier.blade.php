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
                    <option value="pdf">Export PDF</option>
                    <option value="csv">Export CSV</option>
                    <option value="excel">Export Excel</option>
                </select>
                <button class="btn-primary" onclick="openModal('modalTambah')">+ Tambah Supplier</button>
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
                    <th><input type="checkbox" id="selectAll"></th>
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
                        <td><input type="checkbox" class="rowCheckbox"></td>
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
        function openModal(id) { document.getElementById(id).classList.add('show'); }
        function closeModal(id) { document.getElementById(id).classList.remove('show'); }

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
            document.querySelectorAll("#supplierTable tbody tr").forEach(function (row) {
                row.style.display = row.innerText.toLowerCase().includes(value) ? "" : "none";
            });
        });

        // Ambil baris yang dicentang
        function getSelectedRows() {
            let selected = [];
            document.querySelectorAll(".rowCheckbox:checked").forEach(cb => {
                let row = cb.closest("tr");
                let cols = row.querySelectorAll("td");
                selected.push({
                    kdsupplier: cols[1].innerText,
                    namasupplier: cols[2].innerText,
                    alamat: cols[3].innerText,
                    hp: cols[4].innerText,
                    email: cols[5].innerText,
                    ket: cols[6].innerText
                });
            });
            return selected;
        }

        // Export CSV
        function exportCSV() {
            let rows = getSelectedRows();
            if (rows.length === 0) return alert("Pilih data dulu!");

            let csv = "Kode Supplier,Nama Supplier,Alamat,Kontak,Email,Keterangan\n";
            rows.forEach(r => {
                csv += `${r.kdsupplier},${r.namasupplier},${r.alamat},${r.hp},${r.email},${r.ket}\n`;
            });

            let blob = new Blob([csv], { type: "text/csv" });
            let link = document.createElement("a");
            link.href = URL.createObjectURL(blob);
            link.download = "supplier.csv";
            link.click();
        }

        // Export Excel
        function exportExcel() {
            let rows = getSelectedRows();
            if (rows.length === 0) return alert("Pilih data dulu!");

            let table = `<table><tr><th>Kode Supplier</th><th>Nama Supplier</th><th>Alamat</th><th>Kontak</th><th>Email</th><th>Keterangan</th></tr>`;
            rows.forEach(r => {
                table += `<tr><td>${r.kdsupplier}</td><td>${r.namasupplier}</td><td>${r.alamat}</td><td>${r.hp}</td><td>${r.email}</td><td>${r.ket}</td></tr>`;
            });
            table += `</table>`;

            let blob = new Blob([table], { type: "application/vnd.ms-excel" });
            let link = document.createElement("a");
            link.href = URL.createObjectURL(blob);
            link.download = "supplier.xls";
            link.click();
        }

        // Export PDF (surat resmi)
        function exportPDF() {
            let rows = getSelectedRows();
            if (rows.length === 0) return alert("Pilih data dulu!");

            let tableRows = "";
            rows.forEach(r => {
                tableRows += `
                    <tr>
                        <td>${r.kdsupplier}</td>
                        <td>${r.namasupplier}</td>
                        <td>${r.alamat}</td>
                        <td>${r.hp}</td>
                        <td>${r.email}</td>
                        <td>${r.ket}</td>
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
                <p>Bersama ini kami sampaikan daftar supplier yang ada dalam sistem:</p>

                <table border="1" cellspacing="0" cellpadding="5" width="100%">
                    <thead>
                        <tr>
                            <th>Kode</th>
                            <th>Nama</th>
                            <th>Alamat</th>
                            <th>Kontak</th>
                            <th>Email</th>
                            <th>Keterangan</th>
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
                        <title>Surat Resmi Supplier</title>
                        <style>
                            body { font-family: Arial, sans-serif; margin: 20px; }
                            table { border-collapse: collapse; width: 100%; }
                            th, td { border: 1px solid #000; padding: 6px; text-align: left; }
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

        function refreshPage() { location.reload(); }
        function sortTable() {
            let table = document.querySelector("#supplierTable");
            let rows = Array.from(table.rows).slice(1);
            rows.sort((a, b) => a.cells[1].innerText.localeCompare(b.cells[1].innerText));
            rows.forEach(row => table.appendChild(row));
        }

        function handleExport(value) {
            if (value === "pdf") exportPDF();
            if (value === "csv") exportCSV();
            if (value === "excel") exportExcel();
        }

        // Select All
        document.getElementById("selectAll").addEventListener("change", function () {
            document.querySelectorAll(".rowCheckbox").forEach(cb => cb.checked = this.checked);
        });
    </script>

</body>

</html>