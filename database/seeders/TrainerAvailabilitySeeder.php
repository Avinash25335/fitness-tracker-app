<?php

namespace Database\Seeders;

use App\Models\Trainer;
use App\Models\TrainerAvailability;
use Illuminate\Database\Seeder;

class TrainerAvailabilitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $trainers = Trainer::all();
        $days = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'];
        // Full elite schedule restored
        $slots = ["09:00 AM", "11:00 AM", "02:00 PM", "05:00 PM", "07:00 PM"];

        foreach ($trainers as $trainer) {
            foreach ($days as $day) {
                foreach ($slots as $slot) {
                    TrainerAvailability::firstOrCreate([
                        'trainer_id' => $trainer->id,
                        'day' => $day,
                        'slot' => $slot,
                    ]);
                }
            }
        }
    }
}
