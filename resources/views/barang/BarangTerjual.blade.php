<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="{{ asset('css/datamaster/kategori.css') }}">
    <link href="https://fonts.googleapis.com/css?family=Montserrat:400,800" rel="stylesheet">
    <title>Halaman Area</title>
</head>

<body>

    @include('partials.navbar')
    <h1 class="page-title">Barang Terjual</h1>


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
            </div>
            <div style="display:flex; align-items:center; gap:6px;">
                <div class="icon-group">
                    <button title="Sorting" onclick="sortTable()"><i class="fas fa-sort"></i></button>
                    <button title="Refresh" onclick="refreshPage()"><i class="fas fa-sync"></i></button>
                    <button title="Tampilan List"><i class="fas fa-list"></i></button>
                    <button title="Tampilan Grid"><i class="fas fa-th"></i></button>
                </div>
                <input type="text" placeholder="Search">
            </div>
        </div>

        <table>
            <thead>
                <tr>
                    <th>Nama Barang</th>
                    <th>Barcode</th>
                    <th>Kode Baki</th>
                    <th>Berat</th>
                    <th>Kadar</th>
                    <th>Tanggal Jual</th>
                    <th>Harga</th>
                    <th>Staff</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @forelse($barangTerjual as $barang)
                    <tr>
                        <td>{{ $barang->namabarang }}</td>
                        <td>{{ $barang->barcode }}</td>
                        <td>{{ $barang->kdbaki }}</td>
                        <td>{{ $barang->berat }}</td>
                        <td>{{ $barang->kadar }}</td>
                        <td>{{ $barang->tanggalterjual }}</td>
                        <td>{{ number_format($barang->harga, 0, ',', '.') }}</td>
                        <td>{{ $barang->namastaff }}</td>
                        <td>
                            <button class="action-btn">
                                <i class="fa-solid fa-pen-to-square"></i>
                            </button>
                            <form action="#" method="POST" style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <button class="action-btn">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="9" style="text-align:center;">Belum ada barang terjual</td>
                    </tr>
                @endforelse
            </tbody>

        </table>

        <div class="footer">
            SKIBIDI
        </div>
    </div>

    <script>
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