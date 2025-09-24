<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Batal Beli - TANGCAL</title>
    <link rel="stylesheet" href="{{ asset('css/beli/riwayat_batal.css') }}">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>

<body>
    @include('partials.navbar')
    <div class="container">
        <div class="header">
            <!-- Header Top: Judul di tengah -->
            <div class="header-top">
                <h1><i class="fas fa-times-circle"></i> Daftar Pembatalan</h1>
            </div>

            <!-- Header Bottom: Export di kiri, Search/Sorting di kanan -->
            <div class="header-bottom">
                <div class="right-controls">
                    <div class="icon-group">
                        <button title="Sorting" onclick="sortTable()"><i class="fas fa-sort"></i></button>
                        <button title="Refresh" onclick="refreshPage()"><i class="fas fa-sync"></i></button>
                    </div>
                    <input type="text" placeholder="Search">
                </div>
            </div>
        </div>

        <div class="content">
            <div class="table-container">
                <table>
                    <thead>
                        <tr>
                            <th>Tanggal</th>
                            <th>No. Batal</th>
                            <th>No. Beli</th>
                            <th>Nama Barang</th>
                            <th>Berat</th>
                            <th>Harga</th>
                            <th>Kondisi Barang</th>
                            <th>Kondisi Batal</th>
                            <th>Ket</th>
                            <th>Staff</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($riwayat as $item)
                                                    <tr>
                                                        <td>{{ $item->created_at->format('d.m.Y H:i:s') }}</td>
                                                        <td>{{ $item->nofakturbatalbeli }}</td>
                                                        <td>{{ $item->nofakturbeli }}</td>
                                                        <td>{{ $item->namabarang }}</td>
                                                        <td>{{ number_format($item->berat, 3, ',', '.') }}</td>
                                                        <td>Rp. {{ number_format($item->hargabeli, 2, ',', '.') }}</td>

                                                        @php
                                                            $classMap = [
                                                                'BAIK' => 'good',
                                                                'RUSAK' => 'damaged',
                                                                'LECET' => 'warning',
                                                                'HILANG' => 'hilang',
                                                            ];
                                                        @endphp
                                                        <td>
                                                            <span class="status-badge {{ $classMap[$item->kondisibeli] ?? 'good' }}">
                                                                {{ $item->kondisibeli }}
                                                            </span>
                                                        </td>
                                                        <td>
                                                            <span class="status-badge {{ $classMap[$item->kondisibatalbeli] ?? 'warning' }}">
                                                                {{ $item->kondisibatalbeli }}
                                                            </span>
                                                        </td>

                                                        <td>{{ $item->keterangan }}</td>
                                                        <td>{{ $item->namastaff }}</td>
                                                        <td>
                                                            <button class="action-btn"><i class="fa-solid fa-pen-to-square"></i></button>
                                                            <button class="action-btn"><i class="fas fa-trash"></i></button>
                                                        </td>
                                                    </tr>
                        @empty
                            <tr>
                                <td colspan="11" style="text-align:center;">Tidak ada data pembatalan</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="pagination">
                <div class="showing-info">
                    Showing 1 to 3 of 3 rows
                </div>
                <div class="pagination-controls">
                    <button class="page-btn disabled"><i class="fas fa-chevron-left"></i></button>
                    <span class="current-page">1</span>
                    <button class="page-btn disabled"><i class="fas fa-chevron-right"></i></button>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Set tanggal default
        document.addEventListener('DOMContentLoaded', function () {
            const today = new Date().toISOString().substr(0, 10);
            document.getElementById('start-date').value = "2021-03-01";
            document.getElementById('end-date').value = today;
        });

        function handleExport(value) {
            if (value) {
                alert(`Fitur export ${value.toUpperCase()} akan diproses`);
            }
        }

        function sortTable() {
            alert('Fitur sorting akan diproses');
        }

        function refreshPage() {
            location.reload();
        }
    </script>
</body>

</html>