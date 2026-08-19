<?php

namespace App\Notifications;

use App\Models\Pengajuan;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class TiketDibuatNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public Pengajuan $pengajuan,
    ) {}

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $nama = strip_tags($this->pengajuan->data_pengajuan['nama'] ?? 'Pemohon');
        $layanan = strip_tags($this->pengajuan->jenis_layanan);
        $tiket = $this->pengajuan->nomor_tiket;

        return (new MailMessage)
            ->subject("[Diskominsa Aceh Barat] Tiket Pengajuan Berhasil Dibuat - {$tiket}")
            ->greeting("Halo, {$nama}")
            ->line("Pengajuan layanan {$layanan} Anda telah berhasil kami terima.")
            ->line("Nomor Tiket: {$tiket}")
            ->line('Tanggal: '.$this->pengajuan->created_at->format('d M Y, H:i').' WIB')
            ->action('Lacak Status Tiket', url('/?tracking='.urlencode($tiket).'#tracking'))
            ->line('Simpan nomor tiket ini untuk memantau progres permohonan Anda secara berkala.')
            ->salutation("Salam hangat,\nDiskominsa Kabupaten Aceh Barat");
    }
}
