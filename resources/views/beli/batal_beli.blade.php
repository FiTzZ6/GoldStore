<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Transaksi Batal Beli</title>
    <link rel="stylesheet" href="{{ asset('css/beli/batal_beli.css') }}">
</head>

<body>
    @include('partials.navbar')
    <div class="container">
        <div class="header">
            <div class="left">
                <label>No.Faktur</label>
                <input type="text" value="{{ $riwayat->nofaktur ?? '' }}" readonly>
            </div>
            <div class="center">
                <h3>Transaksi Batal Beli</h3>
                <p>SAMBAS HOLDING</p>
            </div>
            <div class="right">
                <label>Toko</label>
                <input type="text" value="{{ $riwayat->namapenjual ?? '' }}" readonly>
            </div>
        </div>

        <hr>

        @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-error">
                {{ session('error') }}
            </div>
        @endif
        <div class="form-section">
            <form method="GET" action="{{ route('batalbeli') }}">
                <div class="form-row">
                    <label for="staff-code">Nama Staff</label>
                    <input type="text" id="staff-code" name="staff" placeholder="Masukkan Nama Staff" list="list_staff"
                        required>
                    <datalist id="list_staff">
                        @foreach($staff as $karyawan)
                            <option value="{{ $karyawan->nama }}"></option>
                        @endforeach
                    </datalist>
                </div>
                <div class="form-row">
                    <label>No. Faktur Beli</label>
                    <div style="display:flex; gap:5px;">
                        <input type="text" name="nofakturbeli" value="{{ request('nofakturbeli') }}"
                            placeholder="Masukkan No Faktur Beli">
                        <button type="submit" class="btn-cari">Cari</button>
                    </div>
                    <span class="note">* isi nomor faktur beli lalu klik tombol cari</span>
                </div>
            </form>
        </div>

        <table>
            <thead>
                <tr>
                    <th>NO FAKTUR BELI</th>
                    <th>NAMA PENJUAL</th>
                    <th>KONDISI BELI</th>
                    <th>NAMA BARANG</th>
                    <th>BERAT</th>
                    <th>HARGA BELI</th>
                    <th>HARGA BATAL BELI</th>
                </tr>
            </thead>
            <tbody>
                @if($riwayat)
                    <tr>
                        <td>{{ $riwayat->nofakturbeli }}</td>
                        <td>{{ $riwayat->namapenjual }}</td>
                        <td>{{ $riwayat->kondisi }}</td>
                        <td>{{ $riwayat->deskripsi }}</td>
                        <td>{{ $riwayat->beratbaru }}</td>
                        <td>{{ number_format($riwayat->hargabaru, 0, ',', '.') }}</td>
                        <td>
                            {{-- Input harga batal beli ikut ke form simpan --}}
                            <input type="number" name="hargabatalbeli" form="form-simpan-batalbeli"
                                placeholder="Masukkan Harga Batal Beli" required>
                        </td>
                    </tr>
                @else
                    <tr>
                        <td colspan="7" class="empty-row">Data belum ada / No Faktur tidak ditemukan</td>
                    </tr>
                @endif
            </tbody>
        </table>

        <div class="form-section">
            <form id="form-simpan-batalbeli" method="POST" action="{{ route('batalbeli.store') }}">
                @csrf

                {{-- Hidden input ambil data hasil pencarian --}}
                <input type="hidden" name="namastaff" value="{{ request('staff') }}">
                <input type="hidden" name="nofakturbeli" value="{{ $riwayat->nofakturbeli ?? '' }}">
                <input type="hidden" name="namapenjual" value="{{ $riwayat->namapenjual ?? '' }}">
                <input type="hidden" name="namabarang" value="{{ $riwayat->deskripsi ?? '' }}">
                <input type="hidden" name="berat" value="{{ $riwayat->beratbaru ?? '' }}">
                <input type="hidden" name="hargabeli" value="{{ $riwayat->hargabaru ?? '' }}">
                <input type="hidden" name="kondisibeli" value="{{ $riwayat->kondisi ?? '' }}">

                <div class="form-row">
                    <label>KONDISI BARANG (Batal)</label>
                    <select name="kondisibatalbeli" required>
                        <option value="">Pilih Kondisi</option>
                        <option value="baik">Baik</option>
                        <option value="rusak">Rusak</option>
                        <option value="lecet">Lecet</option>
                        <option value="hilang">Hilang</option>
                    </select>
                </div>

                <div class="form-row">
                    <label>KETERANGAN BATAL</label>
                    <textarea name="keterangan"></textarea>
                </div>

                <div class="buttons">
                    <button type="reset" class="clear">CLEAR</button>
                    <div class="right-btn">
                        <button type="submit" class="save">SIMPAN</button>
                        <label><input type="checkbox" checked> Cetak Struk Batal Beli</label>
                    </div>
                </div>
            </form>
        </div>

    </div>
</body>

</html>