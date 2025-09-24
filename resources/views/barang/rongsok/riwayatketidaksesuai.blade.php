<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Riwayat Ketidaksesuaian</title>
    <link rel="stylesheet" href="{{ asset('css/barang/ronsok/riwayatsesuai.css') }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
</head>

<body>
    @include('partials.navbar')

    <div class="container">
        <!-- Header -->
        <div class="header">
            <h1><i class="bi bi-clock-history"></i> Riwayat Ketidaksesuaian</h1>
        </div>

        <!-- Kontrol (Export & Search) -->
        <div class="controls">
            <div class="search-section">
                <input type="text" id="searchInput" placeholder="Cari data...">
                <button type="button"><i class="bi bi-search"></i></button>
            </div>
        </div>

        <!-- Tabel Riwayat -->
        <div class="content">
            <div class="table-container">
                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Barang</th>
                            <th>Pelanggan</th>
                            <th>Deskripsi</th>
                            <th>Tanggal</th>
                        </tr>
                    </thead>
                    <tbody id="dataTable">
                        @forelse($forms as $f)
                            <tr>
                                <td>{{ $f->id }}</td>
                                <td>{{ $f->barang->namabarang ?? $f->kdbarang }}</td>
                                <td>{{ $f->pelanggan->namapelanggan ?? $f->kdpelanggan }}</td>
                                <td>{{ $f->deskripsi }}</td>
                                <td>{{ $f->created_at->format('d-m-Y H:i') }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="no-data">
                                    <div class="no-data-message">
                                        <i class="bi bi-inbox"></i>
                                        <p>Belum ada data ketidaksesuaian</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script>
        // Fitur search sederhana
        document.getElementById('searchInput').addEventListener('keyup', function () {
            let filter = this.value.toLowerCase();
            let rows = document.querySelectorAll("#dataTable tr");

            rows.forEach(row => {
                let text = row.textContent.toLowerCase();
                row.style.display = text.includes(filter) ? "" : "none";
            });
        });
    </script>
</body>

</html>