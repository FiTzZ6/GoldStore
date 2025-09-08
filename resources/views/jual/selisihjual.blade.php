<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Selisih Jual Batal - TANGCAL</title>
    <link rel="stylesheet" href="{{ asset('css/jual/selisihjual.css') }}">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>

<body>
    @include('partials.navbar')
    <div class="container">
        <div class="header">
            <h1><i class="fas fa-exchange-alt"></i> DATA TRANSAKSI JUAL SELISIH BATAL</h1>
        </div>

        <div class="controls">
            <div class="export-section">
                <select onchange="handleExport(this.value)">
                    <option value="">Export Basic</option>
                    <option value="print">Print</option>
                    <option value="pdf">PDF</option>
                    <option value="excel">Excel</option>
                    <option value="csv">CSV</option>
                </select>
            </div>
            <div class="right-controls">
                <div class="icon-group">
                    <button title="Sorting" onclick="sortTable()"><i class="fas fa-sort"></i></button>
                    <button title="Refresh" onclick="refreshPage()"><i class="fas fa-sync"></i></button>
                </div>
                <div class="search-section">
                    <input type="text" placeholder="Search">
                    <button><i class="fas fa-search"></i></button>
                </div>
            </div>
        </div>

        <div class="content">
            <div class="table-container">
                <table id="tabelArea">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>No. Faktur Jual</th>
                            <th>No. Faktur Batal Jual</th>
                            <th>Barcode</th>
                            <th>Harga Jual</th>
                            <th>Harga Batal</th>
                            <th>Selisih</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($selisih as $index => $row)
                                                <tr>
                                                    <td>{{ $index + 1 }}</td>
                                                    <td>{{ $row->fakturjual }}</td>
                                                    <td>{{ $row->fakturbataljual }}</td>
                                                    <td>{{ $row->barcode }}</td>
                                                    <td>Rp {{ number_format($row->harga_jual_asli, 0, ',', '.') }}</td>
                                                    <td>Rp {{ number_format($row->harga_batal, 0, ',', '.') }}</td>
                                                    <td>
                                                        @php
                                                            $jual = $row->harga_jual_asli ?? 0;
                                                            $batal = $row->harga_batal ?? 0;
                                                            $selisih = $jual - $batal;
                                                        @endphp

                                                        @if($jual == $batal || $batal == 0)
                                                            <span style="color:green">Rp 0</span>
                                                        @else
                                                            <span style="color:{{ $selisih >= 0 ? 'green' : 'red' }}">
                                                                Rp {{ number_format($selisih, 0, ',', '.') }}
                                                            </span>
                                                        @endif
                                                    </td>
                                                    <td>
                                                        <form action="{{ route('selisihjual.destroy', $row->id ?? 0) }}" method="POST"
                                                            onsubmit="return confirm('Yakin hapus data ini?')">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button class="btn btn-sm btn-danger"><i class="fas fa-trash"></i></button>
                                                        </form>
                                                    </td>
                                                </tr>
                        @empty
                            <tr class="no-data">
                                <td colspan="8">
                                    <div class="no-data-message">
                                        <i class="fas fa-database"></i>
                                        <p>No matching records found</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script>
        // Script export
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
            link.setAttribute("download", "selisih_jual.csv");
            link.click();
        }

        function exportExcel() {
            let table = document.querySelector("table").outerHTML;
            let data = new Blob([table], { type: "application/vnd.ms-excel" });
            let link = document.createElement("a");
            link.href = URL.createObjectURL(data);
            link.download = "selisih_jual.xls";
            link.click();
        }

        function exportPDF() {
            window.print();
        }

        function refreshPage() {
            location.reload();
        }

        function sortTable() {
            let table = document.querySelector("table");
            let rows = Array.from(table.rows).slice(1);
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