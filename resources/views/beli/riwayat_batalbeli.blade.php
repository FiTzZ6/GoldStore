<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Batal Beli - TANGCAL</title>
    <link rel="stylesheet" href="{{ asset('css/beli/riwayat_batal.css') }}">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
@include('partials.navbar')
    <div class="container">
        <div class="header">
            <!-- Header Top: Judul di tengah -->
            <div class="header-top">
                <h1><i class="fas fa-times-circle"></i> Daftar Pembatalan</h1>
            </div>
            
            <!-- Header Bottom: Export di kiri, Search/Sorting di kanan -->
            <div class="header-bottom">
                <div class="left-controls">
                    <select onchange="handleExport(this.value)">
                        <option value="">Pilih Export</option>
                        <option value="print">Export Print</option>
                        <option value="pdf">Export PDF</option>
                        <option value="csv">Export CSV</option>
                        <option value="excel">Export Excel</option>
                    </select>
                </div>
                
                <div class="right-controls">
                    <div class="icon-group">
                        <button title="Sorting" onclick="sortTable()"><i class="fas fa-sort"></i></button>
                        <button title="Refresh" onclick="refreshPage()"><i class="fas fa-sync"></i></button>
                    </div>
                    <input type="text" placeholder="Search">
                </div>
            </div>
        </div>

        <div class="content">
            <div class="table-container">
                <table>
                    <thead>
                        <tr>
                            <th>Tanggal</th>
                            <th>No. Batal</th>
                            <th>No. Beli</th>
                            <th>Nama Barang</th>
                            <th>Berat</th>
                            <th>Harga</th>
                            <th>Kondisi Barang</th>
                            <th>Kondisi Batal</th>
                            <th>Ket</th>
                            <th>Staff</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>27.03.2021 14:40:13</td>
                            <td>BB-SAMBA512103-0001</td>
                            <td>FB-SAMBA512103-0001</td>
                            <td>ANT JPT COR AD PTH</td>
                            <td>0.000</td>
                            <td>Rp. 388.180,00</td>
                            <td><span class="status-badge good">MULUS</span></td>
                            <td><span class="status-badge good">MULUS</span></td>
                            <td>Mengganti Barang</td>
                            <td></td>
                            <td>
                                <button class="action-btn"><i class="fa-solid fa-pen-to-square"></i></button>
                                <button class="action-btn"><i class="fas fa-trash"></i></button>
                            </td>
                        </tr>
                        <tr>
                            <td>28.03.2021 10:15:27</td>
                            <td>BB-SAMBA512103-0002</td>
                            <td>FB-SAMBA512103-0002</td>
                            <td>KABEL HDMI 2.0</td>
                            <td>0.000</td>
                            <td>Rp. 125.000,00</td>
                            <td><span class="status-badge damaged">RUSAK</span></td>
                            <td><span class="status-badge good">MULUS</span></td>
                            <td>Batal otomatis</td>
                            <td></td>
                            <td>
                                <button class="action-btn"><i class="fa-solid fa-pen-to-square"></i></button>
                                <button class="action-btn"><i class="fas fa-trash"></i></button>
                            </td>
                        </tr>
                        <tr>
                            <td>29.03.2021 16:45:32</td>
                            <td>BB-SAMBA512103-0003</td>
                            <td>FB-SAMBA512103-0003</td>
                            <td>ADAPTOR 12V 3A</td>
                            <td>0.000</td>
                            <td>Rp. 87.500,00</td>
                            <td><span class="status-badge good">MULUS</span></td>
                            <td><span class="status-badge damaged">RUSAK</span></td>
                            <td>Stok habis</td>
                            <td></td>
                            <td>
                                <button class="action-btn"><i class="fa-solid fa-pen-to-square"></i></button>
                                <button class="action-btn"><i class="fas fa-trash"></i></button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <div class="pagination">
                <div class="showing-info">
                    Showing 1 to 3 of 3 rows
                </div>
                <div class="pagination-controls">
                    <button class="page-btn disabled"><i class="fas fa-chevron-left"></i></button>
                    <span class="current-page">1</span>
                    <button class="page-btn disabled"><i class="fas fa-chevron-right"></i></button>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Set tanggal default
        document.addEventListener('DOMContentLoaded', function() {
            const today = new Date().toISOString().substr(0, 10);
            document.getElementById('start-date').value = "2021-03-01";
            document.getElementById('end-date').value = today;
        });

        function handleExport(value) {
            if (value) {
                alert(`Fitur export ${value.toUpperCase()} akan diproses`);
            }
        }

        function sortTable() {
            alert('Fitur sorting akan diproses');
        }

        function refreshPage() {
            location.reload();
        }
    </script>
</body>
</html>