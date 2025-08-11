<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Models\UserModel;

class LoginController extends Controller    
{
    public function index()
    {
        $typeuser = Session::get('typeuser');

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
                        return redirect()->route('jual.index');
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
}
