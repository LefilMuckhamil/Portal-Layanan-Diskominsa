<?php

namespace App\Http\Controllers;

use App\Models\Pengajuan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserDashboardController extends Controller
{
    public function riwayat()
    {
        // Mengambil data pengajuan khusus milik user yang sedang login, diurutkan dari yang terbaru
        $pengajuans = Pengajuan::where('user_id', Auth::id())->latest()->get();
        
        return view('user.riwayat', compact('pengajuans'));
    }
}