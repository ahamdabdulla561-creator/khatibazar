<?php

namespace App\Services;

use App\Models\Order;
use App\Models\SiteSetting;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class SteadfastService
{
    protected string $baseUrl = 'https://portal.steadfast.com.bd/api/v1';

    /**
     * Get API Key from settings or environment
     */
    public function getApiKey(): ?string
    {
        return site_setting('steadfast_api_key', config('services.steadfast.api_key', env('STEADFAST_API_KEY')));
    }

    /**
     * Get Secret Key from settings or environment
     */
    public function getSecretKey(): ?string
    {
        return site_setting('steadfast_secret_key', config('services.steadfast.secret_key', env('STEADFAST_SECRET_KEY')));
    }

    /**
     * Check if Steadfast is configured and enabled
     */
    public function isConfigured(): bool
    {
        return !empty($this->getApiKey()) && !empty($this->getSecretKey());
    }

    /**
     * Check if Steadfast is active in settings
     */
    public function isEnabled(): bool
    {
        return site_setting('steadfast_status', '1') == '1' && $this->isConfigured();
    }

    /**
     * Get HTTP client headers
     */
    protected function getHeaders(): array
    {
        return [
            'Api-Key' => $this->getApiKey(),
            'Secret-Key' => $this->getSecretKey(),
            'Content-Type' => 'application/json',
            'Accept' => 'application/json',
        ];
    }

    /**
     * Create / Send Order Parcel to Steadfast
     *
     * @param Order $order
     * @param float|null $customCodAmount
     * @param string|null $customNote
     * @return array ['success' => bool, 'message' => string, 'consignment' => array|null]
     */
    public function createOrder(Order $order, ?float $customCodAmount = null, ?string $customNote = null): array
    {
        if (!$this->isConfigured()) {
            return [
                'success' => false,
                'message' => 'স্টেডফাস্ট এপিআই কি (API Key) বা সিক্রেট কি (Secret Key) কনফিগার করা নেই। অনুগ্রহ করে সেটিংস পেজে গিয়ে যুক্ত করুন।',
            ];
        }

        // Clean customer phone number to 11 digits (e.g. 017XXXXXXXX)
        $cleanPhone = preg_replace('/[^0-9]/', '', $order->customer_phone);
        if (str_starts_with($cleanPhone, '880')) {
            $cleanPhone = substr($cleanPhone, 2);
        } elseif (str_starts_with($cleanPhone, '88')) {
            $cleanPhone = substr($cleanPhone, 2);
        }

        // Determine COD amount to collect from customer
        if ($customCodAmount !== null) {
            $codAmount = (float) $customCodAmount;
        } else {
            // If COD, collect grand_total; if prepaid (paid status), collect 0
            if ($order->payment_status === 'paid' && in_array($order->payment_method, ['bkash', 'nagad', 'rocket'])) {
                $codAmount = 0;
            } else {
                $codAmount = (float) $order->grand_total;
            }
        }

        // Construct full recipient address
        $fullAddress = trim("{$order->shipping_address}, {$order->shipping_upazila}, {$order->shipping_district}");

        // Build items summary for note
        $itemsSummary = $order->items->map(function ($item) {
            return $item->product_name . ($item->variant_name ? " ({$item->variant_name})" : '') . " x {$item->quantity}";
        })->join(', ');

        $note = $customNote ?? ($order->notes ? "{$order->notes} | পণ্য: {$itemsSummary}" : "পণ্য: {$itemsSummary}");

        $payload = [
            'invoice' => $order->order_number,
            'recipient_name' => $order->customer_name,
            'recipient_phone' => $cleanPhone,
            'recipient_address' => $fullAddress,
            'cod_amount' => (int) round($codAmount),
            'note' => mb_substr($note, 0, 450),
        ];

        Log::info("Sending order #{$order->order_number} to Steadfast API", $payload);

        try {
            $response = Http::withHeaders($this->getHeaders())
                ->timeout(20)
                ->post("{$this->baseUrl}/create_order", $payload);

            $data = $response->json();

            Log::info("Steadfast API response for #{$order->order_number}", [
                'status' => $response->status(),
                'body' => $data,
            ]);

            if ($response->successful() && isset($data['status']) && $data['status'] == 200 && isset($data['consignment'])) {
                return [
                    'success' => true,
                    'message' => $data['message'] ?? 'স্টেডফাস্টে অর্ডার সফলভাবে বুক করা হয়েছে!',
                    'consignment' => $data['consignment'],
                ];
            }

            // Extract error message
            $errorMsg = 'স্টেডফাস্ট থেকে অর্ডার তৈরি করা যায়নি।';
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
            Log::error("Steadfast API Exception for #{$order->order_number}: " . $e->getMessage());
            return [
                'success' => false,
                'message' => 'স্টেডফাস্ট সার্ভারের সাথে সংযোগ বিচ্ছিন্ন হয়েছে: ' . $e->getMessage(),
            ];
        }
    }

    /**
     * Check Delivery Status by Consignment ID
     *
     * @param int|string $consignmentId
     * @return array
     */
    public function getStatusByConsignmentId($consignmentId): array
    {
        if (!$this->isConfigured()) {
            return ['success' => false, 'message' => 'API কনফিগার করা নেই।'];
        }

        try {
            $response = Http::withHeaders($this->getHeaders())
                ->timeout(15)
                ->get("{$this->baseUrl}/status_by_cid/{$consignmentId}");

            $data = $response->json();

            if ($response->successful() && isset($data['status']) && $data['status'] == 200) {
                return [
                    'success' => true,
                    'delivery_status' => $data['delivery_status'] ?? 'unknown',
                    'raw' => $data,
                ];
            }

            return [
                'success' => false,
                'message' => $data['message'] ?? 'স্ট্যাটাস পাওয়া যায়নি।',
            ];
        } catch (\Throwable $e) {
            return [
                'success' => false,
                'message' => $e->getMessage(),
            ];
        }
    }

    /**
     * Check Delivery Status by Invoice
     *
     * @param string $invoice
     * @return array
     */
    public function getStatusByInvoice(string $invoice): array
    {
        if (!$this->isConfigured()) {
            return ['success' => false, 'message' => 'API কনফিগার করা নেই।'];
        }

        try {
            $response = Http::withHeaders($this->getHeaders())
                ->timeout(15)
                ->get("{$this->baseUrl}/status_by_invoice/{$invoice}");

            $data = $response->json();

            if ($response->successful() && isset($data['status']) && $data['status'] == 200) {
                return [
                    'success' => true,
                    'delivery_status' => $data['delivery_status'] ?? 'unknown',
                    'raw' => $data,
                ];
            }

            return [
                'success' => false,
                'message' => $data['message'] ?? 'স্ট্যাটাস পাওয়া যায়নি।',
            ];
        } catch (\Throwable $e) {
            return [
                'success' => false,
                'message' => $e->getMessage(),
            ];
        }
    }

    /**
     * Check Delivery Status by Tracking Code
     *
     * @param string $trackingCode
     * @return array
     */
    public function getStatusByTrackingCode(string $trackingCode): array
    {
        if (!$this->isConfigured()) {
            return ['success' => false, 'message' => 'API কনফিগার করা নেই।'];
        }

        try {
            $response = Http::withHeaders($this->getHeaders())
                ->timeout(15)
                ->get("{$this->baseUrl}/status_by_trackingcode/{$trackingCode}");

            $data = $response->json();

            if ($response->successful() && isset($data['status']) && $data['status'] == 200) {
                return [
                    'success' => true,
                    'delivery_status' => $data['delivery_status'] ?? 'unknown',
                    'raw' => $data,
                ];
            }

            return [
                'success' => false,
                'message' => $data['message'] ?? 'স্ট্যাটাস পাওয়া যায়নি।',
            ];
        } catch (\Throwable $e) {
            return [
                'success' => false,
                'message' => $e->getMessage(),
            ];
        }
    }

    /**
     * Get Current Steadfast Account Balance
     *
     * @return array
     */
    public function getCurrentBalance(): array
    {
        if (!$this->isConfigured()) {
            return ['success' => false, 'message' => 'API কনফিগার করা নেই।'];
        }

        try {
            $response = Http::withHeaders($this->getHeaders())
                ->timeout(15)
                ->get("{$this->baseUrl}/get_balance");

            $data = $response->json();

            if ($response->successful() && isset($data['status']) && $data['status'] == 200) {
                return [
                    'success' => true,
                    'balance' => $data['current_balance'] ?? 0.00,
                ];
            }

            return [
                'success' => false,
                'message' => $data['message'] ?? 'ব্যালেন্স তথ্য পাওয়া যায়নি।',
            ];
        } catch (\Throwable $e) {
            return [
                'success' => false,
                'message' => $e->getMessage(),
            ];
        }
    }
}
