<?php

namespace App\Notifications;

use App\Models\Pengajuan;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class TiketDitolakNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public Pengajuan $pengajuan,
        public ?string $alasanPenolakan = null,
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
        $alasan = strip_tags($this->alasanPenolakan ?? 'Berkas atau persyaratan belum memenuhi ketentuan.');

        return (new MailMessage)
            ->subject("[Diskominsa Aceh Barat] Informasi Pengajuan Layanan Ditolak - {$tiket}")
            ->greeting("Halo, {$nama}")
            ->line("Mohon maaf, pengajuan layanan {$layanan} dengan nomor tiket {$tiket} BELUM DAPAT DIPROSES / DITOLAK.")
            ->line("Alasan Penolakan: {$alasan}")
            ->line('Anda dapat meninjau catatan admin atau mengajukan permohonan baru dengan melengkapi berkas yang dibutuhkan.')
            ->action('Cek Detail Tiket', url('/?tracking='.urlencode($tiket).'#tracking'))
            ->salutation("Salam hangat,\nDiskominsa Kabupaten Aceh Barat");
    }
}
