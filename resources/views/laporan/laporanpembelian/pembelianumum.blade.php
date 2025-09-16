<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Pembelian</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/laporan/laporanpembelian/pembelian.css') }}">
</head>

<body>
    @include('partials.navbar')
    <div class="container">
        <div class="header">
            <h1>LAPORAN PEMBELIAN</h1>
        </div>

        {{-- 🔹 Form Filter --}}
        <form method="GET" action="{{ route('pembelianumum') }}">
            <div class="date-section">
                <div class="date-item">
                    <span>Dari</span>
                    <input type="date" name="start_date" value="{{ $start }}">
                </div>
                <div class="date-item">
                    <span>Sampai</span>
                    <input type="date" name="end_date" value="{{ $end }}">
                </div>
                <div class="date-item">
                    <button type="submit" class="btn-print">
                        <i class="fas fa-filter"></i> FILTER
                    </button>
                </div>
            </div>
        </form>

        <div class="divider"></div>

        <form id="form-cetak" method="GET" action="{{ route('pembelianumum.cetak') }}" target="_blank">
            <input type="hidden" name="start_date" value="{{ $start }}">
            <input type="hidden" name="end_date" value="{{ $end }}">

            <table class="table-laporan">
                <thead>
                    <tr>
                        <th><input type="checkbox" id="checkAll"></th>
                        <th>No Faktur Beli</th>
                        <th>Staff</th>
                        <th>Barcode</th>
                        <th>Nama Penjual</th>
                        <th>Tanggal</th>
                        <th>Total</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($pembelian as $row)
                        <tr>
                            <td>
                                <input type="checkbox" name="id[]" value="{{ $row->id }}">
                            </td>
                            <td>{{ $row->nofakturbeli }}</td>
                            <td>{{ $row->staff }}</td>
                            <td>{{ $row->barcode }}</td>
                            <td>{{ $row->namapenjual }}</td>
                            <td>{{ $row->created_at->format('d-m-Y') }}</td>
                            <td>{{ number_format($row->total, 0, ',', '.') }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7">Tidak ada data pembelian</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            <div class="divider"></div>

            {{-- 🔹 Tombol Cetak --}}
            <div class="action-section">
                <button type="submit" class="btn-print">
                    <i class="fas fa-print"></i> CETAK YANG DIPILIH
                </button>
            </div>
        </form>

        <script>

            // Checkbox "Pilih Semua"
            document.getElementById('checkAll').addEventListener('change', function (e) {
                const checkboxes = document.querySelectorAll('input[name="id[]"]');
                checkboxes.forEach(cb => cb.checked = e.target.checked);
            });

            document.addEventListener('DOMContentLoaded', function () {
                // Animasi tombol cetak
                const printButton = document.querySelector('.btn-print');
                if (printButton) {
                    printButton.addEventListener('click', function () {
                        this.innerHTML = '<i class="fas fa-spinner fa-spin"></i> MEMPROSES...';
                        setTimeout(() => {
                            this.innerHTML = '<i class="fas fa-check"></i> BERHASIL DICETAK';
                            this.style.background = 'linear-gradient(135deg, #27ae60 0%, #2ecc71 100%)';
                            setTimeout(() => {
                                this.innerHTML = '<i class="fas fa-print"></i> CETAK LAPORAN';
                                this.style.background = 'linear-gradient(135deg, #2c3e50 0%, #4a6491 100%)';
                            }, 2000);
                        }, 1500);
                    });
                }
            });
        </script>
</body>

</html>