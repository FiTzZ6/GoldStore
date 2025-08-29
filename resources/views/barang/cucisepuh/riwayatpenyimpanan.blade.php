<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="{{ asset('css/barang/cucisepuh/riwayatmutu.css') }}">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>

<body>
    <div class="container">
        <div class="header">
            <h1>Riwayat Penyimpanan Mutu</h1>
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
                        <span class="transaction-id">#PM-20250807-001</span>
                        <span class="transaction-status active">Aktif</span>
                    </div>
                    <div class="transaction-details">
                        <div class="item">
                            <div class="item-info">
                                <h3>Penyimpanan Emas 24K</h3>
                                <p>Durasi: 30 hari x Rp 10.000</p>
                            </div>
                            <div class="item-total">Rp 300.000</div>
                        </div>
                    </div>
                    <div class="transaction-footer">
                        <div class="transaction-total">Total: Rp 300.000</div>
                        <button class="detail-btn">Lihat Detail</button>
                    </div>
                </div>

                <div class="transaction-card">
                    <div class="transaction-header">
                        <span class="transaction-id">#PM-20250807-002</span>
                        <span class="transaction-status completed">Selesai</span>
                    </div>
                    <div class="transaction-details">
                        <div class="item">
                            <div class="item-info">
                                <h3>Penyimpanan Emas 18K</h3>
                                <p>Durasi: 15 hari x Rp 8.000</p>
                            </div>
                            <div class="item-total">Rp 120.000</div>
                        </div>
                    </div>
                    <div class="transaction-footer">
                        <div class="transaction-total">Total: Rp 120.000</div>
                        <button class="detail-btn">Lihat Detail</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>

</html>