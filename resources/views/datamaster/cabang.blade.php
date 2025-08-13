<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="{{ asset('css/datamaster/cabang.css') }}">
    <link href="https://fonts.googleapis.com/css?family=Montserrat:400,800" rel="stylesheet">
    <title>Halaman Area</title>
</head>
<body>

<h1 class="page-title">DAFTAR CABANG</h1>

<div class="container">

    <div class="top-bar">
        <div class="left-controls">
            <select>
                <option>Export Basic</option>
                <option>Export Basic</option>
                
            </select>
            <button class="btn-primary">+ Tambah Area</button>
        </div>
        <div style="display:flex; align-items:center; gap:6px;">
            <div class="icon-group">
                <button title="Sorting"><i class="fas fa-sort"></i></button>
                <button title="Refresh"><i class="fas fa-sync"></i></button>
                <button title="Tampilan List"><i class="fas fa-list"></i></button>
                <button title="Tampilan Grid"><i class="fas fa-th"></i></button>
                <button title="Export"><i class="fas fa-file-export"></i></button>
            </div>
            <input type="text" placeholder="Search">
        </div>
    </div>

    <table>
        <thead>
            <tr>
                <th><input type="checkbox"></th>
                <th>Kode Cabang</th>
                <th>Nama Cabang</th>
                <th>Alamat</th>
                <th>Area</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td><input type="checkbox"></td>
                <td>DSA</td>
                <td>SDSD</td>
                <td>Jl. Raya Purbalingga No. 123</td>
                <td>Area 1</td>
                <td>
                    <button class="action-btn"><i class="fa-solid fa-pen-to-square"></i></button>
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

