<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\UserProfile;
use App\Models\Exercise;
use App\Models\WorkoutPlan;
use App\Models\DietPlan;
use App\Models\BlogCategory;
use App\Models\BlogPost;
use App\Models\Trainer;
use App\Models\ProgressLog;
use App\Models\WorkoutLog;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // ── Admin User ──────────────────────────────────────────────────
        $admin = User::firstOrCreate(
            ['email' => 'admin@fitnesspro.com'],
            [
                'name'     => 'Admin User',
                'password' => Hash::make('password'),
                'role'     => 'admin',
                'age'      => 30,
                'gender'   => 'male',
            ]
        );
        UserProfile::firstOrCreate(
            ['user_id' => $admin->id],
            ['weight' => 75, 'height' => 175, 'bmi' => 24.49, 'goal' => 'maintenance']
        );

        // ── Demo User ───────────────────────────────────────────────────
        $demo = User::firstOrCreate(
            ['email' => 'demo@fitnesspro.com'],
            [
                'name'     => 'Alex Johnson',
                'password' => Hash::make('password'),
                'role'     => 'user',
                'age'      => 27,
                'gender'   => 'male',
            ]
        );
        UserProfile::firstOrCreate(
            ['user_id' => $demo->id],
            ['weight' => 88, 'height' => 178, 'bmi' => 27.77, 'goal' => 'weight_loss']
        );
        // Seed some progress logs for the demo chart
        $dates = collect(range(0, 11))->reverse()->values();
        $weights = [88, 87.5, 87.2, 86.8, 86.5, 86.1, 85.9, 85.5, 85.2, 84.9, 84.6, 84.3];
        foreach ($dates as $i => $daysAgo) {
            ProgressLog::firstOrCreate(
                ['user_id' => $demo->id, 'log_date' => now()->subDays($daysAgo)->format('Y-m-d')],
                ['weight' => $weights[$i]]
            );
        }

        // ── Exercises ───────────────────────────────────────────────────
        $exercises = [
            ['name' => 'Push-ups',         'body_part' => 'Chest',     'sets' => 3, 'reps' => 15],
            ['name' => 'Squats',            'body_part' => 'Legs',      'sets' => 4, 'reps' => 20],
            ['name' => 'Pull-ups',          'body_part' => 'Back',      'sets' => 3, 'reps' => 10],
            ['name' => 'Plank',             'body_part' => 'Core',      'sets' => 3, 'reps' => 60],
            ['name' => 'Dumbbell Curls',    'body_part' => 'Biceps',    'sets' => 3, 'reps' => 12],
            ['name' => 'Overhead Press',    'body_part' => 'Shoulders', 'sets' => 3, 'reps' => 10],
            ['name' => 'Deadlift',          'body_part' => 'Full Body', 'sets' => 4, 'reps' => 8],
            ['name' => 'Bench Press',       'body_part' => 'Chest',     'sets' => 4, 'reps' => 10],
            ['name' => 'Burpees',           'body_part' => 'Cardio',    'sets' => 3, 'reps' => 15],
            ['name' => 'Mountain Climbers', 'body_part' => 'Core',      'sets' => 3, 'reps' => 30],
        ];
        $exModels = [];
        foreach ($exercises as $ex) {
            $exModels[] = Exercise::firstOrCreate(['name' => $ex['name']], $ex);
        }

        // ── Workout Plans ───────────────────────────────────────────────
        $planData = [
            [
                'title'          => 'Beginner Full Body Blast',
                'description'    => 'A carefully structured 4-week programme for beginners. Focuses on mastering foundational movement patterns with bodyweight and light resistance exercises.',
                'level'          => 'beginner',
                'duration_weeks' => 4,
                'exercises'      => [0, 1, 3],
            ],
            [
                'title'          => 'Intermediate Strength Builder',
                'description'    => 'An 8-week progressive overload programme for intermediate trainees looking to build serious strength and lean muscle mass.',
                'level'          => 'intermediate',
                'duration_weeks' => 8,
                'exercises'      => [2, 4, 5, 7],
            ],
            [
                'title'          => 'Advanced Power Athlete',
                'description'    => 'A 12-week elite training system combining compound lifts, plyometrics, and metabolic conditioning for experienced athletes.',
                'level'          => 'advanced',
                'duration_weeks' => 12,
                'exercises'      => [6, 7, 5, 8, 9],
            ],
            [
                'title'          => 'Fat Burner HIIT Circuit',
                'description'    => 'High-intensity interval training designed to maximise calorie burn in minimal time. Perfect for weight-loss goals.',
                'level'          => 'beginner',
                'duration_weeks' => 6,
                'exercises'      => [0, 1, 8, 9, 3],
            ],
        ];
        foreach ($planData as $plan) {
            $exIds   = $plan['exercises'];
            unset($plan['exercises']);
            $wp      = WorkoutPlan::firstOrCreate(['title' => $plan['title']], $plan);
            $attach  = [];
            foreach ($exIds as $order => $idx) {
                if (isset($exModels[$idx])) {
                    $attach[$exModels[$idx]->id] = ['order' => $order + 1];
                }
            }
            if (!empty($attach)) {
                $wp->exercises()->syncWithoutDetaching($attach);
            }
        }

        // ── BUG FIX: Workout logs seeded AFTER plans so FK constraint passes ──
        $firstPlan = WorkoutPlan::first();
        if ($firstPlan) {
            for ($i = 0; $i < 14; $i++) {
                if (in_array($i, [2, 7, 10])) continue; // simulate 3 missed days
                WorkoutLog::firstOrCreate(
                    [
                        'user_id'         => $demo->id,
                        'workout_plan_id' => $firstPlan->id,
                        'date'            => now()->subDays($i)->format('Y-m-d'),
                    ],
                    ['status' => 'completed']
                );
            }
        }

        // ── Diet Plans ──────────────────────────────────────────────────
        $dietPlans = [
            [
                'title'          => 'Calorie Deficit Fat Loss',
                'description'    => 'A structured, calorie-controlled plan designed for steady and sustainable fat loss without sacrificing muscle.',
                'daily_calories' => 1700,
                'goal'           => 'weight_loss',
                'meals_json'     => [
                    'Breakfast'        => 'Oatmeal with berries & 3 boiled eggs',
                    'Morning Snack'    => 'Greek yogurt & almonds',
                    'Lunch'            => 'Grilled chicken breast, brown rice & steamed broccoli',
                    'Afternoon Snack'  => 'Apple & whey protein shake',
                    'Dinner'           => 'Baked salmon, asparagus & quinoa',
                ],
            ],
            [
                'title'          => 'Lean Muscle Gain Plan',
                'description'    => 'A high-protein, calorie-surplus diet plan crafted to maximise muscle hypertrophy and lean body mass gains.',
                'daily_calories' => 3200,
                'goal'           => 'muscle_gain',
                'meals_json'     => [
                    'Breakfast'        => 'Scrambled eggs (5), whole milk oatmeal & banana',
                    'Morning Snack'    => 'Peanut butter sandwich & mass gainer shake',
                    'Lunch'            => 'Beef steak, white rice & mixed vegetables',
                    'Afternoon Snack'  => 'Cottage cheese, walnuts & orange',
                    'Dinner'           => 'Chicken thighs, sweet potato & avocado',
                ],
            ],
            [
                'title'          => 'Balanced Maintenance Diet',
                'description'    => 'A balanced, macro-nutrient optimised plan for athletes looking to maintain their current physique and energy levels.',
                'daily_calories' => 2400,
                'goal'           => 'maintenance',
                'meals_json'     => [
                    'Breakfast'        => 'Whole grain toast, eggs & fresh fruit',
                    'Morning Snack'    => 'Mixed nuts & an apple',
                    'Lunch'            => 'Turkey wrap with veggies & hummus',
                    'Afternoon Snack'  => 'Protein bar & green tea',
                    'Dinner'           => 'Grilled fish, roasted vegetables & brown rice',
                ],
            ],
        ];
        foreach ($dietPlans as $dp) {
            DietPlan::firstOrCreate(['title' => $dp['title']], $dp);
        }

        // ── Blog ────────────────────────────────────────────────────────
        $cats = [
            ['name' => 'Nutrition',  'slug' => 'nutrition'],
            ['name' => 'Training',   'slug' => 'training'],
            ['name' => 'Recovery',   'slug' => 'recovery'],
            ['name' => 'Mindset',    'slug' => 'mindset'],
        ];
        $catModels = [];
        foreach ($cats as $c) {
            $catModels[$c['slug']] = BlogCategory::firstOrCreate(['slug' => $c['slug']], $c);
        }

        $posts = [
            [
                'title'       => 'The Science of Protein: How Much Do You Really Need?',
                'slug'        => 'science-of-protein',
                'content'     => "Protein is the most important macronutrient for anyone looking to build muscle or lose fat. The research consistently shows that most active individuals need between 1.6 and 2.2 grams of protein per kilogram of bodyweight per day.\n\nWhy is protein so important?\n\nProtein provides the amino acids your body needs to repair and build muscle tissue after training. Without adequate protein, even the best workout programme in the world will fail to produce the results you're looking for.\n\nThe best protein sources:\n- Chicken breast (31g per 100g)\n- Lean beef (26g per 100g)\n- Eggs (13g per 100g)\n- Greek yogurt (10g per 100g)\n- Lentils (9g per 100g)\n\nTiming also matters. Consuming 20–40g of protein within 2 hours after training can significantly enhance muscle protein synthesis.",
                'category'    => 'nutrition',
                'image_url'   => 'https://images.unsplash.com/photo-1490645935967-10de6ba17061?q=80&w=800&auto=format&fit=crop',
                'published_at'=> now()->subDays(2),
            ],
            [
                'title'       => 'Progressive Overload: The Only Rule for Muscle Growth',
                'slug'        => 'progressive-overload',
                'content'     => "Progressive overload is the single most important principle in strength training. It simply means doing a little more over time — more weight, more reps, or more sets.\n\nWithout progressive overload, your muscles have no reason to grow. Your body adapts to stress quickly, and once it adapts, growth stops.\n\nHow to apply it:\n1. Add 2.5kg to the bar every week on compound lifts.\n2. Add one extra rep per set each session.\n3. Reduce rest periods to increase workout density.\n4. Add an extra set to your key exercises every 2 weeks.\n\nTrack every workout in a log. What gets measured gets improved.",
                'category'    => 'training',
                'image_url'   => 'https://images.unsplash.com/photo-1534367507873-d2d7e24c797f?q=80&w=800&auto=format&fit=crop',
                'published_at'=> now()->subDays(5),
            ],
            [
                'title'       => 'Why Sleep is Your Most Powerful Recovery Tool',
                'slug'        => 'sleep-recovery',
                'content'     => "Most athletes spend hours obsessing over workouts and nutrition but completely neglect the most powerful recovery tool available: sleep.\n\nDuring deep sleep, your body releases 70% of its daily growth hormone. This is when muscles are repaired, glycogen is restored, and the nervous system recovers from training stress.\n\nPractical sleep optimisation tips:\n- Set a consistent bedtime and wake time, even on weekends.\n- Keep your bedroom cold (18–20°C is optimal).\n- Avoid screens for 60 minutes before bed.\n- No caffeine after 2pm.\n- Aim for 7–9 hours of uninterrupted sleep.\n\nEven one night of poor sleep can reduce testosterone by 10–15% and impair strength by up to 20%.",
                'category'    => 'recovery',
                'image_url'   => 'https://images.unsplash.com/photo-1541781774459-bb2af2f05b55?q=80&w=800&auto=format&fit=crop',
                'published_at'=> now()->subDays(8),
            ],
        ];

        foreach ($posts as $p) {
            $catModel = $catModels[$p['category']] ?? null;
            BlogPost::firstOrCreate(['slug' => $p['slug']], [
                'user_id'          => $admin->id,
                'blog_category_id' => $catModel?->id,
                'title'            => $p['title'],
                'content'          => $p['content'],
                'image_url'        => $p['image_url'],
                'published_at'     => $p['published_at'],
            ]);
        }

        // ── Trainers ─────────────────────────────────────────────────────
        $trainersData = [
            [
                'name' => 'Marcus Reid',
                'email' => 'marcus@fitnesspro.com',
                'specialization' => 'Strength & Conditioning',
                'bio' => 'NSCA-certified strength coach with 10+ years helping athletes build serious muscle and power. Former competitive powerlifter.',
                'hourly_rate' => 60,
                'experience' => 10,
                'image' => 'https://images.unsplash.com/photo-1571019614242-c5c5dee9f50b?q=80&w=600&auto=format&fit=crop',
            ],
            [
                'name' => 'Sarah Jenkins',
                'email' => 'sarah@fitnesspro.com',
                'specialization' => 'Cardio & HIIT',
                'bio' => 'Passionate about helping you burn fat and build endurance. I specialise in high-intensity circuit training that gets results fast.',
                'hourly_rate' => 45,
                'experience' => 6,
                'image' => 'https://images.unsplash.com/photo-1548690312-e3b507d8c110?q=80&w=600&auto=format&fit=crop',
            ],
            [
                'name' => 'David Chen',
                'email' => 'david@fitnesspro.com',
                'specialization' => 'Yoga & Mobility',
                'bio' => 'Registered Yoga Teacher (RYT-500) focused on functional mobility, injury prevention, and mindful movement.',
                'hourly_rate' => 55,
                'experience' => 8,
                'image' => 'https://images.unsplash.com/photo-1594381898411-846e7d193883?q=80&w=600&auto=format&fit=crop',
            ],
        ];

        foreach ($trainersData as $td) {
            $tUser = User::firstOrCreate(
                ['email' => $td['email']],
                [
                    'name'     => $td['name'],
                    'password' => Hash::make('password'),
                    'role'     => 'trainer',
                    'age'      => 30,
                    'gender'   => 'unspecified',
                ]
            );
            Trainer::firstOrCreate(
                ['user_id' => $tUser->id],
                [
                    'specialization' => $td['specialization'],
                    'bio'            => $td['bio'],
                    'hourly_rate'    => $td['hourly_rate'],
                    'experience'     => $td['experience'],
                    'image'          => $td['image'],
                ]
            );
        }

        // Call additional modular seeders
        $this->call([
            TrainerAvailabilitySeeder::class,
            AchievementSeeder::class,
        ]);
    }
}
