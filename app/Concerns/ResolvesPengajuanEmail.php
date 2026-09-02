<?php

namespace App\Concerns;

use App\Models\Pengajuan;

trait ResolvesPengajuanEmail
{
    /**
     * Resolve email from pengajuan model ONLY (anti-IDOR).
     * Priority: bantuan?->email_reset → cloud?->email → tte?->email → user->email.
     * Returns null if no valid email found.
     */
    protected function resolveTargetEmail(Pengajuan $pengajuan): ?string
    {
        $pengajuan->loadMissing('bantuan', 'cloud', 'tte', 'user');

        $email = $pengajuan->bantuan?->email_reset
            ?? $pengajuan->cloud?->email
            ?? $pengajuan->tte?->email
            ?? $pengajuan->user?->email
            ?? null;

        if ($email && filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return $email;
        }

        return null;
    }
}
