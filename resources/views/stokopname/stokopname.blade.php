<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Stok Opname Global - Sistem Manajemen</title>
    <link rel="stylesheet" href="{{ asset('css/stokopname/stok-opname.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
    @include('partials.navbar')
    <div class="container">
        <!-- Header -->
        <div class="header">
            <h1><i class="fas fa-clipboard-check"></i> STOK OPNAME GLOBAL</h1>
        </div>

        <!-- Main Content -->
        <div class="content-wrapper">
            <!-- Panel Kiri - Kontrol Baki -->
            <div class="control-panel">
                <div class="card">
                    <div class="card-header">
                        <h2><i class="fas fa-archive"></i> PILIH BAKI</h2>
                    </div>
                    <div class="card-body">
                        <div class="form-group">
                            <label for="baki-select">BAKI</label>
                            <select id="baki-select" class="form-control">
                                <option value="">PILIH BAKI</option>
                                <option value="B001">B001 - Baki Emas</option>
                                <option value="B002">B002 - Baki Perak</option>
                                <option value="B003">B003 - Baki Permata</option>
                            </select>
                        </div>

                        <div class="baki-info">
                            <div class="info-item">
                                <span class="label">BAKI:</span>
                                <span class="value" id="current-baki">PILIH BAKI</span>
                            </div>
                            <div class="info-item">
                                <span class="label">Jumlah Barang Di Cek:</span>
                                <span class="value" id="items-checked">0 ptg</span>
                            </div>
                            <div class="info-item">
                                <span class="label">Berat Di Cek:</span>
                                <span class="value" id="weight-checked">0 gr</span>
                            </div>
                        </div>

                        <div class="action-buttons">
                            <button class="btn btn-primary btn-save" onclick="simpanSO()">
                                <i class="fas fa-save"></i> SIMPAN STOK OPNAME
                            </button>
                        </div>

                        <div class="warning-note">
                            <i class="fas fa-exclamation-circle"></i>
                            <span>Sebelum berganti baki, simpan stok opname terlebih dahulu</span>
                        </div>
                    </div>
                </div>

                <!-- Barcode Input -->
                <div class="card" id="barcode-card" style="display: none;">
                    <div class="card-header">
                        <h2><i class="fas fa-barcode"></i> SCAN BARCODE</h2>
                    </div>
                    <div class="card-body">
                        <div class="form-group">
                            <input type="text" id="barcode-input" class="form-control" 
                                   placeholder="Scan atau masukkan kode barang" autocomplete="off">
                        </div>
                    </div>
                </div>
            </div>

            <!-- Panel Kanan - Daftar Barang -->
            <div class="content-panel">
                <div class="card">
                    <div class="card-header">
                        <h2><i class="fas fa-list"></i> DAFTAR BARANG BAKI</h2>
                        <div class="header-stats">
                            <span id="total-items">0 barang</span>
                            <span id="total-weight">0 gr</span>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-container">
                            <table class="items-table">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Barcode</th>
                                        <th>Nama Barang</th>
                                        <th>Kategori</th>
                                        <th>Baki</th>
                                        <th>Berat (gr)</th>
                                        <th>Kadar</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody id="items-table-body">
                                    <tr class="empty-row">
                                        <td colspan="8">
                                            <i class="fas fa-box-open"></i>
                                            <p>Silakan pilih baki untuk menampilkan data</p>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Loader -->
        <div class="loader" id="loader">
            <div class="loader-spinner"></div>
            <p>Memproses data...</p>
        </div>
    </div>

    <script>
        // Inisialisasi
        document.addEventListener('DOMContentLoaded', function() {
            // Event listener untuk select baki
            document.getElementById('baki-select').addEventListener('change', function() {
                loadBakiData(this.value);
            });

            // Event listener untuk input barcode
            document.getElementById('barcode-input').addEventListener('input', function(e) {
                handleBarcodeInput(e.target.value);
            });

            // Event listener untuk keyboard shortcut
            document.addEventListener('keydown', function(e) {
                if (e.key === 'Enter' && document.getElementById('barcode-input').value) {
                    processBarcode(document.getElementById('barcode-input').value);
                }
            });
        });

        // Fungsi untuk memuat data baki
        function loadBakiData(baki) {
            if (!baki) return;

            // Tampilkan loader
            document.getElementById('loader').style.display = 'flex';
            
            // Simulasi loading data
            setTimeout(function() {
                // Update info baki
                document.getElementById('current-baki').textContent = baki;
                document.getElementById('barcode-card').style.display = 'block';
                
                // Generate sample data
                generateSampleData(baki);
                
                // Sembunyikan loader
                document.getElementById('loader').style.display = 'none';
                
                // Fokus ke input barcode
                document.getElementById('barcode-input').focus();
            }, 1000);
        }

        // Fungsi untuk generate sample data
        function generateSampleData(baki) {
            const sampleData = [
                { barcode: 'BRC001', name: 'Cincin Emas 24K', category: 'EMAS', weight: '5.25', kadar: '24' },
                { barcode: 'BRC002', name: 'Gelang Perak', category: 'PERAK', weight: '15.75', kadar: '92.5' },
                { barcode: 'BRC003', name: 'Kalung Permata', category: 'PERMATA', weight: '8.50', kadar: '-', checked: true },
                { barcode: 'BRC004', name: 'Anting Emas 18K', category: 'EMAS', weight: '3.15', kadar: '18' },
                { barcode: 'BRC005', name: 'Liontin Perak', category: 'PERAK', weight: '7.80', kadar: '92.5' }
            ];

            const tableBody = document.getElementById('items-table-body');
            let html = '';
            let totalWeight = 0;

            sampleData.forEach((item, index) => {
                totalWeight += parseFloat(item.weight);
                html += `
                    <tr class="${item.checked ? 'checked' : ''}">
                        <td>${index + 1}</td>
                        <td>${item.barcode}</td>
                        <td>${item.name}</td>
                        <td>${item.category}</td>
                        <td>${baki}</td>
                        <td>${item.weight} gr</td>
                        <td>${item.kadar}</td>
                        <td>
                            <div class="status-indicator">
                                <input type="checkbox" ${item.checked ? 'checked' : ''} 
                                    onchange="updateItemStatus('${item.barcode}', this.checked)">
                                <span class="checkmark"></span>
                            </div>
                        </td>
                    </tr>
                `;
            });

            // Update totals
            document.getElementById('total-items').textContent = `${sampleData.length} barang`;
            document.getElementById('total-weight').textContent = `${totalWeight.toFixed(2)} gr`;

            // Update checked counts
            updateCheckedCounts();

            tableBody.innerHTML = html;
        }

        // Fungsi untuk handle input barcode
        function handleBarcodeInput(value) {
            if (value.length >= 6) {
                processBarcode(value);
            }
        }

        // Fungsi untuk process barcode
        function processBarcode(barcode) {
            const rows = document.querySelectorAll('#items-table-body tr');
            let found = false;

            rows.forEach(row => {
                const rowBarcode = row.cells[1].textContent;
                if (rowBarcode === barcode) {
                    found = true;
                    const checkbox = row.querySelector('input[type="checkbox"]');
                    checkbox.checked = !checkbox.checked;
                    updateItemStatus(barcode, checkbox.checked);
                    row.classList.toggle('checked', checkbox.checked);
                }
            });

            if (!found) {
                showNotification('Barcode tidak ditemukan', 'warning');
            }

            // Clear input
            document.getElementById('barcode-input').value = '';
        }

        // Fungsi untuk update status item
        function updateItemStatus(barcode, isChecked) {
            // Simulasi update status
            console.log(`Item ${barcode} ${isChecked ? 'checked' : 'unchecked'}`);
            updateCheckedCounts();
        }

        // Fungsi untuk update jumlah yang dicek
        function updateCheckedCounts() {
            const checkedItems = document.querySelectorAll('#items-table-body input[type="checkbox"]:checked');
            let totalWeight = 0;

            checkedItems.forEach(checkbox => {
                const row = checkbox.closest('tr');
                const weight = parseFloat(row.cells[5].textContent.replace(' gr', ''));
                totalWeight += weight;
            });

            document.getElementById('items-checked').textContent = `${checkedItems.length} ptg`;
            document.getElementById('weight-checked').textContent = `${totalWeight.toFixed(2)} gr`;
        }

        // Fungsi untuk simpan stok opname
        function simpanSO() {
            const checkedItems = document.querySelectorAll('#items-table-body input[type="checkbox"]:checked');
            
            if (checkedItems.length === 0) {
                showNotification('Tidak ada barang yang dicek', 'warning');
                return;
            }

            document.getElementById('loader').style.display = 'flex';

            // Simulasi proses penyimpanan
            setTimeout(function() {
                document.getElementById('loader').style.display = 'none';
                showNotification('Stok opname berhasil disimpan', 'success');
            }, 1500);
        }

        // Fungsi untuk menampilkan notifikasi
        function showNotification(message, type) {
            // Implementasi notifikasi sederhana
            alert(`${type.toUpperCase()}: ${message}`);
        }
    </script>
</body>
</html>