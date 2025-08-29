<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="{{ asset('css/barang/cucisepuh/riwayatcuci.css') }}">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>

<body>
@include('partials.navbar')
    <div class="container">
        <div class="header">
            <h1>Riwayat Cuci Sepuh</h1>
            <div class="date-filter">
                <input type="date" id="start-date">
                <span>s/d</span>
                <input type="date" id="end-date">
                <button class="filter-btn"><i class="fas fa-filter"></i> Filter</button>
            </div>
        </div>

        <div class="content">

            <div class="transaction-list">
                <div class="transaction-card">
                    <div class="transaction-header">
                        <span class="transaction-id">#CS-20250807-001</span>
                        <span class="transaction-status completed">Selesai</span>
                    </div>
                    <div class="transaction-details">
                        <div class="item">
                            <div class="item-info">
                                <h3>Cuci Sepuh Perhiasan Kecil</h3>
                                <p>1 item x Rp 45.000</p>
                            </div>
                            <div class="item-total">Rp 45.000</div>
                        </div>
                    </div>
                    <div class="transaction-footer">
                        <div class="transaction-total">Total: Rp 45.000</div>
                        <button class="detail-btn">Lihat Detail</button>
                    </div>
                </div>

                <div class="transaction-card">
                    <div class="transaction-header">
                        <span class="transaction-id">#CS-20250807-002</span>
                        <span class="transaction-status completed">Selesai</span>
                    </div>
                    <div class="transaction-details">
                        <div class="item">
                            <div class="item-info">
                                <h3>Cuci Sepuh Perhiasan Besar</h3>
                                <p>2 item x Rp 75.000</p>
                            </div>
                            <div class="item-total">Rp 150.000</div>
                        </div>
                    </div>
                    <div class="transaction-footer">
                        <div class="transaction-total">Total: Rp 150.000</div>
                        <button class="detail-btn">Lihat Detail</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>

</html>