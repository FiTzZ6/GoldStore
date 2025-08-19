<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="{{ asset('css/datamaster/bakibarang.css') }}">
    <link href="https://fonts.googleapis.com/css?family=Montserrat:400,800" rel="stylesheet">
    <title>Halaman Area</title>
</head>
@include('partials.navbar')
<body>  <h1 class="page-title">DATA POTONGAN</h1>

<div class="container">

    <div class="top-bar">
        <div class="left-controls">
            <select>
                <option>Export Basic</option>
                <option>Export Basic</option>
                
            </select>
            <button class="btn-primary">+ Tambah Potongan</button>
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
                <th>Nomor</th>
                <th>Kode Kategori</th>
                <th>Jumlah Potongan / Gram</th>
                <th>Jenis Potongan</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td><input type="checkbox"></td>
                <td>1</td>
                <td>KT001</td>
                <td>100</td>
                <td>Diskon</td>
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
