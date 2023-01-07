<?php

namespace App\Services\Nordigen;

use Illuminate\Http\Client\PendingRequest;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class NordigenClient
{
    /**
     * @var string
     */
    protected string $url;

    /**
     * @var string|null
     */
    protected string $token;

    public function __construct()
    {
        $this->url = config('services.nordigen.url', '');
        $this->token = $this->getAccessToken(
            config('services.nordigen.id', ''),
            config('services.nordigen.key', '')
        );
    }

    /**
     * Get access token
     */
    private function getAccessToken(string $id, string $key): ?string
    {
        $response = Http::acceptJson()->post($this->url . '/token/new/', [
            'secret_id' => $id,
            'secret_key' => $key,
        ]);

        $json = $response->json();
        if (!isset($json['access'])) {
            Log::alert('[nordigen] Access token error', $json);
            return null;
        }

        return $json['access'];
    }

    /**
     * @return PendingRequest
     */
    protected function connect(): PendingRequest
    {
        return Http::withHeaders([
            'Authorization' => 'Bearer ' . $this->token,
        ]);
    }

    /**
     * @param string $url
     * @param array $params
     * @return array
     */
    public function get(string $url, array $params = []): array
    {
        return $this->connect()->get($this->url . $url, $params)->json();
    }

    /**
     * @param string $url
     * @param array $data
     * @return array
     */
    public function post(string $url, array $data = []): array
    {
        return $this->connect()->post($this->url . $url, $data)->json();
    }
}
