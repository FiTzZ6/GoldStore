<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pindah Baki - Sistem Manajemen Barang</title>
    <link rel="stylesheet" href="{{ asset('css/barang//pindahbarang/pindahbaki.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
@include('partials.navbar')
    <div class="container">
        <div class="header">
            <h1><i class="fas fa-exchange-alt"></i> Pindah Baki</h1>
            <div class="agent-info">
                <span class="timestamp" id="clock"></span>
            </div>
        </div>

        <main class="main-content">
            <div class="scan-section">
                <label for="inputbarcode" class="scan-label">
                    <i class="fas fa-barcode"></i> Scan Barcode Barang
                </label>
                <div class="scan-input-container">
                    <input type="text" id="inputbarcode" class="scan-input" 
                           placeholder="Scan atau masukkan barcode barang" autofocus>
                    <div class="scan-hint">
                        <i class="fas fa-lightbulb"></i> Ketik Kode Barcode
                    </div>
                </div>
            </div>

            <form id="formPindahBaki" class="form-pindah">
                <div class="form-group">
                    <label for="kdbakitujuan" class="form-label">
                        <i class="fas fa-arrow-right"></i> Baki Tujuan
                    </label>
                    <select id="kdbakitujuan" class="form-select" required>
                        <option value="">Pilih Baki Tujuan</option>
                        <option value="B001">B001 - Baki Utama</option>
                        <option value="B002">B002 - Baki Packaging</option>
                        <option value="B003">B003 - Baki Quality Control</option>
                    </select>
                </div>

                <div class="no-transaksi">
                    <span class="no-transaksi-label">No. Pindah Baki:</span>
                    <span id="nopindahbarang" class="no-transaksi-value">PB-20230902-001</span>
                </div>

                <div class="table-container">
                    <div class="table-header">
                        <h3><i class="fas fa-list"></i> Daftar Barang yang Akan Dipindahkan</h3>
                        <span class="item-count" id="jumlahkirim">0 barang</span>
                    </div>
                    
                    <table class="items-table">
                        <thead>
                            <tr>
                                <th>Barcode</th>
                                <th>Nama Barang</th>
                                <th>Kode Baki Asal</th>
                                <th>Kode Jenis</th>
                                <th>Berat (gr)</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody id="bodykirimbarang">
                            <!-- Baris barang akan ditambahkan secara dinamis -->
                            <tr class="empty-row">
                                <td colspan="6">
                                    <i class="fas fa-box-open"></i>
                                    <p>Belum ada barang yang ditambahkan</p>
                                    <small>Scan barcode untuk menambahkan barang</small>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                    
                    <div class="table-footer">
                        <div class="total-weight">
                            <i class="fas fa-weight-hanging"></i>
                            Total Berat: <span id="totalberat">0 gr</span>
                        </div>
                    </div>
                </div>

                <div class="action-section">
                    <div class="print-option">
                        <label class="checkbox-container">
                            <input type="checkbox" id="print" name="print">
                            <span class="checkmark"></span>
                            Cetak Bukti Pindah
                        </label>
                        
                        <div class="printer-select" id="listprint" style="display: none;">
                            <select id="lstPrinters">
                                <option value="printer1">Printer Utama</option>
                                <option value="printer2">Printer Gudang</option>
                            </select>
                        </div>
                    </div>

                    <div class="shortcut-keys">
                        <h4><i class="fas fa-keyboard"></i> Shortcut Keyboard</h4>
                        <div class="keys-container">
                            <div class="key-item">
                                <span class="key-box">Page Up</span>
                                <span class="key-desc">Fokus Scan Barcode</span>
                            </div>
                            <div class="key-item">
                                <span class="key-box">Page Down</span>
                                <span class="key-desc">Reset Tabel</span>
                            </div>
                            <div class="key-item">
                                <span class="key-box">End</span>
                                <span class="key-desc">Simpan</span>
                            </div>
                            <div class="key-item">
                                <span class="key-box">F9</span>
                                <span class="key-desc">Toggle Print</span>
                            </div>
                        </div>
                        <div class="weight-info">
                            <i class="fas fa-info-circle"></i> Berat Bandrol: 0.05 gr
                        </div>
                    </div>
                </div>

                <div class="form-actions">
                    <button type="button" class="btn btn-secondary" id="btnCancel">
                        <i class="fas fa-times"></i> Batal
                    </button>
                    <button type="button" class="btn btn-primary" id="btnSimpan">
                        <i class="fas fa-save"></i> Simpan
                    </button>
                </div>
            </form>
        </main>
    </div>

    <!-- Modal Konfirmasi -->
    <div class="modal" id="confirmModal">
        <div class="modal-content">
            <div class="modal-header">
                <h3><i class="fas fa-exclamation-triangle"></i> Konfirmasi</h3>
                <span class="close">&times;</span>
            </div>
            <div class="modal-body">
                <p>Apakah Anda yakin ingin membatalkan proses pindah baki?</p>
                <p>Semua data yang telah dimasukkan akan hilang.</p>
            </div>
            <div class="modal-footer">
                <button class="btn btn-outline" id="modalCancel">Tidak</button>
                <button class="btn btn-danger" id="modalConfirm">Ya, Batalkan</button>
            </div>
        </div>
    </div>

    <!-- Loader -->
    <div class="loader" id="loader">
        <div class="loader-spinner"></div>
        <p>Memproses...</p>
    </div>

    <script>
        // Fungsi untuk menampilkan waktu
        function updateClock() {
            const now = new Date();
            const options = { 
                weekday: 'long', 
                year: 'numeric', 
                month: 'long', 
                day: 'numeric',
                hour: '2-digit',
                minute: '2-digit',
                second: '2-digit'
            };
            document.getElementById('clock').textContent = now.toLocaleDateString('id-ID', options);
        }
        
        // Update clock setiap detik
        setInterval(updateClock, 1000);
        updateClock();
        
        // Toggle print option
        document.getElementById('print').addEventListener('change', function() {
            document.getElementById('listprint').style.display = this.checked ? 'block' : 'none';
        });
        
        // Modal functionality
        document.getElementById('btnCancel').addEventListener('click', function() {
            document.getElementById('confirmModal').style.display = 'block';
        });
        
        document.querySelector('.close').addEventListener('click', function() {
            document.getElementById('confirmModal').style.display = 'none';
        });
        
        document.getElementById('modalCancel').addEventListener('click', function() {
            document.getElementById('confirmModal').style.display = 'none';
        });
        
        document.getElementById('modalConfirm').addEventListener('click', function() {
            // Redirect to barang page
            window.location.href = 'barang.html';
        });
        
        // Close modal when clicking outside
        window.addEventListener('click', function(event) {
            if (event.target === document.getElementById('confirmModal')) {
                document.getElementById('confirmModal').style.display = 'none';
            }
        });
        
        // Simulate adding items (for demo purposes)
        document.getElementById('inputbarcode').addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                e.preventDefault();
                simulateAddItem();
            }
        });
        
        function simulateAddItem() {
            const barcode = document.getElementById('inputbarcode').value;
            if (!barcode) return;
            
            // Clear empty row if it exists
            const emptyRow = document.querySelector('.empty-row');
            if (emptyRow) emptyRow.remove();
            
            // Add new item to table
            const tbody = document.getElementById('bodykirimbarang');
            const newRow = document.createElement('tr');
            
            // Sample data based on barcode
            const items = [
                {barcode: '123456', name: 'Produk A', baki: 'B001', jenis: 'JNS001', berat: '120.5'},
                {barcode: '789012', name: 'Produk B', baki: 'B002', jenis: 'JNS002', berat: '85.0'},
                {barcode: '345678', name: 'Produk C', baki: 'B003', jenis: 'JNS003', berat: '210.75'}
            ];
            
            const randomItem = items[Math.floor(Math.random() * items.length)];
            
            newRow.innerHTML = `
                <td>${barcode}</td>
                <td>${randomItem.name}</td>
                <td>${randomItem.baki}</td>
                <td>${randomItem.jenis}</td>
                <td>${randomItem.berat}</td>
                <td>
                    <button class="btn-icon" onclick="removeItem(this)">
                        <i class="fas fa-times"></i>
                    </button>
                </td>
            `;
            
            tbody.appendChild(newRow);
            
            // Update counters
            const itemCount = tbody.children.length;
            document.getElementById('jumlahkirim').textContent = `${itemCount} barang`;
            
            // Update total weight (simple simulation)
            const totalWeight = itemCount * 100 + Math.random() * 100;
            document.getElementById('totalberat').textContent = `${totalWeight.toFixed(2)} gr`;
            
            // Clear input
            document.getElementById('inputbarcode').value = '';
            document.getElementById('inputbarcode').focus();
        }
        
        function removeItem(button) {
            const row = button.closest('tr');
            row.remove();
            
            // Update counters
            const tbody = document.getElementById('bodykirimbarang');
            const itemCount = tbody.children.length;
            document.getElementById('jumlahkirim').textContent = `${itemCount} barang`;
            
            // If no items left, show empty message
            if (itemCount === 0) {
                tbody.innerHTML = `
                    <tr class="empty-row">
                        <td colspan="6">
                            <i class="fas fa-box-open"></i>
                            <p>Belum ada barang yang ditambahkan</p>
                            <small>Scan barcode untuk menambahkan barang</small>
                        </td>
                    </tr>
                `;
            }
        }
        
        // Simulate save
        document.getElementById('btnSimpan').addEventListener('click', function() {
            const tbody = document.getElementById('bodykirimbarang');
            if (tbody.querySelector('.empty-row')) {
                alert('Tidak ada barang untuk disimpan!');
                return;
            }
            
            // Show loader
            document.getElementById('loader').style.display = 'flex';
            
            // Simulate API call
            setTimeout(function() {
                document.getElementById('loader').style.display = 'none';
                alert('Data berhasil disimpan!');
                
                // Reset form
                document.getElementById('bodykirimbarang').innerHTML = `
                    <tr class="empty-row">
                        <td colspan="6">
                            <i class="fas fa-box-open"></i>
                            <p>Belum ada barang yang ditambahkan</p>
                            <small>Scan barcode untuk menambahkan barang</small>
                        </td>
                    </tr>
                `;
                document.getElementById('jumlahkirim').textContent = '0 barang';
                document.getElementById('totalberat').textContent = '0 gr';
            }, 1500);
        });
    </script>
</body>
</html>