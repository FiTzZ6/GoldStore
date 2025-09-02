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
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show mt-3" role="alert">
            <i class="fa fa-check-circle"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show mt-3" role="alert">
            <i class="fa fa-exclamation-circle"></i> {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
    <div class="container">
        <form action="{{ route('transpenjualan.store') }}" method="POST" id="formTransaksi">
            @csrf
            {{-- Hidden input untuk data transaksi --}}
            {{-- Hidden input, ini yang dikirim ke Laravel --}}
            <input type="hidden" id="form-nofaktur" name="nofaktur">
            <input type="hidden" id="form-staff" name="namastaff">
            <input type="hidden" id="form-pelanggan" name="namapelanggan">
            <input type="hidden" id="form-nohp" name="nohp">
            <input type="hidden" id="form-alamat" name="alamat">
            <input type="hidden" id="form-pembayaran" name="pembayaran">
            <input type="hidden" id="form-items" name="items">

            <div class="top-bar">
                <h1>TRANSAKSI PENJUALAN</h1>
                <div class="date">Tanggal: <span id="current-date"></span></div>
                <div class="invoice-input">
                    <label for="invoice-no">No. Faktur:</label>
                    <input type="text" id="invoice-no" value="{{ $nofaktur }}" readonly>
                </div>
                <input type="hidden" id="form-nofaktur" name="nofaktur" value="{{ $nofaktur }}">
            </div>

            <div class="main-content">
                <div class="left-panel">
                    <div class="form-group">
                        <label for="staff-code">Nama Staff</label>
                        <input type="text" id="staff-code" placeholder="Masukkan Nama Staff" list="list_staff">
                        <datalist id="list_staff">
                            @foreach($staff as $karyawan)
                                <option value="{{ $karyawan->nama }}"></option>
                            @endforeach
                        </datalist>
                    </div>

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

                    <div class="divider"></div>

                    <h3>Daftar Produk</h3>
                    <div class="product-list">
                        @foreach($stokjual as $item)
                            <div class="product-item" data-code="{{ $item->barcode }}" data-name="{{ $item->namabarang }}"
                                data-price="{{ $item->hargajual }}" data-fee="{{ $item->ongkos ?? 0 }}">
                                <div class="product-name">{{ $item->namabarang }}</div>
                                <div class="product-code">Kode: {{ $item->barcode }}</div>
                                <div class="product-code">qty: {{ $item->stok }}</div>
                                <div class="product-price">Rp {{ number_format($item->hargajual, 0, ',', '.') }}</div>
                            </div>
                        @endforeach
                    </div>
                </div>

                <div class="right-panel">
                    <!-- <input type="hidden" name="items" id="form-items">
                    <input type="hidden" name="pembayaran" id="form-pembayaran"> -->

                    <div class="invoice-header">
                        <div class="invoice-input">
                            <label for="invoice-no">No. Faktur:</label>
                            <input type="text" id="invoice-no" value="{{ $nofaktur }}" readonly>
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
                        <div class="summary-row">
                            <span>Subtotal</span>
                            <span id="subtotal">Rp 0</span>
                        </div>
                        <div class="summary-row">
                            <span>Pajak (10%)</span>
                            <span id="tax">Rp 0</span>
                        </div>
                        <div class="summary-row grand-total">
                            <span>GRAND TOTAL</span>
                            <span id="grand-total">Rp 0</span>
                        </div>
                    </div>

                    <div class="payment-section">
                        <h3>Metode Pembayaran</h3>
                        <label><input type="radio" name="payment" value="Tunai" checked> Tunai</label>
                        <label><input type="radio" name="payment" value="Debit"> Kartu Debit</label>
                        <label><input type="radio" name="payment" value="Kredit"> Kartu Kredit</label>
                        <label><input type="radio" name="payment" value="Transfer"> Transfer Bank</label>
                    </div>

                    <div class="actions">
                        <button type="button" class="btn btn-primary" id="new-invoice-btn">
                            <i class="fas fa-file-invoice"></i> Invoice Baru
                        </button>
                        <button type="submit" class="btn btn-success">
                            <i class="fas fa-credit-card"></i> BAYAR
                        </button>
                        <button type="button" class="btn btn-warning" >
                            <i class="fas fa-receipt"></i> Cetak Struk
                        </button>
                        <button type="button" class="btn btn-danger" id="reset-btn" >
                            <i class="fas fa-redo"></i> RESET
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>


    <script>
        let currentInvoice = {
            number: "FJ-1250507-0001",
            items: [],
            subtotal: 0,
            tax: 0,
            discount: 0,
            grandTotal: 0
        };

        // 🔹 Generate nomor faktur baru
        function generateInvoiceNumber() {
            const prefix = "FJ-";
            const year = new Date().getFullYear();
            const month = String(new Date().getMonth() + 1).padStart(2, '0');
            const randomNum = String(Math.floor(Math.random() * 10000)).padStart(4, '0');
            return `${prefix}${year}${month}-${randomNum}`;
        }

        // 🔹 Membuat invoice baru
        function createNewInvoice() {
            currentInvoice = {
                number: generateInvoiceNumber(),
                items: [],
                subtotal: 0,
                tax: 0,
                discount: 0,
                grandTotal: 0
            };

            // update UI
            document.getElementById('invoice-no').value = currentInvoice.number;
            document.getElementById('display-invoice').textContent = currentInvoice.number;
            document.querySelector('#invoice-table tbody').innerHTML = '';
            updateSummary();

            alert(`Invoice baru dibuat: ${currentInvoice.number}`);
        }

        // 🔹 Tambah produk ke invoice
        function addProductToInvoice(product) {
            const existingIndex = currentInvoice.items.findIndex(item => item.code === product.code);
            if (existingIndex !== -1) {
                currentInvoice.items[existingIndex].quantity++;
                currentInvoice.items[existingIndex].total =
                    currentInvoice.items[existingIndex].quantity *
                    (currentInvoice.items[existingIndex].price + currentInvoice.items[existingIndex].fee);
            } else {
                currentInvoice.items.push({
                    code: product.code,
                    name: product.name,
                    price: product.price,
                    fee: product.fee,
                    quantity: 1,
                    total: product.price + product.fee
                });
            }
            renderInvoiceItems();
            updateSummary();
        }

        // 🔹 Render daftar item ke tabel
        function renderInvoiceItems() {
            const tbody = document.querySelector('#invoice-table tbody');
            tbody.innerHTML = '';
            currentInvoice.items.forEach((item, index) => {
                const row = document.createElement('tr');
                row.innerHTML = `
            <td>${item.code}</td>
            <td>${item.name}</td>
            <td>
                <div class="qty-control">
                    <button type="button" onclick="updateQuantity(${index},-1)">-</button>
                    <input type="number" value="${item.quantity}" min="1" onchange="updateQuantityInput(${index}, this.value)">
                    <button type="button" onclick="updateQuantity(${index},1)">+</button>
                </div>
            </td>
            <td>${formatCurrency(item.price)}</td>
            <td>${formatCurrency(item.fee)}</td>
            <td>${formatCurrency(item.total)}</td>
            <td><button type="button" onclick="removeItem(${index})">Hapus</button></td>
        `;
                tbody.appendChild(row);
            });
        }

        // 🔹 Update qty via tombol
        function updateQuantity(index, change) {
            currentInvoice.items[index].quantity += change;
            if (currentInvoice.items[index].quantity < 1) currentInvoice.items[index].quantity = 1;
            currentInvoice.items[index].total =
                currentInvoice.items[index].quantity *
                (currentInvoice.items[index].price + currentInvoice.items[index].fee);
            renderInvoiceItems();
            updateSummary();
        }

        // 🔹 Update qty via input number
        function updateQuantityInput(index, value) {
            const qty = parseInt(value) || 1;
            currentInvoice.items[index].quantity = qty < 1 ? 1 : qty;
            currentInvoice.items[index].total =
                currentInvoice.items[index].quantity *
                (currentInvoice.items[index].price + currentInvoice.items[index].fee);
            renderInvoiceItems();
            updateSummary();
        }

        // 🔹 Hapus item
        function removeItem(index) {
            currentInvoice.items.splice(index, 1);
            renderInvoiceItems();
            updateSummary();
        }

        // 🔹 Hitung subtotal, pajak, grand total
        function updateSummary() {
            currentInvoice.subtotal = currentInvoice.items.reduce((sum, i) => sum + i.total, 0);
            currentInvoice.tax = currentInvoice.subtotal * 0.1;
            currentInvoice.grandTotal = currentInvoice.subtotal + currentInvoice.tax;

            document.getElementById('subtotal').textContent = formatCurrency(currentInvoice.subtotal);
            document.getElementById('tax').textContent = formatCurrency(currentInvoice.tax);
            document.getElementById('grand-total').textContent = formatCurrency(currentInvoice.grandTotal);
            document.getElementById('item-count').textContent = currentInvoice.items.reduce((sum, i) => sum + i.quantity, 0);
        }

        // 🔹 Format uang
        function formatCurrency(amount) {
            return 'Rp ' + amount.toLocaleString('id-ID');
        }

        // 🔹 Isi otomatis alamat & telp pelanggan
        document.addEventListener('DOMContentLoaded', () => {
            const nameInput = document.getElementById('customer-name');
            const addressInput = document.getElementById('customer-address');
            const phoneInput = document.getElementById('customer-phone');
            const dataList = document.getElementById('list_nama');

            nameInput.addEventListener('input', function () {
                const val = this.value;
                const option = [...dataList.options].find(o => o.value === val);
                if (option) {
                    addressInput.value = option.dataset.address || '';
                    phoneInput.value = option.dataset.phone || '';
                } else {
                    addressInput.value = '';
                    phoneInput.value = '';
                }
            });

            // set tanggal
            const now = new Date();
            document.getElementById('current-date').textContent = now.toLocaleDateString('id-ID', { day: '2-digit', month: '2-digit', year: 'numeric' });

            // klik produk → tambah item
            document.querySelectorAll('.product-item').forEach(el => {
                el.addEventListener('click', () => {
                    const code = el.dataset.code;
                    const name = el.dataset.name;
                    const price = parseInt(el.dataset.price);
                    const fee = parseInt(el.dataset.fee);
                    addProductToInvoice({ code, name, price, fee });
                });
            });

            // tombol invoice baru
            document.getElementById('new-invoice-btn').addEventListener('click', createNewInvoice);

            // tombol reset
            document.getElementById('reset-btn').addEventListener('click', () => {
                if (confirm('Yakin reset invoice?')) createNewInvoice();
            });

            // tombol print
            document.getElementById('print-btn').addEventListener('click', () => {
                if (currentInvoice.items.length === 0) {
                    alert('Tidak ada item, tambah produk dulu.');
                    return;
                }
                window.print();
            });

            // 🔹 submit ke Laravel via form
            document.getElementById('formTransaksi').addEventListener('submit', e => {
                if (currentInvoice.items.length === 0) {
                    e.preventDefault();
                    alert('Tambahkan produk terlebih dahulu.');
                    return;
                }

                document.getElementById('form-nofaktur').value = currentInvoice.number;
                document.getElementById('form-staff').value = document.getElementById('staff-code').value;
                document.getElementById('form-pelanggan').value = document.getElementById('customer-name').value;
                document.getElementById('form-nohp').value = document.getElementById('customer-phone').value;
                document.getElementById('form-alamat').value = document.getElementById('customer-address').value;

                // 🔹 samakan dengan name="payment"
                document.getElementById('form-pembayaran').value =
                    document.querySelector('input[name="payment"]:checked')?.value || '';

                document.getElementById('form-items').value = JSON.stringify(currentInvoice.items);
            });
        });
    </script>

</body>

</html>