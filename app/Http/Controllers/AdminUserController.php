<?php

namespace App\Http\Controllers;

use App\Models\Pengajuan;
use App\Models\User;
use App\Support\PhoneNumber;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;

class AdminUserController extends Controller
{
    public function index(Request $request)
    {
        $query = User::query();

        if ($request->filled('status_akun') && in_array($request->status_akun, ['pending', 'aktif', 'ditolak'], true)) {
            $query->where('status_akun', $request->status_akun);
        }

        if ($request->filled('search')) {
            $search = addcslashes(trim($request->search), '%_');
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('nip', 'like', "%{$search}%")
                    ->orWhere('unit_kerja', 'like', "%{$search}%");
            });
        }

        $statusCounts = User::query()
            ->selectRaw('status_akun, count(*) as total')
            ->groupBy('status_akun')
            ->pluck('total', 'status_akun');

        $users = $query->orderBy('created_at', 'desc')->paginate(10)->withQueryString();

        return view('admin.users.index', compact('users', 'statusCounts'));
    }

    public function store(Request $request)
    {
        $request->merge(['no_hp' => PhoneNumber::normalize($request->no_hp)]);

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => [
                'required', 'string', 'email', 'max:255',
                'unique:users,email',
                'regex:/^[^@]+@acehbaratkab\.go\.id$/i',
            ],
            'nip' => ['nullable', 'string', 'max:255', 'unique:users,nip'],
            'unit_kerja' => ['nullable', 'string', 'max:255'],
            'jabatan' => ['nullable', 'string', 'max:255'],
            'no_hp' => ['required', 'string', 'regex:/^(\+62|62|08)[0-9]{8,13}$/', 'min:10', 'max:16', 'unique:users,no_hp'],
            'password' => ['required', 'string', 'min:8'],
            'role' => ['required', Rule::in(['admin', 'user'])],
        ], [
            'email.regex' => 'Email wajib menggunakan domain resmi @acehbaratkab.go.id.',
            'email.unique' => 'Email ini sudah terdaftar di sistem.',
            'nip.unique' => 'NIP ini sudah terdaftar di sistem.',
            'no_hp.unique' => 'Nomor HP ini sudah terdaftar di sistem.',
            'no_hp.regex' => 'Format nomor HP/WhatsApp tidak valid. Gunakan format 08xxxxxxxxxx atau 62xxxxxxxxxx.',
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => strtolower(trim($validated['email'])),
            'nip' => $validated['nip'],
            'unit_kerja' => $validated['unit_kerja'],
            'jabatan' => $validated['jabatan'],
            'no_hp' => $validated['no_hp'],
            'password' => Hash::make($validated['password']),
        ]);

        $user->forceFill([
            'role' => $validated['role'],
            // Akun yang dibuat langsung oleh admin dianggap sudah terverifikasi.
            'status_akun' => 'aktif',
            'approved_at' => now(),
            'approved_by' => Auth::id(),
        ])->save();

        return back()->with('sukses', 'Akun '.$user->name.' berhasil dibuat.');
    }

    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $request->merge(['no_hp' => PhoneNumber::normalize($request->no_hp)]);

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => [
                'required', 'string', 'email', 'max:255',
                Rule::unique('users', 'email')->ignore($user->id),
                'regex:/^[^@]+@acehbaratkab\.go\.id$/i',
            ],
            'nip' => ['nullable', 'string', 'max:255', Rule::unique('users', 'nip')->ignore($user->id)],
            'unit_kerja' => ['nullable', 'string', 'max:255'],
            'jabatan' => ['nullable', 'string', 'max:255'],
            'no_hp' => ['required', 'string', 'regex:/^(\+62|62|08)[0-9]{8,13}$/', 'min:10', 'max:16', Rule::unique('users', 'no_hp')->ignore($user->id)],
            'password' => ['nullable', 'string', 'min:8'],
            'role' => ['required', Rule::in(['admin', 'user'])],
            'status_akun' => ['required', Rule::in(['pending', 'aktif', 'ditolak'])],
        ], [
            'email.regex' => 'Email wajib menggunakan domain resmi @acehbaratkab.go.id.',
            'email.unique' => 'Email ini sudah terdaftar di sistem.',
            'nip.unique' => 'NIP ini sudah terdaftar di sistem.',
            'no_hp.unique' => 'Nomor HP ini sudah terdaftar di sistem.',
            'no_hp.regex' => 'Format nomor HP/WhatsApp tidak valid. Gunakan format 08xxxxxxxxxx atau 62xxxxxxxxxx.',
        ]);

        if ($validated['role'] !== $user->role) {
            if ($user->id === Auth::id() && $validated['role'] !== 'admin') {
                return back()->with('error', 'Anda tidak dapat mengubah peran (role) akun Anda sendiri.');
            }

            if ($user->role === 'admin' && $validated['role'] === 'user' && User::where('role', 'admin')->count() <= 1) {
                return back()->with('error', 'Tidak dapat menurunkan admin terakhir. Minimal satu admin harus tetap ada.');
            }
        }

        $role = $validated['role'] ?? null;
        $statusAkun = $validated['status_akun'] ?? null;
        unset($validated['role'], $validated['status_akun']);

        // Self-Guard: admin yang sedang login tidak boleh mengunci status akunnya sendiri.
        if ($user->id === Auth::id()) {
            $statusAkun = 'aktif';
        }

        $password = $validated['password'] ?? null;

        if ($password !== null) {
            $validated['password'] = Hash::make($password);
        } else {
            unset($validated['password']);
        }

        $user->update($validated);

        if ($role !== null && $role !== $user->role) {
            $user->forceFill(['role' => $role])->save();
            Log::info('Admin ID '.auth()->id()." mengubah role User ID {$user->id} menjadi {$role}");
        }

        if ($statusAkun !== null && $statusAkun !== $user->status_akun) {
            $user->forceFill([
                'status_akun' => $statusAkun,
                'approved_at' => now(),
                'approved_by' => Auth::id(),
            ])->save();
            Log::info('Admin ID '.auth()->id()." mengubah status akun User ID {$user->id} menjadi {$statusAkun}");
        }

        return back()->with('sukses', 'Data akun '.$user->name.' berhasil diperbarui.');
    }

    public function aktivasi($id)
    {
        $user = User::findOrFail($id);

        if ($user->id === Auth::id()) {
            return back()->with('error', 'Anda tidak dapat mengubah status verifikasi akun Anda sendiri.');
        }

        if ($user->role === 'admin') {
            return back()->with('error', 'Status verifikasi tidak berlaku untuk akun administrator.');
        }

        $user->forceFill([
            'status_akun' => 'aktif',
            'approved_at' => now(),
            'approved_by' => Auth::id(),
        ])->save();

        Log::info('Admin ID '.auth()->id()." mengaktivasi User ID {$user->id}");

        return back()->with('sukses', 'Akun '.$user->name.' berhasil diaktivasi.');
    }

    public function tolak($id)
    {
        $user = User::findOrFail($id);

        if ($user->id === Auth::id()) {
            return back()->with('error', 'Anda tidak dapat mengubah status verifikasi akun Anda sendiri.');
        }

        if ($user->role === 'admin') {
            return back()->with('error', 'Status verifikasi tidak berlaku untuk akun administrator.');
        }

        $user->forceFill([
            'status_akun' => 'ditolak',
            'approved_at' => now(),
            'approved_by' => Auth::id(),
        ])->save();

        Log::info('Admin ID '.auth()->id()." menolak User ID {$user->id}");

        return back()->with('sukses', 'Akun '.$user->name.' berhasil ditolak.');
    }

    public function destroy($id)
    {
        $user = User::findOrFail($id);

        if ($user->id === Auth::id()) {
            return back()->with('error', 'Anda tidak dapat menghapus akun Anda sendiri.');
        }

        if ($user->role === 'admin' && User::where('role', 'admin')->count() <= 1) {
            return back()->with('error', 'Tidak dapat menghapus admin terakhir. Minimal satu admin harus tetap ada.');
        }

        if (Pengajuan::where('user_id', $user->id)->exists()) {
            return back()->with('error', 'Akun '.$user->name.' memiliki data pengajuan. Hapus pengajuan terlebih dahulu atau nonaktifkan akun.');
        }

        $user->delete();

        return back()->with('sukses', 'Akun '.$user->name.' berhasil dihapus.');
    }
}
