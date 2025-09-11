<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="{{ asset('css/jual/transpenjualan.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>

<body>
    @include('partials.navbar')

    {{-- Alert sukses / error --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show mt-3" role="alert">
            <i class="fa fa-check-circle"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show mt-3" role="alert">
            <i class="fa fa-exclamation-circle"></i> {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="container">
        <form action="{{ route('transpenjualan.store') }}" method="POST" id="formTransaksi">
            @csrf
            {{-- Hidden input --}}
            <input type="hidden" id="form-nofaktur" name="nofaktur" value="{{ $nofaktur }}">
            <input type="hidden" id="form-staff" name="namastaff">
            <input type="hidden" id="form-pelanggan" name="namapelanggan">
            <input type="hidden" id="form-nohp" name="nohp">
            <input type="hidden" id="form-alamat" name="alamat">
            <input type="hidden" id="form-pembayaran" name="pembayaran">
            <input type="hidden" id="form-items" name="items">
            <!-- <input type="hidden" id="form-typepesanan" name="typepesanan"> -->
            <input type="hidden" id="form-tglpesan" name="tglpesan">
            <input type="hidden" id="form-tglambil" name="tglambil">

            <div class="top-bar">
                <h1>TRANSAKSI PENJUALAN</h1>
                <div class="date">Tanggal: <span id="current-date"></span></div>
                <div class="invoice-input">
                    <label for="invoice-no">No. Faktur:</label>
                    <input type="text" id="invoice-no" value="{{ $nofaktur }}" readonly>
                </div>
            </div>

            <div class="main-content">
                <div class="left-panel">
                    {{-- Staff --}}
                    <div class="form-group">
                        <label for="staff-code">Nama Staff</label>
                        <input type="text" id="staff-code" placeholder="Masukkan Nama Staff" list="list_staff">
                        <datalist id="list_staff">
                            @foreach($staff as $karyawan)
                                <option value="{{ $karyawan->nama }}"></option>
                            @endforeach
                        </datalist>
                    </div>

                    {{-- Pelanggan --}}
                    <div class="form-group">
                        <label for="customer-name">Nama Pelanggan</label>
                        <input type="text" id="customer-name" placeholder="Masukkan nama pelanggan" list="list_nama">
                        <datalist id="list_nama">
                            @foreach($pelanggan as $orang)
                                <option value="{{ $orang->namapelanggan }}" data-address="{{ $orang->alamatpelanggan }}"
                                    data-phone="{{ $orang->notelp }}">
                                </option>
                            @endforeach
                        </datalist>
                    </div>

                    <div class="form-group">
                        <label for="customer-address">Alamat Pelanggan</label>
                        <textarea id="customer-address" rows="2" placeholder="Masukkan alamat pelanggan"></textarea>
                    </div>

                    <div class="form-group">
                        <label for="customer-phone">No. Telepon</label>
                        <input type="text" id="customer-phone" placeholder="Masukkan nomor telepon pelanggan">
                    </div>

                    {{-- 🔹 Tipe pesanan --}}
                    <label for="typepesanan">Tipe Pesanan</label>
                    <input list="typepesanan-options" id="typepesanan" name="typepesanan" required>
                    <datalist id="typepesanan-options">
                        <option value="umum">
                        <option value="pesanan">
                    </datalist>

                    {{-- 🔹 Field tambahan untuk pesanan --}}
                    <div id="pesanan-fields" style="display:none; margin-top:15px;">
                        <div class="form-group">
                            <label for="tglpesan">Tanggal Pesan</label>
                            <input type="date" id="tglpesan" name="tglpesan" class="form-control">
                        </div>
                        <div class="form-group">
                            <label for="tglambil">Tanggal Ambil</label>
                            <input type="date" id="tglambil" name="tglambil" class="form-control">
                        </div>
                    </div>

                    <div class="divider"></div>

                    {{-- Produk --}}
                    <h3>Daftar Produk</h3>
                    <div class="product-list">
                        @foreach($stokjual as $item)
                            <div class="product-item" data-code="{{ $item->barcode }}" data-name="{{ $item->namabarang }}"
                                data-price="{{ $item->hargajual }}" data-fee="{{ $item->ongkos ?? 0 }}">
                                <div class="product-name">{{ $item->namabarang }}</div>
                                <div class="product-code">Kode: {{ $item->barcode }}</div>
                                <div class="product-code">Stok: {{ $item->stok }}</div>
                                <div class="product-price">Rp {{ number_format($item->hargajual, 0, ',', '.') }}</div>
                            </div>
                        @endforeach
                    </div>
                </div>

                <div class="right-panel">
                    {{-- Invoice --}}
                    <div class="invoice-header">
                        <div class="invoice-input">
                            <label>No. Faktur:</label>
                            <input type="text" value="{{ $nofaktur }}" readonly>
                        </div>
                        <div class="items-count">Jumlah Barang: <span id="item-count">0</span></div>
                    </div>

                    <table id="invoice-table">
                        <thead>
                            <tr>
                                <th>Kode Barang</th>
                                <th>Nama Barang</th>
                                <th>Qty</th>
                                <th>Harga</th>
                                <th>Ongkos</th>
                                <th>Jumlah</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>

                    <div class="summary">
                        <div class="summary-row"><span>Subtotal</span><span id="subtotal">Rp 0</span></div>
                        <div class="summary-row"><span>Pajak (10%)</span><span id="tax">Rp 0</span></div>
                        <div class="summary-row grand-total"><span>GRAND TOTAL</span><span id="grand-total">Rp 0</span>
                        </div>
                    </div>

                    <div class="payment-section">
                        <h3>Metode Pembayaran</h3>
                        <label><input type="radio" name="payment" value="Tunai" checked> Tunai</label>
                        <label><input type="radio" name="payment" value="Debit"> Debit</label>
                        <label><input type="radio" name="payment" value="Kredit"> Kredit</label>
                        <label><input type="radio" name="payment" value="Transfer"> Transfer</label>
                    </div>

                    <div class="actions">
                        <button type="button" class="btn btn-primary" id="new-invoice-btn">
                            <i class="fas fa-file-invoice"></i> Invoice Baru
                        </button>
                        <button type="submit" class="btn btn-success">
                            <i class="fas fa-credit-card"></i> BAYAR
                        </button>
                        <button type="button" class="btn btn-warning" id="print-btn">
                            <i class="fas fa-receipt"></i> Cetak Struk
                        </button>
                        <button type="button" class="btn btn-danger" id="reset-btn">
                            <i class="fas fa-redo"></i> RESET
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>

    <script>
        let currentInvoice = {
            number: "{{ $nofaktur }}",
            items: [],
            subtotal: 0,
            tax: 0,
            grandTotal: 0
        };

        function generateInvoiceNumber() {
            const prefix = "FJ-";
            const now = new Date();
            const date = now.getFullYear().toString().slice(2) +
                String(now.getMonth() + 1).padStart(2, '0') +
                String(now.getDate()).padStart(2, '0');
            const randomNum = String(Math.floor(Math.random() * 10000)).padStart(4, '0');
            return `${prefix}${date}-${randomNum}`;
        }

        function createNewInvoice() {
            currentInvoice = { number: generateInvoiceNumber(), items: [], subtotal: 0, tax: 0, grandTotal: 0 };
            document.getElementById('invoice-no').value = currentInvoice.number;
            document.getElementById('form-nofaktur').value = currentInvoice.number;
            document.querySelector('#invoice-table tbody').innerHTML = '';
            updateSummary();
            alert(`Invoice baru dibuat: ${currentInvoice.number}`);
        }

        function addProductToInvoice(product) {
            const existing = currentInvoice.items.find(i => i.code === product.code);
            if (existing) {
                existing.quantity++;
                existing.total = existing.quantity * (existing.price + existing.fee);
            } else {
                currentInvoice.items.push({ ...product, quantity: 1, total: product.price + product.fee });
            }
            renderInvoiceItems();
            updateSummary();
        }

        function renderInvoiceItems() {
            const tbody = document.querySelector('#invoice-table tbody');
            tbody.innerHTML = '';
            currentInvoice.items.forEach((item, i) => {
                tbody.innerHTML += `
                <tr>
                    <td>${item.code}</td>
                    <td>${item.name}</td>
                    <td>
                        <div class="qty-control">
                            <button type="button" onclick="updateQty(${i},-1)">-</button>
                            <input type="number" value="${item.quantity}" min="1" onchange="setQty(${i}, this.value)">
                            <button type="button" onclick="updateQty(${i},1)">+</button>
                        </div>
                    </td>
                    <td>${formatCurrency(item.price)}</td>
                    <td>${formatCurrency(item.fee)}</td>
                    <td>${formatCurrency(item.total)}</td>
                    <td><button type="button" onclick="removeItem(${i})">Hapus</button></td>
                </tr>`;
            });
        }

        function updateQty(i, c) {
            currentInvoice.items[i].quantity = Math.max(1, currentInvoice.items[i].quantity + c);
            currentInvoice.items[i].total = currentInvoice.items[i].quantity * (currentInvoice.items[i].price + currentInvoice.items[i].fee);
            renderInvoiceItems(); updateSummary();
        }
        function setQty(i, v) {
            currentInvoice.items[i].quantity = Math.max(1, parseInt(v) || 1);
            currentInvoice.items[i].total = currentInvoice.items[i].quantity * (currentInvoice.items[i].price + currentInvoice.items[i].fee);
            renderInvoiceItems(); updateSummary();
        }
        function removeItem(i) { currentInvoice.items.splice(i, 1); renderInvoiceItems(); updateSummary(); }

        function updateSummary() {
            currentInvoice.subtotal = currentInvoice.items.reduce((s, i) => s + i.total, 0);
            currentInvoice.tax = currentInvoice.subtotal * 0.1;
            currentInvoice.grandTotal = currentInvoice.subtotal + currentInvoice.tax;
            document.getElementById('subtotal').textContent = formatCurrency(currentInvoice.subtotal);
            document.getElementById('tax').textContent = formatCurrency(currentInvoice.tax);
            document.getElementById('grand-total').textContent = formatCurrency(currentInvoice.grandTotal);
            document.getElementById('item-count').textContent = currentInvoice.items.reduce((s, i) => s + i.quantity, 0);
        }
        function formatCurrency(a) { return 'Rp ' + a.toLocaleString('id-ID'); }

        document.addEventListener('DOMContentLoaded', () => {
            const dataList = document.getElementById('list_nama');
            document.getElementById('customer-name').addEventListener('input', function () {
                const opt = [...dataList.options].find(o => o.value === this.value);
                document.getElementById('customer-address').value = opt?.dataset.address || '';
                document.getElementById('customer-phone').value = opt?.dataset.phone || '';
            });
            document.getElementById('current-date').textContent = new Date().toLocaleDateString('id-ID');

            document.querySelectorAll('.product-item').forEach(el => {
                el.addEventListener('click', () => addProductToInvoice({
                    code: el.dataset.code,
                    name: el.dataset.name,
                    price: parseInt(el.dataset.price),
                    fee: parseInt(el.dataset.fee)
                }));
            });

            document.getElementById('new-invoice-btn').addEventListener('click', createNewInvoice);
            document.getElementById('reset-btn').addEventListener('click', () => confirm('Reset invoice?') && createNewInvoice());
            document.getElementById('print-btn').addEventListener('click', () => currentInvoice.items.length ? window.print() : alert('Belum ada item.'));

            // 🔹 Toggle field pesanan (digabung di sini)
            document.getElementById('typepesanan').addEventListener('input', e => {
                document.getElementById('pesanan-fields').style.display =
                    e.target.value === 'pesanan' ? 'block' : 'none';
            });

            // 🔹 Submit form
            document.getElementById('formTransaksi').addEventListener('submit', e => {
                if (!currentInvoice.items.length) {
                    e.preventDefault();
                    return alert('Tambahkan produk dulu');
                }

                const tipe = document.getElementById('typepesanan').value.trim();

                if (tipe === 'pesanan') {
                    if (!document.getElementById('tglpesan').value || !document.getElementById('tglambil').value) {
                        e.preventDefault();
                        return alert('Tanggal pesan & ambil wajib diisi untuk pesanan');
                    }
                }

                // isi hidden input lain
                document.getElementById('form-nofaktur').value = currentInvoice.number;
                document.getElementById('form-staff').value = document.getElementById('staff-code').value;
                document.getElementById('form-pelanggan').value = document.getElementById('customer-name').value;
                document.getElementById('form-nohp').value = document.getElementById('customer-phone').value;
                document.getElementById('form-alamat').value = document.getElementById('customer-address').value;
                document.getElementById('form-pembayaran').value = document.querySelector('input[name="payment"]:checked')?.value || '';
                document.getElementById('form-tglpesan').value = document.getElementById('tglpesan').value;
                document.getElementById('form-tglambil').value = document.getElementById('tglambil').value;
                document.getElementById('form-items').value = JSON.stringify(currentInvoice.items);
            });
        });


    </script>
</body>

</html>