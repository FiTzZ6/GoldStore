<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Laporan Laba Rugi</title>
    <link rel="stylesheet" href="{{ asset('css/laporan/laporanlabarugi/labarugi.css') }}">
    <link href="https://fonts.googleapis.com/css?family=Montserrat:400,800" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" />
</head>

<body>
    @include('partials.navbar')

    <div class="container">
        <div class="header">
            <h1>Laporan Laba Rugi</h1>
        </div>

        {{-- Form Filter --}}
        <form method="POST" action="{{ route('laporan.labarugi.show') }}">
            @csrf
            <div class="date-section">
                <div class="date-item">
                    <span>Dari</span>
                    <input type="date" name="start_date" value="{{ $start }}">
                </div>
                <div class="date-item">
                    <span>Sampai</span>
                    <input type="date" name="end_date" value="{{ $end }}">
                </div>
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-search"></i> Tampilkan
                </button>
            </div>
        </form>

        {{-- Hasil Laporan --}}
        @if(!empty($reports))
            <div class="table-container">
                <table>
                    <thead>
                        <tr>
                            <th>Keterangan</th>
                            <th>Nominal</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>Total Penjualan</td>
                            <td>Rp {{ number_format($reports['penjualan'], 0, ',', '.') }}</td>
                        </tr>
                        <tr>
                            <td>Total Pembelian</td>
                            <td>Rp {{ number_format($reports['pembelian'], 0, ',', '.') }}</td>
                        </tr>
                        <tr>
                            <td>HPP</td>
                            <td>Rp {{ number_format($reports['hpp'], 0, ',', '.') }}</td>
                        </tr>
                        <tr>
                            <td>Laba Kotor</td>
                            <td>Rp {{ number_format($reports['labakotor'], 0, ',', '.') }}</td>
                        </tr>
                        <tr>
                            <td>Laba Bersih</td>
                            <td><strong>Rp {{ number_format($reports['lababersih'], 0, ',', '.') }}</strong></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        @endif
    </div>
</body>

</html>