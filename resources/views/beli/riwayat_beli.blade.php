<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Riwayat Pembelian - TANGCAL</title>
    <link rel="stylesheet" href="{{ asset('css/beli/riwayat_beli.css') }}">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
@include('partials.navbar')
    <div class="container">
        <div class="header">
            <h1><i class="fas fa-history"></i> Riwayat Pembelian</h1>
            <div class="date-filter">
                <input type="date" id="start-date">
                <span>s/d</span>
                <input type="date" id="end-date">
                <button class="filter-btn"><i class="fas fa-filter"></i> Filter</button>
            </div>
        </div>

        <div class="content">
            <div class="date-header">
                <h2>07/08/2025</h2>
            </div>

            <div class="transaction-list">
                <div class="transaction-card">
                    <div class="transaction-header">
                        <span class="transaction-id">#TRX-20250807-001</span>
                        <span class="transaction-status completed">Selesai</span>
                    </div>
                    <div class="transaction-details">
                        <div class="item">
                            <div class="item-info">
                                <h3>TAMFILLKAN Regular</h3>
                                <p>1 item x Rp 45.000</p>
                            </div>
                            <div class="item-total">
                                Rp 45.000
                            </div>
                        </div>
                    </div>
                    <div class="transaction-footer">
                        <div class="transaction-total">
                            Total: Rp 45.000
                        </div>
                        <button class="detail-btn">Lihat Detail</button>
                    </div>
                </div>

                <div class="transaction-card">
                    <div class="transaction-header">
                        <span class="transaction-id">#TRX-20250807-002</span>
                        <span class="transaction-status completed">Selesai</span>
                    </div>
                    <div class="transaction-details">
                        <div class="item">
                            <div class="item-info">
                                <h3>TAMFILLKAN Premium</h3>
                                <p>2 item x Rp 75.000</p>
                            </div>
                            <div class="item-total">
                                Rp 150.000
                            </div>
                        </div>
                    </div>
                    <div class="transaction-footer">
                        <div class="transaction-total">
                            Total: Rp 150.000
                        </div>
                        <button class="detail-btn">Lihat Detail</button>
                    </div>
                </div>
            </div>

            <div class="date-header">
                <h2>06/08/2025</h2>
            </div>

            <div class="transaction-list">
                <div class="transaction-card">
                    <div class="transaction-header">
                        <span class="transaction-id">#TRX-20250806-001</span>
                        <span class="transaction-status completed">Selesai</span>
                    </div>
                    <div class="transaction-details">
                        <div class="item">
                            <div class="item-info">
                                <h3>TAMFILLKAN Regular</h3>
                                <p>3 item x Rp 45.000</p>
                            </div>
                            <div class="item-total">
                                Rp 135.000
                            </div>
                        </div>
                    </div>
                    <div class="transaction-footer">
                        <div class="transaction-total">
                            Total: Rp 135.000
                        </div>
                        <button class="detail-btn">Lihat Detail</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Set tanggal hari ini sebagai default
        document.addEventListener('DOMContentLoaded', function() {
            const today = new Date().toISOString().substr(0, 10);
            document.getElementById('start-date').value = today;
            document.getElementById('end-date').value = today;
        });
    </script>
</body>
</html>