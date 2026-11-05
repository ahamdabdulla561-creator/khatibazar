<?php

namespace App\Services;

use App\Models\Order;
use App\Models\SiteSetting;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class PathaoService
{
    /**
     * Get Base API URL based on environment
     */
    public function getBaseUrl(): string
    {
        $env = site_setting('pathao_environment', config('services.pathao.environment', 'production'));
        return ($env === 'sandbox')
            ? 'https://courier-api-sandbox.pathao.com'
            : 'https://api-hermes.pathao.com';
    }

    public function getClientId(): ?string
    {
        return site_setting('pathao_client_id', config('services.pathao.client_id', env('PATHAO_CLIENT_ID')));
    }

    public function getClientSecret(): ?string
    {
        return site_setting('pathao_client_secret', config('services.pathao.client_secret', env('PATHAO_CLIENT_SECRET')));
    }

    public function getUsername(): ?string
    {
        return site_setting('pathao_username', config('services.pathao.username', env('PATHAO_USERNAME')));
    }

    public function getPassword(): ?string
    {
        return site_setting('pathao_password', config('services.pathao.password', env('PATHAO_PASSWORD')));
    }

    public function getDefaultStoreId(): ?string
    {
        return site_setting('pathao_store_id', config('services.pathao.store_id', env('PATHAO_STORE_ID')));
    }

    /**
     * Check if Pathao credentials are configured
     */
    public function isConfigured(): bool
    {
        return !empty($this->getClientId()) &&
               !empty($this->getClientSecret()) &&
               !empty($this->getUsername()) &&
               !empty($this->getPassword());
    }

    /**
     * Check if Pathao is active in settings
     */
    public function isEnabled(): bool
    {
        return site_setting('pathao_status', '1') == '1' && $this->isConfigured();
    }

    /**
     * Obtain or retrieve cached Bearer Access Token
     */
    public function getAccessToken(): ?string
    {
        if (!$this->isConfigured()) {
            return null;
        }

        $cacheKey = 'pathao_access_token_' . md5($this->getClientId() . $this->getUsername());

        return Cache::remember($cacheKey, now()->addDays(25), function () {
            try {
                $response = Http::asJson()
                    ->acceptJson()
                    ->timeout(20)
                    ->post("{$this->getBaseUrl()}/aladdin/api/v1/issue-token", [
                        'client_id' => $this->getClientId(),
                        'client_secret' => $this->getClientSecret(),
                        'username' => $this->getUsername(),
                        'password' => $this->getPassword(),
                        'grant_type' => 'password',
                    ]);

                $data = $response->json();

                if ($response->successful() && isset($data['access_token'])) {
                    return $data['access_token'];
                }

                Log::error('Pathao issue-token failed: ' . json_encode($data));
                return null;
            } catch (\Throwable $e) {
                Log::error('Pathao issue-token exception: ' . $e->getMessage());
                return null;
            }
        });
    }

    /**
     * Clear cached access token (useful on auth errors)
     */
    public function clearTokenCache(): void
    {
        $cacheKey = 'pathao_access_token_' . md5($this->getClientId() . $this->getUsername());
        Cache::forget($cacheKey);
    }

    /**
     * Common Authenticated Headers
     */
    protected function getHeaders(): array
    {
        return [
            'Authorization' => 'Bearer ' . $this->getAccessToken(),
            'Accept' => 'application/json',
            'Content-Type' => 'application/json',
        ];
    }

    /**
     * Get Merchant's Stores / Pickup Locations
     */
    public function getStores(): array
    {
        if (!$this->isConfigured()) {
            return [];
        }

        return Cache::remember('pathao_stores_list', now()->addHours(6), function () {
            try {
                $response = Http::withHeaders($this->getHeaders())
                    ->timeout(15)
                    ->get("{$this->getBaseUrl()}/aladdin/api/v1/stores");

                $data = $response->json();
                return ($response->successful() && isset($data['data']['data'])) ? $data['data']['data'] : [];
            } catch (\Throwable $e) {
                Log::error('Pathao getStores error: ' . $e->getMessage());
                return [];
            }
        });
    }

    /**
     * Get Cities List (Bangladesh = Country ID 1)
     */
    public function getCities(): array
    {
        if (!$this->isConfigured()) {
            return [];
        }

        return Cache::remember('pathao_cities_list', now()->addDays(7), function () {
            try {
                $response = Http::withHeaders($this->getHeaders())
                    ->timeout(15)
                    ->get("{$this->getBaseUrl()}/aladdin/api/v1/countries/1/city-list");

                $data = $response->json();
                return ($response->successful() && isset($data['data']['data'])) ? $data['data']['data'] : [];
            } catch (\Throwable $e) {
                Log::error('Pathao getCities error: ' . $e->getMessage());
                return [];
            }
        });
    }

    /**
     * Get Zones for a specific City
     */
    public function getZones(int $cityId): array
    {
        if (!$this->isConfigured() || empty($cityId)) {
            return [];
        }

        return Cache::remember("pathao_zones_city_{$cityId}", now()->addDays(7), function () use ($cityId) {
            try {
                $response = Http::withHeaders($this->getHeaders())
                    ->timeout(15)
                    ->get("{$this->getBaseUrl()}/aladdin/api/v1/cities/{$cityId}/zone-list");

                $data = $response->json();
                return ($response->successful() && isset($data['data']['data'])) ? $data['data']['data'] : [];
            } catch (\Throwable $e) {
                Log::error("Pathao getZones for city {$cityId} error: " . $e->getMessage());
                return [];
            }
        });
    }

    /**
     * Get Areas for a specific Zone
     */
    public function getAreas(int $zoneId): array
    {
        if (!$this->isConfigured() || empty($zoneId)) {
            return [];
        }

        return Cache::remember("pathao_areas_zone_{$zoneId}", now()->addDays(7), function () use ($zoneId) {
            try {
                $response = Http::withHeaders($this->getHeaders())
                    ->timeout(15)
                    ->get("{$this->getBaseUrl()}/aladdin/api/v1/zones/{$zoneId}/area-list");

                $data = $response->json();
                return ($response->successful() && isset($data['data']['data'])) ? $data['data']['data'] : [];
            } catch (\Throwable $e) {
                Log::error("Pathao getAreas for zone {$zoneId} error: " . $e->getMessage());
                return [];
            }
        });
    }

    /**
     * Create / Dispatch an Order to Pathao
     *
     * @param Order $order
     * @param array $params Custom override params (store_id, recipient_city, recipient_zone, recipient_area, cod_amount, note, weight)
     * @return array ['success' => bool, 'message' => string, 'consignment_id' => string|null, 'data' => array|null]
     */
    public function createOrder(Order $order, array $params = []): array
    {
        if (!$this->isConfigured()) {
            return [
                'success' => false,
                'message' => 'পাঠাও এপিআই তথ্য (Client ID / Secret / Credentials) কনফিগার করা নেই। অনুগ্রহ করে সেটিংস পেজে যুক্ত করুন।',
            ];
        }

        // Clean customer phone number to 11 digits
        $cleanPhone = preg_replace('/[^0-9]/', '', $order->customer_phone);
        if (str_starts_with($cleanPhone, '880')) {
            $cleanPhone = substr($cleanPhone, 2);
        } elseif (str_starts_with($cleanPhone, '88')) {
            $cleanPhone = substr($cleanPhone, 2);
        }

        // Determine COD amount
        if (isset($params['cod_amount']) && $params['cod_amount'] !== '') {
            $codAmount = (float) $params['cod_amount'];
        } else {
            if ($order->payment_status === 'paid' && in_array($order->payment_method, ['bkash', 'nagad', 'rocket'])) {
                $codAmount = 0;
            } else {
                $codAmount = (float) $order->grand_total;
            }
        }

        $fullAddress = trim("{$order->shipping_address}, {$order->shipping_upazila}, {$order->shipping_district}");

        // Build item summary description
        $itemsSummary = $order->items->map(function ($item) {
            return $item->product_name . ($item->variant_name ? " ({$item->variant_name})" : '') . " x {$item->quantity}";
        })->join(', ');

        $totalQty = max(1, (int) $order->items->sum('quantity'));
        $note = $params['note'] ?? ($order->notes ? "{$order->notes} | পণ্য: {$itemsSummary}" : "পণ্য: {$itemsSummary}");

        $storeId = !empty($params['store_id']) ? (int) $params['store_id'] : (int) $this->getDefaultStoreId();

        $payload = [
            'store_id' => $storeId,
            'merchant_order_id' => $order->order_number,
            'recipient_name' => $order->customer_name,
            'recipient_phone' => $cleanPhone,
            'recipient_address' => mb_substr($fullAddress, 0, 250),
            'recipient_city' => (int) ($params['recipient_city'] ?? 1),
            'recipient_zone' => (int) ($params['recipient_zone'] ?? 1),
            'delivery_type' => 48, // 48-hour delivery
            'item_type' => 2, // 2 = Parcel
            'special_instruction' => mb_substr($note, 0, 250),
            'item_quantity' => $totalQty,
            'item_weight' => (float) ($params['item_weight'] ?? 0.5),
            'amount_to_collect' => (int) round($codAmount),
            'item_description' => mb_substr($itemsSummary ?: 'Farm & Organic Products', 0, 250),
        ];

        if (!empty($params['recipient_area'])) {
            $payload['recipient_area'] = (int) $params['recipient_area'];
        }

        Log::info("Sending order #{$order->order_number} to Pathao API", $payload);

        try {
            $response = Http::withHeaders($this->getHeaders())
                ->timeout(25)
                ->post("{$this->getBaseUrl()}/aladdin/api/v1/orders", $payload);

            // If 401 Unauthorized, clear cache and retry once
            if ($response->status() === 401) {
                $this->clearTokenCache();
                $response = Http::withHeaders($this->getHeaders())
                    ->timeout(25)
                    ->post("{$this->getBaseUrl()}/aladdin/api/v1/orders", $payload);
            }

            $data = $response->json();

            Log::info("Pathao API response for #{$order->order_number}", [
                'status' => $response->status(),
                'body' => $data,
            ]);

            if ($response->successful() && (isset($data['type']) && $data['type'] === 'success' || isset($data['data']['consignment_id']))) {
                $consignmentId = $data['data']['consignment_id'] ?? null;
                return [
                    'success' => true,
                    'message' => $data['message'] ?? 'পাঠাও-তে পার্সেল সফলভাবে বুক করা হয়েছে!',
                    'consignment_id' => $consignmentId,
                    'data' => $data['data'] ?? [],
                ];
            }

            $errorMsg = 'পাঠাও-তে অর্ডার পাঠানো সম্ভব হয়নি।';
            if (isset($data['errors']) && is_array($data['errors'])) {
                $flatErrors = [];
                foreach ($data['errors'] as $field => $errs) {
                    $flatErrors[] = is_array($errs) ? implode(', ', $errs) : $errs;
                }
                $errorMsg .= ' ' . implode(' | ', $flatErrors);
            } elseif (isset($data['message'])) {
                $errorMsg .= ' ' . $data['message'];
            }

            return [
                'success' => false,
                'message' => $errorMsg,
                'data' => $data,
            ];
        } catch (\Throwable $e) {
            Log::error("Pathao API Exception for #{$order->order_number}: " . $e->getMessage());
            return [
                'success' => false,
                'message' => 'পাঠাও সার্ভারের সাথে সংযোগ সমস্যা: ' . $e->getMessage(),
            ];
        }
    }
}
