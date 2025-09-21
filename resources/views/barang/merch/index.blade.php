<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Merchandise</title>
    <link rel="stylesheet" href="{{ asset('css/barang/merch/merch.css') }}">
</head>

<body>
    @include('partials.navbar')

    <div class="container">
        <div class="header">
            <h1>Daftar Merchandise</h1>
            <div style="display:flex; gap:10px; margin-top:10px;">
                <button onclick="openCreateModal()"
                    style="padding:8px 14px; border:none; border-radius:6px; background:linear-gradient(135deg,#2c3e50,#424242); color:white; cursor:pointer;">
                    + Tambah Merchandise
                </button>

                <a href="{{ route('merch.redeemHistory') }}">
                    <button
                        style="padding:8px 14px; border:none; border-radius:6px; background:linear-gradient(135deg,#007bff,#0056b3); color:white; cursor:pointer;">
                        📜 Riwayat Redeem
                    </button>
                </a>
            </div>
        </div>

        {{-- Notifikasi --}}
        @if(session('success'))
            <div style="color: green; margin: 10px 0; font-weight: bold;">
                {{ session('success') }}
            </div>
        @endif
        @if(session('error'))
            <div style="color: red; margin: 10px 0; font-weight: bold;">
                {{ session('error') }}
            </div>
        @endif

        {{-- Tabel Merchandise --}}
        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th>Nama</th>
                        <th>Deskripsi</th>
                        <th>Harga Poin</th>
                        <th>Stok</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($merchandise as $m)
                        <tr>
                            <td>{{ $m->nama_merch }}</td>
                            <td>{{ $m->deskripsi }}</td>
                            <td>{{ $m->poin_harga }}</td>
                            <td>{{ $m->stok }}</td>
                            <td style="display:flex; gap:5px;">
                                <button
                                    onclick="openEditModal({{ $m->id }}, '{{ $m->nama_merch }}', '{{ $m->deskripsi }}', '{{ $m->poin_harga }}', '{{ $m->stok }}')"
                                    style="padding:5px 10px; background:#ffc107; border:none; border-radius:4px; cursor:pointer; color:#fff;">
                                    Edit
                                </button>

                                <form action="{{ route('merch.destroy', $m->id) }}" method="POST"
                                    onsubmit="return confirm('Yakin hapus merch ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                        style="padding:5px 10px; background:#dc3545; border:none; border-radius:4px; cursor:pointer; color:#fff;">
                                        Hapus
                                    </button>
                                </form>

                                <button
                                    onclick="openRedeemModal({{ $m->id }}, '{{ $m->nama_merch }}', '{{ $m->poin_harga }}')"
                                    style="padding:5px 10px; background:#28a745; border:none; border-radius:4px; cursor:pointer; color:#fff;">
                                    Redeem
                                </button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    {{-- Modal Tambah/Edit Merchandise --}}
    <div id="merchModal" style="display:none; position:fixed; top:0; left:0; width:100%; height:100%; 
    background:rgba(0,0,0,0.5); justify-content:center; align-items:center;">
        <div style="background:#fff; padding:20px; border-radius:8px; width:400px; position:relative;">
            <h3 id="modalTitle">Tambah Merchandise</h3>
            <form id="merchForm" method="POST">
                @csrf
                <input type="hidden" name="_method" id="formMethod" value="POST">

                <div style="margin:10px 0;">
                    <label>Nama</label>
                    <input type="text" name="nama_merch" id="nama_merch" required style="width:100%; padding:8px;">
                </div>

                <div style="margin:10px 0;">
                    <label>Deskripsi</label>
                    <textarea name="deskripsi" id="deskripsi" style="width:100%; padding:8px;"></textarea>
                </div>

                <div style="margin:10px 0;">
                    <label>Harga Poin</label>
                    <input type="number" name="poin_harga" id="poin_harga" required style="width:100%; padding:8px;">
                </div>

                <div style="margin:10px 0;">
                    <label>Stok</label>
                    <input type="number" name="stok" id="stok" required style="width:100%; padding:8px;">
                </div>

                <div style="display:flex; justify-content:end; gap:10px; margin-top:15px;">
                    <button type="button" onclick="closeModal()"
                        style="padding:6px 12px; background:#6c757d; color:white; border:none; border-radius:6px;">Batal</button>
                    <button type="submit" id="btnSubmit"
                        style="padding:6px 12px; background:#28a745; color:white; border:none; border-radius:6px;">Simpan</button>
                </div>
            </form>
        </div>
    </div>

    {{-- Modal Redeem --}}
    <div id="redeemModal" style="display:none; position:fixed; top:0; left:0; width:100%; height:100%;
    background:rgba(0,0,0,0.5); justify-content:center; align-items:center;">
        <div style="background:#fff; padding:20px; border-radius:8px; width:420px; position:relative;">
            <h3 id="redeemTitle">Redeem Merchandise</h3>
            <form id="redeemForm" method="POST" action="{{ route('merch.redeem') }}">
                @csrf

                <input type="hidden" name="merch_id" id="redeem_merch_id">

                <div style="margin:10px 0;">
                    <label>Merchandise</label>
                    <input type="text" id="redeem_merch_name" readonly
                        style="width:100%; padding:8px; background:#f0f0f0;">
                </div>

                <div style="margin:10px 0;">
                    <label>Harga Poin</label>
                    <input type="text" id="redeem_merch_poin" readonly
                        style="width:100%; padding:8px; background:#f0f0f0;">
                </div>

                <div style="margin:10px 0;">
                    <label>Pelanggan</label>
                    <input list="pelangganList" name="kdpelanggan" id="redeem_kdpelanggan" required
                        placeholder="Masukkan kode/nama pelanggan"
                        style="width:100%; padding:8px; border:1px solid #ccc; border-radius:6px;">
                    <datalist id="pelangganList">
                        @foreach($pelanggan as $p)
                            <option value="{{ $p->kdpelanggan }}">{{ $p->namapelanggan }}</option>
                        @endforeach
                    </datalist>
                </div>

                <div style="margin:10px 0;">
                    <label>Poin Pelanggan</label>
                    <input type="text" id="redeem_poinDisplay" readonly
                        style="width:100%; padding:8px; background:#f0f0f0;">
                </div>

                <div style="display:flex; justify-content:end; gap:10px; margin-top:15px;">
                    <button type="button" onclick="closeRedeemModal()"
                        style="padding:6px 12px; background:#6c757d; color:white; border:none; border-radius:6px;">Batal</button>
                    <button type="submit"
                        style="padding:6px 12px; background:#28a745; color:white; border:none; border-radius:6px;">Redeem</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        // Modal Tambah/Edit
        function openCreateModal() {
            document.getElementById('modalTitle').innerText = 'Tambah Merchandise';
            document.getElementById('merchForm').action = "{{ route('merch.store') }}";
            document.getElementById('formMethod').value = 'POST';
            document.getElementById('nama_merch').value = '';
            document.getElementById('deskripsi').value = '';
            document.getElementById('poin_harga').value = '';
            document.getElementById('stok').value = '';
            document.getElementById('btnSubmit').innerText = 'Simpan';
            document.getElementById('merchModal').style.display = 'flex';
        }

        function openEditModal(id, nama, deskripsi, poin, stok) {
            document.getElementById('modalTitle').innerText = 'Edit Merchandise';
            document.getElementById('merchForm').action = "/merch/update/" + id;
            document.getElementById('formMethod').value = 'PUT';
            document.getElementById('nama_merch').value = nama;
            document.getElementById('deskripsi').value = deskripsi;
            document.getElementById('poin_harga').value = poin;
            document.getElementById('stok').value = stok;
            document.getElementById('btnSubmit').innerText = 'Update';
            document.getElementById('merchModal').style.display = 'flex';
        }

        function closeModal() {
            document.getElementById('merchModal').style.display = 'none';
        }

        // Modal Redeem
        function openRedeemModal(id, nama, poin) {
            document.getElementById('redeem_merch_id').value = id;
            document.getElementById('redeem_merch_name').value = nama;
            document.getElementById('redeem_merch_poin').value = poin;
            document.getElementById('redeem_kdpelanggan').value = '';
            document.getElementById('redeem_poinDisplay').value = '';
            document.getElementById('redeemModal').style.display = 'flex';
        }

        function closeRedeemModal() {
            document.getElementById('redeemModal').style.display = 'none';
        }

        // Ambil poin pelanggan saat dipilih
        document.getElementById('redeem_kdpelanggan').addEventListener('change', function () {
            let kode = this.value;
            fetch('/pelanggan/get-poin/' + kode)
                .then(res => res.json())
                .then(data => {
                    document.getElementById('redeem_poinDisplay').value = data.poin ?? '0';
                })
                .catch(() => {
                    document.getElementById('redeem_poinDisplay').value = '0';
                });
        });
    </script>
</body>

</html>