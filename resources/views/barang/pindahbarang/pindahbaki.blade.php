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
                    <select id="kdbakitujuan" name="kdbakitujuan" class="form-select" required>
                        <option value="">Pilih Baki Tujuan</option>
                        @foreach($baki as $b)
                            <option value="{{ $b->kdbaki }}">{{ $b->kdbaki }} - {{ $b->namabaki }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="no-transaksi">
                    <span class="no-transaksi-label">No. Pindah Baki:</span>
                    <input name="nopindahbarang" type="text" placeholder="No. Faktur"
                    value="{{ $pb }}" id="nopindahbarang"
                    style="text-align: center;" readonly>
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
        // Fungsi untuk menampilkan jam realtime
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
        setInterval(updateClock, 1000);
        updateClock();

        // Toggle print option
        document.getElementById('print').addEventListener('change', function() {
            document.getElementById('listprint').style.display = this.checked ? 'block' : 'none';
        });

        // Modal Cancel
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
            window.location.href = "{{ url('/PindahBaki') }}";
        });
        window.addEventListener('click', function(event) {
            if (event.target === document.getElementById('confirmModal')) {
                document.getElementById('confirmModal').style.display = 'none';
            }
        });

        // ===============================
        //  SCAN BARCODE
        // ===============================
        document.getElementById('inputbarcode').addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                e.preventDefault();
                const barcode = this.value.trim();
                if (!barcode) return;

                fetch("{{ route('pindahbaki.getBarang') }}", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                        "X-CSRF-TOKEN": "{{ csrf_token() }}"
                    },
                    body: JSON.stringify({ barcode: barcode })
                })
                .then(res => res.json())
                .then(data => {
                    if (!data.status) {
                        alert(data.message || "Barang tidak ditemukan");
                        return;
                    }
                    tambahRow(data.data);
                    document.getElementById('inputbarcode').value = '';
                    document.getElementById('inputbarcode').focus();
                })
                .catch(err => {
                    console.error(err);
                    alert("Terjadi kesalahan koneksi ke server");
                });
            }
        });

        // ===============================
        //  TAMBAH BARANG KE TABEL
        // ===============================
        function tambahRow(item) {
            const tbody = document.getElementById('bodykirimbarang');
            const emptyRow = document.querySelector('.empty-row');
            if (emptyRow) emptyRow.remove();

            const newRow = document.createElement('tr');
            newRow.innerHTML = `
                <td>${item.barcode}</td>
                <td>${item.namabarang}</td>
                <td>${item.kdbaki}</td>
                <td>${item.kdjenis}</td>
                <td>${item.berat}</td>
                <td>
                    <button class="btn-icon" onclick="removeItem(this)">
                        <i class="fas fa-times"></i>
                    </button>
                </td>
            `;
            tbody.appendChild(newRow);
            updateSummary();
        }

        // Hapus barang dari tabel
        function removeItem(button) {
            const row = button.closest('tr');
            row.remove();
            updateSummary();

            const tbody = document.getElementById('bodykirimbarang');
            if (tbody.children.length === 0) {
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

        // Update jumlah barang & total berat
        function updateSummary() {
            const tbody = document.getElementById('bodykirimbarang');
            const rows = tbody.querySelectorAll('tr');
            document.getElementById('jumlahkirim').textContent = `${rows.length} barang`;

            let total = 0;
            rows.forEach(r => {
                if (!r.classList.contains('empty-row')) {
                    total += parseFloat(r.children[4].textContent);
                }
            });
            document.getElementById('totalberat').textContent = total.toFixed(2) + ' gr';
        }

        // ===============================
        //  SIMPAN TRANSAKSI
        // ===============================
        document.getElementById('btnSimpan').addEventListener('click', function() {
            const tujuan = document.getElementById('kdbakitujuan').value;
            if (!tujuan) {
                alert('Pilih baki tujuan dulu!');
                return;
            }

            const rows = document.querySelectorAll('#bodykirimbarang tr');
            if (rows.length === 0 || rows[0].classList.contains('empty-row')) {
                alert('Tidak ada barang untuk disimpan!');
                return;
            }

            let items = [];
            rows.forEach(r => {
                if (!r.classList.contains('empty-row')) {
                    items.push({
                        barcode: r.children[0].textContent,
                        namabarang: r.children[1].textContent,
                        kdbaki: r.children[2].textContent,
                        kdjenis: r.children[3].textContent,
                        berat: r.children[4].textContent
                    });
                }
            });

            // tampilkan loader
            document.getElementById('loader').style.display = 'flex';

            fetch("{{ route('pindahbaki.store') }}", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": "{{ csrf_token() }}"
                },
                body: JSON.stringify({ kdbakitujuan: tujuan, items: items })
            })
            .then(res => res.json())
            .then(data => {
                document.getElementById('loader').style.display = 'none';
                alert(data.message);

                if (data.status) {
                    // reset tabel
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
                }
            })
            .catch(err => {
                document.getElementById('loader').style.display = 'none';
                console.error(err);
                alert("Gagal menyimpan data!");
            });
        });

        document.getElementById('simpan').addEventListener('click', function () {
            const rows = document.querySelectorAll('#table-barang tbody tr');
            let items = [];

            rows.forEach(row => {
                items.push({
                    barcode: row.querySelector('td:nth-child(1)').innerText,
                    asal: row.querySelector('td:nth-child(2)').innerText,
                    tujuan: row.querySelector('td:nth-child(3)').innerText
                });
            });

            fetch("{{ route('pindahbaki.store') }}", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').getAttribute("content")
                },
                body: JSON.stringify({ items })
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    // update no pindah di halaman
                    document.getElementById('nopindahbarang').innerText = data.no_pindah;

                    alert("Data berhasil disimpan. No. Pindah: " + data.no_pindah);
                }
            });
        });
    </script>
</body>
</html>