<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tukar Rupiah</title>
    <link rel="stylesheet" href="{{ asset('css/utility/tukar_duit/tukar.css') }}">
</head>

<body>
    @include('partials.navbar')

    <div class="container">
        <h2 class="mb-4">💰 Tukar Rupiah</h2>

        {{-- Button tambah kurs (admin) --}}
        @if(Session::get('typeuser') == 1)
            <button class="btn btn-primary mb-3" onclick="openModal('modalTambahKurs')">➕ Tambah Kurs</button>
        @endif

        {{-- Tabel Kurs --}}
        <h4>Daftar Kurs</h4>
        <div class="table-responsive">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Mata Uang</th>
                        <th>Nilai</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($kurs as $k)
                        <tr>
                            <td>{{ $k->mata_uang }}</td>
                            <td>{{ number_format($k->nilai, 2) }}</td>
                            <td>
                                @if(Session::get('typeuser') == 1)
                                    <button class="btn btn-warning btn-sm" onclick="openModal('modalEditKurs{{ $k->id }}')">✏️
                                        Edit</button>
                                    <form action="{{ route('tukar.kurs.destroy', $k->id) }}" method="POST"
                                        style="display:inline">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-danger btn-sm">🗑 Hapus</button>
                                    </form>
                                @endif
                            </td>
                        </tr>

                        {{-- Modal Edit Kurs --}}
                        @if(Session::get('typeuser') == 1)
                            <div id="modalEditKurs{{ $k->id }}" class="modal">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h3>Edit Kurs</h3>
                                        <span class="close" onclick="closeModal('modalEditKurs{{ $k->id }}')">&times;</span>
                                    </div>
                                    <form action="{{ route('tukar.kurs.update', $k->id) }}" method="POST">
                                        @csrf
                                        @method('PUT')
                                        <div class="modal-body">
                                            <label>Mata Uang</label>
                                            <input type="text" name="mata_uang" class="form-control"
                                                value="{{ $k->mata_uang }}">
                                            <label>Nilai Tukar</label>
                                            <input type="number" step="0.01" name="nilai" class="form-control"
                                                value="{{ $k->nilai }}">
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary"
                                                onclick="closeModal('modalEditKurs{{ $k->id }}')">Batal</button>
                                            <button class="btn btn-primary">Simpan</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        @endif
                    @endforeach
                </tbody>
            </table>
        </div>

        {{-- Form Transaksi --}}
        <h4 class="mt-5">Buat Transaksi</h4>
        <form action="{{ route('tukar.transaksi.store') }}" method="POST">
            @csrf
            <div class="form-grid">
                <div>
                    <label>Mata Uang</label>
                    <select name="mata_uang" class="form-control">
                        @foreach($kurs as $k)
                            <option value="{{ $k->mata_uang }}">
                                {{ $k->mata_uang }} ({{ number_format($k->nilai, 2) }})
                            </option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label>Jumlah</label>
                    <input type="number" step="0.01" name="jumlah" placeholder="Jumlah" class="form-control">
                </div>
                <div class="full-width text-right">
                    <button class="btn btn-success">💱 Tukar</button>
                </div>
            </div>
        </form>

        {{-- Riwayat Transaksi --}}
        <h4 class="mt-5">Riwayat Transaksi</h4>
        <div class="table-responsive">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Mata Uang</th>
                        <th>Jumlah</th>
                        <th>Total Rupiah</th>
                        <th>Tanggal</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($transaksi as $t)
                        <tr>
                            <td>{{ $t->mata_uang }}</td>
                            <td>{{ $t->jumlah }}</td>
                            <td>{{ number_format($t->total_rupiah, 2) }}</td>
                            <td>{{ $t->created_at }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    {{-- Modal Tambah Kurs --}}
    <div id="modalTambahKurs" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h3>Tambah Kurs</h3>
                <span class="close" onclick="closeModal('modalTambahKurs')">&times;</span>
            </div>
            <form action="{{ route('tukar.kurs.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <label>Mata Uang</label>
                    <input type="text" name="mata_uang" class="form-control" placeholder="Mata Uang">

                    <label>Nilai Tukar</label>
                    <input type="number" step="0.01" name="nilai" class="form-control" placeholder="Nilai Tukar">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary"
                        onclick="closeModal('modalTambahKurs')">Batal</button>
                    <button class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function openModal(id) {
            document.getElementById(id).style.display = "flex";
        }

        function closeModal(id) {
            document.getElementById(id).style.display = "none";
        }
    </script>
</body>

</html>