<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Navbar Goldstore</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/navbar.css') }}">
    <style>
        html,
        body {
            margin: 0;
            padding: 0;
        }

        .navbar {
            background: linear-gradient(to right, #d60000, #900000);
            padding: 10px 20px;
            height: 60px;
            color: white;
            display: flex;
            justify-content: space-between;
            align-items: center;
            font-size: 15px;
            font-family: Arial, sans-serif;
            position: relative;
        }

        .menu {
            list-style: none;
            display: flex;
            gap: 15px;
            margin: 0;
            padding: 0;
        }

        .menu>li {
            position: relative;
        }

        .menu>li>a {
            color: white;
            text-decoration: none;
            font-weight: bold;
            padding: 8px 2px;
            border-radius: 6px;
            transition: background-color 0.3s ease;
            display: block;
        }

        .menu>li>a:hover {
            background-color: #fd7575;
        }

        /* Dropdown */
        .menu li ul {
            display: none;
            position: absolute;
            top: 100%;
            left: 0;
            background-color: #900000;
            padding: 0;
            margin: 0;
            border-radius: 4px;
            list-style: none;
            z-index: 1000;
            min-width: 150px;
        }

        .menu li:hover ul {
            display: block;
        }

        .menu li ul li a {
            display: block;
            padding: 10px;
            color: white;
            text-decoration: none;
            font-weight: normal;
        }

        .menu li ul li a:hover {
            background-color: #fd7575;
        }

        .navbar-icons {
            display: flex;
            gap: 15px;
            align-items: center;
        }

        .navbar-icons .icon {
            color: white;
            text-decoration: none;
            font-size: 18px;
            transition: color 0.3s ease;
        }

        .navbar-icons .icon:hover {
            color: #ffcccb;
        }

        .footer-navbar {
            position: fixed;
            bottom: 0;
            left: 0;
            width: 100%;
            background: linear-gradient(to right, #d60000, #900000);
            color: white;
            text-align: center;
            padding: 10px 20px;
            font-size: 14px;
            font-family: Arial, sans-serif;
        }

        .navbar span {
            color: white;
            text-decoration: none;
            margin: 0 10px;
            font-weight: bold;
        }
    </style>
</head>

<body>
    <script>
        function toggleMenu() {
            const menu = document.querySelector('.menu');
            const overlay = document.querySelector('.menu-overlay');
            menu.classList.toggle('active');
            overlay.style.display = menu.classList.contains('active') ? 'block' : 'none';
        }
    </script>

    <div class="navbar">
        <div class="burger" onclick="toggleMenu()">
            <i class="fas fa-bars"></i>
        </div>
        <ul class="menu">
            {{-- ====================== NAVBAR ====================== --}}

            {{-- HOME --}}
            <li>
                <a href="{{ route('laporan.dashboard') }}"><i class="fas fa-home"></i> Home</a>
            </li>

            {{-- ====================== DATA MASTER ====================== --}}
            <li>
                <a href="#"><i class="fas fa-database"></i> Data Master</a>
                <ul>
                    {{-- SUPER ADMIN, DIREKTUR, DIREKSI, AKUNTAN, MANAGER TOKO --}}
                    @if(in_array(session('typeuser'), [1, 2, 3, 7, 8]))
                        <li><a href="{{ route('area') }}">Area</a></li>
                        <li><a href="{{ route('cabang') }}">Cabang</a></li>
                        <li><a href="{{ route('supplier') }}">Supplier</a></li>
                        <li><a href="{{ route('kategoribarang') }}">Kategori Barang</a></li>
                        <li><a href="{{ route('jenisbarang') }}">Jenis Barang</a></li>
                        <li><a href="{{ route('bakibarang.index') }}">Baki Barang</a></li>
                        <li><a href="{{ route('kondisibarang') }}">Kondisi Barang</a></li>
                        <li><a href="{{ route('ongkos') }}">Ongkos</a></li>
                        <li><a href="{{ route('potongan') }}">Potongan</a></li>
                        <li><a href="{{ route('parameterkas') }}">Parameter Kas</a></li>
                        <li><a href="{{ route('staff') }}">Staff</a></li>
                        <li><a href="{{ route('merch.index') }}">Merchandise</a></li>
                    @endif

                    {{-- Semua user (termasuk CUSTOMER) --}}
                    <li><a href="{{ route('pelanggan') }}">Pelanggan</a></li>
                    <li><a href="{{ route('caripelanggan') }}">Cari Pelanggan</a></li>
                </ul>
            </li>

            {{-- ====================== BARANG ====================== --}}
            @if(in_array(session('typeuser'), [1, 2, 4, 5, 8, 9]))
                <li>
                    <a href="#"><i class="fas fa-box"></i> Barang</a>
                    <ul>
                        <li class="has-submenu"><a href="#">Data Barang</a>
                            <ul class="dropdown-menu-1">
                                <li><a href="{{ route('barangStok') }}">Barang Stok</a></li>
                                <li><a href="{{ route('BarangTerjual') }}">Barang Terjual</a></li>
                                <li><a href="{{ route('barangterhapus') }}">Barang Dihapus</a></li>
                            </ul>
                        </li>
                        <li><a href="{{ route('terimabarang.index') }}">Terima Barang</a></li>
                        <li class="has-submenu">
                            <a href="#">Cuci Sepuh</a>
                            <ul class="dropdown-menu-1">
                                <li><a href="{{ route('cucisepuhform') }}">Formulir Cuci Sepuh</a></li>
                                <li><a href="{{ route('riwayatcuci') }}">Riwayat Cuci Sepuh</a></li>
                                @if(session('typeuser') == 1)
                                    <li><a href="{{ route('formpenyimpanan') }}">Formulir Penyimpanan Mutu</a></li>
                                    <li><a href="{{ route('riwayatpenyimpanan') }}">Riwayat Penyimpanan Mutu</a></li>
                                @endif
                            </ul>
                        </li>
                        <li class="has-submenu">
                            <a href="#">Rongsok</a>
                            <ul class="dropdown-menu-1">
                                @if(session('typeuser') == 1)
                                    <li><a href="{{ route('formrongsok') }}">Formulir Rongsok</a></li>
                                    <li><a href="{{ route('riwayatrongsok') }}">Riwayat Rongsok</a></li>
                                @endif
                                <li><a href="{{ route('formketidaksesuaian') }}">Formulir Ketidaksesuaian</a></li>
                                <li><a href="{{ route('riwayatketidaksesuaian') }}">Riwayat Ketidaksesuaian</a></li>
                            </ul>
                        </li>
                        <li class="has-submenu">
                            <a href="#">Pindah Barang</a>
                            <ul class="dropdown-menu-1">
                                <li><a href="{{ route('pindahbaki') }}">Pindah Baki</a></li>
                                <li><a href="{{ route('riwayatpindahbarang') }}">Riwayat Pindah Baki</a></li>
                            </ul>
                        </li>
                    </ul>
                </li>
            @endif

            {{-- ====================== JUAL ====================== --}}
            @if(in_array(session('typeuser'), [1, 2, 3, 6, 8]))
                <li>
                    <a href="#"><i class="fas fa-cash-register"></i> Jual</a>
                    <ul>
                        <li><a href="{{ route('transpenjualan') }}">Transaksi Penjualan</a></li>
                        <li><a href="{{ route('bataljual') }}">Batal Penjualan</a></li>
                        <li><a href="{{ route('riwayatpenjualan') }}">Riwayat Penjualan</a></li>
                        <li><a href="{{ route('riwayatbataljual') }}">Riwayat Batal Jual</a></li>
                        <li><a href="{{ route('selisihjual') }}">Selisih Jual & Batal</a></li>
                        <li><a href="{{ route('stokjual') }}">Stok Barang Jual</a></li>
                    </ul>
                </li>
            @endif

            {{-- ====================== BELI ====================== --}}
            @if(in_array(session('typeuser'), [1, 2, 3, 4,6, 8, 9]))
                <li>
                    <a href="#"><i class="fas fa-cart-arrow-down"></i> Beli</a>
                    <ul>
                        <li><a href="{{ route('transaksibeli') }}">Transaksi Pembelian</a></li>
                        <li><a href="{{ route('batalbeli') }}">Batal Pembelian</a></li>
                        <li><a href="{{ route('riwayatbeli') }}">Riwayat Pembelian</a></li>
                        <li><a href="{{ route('riwayatbatalbeli') }}">Riwayat Batal Beli</a></li>
                        <li><a href="{{ route('selisihbelibatal') }}">Selisih Beli & Batal</a></li>
                    </ul>
                </li>
            @endif

            {{-- ====================== PESANAN (Staff & Customer) ====================== --}}
            @if(in_array(session('typeuser'), [1, 2, 3, 8, 10]))
                <li>
                    <a href="#"><i class="fas fa-clipboard-list"></i> Pesanan</a>
                    <ul>
                        <li><a href="{{ route('daftarpesanan') }}">Daftar Pesanan</a></li>
                    </ul>
                </li>
            @endif

            {{-- ====================== SERVICE (Kasir & Admin) ====================== --}}
            @if(in_array(session('typeuser'), [1, 2, 6, 8]))
                <li>
                    <a href="#"><i class="fas fa-tools"></i> Service</a>
                    <ul>
                        <li><a href="{{ route('transaksiservice') }}">Transaksi Service</a></li>
                        <li><a href="{{ route('daftarservice') }}">Daftar Service</a></li>
                    </ul>
                </li>
            @endif

            {{-- ====================== UANG KAS (Admin, Akuntan) ====================== --}}
            @if(in_array(session('typeuser'), [1, 7]))
                <li>
                    <a href="#"><i class="fa-solid fa-sack-dollar"></i> Uang Kas</a>
                    <ul>
                        <li><a href="{{ route('uang_kas.index') }}">Daftar Keluar Masuk KAS</a></li>
                    </ul>
                </li>
            @endif


            <!-- navbar laporan  -->
             @if(in_array(session('typeuser'), [1, 2, 3, 8]))
            <li>
                <a href="#"><i class="fas fa-chart-bar"></i> Laporan</a>
                <ul>
                    <li class="has-submenu">
                        <a href="#">Laporan Barang<span style="float: right;"></span></a>
                        <ul class="dropdown-menu">
                            <li><a href="{{ route('stokbarang') }}">Stok Barang</a></li>
                            <li><a href="{{ route('persediaanbarang') }}">Persedian Barang</a></li>
                            <li><a href="{{ route('stokkosong') }}">Stok Kosong</a></li>
                            <li><a href="{{ route('barangcepatlaku') }}">Barang Cepat Laku</a></li>
                            <li><a href="{{ route('baranglambatlaku') }}">Barang Lambat Laku</a></li>
                            <li><a href="{{ route('hapusbarang') }}">Hapus Barang</a></li>
                        </ul>
                    </li>
                    <li class="has-submenu">
                        <a href="#">Laporan Pembelian<span style="float: right;"></span></a>
                        <ul class="dropdown-menu">
                            <li><a href="{{ route('pembelianumum') }}">Pembelian Umum</a></li>
                            <li><a href="{{ route('batalbeli') }}">Batal Beli Umum</a></li>
                        </ul>
                    </li>
                    <li class="has-submenu">
                        <a href="#">Laporan Penjualan<span style="float: right;"></span></a>
                        <ul class="dropdown-menu">
                            <li><a href="{{ route('penjualanumum') }}">Penjualan Umum</a></li>
                            <li><a href="{{ route('penjualanstaff') }}">Penjualan Staff</a></li>
                            <li><a href="{{ route('penjualanbataljual') }}">Batal Jual</a></li>
                        </ul>
                    </li>
                    <li><a href="{{ route('laporankas') }}">Laporan Transaksi Kas</a></li>
                    <li><a href="{{ route('laporan.labarugi') }}">Laporan Laba Rugi</a></li>
                    <li><a href="{{ route('laporan.stokopname') }}">Laporan Stok Opname</a></li>
                    <li><a href="{{ route('laporantukarpoin') }}">Laporan Tukar Poin</a></li>
                    <li><a href="{{ route('laporantukarrupiah') }}">Laporan Tukar Rupiah</a></li>
                    <li><a href="{{ route('laporancucisepuh') }}">Laporan Cuci Sepuh</a></li>
                </ul>
            </li>
            @endif

            {{-- ====================== STOK OPNAME ====================== --}}
            @if(session('typeuser') == 1)
                <li>
                    <a href="#"><i class="fa-solid fa-building-columns"></i> Stok Opname</a>
                    <ul>
                        <li><a href="{{ route('stokopname') }}">Stok Opname Global</a></li>
                    </ul>
                </li>
            @endif

            {{-- ====================== UTILITY ====================== --}}
            @if(in_array(session('typeuser'), [1, 7, 8]))
                <li>
                    <a href="#"><i class="fas fa-cogs"></i> Utility</a>
                    <ul>
                        @if(session('typeuser') == 1)
                            <li class="has-submenu">
                                <a href="#">Permintaan Pembelian</a>
                                <ul class="dropdown-menu-1">
                                    <li><a href="{{ route('formulir_pp') }}">Formulir PP</a></li>
                                    <li><a href="{{ route('formulir_pp.riwayat') }}">Riwayat PP</a></li>
                                </ul>
                            </li>
                        @endif

                        <li class="has-submenu">
                            <a href="#">Akun</a>
                            <ul class="dropdown-menu-1">
                                @if(session('typeuser') == 1)
                                    <li><a href="{{ route('utility.akun.table_akun') }}">Akun</a></li>
                                @endif
                                <li><a href="{{ route('utility.akun.profile') }}">Profil</a></li>
                            </ul>
                        </li>

                        @if(session('typeuser') == 1)
                            <li class="has-submenu">
                                <a href="#">Setting</a>
                                <ul class="dropdown-menu-1">
                                    <li><a href="{{ route('qr_direktur') }}">QR Direktur Oprasional</a></li>
                                </ul>
                            </li>
                        @endif
                        <li><a href="{{ route('tukar.index') }}">Tukar Rupiah</a></li>
                        @if(session('typeuser') == 1)
                            <li><a href="{{ route('utility.company.company_profile') }}">Profil Perusahaan</a></li>
                        @endif
                        <li><a href="{{ route('backup') }}">Backup Data</a></li>
                    </ul>
                </li>
            @endif

        </ul>

        <div class="navbar-icons">
            <a href="#" class="icon"><i class="fa fa-bell"></i></a>
            <a href="{{ url('/logout') }}" class="icon"><i class="fa fa-sign-out-alt"></i></a>
        </div>
    </div>

    <div class="menu-overlay" onclick="toggleMenu()"></div>

    <div class="footer-navbar">
        <i class="fa fa-user"></i>
        <span>{{ session('username') }}</span> |
        <span>{{ \Carbon\Carbon::now()->translatedFormat('l, j F Y') }}</span> |
        <span id="clock"></span>
    </div>

    <script>
        function updateClock() {
            const now = new Date();
            const time = now.toLocaleTimeString('id-ID', { hour12: false });
            document.getElementById("clock").textContent = time;
        }
        setInterval(updateClock, 1000);
        updateClock();

    </script>
</body>

</html>