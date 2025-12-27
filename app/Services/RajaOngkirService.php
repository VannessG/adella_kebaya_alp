<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;

class RajaOngkirService{
    private $apiKey;
    private $baseUrl;

    public function __construct(){
        $this->apiKey = '8c274VxN29980dbd2f56d476QY0CkWUe'; 
        $this->baseUrl = 'https://rajaongkir.komerce.id/api/v1/'; 
    }

    public function getProvinces(){
        return Cache::remember('ro_provinces_final', 1440, function () {
            try {
                $response = Http::withoutVerifying()->withHeaders(['key' => $this->apiKey])->get($this->baseUrl . 'destination/province');
                return $response->json()['data'] ?? [];
            } catch (\Exception $e) { 
                return []; 
            }
        });
    }

    public function getCities($provinceId){
        return Cache::remember("ro_cities_final_{$provinceId}", 1440, function () use ($provinceId) {
            try {
                $response = Http::withoutVerifying()->withHeaders(['key' => $this->apiKey])->get($this->baseUrl . 'destination/city/' . $provinceId);
                return $response->json()['data'] ?? [];
            } catch (\Exception $e) { return []; }
        });
    }

    public function getDistricts($cityId){
        return Cache::remember("ro_districts_final_{$cityId}", 1440, function () use ($cityId) {
            try {
                $response = Http::withoutVerifying()->withHeaders(['key' => $this->apiKey])->get($this->baseUrl . 'destination/district/' . $cityId);
                return $response->json()['data'] ?? [];
            } catch (\Exception $e) { return []; }
        });
    }

    public function calculateShippingCost($originDistrictId, $destinationDistrictId, $weight, $couriers = null, $destinationCityId = null){
        try {
            $couriers = $couriers ?: 'jne';
            $branch = session('selected_branch');
            $branchName = strtolower($branch->name ?? '');
            if (str_contains($branchName, 'bojonegoro')) {
                $originCityId = 566; 
            } else {
                $originCityId = 409;
            }

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
                $results = $response->json()['data'] ?? [];
                $isSameCity = ($originCityId == $destinationCityId) || (in_array($originCityId, [409, 583]) && in_array($destinationCityId, [409, 583]));
                
                foreach ($results as $row) {
                    $formattedCosts[] = [
                        'code' => $couriers,
                        'name' => strtoupper($couriers),
                        'service' => $row['service'] ?? 'REG',
                        'description' => $row['description'] ?? 'Layanan Pengiriman',
                        'cost' => (int)($row['cost'] ?? 0),
                        'etd' => str_replace([' day', ' days'], '', ($row['etd'] ?? '-'))
                    ];
                }

                if ($isSameCity && strtolower($couriers) === 'lion') {
                    $formattedCosts[] = [
                        'code' => 'lion', 
                        'name' => 'LION PARCEL', 
                        'service' => 'SAME DAY',
                        'description' => 'Layanan Sampai Hari Ini (Internal)', 
                        'cost' => 12000, 
                        'etd' => '0-1'
                    ];
                }
            }
            return $formattedCosts;
        } catch (\Exception $e) { 
            Log::error("RajaOngkir Error: " . $e->getMessage());
            return []; 
        }
    }
}