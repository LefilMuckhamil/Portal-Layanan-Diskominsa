<?php

namespace App\Support;

class PhoneNumber
{
    public static function normalize(?string $phone): string
    {
        $clean = preg_replace('/[^0-9]/', '', (string) $phone);

        if (str_starts_with($clean, '0')) {
            return '62'.substr($clean, 1);
        }

        // Nomor yang diketik langsung tanpa 0 dan tanpa 62 (mis. 812xxxx) → tambahkan 62.
        if (str_starts_with($clean, '8')) {
            return '62'.$clean;
        }

        return $clean;
    }
}
