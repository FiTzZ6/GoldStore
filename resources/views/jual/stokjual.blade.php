<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="{{ asset('css/jual/stokjual.css') }}">
    <link href="https://fonts.googleapis.com/css?family=Montserrat:400,800" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" />
    <title>Stok-jual</title>

    <!-- DataTables CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.2/css/buttons.dataTables.min.css">

    <!-- Select2 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

    <!-- jQuery + Select2 JS -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <script src="https://kit.fontawesome.com/a076d05399.js"></script>
</head>

<body>
    @include('partials.navbar') 
    <h1 class="page-title">Stok Barang Jual</h1>


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

                <button class="btn-primary" onclick="openModal('modalTambah')">+ Tambah Barang yang Ingin di
                    jual</button>
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

        <table id="tabelStokJual" class="display">
            <thead>
                <tr>
                    <th>NoFaktur</th>
                    <th>Barcode</th>
                    <th>Nama Barang</th>
                    <th>Berat</th>
                    <th>Kadar</th>
                    <th>Harga Beli</th>
                    <th>Harga Jual</th>
                    <th>Ongkos</th>
                    <th>Stok</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($stokjual as $row)
                    <tr>
                        <td>{{ $row->nofaktur }}</td>
                        <td>{{ $row->barcode }}</td>
                        <td>{{ $row->namabarang }}</td>
                        <td>{{ $row->berat }}</td>
                        <td>{{ $row->kadar }}</td>
                        <td>{{ $row->hargabeli }}</td>
                        <td>{{ $row->hargajual }}</td>
                        <td>{{ $row->ongkos }}</td>
                        <td>{{ $row->stok }}</td>
                        <td>
                            <!-- Tombol Edit -->
                            <button class="action-btn" onclick="openEdit(
                                                                                                            '{{ $row->nofaktur }}',
                                                                                                            '{{ $row->barcode }}',
                                                                                                            '{{ $row->namabarang }}',
                                                                                                            '{{ $row->berat }}',
                                                                                                            '{{ $row->kadar }}',
                                                                                                            '{{ $row->hargabeli }}',
                                                                                                            '{{ $row->hargajual }}',
                                                                                                            '{{ $row->ongkos }}',
                                                                                                            '{{ $row->stok }}'
                                                                                                        )">
                                <i class="fa-solid fa-pen-to-square"></i>
                            </button>

                            <!-- Tombol Hapus -->
                            <button class="action-btn" onclick="openDelete('{{ $row->nofaktur }}')">
                                <i class="fas fa-trash"></i>
                            </button>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <!-- Modal Tambah -->
        <!-- Modal Tambah -->
        <div id="modalTambah" class="modal" style="display:none;">
            <div class="modal-content">
                <span class="close" onclick="closeModal('modalTambah')">&times;</span>
                <h2>Tambah Stok Jual</h2>
                <form action="{{ route('stokjual.store') }}" method="POST">
                    @csrf

                    <label for="barangSelect">Pilih Barang (Barcode / Nama)</label>
                    <select id="barangSelect" name="barcode" style="width: 100%" required>
                        <option value="">-- Pilih Barang --</option>
                        @foreach($barang as $b)
                            <option value="{{ $b->barcode }}" data-namabarang="{{ $b->namabarang }}"
                                data-berat="{{ $b->berat }}" data-kadar="{{ $b->kadar }}"
                                data-hargabeli="{{ $b->hargabeli }}" data-hargajual="{{ $b->hargajual ?? '' }}"
                                data-ongkos="{{ $b->ongkos ?? '' }}" data-stok="{{ $b->stok }}">
                                {{ $b->barcode }} - {{ $b->namabarang }}
                            </option>
                        @endforeach
                    </select>


                    <label>Nama Barang</label>
                    <input type="text" name="namabarang" required>

                    <label>Berat</label>
                    <input type="number" step="0.01" name="berat" required>

                    <label>Kadar</label>
                    <input type="text" name="kadar" required>

                    <label>Harga Beli</label>
                    <input type="number" name="hargabeli" required>

                    <label>Harga Jual</label>
                    <input type="number" name="hargajual" required>

                    <label>Ongkos</label>
                    <input type="number" name="ongkos">

                    <label>Stok</label>
                    <input type="number" name="stok" required>

                    <button type="submit">Simpan</button>
                </form>
            </div>
        </div>

        <!-- ✅ Modal Edit -->
        <div id="modalEdit" class="modal">
            <div class="modal-content">
                <span class="close" onclick="closeModal('modalEdit')">&times;</span>
                <h2>Edit Stok Jual</h2>
                <form id="formEdit" method="POST">
                    @csrf
                    @method('PUT')

                    <label>Barcode</label>
                    <input type="text" name="barcode" id="edit_barcode" required>

                    <label>Nama Barang</label>
                    <input type="text" name="namabarang" id="edit_namabarang" required>

                    <label>Berat</label>
                    <input type="number" step="0.01" name="berat" id="edit_berat" required>

                    <label>Kadar</label>
                    <input type="text" name="kadar" id="edit_kadar" required>

                    <label>Harga Beli</label>
                    <input type="number" name="hargabeli" id="edit_hargabeli" required>

                    <label>Harga Jual</label>
                    <input type="number" name="hargajual" id="edit_hargajual" required>

                    <label>Ongkos</label>
                    <input type="number" name="ongkos" id="edit_ongkos">

                    <label>Stok</label>
                    <input type="number" name="stok" id="edit_stok" required>

                    <button type="submit">Update</button>
                </form>
            </div>
        </div>

        <!-- ✅ Modal Hapus -->
        <div id="modalHapus" class="modal">
            <div class="modal-content">
                <span class="close" onclick="closeModal('modalHapus')">&times;</span>
                <h2>Hapus Stok Jual</h2>
                <p>Yakin ingin menghapus data ini?</p>
                <form id="formHapus" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="submit">Hapus</button>
                </form>
            </div>
        </div>


    </div>

    <script>

        function openModal(id) {
            document.getElementById(id).style.display = "block";

            // Inisialisasi Select2 setelah modal muncul
            if (!$('#barangSelect').hasClass("select2-hidden-accessible")) {
                $('#barangSelect').select2({
                    placeholder: "Cari barcode / nama barang...",
                    allowClear: true,
                    dropdownParent: $('#' + id)
                });
            }

            // Event onchange untuk isi otomatis field
            $('#barangSelect').on('change', function () {
                let selected = $(this).find(':selected');

                $("input[name='namabarang']").val(selected.data('namabarang') || '');
                $("input[name='berat']").val(selected.data('berat') || '');
                $("input[name='kadar']").val(selected.data('kadar') || '');
                $("input[name='hargabeli']").val(selected.data('hargabeli') || '');
                $("input[name='hargajual']").val(selected.data('hargajual') || '');
                $("input[name='ongkos']").val(selected.data('ongkos') || '');
                $("input[name='stok']").val(selected.data('stok') || '');
            });
        }

        function closeModal(id) {
            document.getElementById(id).style.display = "none";

            // Reset select2 dan field
            $('#barangSelect').val(null).trigger('change');
            $("input[name='namabarang']").val('');
            $("input[name='berat']").val('');
            $("input[name='kadar']").val('');
            $("input[name='hargabeli']").val('');
            $("input[name='hargajual']").val('');
            $("input[name='ongkos']").val('');
            $("input[name='stok']").val('');
        }

        // ✅ Edit Modal
        function openEdit(nofaktur, barcode, namabarang, berat, kadar, hargabeli, hargajual, ongkos, stok) {
            document.getElementById("formEdit").action = "/stokjual/" + nofaktur;
            document.getElementById("edit_barcode").value = barcode;
            document.getElementById("edit_namabarang").value = namabarang;
            document.getElementById("edit_berat").value = berat;
            document.getElementById("edit_kadar").value = kadar;
            document.getElementById("edit_hargabeli").value = hargabeli;
            document.getElementById("edit_hargajual").value = hargajual;
            document.getElementById("edit_ongkos").value = ongkos;
            document.getElementById("edit_stok").value = stok;
            openModal("modalEdit");
        }

        // ✅ Delete Modal
        function openDelete(nofaktur) {
            document.getElementById("formHapus").action = "/stokjual/" + nofaktur;
            openModal("modalHapus");
        }

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

        document.querySelector("input[placeholder='Search']").addEventListener("keyup", function () {
            let value = this.value.toLowerCase();
            document.querySelectorAll("table tbody tr").forEach(function (row) {
                row.style.display = row.innerText.toLowerCase().includes(value) ? "" : "none";
            });
        });

    </script>

</body>

</html>