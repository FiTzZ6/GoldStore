<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Service</title>
    <link rel="stylesheet" href="{{ asset('css/service/daftarservice.css') }}">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>

<body>
    @include('partials.navbar')
    <div class="container">
        <div class="header">
            <h1><i class="fas fa-exchange-alt"></i> DAFTAR SERVICE</h1>
        </div>

        <div class="controls">
            <div class="export-section">
                <select>
                    <option value="">Export Basic</option>
                    <option value="print">Print</option>
                    <option value="pdf">PDF</option>
                    <option value="excel">Excel</option>
                    <option value="csv">CSV</option>
                </select>
            </div>
            <div class="right-controls">
                <div class="icon-group">
                    <button title="Sorting" onclick="sortTable()"><i class="fas fa-sort"></i></button>
                    <button title="Refresh" onclick="refreshPage()"><i class="fas fa-sync"></i></button>
                </div>
                <div class="search-section">
                    <input type="text" placeholder="Search">
                    <button><i class="fas fa-search"></i></button>
                </div>
            </div>
        </div>

        <div class="content">
            <div class="table-container">
                <table>
                    <thead>
                        <tr>
                            <th>No Service</th>
                            <th>Tanggal Service</th>
                            <th>Tanggal Ambil</th>
                            <th>Nama Pelanggan</th>
                            <th>Alamat Pelanggan</th>
                            <th>No Telp Pelanggan</th>
                            <th>Staff</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($data as $item)
                            <tr>
                                <td>{{ $item->fakturservice }}</td>
                                <td>{{ $item->tanggalservice }}</td>
                                <td>{{ $item->tanggalambil }}</td>
                                <td>{{ $item->namapelanggan }}</td>
                                <td>{{ $item->alamat ?? '-' }}</td>
                                <td>{{ $item->notelp }}</td>
                                <td>{{ $item->staff }}</td>
                                <td>
                                    <a href="{{ route('transaksiservice.cetak', $item->id) }}"
                                        class="btn btn-sm btn-primary">
                                        <i class="fas fa-print"></i> Cetak
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr class="no-data">
                                <td colspan="8">
                                    <div class="no-data-message">
                                        <i class="fas fa-database"></i>
                                        <p>No matching records found</p>
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
        // Script untuk interaksi dasar
        document.addEventListener('DOMContentLoaded', function () {
            const searchInput = document.querySelector('.search-section input');
            const searchButton = document.querySelector('.search-section button');

            searchButton.addEventListener('click', function () {
                alert('Fitur pencarian: ' + searchInput.value);
            });

            searchInput.addEventListener('keypress', function (e) {
                if (e.key === 'Enter') {
                    alert('Fitur pencarian: ' + searchInput.value);
                }
            });
        });
    </script>
</body>

</html>