<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <link rel="stylesheet" href="{{ asset('css/uang_kas/uangkas.css') }}">
    <link href="https://fonts.googleapis.com/css?family=Montserrat:400,800" rel="stylesheet">
    <title>Uang Kas</title>
</head>

<body>
    @include('partials.navbar')
    <div style="text-align:right; font-weight:bold; font-size:18px; margin-bottom:10px;">
        Kas Awal: <span id="kasAwal">{{ number_format($kasAwal, 0, ',', '.') }}</span>
    </div>
    <div class="container">
        <h2 class="mb-4">Data Uang Kas</h2>

        <button class="btn btn-info" onclick="openModal('modalKasAwal')">
            Lihat Kas Awal
        </button>

        <!-- Tombol Tambah -->
        <button class="btn btn-primary" onclick="openModal('modalTambah')">Tambah Kas</button>

        <!-- Wrapper Tabel -->
        <div class="table-wrapper" style="overflow-x:auto;">
            <table id="kasTable" class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Jumlah Kas</th>
                        <th>Parameter Kas</th>
                        <th>Type</th>
                        <th>Tanggal</th>
                        <th>Keterangan</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($kas as $i => $row)
                        <tr data-id="{{ $row->id }}">
                            <td>{{ $i + 1 }}</td>
                            <td>{{ $row->jumlahkas }}</td>
                            <td>{{ $row->idparameterkas }}</td>
                            <td>{{ $row->type }}</td>
                            <td>{{ \Carbon\Carbon::parse($row->tanggal)->format('Y-m-d H:i:s') }}</td>
                            <td>{{ $row->keterangan }}</td>
                            <td>
                                <button class="btn btn-sm btn-warning btnEdit">Edit</button>
                                <button class="btn btn-sm btn-danger btnHapus">Hapus</button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- Modal Kas Awal -->
    <div id="modalKasAwal" class="modal-overlay" style="display:none;">
        <div class="modal-box">
            <h3>Kas Awal</h3>
            <p style="font-size:20px; font-weight:bold;">
                Rp <span id="kasAwalModal">{{ number_format($kasAwal, 0, ',', '.') }}</span>
            </p>
            <button type="button" onclick="closeModal('modalKasAwal')">Tutup</button>
        </div>
    </div>


    <!-- Modal Tambah -->
    <div id="modalTambah" class="modal-overlay" style="display:none;">
        <div class="modal-box">
            <h3>Tambah Kas</h3>
            <form id="formTambah" action="{{ route('uang_kas.store') }}" method="POST">
                @csrf
                <label>Tanggal</label>
                <input type="datetime-local" name="tanggal" required>

                <label>Jenis</label>
                <select name="type" required>
                    <option value="masuk">Masuk</option>
                    <option value="keluar">Keluar</option>
                </select>

                <label>Jumlah</label>
                <input type="number" name="jumlahkas" required>

                <label>Keterangan</label>
                <select id="ketTambah" required>
                    @foreach($parameterKasList as $param)
                        <option value="{{ $param->kdparameterkas }}">
                            {{ $param->parameterkas }}
                        </option>
                    @endforeach
                </select>

                <input type="hidden" name="idparameterkas" id="paramTambah">

                <button type="submit">Simpan</button>
                <button type="button" onclick="closeModal('modalTambah')">Batal</button>
            </form>
        </div>
    </div>

    <!-- Modal Edit -->
    <div id="modalEdit" class="modal-overlay" style="display:none;">
        <div class="modal-box">
            <h3>Edit Kas</h3>
            <form id="formEdit">
                @csrf
                @method('PUT')
                <input type="hidden" name="id">

                <label>Tanggal</label>
                <input type="datetime-local" name="tanggal" required>
                <label>Jenis</label>
                <select name="type" required>
                    <option value="masuk">Masuk</option>
                    <option value="keluar">Keluar</option>
                </select>

                <label>Jumlah</label>
                <input type="number" name="jumlahkas" required>

                <label>Keterangan</label>
                <select id="ketEdit" required>
                    @foreach($parameterKasList as $param)
                        <option value="{{ $param->kdparameterkas }}">
                            {{ $param->parameterkas }}
                        </option>
                    @endforeach
                </select>

                <input type="hidden" name="idparameterkas" id="paramEdit">

                <button type="submit">Update</button>
                <button type="button" onclick="closeModal('modalEdit')">Batal</button>
            </form>
        </div>
    </div>



    <!-- Alert -->
    <div id="alertBox" class="alert-box"></div>

    <script>
        function openModal(id) {
            document.getElementById(id).style.display = 'flex';
        }

        function closeModal(id) {
            document.getElementById(id).style.display = 'none';
        }

        function showAlert(message, type) {
            let alertBox = document.getElementById("alertBox");
            alertBox.textContent = message;
            alertBox.className = `alert-box alert-${type}`;
            alertBox.style.display = 'block';
            setTimeout(() => {
                alertBox.style.display = 'none';
            }, 3000);
        }

        $(document).ready(function () {
            let table = $('#kasTable').DataTable({
                "lengthMenu": [[10, 20, 25, 50, 75, 100], [10, 20, 25, 50, 75, 100]], // opsi jumlah data tampil
                "order": [[4, "desc"]], // sorting default berdasarkan kolom Tanggal (index 4 dimulai dari 0)
                "columnDefs": [
                    { "orderable": false, "targets": 6 } // kolom aksi tidak bisa di sort
                ],
                "language": {
                    "search": "Cari: ",
                    "lengthMenu": "Tampilkan _MENU_ data",
                    "zeroRecords": "Data tidak ditemukan",
                    "info": "Menampilkan _START_ sampai _END_ dari _TOTAL_ data",
                    "infoEmpty": "Tidak ada data tersedia",
                    "infoFiltered": "(disaring dari _MAX_ total data)"
                }
            });

            // Tambah
            $('#formTambah').submit(function (e) {
                e.preventDefault();
                $.post("{{ route('uang_kas.store') }}", $(this).serialize(), function (res) {
                    if (res.success) {
                        table.row.add([
                            table.rows().count() + 1,
                            res.data.jumlahkas,
                            res.data.idparameterkas,
                            res.data.type,
                            res.data.tanggal,
                            res.data.keterangan ?? '',
                            `<button class="btn btn-sm btn-warning btnEdit">Edit</button>
                 <button class="btn btn-sm btn-danger btnHapus">Hapus</button>`
                        ]).draw();

                        // ✅ Update kas awal
                        $('#kasAwal').text(new Intl.NumberFormat('id-ID').format(res.kas_awal));
                        $('#kasAwalModal').text(new Intl.NumberFormat('id-ID').format(res.kas_awal));

                        closeModal('modalTambah');
                        $('#formTambah')[0].reset();
                        showAlert("Data berhasil ditambahkan!", "success");
                    } else {
                        showAlert("Gagal menambahkan data!", "error");
                    }
                });
            });



            // Edit - tampilkan
            $('#kasTable').on('click', '.btnEdit', function () {
                let tr = $(this).closest('tr');
                $('#formEdit [name=id]').val(tr.data('id'));
                $('#formEdit [name=jumlahkas]').val(tr.find('td:eq(1)').text());
                $('#formEdit [name=idparameterkas]').val(tr.find('td:eq(2)').text());
                $('#formEdit [name=type]').val(tr.find('td:eq(3)').text());
                $('#formEdit [name=tanggal]').val(tr.find('td:eq(4)').text());
                $('#formEdit [name=keterangan]').val(tr.find('td:eq(5)').text());
                openModal('modalEdit');
            });

            // Edit - kirim
            // Edit - kirim
            $('#formEdit').submit(function (e) {
                e.preventDefault();
                let id = $('#formEdit [name=id]').val();
                $.post("/uang-kas/" + id, $(this).serialize() + '&_method=PUT', function (res) {
                    if (res.success) {
                        let tr = $('#kasTable tr[data-id="' + id + '"]');
                        table.row(tr).data([
                            tr.find('td:eq(0)').text(),
                            res.data.jumlahkas,
                            res.data.idparameterkas,
                            res.data.type,
                            res.data.tanggal,
                            res.data.keterangan ?? '',
                            `<button class="btn btn-sm btn-warning btnEdit">Edit</button>
                 <button class="btn btn-sm btn-danger btnHapus">Hapus</button>`
                        ]).draw();

                        // ✅ Update kas awal
                        $('#kasAwal').text(new Intl.NumberFormat('id-ID').format(res.kas_awal));
                        $('#kasAwalModal').text(new Intl.NumberFormat('id-ID').format(res.kas_awal));

                        closeModal('modalEdit');
                        showAlert("Data berhasil diupdate!", "success");
                    } else {
                        showAlert("Gagal update data!", "error");
                    }
                });
            });


            // Hapus
            // Hapus
            $('#kasTable').on('click', '.btnHapus', function () {
                if (confirm('Yakin hapus data ini?')) {
                    let tr = $(this).closest('tr');
                    let id = tr.data('id');
                    $.post("/uang-kas/" + id, { _method: 'DELETE', _token: '{{ csrf_token() }}' }, function (res) {
                        if (res.success) {
                            table.row(tr).remove().draw();

                            // ✅ Update kas awal
                            $('#kasAwal').text(new Intl.NumberFormat('id-ID').format(res.kas_awal));
                            $('#kasAwalModal').text(new Intl.NumberFormat('id-ID').format(res.kas_awal));
                            showAlert("Data berhasil dihapus!", "success");
                        } else {
                            showAlert("Gagal menghapus data!", "error");
                        }
                    });
                }
            });

            $('#ketTambah').on('change', function () {
                $('#paramTambah').val($(this).val());
            });
            $('#ketEdit').on('change', function () {
                $('#paramEdit').val($(this).val());
            });
        });
    </script>
</body>

</html>