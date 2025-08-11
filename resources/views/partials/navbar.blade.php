<!-- resources/views/navbar.blade.php -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Navbar Goldstore</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/navbar.css') }}">
</head>
<body>
    
    <div class="navbar">
        <div class="menu">
            <li class="dropdown">
                <a href="#"><i class="fa fa-database"></i> Data Master Anang Cihuy</a>
                <div class="dropdown-content">
                    <a href="{{ route('datamaster.area') }}">Area</a>
                    <a href="#">Cabang</a>
                    <a href="#">Kategori</a>
                    <a href="#">Supplier</a>
                </div>
            </li>
            <li><i class="fa fa-box"></i><a href="#">Barang</a></li>
            <li><i class="fa fa-calculator"></i><a href="#">Jual</a></li>
            <li><i class="fa fa-tags"></i><a href="#">Beli</a></li>
            <li><i class="fa fa-shopping-basket"></i><a href="#">Pesanan</a></li>
            <li><i class="fa fa-wrench"></i><a href="#">Service</a></li>
            <li><i class="fa fa-file"></i><a href="#">Laporan</a></li>
            <li>
                <a href="{{ route('utility.company_profile') }}">
                    <i class="fa fa-gear"></i> Utility
                </a>
            </li>
        </div>

        <div class="navbar-icons">
            <a href="#" class="icon"><i class="fa fa-bell"></i></a>
            <a href="{{ url('/logout') }}" class="icon"><i class="fa fa-sign-out-alt"></i></a>
        </div>

        <div class="footer-navbar">
            <div class="info">
                <span><i class="fa fa-user"></i> {{ session('username') }}</span>| 
                <span>{{ \Carbon\Carbon::now()->translatedFormat('l, j F Y') }}</span> | 
                <span>{{ \Carbon\Carbon::now()->format('H:i:s') }}</span>
            </div>
        </div>
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
