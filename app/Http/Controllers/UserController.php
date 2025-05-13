<?php

 

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function showRegisterForm()
    {
        return view('auth.register');
    }

    public function login(Request $request)
    {
        // Validasi kredensial login
        $request->validate([
            'user_name' => 'required|string',
            'password' => 'required|string',
        ]);
    
        // Ambil kredensial dari request
        $credentials = $request->only('user_name', 'password');
    
        // Coba autentikasi pengguna
        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
    
            // Ambil data pengguna setelah autentikasi
            $user = Auth::user();
            $request->session()->put([
                'user_name' => $user->user_name,
                'user_fullname' => $user->user_fullname,
                'user_nik' => $user->user_nik,
                'department' => $user->department,
                'posisi' => $user->posisi,
            ]);
    
            return redirect()->route('user.dashboard'); // Redirect ke dashboard pengguna
        }
    
        // Jika autentikasi gagal
        return back()->withErrors([
            'loginError' => 'The provided credentials do not match our records.',
        ])->onlyInput('user_name');
    }




    public function register(Request $request)
{
    // Validasi input
    $validator = Validator::make($request->all(), [
        'user_name' => 'required|string|max:255|unique:users',
        'user_email' => 'required|string|email|max:255|unique:users',
        'password' => 'required|string|min:8|confirmed',
    ]);

    if ($validator->fails()) {
        return back()->withErrors($validator)->withInput();
    }

    // Membuat pengguna baru
    $user = User::create([
        'user_name' => $request->user_name,
        'user_email' => $request->user_email,
        'password' => Hash::make($request->password),
        'user_role_id' => 2, // Assign default role (e.g., 'User')
        'department' => $request->department,
        'posisi' => $request->posisi,
        'user_nik' => $request->user_nik,
        'user_fullname' => $request->user_fullname,
        'is_active' => true, // Mengaktifkan user secara default
    ]);

    // Mengarahkan ke halaman login dengan pesan sukses
    return redirect()->route('login')->with('success', 'User successfully registered');
}
public function showChangePasswordForm()
{
    return view('auth.change_password');
}
public function changePassword(Request $request)
{
    // Validasi input
    $validator = Validator::make($request->all(), [
        'current_password' => 'required',
        'new_password' => 'required|string|min:8|confirmed',
    ]);

    if ($validator->fails()) {
        return back()->withErrors($validator)->withInput();
    }

    // Ambil pengguna yang sedang login
    $user = Auth::user();

    // Cek apakah password lama cocok dengan password yang tersimpan
    if (!Hash::check($request->current_password, $user->password)) {
        return back()->withErrors(['current_password' => 'Current password is incorrect.']);
    }

    // Update password dengan password baru
    $user->password = Hash::make($request->new_password);
    $user->save();

    // Redirect ke halaman profil atau halaman lain dengan pesan sukses
    return back()->with('success', 'Password successfully changed');
}

    public function logout(Request $request)
    {
        Auth::logout();  

         
        $request->session()->invalidate();

         
        $request->session()->regenerateToken();

         
        return redirect('/login');
    }

   
}
