<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="{{ asset('css/jual/bataljual.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>

<body>
    @include('partials.navbar')
    <div class="container">
        <div class="top-bar">
            <div class="logo-area">
                <div class="logo">SH</div>
                <div class="company-info">
                    <h1>SAMBAS HOLDING</h1>
                    <p>Transaksi Batal Penjualan</p>
                </div>
            </div>
            <div class="invoice-number">
                <i class="fas fa-file-invoice"></i>
                No. Faktor: <span id="invoice-number">BJ-HLD-25081-0001</span>
            </div>
        </div>

        <div class="title">
            <h2>FORMULIR PEMBATALAN PENJUALAN</h2>
            <p>Silakan isi formulir berikut untuk membatalkan transaksi penjualan</p>
        </div>

        @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        {{-- MULAI FORM --}}
        <form action="{{ route('bataljual.store') }}" method="POST">
            @csrf

            <div class="content">
                <div class="form-section">
                    <h3 class="section-title"><i class="fas fa-user"></i> Informasi Staff</h3>
                    <div class="form-row">
                        <div class="form-group">
                            <label for="staff-code">Nama Staff</label>
                            <input type="text" name="namastaff" id="namastaff" class="form-control"
                                placeholder="Masukkan kode staff" list="list_staff">
                            <datalist id="list_staff">
                                @foreach($staff as $karyawan)
                                    <option value="{{ $karyawan->nama }}"></option>
                                @endforeach
                            </datalist>
                            <p class="instructions">*Scan atau enter kode staff unik</p>
                        </div>

                        <div class="form-group">
                            <label for="barcode">Barcode</label>
                            <input type="text" name="barcode" id="barcode-input" class="form-control"
                                placeholder="Masukkan barcode" list="list_barcode">
                            <datalist id="list_barcode">
                                @foreach($stokjual as $jual)
                                    <option value="{{ $jual->barcode }}"></option>
                                @endforeach
                            </datalist>
                            <p class="instructions">*Scan atau enter kode barcode unik</p>
                        </div>
                    </div>
                </div>

                <div class="form-section">
                    <h3 class="section-title"><i class="fas fa-shopping-cart"></i> Detail Barang</h3>
                    <table class="item-table">
                        <thead>
                            <tr>
                                <th>No. Faktur Jual</th>
                                <th>Barcode</th>
                                <th>Nama Barang</th>
                                <th>Harga</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td><input type="text" name="fakturjual" id="nofaktur" placeholder="No. Faktur"></td>
                                <td><input type="text" id="barcode-field" placeholder="Barcode" readonly></td>
                                <td><input type="text" name="namabarang" id="namabarang" placeholder="Nama Barang"></td>
                                <td><input type="text" name="harga" id="harga" placeholder="Harga"></td>
                            </tr>
                        </tbody>
                    </table>

                    <table class="item-table">
                        <thead>
                            <tr>
                                <th>Kadar</th>
                                <th>Berat</th>
                                <th>Ongkos</th>
                                <th>Jumlah</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td><input type="text" name="kadar" id="kadar" placeholder="Kadar"></td>
                                <td><input type="text" name="berat" id="berat" placeholder="Berat"></td>
                                <td><input type="text" name="ongkos" id="ongkos" placeholder="Ongkos"></td>
                                <td><input type="text" name="quantity" id="jumlah" placeholder="Jumlah"></td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <div class="form-section">
                    <h3 class="section-title"><i class="fas fa-info-circle"></i> Informasi Pembatalan</h3>

                    <div class="form-group">
                        <label>Kondisi Barang</label>
                        <div class="radio-group">
                            <div class="radio-option">
                                <input type="radio" id="condition-good" name="kondisi" value="baik">
                                <label for="condition-good">Baik</label>
                            </div>
                            <div class="radio-option">
                                <input type="radio" id="condition-damaged" name="kondisi" value="rusak">
                                <label for="condition-damaged">Rusak</label>
                            </div>
                            <div class="radio-option">
                                <input type="radio" id="condition-missing" name="kondisi" value="hilang">
                                <label for="condition-missing">Hilang</label>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="cancellation-reason">Keterangan Batal</label>
                        <textarea name="keterangan" id="cancellation-reason" class="form-control"
                            placeholder="Jelaskan alasan pembatalan transaksi..."></textarea>
                    </div>
                </div>

                <div class="button-group">
                    <button type="reset" class="btn btn-clear"><i class="fas fa-eraser"></i> CLEAR</button>
                    <button type="button" class="btn btn-cancel"><i class="fas fa-times-circle"></i> CANCEL</button>
                    <button type="submit" class="btn btn-save"><i class="fas fa-save"></i> SIMPAN</button>
                </div>
            </div>
        </form>
        {{-- AKHIR FORM --}}
    </div>

    <script>
        // generate nomor faktur batal
        function generateInvoiceNumber() {
            const prefix = "BJ-HLD";
            const date = new Date();
            const year = date.getFullYear().toString().substr(-2);
            const month = (date.getMonth() + 1).toString().padStart(2, '0');
            const day = date.getDate().toString().padStart(2, '0');
            const random = Math.floor(1000 + Math.random() * 9000);
            return `${prefix}-${year}${month}${day}-${random}`;
        }

        document.addEventListener('DOMContentLoaded', function () {
            document.getElementById('invoice-number').textContent = generateInvoiceNumber();

            // CLEAR form
            document.querySelector('.btn-clear').addEventListener('click', function () {
                if (confirm('Apakah Anda yakin ingin menghapus semua data yang telah dimasukkan?')) {
                    document.querySelectorAll('input, textarea').forEach(el => el.value = '');
                    document.querySelectorAll('input[type="radio"]').forEach(r => r.checked = false);
                }
            });

            // CANCEL (hanya popup, tidak submit)
            document.querySelector('.btn-cancel').addEventListener('click', function () {
                if (confirm('Batalkan transaksi penjualan?')) {
                    alert('Transaksi telah dibatalkan');
                }
            });

            // isi otomatis data barang dari barcode
            document.getElementById('barcode-input').addEventListener('change', function () {
                let barcode = this.value;
                if (barcode) {
                    fetch(`/get-barang/${barcode}`)
                        .then(response => response.json())
                        .then(data => {
                            if (!data.error) {
                                document.getElementById('nofaktur').value = data.nofaktur;
                                document.getElementById('barcode-field').value = data.barcode;
                                document.getElementById('namabarang').value = data.namabarang;
                                document.getElementById('harga').value = data.hargajual;
                                document.getElementById('kadar').value = data.kadar;
                                document.getElementById('berat').value = data.berat;
                                document.getElementById('ongkos').value = data.ongkos;
                                document.getElementById('jumlah').value = 1;
                            } else {
                                alert('Barang tidak ditemukan');
                            }
                        })
                        .catch(err => console.error(err));
                }
            });
        });
    </script>
</body>

</html>