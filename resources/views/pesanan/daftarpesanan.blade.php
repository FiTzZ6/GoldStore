<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Pesanan</title>
    <link rel="stylesheet" href="{{ asset('css/pesanan/daftarpesanan.css') }}">
</head>

<body>
    @include('partials.navbar')

    <main class="container">
        <h1>Pesanan</h1>

        {{-- 🔹 Alert sukses & error --}}
        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        @if(session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif
        @if($errors->any())
            <div class="alert alert-danger">
                <ul style="margin:0; padding-left:18px;">
                    @foreach($errors->all() as $err)
                        <li>{{ $err }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <table class="table">
            <thead>
                <tr>
                    <th>No Pesan</th>
                    <th>Tanggal Pesan</th>
                    <th>Tanggal Ambil</th>
                    <th>Nama Pemesan</th>
                    <th>Alamat Pemesan</th>
                    <th>No.Telp Pemesan</th>
                    <th>Staff</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($pesanan as $p)
                    <tr>
                        <td>{{ $p->nofakturpesan }}</td>
                        <td>{{ $p->tglpesan }}</td>
                        <td>{{ $p->tglambil ?? '-' }}</td>
                        <td>{{ $p->namapemesan }}</td>
                        <td>{{ $p->alamatpemesan }}</td>
                        <td>{{ $p->notelp }}</td>
                        <td>{{ $p->staff }}</td>
                        <td>
                            <!-- Tombol Edit -->
                            <button class="btn btn-sm btn-primary edit-btn" data-id="{{ $p->id }}"
                                data-nofaktur="{{ $p->nofakturpesan }}" data-tglpesan="{{ $p->tglpesan }}"
                                data-tglambil="{{ $p->tglambil }}" data-namapemesan="{{ $p->namapemesan }}"
                                data-alamatpemesan="{{ $p->alamatpemesan }}" data-notelp="{{ $p->notelp }}"
                                data-staff="{{ $p->staff }}">
                                Edit
                            </button>

                            <!-- Tombol Hapus -->
                            <form action="{{ route('pesanan.destroy', $p->id) }}" method="POST" style="display:inline">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger"
                                    onclick="return confirm('Yakin hapus pesanan ini?')">
                                    Hapus
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="text-center">Belum ada pesanan</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <!-- 🔹 Modal Edit -->
        <div id="editModal" class="modal">
            <div class="modal-content">
                <span class="close">&times;</span>
                <h2>Edit Pesanan</h2>

                <form id="editForm" method="POST">
                    @csrf
                    @method('PUT')

                    <input type="hidden" name="id" id="edit-id">

                    <div class="form-group">
                        <label>No Faktur</label>
                        <input type="text" id="edit-nofaktur" disabled>
                    </div>

                    <div class="form-group">
                        <label>Tanggal Pesan</label>
                        <input type="date" name="tglpesan" id="edit-tglpesan" required>
                    </div>

                    <div class="form-group">
                        <label>Tanggal Ambil</label>
                        <input type="date" name="tglambil" id="edit-tglambil">
                    </div>

                    <div class="form-group">
                        <label>Nama Pemesan</label>
                        <input type="text" name="namapemesan" id="edit-namapemesan" required>
                    </div>

                    <div class="form-group">
                        <label>Alamat Pemesan</label>
                        <textarea name="alamatpemesan" id="edit-alamatpemesan"></textarea>
                    </div>

                    <div class="form-group">
                        <label>No. Telp</label>
                        <input type="text" name="notelp" id="edit-notelp">
                    </div>

                    <div class="form-group">
                        <label>Staff</label>
                        <input type="text" name="staff" id="edit-staff">
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary close">Batal</button>
                        <button type="submit" class="btn btn-primary">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </main>

    <style>
        /* Modal Styling */
        .modal {
            display: none;
            position: fixed;
            z-index: 1000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
        }

        .modal-content {
            background: #fff;
            margin: 5% auto;
            padding: 20px;
            border-radius: 8px;
            width: 420px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.3);
            animation: fadeIn 0.3s ease-in-out;
            position: relative;
        }

        .modal-content h2 {
            margin-top: 0;
            margin-bottom: 15px;
        }

        .close {
            position: absolute;
            right: 12px;
            top: 8px;
            font-size: 22px;
            cursor: pointer;
            color: #555;
        }

        .form-group {
            margin-bottom: 12px;
        }

        .form-group input,
        .form-group textarea {
            width: 100%;
            padding: 6px 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }

        .modal-footer {
            display: flex;
            justify-content: flex-end;
            gap: 10px;
            margin-top: 15px;
        }

        .btn-secondary {
            background-color: #ccc;
            border: none;
            padding: 6px 12px;
            border-radius: 4px;
            cursor: pointer;
        }

        .btn-secondary:hover {
            background-color: #bbb;
        }

        .alert {
            padding: 10px 15px;
            border-radius: 4px;
            margin-bottom: 15px;
        }

        .alert-success {
            background-color: #d4edda;
            color: #155724;
        }

        .alert-danger {
            background-color: #f8d7da;
            color: #721c24;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
    </style>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const modal = document.getElementById('editModal');
            const closeBtns = modal.querySelectorAll('.close');
            const form = document.getElementById('editForm');

            // Buka modal
            document.querySelectorAll('.edit-btn').forEach(btn => {
                btn.addEventListener('click', () => {
                    modal.style.display = 'block';

                    // isi data
                    document.getElementById('edit-id').value = btn.dataset.id;
                    document.getElementById('edit-nofaktur').value = btn.dataset.nofaktur;
                    document.getElementById('edit-tglpesan').value = btn.dataset.tglpesan;
                    document.getElementById('edit-tglambil').value = btn.dataset.tglambil;
                    document.getElementById('edit-namapemesan').value = btn.dataset.namapemesan;
                    document.getElementById('edit-alamatpemesan').value = btn.dataset.alamatpemesan;
                    document.getElementById('edit-notelp').value = btn.dataset.notelp;
                    document.getElementById('edit-staff').value = btn.dataset.staff;

                    // set action form
                    form.action = `/pesanan/${btn.dataset.id}`;
                });
            });

            // Close modal
            closeBtns.forEach(btn => {
                btn.addEventListener('click', () => modal.style.display = 'none');
            });
            window.addEventListener('click', e => {
                if (e.target == modal) modal.style.display = 'none';
            });
        });
    </script>
</body>

</html>