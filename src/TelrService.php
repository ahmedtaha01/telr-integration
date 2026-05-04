<?php

namespace AhmedTaha\Telr;

use AhmedTaha\Telr\DTO\TelrSdkDTO;
use AhmedTaha\Telr\Enums\TelrMode;
use AhmedTaha\Telr\Exceptions\TelrException;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;

class TelrService
{
    protected string $store;
    protected string $authKey;
    protected string $name;
    protected TelrMode $mode;
    protected string $baseUrl;
    protected string $credentials;

    public function __construct()
    {
        $this->mode       = TelrMode::from(config('telr.mode'));
        $this->store      = config('telr.'.$this->mode->value.'.store_id');
        $this->authKey    = config('telr.'.$this->mode->value.'.auth_key');
        $this->baseUrl    = config('telr.'.$this->mode->value.'.base_url');
        $this->credentials = base64_encode($this->store . ':' . $this->authKey);

    }

    public function createSDKPaymentTransaction(TelrSdkDTO $payload): array
    {
        $payload = $payload->toArray();
        $payload['test'] = $this->mode->isTest() ? '1' : '0';

        $response = $this->createTransaction($payload);

        if($response->failed()) {
            throw new TelrException('Failed to create transaction: ' . $response->body());
        }

        $responseData = $response->json();

        return [
            'token_url' => $responseData['_links']['auth']['href'],
            'order_url' => $responseData['_links']['self']['href'],
            'ref'       => $responseData['ref'],
            'response_data' => $responseData
        ];
    }

    private function createTransaction(array $payload): Response
    {
        return Http::withHeaders([
                'Content-Type'  => 'application/json',
                'Authorization' => 'Basic ' . $this->credentials,
            ])->post($this->baseUrl . '/orders', $payload);
    }

    public function verifyTransaction(string $ref): array
    {
        $response = $this->getTransactionStatus($ref);

        if($response->failed()) {
            throw new TelrException('Failed to verify transaction: ' . $response->body());
        }

        return $response->json();
    }

    private function getTransactionStatus(string $ref): Response
    {
        return Http::withHeaders([
            'Content-Type'  => 'application/json',
            'Authorization' => 'Basic ' . $this->credentials,
        ])->get($this->baseUrl . '/orders/' . $ref);
    }
}