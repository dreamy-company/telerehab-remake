<?php

// Batas ukuran satu file upload dipusatkan di sini supaya tidak lagi tersebar
// sebagai angka literal di rule validasi Livewire, controller web, dan API.
$maxSizeKb = (int) env('UPLOAD_MAX_SIZE_KB', 102400); // 102400 KB = 100MB

$imageMimes = 'jpg,jpeg,png';
$documentMimes = 'jpg,jpeg,png,pdf';
$videoMimes = 'mp4,mov,avi,webm,mkv,3gp';

// Ubah "jpg,jpeg,png" menjadi ".jpg,.jpeg,.png" untuk atribut accept.
$toAccept = fn (string $mimes) => '.'.str_replace(',', ',.', $mimes);

return [

    /*
    |---------------------------------------------------------------------------
    | Batas Ukuran File
    |---------------------------------------------------------------------------
    |
    | 'max_size_kb' dipakai langsung pada rule "max:" Laravel (satuannya KB).
    | 'max_size_mb' untuk teks yang ditampilkan ke user di Blade.
    | 'max_size_bytes' dikirim ke browser lewat <meta name="upload-max-bytes">.
    |
    | PENTING: nilai ini harus <= upload_max_filesize di php.ini, dan
    | post_max_size harus lebih besar lagi. Lihat docs/UPLOAD-LIMITS.md.
    |
    */

    'max_size_kb' => $maxSizeKb,

    'max_size_mb' => intdiv($maxSizeKb, 1024),

    'max_size_bytes' => $maxSizeKb * 1024,

    /*
    |---------------------------------------------------------------------------
    | Format Yang Diizinkan
    |---------------------------------------------------------------------------
    */

    'image_mimes' => $imageMimes,

    'document_mimes' => $documentMimes,

    'video_mimes' => $videoMimes,

    // Versi mimetypes (dicek dari isi file, bukan ekstensi) untuk upload video.
    'video_mimetypes' => 'video/mp4,video/quicktime,video/x-msvideo,video/webm,video/x-matroska,video/3gpp',

    /*
    |---------------------------------------------------------------------------
    | Atribut accept Untuk Input File
    |---------------------------------------------------------------------------
    |
    | Diturunkan dari daftar mimes di atas supaya filter dialog browser selalu
    | sama dengan yang diterima server. Sebelumnya input kartu BPJS memakai
    | accept="image/*" padahal server juga menerima PDF, sehingga PDF tidak
    | pernah bisa dipilih user.
    |
    */

    'image_accept' => $toAccept($imageMimes),

    'document_accept' => $toAccept($documentMimes),

    'video_accept' => 'video/*',

];
