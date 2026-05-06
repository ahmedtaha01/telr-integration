<?php

namespace AhmedTaha\Telr\DTO;

class TelrSdkDTO
{
    public string $cartid;
    public float $amount;
    public string $currency;
    public string $description;
    public string $customerRef;
    public string $customerEmail;

    public function __construct(string $orderReference, float $amount, string $currency, string $description, string $customerRef = '', string $customerEmail = '')
    {
        $this->cartid = $orderReference;
        $this->amount = $amount;
        $this->currency = $currency;
        $this->description = $description;
        $this->customerRef = $customerRef;
        $this->customerEmail = $customerEmail;
    }

    public function toArray(): array
    {
        return [
            'cartid' => $this->cartid,
            'amount'      => [
                'value' => number_format($this->amount, 2, '.', ''),
                'currency' => $this->currency,
            ],
            'description' => $this->description,
            'customer' => [
                'ref' => $this->customerRef,
                'email' => $this->customerEmail,
            ],
        ];
    }
}
