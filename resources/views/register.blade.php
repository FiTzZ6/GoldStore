<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Form Register</title>
    <link rel="stylesheet" href="{{ asset('css/register.css') }}">
</head>
<body>
    <div class="container">
        <h2>Form Register</h2>

        @if(session('msg'))
            <div class="alert">{{ session('msg') }}</div>
        @endif

        <form method="POST" action="{{ route('register.submit') }}">
            @csrf

            <label for="name">Nama</label>
            <input type="text" name="name" value="{{ old('name') }}" required>

            <label for="username">Username</label>
            <input type="text" name="username" value="{{ old('username') }}" required>

            <label for="password">Password (minimal 6 karakter)</label>
            <input type="password" name="password" required>

            <label for="usertype">Jenis User</label>
            <select name="usertype" required>
                <option value="">-- Pilih --</option>
                <option value="1" {{ old('usertype') == 1 ? 'selected' : '' }}>Admin</option>
                <option value="4" {{ old('usertype') == 4 ? 'selected' : '' }}>Petugas Barang</option>
                <option value="6" {{ old('usertype') == 6 ? 'selected' : '' }}>Petugas Penjualan</option>
                <option value="9" {{ old('usertype') == 9 ? 'selected' : '' }}>Petugas Pembelian</option>
            </select>

            <label for="kdtoko">Kode Toko</label>
            <input type="number" name="kdtoko" value="{{ old('kdtoko') }}" required>

            <button type="submit">Daftar</button>
        </form>

        <p>Sudah punya akun? <a href="{{ route('login') }}">Login di sini</a></p>
    </div>
</body>
</html>
