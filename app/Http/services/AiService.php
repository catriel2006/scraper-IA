<?php

namespace App\Http\Services;

use Illuminate\Support\Facades\Http;

class AiService
{
    public static function summarize($rawText)
    {
        $prompt = "
            Voici des données publiques trouvées sur une personne :
            ---------------------
            $rawText
            ---------------------
            Résume-les en JSON contenant :
            - poste_actuel
            - parcours_professionnel
            - competences
            - publications
            - contacts_publics
        ";


        $apiKey = env("GEMINI_API_KEY");

        // Vérif clé
        if (!$apiKey) {
            return ["error" => "Clé GEMINI_API_KEY manquante dans .env"];
        }

        // GEMINI = URL avec ?key= (PAS OpenAI !)
        $response = Http::post("https://generativelanguage.googleapis.com/v1beta/models/gemini-1.5-flash-latest:generateContent?key={AIzaSyB6fK6o1t9KSKUQqO2ks3e4Ss1V6LpUhtY}", [
            "contents" => [
                [
                    "parts" => [
                        ["text" => $prompt]
                    ]
                ]
            ]
        ]);

        if (!$response->ok()) {
            return ["error" => "Gemini API erreur: " . $response->status()];
        }

        $data = $response->json();
        $text = $data['candidates'][0]['content']['parts'][0]['text'] ?? 'Erreur parsing';

        return json_decode($text, true) ?: ["raw_summary" => $text];
    }
}
