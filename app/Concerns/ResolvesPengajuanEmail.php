<?php

namespace App\Concerns;

use App\Models\Pengajuan;

trait ResolvesPengajuanEmail
{
    /**
     * Resolve email from pengajuan model ONLY (anti-IDOR).
     * Priority: data_pengajuan['email_reset'] → data_pengajuan['email'] → user->email.
     * Returns null if no valid email found.
     */
    protected function resolveTargetEmail(Pengajuan $pengajuan): ?string
    {
        $data = is_array($pengajuan->data_pengajuan)
            ? $pengajuan->data_pengajuan
            : (json_decode((string) $pengajuan->getRawOriginal('data_pengajuan') ?? '{}', true) ?: []);

        $email = $data['email_reset'] ?? $data['email'] ?? $pengajuan->user?->email ?? null;

        if ($email && filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return $email;
        }

        return null;
    }
}
