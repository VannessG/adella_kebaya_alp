<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;

class RajaOngkirService
{
    private $apiKey;
    private $baseUrl;

    public function __construct()
    {
        $this->apiKey = '8c274VxN29980dbd2f56d476QY0CkWUe'; 
        $this->baseUrl = 'https://rajaongkir.komerce.id/api/v1/'; 
    }

    public function getProvinces()
    {
        // Cache selama 24 jam agar tidak menghabiskan kuota API
        return Cache::remember('ro_provinces_final', 1440, function () {
            try {
                $response = Http::withoutVerifying()->withHeaders(['key' => $this->apiKey])->get($this->baseUrl . 'destination/province');
                return $response->json()['data'] ?? [];
            } catch (\Exception $e) { return []; }
        });
    }

    public function getCities($provinceId)
    {
        return Cache::remember("ro_cities_final_{$provinceId}", 1440, function () use ($provinceId) {
            try {
                $response = Http::withoutVerifying()->withHeaders(['key' => $this->apiKey])->get($this->baseUrl . 'destination/city/' . $provinceId);
                return $response->json()['data'] ?? [];
            } catch (\Exception $e) { return []; }
        });
    }

    public function getDistricts($cityId)
    {
        return Cache::remember("ro_districts_final_{$cityId}", 1440, function () use ($cityId) {
            try {
                $response = Http::withoutVerifying()->withHeaders(['key' => $this->apiKey])->get($this->baseUrl . 'destination/district/' . $cityId);
                return $response->json()['data'] ?? [];
            } catch (\Exception $e) { return []; }
        });
    }

    public function calculateShippingCost($originDistrictId, $destinationDistrictId, $weight, $couriers = null)
    {
        try {
            $couriers = $couriers ?: 'jne';
            
            // Backup ID Kota jika API Limit tercapai
            $originCityId = ($originDistrictId == 953) ? 83 : (($originDistrictId == 6626) ? 409 : null);

            $response = Http::withoutVerifying()
                ->timeout(15)
                ->withHeaders(['key' => $this->apiKey, 'Content-Type' => 'application/x-www-form-urlencoded'])
                ->asForm()
                ->post($this->baseUrl . 'calculate/district/domestic-cost', [
                    'origin' => $originDistrictId,
                    'destination' => $destinationDistrictId,
                    'weight' => max((int)$weight, 1),
                    'courier' => $couriers
                ]);

            $formattedCosts = [];
            if ($response->successful()) {
                $resData = $response->json();
                $results = $resData['data'] ?? [];
                
                $destCityId = $resData['meta']['destination_details']['city_id'] ?? null;
                $isSameCity = ($originCityId && $destCityId && $originCityId == $destCityId);

                foreach ($results as $row) {
                    $formattedCosts[] = [
                        'code' => $couriers,
                        'name' => strtoupper($couriers),
                        'service' => $row['service'] ?? 'REG',
                        'description' => $row['description'] ?? 'Layanan Pengiriman',
                        'cost' => (int)($row['cost'] ?? 0),
                        'etd' => str_replace(' day', '', ($row['etd'] ?? '-'))
                    ];
                }

                if ($couriers == 'lion' && $isSameCity) {
                    $formattedCosts[] = [
                        'code' => 'lion', 'name' => 'LION PARCEL', 'service' => 'SAME DAY',
                        'description' => 'Sampai Hari Ini', 'cost' => 12000, 'etd' => '1'
                    ];
                }
            }
            return $formattedCosts;
        } catch (\Exception $e) { return []; }
    }
}