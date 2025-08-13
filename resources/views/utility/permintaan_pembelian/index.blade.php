<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="{{ asset('css/utility/permintaan_pembelian/index.css') }}">
    <title>Permintaan Pembelian</title>
</head>
<body>
@include('partials.navbar')

<div class="container-pp">

    <!-- Judul dan Tombol -->
    <div class="header-pp">
        <h1>Formulir Permintaan Pembelian</h1>
        <a href="{{ route('formulir_pp.index') }}" class="btn-primary">
            <i class="fa fa-plus"></i> Formulir Permintaan Pembelian
        </a>
    </div>

    <!-- Filter Export -->
    <div class="filter-section">
        <select class="select-export">
            <option>Export Basic</option>
            <option>Export Excel</option>
            <option>Export PDF</option>
        </select>

    <div class="right-tools">
        <div class="icon-group">
            <button title="Sorting"><i class="fas fa-sort"></i></button>
            <button title="Refresh"><i class="fas fa-sync"></i></button>
            <button title="Tampilan List"><i class="fas fa-list"></i></button>
            <button title="Tampilan Grid"><i class="fas fa-th"></i></button>
            <button title="Export"><i class="fas fa-file-export"></i></button> 
        </div>
        <div class="search-box">
            <input type="text" placeholder="Search">
            <button class="btn-icon"><i class="fa fa-search"></i></button>
        </div>
    </div>


    <!-- Tabel Data -->
    <table class="table-pp">
        <thead>
            <tr>
                <th>No</th>
                <th>No. PP</th>
                <th>KD Toko</th>
                <th>Tgl Permintaan</th>
                <th>Tgl Dibutuhkan</th>
                <th>Status</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            @forelse($data as $index => $item)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $item->no_pp }}</td>
                    <td>{{ $item->kd_toko }}</td>
                    <td>{{ $item->tgl_permintaan }}</td>
                    <td>{{ $item->tgl_dibutuhkan }}</td>
                    <td>{{ $item->status }}</td>
                    <td>
                        <a href="#" class="btn-icon"><i class="fa fa-eye"></i></a>
                        <a href="#" class="btn-icon"><i class="fa fa-edit"></i></a>
                        <form action="{{ route('formulir_pp.destroy', $item->id) }}" method="POST" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button class="btn-icon" onclick="return confirm('Yakin ingin menghapus?')">
                                <i class="fa fa-trash"></i>
                            </button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" class="no-data">No matching records found</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
</body>
</html>