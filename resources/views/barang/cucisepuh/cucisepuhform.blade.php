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
                    <th>Nama</th>
                    <th>Barang</th>
                    <th>Berat (gr)</th>
                    <th>Karat</th>
                    <th>Tanggal Masuk</th>
                    <th>Status</th>
                    <th>Ongkos (Rp)</th>
                    <th>Total (Rp)</th>
                    <th>Metode</th>
                    <th>Foto</th>
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
                        <td>{{ number_format($f->ongkos_cuci, 2) }}</td>
                        <td>{{ number_format($f->total_bayar, 2) }}</td>
                        <td>{{ ucfirst($f->metode_bayar) }}</td>
                        <td>
                            @if($f->foto_barang)
                                <img src="{{ asset('storage/cuci_sepuh/' . $f->foto_barang) }}" width="60" alt="Foto">
                            @else
                                <span class="text-muted">-</span>
                            @endif
                        </td>
                        <td>
                            <button type="button" class="btn btn-success btn-sm" data-bs-toggle="modal"
                                data-bs-target="#editModal" data-id="{{ $f->id_cuci }}" data-nama="{{ $f->nama_pelanggan }}"
                                data-barang="{{ $f->jenis_barang }}" data-berat="{{ $f->berat }}"
                                data-karat="{{ $f->karat }}" data-tanggal="{{ $f->tanggal_cuci }}"
                                data-status="{{ $f->status }}" data-ongkos="{{ $f->ongkos_cuci }}"
                                data-total="{{ $f->total_bayar }}" data-metode="{{ $f->metode_bayar }}"
                                data-catatan="{{ $f->catatan }}">
                                <i class="fa fa-edit"></i>
                            </button>
                            <button type="button" class="btn btn-danger btn-sm" data-bs-toggle="modal"
                                data-bs-target="#deleteModal" data-id="{{ $f->id_cuci }}">
                                <i class="fa fa-trash"></i>
                            </button>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="9">Belum ada data</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Modal Create -->
    <div class="modal fade" id="formModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-scrollable"> <!-- tambahkan class ini -->
            <div class="modal-content">
                <form id="cuciSepuhForm" method="POST" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" id="formMethod" name="_method" value="POST">
                    <div class="modal-header">
                        <h3><i class="fa fa-ring"></i> <span id="modalTitle">Tambah Formulir</span></h3>
                        <span class="close" data-bs-dismiss="modal">&times;</span>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label>Nama Pelanggan</label>
                            <input type="text" name="nama_pelanggan" id="nama_pelanggan" class="form-control" required>
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
                                <input type="number" step="0.001" name="berat" id="berat" class="form-control" required>
                            </div>
                            <div class="form-group">
                                <label>Karat</label>
                                <input type="number" step="0.001" name="karat" id="karat" class="form-control" required>
                            </div>
                        </div>
                        <div class="form-group">
                            <label>Tanggal Masuk</label>
                            <input type="date" name="tanggal_cuci" id="tanggal_cuci" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label>Status</label>
                            <select name="status" id="status" class="form-control" required>
                                <option value="pending">Pending</option>
                                <option value="proses">Proses</option>
                                <option value="selesai">Selesai</option>
                            </select>
                        </div>
                        <div class="form-grid">
                            <div class="form-group">
                                <label>Ongkos Cuci (Rp)</label>
                                <input type="number" step="0.01" name="ongkos_cuci" id="ongkos_cuci"
                                    class="form-control">
                            </div>
                            <div class="form-group">
                                <label>Total Bayar (Rp)</label>
                                <input type="number" step="0.01" name="total_bayar" id="total_bayar"
                                    class="form-control">
                            </div>
                        </div>

                        <div class="form-group">
                            <label>Metode Bayar</label>
                            <select name="metode_bayar" id="metode_bayar" class="form-control">
                                <option value="cash">Cash</option>
                                <option value="transfer">Transfer</option>
                                <option value="qris">QRIS</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label>Catatan</label>
                            <textarea name="catatan" id="catatan" class="form-control"></textarea>
                        </div>
                        <div class="form-group">
                            <label>Foto Barang</label>
                            <input type="file" name="foto_barang" id="foto_barang" class="form-control" accept="image/*"
                                onchange="previewFoto(event)">
                            <div class="mt-2">
                                <img id="preview" src="" width="120"
                                    style="display:none;border:1px solid #ccc;padding:3px;">
                            </div>
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

    <!-- Modal Edit -->
    <div class="modal fade" id="editModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-scrollable"> <!-- tambahkan class ini -->
            <div class="modal-content">
                <form id="editForm" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="modal-header">
                        <h3><i class="fa fa-pen"></i> Edit Formulir</h3>
                        <span class="close" data-bs-dismiss="modal">&times;</span>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" id="edit_id">
                        <div class="form-group">
                            <label>Nama Pelanggan</label>
                            <input type="text" name="nama_pelanggan" id="edit_nama_pelanggan" class="form-control"
                                required>
                        </div>
                        <div class="form-group">
                            <label>Barang</label>
                            <input type="text" name="jenis_barang" id="edit_jenis_barang" class="form-control" required>
                        </div>
                        <div class="form-grid">
                            <div class="form-group">
                                <label>Berat (gr)</label>
                                <input type="number" step="0.001" name="berat" id="edit_berat" class="form-control"
                                    required>
                            </div>
                            <div class="form-group">
                                <label>Karat</label>
                                <input type="number" step="0.001" name="karat" id="edit_karat" class="form-control"
                                    required>
                            </div>
                        </div>
                        <div class="form-group">
                            <label>Tanggal Masuk</label>
                            <input type="date" name="tanggal_cuci" id="edit_tanggal_cuci" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label>Status</label>
                            <select name="status" id="edit_status" class="form-control" required>
                                <option value="pending">Pending</option>
                                <option value="proses">Proses</option>
                                <option value="selesai">Selesai</option>
                            </select>
                        </div>
                        <div class="form-grid">
                            <div class="form-group">
                                <label>Ongkos Cuci (Rp)</label>
                                <input type="number" step="0.01" name="ongkos_cuci" id="edit_ongkos_cuci"
                                    class="form-control">
                            </div>
                            <div class="form-group">
                                <label>Total Bayar (Rp)</label>
                                <input type="number" step="0.01" name="total_bayar" id="edit_total_bayar"
                                    class="form-control">
                            </div>
                        </div>

                        <div class="form-group">
                            <label>Metode Bayar</label>
                            <select name="metode_bayar" id="edit_metode_bayar" class="form-control">
                                <option value="cash">Cash</option>
                                <option value="transfer">Transfer</option>
                                <option value="qris">QRIS</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label>Catatan</label>
                            <textarea name="catatan" id="edit_catatan" class="form-control"></textarea>
                        </div>
                        <div class="form-group">
                            <label>Foto Barang</label>
                            <input type="file" name="foto_barang" id="edit_foto_barang" class="form-control"
                                accept="image/*" onchange="previewFotoEdit(event)">
                            <div class="mt-2">
                                <img id="edit_preview" src="" width="120"
                                    style="display:none;border:1px solid #ccc;padding:3px;">
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-success">Update</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal Delete -->
    <div class="modal fade" id="deleteModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-sm">
            <div class="modal-content">
                <form id="deleteForm" method="POST">
                    @csrf
                    @method('DELETE')
                    <div class="modal-body text-center">
                        <h5>Yakin ingin hapus data ini?</h5>
                    </div>
                    <div class="modal-footer justify-content-center">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-danger">Hapus</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        // tambah
        function openCreateModal() {
            const form = document.getElementById('cuciSepuhForm');
            form.action = "{{ route('cucisepuh.store') }}";
            document.getElementById('formMethod').value = 'POST';
            document.getElementById('btnSubmit').innerText = 'Simpan';

            // reset
            document.getElementById('nama_pelanggan').value = '';
            document.getElementById('jenis_barang').value = '';
            document.getElementById('berat').value = '';
            document.getElementById('karat').value = '';
            document.getElementById('tanggal_cuci').value = new Date().toISOString().slice(0, 10);
            document.getElementById('status').value = 'pending';
            document.getElementById('preview').style.display = 'none';
        }

        // edit
        const editModal = document.getElementById('editModal');
        editModal.addEventListener('show.bs.modal', function (event) {
            const button = event.relatedTarget;
            const id = button.getAttribute('data-id');
            const nama = button.getAttribute('data-nama');
            const barang = button.getAttribute('data-barang');
            const berat = button.getAttribute('data-berat');
            const karat = button.getAttribute('data-karat');
            const tanggal = button.getAttribute('data-tanggal');
            const status = button.getAttribute('data-status');
            const ongkos = button.getAttribute('data-ongkos');
            const total = button.getAttribute('data-total');
            const metode = button.getAttribute('data-metode');
            const catatan = button.getAttribute('data-catatan');

            const form = editModal.querySelector('#editForm');
            form.action = `/cucisepuhform/${id}`;

            editModal.querySelector('#edit_nama_pelanggan').value = nama;
            editModal.querySelector('#edit_jenis_barang').value = barang;
            editModal.querySelector('#edit_berat').value = parseFloat(berat).toFixed(3);
            editModal.querySelector('#edit_karat').value = parseFloat(karat).toFixed(3);
            editModal.querySelector('#edit_tanggal_cuci').value = tanggal;
            editModal.querySelector('#edit_status').value = status;
            editModal.querySelector('#edit_ongkos_cuci').value = ongkos;
            editModal.querySelector('#edit_total_bayar').value = total;
            editModal.querySelector('#edit_metode_bayar').value = metode;
            editModal.querySelector('#edit_catatan').value = catatan;

            // kosongkan preview foto, biar user bisa ganti
            document.getElementById('edit_preview').style.display = 'none';
        });

        // delete
        const deleteModal = document.getElementById('deleteModal');
        deleteModal.addEventListener('show.bs.modal', function (event) {
            const button = event.relatedTarget;
            const id = button.getAttribute('data-id');
            const form = deleteModal.querySelector('#deleteForm');
            form.action = `/cucisepuhform/${id}`;
        });

        function previewFoto(event) {
            let img = document.getElementById('preview');
            img.src = URL.createObjectURL(event.target.files[0]);
            img.style.display = 'block';
        }
        function previewFotoEdit(event) {
            let img = document.getElementById('edit_preview');
            img.src = URL.createObjectURL(event.target.files[0]);
            img.style.display = 'block';
        }

        // auto isi berat & karat saat pilih barang
        document.getElementById('jenis_barang').addEventListener('input', function () {
            const input = this.value;
            const options = document.querySelectorAll('#barangList option');
            let found = false;

            options.forEach(option => {
                if (option.value === input) {
                    document.getElementById('berat').value = option.getAttribute('data-berat');
                    document.getElementById('karat').value = option.getAttribute('data-karat');
                    found = true;
                }
            });

            // kalau barang tidak ditemukan, reset nilai
            if (!found) {
                document.getElementById('berat').value = '';
                document.getElementById('karat').value = '';
            }
        });
    </script>

</body>

</html>