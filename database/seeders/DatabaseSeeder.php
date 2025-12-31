<?php

namespace Database\Seeders;

use App\Models\Rehab;
use App\Models\RehabType;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Testing\Fluent\Concerns\Has;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {

        // Admin User
        User::factory()->create([
            'name' => 'Admin User',
            'email' => 'admin@gmail.com',
            'role' => 'admin',
            'telephone' => '081234567890',
            'password' => Hash::make('password'),
        ]);

        // Doctor User
        User::factory()->create([
            'name' => 'Doctor User',
            'email' => 'doctor@me',
            'role' => 'doctor',
            'telephone' => '089876543210',
            'password' => Hash::make('password'),
        ]);

        // Patient User
        $patientUser = User::factory()->create([
            'name' => 'Patient User',
            'email' => 'patient@me',
            'role' => 'patient',
            'telephone' => '087654321098',
            'password' => Hash::make('password'),
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
        RehabType::insert(
            [
                ['name' => 'Isometric Exercise'],
                ['name' => 'Range of Motion Exercise'],
                ['name' => 'Strengthening Exercise'],
                ['name' => 'Balance and Coordination Training'],
                ['name' => 'Gait Training'],
                ['name' => 'Functional Training'],
            ]
        );
        Rehab::insert(
            [
                [
                    'rehabilitation_type_id' => 1,
                    'name' => 'Isometric Exercise for Upper Limb',
                    'description' => 'An exercise to strengthen upper limb muscles without joint movement.',
                ],
                [
                    'rehabilitation_type_id' => 2,
                    'name' => 'Range of Motion Exercise for Knee',
                    'description' => 'An exercise to improve knee joint flexibility and movement range.',
                ],
                [
                    'rehabilitation_type_id' => 3,
                    'name' => 'Strengthening Exercise for Core Muscles',
                    'description' => 'An exercise to enhance core muscle strength and stability.',
                ],
            ]
        );
    }
}
