<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Navbar Goldstore</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/navbar.css') }}">
</head>
<body>
    <script>
    function toggleMenu() {
        const menu = document.querySelector('.menu');
        menu.classList.toggle('active');
    }
</script>

    <div class="navbar">
        <ul class="menu">

    <li>
        <a href="{{ route('laporan.dashboard') }}"><i class="fas fa-home"></i> Home</a>
    </li>
    <li>
        <a href="#"><i class="fas fa-database"></i> Data Master</a>
        <ul>
            <li><a href="{{ route('datamaster.area') }}">Area</a></li>
            <li><a href="#">Cabang</a></li>
            <li><a href="#">Supplier</a></li>
            <li><a href="#">Karyawan</a></li>
            <li><a href="#">Kategori Barang</a></li>
            <li><a href="#">Jenis Barang</a></li>
            <li><a href="#">Baki Barang</a></li>
            <li><a href="#">Kondisi Barang</a></li>
            <li><a href="#">Ongkos</a></li>
            <li><a href="#">Potongan</a></li>
            <li><a href="#">Parameter Kas</a></li>
            <li><a href="#">Staff</a></li>
            <li><a href="#">Merchandise</a></li>
            <li><a href="#">Pelanggan</a></li>
            <li><a href="#">Cari Pelanggan</a></li>

        </ul>
    </li>
    <li>
        <a href="#"><i class="fas fa-box"></i> Barang</a>
        <ul>
            <li><a href="#">Data Barang</a></li>
            <li><a href="#">Stok Barang</a></li>
        </ul>
    </li>
    <li>
        <a href="#"><i class="fas fa-cash-register"></i> Jual</a>
        <ul>
            <li><a href="#">Transaksi Jual</a></li>
            <li><a href="#">Riwayat Penjualan</a></li>
        </ul>
    </li>
    <li>
        <a href="#"><i class="fas fa-cart-arrow-down"></i> Beli</a>
        <ul>
            <li><a href="#">Pembelian</a></li>
            <li><a href="#">Riwayat Pembelian</a></li>
        </ul>
    </li>
    <li>
        <a href="#"><i class="fas fa-clipboard-list"></i> Pesanan</a>
        <ul>
            <li><a href="#">Daftar Pesanan</a></li>
            <li><a href="#">Riwayat Pesanan</a></li>
        </ul>
    </li>
    <li>
        <a href="#"><i class="fas fa-tools"></i> Service</a>
        <ul>
            <li><a href="#">Input Service</a></li>
            <li><a href="#">Riwayat Service</a></li>
        </ul>
    </li>
    <li>
        <a href="#"><i class="fas fa-chart-bar"></i> Laporan</a>
        <ul>
            <li><a href="#">Laporan Penjualan</a></li>
            <li><a href="#">Laporan Pembelian</a></li>
        </ul>
    </li>
    <li>
        <a href="#"><i class="fas fa-cogs"></i> Utility</a>
        <ul>
        <li class="has-submenu">
            <a href="#">Permintaan Pembelian <span style="float: right;"></span></a>
            <ul class="dropdown-menu-1">
                <li><a href="#">Formulir PP</a></li>
                <li><a href="#">Riwayat PP</a></li>
            </ul>
        </li>

            <li><a href="#">Akun</a></li>
            <li><a href="#">Setting</a></li>
            <li><a href="#">Tukar Koin</a></li>
            <li><a href="#">Tukar Rupiah</a></li>
            <li><a href="{{ route('utility.company_profile') }}">Profil Perusahaan</a></li>
            <li><a href="#">Backup Data</a></li>
            <li><a href="#">Dokumen</a></li>
        </ul>
    </li>
</ul>

        <div class="navbar-icons">
            <a href="#" class="icon"><i class="fa fa-bell"></i></a>
            <a href="{{ url('/logout') }}" class="icon"><i class="fa fa-sign-out-alt"></i></a>
        </div>
    </div>

    <div class="footer-navbar">
        <i class="fa fa-user"></i>
        <span>{{ session('username') }}</span> | 
        <span>{{ \Carbon\Carbon::now()->translatedFormat('l, j F Y') }}</span> | 
        <span>{{ \Carbon\Carbon::now()->format('H:i:s') }}</span>
    </div>

    <script>
        function updateDateTime() {
            const now = new Date();
            const options = { 
                weekday: 'long', year: 'numeric', month: 'long', 
                day: 'numeric', hour: '2-digit', minute: '2-digit', second: '2-digit' 
            };
            document.getElementById("datetime").innerHTML = now.toLocaleString('id-ID', options);
        }
        setInterval(updateDateTime, 1000);
        updateDateTime();
    </script>
</body>
</html>
