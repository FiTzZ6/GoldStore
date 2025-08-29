<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Permintaan Pembelian - TANGCAL</title>
    <link rel="stylesheet" href="{{ asset('css/utility/permintaan_pembelian/formulir_pp.css') }}">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
@include('partials.navbar')
    <div class="container">
        <div class="header">
            <h1><i class="fas fa-file-alt"></i> PERMINTAAN PEMBELIAN</h1>
        </div>

        <div class="form-container">
            <div class="form-header">
                <div class="form-group">
                    <label for="no-pp">No PP.</label>
                    <input type="text" id="no-pp" value="PP-HLD: 0625.6001" readonly>
                </div>
                <div class="form-group">
                    <label for="tanggal">Tanggal Permintaan</label>
                    <input type="text" id="tanggal" value="Kamis, 7 Agustus 2025 10:28" readonly>
                </div>
            </div>

            <div class="form-details">
                <div class="form-group">
                    <label for="toko">Toko</label>
                    <input type="text" id="toko" value="SANIBAS HOLDING PURBALINGGA" readonly>
                </div>
                <div class="form-group">
                    <label for="tanggal-ditetapkan">Tanggal Ditetapkan</label>
                    <input type="text" id="tanggal-ditetapkan" value="07/08/2025" readonly>
                </div>
            </div>

            <div class="divider"></div>

            <div class="table-section">
                <h2>Daftar Barang</h2>
                <div class="table-container">
                    <table>
                        <thead>
                            <tr>
                                <th>Nama Barang</th>
                                <th>Spesifikasi</th>
                                <th>Jumlah</th>
                                <th>Satuan</th>
                                <th>Supplier 1</th>
                                <th>Harga 1</th>
                                <th>Supplier 2</th>
                                <th>Harga 2</th>
                                <th>Supplier</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td><input type="text" placeholder="Nama barang"></td>
                                <td><input type="text" placeholder="Spesifikasi"></td>
                                <td><input type="number" placeholder="Jumlah" min="1"></td>
                                <td>
                                    <select>
                                        <option value="">Pilih Satuan</option>
                                        <option value="pcs">Pcs</option>
                                        <option value="unit">Unit</option>
                                        <option value="kg">Kg</option>
                                        <option value="meter">Meter</option>
                                        <option value="lusin">Lusin</option>
                                    </select>
                                </td>
                                <td><input type="text" placeholder="Supplier 1"></td>
                                <td><input type="number" placeholder="Harga" min="0"></td>
                                <td><input type="text" placeholder="Supplier 2"></td>
                                <td><input type="number" placeholder="Harga" min="0"></td>
                                <td>
                                    <select>
                                        <option value="">Pilih Supplier</option>
                                        <option value="supplier1">Supplier 1</option>
                                        <option value="supplier2">Supplier 2</option>
                                    </select>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <div class="table-actions">
                    <button class="btn-add" id="tambah-barang">
                        <i class="fas fa-plus"></i> Tambah Barang
                    </button>
                </div>
            </div>

            <div class="form-actions">
                <button class="btn-primary">
                    <i class="fas fa-paper-plane"></i> KIRIM
                </button>
                <button class="btn-secondary">
                    <i class="fas fa-print"></i> Cetak Form PP
                </button>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const tambahBarangBtn = document.getElementById('tambah-barang');
            const tableBody = document.querySelector('table tbody');
            
            // Fungsi untuk menambah baris barang baru
            tambahBarangBtn.addEventListener('click', function() {
                const newRow = document.createElement('tr');
                newRow.innerHTML = `
                    <td><input type="text" placeholder="Nama barang"></td>
                    <td><input type="text" placeholder="Spesifikasi"></td>
                    <td><input type="number" placeholder="Jumlah" min="1"></td>
                    <td>
                        <select>
                            <option value="">Pilih Satuan</option>
                            <option value="pcs">Pcs</option>
                            <option value="unit">Unit</option>
                            <option value="kg">Kg</option>
                            <option value="meter">Meter</option>
                            <option value="lusin">Lusin</option>
                        </select>
                    </td>
                    <td><input type="text" placeholder="Supplier 1"></td>
                    <td><input type="number" placeholder="Harga" min="0"></td>
                    <td><input type="text" placeholder="Supplier 2"></td>
                    <td><input type="number" placeholder="Harga" min="0"></td>
                    <td>
                        <select>
                            <option value="">Pilih Supplier</option>
                            <option value="supplier1">Supplier 1</option>
                            <option value="supplier2">Supplier 2</option>
                        </select>
                    </td>
                `;
                tableBody.appendChild(newRow);
            });
            
            // Fungsi untuk tombol KIRIM
            document.querySelector('.btn-primary').addEventListener('click', function() {
                alert('Formulir Permintaan Pembelian berhasil dikirim!');
            });
            
            // Fungsi untuk tombol Cetak
            document.querySelector('.btn-secondary').addEventListener('click', function() {
                alert('Mencetak Formulir Permintaan Pembelian...');
            });
        });
    </script>
</body>
</html>