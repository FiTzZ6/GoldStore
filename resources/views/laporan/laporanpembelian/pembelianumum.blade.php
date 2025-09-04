<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Pembelian</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/laporan/laporanpembelian/pembelian.css') }}">
</head>

<body>
    @include('partials.navbar')
    <div class="container">
        <div class="header">
            <h1>LAPORAN PEMBELIAN</h1>
        </div>
        
        <div class="date-section">
            <div class="date-item">
                <span>TANGGAL</span>
                <input type="text" class="date-input" value="07/08/2025">
            </div>
            <div class="date-item">
                <span>s/d</span>
                <input type="text" class="date-input" value="07/08/2025">
            </div>
        </div>
        
        <div class="divider"></div>
        
        <div class="filters-section">
            <div class="filter-group">
                <h3>JENS</h3>
                <div class="options">
                    <div class="option-item">
                        <label for="jens">Jenis Laporan</label>
                        <select id="jens">
                            <option>SEMUA JENS</option>
                            <option>FAKTUR</option>
                            <option>SEMUA</option>
                        </select>
                    </div>
                    
                    <div class="option-item">
                        <label for="faktur">No. Faktur</label>
                        <input type="text" id="faktur" placeholder="Masukkan no. faktur">
                    </div>
                    
                    <div class="option-item">
                        <label for="dari">Dari</label>
                        <input type="text" id="dari" placeholder="Masukkan sumber">
                    </div>
                </div>
            </div>
        </div>
        
        <div class="divider"></div>
        
        <div class="action-section">
            <button class="btn-print">
                <i class="fas fa-print"></i>
                CETAK LAPORAN
            </button>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Format tanggal inputs
            const dateInputs = document.querySelectorAll('.date-input');
            
            dateInputs.forEach(input => {
                input.addEventListener('focus', function() {
                    this.type = 'date';
                });
                
                input.addEventListener('blur', function() {
                    if(this.value === '') {
                        this.type = 'text';
                    }
                });
            });
            
            // Animasi tombol
            const printButton = document.querySelector('.btn-print');
            
            printButton.addEventListener('click', function() {
                this.innerHTML = '<i class="fas fa-spinner fa-spin"></i> MEMPROSES...';
                
                setTimeout(() => {
                    this.innerHTML = '<i class="fas fa-check"></i> BERHASIL DICETAK';
                    this.style.background = 'linear-gradient(135deg, #27ae60 0%, #2ecc71 100%)';
                    
                    setTimeout(() => {
                        this.innerHTML = '<i class="fas fa-print"></i> CETAK LAPORAN';
                        this.style.background = 'linear-gradient(135deg, #2c3e50 0%, #4a6491 100%)';
                    }, 2000);
                }, 1500);
            });
        });
    </script>
</body>
</html>
