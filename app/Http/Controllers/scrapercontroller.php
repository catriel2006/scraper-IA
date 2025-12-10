<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Services\SearchService;
use App\Http\Services\FirecrawlService;
use App\Http\Services\AiService;

class ScraperController extends Controller
{
    public function scrape(Request $request)
    {
        $request->validate([
            'first_name' => 'required|string',
            'last_name'  => 'required|string',
            'company'    => 'required|string',
        ]);

        $query = trim("{$request->first_name} {$request->last_name} {$request->company}");
        $links = SearchService::search($query);

        $rawTexts = [];
        foreach (array_slice($links, 0, 3) as $url) {
            try {
                $text = FirecrawlService::scrape($url);
                if ($text && strlen($text) > 100) {
                    $rawTexts[] = $text;
                    if (count($rawTexts) >= 2) break;
                }
            } catch (\Exception $e) {
            }
        }

        if (!empty($rawTexts)) {
            $summary = AiService::summarize(implode("\n\n", $rawTexts));
        } else {
            $summary = [
                'status' => 'NO_DATA',
                'message' => 'Anti-bot détectés. Prêt pour SERP API/Apify avec clés fournies.',
                'links_tried' => $links
            ];
        }

        return response()->json([
    'query' => $query,
    'texts_found' => count($rawTexts),
    'links_tried' => $links,  // <- IMPORTANT
    'results' => $summary
], 200);

    }
}
