<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Permintaan Pembelian - TANGCAL</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://code.jquery.com/ui/1.13.2/jquery-ui.min.js"></script>
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css">
    <link rel="stylesheet" href="{{ asset('css/utility/permintaan_pembelian/formulir_pp.css') }}">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
@include('partials.navbar')
    <div class="container">

        @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        <div class="header">
            <h1><i class="fas fa-file-alt"></i> PERMINTAAN PEMBELIAN</h1>
        </div>

        <div class="form-container">
            <form action="{{ route('formulir_pp.store') }}" method="POST">
                @csrf

                <div class="form-header">
                    <!-- Kolom kiri -->
                    <div class="left-col">
                        <div class="mb-3">
                            <label for="kdtoko" class="form-label">Pilih Toko</label>
                            <select id="kdtoko" name="kdtoko" class="form-control" required>
                                <option value="">-- Pilih Toko --</option>
                                @foreach($toko as $t)
                                    <option value="{{ $t->kdtoko }}">{{ $t->namatoko }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="nopp" class="form-label">No. PP</label>
                            <input type="text" id="nopp" name="nopp" class="form-control" readonly>
                        </div>
                    </div>

                    <!-- Kolom kanan -->
                    <div class="right-col">
                        <div class="mb-3">
                            <label for="tanggal" class="form-label">Tanggal Permintaan</label>
                            <input type="text" id="tanggal" value="{{ now()->format('d-m-Y H:i') }}" readonly>
                        </div>

                        <div class="mb-3">
                            <label for="tanggal-ditetapkan" class="form-label">Tanggal Ditetapkan</label>
                            <input type="date" id="tanggal-ditetapkan" name="tanggal_dibutuhkan" required>
                        </div>
                    </div>
                </div>

                <div class="divider"></div>

                <div class="table-section">
                    <h2>Daftar Barang</h2>

                    <div class="mb-3">
                        <label for="barang" class="form-label">Cari Barang</label>
                        <input type="text" id="barang" class="form-control" placeholder="Ketik nama barang...">
                        <input type="hidden" id="kdbarang" name="kdbarang">
                    </div>

                    <div id="supplier-info" style="display:none; margin-top:10px;">
                        <strong>Supplier:</strong> <span id="supplier-name"></span>
                    </div>

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
                                    <th>Supplier 3</th>
                                    <th>Harga 3</th>
                                    <th>Supplier</th>
                                </tr>
                            </thead>
                            <tbody id="barang-body">
                                <tr>
                                    <td><input type="text" name="namabarang[]" placeholder="Nama barang"></td>
                                    <td><input type="text" name="spesifikasi[]" placeholder="Spesifikasi"></td>
                                    <td><input type="number" name="jumlah[]" placeholder="Jumlah" min="1"></td>
                                    <td>
                                        <select name="satuan[]">
                                            <option value="">Pilih Satuan</option>
                                            <option value="pcs">Pcs</option>
                                            <option value="kg">Kg</option>
                                        </select>
                                    </td>
                                    <td><input type="text" name="supplier1[]" placeholder="Supplier 1"></td>
                                    <td><input type="number" name="harga1[]" placeholder="Harga" min="0"></td>
                                    <td><input type="text" name="supplier2[]" placeholder="Supplier 2"></td>
                                    <td><input type="number" name="harga2[]" placeholder="Harga" min="0"></td>
                                    <td><input type="text" name="supplier3[]" placeholder="Supplier 3"></td>
                                    <td><input type="number" name="harga3[]" placeholder="Harga" min="0"></td>
                                    <td>
                                        <select name="supplier_pilih[]">
                                            <option value="">Pilih Supplier</option>
                                            <option value="supplier1">Supplier 1</option>
                                            <option value="supplier2">Supplier 2</option>
                                            <option value="supplier3">Supplier 3</option>
                                        </select>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <div class="table-actions">
                        <button type="button" class="btn-add" id="tambah-barang">
                            <i class="fas fa-plus"></i> Tambah Barang
                        </button>
                    </div>
                </div>

                <div class="form-actions">
                    <button type="submit" class="btn-primary">
                        <i class="fas fa-paper-plane"></i> KIRIM
                    </button>
                    <button type="button" class="btn-secondary" onclick="window.print()">
                        <i class="fas fa-print"></i> Cetak Form PP
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        $(function() {
            $("#barang").autocomplete({
                source: function(request, response) {
                    $.ajax({
                        url: "{{ route('formulirpp.search.barang') }}",
                        data: { q: request.term },
                        success: function(data) {
                            response($.map(data, function(item) {
                                return {
                                    label: item.namabarang,
                                    value: item.namabarang,
                                    kdbarang: item.kdbarang,
                                    supplier: item.supplier && item.supplier.namasupplier ? item.supplier.namasupplier : '-'
                                };
                            }));
                        }
                    });
                },
                select: function(event, ui) {
                    $("#kdbarang").val(ui.item.kdbarang);
                    $("#supplier-name").text(ui.item.supplier);
                    $("#supplier-info").show();

                    const tableBody = document.getElementById('barang-body');
                    const firstRow = tableBody.querySelector("tr:first-child td input");

                    // kalau baris pertama masih kosong → isi ulang, jangan bikin baris baru
                    if (firstRow && firstRow.value === "") {
                        tableBody.querySelector("tr:first-child").innerHTML = `
                            <td><input type="text" name="namabarang[]" value="${ui.item.value}" readonly></td>
                            <td><input type="text" name="spesifikasi[]" placeholder="Spesifikasi"></td>
                            <td><input type="number" name="jumlah[]" placeholder="Jumlah" min="1"></td>
                            <td>
                                <select name="satuan[]">
                                    <option value="">Pilih Satuan</option>
                                    <option value="pcs">Pcs</option>
                                    <option value="kg">Kg</option>
                                    <option value="unit">Unit</option>
                                    <option value="meter">Meter</option>
                                    <option value="lusin">Lusin</option>
                                </select>
                            </td>
                            <td><input type="text" name="supplier1[]" value="${ui.item.supplier}" readonly></td>
                            <td><input type="number" name="harga1[]" placeholder="Harga" min="0"></td>
                            <td><input type="text" name="supplier2[]" placeholder="Supplier 2"></td>
                            <td><input type="number" name="harga2[]" placeholder="Harga" min="0"></td>
                            <td><input type="text" name="supplier3[]" placeholder="Supplier 3"></td>
                            <td><input type="number" name="harga3[]" placeholder="Harga" min="0"></td>
                            <td>
                                <select name="supplier_pilih[]">
                                    <option value="">Pilih Supplier</option>
                                    <option value="supplier1">Supplier 1</option>
                                    <option value="supplier2">Supplier 2</option>
                                    <option value="supplier3">Supplier 3</option>
                                </select>
                            </td>
                        `;
                    } else {
                        // kalau sudah ada isi → tambahkan baris baru
                        const newRow = document.createElement('tr');
                        newRow.innerHTML = `
                            <td><input type="text" name="namabarang[]" value="${ui.item.value}" readonly></td>
                            <td><input type="text" name="spesifikasi[]" placeholder="Spesifikasi"></td>
                            <td><input type="number" name="jumlah[]" placeholder="Jumlah" min="1"></td>
                            <td>
                                <select name="satuan[]">
                                    <option value="">Pilih Satuan</option>
                                    <option value="pcs">Pcs</option>
                                    <option value="kg">Kg</option>
                                </select>
                            </td>
                            <td><input type="text" name="supplier1[]" value="${ui.item.supplier}" readonly></td>
                            <td><input type="number" name="harga1[]" placeholder="Harga" min="0"></td>
                            <td><input type="text" name="supplier2[]" placeholder="Supplier 2"></td>
                            <td><input type="number" name="harga2[]" placeholder="Harga" min="0"></td>
                            <td><input type="text" name="supplier3[]" placeholder="Supplier 3"></td>
                            <td><input type="number" name="harga3[]" placeholder="Harga" min="0"></td>
                            <td>
                                <select name="supplier_pilih[]">
                                    <option value="">Pilih Supplier</option>
                                    <option value="supplier1">Supplier 1</option>
                                    <option value="supplier2">Supplier 2</option>
                                    <option value="supplier3">Supplier 3</option>
                                </select>
                            </td>
                        `;
                        tableBody.appendChild(newRow);
                    }

                    $("#barang").val("");
                    return false;
                }
            });
        });

        document.addEventListener('DOMContentLoaded', function() {
            const tambahBarangBtn = document.getElementById('tambah-barang');
            const tableBody = document.getElementById('barang-body');

            // Tambah baris baru
            tambahBarangBtn.addEventListener('click', function() {
                const newRow = document.createElement('tr');
                newRow.innerHTML = `
                    <td><input type="text" name="namabarang[]" placeholder="Nama barang"></td>
                    <td><input type="text" name="spesifikasi[]" placeholder="Spesifikasi"></td>
                    <td><input type="number" name="jumlah[]" placeholder="Jumlah" min="1"></td>
                    <td>
                        <select name="satuan[]">
                            <option value="">Pilih Satuan</option>
                            <option value="pcs">Pcs</option>
                            <option value="unit">Unit</option>
                            <option value="kg">Kg</option>
                            <option value="meter">Meter</option>
                            <option value="lusin">Lusin</option>
                        </select>
                    </td>
                    <td><input type="text" name="supplier1[]" placeholder="Supplier 1"></td>
                    <td><input type="number" name="harga1[]" placeholder="Harga" min="0"></td>
                    <td><input type="text" name="supplier2[]" placeholder="Supplier 2"></td>
                    <td><input type="number" name="harga2[]" placeholder="Harga" min="0"></td>
                    <td><input type="text" name="supplier3[]" placeholder="Supplier 3"></td>
                    <td><input type="number" name="harga3[]" placeholder="Harga" min="0"></td>
                    <td>
                        <select name="supplier_pilih[]">
                            <option value="">Pilih Supplier</option>
                            <option value="supplier1">Supplier 1</option>
                            <option value="supplier2">Supplier 2</option>
                            <option value="supplier3">Supplier 3</option>
                        </select>
                    </td>
                `;
                tableBody.appendChild(newRow);
            });

            // Generate nomor PP otomatis
            document.getElementById('kdtoko').addEventListener('change', function() {
                let kdtoko = this.value;

                if (kdtoko) {
                    fetch(`/generate-nopp/${kdtoko}`)
                        .then(response => response.json())
                        .then(data => {
                            document.getElementById('nopp').value = data.nopp;
                        });
                } else {
                    document.getElementById('nopp').value = '';
                }
            });
        });
    </script>
</body>
</html>
