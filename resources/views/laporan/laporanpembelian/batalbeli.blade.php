<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Batal Beli</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/laporan/laporanpembelian/batalbeli.css') }}">
</head>

<body>
    @include('partials.navbar')
    <div class="container">
        <div class="header">
            <h1>LAPORAN BATAL BELI</h1>
        </div>

        {{-- 🔹 Form Filter --}}
        <form method="GET" action="{{ route('batalbeli') }}">
            <div class="date-section">
                <div class="date-item">
                    <span>Dari</span>
                    <input type="date" name="start_date" value="{{ request('start_date') }}">
                </div>
                <div class="date-item">
                    <span>Sampai</span>
                    <input type="date" name="end_date" value="{{ request('end_date') }}">
                </div>
                <div class="date-item">
                    <div class="option-item">
                        <label for="nofakturbeli">No Faktur</label>
                        <input type="text" name="nofakturbeli" placeholder="Masukkan no faktur"
                            value="{{ request('nofakturbeli') }}">
                    </div>
                </div>
                <div class="date-item">
                    <button type="submit" class="btn-print">
                        <i class="fas fa-filter"></i> FILTER
                    </button>
                </div>
            </div>
        </form>

        <div class="divider"></div>

        {{-- 🔹 Form Cetak --}}
        <form id="form-cetak" method="GET" action="{{ route('batalbeli.cetak') }}" target="_blank">
            <input type="hidden" name="start_date" value="{{ request('start_date') }}">
            <input type="hidden" name="end_date" value="{{ request('end_date') }}">
            <input type="hidden" name="nofakturbeli" value="{{ request('nofakturbeli') }}">

            <table class="table-laporan">
                <thead>
                    <tr>
                        <th><input type="checkbox" id="checkAll"></th>
                        <th>No Faktur Beli</th>
                        <th>No Faktur Batal</th>
                        <th>Staff</th>
                        <th>Nama Penjual</th>
                        <th>Nama Barang</th>
                        <th>Berat</th>
                        <th>Harga Beli</th>
                        <th>Harga Batal</th>
                        <th>Keterangan</th>
                        <th>Tanggal</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($transaksi as $row)
                        <tr>
                            <td>
                                <input type="checkbox" name="id[]" value="{{ $row->id }}">
                            </td>
                            <td>{{ $row->nofakturbeli }}</td>
                            <td>{{ $row->nofakturbatalbeli }}</td>
                            <td>{{ $row->namastaff }}</td>
                            <td>{{ $row->namapenjual }}</td>
                            <td>{{ $row->namabarang }}</td>
                            <td>{{ $row->berat }}</td>
                            <td>{{ number_format($row->hargabeli, 0, ',', '.') }}</td>
                            <td>{{ number_format($row->hargabatalbeli, 0, ',', '.') }}</td>
                            <td>{{ $row->keterangan }}</td>
                            <td>{{ $row->created_at->format('d-m-Y') }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="11">Tidak ada data batal beli</td>
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
                                this.innerHTML = '<i class="fas fa-print"></i> CETAK YANG DIPILIH';
                                this.style.background = 'linear-gradient(135deg, #2c3e50 0%, #4a6491 100%)';
                            }, 2000);
                        }, 1500);
                    });
                }
            });
        </script>
</body>

</html>