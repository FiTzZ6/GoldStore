<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Uang_Kas</title>
</head>
<body>
    @extends('layouts.app')

@section('content')
<div class="container">
    <h2 class="mb-4 text-center">DAFTAR KAS KELUAR MASUK</h2>
    <a href="{{ route('kas.create') }}" class="btn btn-success mb-3">+ Transaksi Kas</a>

    <table id="kasTable" class="table table-bordered">
        <thead>
            <tr>
                <th>TANGGAL</th>
                <th>JUMLAH</th>
                <th>KATEGORI</th>
                <th>KETERANGAN</th>
                <th>TYPE</th>
                <th>ACTION</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($kas as $row)
            <tr>
                <td>{{ $row->tanggal }}</td>
                <td>Rp. {{ number_format($row->jumlahkas, 0, ',', '.') }}</td>
                <td>{{ $row->idparameterkas }}</td>
                <td>{{ $row->keterangan }}</td>
                <td>{{ $row->type }}</td>
                <td>
                    <a href="{{ route('kas.edit', $row->id) }}" class="btn btn-warning btn-sm">✏</a>
                    <form action="{{ route('kas.destroy', $row->id) }}" method="POST" style="display:inline-block;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" onclick="return confirm('Yakin mau hapus?')" class="btn btn-danger btn-sm">🗑</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>

@push('scripts')
<script>
    $(document).ready(function() {
        $('#kasTable').DataTable();
    });
</script>
@endpush
@endsection

</body>
</html>