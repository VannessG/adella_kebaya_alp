<?php

namespace App\Services;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class RajaOngkirService
{
    private $apiKey;
    private $baseUrl;
    private $cacheLifetime = 86400; // 24 hours in seconds

    public function __construct()
    {
        $this->apiKey = config('services.rajaongkir.key');
        $this->baseUrl = rtrim(config('services.rajaongkir.base_url'), '/') . '/';
    }

    /**
     * Get cached data or fetch from API
     */
    private function getCachedOrFetch($cacheKey, $apiCall)
    {
        // Check cache
        $cached = DB::table('rajaongkir_cache')
            ->where('cache_key', $cacheKey)
            ->where('cached_at', '>', Carbon::now()->subSeconds($this->cacheLifetime))
            ->first();

        if ($cached) {
            Log::info('RajaOngkir Cache Hit', ['key' => $cacheKey]);
            return json_decode($cached->cache_value, true);
        }

        // Fetch from API
        Log::info('RajaOngkir Cache Miss - Fetching from API', ['key' => $cacheKey]);
        $data = $apiCall();

        // Store in cache
        if (!empty($data)) {
            DB::table('rajaongkir_cache')->updateOrInsert(
                ['cache_key' => $cacheKey],
                [
                    'cache_value' => json_encode($data),
                    'cached_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ]
            );
        }

        return $data;
    }

    /**
     * Get all provinces
     */
    public function getProvinces()
    {
        return $this->getCachedOrFetch('provinces', function () {
            try {
                $response = Http::timeout(30)
                    ->withHeaders(['key' => $this->apiKey])
                    ->get($this->baseUrl . 'destination/province');

                if ($response->successful()) {
                    $data = $response->json();
                    return $data['data'] ?? [];
                }

                Log::error('RajaOngkir API Error - Get Provinces', [
                    'status' => $response->status(),
                    'body' => $response->body()
                ]);

                return [];
            } catch (\Exception $e) {
                Log::error('RajaOngkir Exception - Get Provinces', [
                    'message' => $e->getMessage()
                ]);
                return [];
            }
        });
    }

    /**
     * Get cities by province ID
     */
    public function getCitiesByProvince($provinceId)
    {
        $cacheKey = "cities_province_{$provinceId}";
        
        return $this->getCachedOrFetch($cacheKey, function () use ($provinceId) {
            try {
                $response = Http::timeout(30)
                    ->withHeaders(['key' => $this->apiKey])
                    ->get($this->baseUrl . 'destination/city/' . $provinceId);

                if ($response->successful()) {
                    $data = $response->json();
                    return $data['data'] ?? [];
                }

                Log::error('RajaOngkir API Error - Get Cities', [
                    'province_id' => $provinceId,
                    'status' => $response->status(),
                    'body' => $response->body()
                ]);

                return [];
            } catch (\Exception $e) {
                Log::error('RajaOngkir Exception - Get Cities', [
                    'message' => $e->getMessage()
                ]);
                return [];
            }
        });
    }

    /**
     * Get districts by city ID
     */
    public function getDistrictsByCity($cityId)
    {
        $cacheKey = "districts_city_{$cityId}";
        
        return $this->getCachedOrFetch($cacheKey, function () use ($cityId) {
            try {
                $response = Http::timeout(30)
                    ->withHeaders(['key' => $this->apiKey])
                    ->get($this->baseUrl . 'destination/district/' . $cityId);

                if ($response->successful()) {
                    $data = $response->json();
                    // ✅ Clean: hanya return id dan name saja
                    return array_map(function($district) {
                        return [
                            'id' => $district['id'],
                            'name' => $district['name']
                        ];
                    }, $data['data'] ?? []);
                }

                Log::error('RajaOngkir API Error - Get Districts', [
                    'city_id' => $cityId,
                    'status' => $response->status(),
                    'body' => $response->body()
                ]);

                return [];
            } catch (\Exception $e) {
                Log::error('RajaOngkir Exception - Get Districts', [
                    'message' => $e->getMessage()
                ]);
                return [];
            }
        });
    }

    /**
     * Calculate shipping cost - NO CACHE (always fresh)
     */
    public function calculateShippingCost($originDistrictId, $destinationDistrictId, $weight, $couriers = null)
    {
        try {
            if (!$couriers) {
                $couriers = 'jne:sicepat:jnt:ninja:tiki:pos';
            }

            $weight = max((int)$weight, 1);

            Log::info('=== CALCULATE SHIPPING REQUEST ===', [
                'origin' => $originDistrictId,
                'destination' => $destinationDistrictId,
                'weight_from_request' => $weight,
                'weight_final' => $weight,
                'user_id' => Auth::user()?->id ?? null
            ]);

            Log::info('=== RAJAONGKIR REQUEST ===', [
                'origin_district' => $originDistrictId,
                'destination_district' => $destinationDistrictId,
                'weight_grams' => $weight,
                'couriers' => $couriers,
                'url' => $this->baseUrl . 'calculate/district/domestic-cost'
            ]);

            $response = Http::timeout(30)
                ->withHeaders([
                    'key' => $this->apiKey,
                    'Content-Type' => 'application/x-www-form-urlencoded'
                ])
                ->asForm()
                ->post($this->baseUrl . 'calculate/district/domestic-cost', [
                    'origin' => $originDistrictId,
                    'destination' => $destinationDistrictId,
                    'weight' => $weight,
                    'courier' => $couriers,
                    'price' => 'lowest'
                ]);

            Log::info('=== RAJAONGKIR RAW RESPONSE ===', [
                'status' => $response->status(),
                'body' => $response->body()
            ]);

            if ($response->successful()) {
                $data = $response->json();
                
                $formattedCosts = [];
                
                if (isset($data['data']) && is_array($data['data'])) {
                    foreach ($data['data'] as $service) {
                        $formattedCosts[] = [
                            'code' => strtolower($service['code']),
                            'name' => $service['name'],
                            'service' => $service['service'],
                            'description' => $service['description'] ?? '',
                            'cost' => (int)$service['cost'],
                            'etd' => $service['etd'] ?? null
                        ];
                    }
                }

                usort($formattedCosts, function($a, $b) {
                    return $a['cost'] - $b['cost'];
                });

                Log::info('=== RAJAONGKIR FORMATTED ===', [
                    'count' => count($formattedCosts),
                    'cheapest' => $formattedCosts[0] ?? null,
                ]);

                return $formattedCosts;
            }

            Log::error('RajaOngkir API Error - Calculate Cost', [
                'origin' => $originDistrictId,
                'destination' => $destinationDistrictId,
                'weight' => $weight,
                'status' => $response->status(),
                'body' => $response->body()
            ]);

            return [];
        } catch (\Exception $e) {
            Log::error('RajaOngkir Exception - Calculate Cost', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return [];
        }
    }

    /**
     * Get origin district ID for Magelang
     */
    public function getMagelangDistrictId()
    {
        return config('services.rajaongkir.origin_district_id', 3732);
    }

    /**
     * Clear cache - untuk admin atau debugging
     */
    public function clearCache($cacheKey = null)
    {
        if ($cacheKey) {
            DB::table('rajaongkir_cache')->where('cache_key', $cacheKey)->delete();
            Log::info('RajaOngkir Cache Cleared', ['key' => $cacheKey]);
        } else {
            DB::table('rajaongkir_cache')->truncate();
            Log::info('RajaOngkir All Cache Cleared');
        }
    }
}