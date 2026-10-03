<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class HolidayService
{
    private string $apiUrl =
        'https://p4c6e4mu4k5sg4fwnertd2zgwi0hordn.lambda-url.eu-north-1.on.aws/api/holidays';

    public function checkDate(string $date): ?array
    {
        try {
            $year = date('Y', strtotime($date));
            $cacheKey = "holidays_LK_{$year}";

            $holidays = Cache::remember($cacheKey, now()->addHours(24), function () use ($year) {
                $response = Http::timeout(5)->get($this->apiUrl, [
                    'countryCode' => 'LK',
                    'year' => $year,
                ]);

                if (!$response->successful()) {
                    return null;
                }

                $data = $response->json();

                return is_array($data) ? $data : null;
            });

            if (!is_array($holidays)) {
                return null;
            }

            foreach ($holidays as $holiday) {
                if (($holiday['date'] ?? null) === $date) {
                    return [
                        'is_holiday' => true,
                        'name' => $holiday['title']['en']
                            ?? $holiday['title']['original']
                            ?? 'Public Holiday',
                        'description' => $holiday['description']['en'] ?? null,
                    ];
                }
            }

            return [
                'is_holiday' => false,
                'name' => null,
                'description' => null,
            ];
        } catch (\Throwable $e) {
            Log::warning('Holiday API request failed', [
                'message' => $e->getMessage(),
            ]);

            return null;
        }
    }
}