<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Models\UserModel;
use Illuminate\Support\Facades\Validator;

class LoginController extends Controller
{
    public function index()
    {
        $typeuser = Session::get('usertype');

        if ($typeuser == 1 || $typeuser == 2 || $typeuser == 3 || $typeuser == 7 || $typeuser == 8) {
            return redirect()->route('laporan.dashboard');
        } elseif ($typeuser == 4 || $typeuser == 5) {
            return redirect()->route('barang.index');
        } elseif ($typeuser == 6) {
            return redirect()->route('jual.index');
        } elseif ($typeuser == 9) {
            return redirect()->route('beli.index');
        }

        return view('login');
    }

    public function masuk(Request $request)
    {
        $request->validate([
            'username' => 'required',
            'password' => 'required'
        ]);

        $user = UserModel::where('username', $request->username)->first();

        if ($user) {
            if (Hash::check($request->password, $user->password)) {

                Session::put([
                    'iduser' => $user->kduser,
                    'name' => $user->name,
                    'username' => $user->username,
                    'typeuser' => $user->usertype,
                    'kdtoko' => $user->kdtoko,
                ]);

                switch ($user->usertype) {
                    case 1:
                    case 2:
                    case 3:
                    case 7:
                    case 8:
                        return redirect()->route('laporan.dashboard');
                    case 4:
                    case 5:
                        return redirect()->route('barang.index');
                    case 6:
                        return redirect()->route('laporan.dashboard');
                    case 9:
                        return redirect()->route('beli.index');
                    default:
                        return redirect()->route('login')->with('msg', 'Hak akses tidak dikenali');
                }

            } else {
                return redirect()->route('login')->with('msg', 'Username/Password Salah');
            }
        }

        return redirect()->route('login')->with('msg', 'Akun Tidak Terdaftar Pada Sistem');
    }

    public function formregister()
    {
        return view('register');
    }

    public function daftarakun(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'username' => 'required|unique:user,username',
            'password' => 'required|min:6',
            'usertype' => 'required|integer',
            'kdtoko' => 'required|integer',
        ]);

        $user = UserModel::create([
            'name' => $request->name,
            'username' => $request->username,
            'password' => Hash::make($request->password),
            'usertype' => $request->usertype,
            'kdtoko' => $request->kdtoko,
            'createddate' => now(),
        ]);

        if ($user) {
            return redirect()->route('login')->with('msg', 'Pendaftaran Akun Telah Berhasil!');
        }

        return redirect()->route('login')->with('msg', 'Pendaftaran Akun Gagal!');
    }

    public function logout()
    {
        Session::flush();
        return redirect()->route('login')->with('logout_notification', 'logged_out');
    }

    public function listAkun()
    {
        $users = UserModel::with('userTypeData')->get();
        return view('utility.table_akun', compact('users'));
    }
    // Form tambah akun
    public function createAkun()
    {
        $usertypes = UserType::all();
        return view('utility.create_akun', compact('usertypes'));
    }

    // Simpan akun baru
    public function storeAkun(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string',
            'username' => 'required|unique:user,username',
            'password' => 'required|min:6',
            'usertype' => 'required|integer',
            'kdtoko' => 'required|integer',
        ]);
        if ($validator->fails()) {
            return redirect()->route('utility.table_akun')
                ->with([
                    'msg' => 'password kamu kurang harusnya 6, kamu tidak memnuhi kriteria!',
                    'status' => 'error'
                ]);
        }

        $user = UserModel::create([
            'name' => $request->name,
            'username' => $request->username,
            'password' => Hash::make($request->password),
            'usertype' => $request->usertype,
            'kdtoko' => $request->kdtoko,
            'createddate' => now(),
        ]);

        if ($user) {
            return redirect()->route('utility.table_akun')->with([
                'msg' => 'Yeay Akun kamu telah berhasil dibuat!',
                'status' => 'success'
            ]);
        }

        return redirect()->route('utility.table_akun')->with([
            'msg' => 'Gagal menambahkan akun!',
            'status' => 'error'
        ]);
    }

    // Form edit akun
    public function editAkun($id)
    {
        $user = UserModel::findOrFail($id);
        $usertypes = UserType::all();
        return view('utility.edit_akun', compact('user', 'usertypes'));
    }

    // Update akun
    public function updateAkun(Request $request, $id)
    {
        $user = UserModel::findOrFail($id);

        $request->validate([
            'name' => 'required|string',
            'username' => 'required|unique:user,username,' . $id . ',kduser',
            'password' => 'nullable|min:6',
            'usertype' => 'required|integer',
            'kdtoko' => 'required|integer',
        ]);

        $data = [
            'name' => $request->name,
            'username' => $request->username,
            'usertype' => $request->usertype,
            'kdtoko' => $request->kdtoko,
        ];

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $user->update($data);

        if ($user) {
            return redirect()->route('utility.table_akun')->with([
                'msg' => 'Akun berhasil diEdit!',
                'status' => 'success'
            ]);
        }

        return redirect()->route('utility.table_akun')->with([
            'msg' => 'Gagal mengedit table!',
            'status' => 'error'
        ]);
    }

    // Hapus akun
    public function destroyAkun($id)
    {
        $user = UserModel::findOrFail($id)->delete();
        if ($user) {
            return redirect()->route('utility.table_akun')->with([
                'msg' => 'Akun berhasil dihapus!',
                'status' => 'success'
            ]);
        }

        return redirect()->route('utility.table_akun')->with([
            'msg' => 'Gagal menghapus table kamu!',
            'status' => 'error'
        ]);
    }
}
