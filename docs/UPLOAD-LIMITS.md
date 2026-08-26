# Batas Ukuran File Upload

Batas upload aplikasi ini adalah **100 MB per file** untuk semua jenis file
(video rehabilitasi, video feedback dokter/terapis, foto kondisi pasien, dan
kartu BPJS berupa gambar atau PDF).

Batas 100 MB **tidak akan berfungsi hanya dengan mengubah kode Laravel**. Ada
tiga lapis yang semuanya harus dinaikkan — kalau salah satu masih rendah, upload
akan gagal sebelum Laravel sempat memproses request.

```
Browser  →  nginx  →  PHP-FPM  →  Laravel / Livewire
            (1)       (2)          (3)
```

| Lapis | Setting | Nilai wajib |
|---|---|---|
| (1) nginx | `client_max_body_size` | `150M` |
| (2) PHP | `post_max_size` | `150M` |
| (2) PHP | `upload_max_filesize` | `128M` |
| (3) Laravel | `UPLOAD_MAX_SIZE_KB` di `.env` | `102400` (= 100 MB) |

**Aturan yang tidak boleh dilanggar:**

```
client_max_body_size  >=  post_max_size  >  upload_max_filesize  >  UPLOAD_MAX_SIZE_KB
       150M                  150M            128M                     100M
```

Kenapa ada headroom? Satu request upload tidak hanya berisi file — ada boundary
multipart, field form lain, token CSRF, dan header. Jadi total body selalu
sedikit lebih besar dari ukuran file itu sendiri.

---

## 1. PHP-FPM

Salin file `docs/php/99-upload.ini` yang sudah disediakan di repo ini:

```bash
sudo cp docs/php/99-upload.ini /etc/php/8.4/fpm/conf.d/99-upload.ini
sudo cp docs/php/99-upload.ini /etc/php/8.4/cli/conf.d/99-upload.ini
sudo systemctl restart php8.4-fpm
```

> Sesuaikan `8.4` dengan versi PHP Anda (`php -v`).

Isinya:

```ini
upload_max_filesize = 128M
post_max_size       = 150M
memory_limit        = 512M
max_execution_time  = 600
max_input_time      = 600
max_file_uploads    = 50
```

Penjelasan tiap nilai:

| Setting | Default PHP | Kenapa dinaikkan |
|---|---|---|
| `upload_max_filesize` | `2M` | Batas satu file. Harus di atas 100 MB. |
| `post_max_size` | `8M` | Batas seluruh body. **Kalau terlampaui, PHP membuang seluruh `$_POST` termasuk token CSRF**, sehingga user melihat "419 Page Expired", bukan pesan ukuran file. |
| `memory_limit` | `128M` | Laravel membaca file saat memvalidasi `mimetypes`. |
| `max_execution_time` | `30` | Upload 100 MB di koneksi lambat mudah melebihi 30 detik. |
| `max_input_time` | `60` | Waktu PHP membaca/parse body request masuk. |
| `max_file_uploads` | `20` | Form foto kondisi pasien mengizinkan pilih banyak file sekaligus. |

**Kenapa perlu disalin ke `cli/` juga?** Queue worker (`php artisan queue:work`)
dan server dev (`composer run dev` / `php artisan serve`) memakai `php.ini` SAPI
**CLI**, bukan FPM. Kalau hanya FPM yang diatur, upload di lingkungan dev tetap
mentok di 2 MB dan bikin bingung saat testing.

Pastikan juga partisi `upload_tmp_dir` (biasanya `/tmp`) punya ruang kosong yang
cukup — setiap upload ditulis dulu ke sana sebelum dipindahkan ke `storage/`.

---

## 2. PHP-FPM pool

`/etc/php/8.4/fpm/pool.d/www.conf`:

```ini
request_terminate_timeout = 600
```

Kalau nilainya lebih kecil dari `max_execution_time`, FPM akan memutus proses di
tengah upload dan nginx membalas `502 Bad Gateway`.

---

## 3. nginx

Di dalam `server { ... }` pada konfigurasi site (biasanya
`/etc/nginx/sites-available/<nama-site>`):

```nginx
server {
    # ...

    # Harus >= post_max_size di php.ini.
    # Kalau ini kurang, nginx menolak dengan "413 Request Entity Too Large"
    # sebelum request sampai ke PHP.
    client_max_body_size 150M;

    # Beri waktu untuk koneksi lambat mengirim body 100MB.
    client_body_timeout 600s;
    send_timeout        600s;

    location ~ \.php$ {
        # ... konfigurasi fastcgi_pass yang sudah ada ...

        # Harus >= request_terminate_timeout di pool FPM.
        fastcgi_read_timeout 600s;
    }
}
```

Terapkan:

```bash
sudo nginx -t && sudo systemctl reload nginx
```

---

## 4. Laravel

Di `.env` server:

```dotenv
UPLOAD_MAX_SIZE_KB=102400
```

Satuannya **KB**. Nilai ini adalah satu-satunya tempat untuk mengubah batas
aplikasi — dipakai oleh `config/upload.php`, seluruh rule validasi (Livewire,
controller web, dan API mobile), rule temporary upload Livewire di
`config/livewire.php`, teks batas di semua halaman, dan pengecekan ukuran di
sisi browser.

Setelah mengubahnya:

```bash
php artisan config:clear
# di produksi, kalau memakai config cache:
php artisan config:cache
```

Kalau ingin menaikkan lagi ke, misalnya, 200 MB: ubah `UPLOAD_MAX_SIZE_KB` ke
`204800`, lalu naikkan juga `upload_max_filesize` (mis. `256M`), `post_max_size`
(mis. `300M`), dan `client_max_body_size` (mis. `300M`) mengikuti aturan urutan
di atas.

---

## 5. Cloudflare / proxy / load balancer

Kalau domain berada di belakang **Cloudflare**, ada batas ukuran body sendiri
yang **tidak bisa dinaikkan lewat server**:

| Paket Cloudflare | Batas body |
|---|---|
| Free | 100 MB |
| Pro | 100 MB |
| Business | 200 MB |
| Enterprise | 500 MB (bisa dinaikkan) |

Di paket Free/Pro, file tepat 100 MB kemungkinan besar ditolak karena overhead
multipart membuat total body melebihi 100 MB. Pilihannya:

- Turunkan `UPLOAD_MAX_SIZE_KB` ke mis. `92160` (90 MB), **atau**
- Buat DNS record untuk endpoint upload dalam mode **DNS only** (awan abu-abu,
  bypass proxy), **atau**
- Naikkan paket Cloudflare.

Hal yang sama berlaku untuk load balancer lain (AWS ALB, Nginx Proxy Manager,
Traefik) — cek batas body masing-masing.

---

## 6. Verifikasi

**Cek nilai PHP yang benar-benar aktif:**

```bash
# SAPI CLI
php -i | grep -E 'upload_max_filesize|post_max_size|memory_limit|max_file_uploads|max_execution_time'

# SAPI FPM (yang dipakai web) — buat file sementara di public/
echo '<?php phpinfo();' | sudo tee /var/www/<app>/public/_phpinfo.php
# buka https://<domain>/_phpinfo.php lalu HAPUS lagi:
sudo rm /var/www/<app>/public/_phpinfo.php
```

**Cek nilai Laravel:**

```bash
php artisan tinker --execute="dump(config('upload'));"
```

**Uji upload nyata:**

```bash
# Buat file dummy 90MB
head -c 90M /dev/urandom > /tmp/dummy.mp4

curl -i -X POST \
  -H "Authorization: Bearer <token-sanctum>" \
  -F "video=@/tmp/dummy.mp4" \
  https://<domain>/api/patient/rehabilitations/1/exercise/upload
```

Yang diharapkan:

| Ukuran file | Hasil |
|---|---|
| 90 MB | `200` — upload sukses |
| 110 MB | `422` — pesan validasi "Ukuran video maksimal 100MB." |
| 300 MB | `413` JSON, **bukan** 419/502/halaman error mentah |

---

## 7. Kalau upload masih gagal

| Gejala | Penyebab paling mungkin |
|---|---|
| `413 Request Entity Too Large` (halaman nginx) | `client_max_body_size` masih kecil |
| `419 Page Expired` | `post_max_size` terlampaui — PHP membuang token CSRF |
| `502 Bad Gateway` di tengah upload | `fastcgi_read_timeout` / `request_terminate_timeout` terlalu pendek |
| `500` saat validasi | `memory_limit` kurang |
| Progress bar mentok lalu berhenti diam | `upload_max_filesize` masih 2M |
| Batas di UI masih tertulis angka lama | `php artisan config:clear` belum dijalankan |
| Upload dev jalan, produksi gagal | `php.ini` diatur di `cli/` saja, belum di `fpm/` |

Log yang perlu dilihat:

```bash
tail -f storage/logs/laravel.log
sudo tail -f /var/log/nginx/error.log
sudo tail -f /var/log/php8.4-fpm.log
```

---

## 8. Catatan kapasitas disk

Semua file upload disimpan di disk `public` (`storage/app/public`) di server yang
sama. Dengan naiknya batas dari 2 MB ke 100 MB, konsumsi disk bisa meningkat
drastis. Belum ada mekanisme retensi/pembersihan file lama di aplikasi ini —
pantau kapasitas disk secara berkala:

```bash
df -h
du -sh storage/app/public/*
```

Livewire membersihkan file temporary di `livewire-tmp` yang lebih tua dari 24 jam
secara otomatis (`'cleanup' => true` di `config/livewire.php`).
