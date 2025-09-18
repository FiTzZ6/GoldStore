<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Formulir Cuci Sepuh Emas</title>
    <link rel="stylesheet" href="{{ asset('css/barang/cucisepuh/formcucisepuh.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</head>

<body>
    @include('partials.navbar')

    <div class="container">
        <div class="header">
            <h1><i class="fa-solid fa-ring"></i> Formulir Cuci Sepuh Emas</h1>
            <div class="header-info">
                <span class="info-item">Total Formulir: {{ $formulirs->count() }}</span>
            </div>
        </div>

        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <!-- Header & tombol tambah -->
        <div class="action-buttons">
            <button id="add-barang" data-bs-toggle="modal" data-bs-target="#formModal" onclick="openCreateModal()">
                <i class="fa fa-plus"></i> Tambah Formulir
            </button>
        </div>

        <!-- Tabel -->
        <table class="data-table">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Nama Pelanggan</th>
                    <th>Barang</th>
                    <th>Berat (gr)</th>
                    <th>Karat</th>
                    <th>Tanggal Masuk</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($formulirs as $f)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $f->nama_pelanggan }}</td>
                        <td>{{ $f->jenis_barang }}</td>
                        <td>{{ number_format($f->berat, 3) }}</td>
                        <td>{{ number_format($f->karat, 3) }}</td>
                        <td>{{ $f->tanggal_cuci }}</td>
                        <td>
                            <span class="badge @if($f->status == 'selesai') bg-success
                            @elseif($f->status == 'proses') bg-warning
                            @else bg-secondary @endif">
                                {{ ucfirst($f->status) }}
                            </span>
                        </td>
                        <td>
                            <button class="btn btn-success btn-sm" data-bs-toggle="modal" data-bs-target="#formModal"
                                onclick="openEditModal(@json($f))"><i class="fa fa-edit"></i></button>
                            <form action="{{ route('cucisepuh.destroy', $f->id_cuci) }}" method="POST"
                                style="display:inline-block">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-danger btn-sm" onclick="return confirm('Yakin hapus data?')">
                                    <i class="fa fa-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8">Belum ada data</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <!-- Modal Form -->
        <div class="modal fade" id="formModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <form id="cuciSepuhForm" method="POST">
                        @csrf
                        <input type="hidden" id="formMethod" name="_method" value="POST">
                        <div class="modal-header">
                            <h3><i class="fa fa-ring"></i> <span id="modalTitle">Tambah Formulir</span></h3>
                            <span class="close" data-bs-dismiss="modal">&times;</span>
                        </div>
                        <div class="modal-body">
                            <div class="form-group">
                                <label>Nama Pelanggan</label>
                                <input type="text" name="nama_pelanggan" id="nama_pelanggan" class="form-control"
                                    required>
                            </div>
                            <div class="form-group">
                                <label>Barang</label>
                                <input list="barangList" name="jenis_barang" id="jenis_barang" class="form-control"
                                    required>
                                <datalist id="barangList">
                                    @foreach($barangs as $barang)
                                        <option value="{{ $barang->namabarang }}"
                                            data-berat="{{ number_format($barang->berat, 3, '.', '') }}"
                                            data-karat="{{ number_format($barang->kadar, 3, '.', '') }}">
                                    @endforeach
                                </datalist>
                            </div>
                            <div class="form-grid">
                                <div class="form-group">
                                    <label>Berat (gr)</label>
                                    <input type="number" name="berat" id="berat" class="form-control" step="0.001"
                                        required>
                                </div>
                                <div class="form-group">
                                    <label>Karat</label>
                                    <input type="number" name="karat" id="karat" class="form-control" step="0.001"
                                        required>
                                </div>
                            </div>
                            <div class="form-group full-width">
                                <label>Tanggal Masuk</label>
                                <input type="date" name="tanggal_cuci" id="tanggal_cuci" class="form-control" required>
                            </div>
                            <div class="form-group full-width">
                                <label>Status</label>
                                <select name="status" id="status" class="form-control">
                                    <option value="pending">Pending</option>
                                    <option value="proses">Proses</option>
                                    <option value="selesai">Selesai</option>
                                </select>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                            <button type="submit" class="btn btn-primary" id="btnSubmit">Simpan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <script>
            const form = document.getElementById('cuciSepuhForm');
            const modalTitle = document.getElementById('modalTitle');
            const btnSubmit = document.getElementById('btnSubmit');
            const formMethod = document.getElementById('formMethod');

            const fields = {
                nama_pelanggan: document.getElementById('nama_pelanggan'),
                jenis_barang: document.getElementById('jenis_barang'),
                berat: document.getElementById('berat'),
                karat: document.getElementById('karat'),
                tanggal_cuci: document.getElementById('tanggal_cuci'),
                status: document.getElementById('status')
            };

            // Buka modal untuk tambah
            function openCreateModal() {
                modalTitle.innerText = 'Tambah Formulir';
                form.action = "{{ route('cucisepuh.store') }}";
                formMethod.value = 'POST';
                btnSubmit.innerText = 'Simpan';

                // Reset semua field
                for (let key in fields) {
                    if (key === 'tanggal_cuci') {
                        fields[key].value = new Date().toISOString().slice(0, 10);
                    } else if (key === 'status') {
                        fields[key].value = 'pending';
                    } else {
                        fields[key].value = '';
                    }
                }
            }

            // Buka modal untuk edit
            function openEditModal(data) {
                modalTitle.innerText = 'Edit Formulir';
                form.action = `/cucisepuhform/${data.id_cuci}`; // pastikan URL sesuai route
                formMethod.value = 'PUT';
                btnSubmit.innerText = 'Update';

                fields.nama_pelanggan.value = data.nama_pelanggan;
                fields.jenis_barang.value = data.jenis_barang;
                fields.berat.value = parseFloat(data.berat).toFixed(3);
                fields.karat.value = parseFloat(data.karat).toFixed(3);
                fields.tanggal_cuci.value = data.tanggal_cuci;
                fields.status.value = data.status;
            }

            // Auto-fill berat & karat saat pilih barang dari datalist
            fields.jenis_barang.addEventListener('input', function () {
                const val = this.value;
                const option = Array.from(document.querySelectorAll('#barangList option')).find(o => o.value === val);
                if (option) {
                    fields.berat.value = option.dataset.berat;
                    fields.karat.value = option.dataset.karat;
                }
            });
        </script>

</body>

</html>