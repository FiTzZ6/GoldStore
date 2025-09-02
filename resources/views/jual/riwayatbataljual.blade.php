<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="{{ asset('css/jual/riwayatbataljual.css') }}">
    <link href="https://fonts.googleapis.com/css?family=Montserrat:400,800" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" />
    <script src="https://kit.fontawesome.com/a076d05399.js"></script>
</head>

<body>
    @include('partials.navbar')
    <h1>RIWAYAT BATAL BELI</h1>

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
            </div>
            <div style="display:flex; align-items:center; gap:6px;">
                <div class="icon-group">
                    <button title="Sorting" onclick="sortTable()"><i class="fas fa-sort"></i></button>
                    <button title="Refresh" onclick="refreshPage()"><i class="fas fa-sync"></i></button>
                </div>
                <input type="text" placeholder="Search">
            </div>
        </div>

        <table id="tabelArea" class="display">
            <thead>
                <tr>
                    <th><input type="checkbox"></th>
                    <th>Tanggal</th>
                    <th>No. batal</th>
                    <th>No. transaksi</th>
                    <th>Barcode</th>
                    <th>Nama Barang</th>
                    <th>Berat</th>
                    <th>Harga</th>
                    <th>Ongkos</th>
                    <th>Total</th>
                    <th>Staff</th>
                    <th>Kondisi</th>
                    <th>Keterangan</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($riwayat as $item)
                    <tr>
                        <td><input type="checkbox"></td>
                        <td>{{ \Carbon\Carbon::parse($item->created_at)->format('d-m-Y') }}</td>
                        <td>{{ $item->fakturbataljual }}</td>
                        <td>{{ $item->fakturjual }}</td>
                        <td>{{ $item->barcode }}</td>
                        <td>{{ $item->namabarang }}</td>
                        <td>{{ $item->berat }}</td>
                        <td>{{ number_format($item->harga, 0, ',', '.') }}</td>
                        <td>{{ number_format($item->ongkos, 0, ',', '.') }}</td>
                        <td>{{ number_format(($item->harga * $item->quantity) + $item->ongkos, 0, ',', '.') }}</td>
                        <td>{{ $item->namastaff }}</td>
                        <td>{{ ucfirst($item->kondisi) }}</td>
                        <td>{{ $item->keterangan }}</td>
                        <td>
                            <button class="action-btn"><i class="fa-solid fa-pen-to-square"></i></button>
                            <button class="action-btn"><i class="fas fa-trash"></i></button>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        <div class="pagination">
            {{ $riwayat->links() }}
        </div>
    </div>


    <script>

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
    </script>
</body>

</html>