<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Daftar Baki</title>
    <link rel="stylesheet" href="{{ asset('css/datamaster/bakibarang.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.3.6/css/buttons.dataTables.min.css">
</head>
<body>
@include('partials.navbar')

<h1>DAFTAR AREA</h1>
<div class="container">
    <div class="top-bar">
        <div>
            <select>
                <option>Export Basic</option>
                <option>Export Excel</option>
                <option>Export PDF</option>
            </select>
            <button class="btn-primary" onclick="document.getElementById('form-tambah').style.display='block'">
                + Tambah Baki
            </button>
        </div>
        <div style="display:flex; align-items:center; gap:6px;">
            <div class="icon-group">
                <button title="Sorting"><i class="fas fa-sort"></i></button>
                <button title="Refresh" onclick="location.reload()"><i class="fas fa-sync"></i></button>
                <button title="Tampilan List"><i class="fas fa-list"></i></button>
                <button title="Tampilan Grid"><i class="fas fa-th"></i></button>
                <button title="Export"><i class="fas fa-file-export"></i></button>
            </div>
        </div>
    </div>

    <table id="bakiTable" class="display" style="width:100%">
        <thead>
            <tr>
                <th><input type="checkbox"></th>
                <th>Kode Baki</th>
                <th>Nama Baki</th>
                <th>Kode Kategori</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            @foreach($baki as $item)
            <tr>
                <td><input type="checkbox"></td>
                <td>{{ $item->kdbaki }}</td>
                <td>{{ $item->namabaki }}</td>
                <td>{{ $item->kdkategori }}</td>
                <td>
                    <button class="action-btn"><i class="fa-solid fa-pen-to-square"></i></button>
                    <form action="{{ route('bakibarang.destroy', $item->kdbaki) }}" method="POST" style="display:inline;">
                        @csrf
                        @method('DELETE')
                        <button class="action-btn" onclick="return confirm('Yakin hapus?')"><i class="fas fa-trash"></i></button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <!-- Form Tambah Data -->
    <div id="form-tambah" style="display:none; margin-top:20px;">
        <form method="POST" action="{{ route('bakibarang.store') }}">
            @csrf
            <label>Kode Baki</label>
            <input type="text" name="kdbaki" required>
            <label>Nama Baki</label>
            <input type="text" name="namabaki" required>
            <label>Kode Kategori</label>
            <input type="text" name="kdkategori">
            <button type="submit" class="btn-primary">Simpan</button>
        </form>
    </div>

    <div class="footer fixed-bottom">
        <i class="fas fa-user"></i> admin | {{ now()->format('l, d F Y | H.i.s') }}
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.3.6/js/dataTables.buttons.min.js"></script>
<script>
    $(document).ready(function() {
        $('#bakiTable').DataTable({
            "pageLength": 10,
            "lengthChange": false,
            "ordering": true,
            "searching": true
        });
    });
</script>
</body>
</html>
