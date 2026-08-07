<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;   // Tambahkan ini
use Illuminate\Support\Facades\Cache;  // Tambahkan ini

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        // Bagikan status saklar chat ke SEMUA halaman view sekaligus!
        View::share('chatAktif', Cache::get('chat_global_aktif', true));
    }
}