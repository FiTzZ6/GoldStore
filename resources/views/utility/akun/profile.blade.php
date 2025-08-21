<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Profil</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: linear-gradient(#d7d7d7, #6d6d6d);
            margin: 0;
        }
        .container {
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }
        .content {
            flex: 1; /* supaya isi berada di tengah antara navbar dan footer */
            display: flex;
            justify-content: center;
            align-items: center;
            text-align: center;
        }
        .card {
            padding: 30px;
            border-radius: 12px;
        }
        .avatar {
            font-size: 100px;
            margin-bottom: 10px;
        }
        .name {
            font-size: 22px;
            font-weight: bold;
        }
        .role {
            color: #666;
            margin: 5px 0;
        }
        .dept {
            margin-top: 10px;
        }
    </style>
</head>
<body>
    <div class="container">
        {{-- Navbar --}}
        @include('partials.navbar')

        <div class="content">
            <div class="card">
                <div class="avatar">👨‍💼</div>
                <div class="name">{{ Session::get('username') }}</div>
                <div class="role">
                    @php
                        $roles = [
                            1 => 'SUPER ADMIN',
                            2 => 'ADMIN',
                            3 => 'STAFF',
                            4 => 'GUDANG',
                            5 => 'KASIR',
                            6 => 'PENJUALAN',
                            7 => 'MANAJER',
                            8 => 'SUPERVISOR',
                            9 => 'PEMBELIAN',
                        ];
                        echo $roles[Session::get('typeuser')] ?? 'USER';
                    @endphp
                </div>
                <div class="dept">🏢 {{ Session::get('kdtoko') }}</div>
            </div>
        </div>

    </div>
</body>
</html>
