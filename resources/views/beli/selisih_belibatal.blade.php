<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Selisih Beli Batal</title>
    <link rel="stylesheet" href="{{ asset('css/beli/selisih_belibatal.css') }}">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>

<body>
    @include('partials.navbar')
    <div class="container">
        <div class="header">
            <h1><i class="fas fa-exchange-alt"></i> DATA TRANSAKSI BELI SELISIH BATAL</h1>
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
                            <th>No</th>
                            <th>No. Faktor beli</th>
                            <th>No. Faktor batal beli</th>
                            <th>Barcode</th>
                            <th>Harga Beli</th>
                            <th>Harga Batal</th>
                            <th>Selisih</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($data as $index => $row)
                                                <tr>
                                                    <td>{{ $index + 1 }}</td>
                                                    <td>{{ $row->nofakturbeli }}</td>
                                                    <td>{{ $row->nofakturbatalbeli }}</td>
                                                    <td>{{ $row->barcode }}</td>
                                                    <td>Rp {{ number_format($row->hargabeli, 0, ',', '.') }}</td>
                                                    <td>Rp {{ number_format($row->hargabatalbeli, 0, ',', '.') }}</td>
                                                    <td>
                                                        @php
                                                            $hargabeli = $row->hargabeli ?? 0;
                                                            $hargabatal = $row->hargabatalbeli ?? 0;

                                                            // default selisih
                                                            $selisih = $hargabeli - $hargabatal;
                                                        @endphp

                                                        @if($hargabeli == $hargabatal || $hargabatal == 0)
                                                            <span style="color:green">Rp 0</span>
                                                        @else
                                                            <span style="color:{{ $selisih >= 0 ? 'green' : 'red' }}">
                                                                Rp {{ number_format($selisih, 0, ',', '.') }}
                                                            </span>
                                                        @endif
                                                    </td>
                                                    <td>
                                                        <button class="btn btn-sm btn-info">Detail</button>
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