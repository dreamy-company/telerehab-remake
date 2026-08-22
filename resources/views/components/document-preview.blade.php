@props(['url', 'pdf' => false])

{{--
    Kartu BPJS boleh berupa gambar ATAU PDF (lihat config/upload.php).
    Merender PDF lewat <img> hanya menghasilkan ikon gambar rusak, jadi PDF
    ditampilkan sebagai tautan yang bisa dibuka di tab baru.
--}}
@if ($pdf)
    <a href="{{ $url }}" target="_blank"
        class="flex flex-col items-center justify-center h-full text-slate-500 hover:text-primary-600 transition-colors">
        <i class="far fa-file-pdf text-3xl mb-2"></i>
        <span class="text-xs font-semibold">Open PDF</span>
    </a>
@else
    <img src="{{ $url }}" {{ $attributes->merge(['class' => 'w-full h-full object-cover']) }}>
@endif
