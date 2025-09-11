<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Surat Kirim Barang</title>
    <link rel="stylesheet" href="{{ asset('css/datamaster/kategori.css') }}">
</head>
<body>
@include('partials.navbar')

<h1 class="page-title">Daftar Surat Kirim</h1>

@if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif

<a href="{{ route('suratkirim.create') }}" class="btn-primary">+ Tambah Surat Kirim</a>

<table>
    <thead>
        <tr>
            <th>No. Surat</th>
            <th>Tanggal</th>
            <th>Toko Pengirim</th>
            <th>Toko Penerima</th>
            <th>Status</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($suratKirim as $surat)
            <tr>
                <td>{{ $surat->nokirim }}</td>
                <td>{{ $surat->tanggal }}</td>
                <td>{{ $surat->kdtokokirim }}</td>
                <td>{{ $surat->kdtokoterima }}</td>
                <td>{{ $surat->status }}</td>
            </tr>
        @endforeach
    </tbody>
</table>
</body>
</html>
