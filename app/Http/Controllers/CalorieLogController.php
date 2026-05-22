<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Models\CalorieLog;
use Illuminate\Support\Facades\Auth;

class CalorieLogController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'meal_description' => 'required|string|max:255'
        ]);

        $description = $request->input('meal_description');
        
        $apiKey = env('NVIDIA_API_KEY');
        if (!$apiKey) {
            return response()->json(['error' => 'NVIDIA API Key not configured.'], 500);
        }

        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $apiKey,
                'Content-Type' => 'application/json',
            ])->post('https://integrate.api.nvidia.com/v1/chat/completions', [
                'model' => 'meta/llama-3.1-70b-instruct',
                'messages' => [
                    [
                        'role' => 'system',
                        'content' => 'You are an expert nutritionist AI. Estimate the calories, protein, carbs, and fats for the described meal. You MUST return ONLY a raw JSON object with the following keys and integer values: "calories", "protein", "carbs", "fats". Do not include markdown formatting or any other text.'
                    ],
                    [
                        'role' => 'user',
                        'content' => $description
                    ]
                ],
                'temperature' => 0.2,
                'max_tokens' => 1024,
            ]);

            if ($response->failed()) {
                return response()->json(['error' => 'Failed to connect to NVIDIA API.'], 500);
            }

            $data = $response->json();
            $content = $data['choices'][0]['message']['content'] ?? '';
            
            // Clean up content just in case the LLM returned markdown (e.g. ```json ... ```)
            $content = str_replace(['```json', '```'], '', $content);
            $parsed = json_decode(trim($content), true);

            if (!$parsed || !isset($parsed['calories'])) {
                return response()->json(['error' => 'Could not parse nutritional information from AI response.'], 500);
            }

            $log = CalorieLog::create([
                'user_id' => Auth::id(),
                'meal_description' => $description,
                'calories' => (int) $parsed['calories'],
                'protein' => (int) ($parsed['protein'] ?? 0),
                'carbs' => (int) ($parsed['carbs'] ?? 0),
                'fats' => (int) ($parsed['fats'] ?? 0),
                'logged_at' => now()->toDateString(),
            ]);

            return response()->json([
                'success' => true,
                'data' => $log,
                'message' => 'Meal logged successfully!'
            ]);

        } catch (\Exception $e) {
            return response()->json(['error' => 'An error occurred: ' . $e->getMessage()], 500);
        }
    }
}
