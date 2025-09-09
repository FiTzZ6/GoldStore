<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Transaksi Service - Sistem Manajemen</title>
    <link rel="stylesheet" href="{{ asset('css/service/transaksiservice.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>

<body>
    @include('partials.navbar')

    @if(session('success'))
        <div class="alert alert-success">
            <i class="fas fa-check-circle"></i> {{ session('success') }}
        </div>
    @endif

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>⚠️ {{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="container">
        <div class="header">
            <h1><i class="fas fa-tools"></i> Transaksi Service</h1>
            <div class="header-info">
                <span class="info-item">No. Faktur: <strong id="nofaktur">Akan dibuat otomatis</strong></span>
            </div>
        </div>

        {{-- 🔹 FORM UTAMA --}}
        <form action="{{ route('transaksiservice.store') }}" method="POST">
            @csrf
            <div class="content-wrapper">
                <!-- Panel Kiri - Data Transaksi -->
                <div class="left-panel">
                    <div class="card">
                        <div class="card-header">
                            <h3><i class="fas fa-user"></i> Data Staff & Pelanggan</h3>
                        </div>
                        <div class="card-body">
                            <div class="form-group">
                                <label for="staff">Staff</label>
                                <input type="text" name="staff" id="staff" class="form-control"
                                    placeholder="Masukkan kode staff" list="list_staff" required>
                                <datalist id="list_staff">
                                    @foreach($staff as $karyawan)
                                        <option value="{{ $karyawan->nama }}"></option>
                                    @endforeach
                                </datalist>
                            </div>

                            <div class="form-group">
                                <label for="tanggalservice">Tanggal Servis</label>
                                <input type="date" name="tanggalservice" id="tanggalservice" class="form-control"
                                    required>
                            </div>

                            <div class="form-group">
                                <label for="tanggalambil">Tanggal Ambil</label>
                                <input type="date" name="tanggalambil" id="tanggalambil" class="form-control" required>
                            </div>

                            <div class="form-group customer-type">
                                <label>Tipe Pelanggan</label>
                                <div class="radio-group">
                                    <label class="radio-container">
                                        <input type="radio" name="tipepelanggan" value="umum" checked>
                                        <span class="radio-checkmark"></span>
                                        Umum
                                    </label>
                                    <label class="radio-container">
                                        <input type="radio" name="tipepelanggan" value="pelanggan">
                                        <span class="radio-checkmark"></span>
                                        Pelanggan
                                    </label>
                                </div>
                            </div>

                            <div class="form-group" id="kodepelanggan-field" style="display: none;">
                                <label for="nopelanggan">No. Pelanggan</label>
                                <input type="text" name="nopelanggan" id="nopelanggan" class="form-control"
                                    placeholder="Masukkan kode pelanggan">
                            </div>

                            <div class="form-group">
                                <label for="namapelanggan">Nama</label>
                                <input type="text" name="namapelanggan" id="namapelanggan" class="form-control"
                                    placeholder="Masukkan nama pelanggan" required>
                            </div>

                            <div class="form-group">
                                <label for="alamat">Alamat</label>
                                <textarea name="alamat" id="alamat" class="form-control"
                                    placeholder="Masukkan alamat pelanggan" rows="2"></textarea>
                            </div>

                            <div class="form-group">
                                <label for="notelp">No Telp</label>
                                <input type="text" name="notelp" id="notelp" class="form-control"
                                    placeholder="Masukkan nomor telefon pelanggan" required>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Panel Kanan - Input Barang -->
                <div class="right-panel">
                    <div class="card">
                        <div class="card-header">
                            <h3><i class="fas fa-plus-circle"></i> Tambah Barang Service</h3>
                        </div>
                        <div class="card-body" id="barang-wrapper">
                            <div class="barang-item">
                                <div class="form-group full-width">
                                    <label for="namabarang">Nama Barang</label>
                                    <input type="text" name="namabarang[]" class="form-control" required>
                                </div>

                                <div class="form-group">
                                    <label for="jenis">Jenis</label>
                                    <select name="jenis[]" class="form-control" required>
                                        <option value="Perhiasan Emas">Perhiasan Emas</option>
                                        <option value="Perhiasan Perak">Perhiasan Perak</option>
                                        <option value="Aksesoris">Aksesoris</option>
                                    </select>
                                </div>

                                <div class="form-group">
                                    <label for="berat">Berat (gr)</label>
                                    <input type="number" name="berat[]" class="form-control" step="0.01" required>
                                </div>

                                <div class="form-group">
                                    <label for="qty">Qty</label>
                                    <input type="number" name="qty[]" class="form-control" value="1" min="1" required>
                                </div>

                                <div class="form-group">
                                    <label for="harga">Harga</label>
                                    <input type="number" name="harga[]" class="form-control" value="0" min="0" required>
                                </div>

                                <div class="form-group">
                                    <label for="ongkos">Ongkos</label>
                                    <input type="number" name="ongkos[]" class="form-control" required>
                                </div>

                                <div class="form-group full-width">
                                    <label for="deskripsi">Deskripsi</label>
                                    <textarea name="deskripsi[]" class="form-control" rows="2"></textarea>
                                </div>
                            </div>
                        </div>

                        <button type="button" id="add-barang">+ Tambah Barang</button>
                    </div>

                    <!-- Action Buttons -->
                    <div class="action-buttons">
                        <button type="reset" class="btn btn-danger">
                            <i class="fas fa-trash"></i> Reset
                        </button>

                        <button type="submit" class="btn btn-success">
                            <i class="fas fa-save"></i> Simpan
                        </button>

                        <a href="{{ route('transaksiservice.cetak', ['id' => 1]) }}" target="_blank"
                            class="btn btn-primary">
                            <i class="fas fa-print"></i> Cetak Struk
                        </a>
                    </div>
                </div>

            </div>
        </form>
    </div>

    <script>
        // toggle field kode pelanggan
        document.querySelectorAll('input[name="tipepelanggan"]').forEach(radio => {
            radio.addEventListener('change', function () {
                document.getElementById('kodepelanggan-field').style.display =
                    this.value === 'pelanggan' ? 'block' : 'none';
            });
        });

        // set tanggal default hari ini
        const today = new Date().toISOString().split('T')[0];
        document.getElementById('tanggalservice').value = today;
        document.getElementById('tanggalambil').value = today;


        document.getElementById('add-barang').addEventListener('click', function () {
            let wrapper = document.getElementById('barang-wrapper');
            let newItem = wrapper.querySelector('.barang-item').cloneNode(true);

            // kosongkan input sebelum append
            newItem.querySelectorAll('input, textarea').forEach(el => el.value = '');
            wrapper.appendChild(newItem);
        });
    </script>
</body>

</html>