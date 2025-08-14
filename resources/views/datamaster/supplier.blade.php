<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="{{ asset('css/datamaster/supplier.css') }}">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.5/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.1/css/buttons.dataTables.min.css">
    <link href="https://fonts.googleapis.com/css?family=Montserrat:400,800" rel="stylesheet">
    <title>Data Supplier</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>

<body>
    @include('partials.navbar')
    <div id="toast" class="toast"></div>
    <div id="alertBox" class="alert"></div>

    <h2>DATA SUPPLIER</h2>
    <button class="btn-add" onclick="openModal('create')">+ Tambah Supplier</button>

    <table id="supplierTable">
        <thead>
            <tr>
                <th>Kode Supplier</th>
                <th>Nama Supplier</th>
                <th>Alamat</th>
                <th>Kontak</th>
                <th>Email</th>
                <th>Keterangan</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach($suppliers as $s)
                <tr data-id="{{ $s->kdsupplier }}">
                    <td>{{ $s->kdsupplier }}</td>
                    <td>{{ $s->namasupplier }}</td>
                    <td>{{ $s->alamat }}</td>
                    <td>{{ $s->hp }}</td>
                    <td>{{ $s->email }}</td>
                    <td>{{ $s->ket }}</td>
                    <td>
                        <button class="btn-edit" onclick="openModal('edit', this)">Edit</button>
                        <button class="btn-delete" onclick="deleteSupplier('{{ $s->kdsupplier }}')">Hapus</button>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <!-- Modal Form -->
    <div id="modalForm" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeModal()">&times;</span>
            <h3 id="modalTitle">Tambah Supplier</h3>
            <form id="supplierForm">
                <input type="hidden" id="formType" value="create">

                <label>Kode Supplier</label>
                <input type="text" id="kdsupplier" required>

                <label>Nama Supplier</label>
                <input type="text" id="namasupplier">

                <label>Alamat</label>
                <textarea id="alamat"></textarea>

                <label>Kontak (HP)</label>
                <input type="text" id="hp">

                <label>Email</label>
                <input type="email" id="email">

                <label>Keterangan</label>
                <textarea id="ket"></textarea>

                <button type="submit" style="background:#007bff;color:white;border:none;">Simpan</button>
            </form>
        </div>
    </div>

    <!-- JS -->
    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.5/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.1/js/dataTables.buttons.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.html5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.print.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.js"></script>

    <script>
        let modal = document.getElementById('modalForm');
        let formType = document.getElementById('formType');
        let editId = null;

        function openModal(type, btn = null) {
            formType.value = type;
            if (type === 'create') {
                document.getElementById('modalTitle').innerText = 'Tambah Supplier';
                document.getElementById('supplierForm').reset();
                editId = null;
                document.getElementById('kdsupplier').readOnly = false;
            } else {
                document.getElementById('modalTitle').innerText = 'Edit Supplier';
                let row = btn.closest('tr');
                editId = row.dataset.id;
                let cells = row.querySelectorAll('td');
                document.getElementById('kdsupplier').value = cells[0].innerText;
                document.getElementById('namasupplier').value = cells[1].innerText;
                document.getElementById('alamat').value = cells[2].innerText;
                document.getElementById('hp').value = cells[3].innerText;
                document.getElementById('email').value = cells[4].innerText;
                document.getElementById('ket').value = cells[5].innerText;
                document.getElementById('kdsupplier').readOnly = true;
            }
            modal.style.display = 'block';
        }

        function closeModal() {
            modal.style.display = 'none';
        }

        document.getElementById('supplierForm').addEventListener('submit', function(e) {
            e.preventDefault();
            let data = {
                kdsupplier: document.getElementById('kdsupplier').value,
                namasupplier: document.getElementById('namasupplier').value,
                alamat: document.getElementById('alamat').value,
                hp: document.getElementById('hp').value,
                email: document.getElementById('email').value,
                ket: document.getElementById('ket').value
            };

            let url = '';
            let method = '';
            if (formType.value === 'create') {
                url = '/TAMBAH';
                method = 'POST';
            } else {
                url = '/UPDATE/' + editId;
                method = 'PUT';
            }

            fetch(url, {
                method: method,
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify(data)
            })
            .then(res => res.json())
            .then(res => {
                if (res.success) {
                    closeModal();
                    showAlert(formType.value === 'create' ? 'Data kamu sudah ditambahkan!' : 'Data kamu sudah diedit!', formType.value);
                    setTimeout(() => location.reload(), 1200);
                }
            });
        });

        function deleteSupplier(id) {
            if (confirm('Yakin hapus supplier ini?')) {
                fetch('/HAPUS/' + id, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    }
                })
                .then(res => res.json())
                .then(res => {
                    if (res.success) {
                        showAlert('Data kamu sudah dihapus!', 'delete');
                        setTimeout(() => location.reload(), 1200);
                    }
                });
            }
        }

        function showAlert(message, type = 'success') {
            let alertBox = document.getElementById('alertBox');
            alertBox.textContent = message;
            alertBox.className = 'alert ' + type + ' show';
            setTimeout(() => {
                alertBox.classList.remove('show');
            }, 3000);
        }

        $(document).ready(function() {
            $('#supplierTable').DataTable({
                dom: 'Bfrtip',
                buttons: [
                    'excelHtml5', 'csvHtml5', 'pdfHtml5', 'print'
                ],
                columnDefs: [
                    { orderable: false, targets: -1 } // kolom aksi tidak bisa sort
                ]
            });
        });
    </script>
</body>
</html>
