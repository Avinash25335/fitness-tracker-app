<?php

namespace App\Services;

class AICoachService
{
    /**
     * Generate coaching insights based on user data.
     * Returns an array of ['type' => 'success|warning|info', 'message' => '...']
     */
    public function analyze(array $data): array
    {
        $insights = [];

        $workoutsThisWeek    = $data['workouts_this_week'] ?? 0;
        $workoutsLastWeek    = $data['workouts_last_week'] ?? 0;
        $streak              = $data['streak'] ?? 0;
        $totalWorkouts       = $data['total_workouts'] ?? 0;
        $goal                = $data['goal'] ?? null;
        $weightTrend         = $data['weight_trend'] ?? 0;
        $avgCaloriesPerWeek  = $data['avg_calories_per_week'] ?? 0;
        $prsBroken           = $data['prs_broken_this_week'] ?? 0;
        $missedMuscleGroups  = $data['missed_muscle_groups'] ?? [];
        $daysSinceLastWorkout = (int)round($data['days_since_last_workout'] ?? 0);
        $totalWorkoutsThisWeek = (int)round($data['total_workout_duration_this_week'] ?? 0); // minutes

        // ══════════════════════════════════════════════════════════════════════
        // SECTION A: WORKOUT COACHING
        // ══════════════════════════════════════════════════════════════════════

        // Rule 1: PR Celebration
        if ($prsBroken > 0) {
            $insights[] = [
                'type'     => 'success',
                'category' => 'Strength',
                'message'  => "🏆 You broke {$prsBroken} personal record(s) this week! Keep applying progressive overload — add 2.5–5kg next session to keep the gains coming.",
            ];
        }

        // Rule 2: Workout Frequency
        if ($workoutsThisWeek === 0) {
            $insights[] = [
                'type'     => 'warning',
                'category' => 'Consistency',
                'message'  => '⚠️ No workouts logged this week yet. Even one 30-minute session resets your momentum — get back on track today!',
            ];
        } elseif ($workoutsThisWeek < 3) {
            $insights[] = [
                'type'     => 'warning',
                'category' => 'Consistency',
                'message'  => "📉 Only {$workoutsThisWeek} workout(s) this week. Aim for 4–5 sessions for optimal results. Try scheduling your next session right now.",
            ];
        } elseif ($workoutsThisWeek >= 5) {
            $insights[] = [
                'type'     => 'success',
                'category' => 'Consistency',
                'message'  => "🔥 Outstanding! {$workoutsThisWeek} sessions this week puts you in the top 10% of consistent athletes. World-class work ethic.",
            ];
        }

        // Rule 3: Missed Muscle Groups — THE KEY FEATURE
        if (!empty($missedMuscleGroups)) {
            $missed = implode(', ', $missedMuscleGroups);
            $count  = count($missedMuscleGroups);
            $insights[] = [
                'type'     => 'warning',
                'category' => 'Volume Balance',
                'message'  => "⚠️ You missed {$count} muscle group(s) this week: {$missed}. Increase volume for these groups next week to maintain balanced development and prevent imbalances.",
            ];
        }

        // Rule 4: Volume Drop vs Last Week
        if ($workoutsLastWeek > 0 && $workoutsThisWeek < $workoutsLastWeek) {
            $drop = $workoutsLastWeek - $workoutsThisWeek;
            if ($drop >= 2) {
                $insights[] = [
                    'type'     => 'warning',
                    'category' => 'Volume',
                    'message'  => "📉 Training volume dropped by {$drop} session(s) vs last week. If intentional (deload week), great. Otherwise, remove whatever blocked you before it becomes a habit.",
                ];
            }
        }

        // ══════════════════════════════════════════════════════════════════════
        // SECTION B: GOAL-SPECIFIC ANALYSIS
        // ══════════════════════════════════════════════════════════════════════

        if ($goal === 'muscle_gain') {
            if ($weightTrend <= 0) {
                $insights[] = [
                    'type'     => 'warning',
                    'category' => 'Goal Analysis',
                    'message'  => '💪 Weight not increasing despite training? For muscle gain you need a caloric surplus of +300–500 kcal/day. Hit 2g protein per kg of bodyweight and prioritise compound lifts.',
                ];
            } elseif ($weightTrend > 0.3) {
                $insights[] = [
                    'type'     => 'success',
                    'category' => 'Goal Analysis',
                    'message'  => "📈 Weight is trending upward ({$weightTrend}kg this period) — your surplus is working. Keep monitoring to ensure it's lean mass, not excess fat.",
                ];
            }
        }

        if ($goal === 'weight_loss') {
            if ($weightTrend > 0.5) {
                $insights[] = [
                    'type'     => 'info',
                    'category' => 'Goal Analysis',
                    'message'  => '📊 Weight trending upward. Audit your calorie intake — a 300–500 kcal deficit is optimal for fat loss without muscle sacrifice. Consider tracking meals for 3 days.',
                ];
            } elseif ($weightTrend < -0.1) {
                $insights[] = [
                    'type'     => 'success',
                    'category' => 'Goal Analysis',
                    'message'  => "✅ Weight is dropping consistently ({$weightTrend}kg this period). Your deficit is working — maintain current nutrition and training.",
                ];
            }
        }

        if ($goal === 'maintenance' && abs($weightTrend) > 1) {
            $insights[] = [
                'type'     => 'info',
                'category' => 'Goal Analysis',
                'message'  => "⚖️ Weight shifted by {$weightTrend}kg. For maintenance, tighten your macro tracking — you may be drifting into a surplus or deficit without realising.",
            ];
        }

        // ══════════════════════════════════════════════════════════════════════
        // SECTION C: AI DIET RECOMMENDATIONS
        // ══════════════════════════════════════════════════════════════════════

        if ($goal === 'muscle_gain') {
            $insights[] = [
                'type'     => 'info',
                'category' => 'Diet AI',
                'message'  => '🍗 Diet for Muscle Gain: Eat every 3–4 hours. Prioritise chicken, eggs, Greek yogurt, and whey. Target 2–2.5g protein/kg bodyweight. Add complex carbs (rice, oats) around workouts for fuel and recovery.',
            ];
        } elseif ($goal === 'weight_loss') {
            $insights[] = [
                'type'     => 'info',
                'category' => 'Diet AI',
                'message'  => '🥗 Diet for Fat Loss: Keep protein high (1.8g/kg) to preserve muscle. Focus on volume foods — leafy greens, lean protein, legumes. Avoid liquid calories. Intermittent fasting (16:8) can help if it fits your schedule.',
            ];
        } elseif ($goal === 'maintenance') {
            $insights[] = [
                'type'     => 'info',
                'category' => 'Diet AI',
                'message'  => '⚖️ Maintenance Nutrition: Eat at TDEE. Cycle carbs around training days (more carbs = training days, fewer = rest days). Prioritise whole foods and keep protein at 1.6g/kg to retain muscle mass.',
            ];
        } else {
            $insights[] = [
                'type'     => 'info',
                'category' => 'Diet AI',
                'message'  => '🥦 General Nutrition Tip: Drink 3L water daily. Eat 4–5 meals spaced evenly. Protein with every meal. Minimise ultra-processed foods. Set a fitness goal in Settings to unlock goal-specific diet advice.',
            ];
        }

        // ══════════════════════════════════════════════════════════════════════
        // SECTION D: RECOVERY SUGGESTIONS
        // ══════════════════════════════════════════════════════════════════════

        // Recovery based on workout frequency
        if ($workoutsThisWeek >= 6) {
            $insights[] = [
                'type'     => 'warning',
                'category' => 'Recovery',
                'message'  => '😴 6+ sessions this week — elite effort, but recovery is where growth happens. Schedule 1–2 rest days. Sleep 7–9 hours. Consider an ice bath or contrast shower to reduce muscle soreness.',
            ];
        } elseif ($workoutsThisWeek >= 4) {
            $insights[] = [
                'type'     => 'info',
                'category' => 'Recovery',
                'message'  => '🧘 Good volume this week. Prioritise 7–8 hours sleep and a 10-min post-workout stretch. Magnesium glycinate at night can significantly improve sleep quality and muscle recovery.',
            ];
        }

        // Recovery based on days since last workout
        if ($daysSinceLastWorkout >= 3 && $daysSinceLastWorkout < 7) {
            $insights[] = [
                'type'     => 'info',
                'category' => 'Recovery',
                'message'  => "🔋 {$daysSinceLastWorkout} days since your last session — your muscles are fully recovered and ready. This is optimal time to train at full intensity. Don't let the momentum die!",
            ];
        } elseif ($daysSinceLastWorkout >= 7) {
            $insights[] = [
                'type'     => 'warning',
                'category' => 'Recovery',
                'message'  => "⏰ {$daysSinceLastWorkout} days since your last workout. Muscle detraining begins after 10–14 days — start with 70% of your usual weight today to ease back safely.",
            ];
        }

        // General recovery tip always shown
        $insights[] = [
            'type'     => 'info',
            'category' => 'Recovery',
            'message'  => '💤 Recovery Protocol: Sleep 7–9h nightly (non-negotiable). Consume 20–40g protein within 30 min post-workout. Foam roll tight areas. Deload week every 6–8 weeks — reduce volume by 40–50% for full CNS recovery.',
        ];

        // ══════════════════════════════════════════════════════════════════════
        // SECTION E: STREAK & MOTIVATION
        // ══════════════════════════════════════════════════════════════════════

        if ($streak >= 30) {
            $insights[] = [
                'type'     => 'success',
                'category' => 'Streak',
                'message'  => "🏅 LEGEND — {$streak}-day streak! You've made fitness a lifestyle. You are in the top 1% of FitCore athletes.",
            ];
        } elseif ($streak >= 7) {
            $insights[] = [
                'type'     => 'success',
                'category' => 'Streak',
                'message'  => "⚡ {$streak}-day streak! One week of consistency is scientifically shown to rewire habit loops. You're building an elite training identity.",
            ];
        } elseif ($streak < 3 && $totalWorkouts > 3) {
            $insights[] = [
                'type'     => 'info',
                'category' => 'Streak',
                'message'  => "📅 {$streak}-day streak. Log every day — even a 10-min walk counts as activity. The habit of showing up daily is more powerful than any single workout.",
            ];
        }

        // Default fallback
        if (empty($insights)) {
            $insights[] = [
                'type'     => 'info',
                'category' => 'General',
                'message'  => '🚀 Start logging workouts and weight to unlock your personalised AI coaching, diet advice, and recovery plan here.',
            ];
        }

        return $insights;
    }
}
