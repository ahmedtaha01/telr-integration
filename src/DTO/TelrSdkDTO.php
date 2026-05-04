<?php

namespace AhmedTaha\Telr\DTO;

use function Laravel\Prompts\number;

class TelrSdkDTO
{
    public string $cartid;
    public float $amount;
    public string $currency;
    public string $description;
    public string $customerRef;
    public string $customerEmail;

    public function __construct(array $data)
    {
        $this->cartid = $data['cartid'];
        $this->amount = $data['amount'];
        $this->currency = $data['currency'];
        $this->description = $data['description'];
        $this->customerRef = $data['customer']['ref'] ?? '';
        $this->customerEmail = $data['customer']['email'] ?? '';
    }

    public function toArray(): array
    {
        return [
            'cartid' => $this->cartid,
            'amount'      => [
                'value' => number_format($this->amount, 2, '.', ''),
                'currency' => $this->currency,
            ],
            'currency' => $this->currency,
            'description' => $this->description,
            'customer' => [
                'ref' => $this->customerRef,
                'email' => $this->customerEmail,
            ],
        ];
    }
}
