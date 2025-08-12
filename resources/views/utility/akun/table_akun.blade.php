<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="{{ asset('css/utility/menu_akun/table_akun.css') }}">
    <link href="https://fonts.googleapis.com/css?family=Montserrat:400,800" rel="stylesheet">
    <title>Halaman Utility</title>
</head>

<body>
    @include('partials.navbar')
    <div class="container">
        <h2>Daftar Akun</h2>
        <button class="btn btn-primary mb-3" onclick="openModal()">Tambah Akun</button>

        @if(session('msg'))
                    <div id="popupAlert" style="
                position: fixed;
                top: 20px;
                right: 20px;
                padding: 15px 20px;
                border-radius: 8px;
                color: white;
                background-color: {{ session('status') === 'success' ? '#28a745' : '#dc3545' }};
                box-shadow: 0 4px 8px rgba(0,0,0,0.2);
                z-index: 9999;
                display: flex;
                align-items: center;
            ">
                        <span style="flex:1">{{ session('msg') }}</span>
                        <button onclick="document.getElementById('popupAlert').remove()" style="
                    background: none;
                    border: none;
                    color: white;
                    font-size: 18px;
                    cursor: pointer;
                    margin-left: 10px;
                ">&times;</button>
                    </div>
                    <script>
                        setTimeout(() => {
                            const popup = document.getElementById('popupAlert');
                            if (popup) {
                                popup.remove();
                            }
                        }, 7000); // hilang otomatis 3 detik
                    </script>
        @endif

        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>No.</th>
                    <th>Nama</th>
                    <th>Username</th>
                    <th>KD Toko</th>
                    <th>Tipe User</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach($users as $i => $user)
                    <tr>
                        <td>{{ $i + 1 }}</td>
                        <td>{{ $user->name }}</td>
                        <td>{{ $user->username }}</td>
                        <td>{{ $user->kdtoko }}</td>
                        <td>{{ $user->userTypeData->usertype ?? '-' }}</td>
                        <td>
                            <button class="btn btn-warning btn-sm" onclick="openModal({{ $user }})">Edit</button>
                            <form action="{{ route('user.destroy', $user->kduser) }}" method="POST"
                                style="display:inline-block">
                                @csrf
                                @method('DELETE')
                                <button type="submit" onclick="return confirm('Hapus akun ini?')"
                                    class="btn btn-danger btn-sm">Hapus</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <!-- ===== Modal Form Tambah/Edit ===== -->
    <div id="akunModal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeModal()">&times;</span>
            <h3 id="modalTitle">Tambah Akun</h3>
            <form id="akunForm" method="POST" action="{{ route('user.store') }}">
                @csrf
                <input type="hidden" name="_method" id="formMethod" value="POST">

                <div class="form-group">
                    <label>Nama</label>
                    <input type="text" name="name" id="name" required>
                </div>

                <div class="form-group">
                    <label>Username</label>
                    <input type="text" name="username" id="username" required>
                </div>

                <div class="form-group">
                    <label>Password</label>
                    <input type="password" name="password" id="password">
                </div>

                <div class="form-group">
                    <label>KD Toko</label>
                    <input type="number" name="kdtoko" id="kdtoko" required>
                </div>

                <div class="form-group">
                    <label>Tipe User</label>
                    <select name="usertype" id="usertype" required>
                        <option value="">-- Pilih Tipe User --</option>
                        @foreach(\App\Models\UserType::all() as $type)
                            <option value="{{ $type->usertypeid }}">{{ $type->usertype }}</option>
                        @endforeach
                    </select>
                </div>

                <button type="submit" class="btn btn-primary">Simpan</button>
            </form>
        </div>
    </div>

    <script>
        function openModal(user = null) {
            const modal = document.getElementById('akunModal');
            const form = document.getElementById('akunForm');
            const methodInput = document.getElementById('formMethod');
            const title = document.getElementById('modalTitle');

            if (user) {
                // Edit mode
                form.action = `/utility/akun/${user.kduser}`;
                methodInput.value = 'PUT';
                title.innerText = 'Edit Akun';
                document.getElementById('name').value = user.name;
                document.getElementById('username').value = user.username;
                document.getElementById('password').value = '';
                document.getElementById('kdtoko').value = user.kdtoko;
                document.getElementById('usertype').value = user.usertype;
            } else {
                // Tambah mode
                form.action = `{{ route('user.store') }}`;
                methodInput.value = 'POST';
                title.innerText = 'Tambah Akun';
                form.reset();
            }

            modal.style.display = 'block';
        }


        function closeModal() {
            document.getElementById('akunModal').style.display = 'none';
        }

        window.onclick = function (e) {
            const modal = document.getElementById('akunModal');
            if (e.target == modal) {
                closeModal();
            }
        }
    </script>
</body>

</html>