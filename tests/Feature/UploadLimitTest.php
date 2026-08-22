<?php

namespace Tests\Feature;

use App\Livewire\Auth\Register;
use App\Livewire\Patient\RehabilitationExercise;
use App\Models\Patient;
use App\Models\PatientPhoto;
use App\Models\Rehab;
use App\Models\RehabRoutine;
use App\Models\RehabType;
use App\Models\RoutineResult;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Laravel\Sanctum\Sanctum;
use Livewire\Livewire;
use Tests\TestCase;

/**
 * Menjaga agar batas upload 100MB benar-benar berlaku di semua jalur (API mobile
 * dan Livewire) dan tidak diam-diam turun lagi ke 2MB seperti sebelumnya.
 */
class UploadLimitTest extends TestCase
{
    use RefreshDatabase;

    private function maxKb(): int
    {
        return config('upload.max_size_kb');
    }

    /** Sedikit di bawah batas: harus diterima. */
    private function videoDiBawahBatas(): UploadedFile
    {
        return UploadedFile::fake()->create('latihan.mp4', $this->maxKb() - 1024, 'video/mp4');
    }

    /** Sedikit di atas batas: harus ditolak. */
    private function videoDiAtasBatas(): UploadedFile
    {
        return UploadedFile::fake()->create('latihan.mp4', $this->maxKb() + 1024, 'video/mp4');
    }

    private function pasien(): User
    {
        // country & telephone NOT NULL di migrasi users, tapi tidak diisi UserFactory.
        $user = User::factory()->create([
            'role' => 'patient',
            'country' => 'Indonesia',
            'telephone' => '0812'.fake()->unique()->numerify('########'),
        ]);

        Patient::create([
            'user_id' => $user->id,
            'medical_record_number' => 'MR-'.$user->id,
            'bpjs_number' => 'BPJS-'.$user->id,
            'address' => 'Jl. Contoh No. 1, Surabaya',
        ]);

        return $user->refresh();
    }

    private function rutinAktifUntuk(User $user): RehabRoutine
    {
        $dokter = User::factory()->create([
            'role' => 'doctor',
            'country' => 'Indonesia',
            'telephone' => '0813'.fake()->unique()->numerify('########'),
        ]);

        $rehab = Rehab::create([
            'rehabilitation_type_id' => RehabType::create(['name' => 'Fase 1'])->id,
            'name' => 'Latihan Lutut',
            'description' => 'Deskripsi latihan.',
        ]);

        return RehabRoutine::create([
            'doctor_id' => $dokter->id,
            'patient_id' => $user->patient->id,
            'status' => 'process',
            'rehabilitation_id' => $rehab->id,
            'goal' => 'Meningkatkan mobilitas',
            'target' => now()->addWeek()->toDateString(),
        ]);
    }

    public function test_batas_upload_bersumber_dari_satu_config(): void
    {
        // Livewire memvalidasi temporary upload SEBELUM rule aplikasi berjalan.
        // Kalau keduanya berbeda, file ditolak sebelum pesan error yang benar
        // sempat muncul.
        $this->assertContains(
            'max:'.$this->maxKb(),
            config('livewire.temporary_file_upload.rules')
        );

        $this->assertSame(102400, $this->maxKb());
        $this->assertSame(100, config('upload.max_size_mb'));
        $this->assertSame(102400 * 1024, config('upload.max_size_bytes'));

        // PDF wajib ada di preview_mimes, kalau tidak temporaryUrl() melempar
        // exception saat user memilih kartu BPJS berformat PDF.
        $this->assertContains('pdf', config('livewire.temporary_file_upload.preview_mimes'));
    }

    public function test_api_menerima_video_tepat_di_bawah_batas(): void
    {
        Storage::fake('public');

        $user = $this->pasien();
        $rutin = $this->rutinAktifUntuk($user);
        Sanctum::actingAs($user);

        $this->postJson("/api/patient/rehabilitations/{$rutin->id}/exercise/upload", [
            'video' => $this->videoDiBawahBatas(),
        ])->assertStatus(201);

        $this->assertCount(1, Storage::disk('public')->files('exercise_videos'));
        $this->assertDatabaseCount(RoutineResult::class, 1);
    }

    public function test_api_menolak_video_di_atas_batas(): void
    {
        Storage::fake('public');

        $user = $this->pasien();
        $rutin = $this->rutinAktifUntuk($user);
        Sanctum::actingAs($user);

        $response = $this->postJson("/api/patient/rehabilitations/{$rutin->id}/exercise/upload", [
            'video' => $this->videoDiAtasBatas(),
        ]);

        $response->assertStatus(422)->assertJsonValidationErrors('video');
        $this->assertSame(
            'Ukuran video maksimal 100MB.',
            $response->json('errors.video.0')
        );
        $this->assertEmpty(Storage::disk('public')->files('exercise_videos'));
    }

    public function test_pasien_bisa_upload_video_besar_lewat_livewire(): void
    {
        Storage::fake('public');

        $user = $this->pasien();
        $rutin = $this->rutinAktifUntuk($user);

        Livewire::actingAs($user)
            ->test(RehabilitationExercise::class, ['id' => $rutin->id])
            ->set('video', $this->videoDiBawahBatas())
            ->call('uploadVideo')
            ->assertHasNoErrors('video');

        $this->assertCount(1, Storage::disk('public')->files('rehab_videos'));
        $this->assertDatabaseCount(RoutineResult::class, 1);
    }

    /**
     * Livewire menolak file kebesaran di endpoint temporary upload, SEBELUM
     * properti komponen terisi dan sebelum rule aplikasi sempat berjalan. Jadi
     * pesan custom komponen tidak muncul di jalur ini — di browser kegagalan
     * ini memicu event 'livewire-upload-error' yang ditangkap penjaga upload di
     * resources/js/app.js dan ditampilkan sebagai toast berbahasa Indonesia.
     */
    public function test_livewire_memblokir_video_di_atas_batas_sebelum_tersimpan(): void
    {
        Storage::fake('public');

        $user = $this->pasien();
        $rutin = $this->rutinAktifUntuk($user);

        $component = Livewire::actingAs($user)
            ->test(RehabilitationExercise::class, ['id' => $rutin->id])
            ->set('video', $this->videoDiAtasBatas());

        $component->assertHasErrors('video');
        $this->assertNull($component->get('video'));
        $this->assertEmpty(Storage::disk('public')->files('rehab_videos'));
    }

    /**
     * File yang lolos gerbang ukuran tapi salah format tetap divalidasi oleh
     * komponen, jadi di sinilah pesan Bahasa Indonesia benar-benar terlihat.
     */
    public function test_format_video_salah_ditolak_dengan_pesan_indonesia(): void
    {
        $user = $this->pasien();
        $rutin = $this->rutinAktifUntuk($user);

        $component = Livewire::actingAs($user)
            ->test(RehabilitationExercise::class, ['id' => $rutin->id])
            ->set('video', UploadedFile::fake()->create('dokumen.pdf', 1024, 'application/pdf'));

        $component->assertHasErrors('video');
        $this->assertContains(
            'Format video tidak didukung (gunakan mp4, mov, avi, webm, mkv, atau 3gp).',
            $component->errors()->get('video')
        );
    }

    public function test_registrasi_menerima_dokumen_jauh_di_atas_batas_lama_2mb(): void
    {
        Storage::fake('public');

        Livewire::test(Register::class)
            ->set('name', 'Budi Santoso')
            ->set('email', 'budi@example.com')
            ->set('password', 'rahasia123')
            ->set('country', 'Indonesia')
            ->set('telephone', '081234567890')
            ->set('address', 'Jl. Kenangan No. 10, Surabaya')
            ->set('medical_record_number', 'MR-001')
            ->set('bpjs_number', 'BPJS-001')
            ->set('prosthetic', 'Kaki kiri')
            ->set('prosthetic_since', '2024-01-01')
            // 50MB: dulu ditolak karena batasnya 2MB.
            ->set('bpjs_card', UploadedFile::fake()->create('bpjs.pdf', 50 * 1024, 'application/pdf'))
            ->set('patient_condition', [
                UploadedFile::fake()->image('kondisi.jpg')->size(30 * 1024),
            ])
            ->call('register')
            ->assertHasNoErrors(['bpjs_card', 'patient_condition.*']);

        // Pastikan benar-benar tersimpan, bukan sekadar lolos validasi:
        // register() menelan Exception dan hanya dispatch alert.
        $this->assertDatabaseHas('users', ['email' => 'budi@example.com']);
        $this->assertDatabaseCount(PatientPhoto::class, 1);
        $this->assertCount(1, Storage::disk('public')->files('bpjs'));
        $this->assertCount(1, Storage::disk('public')->files('patient_photos'));
    }

    public function test_registrasi_memblokir_dokumen_di_atas_batas(): void
    {
        Storage::fake('public');

        $component = Livewire::test(Register::class)
            ->set('bpjs_card', UploadedFile::fake()->create('bpjs.pdf', $this->maxKb() + 1024, 'application/pdf'));

        $component->assertHasErrors('bpjs_card');
        $this->assertNull($component->get('bpjs_card'));
        $this->assertEmpty(Storage::disk('public')->files('bpjs'));
    }
}
