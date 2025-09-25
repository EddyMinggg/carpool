<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class PlacesController extends Controller
{
    private $apiKey;

    public function __construct()
    {
        $this->apiKey = env('GOOGLE_PLACES_API_KEY', env('GEOCODING_API_KEY'));
        
        // 檢查 API key 是否存在
        if (empty($this->apiKey)) {
            Log::error('Google Places API key not found in environment variables');
        }
    }

    /**
     * 搜索地點
     */
    public function search(Request $request)
    {
        $query = $request->get('q');
        
        if (empty($query)) {
            return response()->json([
                'success' => false,
                'message' => '搜索關鍵字不能為空'
            ], 400);
        }

        try {
            $results = $this->searchGooglePlaces($query);
            
            return response()->json([
                'success' => true,
                'data' => $results
            ]);
        } catch (\Exception $e) {
            Log::error('Places search error: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => '搜索地址時發生錯誤: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * 反向地理編碼
     */
    public function reverseGeocode(Request $request)
    {
        $lat = $request->get('lat');
        $lng = $request->get('lng');

        if (!$lat || !$lng) {
            return response()->json(['error' => 'Missing coordinates'], 400);
        }

        $cacheKey = 'reverse_geocode_' . md5($lat . '_' . $lng);
        $allResults = Cache::remember($cacheKey, 600, function () use ($lat, $lng) {
            return $this->reverseGeocodeGoogleAll($lat, $lng);
        });

        return response()->json(['results' => $allResults]);
    }

    /**
     * 獲取地點詳細信息
     */
    public function placeDetails(Request $request)
    {
        $placeId = $request->get('place_id');
        
        if (!$placeId) {
            return response()->json(['error' => 'Missing place_id'], 400);
        }

        $cacheKey = 'place_details_' . $placeId;
        $result = Cache::remember($cacheKey, 3600, function () use ($placeId) {
            return $this->getGooglePlaceDetails($placeId);
        });

        return response()->json(['result' => $result]);
    }

    /**
     * 使用 Google Places API 搜索地點
     */
    private function searchGooglePlaces($query)
    {
        try {
            $response = Http::timeout(30)->get('https://maps.googleapis.com/maps/api/place/textsearch/json', [
                'query' => $query,
                'key' => $this->apiKey,
                'language' => 'zh-TW',
                'region' => 'HK'
            ]);
            
            if ($response->successful()) {
                $data = $response->json();
                if (isset($data['error_message'])) {
                    Log::error('Google API error: ' . $data['error_message']);
                }
                return $this->formatGooglePlacesResults($data['results'] ?? []);
            }

            Log::error('Google Places API error: ' . $response->body());
            return [];

        } catch (\Exception $e) {
            Log::error('Google Places search error: ' . $e->getMessage());
            Log::error('Stack trace: ' . $e->getTraceAsString());
            throw $e; // 重新拋出異常以便上層處理
        }
    }

    /**
     * Google 反向地理編碼
     */
    /**
     * 返回所有 Google Geocoding 結果
     */
    private function reverseGeocodeGoogleAll($lat, $lng)
    {
        try {
            $response = Http::get('https://maps.googleapis.com/maps/api/geocode/json', [
                'latlng' => $lat . ',' . $lng,
                'key' => $this->apiKey,
                'language' => 'zh-TW',
                'result_type' => 'street_address|premise|subpremise|establishment|point_of_interest|neighborhood|sublocality|political',
                'location_type' => 'ROOFTOP|RANGE_INTERPOLATED|GEOMETRIC_CENTER'
            ]);

            if ($response->successful()) {
                $data = $response->json();
                $results = $data['results'] ?? [];
                return array_map([$this, 'formatGoogleGeocodeResult'], $results);
            }

            Log::error('Google Geocoding API error: ' . $response->body());
            return [];

        } catch (\Exception $e) {
            Log::error('Google reverse geocoding error: ' . $e->getMessage());
            return [];
        }
    }

    /**
     * 獲取 Google 地點詳細信息
     */
    private function getGooglePlaceDetails($placeId)
    {
        try {
            $response = Http::get('https://maps.googleapis.com/maps/api/place/details/json', [
                'place_id' => $placeId,
                'key' => $this->apiKey,
                'language' => 'zh-TW',
                'fields' => 'place_id,name,formatted_address,geometry,types,formatted_phone_number,opening_hours,rating,reviews'
            ]);

            if ($response->successful()) {
                $data = $response->json();
                return $this->formatGooglePlaceDetails($data['result'] ?? null);
            }

            Log::error('Google Place Details API error: ' . $response->body());
            return null;

        } catch (\Exception $e) {
            Log::error('Google place details error: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * 格式化 Google Places 搜索結果
     */
    private function formatGooglePlacesResults($results)
    {
        return array_map(function ($place) {
            return [
                'place_id' => $place['place_id'],
                'name' => $place['name'],
                'formatted_address' => $place['formatted_address'],
                'geometry' => [
                    'location' => [
                        'lat' => $place['geometry']['location']['lat'],
                        'lng' => $place['geometry']['location']['lng']
                    ]
                ],
                'types' => $place['types'] ?? [],
                'rating' => $place['rating'] ?? null,
                'price_level' => $place['price_level'] ?? null
            ];
        }, $results);
    }

    /**
     * 格式化 Google 地理編碼結果
     */
    private function formatGoogleGeocodeResult($result)
    {
        return [
            'place_id' => $result['place_id'],
            'name' => $this->extractLocationName($result),
            'formatted_address' => $result['formatted_address'],
            'geometry' => [
                'location' => [
                    'lat' => $result['geometry']['location']['lat'],
                    'lng' => $result['geometry']['location']['lng']
                ]
            ],
            'types' => $result['types'] ?? [],
            'address_components' => $result['address_components'] ?? []
        ];
    }

    /**
     * 格式化 Google 地點詳細信息
     */
    private function formatGooglePlaceDetails($result)
    {
        if (!$result) return null;

        return [
            'place_id' => $result['place_id'],
            'name' => $result['name'],
            'formatted_address' => $result['formatted_address'],
            'geometry' => [
                'location' => [
                    'lat' => $result['geometry']['location']['lat'],
                    'lng' => $result['geometry']['location']['lng']
                ]
            ],
            'types' => $result['types'] ?? [],
            'formatted_phone_number' => $result['formatted_phone_number'] ?? null,
            'opening_hours' => $result['opening_hours'] ?? null,
            'rating' => $result['rating'] ?? null,
            'reviews' => array_slice($result['reviews'] ?? [], 0, 3) // 只取前3個評論
        ];
    }

    /**
     * 從地理編碼結果中提取合適的地點名稱
     */
    private function extractLocationName($result)
    {
        $addressComponents = $result['address_components'] ?? [];
        
        // 優先選擇建築物、商家或街道名稱
        foreach ($addressComponents as $component) {
            $types = $component['types'];
            
            if (in_array('establishment', $types) || 
                in_array('premise', $types) ||
                in_array('subpremise', $types)) {
                return $component['long_name'];
            }
        }

        // 如果沒有找到建築物名稱，使用街道號碼 + 街道名稱
        $streetNumber = '';
        $streetName = '';
        
        foreach ($addressComponents as $component) {
            $types = $component['types'];
            
            if (in_array('street_number', $types)) {
                $streetNumber = $component['long_name'];
            } elseif (in_array('route', $types)) {
                $streetName = $component['long_name'];
            }
        }

        if ($streetNumber && $streetName) {
            return $streetNumber . ' ' . $streetName;
        }

        return $streetName ?: $result['formatted_address'];
    }
}