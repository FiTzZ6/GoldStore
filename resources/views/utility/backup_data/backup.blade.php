<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Backup & Restore</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css"/>
    <style>
        .container { display: flex; justify-content: space-around; padding: 50px; }
        .card { background: #f1f1f1; padding: 30px; border-radius: 10px; text-align: center; width: 40%; }
        button { padding: 10px 20px; border: none; border-radius: 5px; cursor: pointer; }
        .btn-backup { background: #007bff; color: #fff; }
        .btn-restore { background: #28a745; color: #fff; }
    </style>
</head>
<body>
     @include('partials.navbar')
    <div class="container">
        <!-- Backup -->
        <div class="card">
            <h2>Backup Data</h2>
            <i class="fas fa-database fa-2x"></i>
            <p>Hasil backup akan tersimpan dalam file .sql</p>
            <form action="#" method="POST">
                @csrf
                <button type="submit" class="btn-backup">Backup</button>
            </form>
        </div>

        <!-- Restore -->
        <div class="card">
            <h2>Restore Data</h2>
            <i class="fas fa-paper-plane fa-2x"></i>
            <p>Upload file SQL untuk merestore database.</p>
            <form action="#" method="POST" enctype="multipart/form-data">
                @csrf
                <input type="file" name="restore_file" required>
                <br><br>
                <button type="submit" class="btn-restore">Restore</button>
            </form>
        </div>
    </div>
</body>
</html>
