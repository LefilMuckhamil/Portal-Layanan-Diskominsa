<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Support\PhoneNumber;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Validation\Rules\Password;

class AuthController extends Controller
{
    // login
    public function authenticate(Request $request)
    {
        $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        // Normalisasi email sebelum autentikasi agar konsisten terlepas dari kapitalisasi/spasi.
        $request->merge(['email' => strtolower(trim($request->email))]);

        // Rate limiting anti brute force: maksimal 5 percobaan gagal per menit per email + IP.
        $throttleKey = 'login:'.strtolower($request->email).'|'.$request->ip();
        if (RateLimiter::tooManyAttempts($throttleKey, 5)) {
            $menit = (int) ceil(RateLimiter::availableIn($throttleKey) / 60);

            return back()->withErrors([
                'email' => "Terlalu banyak percobaan login. Coba lagi dalam {$menit} menit.",
            ])->onlyInput('email');
        }

        $user = User::where('email', $request->email)->first();

        if (! $user || ! Hash::check($request->password, $user->password)) {
            RateLimiter::hit($throttleKey, 60);

            return back()->withErrors([
                'email' => 'Maaf, Email atau Password Anda salah.',
            ])->onlyInput('email');
        }

        RateLimiter::clear($throttleKey);

        // Gate status akun: pending/ditolak tidak diizinkan masuk.
        if ($user->status_akun === User::STATUS_PENDING) {
            return redirect()->route('login')->with('error', 'Akun ASN Anda belum diaktivasi. Menunggu verifikasi Administrator Diskominsa.');
        }

        if ($user->status_akun === User::STATUS_DITOLAK) {
            return redirect()->route('login')->with('error', 'Pendaftaran akun Anda ditolak. Silakan hubungi Diskominsa untuk klarifikasi.');
        }

        Auth::login($user);
        // Mencegah session fixation.
        $request->session()->regenerate();

        return $user->role === 'admin'
            ? redirect()->route('admin.dashboard')
            : redirect()->intended('/');
    }

    // register
    public function registerProcess(Request $request)
    {
        $request->merge(['no_hp' => PhoneNumber::normalize($request->no_hp)]);

        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'unit_kerja' => ['required', 'string', 'max:255'],
            'jabatan' => ['required', 'string', 'max:255'],
            'no_hp' => ['required', 'string', 'regex:/^(\+62|62|08)[0-9]{8,13}$/', 'min:10', 'max:16', 'unique:users,no_hp'],

            'password' => [
                'required',
                'confirmed',
                Password::min(8)->letters()->numbers()->mixedCase(),
            ],

            'nip' => [
                'required',
                'string',
                'regex:/^[0-9]{18}$/',
                'unique:users,nip',
            ],

            'email' => [
                'required',
                'string',
                'email:rfc,dns',
                'max:255',
                'unique:users,email',
                'ends_with:@acehbaratkab.go.id',
            ],
        ], [
            'email.ends_with' => 'Email harus menggunakan domain resmi @acehbaratkab.go.id.',
            'email.unique' => 'Alamat email dinas ini sudah terdaftar. Silakan gunakan email lain atau masuk ke akun Anda.',
            'nip.unique' => 'NIP ini sudah terdaftar di sistem. Silakan gunakan akun yang sudah ada.',
            'nip.regex' => 'Format NIP tidak valid. NIP harus terdiri dari 18 digit angka.',
            'no_hp.required' => 'Nomor HP wajib diisi.',
            'no_hp.regex' => 'Format nomor HP/WhatsApp tidak valid. Gunakan format 08xxxxxxxxxx atau 62xxxxxxxxxx.',
            'password.min' => 'Kata sandi minimal harus 8 karakter.',
            'password.mixed_case' => 'Kata sandi harus mengandung kombinasi huruf besar dan huruf kecil (contoh: Password123).',
            'password.letters' => 'Kata sandi harus mengandung huruf.',
            'password.numbers' => 'Kata sandi harus mengandung setidaknya satu angka.',
            'password.confirmed' => 'Konfirmasi kata sandi tidak cocok.',
            'password.uncompromised' => 'Kata sandi ini terlalu mudah ditebak. Gunakan kombinasi huruf, angka, dan huruf kapital.',
        ]);

        // normalisasi email ke huruf kecil sebelum disimpan
        $request->merge(['email' => strtolower(trim($request->email))]);

        // Sanitasi anti-XSS pada teks bebas sebelum disimpan.
        // strip_tags tidak membuang isi <script>, jadi tag script dihapus eksplisit terlebih dahulu.
        $sanitize = fn (string $value): string => trim(strip_tags(preg_replace('#<script\b[^>]*>.*?</script>#is', '', trim($value))));

        $name = $sanitize($request->name);
        $unitKerja = $sanitize($request->unit_kerja);
        $jabatan = $sanitize($request->jabatan);

        // Whitelist fillable eksplisit — hanya field aman yang boleh di-mass-assignment.
        $user = User::create([
            'name' => $name,
            'nip' => $request->nip,
            'unit_kerja' => $unitKerja,
            'jabatan' => $jabatan,
            'no_hp' => $request->no_hp,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        // Anti privilege escalation & mass assignment:
        // role WAJIB 'user' dan status_akun WAJIB 'pending' (bukan dari input user).
        $user->forceFill([
            'role' => 'user',
            'status_akun' => 'pending',
        ])->save();

        // JANGAN login otomatis — akun harus diverifikasi admin terlebih dahulu.
        // Bersihkan sesi register untuk mencegah fixation.
        $request->session()->regenerate();

        return redirect()->route('login')->with('info', 'Pendaftaran berhasil! Akun ASN Anda sedang dalam proses verifikasi oleh Administrator Diskominsa.');
    }

    // logout
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}
