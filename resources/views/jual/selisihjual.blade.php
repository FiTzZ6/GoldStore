<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="{{ asset('css/jual/selisihjual.css') }}">
    <link href="https://fonts.googleapis.com/css?family=Montserrat:400,800" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" />
    <script src="https://kit.fontawesome.com/a076d05399.js"></script>
</head>

<body>

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
                    <th>No</th>
                    <th>No. transaksi</th>
                    <th>Barcode</th>
                    <th>Harga Jual</th>
                    <th>Harga Batal</th>
                    <th>Ongkos</th>
                    <th>Selisih</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                    <tr>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td>
                            <button class="action-btn" onclick="editArea('{{ $area->kdarea }}', '{{ $area->namaarea }}')"><i class="fa-solid fa-pen-to-square"></i></button>
                            <button class="action-btn" onclick="hapusArea('{{ $area->kdarea }}')"><i class="fas fa-trash"></i></button>
                        </td>
                    </tr>
            </tbody>
        </table>
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