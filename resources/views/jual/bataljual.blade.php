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
        
        <div class="content">
            <div class="form-section">
                <h3 class="section-title"><i class="fas fa-user"></i> Informasi Staff</h3>
                <div class="form-row">
                    <div class="form-group">
                        <label for="staff-code">Kode Staff</label>
                        <input type="text" id="staff-code" class="form-control" placeholder="Masukkan kode staff">
                        <p class="instructions">*Scan atau enter kode staff unik</p>
                    </div>
                    
                    <div class="form-group">
                        <label for="barcode">Barcode</label>
                        <input type="text" id="barcode" class="form-control" placeholder="Masukkan barcode">
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
                            <th>Atribut</th>
                            <th>Harga</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td><input type="text" placeholder="No. Faktur"></td>
                            <td><input type="text" placeholder="Barcode"></td>
                            <td><input type="text" placeholder="Nama Barang"></td>
                            <td><input type="text" placeholder="Atribut"></td>
                            <td><input type="text" placeholder="Harga"></td>
                        </tr>
                    </tbody>
                </table>
                
                <table class="item-table">
                    <thead>
                        <tr>
                            <th>Harga Atribut</th>
                            <th>Kadar</th>
                            <th>Berat</th>
                            <th>Ongkos</th>
                            <th>Jumlah</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td><input type="text" placeholder="Harga Atribut"></td>
                            <td><input type="text" placeholder="Kadar"></td>
                            <td><input type="text" placeholder="Berat"></td>
                            <td><input type="text" placeholder="Ongkos"></td>
                            <td><input type="text" placeholder="Jumlah"></td>
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
                            <input type="radio" id="condition-good" name="condition" value="good">
                            <label for="condition-good">Baik</label>
                        </div>
                        <div class="radio-option">
                            <input type="radio" id="condition-damaged" name="condition" value="damaged">
                            <label for="condition-damaged">Rusak</label>
                        </div>
                        <div class="radio-option">
                            <input type="radio" id="condition-missing" name="condition" value="missing">
                            <label for="condition-missing">Hilang</label>
                        </div>
                    </div>
                </div>
                
                <div class="form-group">
                    <label for="cancellation-reason">Keterangan Batal</label>
                    <textarea id="cancellation-reason" class="form-control" placeholder="Jelaskan alasan pembatalan transaksi..."></textarea>
                </div>
            </div>
            
            <div class="button-group">
                <button class="btn btn-clear"><i class="fas fa-eraser"></i> CLEAR</button>
                <button class="btn btn-cancel"><i class="fas fa-times-circle"></i> CANCEL</button>
                <button class="btn btn-save"><i class="fas fa-save"></i> SIMPAN</button>
            </div>
        </div>
        
        <footer>
            <p>© 2023 Sambas Holding - Sistem Manajemen Pembatalan Penjualan</p>
        </footer>
    </div>

    <script>
        // Function to generate invoice number
        function generateInvoiceNumber() {
            const prefix = "BJ-HLD";
            const date = new Date();
            const year = date.getFullYear().toString().substr(-2);
            const month = (date.getMonth() + 1).toString().padStart(2, '0');
            const day = date.getDate().toString().padStart(2, '0');
            const random = Math.floor(1000 + Math.random() * 9000);
            
            return `${prefix}-${year}${month}${day}-${random}`;
        }
        
        // Set generated invoice number on page load
        document.addEventListener('DOMContentLoaded', function() {
            document.getElementById('invoice-number').textContent = generateInvoiceNumber();
            
            // Add event listeners for buttons
            document.querySelector('.btn-clear').addEventListener('click', function() {
                if(confirm('Apakah Anda yakin ingin menghapus semua data yang telah dimasukkan?')) {
                    document.querySelectorAll('input, textarea').forEach(element => {
                        element.value = '';
                    });
                    document.querySelectorAll('input[type="radio"]').forEach(radio => {
                        radio.checked = false;
                    });
                }
            });
            
            document.querySelector('.btn-cancel').addEventListener('click', function() {
                if(confirm('Batalkan transaksi penjualan?')) {
                    alert('Transaksi telah dibatalkan');
                    // Here you would typically submit the form or process the cancellation
                }
            });
            
            document.querySelector('.btn-save').addEventListener('click', function() {
                alert('Data pembatalan telah disimpan');
                // Here you would typically submit the form or process the data
            });
        });
    </script>
</body>
</html>