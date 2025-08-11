<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="{{ asset('css/area.css') }}">
    <link href="https://fonts.googleapis.com/css?family=Montserrat:400,800" rel="stylesheet">
    <title>Halaman Area</title>
</head>
<body>
    @include('partials.navbar')

    <div class="breadcrumb">
        <a href="{{ route('laporan.dashboard') }}">Home</a> &gt; 
        <a href="#">Data Master</a> &gt; 
        <span>Area</span>
    </div>

    <div class="card">
        <div class="card-header">
            <h2>DAFTAR AREA</h2>
        </div>
        <div class="card-body">
            <div class="toolbar">
                <div class="left-tools">
                    <select class="export-select">
                        <option>Export Basic</option>
                        <option>Excel</option>
                        <option>PDF</option>
                    </select>
                    <button class="btn-primary">
                        <i class="fa fa-plus"></i> Tambah Area
                    </button>
                </div>
                <div class="search-container">
                    <input type="text" placeholder="Search">
                    <button><i class="fa fa-search"></i></button>
                </div>
            </div>


            <table class="data-table">
                <thead>
                    <tr>
                        <th><input type="checkbox"></th>
                        <th>Kode Area</th>
                        <th>Nama Area</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td><input type="checkbox"></td>
                        <td>DSA</td>
                        <td>SDSD</td>
                        <td>
                            <button class="btn-edit"><i class="fa fa-pen"></i></button>
                            <button class="btn-delete"><i class="fa fa-trash"></i></button>
                        </td>
                    </tr>
                    <tr>
                        <td><input type="checkbox"></td>
                        <td>PBG</td>
                        <td>PURBALINGGA</td>
                        <td>
                            <button class="btn-edit"><i class="fa fa-pen"></i></button>
                            <button class="btn-delete"><i class="fa fa-trash"></i></button>
                        </td>
                    </tr>
                </tbody>
            </table>
            <div class="table-footer">
                Showing 1 to 2 of 2 rows
            </div>
        </div>
    </div>
</body>
</html>

