<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
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
                            <select id="baki-select" name="kdbaki" class="form-control"> 
                                <option value="">PILIH BAKI</option> 
                                @foreach($baki as $b)
                                    <option value="{{ $b->kdbaki }}">{{ $b->kdbaki }} - {{ $b->namabaki }}</option>
                                @endforeach
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
                            <span id="total-items">Barang: 0 ptg</span>
                            <span id="total-weight">Berat: 0 gr</span>
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

        function loadBakiData(baki) {
            if (!baki) return;

            document.getElementById('loader').style.display = 'flex';

            fetch(`/stokopname/barang/${baki}`) // ✅ gunakan parameter 'baki'
            .then(response => response.json())
            .then(data => {
                let rows = '';
                data.forEach((item, index) => {
                    rows += `
                        <tr>
                            <td>${index + 1}</td>
                            <td>${item.barcode}</td>
                            <td>${item.namabarang}</td>
                            <td>${item.kategori}</td>
                            <td>${item.kdbaki}</td>
                            <td>${item.berat} gr</td>
                            <td>${item.kadar}</td>
                            <td>
                                <div class="status-indicator">
                                    <input type="checkbox" onchange="updateItemStatus('${item.barcode}', this.checked)">
                                    <span class="checkmark"></span>
                                </div>
                            </td>
                        </tr>
                    `;
                });

                document.querySelector('#items-table-body').innerHTML = rows;
                document.getElementById('loader').style.display = 'none'; // ✅ jangan lupa hide loader

                updateSummary();
            })
            .catch(err => {
                console.error(err);
                document.getElementById('loader').style.display = 'none';
            });
        }

        // Generate data dari DB
        function generateDataFromDB(baki, data) {
            const tableBody = document.getElementById('items-table-body');
            let html = '';
            let totalWeight = 0;

            if (!data || data.length === 0) {
                html = `
                    <tr class="empty-row">
                        <td colspan="8">
                            <i class="fas fa-box-open"></i>
                            <p>Tidak ada barang dalam baki ini</p>
                        </td>
                    </tr>
                `;
                document.getElementById('total-items').textContent = `0 barang`;
                document.getElementById('total-weight').textContent = `0 gr`;
                tableBody.innerHTML = html;
                return;
            }

            data.forEach((item, index) => {
                totalWeight += parseFloat(item.berat);
                html += `
                    <tr>
                        <td>${index + 1}</td>
                        <td>${item.barcode}</td>
                        <td>${item.namabarang}</td>
                        <td>${item.kategori}</td>
                        <td>${baki}</td>
                        <td>${item.berat} gr</td>
                        <td>${item.kadar}</td>
                        <td>
                            <div class="status-indicator">
                                <input type="checkbox" onchange="updateItemStatus('${item.barcode}', this.checked)">
                                <span class="checkmark"></span>
                            </div>
                        </td>
                    </tr>
                `;
            });

            document.getElementById('total-items').textContent = `${data.length} barang`;
            document.getElementById('total-weight').textContent = `${totalWeight.toFixed(2)} gr`;

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
                if (row.cells.length < 2) return; // skip jika baris kosong
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

            document.getElementById('barcode-input').value = '';
        }

        // Fungsi untuk update status item
        function updateItemStatus(barcode, isChecked) {
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

            const items = [];
            checkedItems.forEach(cb => {
                const row = cb.closest('tr');
                items.push({
                    barcode: row.cells[1].textContent,
                    namabarang: row.cells[2].textContent,
                    kdkategori: row.cells[3].textContent,
                    kdbaki: row.cells[4].textContent,
                    berat: row.cells[5].textContent.replace(' gr', ''),
                    kadar: row.cells[6].textContent
                });
            });

            const kdbaki = document.getElementById('baki-select').value;

            document.getElementById('loader').style.display = 'flex';

            fetch('/stokopname/simpan', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify({ kdbaki, items })
            })
            .then(res => res.json())
            .then(res => {
                document.getElementById('loader').style.display = 'none';
                if (res.success) {
                    showNotification('Stok opname berhasil disimpan', 'success');
                } else {
                    showNotification(res.message || 'Gagal menyimpan stok opname', 'error');
                }
            })
            .catch(err => {
                console.error(err);
                document.getElementById('loader').style.display = 'none';
                showNotification('Terjadi kesalahan', 'error');
            });
        }

        // Fungsi untuk menampilkan notifikasi
        function showNotification(message, type) {
            alert(`${type.toUpperCase()}: ${message}`);
        }

        function updateSummary() {
            const rows = document.querySelectorAll('#items-table-body tr');
            let jumlah = 0;
            let totalBerat = 0;

            rows.forEach(row => {
                if (row.cells.length > 5) {
                    jumlah++;
                    let beratText = row.cells[5].textContent.replace(" gr", "").trim();
                    totalBerat += parseFloat(beratText) || 0;
                }
            });

            document.getElementById('total-items').textContent = `Barang: ${jumlah} ptg`;
            document.getElementById('total-weight').textContent = `Berat: ${totalBerat.toFixed(2)} gr`;
        }
    </script>
</body>
</html>