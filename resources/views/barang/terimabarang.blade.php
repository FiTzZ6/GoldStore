<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Terima Barang</title>
    <link rel="stylesheet" href="{{ asset('css/datamaster/kategori.css') }}">
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

                {{-- Hanya admin yang bisa buat surat --}}
                @if (Session::get('role') == 'admin')
                    <button class="btn-primary" onclick="openModal('modalTambahSurat')">+ Tambah Surat Kirim</button>
                @endif
            </div>
        </div>

        <table>
            <thead>
                <tr>
                    <th>No. Surat Kirim</th>
                    <th>Tanggal</th>
                    <th>Kode Toko Pengirim</th>
                    <th>Status Kirim</th>
                    <th>Surat</th>
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
                        <td>
                            {{-- Jika admin -> bisa edit sebelum diterima --}}
                            @if (Session::get('role') == 'admin' && !$surat->terimaBarang)
                                <a href="{{ route('suratkirim.edit', $surat->nokirim) }}" class="action-btn">
                                    <i class="fa fa-pen"></i> Edit
                                </a>
                            @endif

                            {{-- Jika bukan admin -> tombol terima --}}
                            @if (Session::get('role') != 'admin' && !$surat->terimaBarang)
                                <form action="{{ route('terimabarang.terima', $surat->nokirim) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="action-btn">
                                        <i class="fa fa-check"></i> Terima
                                    </button>
                                </form>
                            @endif

                            {{-- Export PDF --}}
                            @if ($surat->terimaBarang)
                                <a href="{{ route('terimabarang.cetakpdf', $surat->nokirim) }}" target="_blank"
                                    class="action-btn">
                                    <i class="fa fa-file-pdf"></i> PDF
                                </a>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        {{-- Modal Tambah Surat (hanya admin) --}}
        @if (Session::get('role') == 'admin')
            <div id="modalTambahSurat" class="modal" style="display:none;">
                <div class="modal-content">
                    <span class="close" onclick="closeModal('modalTambahSurat')">&times;</span>
                    <h2>Buat Surat Kirim Barang</h2>

                    <form action="{{ route('suratkirim.store') }}" method="POST">
                        @csrf
                        <label>Kode Toko Penerima</label>
                        <input type="text" name="kdtokoterima" required>

                        <h3>Barang</h3>
                        <div id="barang-list">
                            <div class="barang-item">
                                <input type="text" name="barang[0][barcode]" placeholder="Barcode">
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
        @endif
    </div>

    <script>
        function openModal(id) { document.getElementById(id).style.display = 'block'; }
        function closeModal(id) { document.getElementById(id).style.display = 'none'; }

        // tambah barang dinamis
        let barangIndex = 1;
        function tambahBarang() {
            let div = document.createElement("div");
            div.classList.add("barang-item");
            div.innerHTML = `
                <input type="text" name="barang[${barangIndex}][barcode]" placeholder="Barcode">
                <input type="text" name="barang[${barangIndex}][namabarang]" placeholder="Nama Barang">
                <input type="text" name="barang[${barangIndex}][kdjenis]" placeholder="Jenis">
                <input type="text" name="barang[${barangIndex}][kdbaki]" placeholder="Baki">
                <input type="number" step="0.01" name="barang[${barangIndex}][berat]" placeholder="Berat">
                <input type="number" name="barang[${barangIndex}][qty]" placeholder="Qty">`;
            document.getElementById("barang-list").appendChild(div);
            barangIndex++;
        }

        // export handler
        function handleExport(value) {
            if (value === "print") window.print();
            if (value === "pdf") window.print(); // bisa pakai dompdf via route juga
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