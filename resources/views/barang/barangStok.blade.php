<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="{{ asset('css/barang/databarang.css') }}">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.5/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.1/css/buttons.dataTables.min.css">
    <link href="https://fonts.googleapis.com/css?family=Montserrat:400,800" rel="stylesheet">
    <title>Barang Stok</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>

<body>
    @include('partials.navbar')

    <h1>Barang Stok</h1>

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
                <form method="GET" action="{{ route('barangStok') }}">
                    <label for="per_page">Tampilkan:</label>
                    <select name="per_page" id="per_page" onchange="this.form.submit()">
                        <option value="15" {{ $perPage == 15 ? 'selected' : '' }}>15</option>
                        <option value="30" {{ $perPage == 30 ? 'selected' : '' }}>30</option>
                        <option value="50" {{ $perPage == 50 ? 'selected' : '' }}>50</option>
                        <option value="{{ $barang->total() }}" {{ $perPage == $barang->total() ? 'selected' : '' }}>All
                        </option>
                    </select>
                </form>

                @if(session('typeuser') == 1)
                    <button class="btn-primary" onclick="openModal('modalTambah')">+ Tambah Barang</button>
                @endif
            </div>
            <div style="display:flex; align-items:center; gap:6px;">
                <div class="icon-group">
                    <button title="Sorting" onclick="sortTable()"><i class="fas fa-sort"></i></button>
                    <button title="Refresh" onclick="refreshPage()"><i class="fas fa-sync"></i></button>
                </div>
                <input type="text" placeholder="Search" id="searchInput">
            </div>
        </div>

        <table>
            <thead>
                <tr>
                    <th>Nama Barang</th>
                    <th>Kategori</th>
                    <th>Jenis</th>
                    <th>Baki</th>
                    <th>Stok</th>
                    <th>Foto</th>
                    <th>Barcode</th>
                    @if(session('typeuser') == 1)
                        <th>Aksi</th>
                    @endif
                </tr>
            </thead>
            <tbody>
                @foreach($barang as $b)
                    <tr>
                        <td>{{ $b->namabarang }}</td>
                        <td>{{ $b->KategoriBarang->namakategori ?? '-' }}</td>
                        <td>{{ $b->JenisBarang->namajenis ?? '-' }}</td>
                        <td>{{ $b->baki->namabaki ?? '-' }}</td>
                        <td>{{ $b->stok }}</td>
                        <td>
                            @if($b->photo_url)
                                <img src="{{ $b->photo_url }}" width="60" style="cursor:pointer;"
                                    onclick="showImage('{{ $b->photo_url }}','{{ $b->namabarang }}')">
                            @endif
                        </td>
                        <td>
                            @if($b->barcode_url)
                                <img src="{{ $b->barcode_url }}" width="80" style="cursor:pointer;"
                                    onclick="showImage('{{ $b->barcode_url }}','Kode: {{ $b->barcode }}')">
                                <div style="font-size:12px; text-align:center;">{{ $b->barcode }}</div>
                            @endif
                        </td>
                        @if(session('typeuser') == 1)
                            <td>
                                <button onclick="openEditModal(
                                                    '{{ $b->kdbarang }}',
                                                    '{{ $b->namabarang }}',
                                                    '{{ $b->kdjenis }}',
                                                    '{{ $b->kdbaki }}',
                                                    '{{ $b->kdkategori }}',
                                                    '{{ $b->kdtoko }}',
                                                    '{{ $b->berat }}',
                                                    '{{ $b->kadar }}',
                                                    '{{ $b->hargabeli }}',
                                                    '{{ $b->kdstatus }}',
                                                    '{{ $b->kdsupplier }}',
                                                    '{{ $b->atribut }}',
                                                    '{{ $b->hargaatribut }}',
                                                    '{{ $b->beratasli }}',
                                                    '{{ $b->beratbandrol }}',
                                                    '{{ $b->kdintern }}',
                                                    '{{ $b->stok }}',
                                                    '{{ $b->photo_type }}',
                                                    '{{ $b->camera_type }}'
                                                )">
                                    Edit
                                </button>

                                <button onclick="openDeleteModal('{{ $b->kdbarang }}')">Hapus</button>
                            </td>
                        @endif
                    </tr>
                @endforeach
            </tbody>
        </table>
        <!-- <div style="margin-top:10px;">
            {{ $barang->links() }}
        </div> -->
    </div>

    {{-- Modal Tambah --}}
    <div id="modalTambah" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeModal('modalTambah')">&times;</span>
            <h2>Tambah Barang</h2>
            <form action="{{ route('barang.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <label>Nama Barang</label>
                <input type="text" name="namabarang" required>

                <label>Kategori</label>
                <select name="kdkategori" required>
                    <option value="">-- Pilih Kategori --</option>
                    @foreach($kategori as $k)
                        <option value="{{ $k->kdkategori }}">{{ $k->namakategori }}</option>
                    @endforeach
                </select>

                <label>Jenis</label>
                <select name="kdjenis" required>
                    <option value="">-- Pilih Jenis --</option>
                    @foreach($jenis as $j)
                        <option value="{{ $j->kdjenis }}">{{ $j->namajenis }}</option>
                    @endforeach
                </select>

                <label>Baki</label>
                <select name="kdbaki" required>
                    <option value="">-- Pilih Baki --</option>
                    @foreach($baki as $b)
                        <option value="{{ $b->kdbaki }}">{{ $b->namabaki }}</option>
                    @endforeach
                </select>

                <label>Foto</label>
                <input type="file" name="photo">

                <label>Kode Toko</label required>
                <select name="kdtoko">
                    <option value="">-- Pilih toko --</option>
                    @foreach($toko as $t)
                        <option value="{{ $t->kdtoko }}">{{ $t->namatoko }}</option>
                    @endforeach
                </select>

                <label>Berat</label>
                <input type="number" step="0.001" name="berat">

                <label>Kadar</label>
                <input type="number" step="0.001" name="kadar">

                <label>Harga Beli</label>
                <input type="number" step="0.001" name="hargabeli">

                <label>Status</label>
                <select name="kdstatus">
                    <option value="">-- Pilih status --</option>
                    @foreach($status as $s)
                        <option value="{{ $s->kdstatus }}">{{ $s->status }}</option>
                    @endforeach
                </select>

                <label>Supplier</label>
                <select name="kdsupplier">
                    <option value="">-- Pilih supplier --</option>
                    @foreach($supplier as $su)
                        <option value="{{ $su->kdsupplier }}">{{ $su->namasupplier }}</option>
                    @endforeach
                </select>

                <label>Atribut</label>
                <input type="text" name="atribut">

                <label>Harga Atribut</label>
                <input type="number" step="0.001" name="hargaatribut">

                <label>Berat Asli</label>
                <input type="number" step="0.001" name="beratasli">

                <label>Berat Bandrol</label>
                <input type="number" step="0.001" name="beratbandrol">

                <label>Kode Intern</label>
                <select type="text" name="kdintern">
                    <option value="">-- Pilih intern --</option>
                    @foreach($intern as $int)
                        <option value="{{ $int->barcode }}">{{ $int->tipebarang }}</option>
                    @endforeach
                </select>

                <label>Quantity</label>
                <input type="number" name="stok">

                <label>Photo Type</label>
                <input type="text" name="photo_type">

                <label>Camera Type</label>
                <input type="text" name="camera_type">

                <button type="submit" class="btn-primary">Simpan</button>
            </form>
        </div>
    </div>

    {{-- Modal Edit --}}
    <div id="modalEdit" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeModal('modalEdit')">&times;</span>
            <h2>Edit Barang</h2>
            <form id="formEdit" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <label>Nama Barang</label>
                <input type="text" id="editNama" name="namabarang" required>

                <label>Kategori</label>
                <select id="editKategori" name="kdkategori" required>
                    <option value="">-- Pilih Kategori --</option>
                    @foreach($kategori as $k)
                        <option value="{{ $k->kdkategori }}">{{ $k->namakategori }}</option>
                    @endforeach
                </select>

                <label>Jenis</label>
                <select id="editJenis" name="kdjenis" required>
                    <option value="">-- Pilih Jenis --</option>
                    @foreach($jenis as $j)
                        <option value="{{ $j->kdjenis }}">{{ $j->namajenis }}</option>
                    @endforeach
                </select>

                <label>Baki</label>
                <select id="editBaki" name="kdbaki" required>
                    <option value="">-- Pilih Baki --</option>
                    @foreach($baki as $b)
                        <option value="{{ $b->kdbaki }}">{{ $b->namabaki }}</option>
                    @endforeach
                </select>

                <label>Foto</label>
                <input type="file" id="editPhoto" name="photo">

                <label>Kode Toko</label>
                <select id="editToko" name="kdtoko">
                    <option value="">-- Pilih toko --</option>
                    @foreach($toko as $t)
                        <option value="{{ $t->kdtoko }}">{{ $t->namatoko }}</option>
                    @endforeach
                </select>

                <label>Berat</label>
                <input type="number" id="editBerat" step="0.001" name="berat">

                <label>Kadar</label>
                <input type="number" id="editKadar" step="0.001" name="kadar">

                <label>Harga Beli</label>
                <input type="number" id="editHargaBeli" step="0.001" name="hargabeli">

                <label>Status</label>
                <select id="editStatus" name="kdstatus">
                    <option value="">-- Pilih status --</option>
                    @foreach($status as $s)
                        <option value="{{ $s->kdstatus }}">{{ $s->status }}</option>
                    @endforeach
                </select>

                <label>Supplier</label>
                <select id="editSupplier" name="kdsupplier">
                    <option value="">-- Pilih supplier --</option>
                    @foreach($supplier as $su)
                        <option value="{{ $su->kdsupplier }}">{{ $su->namasupplier }}</option>
                    @endforeach
                </select>

                <label>Atribut</label>
                <input type="text" id="editAtribut" name="atribut">

                <label>Harga Atribut</label>
                <input type="number" id="editHargaAtribut" step="0.001" name="hargaatribut">

                <label>Berat Asli</label>
                <input type="number" id="editBeratAsli" step="0.001" name="beratasli">

                <label>Berat Bandrol</label>
                <input type="number" id="editBeratBandrol" step="0.001" name="beratbandrol">

                <label>Kode Intern</label>
                <select id="editIntern" name="kdintern">
                    <option value="">-- Pilih intern --</option>
                    @foreach($intern as $int)
                        <option value="{{ $int->barcode }}">{{ $int->tipebarang }}</option>
                    @endforeach
                </select>

                <label>Quantity</label>
                <input type="number" id="editStok" name="stok">

                <label>Photo Type</label>
                <input type="text" id="editPhotoType" name="photo_type">

                <label>Camera Type</label>
                <input type="text" id="editCameraType" name="camera_type">

                <button type="submit" class="btn-primary">Update</button>
            </form>

        </div>
    </div>


    {{-- Modal Hapus --}}
    <div id="modalHapus" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeModal('modalHapus')">&times;</span>
            <h2>Hapus Barang</h2>
            <form id="formHapus" method="POST">
                @csrf
                @method('DELETE')
                <p>Apakah anda yakin ingin menghapus barang ini?</p>
                <button type="submit" class="btn-primary">Ya, Hapus</button>
            </form>
        </div>
    </div>

    {{-- Modal Preview Gambar --}}
    <div id="modalImage" class="modal">
        <div class="modal-content" style="max-width:600px; text-align:center;">
            <span class="close" onclick="closeModal('modalImage')">&times;</span>
            <img id="previewImage" src="" style="max-width:100%; border-radius:8px;">
            <p id="previewText" style="margin-top:8px; font-weight:bold;"></p>
        </div>
    </div>


    <script>

        function openModal(id) { document.getElementById(id).classList.add('show'); }
        function closeModal(id) { document.getElementById(id).classList.remove('show'); }

        function openEditModal(id, nama, jenis, baki, kategori, toko, berat, kadar, hargabeli, status, supplier, atribut, hargaatribut, beratasli, beratbandrol, kdintern, stok, photo_type, camera_type) {
            document.getElementById('modalEdit').classList.add('show');
            document.getElementById('formEdit').action = '/StokBarang/' + id;

            // Isi field
            document.getElementById('editNama').value = nama;
            document.getElementById('editJenis').value = jenis;
            document.getElementById('editBaki').value = baki;
            document.getElementById('editKategori').value = kategori;
            document.getElementById('editToko').value = toko || '';
            document.getElementById('editBerat').value = berat || '';
            document.getElementById('editKadar').value = kadar || '';
            document.getElementById('editHargaBeli').value = hargabeli || '';
            document.getElementById('editStatus').value = status || '';
            document.getElementById('editSupplier').value = supplier || '';
            document.getElementById('editAtribut').value = atribut || '';
            document.getElementById('editHargaAtribut').value = hargaatribut || '';
            document.getElementById('editBeratAsli').value = beratasli || '';
            document.getElementById('editBeratBandrol').value = beratbandrol || '';
            document.getElementById('editIntern').value = kdintern || '';
            document.getElementById('editStok').value = stok || '';
            document.getElementById('editPhotoType').value = photo_type || '';
            document.getElementById('editCameraType').value = camera_type || '';
        }

        function openDeleteModal(id) {
            document.getElementById('modalHapus').classList.add('show');
            document.getElementById('formHapus').action = '/StokBarang/' + id;
        }

        // Search filter
        document.getElementById("searchInput").addEventListener("keyup", function () {
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

        function showImage(src, text) {
            document.getElementById('modalImage').classList.add('show');
            document.getElementById('previewImage').src = src;
            document.getElementById('previewText').innerText = text || '';
        }
    </script>
</body>

</html>