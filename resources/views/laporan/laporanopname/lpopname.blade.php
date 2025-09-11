<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Stok Opname</title>
    <link rel="stylesheet" href="{{ asset('css/laporan/laporanopname/lpopname.css') }}">
    {{-- Tambahkan bootstrap kalau belum ada --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    @include('partials.navbar')

    <div class="container">
        <h1>LAPORAN STOK OPNAME</h1>

        <div class="row">
            <!-- Filter sebelah kiri -->
            <div class="col-md-4">
                <div class="card p-4">
                    <h5>Filter Tanggal</h5>
                    <form method="POST" action="{{ route('laporan.stokopname.show') }}">
                        @csrf
                        <div class="mb-3">
                            <label for="start_date" class="form-label">Tanggal Mulai</label>
                            <input type="date" id="start_date" name="start_date" class="form-control"
                                   value="{{ $start ?? date('Y-m-d') }}">
                        </div>
                        <div class="mb-3">
                            <label for="end_date" class="form-label">Tanggal Akhir</label>
                            <input type="date" id="end_date" name="end_date" class="form-control"
                                   value="{{ $end ?? date('Y-m-d') }}">
                        </div>

                        <div class="form-check mb-3">
                            <input type="checkbox" class="form-check-input" id="cetak" name="print">
                            <label class="form-check-label" for="cetak">Cetak Laporan</label>
                        </div>

                        <button type="submit" class="btn btn-primary w-100">Tampilkan</button>
                    </form>
                </div>
            </div>

            <!-- Tabel sebelah kanan -->
            <div class="col-md-8">
                <div class="card p-4">
                    <h5>Hasil Laporan</h5>
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Tanggal</th>
                                <th>Kode Barang</th>
                                <th>Nama Barang</th>
                                <th>Stok Sistem</th>
                                <th>Stok Fisik</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($reports as $report)
                                <tr>
                                    <td>{{ $report->tanggal }}</td>
                                    <td>{{ $report->kode_barang }}</td>
                                    <td>{{ $report->nama_barang }}</td>
                                    <td>{{ $report->stok_sistem }}</td>
                                    <td>{{ $report->stok_fisik }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center">Tidak ada data</td>
                                </tr>
                            @endforelse
                        </tbody>
