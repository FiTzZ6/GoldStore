<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="{{ asset('css/datamaster/area.css') }}">
    <link href="https://fonts.googleapis.com/css?family=Montserrat:400,800" rel="stylesheet">
    <title>Halaman Area</title>

    <!-- DataTables CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.2/css/buttons.dataTables.min.css">

    <script src="https://kit.fontawesome.com/a076d05399.js"></script>
</head>

<body>
    @include('partials.navbar')

    <div class="container">
        <h1>DAFTAR AREA</h1>

        <!-- Alert sukses -->
        @if(session('success'))
            <div style="padding:10px; background:#d4edda; color:#155724; margin-bottom:15px; border-radius:5px;">
                {{ session('success') }}
            </div>
        @endif

        <div class="top-bar">
            <button class="btn-primary" onclick="openModal('modalTambah')">+ Tambah Area</button>
        </div>

        <table id="tabelArea" class="display">
            <thead>
                <tr>
                    <th>Kode Area</th>
                    <th>Nama Area</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($areas as $area)
                    <tr>
                        <td>{{ $area->kdarea }}</td>
                        <td>{{ $area->namaarea }}</td>
                        <td>
                            <button onclick="editArea('{{ $area->kdarea }}', '{{ $area->namaarea }}')">Edit</button>
                            <button onclick="hapusArea('{{ $area->kdarea }}')">Hapus</button>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <!-- Modal Tambah -->
    <div id="modalTambah" class="modal" style="display:none;">
        <div class="modal-content">
            <span class="close" onclick="closeModal('modalTambah')">&times;</span>
            <h2>Tambah Area</h2>

            @if ($errors->any())
                <div style="background:#f8d7da; color:#721c24; padding:10px; margin-bottom:10px; border-radius:5px;">
                    <ul style="margin:0; padding-left:20px;">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('store') }}" method="POST" id="formTambahArea">
                @csrf
                <label>Kode Area</label>
                <input type="text" name="kdarea" value="{{ old('kdarea') }}" required>

                <label>Nama Area</label>
                <input type="text" name="namaarea" value="{{ old('namaarea') }}">

                <button type="submit">Simpan</button>
            </form>
        </div>
    </div>

    <!-- Modal Edit -->
    <div id="modalEdit" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeModal('modalEdit')">&times;</span>
            <h2>Edit Area</h2>
            <form id="formEdit" method="POST">
                @csrf
                @method('PUT')
                <label>Kode Area</label>
                <input type="text" name="kdarea" id="editkdarea" required>
                <label>Nama Area</label>
                <input type="text" name="namaarea" id="editNamaArea">
                <button type="submit">Update</button>
            </form>
        </div>
    </div>

    <!-- Modal Hapus -->
    <div id="modalHapus" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeModal('modalHapus')">&times;</span>
            <h2>Hapus Area</h2>
            <p>Yakin ingin menghapus area ini?</p>
            <form id="formHapus" method="POST">
                @csrf
                @method('DELETE')
                <button type="submit">Hapus</button>
            </form>
        </div>
    </div>

    <!-- jQuery & DataTables -->
    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.2/js/dataTables.buttons.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.html5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.print.min.js"></script>

    <script>
        $(document).ready(function () {
            $('#tabelArea').DataTable({
                dom: 'Bfrtip',
                buttons: ['csv', 'excel', 'pdf', 'print'],
                pageLength: 20,
                lengthMenu: [[20, 25, 50, 75, 100], [20, 25, 50, 75, 100]],
            });
        });

        function openModal(id) { document.getElementById(id).style.display = 'block'; }
        function closeModal(id) { document.getElementById(id).style.display = 'none'; }

        function editArea(kd, nama) {
            document.getElementById('formEdit').action = '/update-area/' + kd;
            document.getElementById('editkdarea').value = kd;
            document.getElementById('editNamaArea').value = nama;
            openModal('modalEdit');
        }

        function hapusArea(kd) {
            document.getElementById('formHapus').action = '/hapus-area/' + kd;
            openModal('modalHapus');
        }
    </script>
</body>

</html>