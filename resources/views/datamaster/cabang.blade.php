<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="{{ asset('css/datamaster/cabang.css') }}">
    <link href="https://fonts.googleapis.com/css?family=Montserrat:400,800" rel="stylesheet">
    <title>Halaman Cabang</title>

    <!-- (opsional) Font Awesome agar ikon tampil -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" />

    <!-- SheetJS untuk Excel -->
    <script src="https://cdn.jsdelivr.net/npm/xlsx@0.20.2/dist/xlsx.full.min.js"></script>
    <!-- jsPDF + AutoTable untuk PDF -->
    <script src="https://cdn.jsdelivr.net/npm/jspdf@2.5.1/dist/jspdf.umd.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/jspdf-autotable@3.8.2/dist/jspdf.plugin.autotable.min.js"></script>
</head>

<body>
    @include('partials.navbar')
    <h1 class="page-title">DAFTAR CABANG</h1>


    <div class="container">

        <div class="top-bar">
            <div class="left-controls">
                <div class="export-group" style="display:flex; gap:8px; flex-wrap:wrap;">
                    <button type="button" class="btn-primary" id="btnExportCSV">
                        <i class="fa-solid fa-file-csv"></i> Export CSV
                    </button>
                    <button type="button" class="btn-primary" id="btnExportXLSX">
                        <i class="fa-regular fa-file-excel"></i> Export Excel
                    </button>
                    <button type="button" class="btn-primary" id="btnExportPDF">
                        <i class="fa-regular fa-file-pdf"></i> Export PDF
                    </button>
                    <button type="button" class="btn-primary" id="btnPrint">
                        <i class="fa-solid fa-print"></i> Print
                    </button>
                </div>
                <button class="btn-primary" onclick="document.getElementById('modalTambah')?.showModal()">+ Tambah
                    Area</button>
            </div>

            <div style="display:flex; align-items:center; gap:6px;">
                <div class="icon-group">
                    <button title="Sorting"><i class="fas fa-sort"></i></button>
                    <button title="Refresh" onclick="location.reload()"><i class="fas fa-sync"></i></button>
                    <button title="Tampilan List"><i class="fas fa-list"></i></button>
                    <button title="Tampilan Grid"><i class="fas fa-th"></i></button>
                    <button title="Export"
                        onclick="document.querySelector('.export-group').scrollIntoView({behavior:'smooth'})"><i
                            class="fas fa-file-export"></i></button>
                </div>
                <input type="text" id="searchBox" placeholder="Search" oninput="filterTable()">
            </div>
        </div>

        <!-- Tambahkan id pada tabel -->
        <table id="cabangTable">
            <thead>
                <tr>
                    <th><input type="checkbox" id="checkAll" onclick="toggleAll(this)"></th>
                    <th>Kode Cabang</th>
                    <th>Nama Cabang</th>
                    <th>Alamat</th>
                    <th>Area</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach($tokos as $toko)
                    <tr>
                        <td><input type="checkbox" class="row-check" value="{{ $toko->kdtoko }}"></td>
                        <td>{{ $toko->kdtoko }}</td>
                        <td>{{ $toko->namatoko }}</td>
                        <td>{{ $toko->alamattoko }}</td>
                        <td>{{ $toko->area }}</td>
                        <td style="white-space:nowrap;">
                            <!-- contoh tombol edit & delete, sesuaikan action/form kamu -->
                            <form action="{{ route('datamaster.update', $toko->kdtoko) }}" method="POST"
                                style="display:inline;" enctype="multipart/form-data">
                                @csrf
                                <button type="submit" class="action-btn" title="Edit">
                                    <i class="fa-solid fa-pen-to-square"></i>
                                </button>
                            </form>
                            <form action="{{ route('datamaster.destroy', $toko->kdtoko) }}" method="POST"
                                style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="action-btn" title="Hapus"
                                    onclick="return confirm('Hapus cabang ini?')">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <!-- Modal Tambah Cabang -->
    <dialog id="modalTambah">
        <form method="POST" action="{{ route('datamaster.store') }}" enctype="multipart/form-data">
            @csrf
            <h2>Tambah Cabang</h2>

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

            <menu>
                <button type="submit" class="btn-primary">Simpan</button>
                <button type="button" class="btn-secondary"
                    onclick="document.getElementById('modalTambah').close()">Batal</button>
            </menu>
        </form>
    </dialog>

    <dialog id="modalEdit-{{ $toko->id }}">
        <form action="{{ route('datamaster.update', $toko->kdtoko) }}" method="POST" style="display:inline;"
            enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <h2>Edit Cabang</h2>

            <label>Nama Cabang</label>
            <input type="text" name="namatoko" value="{{ $toko->namatoko }}">

            <label>Alamat</label>
            <input type="text" name="alamattoko" value="{{ $toko->alamattoko }}">

            <label>Area</label>
            <input type="text" name="area" value="{{ $toko->area }}">

            <label>Logo</label>
            <input type="file" name="logo">

            <menu>
                <button type="submit" class="btn-primary">Simpan</button>
                <button type="button" class="btn-secondary"
                    onclick="document.getElementById('modalEdit-{{ $toko->id }}').close()">Batal</button>
            </menu>
        </form>
    </dialog>

    @if(session('success'))
        <script>
            // SweetAlert (pastikan sudah load swal di layout utama jika mau pakai)
            // Swal.fire({ icon: 'success', title: @json(session('success')), showConfirmButton: false, timer: 1500 });
            console.log(@json(session('success')));
        </script>
    @endif

    <script>
        // =========================
        // 1) UTIL: Ambil data tabel
        // =========================
        function getTableData(includeHeaders = true) {
            const table = document.getElementById('cabangTable');
            const rows = Array.from(table.querySelectorAll('tr'));
            let data = [];

            rows.forEach((row, idx) => {
                const cells = Array.from(row.querySelectorAll(idx === 0 ? 'th' : 'td'));
                if (cells.length === 0) return;

                // Buang kolom checkbox (index 0) & kolom Action (kolom terakhir)
                const sliced = cells.slice(1, cells.length - 1).map(td => td.innerText.trim());

                if (idx === 0) {
                    if (includeHeaders) data.push(sliced);
                } else {
                    // Skip baris tersembunyi (hasil filter)
                    if (row.style.display === 'none') return;
                    data.push(sliced);
                }
            });
            return data;
        }

        // =========================
        // 2) EXPORT CSV (murni JS)
        // =========================
        function exportCSV(filename = 'cabang.csv') {
            const rows = getTableData(true);
            const csv = rows.map(r => r.map(v => {
                // escape tanda kutip & koma
                const needsQuote = /[",\n]/.test(v);
                let s = v.replace(/"/g, '""');
                return needsQuote ? `"${s}"` : s;
            }).join(',')).join('\n');

            const blob = new Blob([csv], { type: 'text/csv;charset=utf-8;' });
            const link = document.createElement('a');
            link.href = URL.createObjectURL(blob);
            link.download = filename;
            link.click();
            URL.revokeObjectURL(link.href);
        }

        // ==========================================
        // 3) EXPORT EXCEL (SheetJS - xlsx.full.min.js)
        // ==========================================
        function exportXLSX(filename = 'cabang.xlsx') {
            const wb = XLSX.utils.book_new();
            const data = getTableData(true);
            const ws = XLSX.utils.aoa_to_sheet(data);
            XLSX.utils.book_append_sheet(wb, ws, 'Cabang');
            XLSX.writeFile(wb, filename);
        }

        // ==========================================
        // 4) EXPORT PDF (jsPDF + autoTable)
        // ==========================================
        async function exportPDF(filename = 'cabang.pdf') {
            const { jsPDF } = window.jspdf;
            const doc = new jsPDF({ orientation: 'landscape' });

            const data = getTableData(true);
            const head = [data[0]];
            const body = data.slice(1);

            doc.text('Daftar Cabang', 14, 14);
            doc.autoTable({
                head,
                body,
                startY: 20,
                styles: { fontSize: 8 },
                headStyles: { fillColor: [52, 73, 94] },
                didDrawPage: (d) => { /* bisa tambah nomor halaman jika perlu */ }
            });

            doc.save(filename);
        }

        // =========================
        // 5) PRINT (window.print)
        // =========================
        function printTable() {
            const table = document.getElementById('cabangTable').cloneNode(true);

            // Hapus kolom checkbox & Action saat print
            // header
            table.querySelectorAll('thead th:first-child, thead th:last-child').forEach(el => el.remove());
            // body
            table.querySelectorAll('tbody tr').forEach(tr => {
                // skip yang tersembunyi (hasil filter)
                if (tr.style.display === 'none') { tr.remove(); return; }
                tr.querySelector('td:first-child')?.remove();
                tr.querySelector('td:last-child')?.remove();
            });

            const w = window.open('', '_blank');
            w.document.write(`
            <html>
            <head>
                <title>Print - Daftar Cabang</title>
                <style>
                    body { font-family: Arial, sans-serif; padding: 16px; }
                    h2 { margin-bottom: 12px; }
                    table { width: 100%; border-collapse: collapse; }
                    th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
                    th { background: #f2f2f2; }
                </style>
            </head>
            <body>
                <h2>Daftar Cabang</h2>
                ${table.outerHTML}
            </body>
            </html>
        `);
            w.document.close();
            w.focus();
            w.print();
            w.close();
        }

        // =========================
        // 6) SEARCH sederhana (client)
        // =========================
        function filterTable() {
            const q = document.getElementById('searchBox').value.toLowerCase();
            const rows = document.querySelectorAll('#cabangTable tbody tr');
            rows.forEach(tr => {
                const text = tr.innerText.toLowerCase();
                tr.style.display = text.includes(q) ? '' : 'none';
            });
        }

        // =========================
        // 7) Checkbox helper
        // =========================
        function toggleAll(box) {
            const checks = document.querySelectorAll('.row-check');
            checks.forEach(ch => ch.checked = box.checked);
        }

        // =========================
        // 8) Bind tombol export
        // =========================
        document.getElementById('btnExportCSV').addEventListener('click', () => exportCSV());
        document.getElementById('btnExportXLSX').addEventListener('click', () => exportXLSX());
        document.getElementById('btnExportPDF').addEventListener('click', () => exportPDF());
        document.getElementById('btnPrint').addEventListener('click', () => printTable());
    </script>




</body>

</html>