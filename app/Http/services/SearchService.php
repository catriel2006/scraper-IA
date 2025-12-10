<?php

namespace App\Http\Services;

class SearchService
{
    public static function search(string $query): array
    {
        $apiKey = env('SERPAPI_KEY');

        if (!$apiKey) {
            // Fallback: tes anciens liens "manuels"
            $parts = explode(' ', strtolower($query));
            $firstName = $parts[0] ?? '';
            $lastName  = $parts[1] ?? '';
            $company   = implode(' ', array_slice($parts, 2));

            return [
                "https://www.linkedin.com/in/" . urlencode($firstName . '-' . $lastName),
                "https://fr.linkedin.com/search/results/people/?keywords=" . urlencode($query),
                "https://www.google.com/search?q=" . urlencode($query),
                "https://{$company}.com",
                "https://www.{$company}.com",
            ];
        }

        // Appel SERPAPI (Google Search)
        $url = "https://serpapi.com/search.json?q=" . urlencode($query) . "&engine=google&api_key=" . $apiKey;

        try {
            $response = file_get_contents($url);
            if (!$response) {
                return [];
            }

            $data = json_decode($response, true);

            // On récupère les liens des résultats organiques
            $organic = $data['organic_results'] ?? [];
            $links = array_column($organic, 'link');

            // On garde max 5 liens
            return array_slice($links, 0, 5);

        } catch (\Exception $e) {
            // En cas d’erreur, on renvoie un tableau vide
            return [];
        }
    }
}
