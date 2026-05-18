<?php

namespace App\Services;

class DietService
{
    /**
     * Generate a complete, personalized nutrition plan with dynamic meal synthesis
     */
    public function generatePlan($user, $overrides = [])
    {
        // Get profile data with overrides
        $profile = $user->profile;
        
        $weight = $overrides['weight'] ?? ($profile->weight ?? 70);
        $height = $overrides['height'] ?? ($profile->height ?? 175);
        $age = $overrides['age'] ?? ($profile->age ?? 25);
        $gender = $overrides['gender'] ?? ($profile->gender ?? 'male');
        $goal = $overrides['goal'] ?? ($profile->goal ?? 'maintenance');
        $activityLevel = $overrides['activity_level'] ?? ($profile->activity_level ?? 'moderate');
        
        // 1. Calculate BMI
        $bmi = $weight / (($height / 100) ** 2);

        // 2. Calculate BMR (Mifflin-St Jeor)
        if ($gender == 'male') {
            $bmr = (10 * $weight) + (6.25 * $height) - (5 * $age) + 5;
        } else {
            $bmr = (10 * $weight) + (6.25 * $height) - (5 * $age) - 161;
        }
        
        // 3. Activity Multiplier
        $multipliers = [
            'sedentary' => 1.2,
            'light' => 1.375,
            'moderate' => 1.55,
            'active' => 1.725,
            'extra_active' => 1.9,
        ];
        $multiplier = $multipliers[$activityLevel] ?? 1.55;

        // 4. TDEE
        $tdee = $bmr * $multiplier;

        // 5. Goal Adjustment
        if ($goal == 'weight_loss' || $goal == 'cut') {
            $calories = $tdee - 500;
        } elseif ($goal == 'muscle_gain' || $goal == 'bulk') {
            $calories = $tdee + 500;
        } else {
            $calories = $tdee;
        }

        // 6. Macro Calculation (Science Based)
        // Protein: 2.2g per kg (for muscle maintenance/growth)
        $protein = $weight * 2.2; 
        // Fats: 25% of total calories
        $fat = ($calories * 0.25) / 9; 
        // Carbs: Remainder
        $carbs = ($calories - ($protein * 4 + $fat * 9)) / 4;

        $macros = [
            'protein' => round($protein),
            'fat' => round($fat),
            'carbs' => round($carbs),
        ];

        // 7. Meal Synthesis
        $meals = $this->buildMeals($goal, $macros);

        return [
            'bmi' => round($bmi, 1),
            'bmr' => round($bmr),
            'tdee' => round($tdee),
            'calories' => round($calories),
            'macros' => $macros,
            'meals' => $meals,
            'gender' => $gender,
            'activity_level' => $activityLevel,
            'age' => $age
        ];
    }

    /**
     * Build meal structure based on fitness goal with synthesized quantities
     */
    private function buildMeals($goal, $macros)
    {
        $p = $macros['protein'];
        $c = $macros['carbs'];

        // Quantities are approximations based on macronutrient density
        if ($goal == 'weight_loss' || $goal == 'cut') {
            return [
                'Breakfast' => [round($c * 0.3/0.12) . 'g Oats', round($p * 0.3/0.15) . 'g Egg whites', '1/2 Grapefruit'],
                'Lunch' => [round($c * 0.3/0.28) . 'g Brown rice', round($p * 0.35/0.25) . 'g Grilled Chicken', 'Unlimited Green Salad'],
                'Dinner' => [round($p * 0.35/0.20) . 'g Baked Fish', 'Steamed Broccoli & Asparagus'],
                'Snacks' => ['1 Apple', '15g Almonds']
            ];
        }

        if ($goal == 'muscle_gain' || $goal == 'bulk') {
            return [
                'Breakfast' => [round($c * 0.3/0.12) . 'g Oats + 1 Scoop Whey', '3 Whole Eggs', '1 Banana'],
                'Lunch' => [round($c * 0.4/0.28) . 'g White Rice', round($p * 0.35/0.25) . 'g Chicken Thighs', '1 Cup Greek Yogurt'],
                'Dinner' => ['2 Whole Wheat Chapatis', round($p * 0.35/0.20) . 'g Lean Beef / Paneer', 'Sauteed Vegetables in Olive Oil'],
                'Snacks' => ['Peanut Butter & Toast', 'Protein Shake']
            ];
        }

        // Default / Maintenance
        return [
            'Breakfast' => [round($c * 0.25/0.12) . 'g Oats', '2 Boiled Eggs', 'Coffee/Tea'],
            'Lunch' => [round($c * 0.4/0.28) . 'g Steamed Rice', 'Lentil Soup (Dal)', 'Fresh Salad'],
            'Dinner' => ['1 Chapati', round($p * 0.3/0.20) . 'g Grilled Fish or Tofu', 'Mixed Vegetables'],
            'Snacks' => ['Seasonal Fruit', '100g Cottage Cheese']
        ];
    }
}
