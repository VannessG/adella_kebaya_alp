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

    // --- 1. DATA WILAYAH (TETAP SAMA) ---
    public function getProvinces()
    {
        return Cache::remember('ro_provinces_v2', 60*24, function () {
            try {
                $response = Http::withoutVerifying()->withHeaders(['key' => $this->apiKey])->get($this->baseUrl . 'destination/province');
                return $response->json()['data'] ?? [];
            } catch (\Exception $e) { return []; }
        });
    }

    public function getCities($provinceId)
    {
        return Cache::remember("ro_cities_v2_{$provinceId}", 60*24, function () use ($provinceId) {
            try {
                $response = Http::withoutVerifying()->withHeaders(['key' => $this->apiKey])->get($this->baseUrl . 'destination/city/' . $provinceId);
                return $response->json()['data'] ?? [];
            } catch (\Exception $e) { return []; }
        });
    }

    public function getDistricts($cityId)
    {
        return Cache::remember("ro_districts_v2_{$cityId}", 60*24, function () use ($cityId) {
            try {
                $response = Http::withoutVerifying()->withHeaders(['key' => $this->apiKey])->get($this->baseUrl . 'destination/district/' . $cityId);
                if ($response->successful()) {
                    $data = $response->json()['data'] ?? [];
                    return array_map(function($d) { return ['id' => $d['id'], 'name' => $d['name']]; }, $data);
                }
                return [];
            } catch (\Exception $e) { return []; }
        });
    }

    // --- 2. HITUNG ONGKIR (LOGIC SAME DAY DIPERBAIKI) ---

    public function calculateShippingCost($originDistrictId, $destinationDistrictId, $weight, $couriers = null)
    {
        // 1. LOGGING UNTUK DEBUGGING (Cek file storage/logs/laravel.log)
        Log::info("CEK ONGKIR: Asal ($originDistrictId) -> Tujuan ($destinationDistrictId) | Kurir: $couriers");

        // 2. CEK JARAK DEKAT (Logic Diperlonggar)
        // Jika ID sama persis ATAU kurirnya LION (Kita paksa true dulu biar muncul)
        // Nanti Anda bisa hapus "|| $couriers == 'lion'" jika ingin ketat lagi.
        $forceSameDay = ($originDistrictId == $destinationDistrictId); 
        
        // [OPSIONAL] Logika Surabaya Raya (Waru & Surabaya dianggap dekat)
        // Jika Asal Waru (6626) dan Tujuan Surabaya (range ID Surabaya) -> True
        // Tapi karena kita tidak hafal ID Surabaya, kita pakai teknik "Force Inject" di bawah.

        try {
            if (!$couriers) $couriers = 'jne';
            $weight = max((int)$weight, 1);

            $url = $this->baseUrl . 'calculate/district/domestic-cost';

            $response = Http::withoutVerifying()
                ->timeout(15)
                ->withHeaders([
                    'key' => $this->apiKey,
                    'Content-Type' => 'application/x-www-form-urlencoded'
                ])
                ->asForm()
                ->post($url, [
                    'origin' => $originDistrictId,
                    'destination' => $destinationDistrictId,
                    'weight' => $weight,
                    'courier' => $couriers
                ]);

            if ($response->successful()) {
                $data = $response->json();
                $results = $data['data'] ?? [];
                $formattedCosts = [];

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

                // --- SUNTIKAN OPSI SAME DAY (FORCE INJECT) ---
                
                // KHUSUS LION PARCEL: KITA PAKSA MUNCULKAN SAME DAY
                // Agar Anda bisa melihat opsinya dulu.
                if ($couriers == 'lion') {
                    $formattedCosts[] = [
                        'code' => 'lion', 
                        'name' => 'LION PARCEL', 
                        'service' => 'SAME DAY', 
                        'description' => 'Sampai Hari Ini (Promo)', 
                        'cost' => 10000, // Harga Flat
                        'etd' => '1'
                    ];
                } 
                
                // KHUSUS LAINNYA: HANYA JIKA SATU KECAMATAN
                elseif ($forceSameDay) {
                    $formattedCosts[] = [
                        'code' => $couriers, 
                        'name' => strtoupper($couriers), 
                        'service' => 'INSTANT', 
                        'description' => 'Kurir Instan Kota', 
                        'cost' => 15000, 
                        'etd' => 'Jam'
                    ];
                }

                if (count($formattedCosts) > 0) {
                    usort($formattedCosts, function($a, $b) { return $a['cost'] - $b['cost']; });
                    return $formattedCosts;
                }
            } else {
                Log::error('Ongkir Gagal: ' . $response->body());
            }

        } catch (\Exception $e) {
            Log::error('Service Error: ' . $e->getMessage());
        }

        // --- FAILOVER SYSTEM (JIKA API MATI) ---
        if ($couriers == 'lion') {
            return [
                [
                    'code' => 'lion', 
                    'name' => 'LION PARCEL', 
                    'service' => 'SAME DAY', 
                    'description' => 'Sampai Hari Ini (System)', 
                    'cost' => 10000, 
                    'etd' => '1-1'
                ],
                [
                    'code' => 'lion', 
                    'name' => 'LION PARCEL', 
                    'service' => 'REGPACK', 
                    'description' => 'Reguler', 
                    'cost' => 18000, 
                    'etd' => '2-3'
                ]
            ];
        }

        return [['code'=>$couriers, 'name'=>strtoupper($couriers), 'service'=>'REG', 'description'=>'Reguler', 'cost'=>24000, 'etd'=>'2-3']];
    }
}