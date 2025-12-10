<?php

namespace App\Http\Services;

use Illuminate\Support\Facades\Http;

class FirecrawlService
{
    public static function scrape($url)
    {
        $response = Http::withToken(env("FIRECRAWL_KEY"))
            ->post("https://api.firecrawl.dev/v1/scrape", [
                "url" => $url,
                "formats" => ["markdown"]
            ]);

        if (!$response->ok()) return null;

        return $response->json()["markdown"] ?? null;
    }
}
