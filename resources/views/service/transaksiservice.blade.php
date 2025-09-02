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
    <div class="container">
        <div class="header">
            <h1><i class="fas fa-tools"></i> Transaksi Service</h1>
            <div class="header-info">
                <span class="info-item">No. Pesan: <strong id="nofaktur">SY-HELD-001</strong></span>
            </div>
        </div>

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
                            <input type="text" id="staff" class="form-control" placeholder="Masukkan kode staff" autofocus>
                            <input type="hidden" id="kdstaff">
                        </div>

                        <div class="form-group">
                            <label for="tglservis">Tanggal Servis</label>
                            <input type="date" id="tglservis" class="form-control" value="2023-09-07">
                        </div>

                        <div class="form-group">
                            <label for="tglambil">Tanggal Ambil</label>
                            <input type="date" id="tglambil" class="form-control" value="2023-09-07">
                        </div>

                        <div class="form-group customer-type">
                            <label>Tipe Pelanggan</label>
                            <div class="radio-group">
                                <label class="radio-container">
                                    <input type="radio" name="custype" id="umum" checked>
                                    <span class="radio-checkmark"></span>
                                    Umum
                                </label>
                                <label class="radio-container">
                                    <input type="radio" name="custype" id="pelanggan">
                                    <span class="radio-checkmark"></span>
                                    Pelanggan
                                </label>
                            </div>
                        </div>

                        <div class="form-group" id="kodepelanggan-field" style="display: none;">
                            <label for="kdpelanggan">No. Pelanggan</label>
                            <input type="text" id="kdpelanggan" class="form-control" placeholder="Masukkan kode pelanggan">
                        </div>

                        <div class="form-group">
                            <label for="custnama">Nama</label>
                            <input type="text" id="custnama" class="form-control" placeholder="Masukkan nama pelanggan">
                        </div>

                        <div class="form-group">
                            <label for="custalamat">Alamat</label>
                            <textarea id="custalamat" class="form-control" placeholder="Masukkan alamat pelanggan" rows="2"></textarea>
                        </div>

                        <div class="form-group">
                            <label for="custnotelp">No Telp</label>
                            <input type="text" id="custnotelp" class="form-control" placeholder="Masukkan nomor telefon pelanggan">
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
                    <div class="card-body">
                        <form id="formAdd" class="form-grid">
                            <div class="form-group full-width">
                                <label for="namabarang">Nama Barang</label>
                                <input type="text" id="namabarang" class="form-control" placeholder="Nama Barang">
                            </div>

                            <div class="form-group">
                                <label for="kdjenis">Jenis</label>
                                <select id="kdjenis" class="form-control">
                                    <option value="JNS001">Perhiasan Emas</option>
                                    <option value="JNS002">Perhiasan Perak</option>
                                    <option value="JNS003">Aksesoris</option>
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="berat">Berat (gr)</label>
                                <input type="text" id="berat" class="form-control" placeholder="Berat">
                            </div>

                            <div class="form-group">
                                <label for="qty">Qty</label>
                                <input type="number" id="qty" class="form-control" placeholder="Qty" value="1" min="1">
                            </div>

                            <div class="form-group">
                                <label for="ongkos">Ongkos</label>
                                <input type="text" id="ongkos" class="form-control" placeholder="Ongkos">
                            </div>

                            <div class="form-group full-width">
                                <label for="deskripsi">Deskripsi</label>
                                <textarea id="deskripsi" class="form-control" placeholder="Deskripsi" rows="2"></textarea>
                            </div>

                            <div class="form-group full-width">
                                <button type="button" class="btn btn-primary btn-add" onclick="addBarang()">
                                    <i class="fas fa-plus"></i> Tambah Barang
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Daftar Barang -->
                <div class="card">
                    <div class="card-header">
                        <h3><i class="fas fa-list"></i> Daftar Barang Service</h3>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="data-table">
                                <thead>
                                    <tr>
                                        <th>Jenis</th>
                                        <th>Nama Barang</th>
                                        <th>Deskripsi</th>
                                        <th>Berat (gr)</th>
                                        <th>QTY</th>
                                        <th>Ongkos</th>
                                        <th>Jumlah</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody id="bodyjual">
                                    <tr class="empty-row">
                                        <td colspan="8">
                                            <i class="fas fa-box-open"></i>
                                            <p>Belum ada barang yang ditambahkan</p>
                                        </td>
                                    </tr>
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <td colspan="3" class="total-label">Total</td>
                                        <td id="total-berat">0 gr</td>
                                        <td id="total-qty">0</td>
                                        <td id="total-ongkos">Rp 0</td>
                                        <td id="total-jumlah" colspan="2">Rp 0</td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="action-buttons">
                    <button class="btn btn-secondary" data-target="#cancelModal" data-toggle="modal">
                        <i class="fas fa-times"></i> Keluar
                    </button>
                    
                    <div class="print-option">
                        <label class="checkbox-container">
                            <input type="checkbox" id="print" name="print">
                            <span class="checkmark"></span>
                            Cetak Struk
                        </label>
                    </div>
                    
                    <button class="btn btn-danger" onclick="reset()">
                        <i class="fas fa-trash"></i> Reset
                    </button>
                    
                    <button class="btn btn-success" data-toggle="modal" data-target="#paymentModal">
                        <i class="fas fa-save"></i> Simpan & Bayar
                    </button>
                </div>
            </div>
        </div>

        <!-- Modal Pembayaran -->
        <div class="modal" id="paymentModal">
            <div class="modal-content">
                <div class="modal-header">
                    <h3><i class="fas fa-credit-card"></i> Pembayaran</h3>
                    <span class="close">&times;</span>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label>Total Pembayaran</label>
                        <input type="text" class="form-control total-payment" id="grandtotalacuan" value="Rp 0" readonly>
                    </div>

                    <div class="form-group">
                        <label>Metode Pembayaran</label>
                        <div class="payment-methods">
                            <label class="radio-container">
                                <input type="radio" name="tipepembayaran" value="cash" checked>
                                <span class="radio-checkmark"></span>
                                CASH
                            </label>
                            <label class="radio-container">
                                <input type="radio" name="tipepembayaran" value="debit">
                                <span class="radio-checkmark"></span>
                                DEBIT
                            </label>
                            <label class="radio-container">
                                <input type="radio" name="tipepembayaran" value="dp">
                                <span class="radio-checkmark"></span>
                                UANG MUKA
                            </label>
                        </div>
                    </div>

                    <div class="form-group" id="debitField" style="display: none;">
                        <label>Bank</label>
                        <select class="form-control" id="debitbank">
                            <option value="">--- PILIH BANK ---</option>
                            <option value="BRI">BRI</option>
                            <option value="BCA">BCA</option>
                            <option value="BNI">BNI</option>
                            <option value="MANDIRI">MANDIRI</option>
                            <option value="LAIN">BANK LAIN</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label>Jumlah Bayar</label>
                        <input type="text" class="form-control" id="paymentAmount" placeholder="Masukkan jumlah bayar">
                    </div>

                    <div class="form-group">
                        <label>Kembalian</label>
                        <input type="text" class="form-control" id="changeAmount" value="Rp 0" readonly>
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-outline" data-dismiss="modal">Batal</button>
                    <button class="btn btn-primary" onclick="processPayment()">
                        <i class="fas fa-check"></i> Konfirmasi Pembayaran
                    </button>
                </div>
            </div>
        </div>

        <!-- Modal Konfirmasi Keluar -->
        <div class="modal" id="cancelModal">
            <div class="modal-content">
                <div class="modal-header">
                    <h3><i class="fas fa-exclamation-triangle"></i> Konfirmasi</h3>
                    <span class="close">&times;</span>
                </div>
                <div class="modal-body">
                    <p>Apakah anda yakin ingin keluar?</p>
                    <p>Semua data yang belum disimpan akan hilang.</p>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-outline" data-dismiss="modal">Batal</button>
                    <button class="btn btn-danger" onclick="confirmExit()">Ya, Keluar</button>
                </div>
            </div>
        </div>

        <!-- Shortcut Help -->
        <div class="shortcut-help">
            <div class="help-section">
                <h4><i class="fas fa-keyboard"></i> Shortcut Keyboard</h4>
                <div class="shortcut-grid">
                    <div class="shortcut-item">
                        <span class="key">DELETE</span>
                        <span class="description">Reset Keranjang</span>
                    </div>
                    <div class="shortcut-item">
                        <span class="key">INSERT</span>
                        <span class="description">Cari Data Barang</span>
                    </div>
                    <div class="shortcut-item">
                        <span class="key">END</span>
                        <span class="description">Bayar</span>
                    </div>
                    <div class="shortcut-item">
                        <span class="key">PAGE UP</span>
                        <span class="description">Pelanggan</span>
                    </div>
                    <div class="shortcut-item">
                        <span class="key">PAGE DOWN</span>
                        <span class="description">Umum</span>
                    </div>
                    <div class="shortcut-item">
                        <span class="key">F9</span>
                        <span class="description">Cetak/Tidak Cetak</span>
                    </div>
                    <div class="shortcut-item">
                        <span class="key">F11</span>
                        <span class="description">Input Kode Staff</span>
                    </div>
                    <div class="shortcut-item">
                        <span class="key">ESC</span>
                        <span class="description">Keluar</span>
                    </div>
                    <div class="shortcut-item">
                        <span class="key">HOME</span>
                        <span class="description">Fokus Kode Barang</span>
                    </div>
                    <div class="shortcut-item">
                        <span class="key">F10</span>
                        <span class="description">Member Baru</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        // JavaScript functions would be implemented here
        // This is a simplified version for demonstration purposes
        
        document.addEventListener('DOMContentLoaded', function() {
            // Initialize date fields with current date
            const today = new Date().toISOString().split('T')[0];
            document.getElementById('tglservis').value = today;
            document.getElementById('tglambil').value = today;
            
            // Toggle pelanggan field based on radio selection
            document.querySelectorAll('input[name="custype"]').forEach(radio => {
                radio.addEventListener('change', function() {
                    document.getElementById('kodepelanggan-field').style.display = 
                        this.id === 'pelanggan' ? 'block' : 'none';
                });
            });
            
            // Toggle debit field based on payment method
            document.querySelectorAll('input[name="tipepembayaran"]').forEach(radio => {
                radio.addEventListener('change', function() {
                    document.getElementById('debitField').style.display = 
                        this.value === 'debit' ? 'block' : 'none';
                });
            });
            
            // Format currency input
            document.getElementById('ongkos').addEventListener('input', formatCurrency);
            document.getElementById('paymentAmount').addEventListener('input', calculateChange);
        });
        
        function formatCurrency(e) {
            // Currency formatting logic
        }
        
        function calculateChange() {
            // Change calculation logic
        }
        
        function addBarang() {
            // Add item to table logic
        }
        
        function reset() {
            // Reset form logic
        }
        
        function processPayment() {
            // Payment processing logic
        }
        
        function confirmExit() {
            // Exit confirmation logic
        }
    </script>
</body>
</html>