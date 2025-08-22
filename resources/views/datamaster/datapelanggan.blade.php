<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="{{ asset('css/datamaster/cabang.css') }}">
    <link href="https://fonts.googleapis.com/css?family=Montserrat:400,800" rel="stylesheet">
    <script src="https://kit.fontawesome.com/a076d05399.js"></script>
    <title>Halaman Area</title>
</head>
<body>
    <h1 class="page-title">DATA PELANGGAN</h1>

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
            <button class="btn-primary">+ Tambah Pelanggan</button>
            <button class="btn-primary">Pengaturan Member</button>
        </div>
        <div style="display:flex; align-items:center; gap:6px;">
            <div class="icon-group">
                <button title="Sorting"><i class="fas fa-sort"></i></button>
                <button title="Refresh"><i class="fas fa-sync"></i></button>
            </div>
            <input type="text" placeholder="Search">
        </div>
    </div>

    <table>
        <thead>
            <tr>
                <th><input type="checkbox"></th>
                <th>Kode</th>
                <th>Nama</th>
                <th>Alamat</th>
                <th>Kontak</th>
                <th>Point</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td><input type="checkbox"></td>
                <td>DSA</td>
                <td>SDSD</td>
                <td>Jl. Raya Purbalingga No. 123</td>
                <td>08909090</td>
                <td>100</td>
                <td>
                    <button class="action-btn"><i class="fa-solid fa-pen-to-square"></i></button>
                    <button class="action-btn"><i class="fa-solid fa-file-export"></i></button>
                    <button class="action-btn"><i class="fa-solid fa-file"></i></button>
                    <button class="action-btn"><i class="fas fa-trash"></i></button>
                </td>
            </tr>
        </tbody>
    </table>

    <div class="footer">
        SKIBIDI
    </div>
</div>
</body>
</html>