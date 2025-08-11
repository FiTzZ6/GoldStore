<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Dashboard')</title>
    <link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>
<body>
    @include('partials.navbar')

    <div class="company-info" style="text-align: center; margin-top: 50px;">
        @if($company)
        <h1>Toko Emas<br>{{ $company->name }}</h1>
            <img src="{{ asset('storage/assets/' . $company->logo) }}" alt="Logo Perusahaan">
        @else
            <p>Data perusahaan belum tersedia</p>
        @endif
    </div>

</body>
</html>

