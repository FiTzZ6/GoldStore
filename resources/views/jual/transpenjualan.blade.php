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
    <div class="container">

        <div class="top-bar">
            <h1>TRANSAKSI PENJUALAN</h1>
            <div class="date">Tanggal: <span id="current-date"></span></div>
            <div class="invoice-input">
                <label for="invoice-no">No. Faktur:</label>
                <input type="text" id="invoice-no" value="FJ-1250507-0001" readonly>
            </div>
        </div>

        <div class="main-content">
            <div class="left-panel">
                <div class="form-group">
                    <label for="staff-code">Nama Staff</label>
                    <input type="text" id="staff-code" placeholder="Masukkan Nama Staff" list="list_staff">
                    <datalist id="list_staff">
                        @foreach($staff as $karyawan)
                            <option value="{{ $karyawan->nama }}" >
                            </option>
                        @endforeach
                    </datalist>
                </div>
                
                <div class="form-group">
                    <label for="customer-name">Nama Pelanggan</label>
                    <input type="text" id="customer-name" placeholder="Masukkan nama pelanggan" list="list_nama">
                    <datalist id="list_nama">
                        @foreach($pelanggan as $orang)
                            <option value="{{ $orang->namapelanggan }}" 
                                    data-address="{{ $orang->alamatpelanggan }}" 
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
                <!-- <input type="text" placeholder="Search" id="searchInput"> -->
                <div class="product-list">
                    <div class="product-item" data-code="BRG-001" data-name="Produk Sample 1" data-price="50000" data-fee="5000">
                        <div class="product-name">Produk Sample 1</div>
                        <div class="product-code">Kode: BRG-001</div>
                        <div class="product-price">Rp 50.000</div>
                    </div>
                    <div class="product-item" data-code="BRG-002" data-name="Produk Sample 2" data-price="75000" data-fee="7500">
                        <div class="product-name">Produk Sample 2</div>
                        <div class="product-code">Kode: BRG-002</div>
                        <div class="product-price">Rp 75.000</div>
                    </div>
                    <div class="product-item" data-code="BRG-003" data-name="Produk Sample 3" data-price="30000" data-fee="3000">
                        <div class="product-name">Produk Sample 3</div>
                        <div class="product-code">Kode: BRG-003</div>
                        <div class="product-price">Rp 30.000</div>
                    </div>
                    <div class="product-item" data-code="BRG-004" data-name="Produk Sample 4" data-price="120000" data-fee="10000">
                        <div class="product-name">Produk Sample 4</div>
                        <div class="product-code">Kode: BRG-004</div>
                        <div class="product-price">Rp 120.000</div>
                    </div>
                </div>
            </div>
            
            <div class="right-panel">
                <div class="invoice-header">
                    <div class="invoice-no">No. Faktur: <span id="display-invoice">FJ-1250507-0001</span></div>
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
                    <tbody>
                        <!-- Items will be added here dynamically -->
                    </tbody>
                </table>
                
                <div class="summary">
                    <div class="summary-row">
                        <span class="summary-label">Subtotal</span>
                        <span id="subtotal">Rp 0</span>
                    </div>
                    <div class="summary-row">
                        <span class="summary-label">Pajak (10%)</span>
                        <span id="tax">Rp 0</span>
                    </div>
                    <div class="summary-row">
                        <span class="summary-label">DISCOUNT (0%)</span>
                        <span id="discount">Rp 0</span>
                    </div>
                    <div class="summary-row grand-total">
                        <span class="summary-label">GRAND TOTAL</span>
                        <span id="grand-total">Rp 0</span>
                    </div>
                </div>
                
                <div class="payment-section">
                    <h3>Metode Pembayaran</h3>
                    <div class="payment-methods">
                        <label class="payment-method">
                            <input type="radio" name="payment" checked> Tunai
                        </label>
                        <label class="payment-method">
                            <input type="radio" name="payment"> Kartu Debit
                        </label>
                        <label class="payment-method">
                            <input type="radio" name="payment"> Kartu Kredit
                        </label>
                        <label class="payment-method">
                            <input type="radio" name="payment"> Transfer Bank
                        </label>
                    </div>
                </div>
                
                <div class="actions">
                    <button class="btn btn-primary" id="new-invoice-btn">
                        <i class="fas fa-file-invoice"></i> Invoice Baru
                    </button>
                    <button class="btn btn-success" id="pay-btn">
                        <i class="fas fa-credit-card"></i> BAYAR
                    </button>
                    <button class="btn btn-warning" id="print-btn">
                        <i class="fas fa-receipt"></i> Cetak Struk
                    </button>
                    <button class="btn btn-danger" id="reset-btn">
                        <i class="fas fa-redo"></i> RESET
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Product database
        const products = [
            { code: "BRG-001", name: "Produk Sample 1", price: 50000, fee: 5000 },
            { code: "BRG-002", name: "Produk Sample 2", price: 75000, fee: 7500 },
            { code: "BRG-003", name: "Produk Sample 3", price: 30000, fee: 3000 },
            { code: "BRG-004", name: "Produk Sample 4", price: 120000, fee: 10000 }
        ];
        
        // Invoice data
        let currentInvoice = {
            number: "FJ-1250507-0001",
            items: [],
            subtotal: 0,
            tax: 0,
            discount: 0,
            grandTotal: 0
        };
        
        // Function to generate a new invoice number
        function generateInvoiceNumber() {
            const prefix = "FJ-";
            const year = new Date().getFullYear();
            const month = String(new Date().getMonth() + 1).padStart(2, '0');
            const randomNum = String(Math.floor(Math.random() * 10000)).padStart(4, '0');
            return `${prefix}${year}${month}-${randomNum}`;
        }
        
        // Function to create a new invoice
        function createNewInvoice() {
            currentInvoice = {
                number: generateInvoiceNumber(),
                items: [],
                subtotal: 0,
                tax: 0,
                discount: 0,
                grandTotal: 0
            };
            
            // Update UI
            document.getElementById('invoice-no').value = currentInvoice.number;
            document.getElementById('display-invoice').textContent = currentInvoice.number;
            document.getElementById('invoice-table').querySelector('tbody').innerHTML = '';
            updateSummary();
            
            // Show notification
            alert(`Invoice baru telah dibuat: ${currentInvoice.number}`);
        }
        
        // Function to add product to invoice
        function addProductToInvoice(product) {
            // Check if product already exists in invoice
            const existingItemIndex = currentInvoice.items.findIndex(item => item.code === product.code);
            
            if (existingItemIndex !== -1) {
                // Increase quantity if product already exists
                currentInvoice.items[existingItemIndex].quantity += 1;
                currentInvoice.items[existingItemIndex].total = 
                    currentInvoice.items[existingItemIndex].quantity * 
                    (currentInvoice.items[existingItemIndex].price + currentInvoice.items[existingItemIndex].fee);
            } else {
                // Add new product to invoice
                currentInvoice.items.push({
                    code: product.code,
                    name: product.name,
                    price: product.price,
                    fee: product.fee,
                    quantity: 1,
                    total: product.price + product.fee
                });
            }
            
            // Update UI
            renderInvoiceItems();
            updateSummary();
        }
        
        // Function to render invoice items
        function renderInvoiceItems() {
            const tbody = document.getElementById('invoice-table').querySelector('tbody');
            tbody.innerHTML = '';
            
            currentInvoice.items.forEach((item, index) => {
                const row = document.createElement('tr');
                row.innerHTML = `
                    <td>${item.code}</td>
                    <td>${item.name}</td>
                    <td>
                        <div class="qty-control">
                            <button class="qty-btn" onclick="updateQuantity(${index}, -1)">-</button>
                            <input type="number" class="qty-input" value="${item.quantity}" min="1" onchange="updateQuantityInput(${index}, this.value)">
                            <button class="qty-btn" onclick="updateQuantity(${index}, 1)">+</button>
                        </div>
                    </td>
                    <td>${formatCurrency(item.price)}</td>
                    <td>${formatCurrency(item.fee)}</td>
                    <td>${formatCurrency(item.total)}</td>
                    <td>
                        <button class="qty-btn" style="background: #e74c3c;" onclick="removeItem(${index})">
                            <i class="fas fa-trash"></i>
                        </button>
                    </td>
                `;
                tbody.appendChild(row);
            });
        }
        
        // Fungsi Isi auto data transaksi penjualan
        const nameInput = document.getElementById('customer-name');
        const addressInput = document.getElementById('customer-address');
        const phoneInput = document.getElementById('customer-phone');
        const dataList = document.getElementById('list_nama');

        nameInput.addEventListener('input', function() {
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

        // Function to update item quantity
        function updateQuantity(index, change) {
            currentInvoice.items[index].quantity += change;
            
            if (currentInvoice.items[index].quantity < 1) {
                currentInvoice.items[index].quantity = 1;
            }
            
            currentInvoice.items[index].total = 
                currentInvoice.items[index].quantity * 
                (currentInvoice.items[index].price + currentInvoice.items[index].fee);
            
            renderInvoiceItems();
            updateSummary();
        }
        
        // Function to update quantity from input
        function updateQuantityInput(index, value) {
            const quantity = parseInt(value) || 1;
            currentInvoice.items[index].quantity = quantity;
            
            if (currentInvoice.items[index].quantity < 1) {
                currentInvoice.items[index].quantity = 1;
            }
            
            currentInvoice.items[index].total = 
                currentInvoice.items[index].quantity * 
                (currentInvoice.items[index].price + currentInvoice.items[index].fee);
            
            renderInvoiceItems();
            updateSummary();
        }
        
        // Function to remove item from invoice
        function removeItem(index) {
            currentInvoice.items.splice(index, 1);
            renderInvoiceItems();
            updateSummary();
        }
        
        // Function to update summary
        function updateSummary() {
            // Calculate subtotal
            currentInvoice.subtotal = currentInvoice.items.reduce((sum, item) => sum + item.total, 0);
            
            // Calculate tax (10%)
            currentInvoice.tax = currentInvoice.subtotal * 0.1;
            
            // Calculate grand total
            currentInvoice.grandTotal = currentInvoice.subtotal + currentInvoice.tax;
            
            // Update UI
            document.getElementById('subtotal').textContent = formatCurrency(currentInvoice.subtotal);
            document.getElementById('tax').textContent = formatCurrency(currentInvoice.tax);
            document.getElementById('grand-total').textContent = formatCurrency(currentInvoice.grandTotal);
            document.getElementById('item-count').textContent = currentInvoice.items.reduce((sum, item) => sum + item.quantity, 0);
        }
        
        // Function to format currency
        function formatCurrency(amount) {
            return 'Rp ' + amount.toLocaleString('id-ID');
        }
        
        // Function to set current date
        function setCurrentDate() {
            const now = new Date();
            const options = { day: '2-digit', month: '2-digit', year: 'numeric' };
            document.getElementById('current-date').textContent = now.toLocaleDateString('id-ID', options);
        }
        
        // Initialize on page load
        document.addEventListener('DOMContentLoaded', function() {
            setCurrentDate();
            
            // Add event listeners to product items
            document.querySelectorAll('.product-item').forEach(item => {
                item.addEventListener('click', function() {
                    const code = this.getAttribute('data-code');
                    const name = this.getAttribute('data-name');
                    const price = parseInt(this.getAttribute('data-price'));
                    const fee = parseInt(this.getAttribute('data-fee'));
                    
                    addProductToInvoice({ code, name, price, fee });
                });
            });
            
            // Add event listener to new invoice button
            document.getElementById('new-invoice-btn').addEventListener('click', createNewInvoice);
            
            // Add event listener to reset button
            document.getElementById('reset-btn').addEventListener('click', function() {
                if (confirm('Apakah Anda yakin ingin mereset invoice?')) {
                    createNewInvoice();
                }
            });
            
            // Add event listener to pay button
            document.getElementById('pay-btn').addEventListener('click', function() {
                if (currentInvoice.items.length === 0) {
                    alert('Tidak ada item dalam invoice. Silakan tambahkan item terlebih dahulu.');
                    return;
                }
                
                alert(`Pembayaran berhasil untuk invoice ${currentInvoice.number}\nTotal: ${formatCurrency(currentInvoice.grandTotal)}`);
                createNewInvoice();
            });
            
            // Add event listener to print button
            document.getElementById('print-btn').addEventListener('click', function() {
                if (currentInvoice.items.length === 0) {
                    alert('Tidak ada item dalam invoice. Silakan tambahkan item terlebih dahulu.');
                    return;
                }
                
                window.print();
            });
        });
    </script>
</body>
</html>