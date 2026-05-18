<?php

namespace App\Services;

use App\Models\User;
use App\Models\WorkoutPlan;
use App\Models\DietPlan;

class RecommendationService
{
    /**
     * Get rule-based recommendations based on user profile.
     * Structured so OpenAI can be plugged in via getAIRecommendations() later.
     */
    public function getRecommendations(User $user): array
    {
        $profile = $user->profile;

        if (!$profile || (!$profile->bmi && !$profile->goal)) {
            return $this->getDefaultRecommendations();
        }

        $bmi  = (float) $profile->bmi;
        $goal = $profile->goal; // stored as: weight_loss | muscle_gain | maintenance

        return [
            'workout_plan' => $this->recommendWorkout($bmi, $goal),
            'diet_plan'    => $this->recommendDiet($bmi, $goal),
            'tips'         => $this->getTips($bmi, $goal),
        ];
    }

    protected function recommendWorkout(float $bmi, ?string $goal): ?WorkoutPlan
    {
        // Rule: weight_loss → cardio-friendly beginner plan
        if ($goal === 'weight_loss' || $bmi > 27) {
            return WorkoutPlan::where('level', 'beginner')->first()
                ?? WorkoutPlan::first();
        }

        // Rule: muscle_gain → intermediate/advanced strength plan
        if ($goal === 'muscle_gain') {
            return WorkoutPlan::where('level', 'intermediate')->first()
                ?? WorkoutPlan::where('level', 'advanced')->first()
                ?? WorkoutPlan::first();
        }

        // Maintenance or default → any plan
        return WorkoutPlan::first();
    }

    protected function recommendDiet(float $bmi, ?string $goal): ?DietPlan
    {
        // Try to find a diet that matches the stored goal value
        $diet = DietPlan::where('goal', $goal)->first();

        if (!$diet && $goal === 'weight_loss') {
            // Fallback: lowest calorie plan
            $diet = DietPlan::orderBy('daily_calories', 'asc')->first();
        }

        if (!$diet && $goal === 'muscle_gain') {
            // Fallback: highest calorie plan
            $diet = DietPlan::orderBy('daily_calories', 'desc')->first();
        }

        return $diet ?? DietPlan::first();
    }

    protected function getTips(float $bmi, ?string $goal): array
    {
        $tips = [
            'Drink at least 3 litres of water daily — hydration is performance.',
            'Aim for 7–8 hours of sleep each night for optimal recovery.',
            'Consistency beats intensity. Never miss two days in a row.',
        ];

        if ($bmi > 30) {
            array_unshift($tips, 'Your BMI suggests obesity range. Start with low-impact cardio (walking, cycling) and consult a doctor.');
        } elseif ($bmi > 25) {
            array_unshift($tips, 'Focus on HIIT and a calorie deficit of 300–500 kcal/day for steady fat loss.');
        } elseif ($bmi < 18.5) {
            array_unshift($tips, 'Your BMI is low. Prioritise a calorie surplus with high-protein foods to build mass.');
        }

        if ($goal === 'muscle_gain') {
            $tips[] = 'Eat 1.6–2.2g of protein per kg of bodyweight to maximise muscle synthesis.';
            $tips[] = 'Progressive overload is key — add weight or reps every 1–2 weeks.';
        }

        if ($goal === 'weight_loss') {
            $tips[] = 'Track your calories for at least 2 weeks to understand your baseline intake.';
        }

        return $tips;
    }

    protected function getDefaultRecommendations(): array
    {
        return [
            'workout_plan' => WorkoutPlan::first(),
            'diet_plan'    => DietPlan::first(),
            'tips'         => [
                'Complete your profile to unlock personalised recommendations!',
                'Start by logging your current weight in the Progress section.',
            ],
        ];
    }

    /**
     * Future hook: swap this method body for an OpenAI API call.
     * The dashboard calls getRecommendations(), so no other code changes needed.
     */
    public function getAIRecommendations(User $user): array
    {
        // Example future usage:
        // $response = \OpenAI::chat()->create([
        //     'model'    => 'gpt-4o',
        //     'messages' => [['role' => 'user', 'content' => "Give fitness tips for BMI {$user->profile->bmi} and goal {$user->profile->goal}"]],
        // ]);
        // return ['tips' => [$response->choices[0]->message->content]];

        return $this->getRecommendations($user);
    }
}
