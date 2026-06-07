<?php

namespace Database\Seeders;

use App\Models\Country;
use App\Models\Rehab;
use App\Models\RehabType;
use App\Models\User;
use Database\Seeders\MovementExerciseSeeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $indonesiaId = Country::where('name', 'Indonesia')->value('id');

        // Admin User
        User::factory()->create([
            'name' => 'Admin User',
            'email' => 'admin@gmail.com',
            'role' => 'admin',
            'country_id' => $indonesiaId,
            'telephone' => '081234567890',
            'password' => Hash::make('admintelerehab'),
        ]);

        // Doctor User
        User::factory()->create([
            'name' => 'Doctor User',
            'email' => 'doctor@gmail.com',
            'role' => 'doctor',
            'country_id' => $indonesiaId,
            'telephone' => '089876543210',
            'password' => Hash::make('doctortelerehab'),
        ]);

        // Patient User
        $patientUser = User::factory()->create([
            'name' => 'Patient User',
            'email' => 'patient@gmail.com',
            'role' => 'patient',
            'country_id' => $indonesiaId,
            'telephone' => '087654321098',
            'password' => Hash::make('patienttelerehab'),
        ]);

        User::factory()->create([
            'name' => 'Therapist User',
            'email' => 'therapist@gmail.com',
            'role' => 'therapist',
            'country_id' => $indonesiaId,
            'telephone' => '081122334455',
            'password' => Hash::make('therapisttelerehab'),
        ]);

        // patient data
        $patientData = $patientUser->patient()->create([
            'medical_record_number' => '12345678',
            'bpjs_number' => '0001234567890123',
            'bpjs_card' => 'card_image.jpg',
            'prosthetic' => 'Right leg prosthetic',
            'prosthetic_since' => '2023-01-15',
            'address' => '123 Main Street, Jakarta, Indonesia',
        ]);
        // patientphoto
        $patientData->photos()->createMany([
            ['url' => 'patient_photo1.jpg'],
            ['url' => 'patient_photo2.jpg'],
        ]);

        // Phase
        RehabType::insert([
            ['name' => 'Isometric'],
            ['name' => 'Proprioceptive'],
        ]);

        $rehabs = [
            ['type' => 1, 'name' => 'Knee Isometric Exercise', 'desc' => 'Exercise for thigh muscle strength with constant pressure'],
            ['type' => 1, 'name' => 'Hip Isometric', 'desc' => 'Strengthening the muscles around the hip after amputation'],
            ['type' => 2, 'name' => 'Leg Proprioceptive', 'desc' => 'Training balance and orientation with prosthetics'],
            ['type' => 2, 'name' => 'Bosu Ball Exercise', 'desc' => 'Training proprioception and posture control'],
            ['type' => 1, 'name' => 'Thigh Muscle Contraction', 'desc' => 'Exercise to hold thigh muscle contraction for 10 seconds'],
            ['type' => 2, 'name' => 'Walking with a Cane', 'desc' => 'Training weight distribution and balance'],
            ['type' => 1, 'name' => 'Sit to Stand Exercise', 'desc' => 'Improving stability during position transition'],
            ['type' => 2, 'name' => 'Side Stepping', 'desc' => 'Lateral mobility training for prosthetic patients'],
            ['type' => 1, 'name' => 'Calf Muscle Pressure', 'desc' => 'Isometric exercise for prosthetic calf strength'],
            ['type' => 2, 'name' => 'Standing with Eyes Closed', 'desc' => 'Training proprioceptive system with reduced visual input'],
        ];

        foreach ($rehabs as $r) {
            Rehab::create([
                'rehabilitation_type_id' => $r['type'],
                'name' => $r['name'],
                'description' => $r['desc'],
                'video_url' => "https://www.youtube.com/embed/2-Ferf2Nyq8?si=LznlquPVw2WTo7ZB",
                'isDeleted' => false,
                'deleted_by' => null,
            ]);
        }

        $this->call(MovementExerciseSeeder::class);
    }
}
