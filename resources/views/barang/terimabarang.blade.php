<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Terima Barang</title>
    <link rel="stylesheet" href="{{ asset('css/barang/terimabarang.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>

<body>
    @include('partials.navbar')

    <h1 class="page-title">Terima Barang</h1>

    <div class="container">

        {{-- Alert success/error --}}
        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        @if (session('error'))
            <div class="alert alert-error">{{ session('error') }}</div>
        @endif

        <div class="top-bar">
            <div class="left-controls">
                <select onchange="handleExport(this.value)">
                    <option value="">Pilih Export</option>
                    <option value="print">Print</option>
                    <option value="pdf">PDF</option>
                    <option value="csv">CSV</option>
                    <option value="excel">Excel</option>
                </select>

                {{-- Hanya SUPER ADMIN yang bisa buat surat --}}
                @if(session('typeuser') == 1)
                    <button class="btn-primary" onclick="openModal('modalTambahSurat')">+ Tambah Surat Kirim</button>
                @endif
            </div>
        </div>

        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>No Kirim</th>
                    <th>Tanggal</th>
                    <th>Kode Toko Kirim</th>
                    <th>Status</th>
                    <th>No Terima</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($suratKirim as $surat)
                    <tr>
                        <td>{{ $surat->nokirim }}</td>
                        <td>{{ $surat->tanggal }}</td>
                        <td>{{ $surat->kdtokokirim }}</td>
                        <td>{{ $surat->status }}</td>
                        <td>
                            @if ($surat->terimaBarang)
                                {{ $surat->terimaBarang->noterima }}
                            @else
                                Belum diterima
                            @endif
                        </td>
                        <td class="d-flex gap-2">
                            {{-- Hanya SUPER ADMIN yang bisa edit sebelum barang diterima --}}
                            @if(session('typeuser') == 1 && !$surat->terimaBarang)
                                <button class="btn btn-warning btn-sm" onclick="openEditModal({{ $surat }})">
                                    <i class="fa fa-pen"></i> Edit
                                </button>
                            @endif

                            {{-- User non-admin bisa terima barang --}}
                            @if(session('typeuser') != 1 && !$surat->terimaBarang)
                                <form action="{{ route('terimabarang.terima', $surat->nokirim) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="btn btn-success btn-sm">
                                        <i class="fa fa-check"></i> Terima
                                    </button>
                                </form>
                            @endif

                            {{-- Setelah diterima, semua role bisa lihat PDF --}}
                            @if ($surat->terimaBarang)
                                <a href="{{ route('terimabarang.preview', $surat->nokirim) }}" target="_blank"
                                    class="btn btn-info btn-sm">
                                    <i class="fa fa-eye"></i> Preview PDF
                                </a>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        {{-- Modal Tambah Surat (hanya SUPER ADMIN) --}}
        @if (Session::get('typeuser') == 1)
            <div id="modalTambahSurat" class="modal">
                <div class="modal-content">
                    <span class="close" onclick="closeModal('modalTambahSurat')">&times;</span>
                    <h2>Buat Surat Kirim Barang</h2>

                    <form action="{{ route('suratkirim.store') }}" method="POST">
                        @csrf
                        <label>Kode Toko Penerima</label>
                        <input list="toko-list" name="kdtokoterima" required>
                        <datalist id="toko-list">
                            @foreach($tokoList as $toko)
                                <option value="{{ $toko->kdtoko }}">{{ $toko->namatoko }}</option>
                            @endforeach
                        </datalist>

                        <h3>Barang</h3>
                        <div id="barang-list">
                            <div class="barang-item">
                                <input type="text" name="barang[0][barcode]" placeholder="Barcode" list="barcode-list"
                                    onchange="isiBarang(this, 0)">
                                <datalist id="barcode-list">
                                    @foreach($barangList as $b)
                                        <option value="{{ $b->barcode }}" data-namabarang="{{ $b->namabarang }}"
                                            data-kdjenis="{{ $b->kdjenis }}" data-kdbaki="{{ $b->kdbaki }}"
                                            data-berat="{{ $b->berat }}">
                                            {{ $b->namabarang }}
                                        </option>
                                    @endforeach
                                </datalist>

                                <input type="text" name="barang[0][namabarang]" placeholder="Nama Barang">
                                <input type="text" name="barang[0][kdjenis]" placeholder="Jenis">
                                <input type="text" name="barang[0][kdbaki]" placeholder="Baki">
                                <input type="number" step="0.01" name="barang[0][berat]" placeholder="Berat">
                                <input type="number" name="barang[0][qty]" placeholder="Qty">
                            </div>
                        </div>

                        <button type="button" onclick="tambahBarang()">+ Tambah Barang</button>
                        <br><br>
                        <button type="submit" class="btn-primary">Simpan Surat Kirim</button>
                    </form>
                </div>
            </div>

            <div id="modalEditSurat" class="modal">
                <div class="modal-content">
                    <span class="close" onclick="closeModal('modalEditSurat')">&times;</span>
                    <h2>Edit Surat Kirim Barang</h2>

                    <form id="formEditSurat" method="POST">
                        @csrf
                        @method('PUT')
                        <label>Kode Toko Kirim</label>
                        <input list="toko-list" name="kdtokokirim" id="kdtokokirim-edit" required>
                        <datalist id="toko-list">
                            @foreach($tokoList as $toko)
                                <option value="{{ $toko->kdtoko }}">{{ $toko->namatoko }}</option>
                            @endforeach
                        </datalist>

                        <h3>Barang</h3>
                        <div id="barang-list-edit"></div>

                        <button type="button" onclick="tambahBarangEdit()">+ Tambah Barang</button>
                        <br><br>
                        <button type="submit" class="btn-primary">Simpan Perubahan</button>
                    </form>
                </div>
            </div>
        @endif
    </div>

    <script>
        function openModal(id) {
            document.getElementById(id).classList.add('show');
        }

        function closeModal(id) {
            document.getElementById(id).classList.remove('show');
        }

        // klik di luar modal juga close
        document.querySelectorAll('.modal').forEach(modal => {
            modal.addEventListener('click', function (e) {
                if (e.target === modal) {
                    modal.classList.remove('show');
                }
            });
        });;

        // tambah barang dinamis
        let barangIndex = 1;
        function tambahBarangEdit() {
            let div = document.createElement("div");
            div.classList.add("barang-item");
            div.innerHTML = `
        <input type="text" name="barang[${barangIndexEdit}][barcode]" placeholder="Barcode" onchange="isiBarangEdit(this, ${barangIndexEdit})">
        <input type="text" name="barang[${barangIndexEdit}][namabarang]" placeholder="Nama Barang">
        <input type="text" name="barang[${barangIndexEdit}][kdjenis]" placeholder="Jenis">
        <input type="text" name="barang[${barangIndexEdit}][kdbaki]" placeholder="Baki">
        <input type="number" step="0.01" name="barang[${barangIndexEdit}][berat]" placeholder="Berat">
        <input type="number" name="barang[${barangIndexEdit}][qty]" placeholder="Qty">
    `;
            document.getElementById("barang-list-edit").appendChild(div);
            barangIndexEdit++;
        }

        function isiBarangEdit(input, index) {
            const option = Array.from(document.querySelectorAll(`#barcode-list option`))
                .find(opt => opt.value === input.value);

            if (option) {
                document.querySelector(`input[name='barang[${index}][namabarang]']`).value = option.dataset.namabarang;
                document.querySelector(`input[name='barang[${index}][kdjenis]']`).value = option.dataset.kdjenis;
                document.querySelector(`input[name='barang[${index}][kdbaki]']`).value = option.dataset.kdbaki;
                document.querySelector(`input[name='barang[${index}][berat]']`).value = option.dataset.berat;
            }
        }

        function openEditModal(surat) {
            // Reset modal
            document.getElementById('barang-list-edit').innerHTML = '';
            barangIndexEdit = 0;

            // Set form action
            document.getElementById('formEditSurat').action = `/suratkirim/${surat.nokirim}`;

            // Set toko penerima
            document.getElementById('kdtokokirim-edit').value = surat.kdtokokirim;

            // Tambah barang ke modal
            surat.detail.forEach(item => {
                let div = document.createElement('div');
                div.classList.add('barang-item');
                div.innerHTML = `
            <input type="text" name="barang[${barangIndexEdit}][barcode]" placeholder="Barcode" value="${item.barcode}" onchange="isiBarangEdit(this, ${barangIndexEdit})">
            <input type="text" name="barang[${barangIndexEdit}][namabarang]" placeholder="Nama Barang" value="${item.namabarang}">
            <input type="text" name="barang[${barangIndexEdit}][kdjenis]" placeholder="Jenis" value="${item.kdjenis}">
            <input type="text" name="barang[${barangIndexEdit}][kdbaki]" placeholder="Baki" value="${item.kdbaki}">
            <input type="number" step="0.01" name="barang[${barangIndexEdit}][berat]" placeholder="Berat" value="${item.berat}">
            <input type="number" name="barang[${barangIndexEdit}][qty]" placeholder="Qty" value="${item.qty}">
        `;
                document.getElementById('barang-list-edit').appendChild(div);
                barangIndexEdit++;
            });

            // Buka modal
            document.getElementById('modalEditSurat').classList.add('show');
        }

        // export handler
        function handleExport(value) {
            if (value === "print") window.print();
            if (value === "pdf") window.print();
            if (value === "csv") exportCSV();
            if (value === "excel") exportExcel();
        }

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
            link.setAttribute("download", "suratbarang.csv");
            link.click();
        }

        function exportExcel() {
            let table = document.querySelector("table").outerHTML;
            let data = new Blob([table], { type: "application/vnd.ms-excel" });
            let link = document.createElement("a");
            link.href = URL.createObjectURL(data);
            link.download = "suratbarang.xls";
            link.click();
        }
    </script>
</body>

</html>