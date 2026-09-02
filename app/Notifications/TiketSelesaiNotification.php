<?php

namespace App\Notifications;

use App\Models\Pengajuan;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class TiketSelesaiNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public Pengajuan $pengajuan,
        public ?string $catatanAdmin = null,
    ) {}

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $this->pengajuan->loadMissing('pemohon', 'layanan');
        $nama = strip_tags($this->pengajuan->pemohon?->nama ?? 'Pemohon');
        $layanan = strip_tags($this->pengajuan->layanan?->nama ?? 'IT');
        $tiket = $this->pengajuan->nomor_tiket;
        $catatan = strip_tags($this->catatanAdmin ?? 'Permohonan telah selesai diproses sesuai SOP dan ketentuan yang berlaku.');

        return (new MailMessage)
            ->subject("[Diskominsa Aceh Barat] Pengajuan Layanan Selesai - {$tiket}")
            ->greeting("Halo, {$nama}")
            ->line("Kabar baik! Pengajuan layanan {$layanan} dengan nomor tiket {$tiket} telah SELESAI diproses oleh Tim Teknis Diskominsa.")
            ->line("Catatan Petugas: {$catatan}")
            ->action('Lihat Detail & Unduh Berkas', url('/?tracking='.urlencode($tiket).'#tracking'))
            ->salutation("Salam hangat,\nDiskominsa Kabupaten Aceh Barat");
    }
}
