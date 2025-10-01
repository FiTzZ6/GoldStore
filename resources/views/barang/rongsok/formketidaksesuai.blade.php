<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Form Ketidaksesuaian</title>
    <link rel="stylesheet" href="{{ asset('css/barang/ronsok/formsesuai.css') }}">
</head>

<body>
    @include('partials.navbar')
    <div class="container">
        <h1>Formulir Ketidaksesuaian</h1>

        <!-- Form Input -->
        <form action="{{ route('formketidaksesuaian.store') }}" method="POST" class="mb-4">
            @csrf
            <div class="mb-3">
                <label>Barang</label>
                <select name="kdbarang" class="form-control" required>
                    <option value="">-- Pilih Barang --</option>
                    @foreach($barang as $b)
                        <option value="{{ $b->kdbarang }}">{{ $b->namabarang }}</option>
                    @endforeach
                </select>
            </div>

            <div class="mb-3">
                <label>Pelanggan</label>
                <select name="kdpelanggan" class="form-control" required>
                    <option value="">-- Pilih Pelanggan --</option>
                    @foreach($pelanggan as $p)
                        <option value="{{ $p->kdpelanggan }}">{{ $p->namapelanggan }}</option>
                    @endforeach
                </select>
            </div>

            <div class="mb-3">
                <label>Deskripsi</label>
                <textarea name="deskripsi" class="form-control" required></textarea>
            </div>

            <button type="submit" class="btn btn-primary">Simpan</button>
        </form>
    </div>
</body>

</html>