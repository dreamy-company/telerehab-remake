<?php

namespace Database\Seeders;

use App\Models\MovementExercise;
use Illuminate\Database\Seeder;

class MovementExerciseSeeder extends Seeder
{
    public function run(): void
    {
        $exercises = [
            [
                'name' => 'Shoulder Raise (Right)',
                'target_joint' => 'right_shoulder',
                'thresholds' => ['min_angle' => 20, 'max_angle' => 160, 'target_reps' => 10],
                'description' => 'Raise the right arm laterally from the body side up to shoulder height and above. Tracks the angle at the right shoulder joint.',
            ],
            [
                'name' => 'Shoulder Raise (Left)',
                'target_joint' => 'left_shoulder',
                'thresholds' => ['min_angle' => 20, 'max_angle' => 160, 'target_reps' => 10],
                'description' => 'Raise the left arm laterally from the body side up to shoulder height and above. Tracks the angle at the left shoulder joint.',
            ],
            [
                'name' => 'Elbow Flexion (Right)',
                'target_joint' => 'right_elbow',
                'thresholds' => ['min_angle' => 30, 'max_angle' => 160, 'target_reps' => 12],
                'description' => 'Bend the right elbow from full extension toward full flexion. Tracks the angle at the right elbow joint.',
            ],
            [
                'name' => 'Knee Extension (Right)',
                'target_joint' => 'right_knee',
                'thresholds' => ['min_angle' => 30, 'max_angle' => 170, 'target_reps' => 10],
                'description' => 'Extend the right knee from a bent position toward full extension. Useful for prosthetic and post-amputation rehabilitation.',
            ],
            [
                'name' => 'Hip Flexion (Right)',
                'target_joint' => 'right_hip',
                'thresholds' => ['min_angle' => 10, 'max_angle' => 90, 'target_reps' => 8],
                'description' => 'Lift the right knee toward the chest from a standing or seated position. Tracks the angle at the right hip joint.',
            ],
        ];

        foreach ($exercises as $exercise) {
            MovementExercise::create($exercise);
        }
    }
}
