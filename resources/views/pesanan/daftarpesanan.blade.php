<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Pesanan</title>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
</head>
<body>
    {{-- Navbar --}}
    @include('partials.navbar')

    {{-- Konten Utama --}}
    <main class="container">
        <h1>Pesanan</h1>
        <div class="export-section">
            <select>
                <option value="export-basic">Export Basic</option>
            </select>
            <input type="text" placeholder="Search">
        </div>

        <table class="table">
            <thead>
                <tr>
                    <th><input type="checkbox"></th>
                    <th>No Pesan</th>
                    <th>Tanggal Pesan</th>
                    <th>Tanggal Ambil</th>
                    <th>Nama Pemesan</th>
                    <th>Alamat Pemesan</th>
                    <th>No.Telp Pemesan</th>
                    <th>Staff</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($pesanan as $item)
                <tr>
                    <td><input type="checkbox"></td>
                    <td>{{ $item->id }}</td>
                    <td>{{ $item->tgl_pesan }}</td>
                    <td>{{ $item->tgl_ambil }}</td>
                    <td>{{ $item->nama_pemesan }}</td>
                    <td>{{ $item->alamat_pemesan }}</td>
                    <td>{{ $item->telp_pemesan }}</td>
                    <td>{{ $item->staff }}</td>
                    <td>
                        <a href="#" class="btn btn-sm btn-primary">Edit</a>
                        <a href="#" class="btn btn-sm btn-danger">Hapus</a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="9" style="text-align:center;">No matching records found</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </main>

    {{-- Footer --}}
    <footer>
        <p>&copy; {{ date('Y') }} - GoldStore</p>
    </footer>
</body>
</html>
