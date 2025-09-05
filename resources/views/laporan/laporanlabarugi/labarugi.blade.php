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
        <h1>LAPORAN LABA RUGI</h1>

        <!-- Filter -->
        <div class="card p-4 mb-4">
            <form method="POST" action="{{ route('laporan.labarugi.show') }}">
                @csrf
                <div class="row g-3">
                    <div class="col-md-5">
                        <label for="start_date">Tanggal Mulai</label>
                        <input type="date" name="start_date" id="start_date"
                               class="form-control"
                               value="{{ old('start_date', $start ?? date('Y-m-d')) }}">
                    </div>
                    <div class="col-md-5">
                        <label for="end_date">Tanggal Akhir</label>
                        <input type="date" name="end_date" id="end_date"
                               class="form-control"
                               value="{{ old('end_date', $end ?? date('Y-m-d')) }}">
                    </div>
                    <div class="col-md-2 d-flex align-items-end">
                        <button type="submit" class="btn btn-danger w-100">Tampilkan</button>
                    </div>
                </div>
                <div class="form-check mt-3">
                    <input type="checkbox" class="form-check-input" id="cetak" name="print">
                    <label class="form-check-label" for="cetak">Cetak Laporan</label>
                </div>
            </form>
        </div>

        <!-- Periode Laporan -->
        @if(!empty($start) && !empty($end))
        <p style="text-align:center; font-weight:bold; margin-bottom:15px;">
            Periode: {{ \Carbon\Carbon::parse($start)->format('d-m-Y') }}
            s/d
            {{ \Carbon\Carbon::parse($end)->format('d-m-Y') }}
        </p>
        @endif

        <!-- Tabel Laporan -->
        <div class="table-wrapper">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Tanggal</th>
                        <th>Jual</th>
                        <th>HPP</th>
                        <th>Laba</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($reports ?? [] as $report)
                    <tr>
                        <td>{{ $report->date }}</td>
                        <td class="type-masuk">Rp {{ number_format($report->sales, 0, ',', '.') }}</td>
                        <td class="type-keluar">Rp {{ number_format($report->hpp, 0, ',', '.') }}</td>
                        <td class="type-masuk">Rp {{ number_format($report->profit, 0, ',', '.') }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="text-center">Tidak ada data</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>
