<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Transaksi Pembelian Umum</title>
    <link rel="stylesheet" href="{{ asset('css/beli/transaksi_beli.css') }}">
</head>

<body>
    @include('partials.navbar')
    <div class="container">
        <div class="header">
            <div class="left">
                <label>No. Faktur</label>
                <input type="text" value="FB-1250807-0001" readonly>
            </div>
            <div class="center">
                <h3>Transaksi Pembelian Umum</h3>
                <p>SAMBAS HOLDING</p>
            </div>
            <div class="right">
                <label>TOKO</label>
                <input type="text" value="HLD" readonly>
            </div>
        </div>

        <hr>

        @if ($errors->any())
            <script>
                alert("{{ implode('\n', $errors->all()) }}");
            </script>
        @endif

        @if(session('error'))
            <script>
                alert("{{ session('error') }}");
            </script>
        @endif

        @if(session('success'))
            <script>
                alert("{{ session('success') }}");
            </script>
        @endif

        {{-- 🔹 FORM GET UNTUK CEK BARCODE --}}
        <form method="GET" action="{{ route('transaksibeli') }}">
            <div class="form-row">
                <label>BARCODE</label>
                <input type="text" name="barcode" value="{{ old('barcode', $barcode ?? '') }}"
                    placeholder="Masukkan kode barang">
                <button type="submit">Cek Riwayat</button>
            </div>
        </form>

        {{-- 🔹 FORM GET UNTUK PILIH RIWAYAT --}}
        @if(isset($riwayat) && count($riwayat) > 0)
            <div class="form-section">
                <div class="form-row">
                    <h4 style="margin-bottom: 10px;">Pilih Riwayat Penjualan</h4>
                    <form method="GET" action="{{ route('transaksibeli') }}">
                        <input type="hidden" name="barcode" value="{{ $barcode }}">
                        <select name="riwayat_id" onchange="this.form.submit()">
                            <option value="">-- Pilih salah satu --</option>
                            @foreach($riwayat as $r)
                                <option value="{{ $r->id }}" {{ isset($selected) && $selected->id == $r->id ? 'selected' : '' }}>
                                    {{ $r->namapelanggan }} | {{ $r->nofaktur }} | {{ $r->created_at }}
                                </option>
                            @endforeach
                        </select>
                    </form>
                </div>
            </div>
        @endif

        {{-- 🔹 FORM POST UNTUK SIMPAN TRANSAKSI --}}
        <form method="POST" action="{{ route('transaksibeli.store') }}">
            @csrf
            <div class="form-section">
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
                    <label>NAMA PENJUAL</label>
                    <input type="text" name="namapenjual" value="{{ $selected->namapelanggan ?? '' }}"
                        placeholder="Nama Penjual">
                </div>
                <div class="form-row">
                    <label>ALAMAT</label>
                    <input type="text" name="alamat" value="{{ $selected->alamat ?? '' }}" placeholder="Alamat">
                </div>
                <div class="form-row">
                    <label>No. Telp</label>
                    <input type="text" name="notelp" value="{{ $selected->nohp ?? '' }}" placeholder="No. Telp">
                </div>
            </div>

            {{-- tabel barang --}}
            <table>
                <thead>
                    <tr>
                        <th>Faktur</th>
                        <th>Barcode</th>
                        <th>Deskripsi</th>
                        <th>Jenis</th>
                        <th>Kondisi</th>
                        <th>Berat Nota</th>
                        <th>Berat (Gram)</th>
                        <th>Harga Nota</th>
                        <th>Harga Beli</th>
                        <th>Harga Rata</th>
                        <th>Potongan</th>
                    </tr>
                </thead>
                <tbody>
                    @if($selected && $barang)
                        <tr>
                            {{-- hidden field untuk simpan --}}
                            <input type="hidden" name="barcode" value="{{ $selected->barcode }}">
                            <input type="hidden" name="nofaktur" value="{{ $selected->nofaktur }}">
                            <input type="hidden" name="deskripsi"
                                value="{{ $barang->barang->namabarang ?? $selected->namabarang }}">
                            <input type="hidden" name="jenis" value="{{ $barang->barang->JenisBarang->namajenis ?? '-' }}">
                            <input type="hidden" name="beratlama" value="{{ $selected->berat ?? $barang->berat ?? 0 }}">
                            <input type="hidden" name="hargalama" value="{{ $selected->harga ?? 0 }}">

                            <td>{{ $selected->nofaktur }}</td>
                            <td>{{ $selected->barcode }}</td>
                            <td>{{ $barang->barang->namabarang ?? $selected->namabarang }}</td>
                            <td>{{ $barang->barang->JenisBarang->namajenis ?? '-' }}</td>
                            <td>
                                <select name="kondisi" required>
                                    <option value="">-- pilih --</option>
                                    <option value="baik">Baik</option>
                                    <option value="rusak">Rusak</option>
                                    <option value="hilang">Hilang</option>
                                </select>
                            </td>
                            <td>{{ $barang->berat }}</td>
                            <td><input type="number" step="0.001" name="beratbaru" placeholder="Isi berat baru" required>
                            </td>
                            <td>{{ $selected->harga }}</td>
                            <td><input type="number" step="0.001" name="hargabaru" placeholder="Isi harga baru" required>
                            </td>
                            <td><input type="number" step="0.001" name="hargarata" placeholder="Isi harga rata-rata"
                                    required></td>
                            <td><input type="number" step="0.001" name="potongan" placeholder="Potongan customer" required>
                            </td>
                        </tr>
                    @else
                        <tr>
                            <td colspan="11" style="text-align:center">Belum ada data</td>
                        </tr>
                    @endif
                </tbody>
            </table>

            <div class="total">
                TOTAL: <input type="number" id="total" name="total" readonly>
            </div>
            <div class="buttons">
                <button type="reset" class="reset">RESET</button>
                <button type="submit" class="save">💾 SIMPAN</button>
            </div>
        </form>
    </div>

    <script>
        const hargarata = document.querySelector('input[name="hargarata"]');
        const potongan = document.querySelector('input[name="potongan"]');
        const total = document.getElementById('total');

        function hitungTotal() {
            let rata = parseFloat(hargarata?.value) || 0;
            let diskon = parseFloat(potongan?.value) || 0;
            let hasil = rata - diskon;
            if (hasil < 0) {
                alert("Total tidak boleh negatif!");
            }
            total.value = hasil;
        }

        hargarata?.addEventListener('input', hitungTotal);
        potongan?.addEventListener('input', hitungTotal);
    </script>
</body>

</html>