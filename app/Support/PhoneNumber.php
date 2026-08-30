<?php

namespace App\Support;

class PhoneNumber
{
    /**
     * Normalisasi ke format lokal Indonesia: selalu berawalan 08.
     * +62/62 → 08, nomor 8xx tanpa awalan → 08xx, 08xx → tetap 08xx.
     */
    public static function normalize(?string $phone): string
    {
        $clean = preg_replace('/[^0-9]/', '', (string) $phone);

        if (str_starts_with($clean, '62') && strlen($clean) > 2) {
            return '0'.substr($clean, 2);
        }

        // Nomor yang diketik langsung tanpa 0 dan tanpa 62 (mis. 812xxxx) → tambahkan 0.
        if (str_starts_with($clean, '8')) {
            return '0'.$clean;
        }

        return $clean;
    }

    /**
     * Format tampilan untuk admin/pengguna di layar: selalu berawalan 08.
     */
    public static function local(?string $phone): string
    {
        return self::normalize($phone);
    }

    /**
     * Format untuk tautan API WhatsApp (https://wa.me/62...): selalu 62xxxxxxxxxx.
     */
    public static function wa(?string $phone): string
    {
        $local = self::normalize($phone);

        if (str_starts_with($local, '0')) {
            return '62'.substr($local, 1);
        }

        if (str_starts_with($local, '8')) {
            return '62'.$local;
        }

        return $local;
    }
}
