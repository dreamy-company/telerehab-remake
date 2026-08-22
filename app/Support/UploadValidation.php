<?php

namespace App\Support;

/**
 * Pesan error upload dalam Bahasa Indonesia.
 *
 * Laravel hanya menyediakan pesan bawaan berbahasa Inggris yang menyebut ukuran
 * dalam kilobyte ("must not be greater than 102400 kilobytes"), yang tidak
 * berguna buat user. Membuat lang/en/validation.php sendiri bukan pilihan karena
 * file itu MENGGANTI seluruh pesan bawaan, bukan menambah. Jadi pesan dibangun
 * per pemanggilan validate() lewat helper ini supaya tetap satu sumber.
 */
class UploadValidation
{
    /**
     * @param  array<string, string>  $fields  Pasangan nama field => label untuk user.
     *                                         Contoh: ['bpjs_card' => 'kartu BPJS']
     * @return array<string, string>
     */
    public static function messages(array $fields): array
    {
        $maxMb = config('upload.max_size_mb');
        $messages = [];

        foreach ($fields as $field => $label) {
            $messages["{$field}.max"] = "Ukuran {$label} maksimal {$maxMb}MB.";
            $messages["{$field}.file"] = ucfirst($label).' harus berupa file yang valid.';
            $messages["{$field}.mimes"] = "Format {$label} tidak didukung (hanya :values).";
            $messages["{$field}.mimetypes"] = "Format {$label} tidak didukung.";
        }

        return $messages;
    }
}
