<!DOCTYPE html>
<html lang="id">
<head>
    <title>Halaman Area</title>
    <link rel="stylesheet" href="{{ asset('css/datamaster/caripel.css') }}">
</head>
<body>
@include('partials.navbar')

<div class="container">
        <h1>Cari Pelanggan</h1>
        <input type="text" id="search" class="search-box" placeholder="Ketik nama atau kode pelanggan...">
        <div class="results" id="results"></div>
    </div>

    <script>
        const searchBox = document.getElementById('search');
        const resultsDiv = document.getElementById('results');

        searchBox.addEventListener('keyup', function() {
            let query = this.value;

            if (query.length > 1) {
                fetch(`/caripelanggan/search?query=${query}`)
                    .then(res => res.json())
                    .then(data => {
                        resultsDiv.innerHTML = '';
                        if (data.length > 0) {
                            data.forEach(p => {
                                resultsDiv.innerHTML += `
                                    <div class="card">
                                        <h3>${p.namapelanggan}</h3>
                                        <p><strong>Kode:</strong> ${p.kdpelanggan}</p>
                                        <p><strong>Alamat:</strong> ${p.alamatpelanggan}</p>
                                        <p><strong>Telp:</strong> ${p.notelp}</p>
                                        <p><strong>Poin:</strong> ${p.poin}</p>
                                    </div>
                                `;
                            });
                        } else {
                            resultsDiv.innerHTML = '<p>Tidak ada pelanggan ditemukan.</p>';
                        }
                    });
            } else {
                resultsDiv.innerHTML = '';
            }
        });
    </script>
</body>
</html>
