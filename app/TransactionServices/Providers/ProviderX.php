<?php

namespace App\TransactionServices\Providers;

use App\TransactionServices\Contracts\TransactionProvider;
use Illuminate\Support\Collection;

class ProviderX implements TransactionProvider
{
    /**
     * @param string $jsonFilePath
     * @return array
     */
    public function transform(string $jsonFilePath): array
    {
        $fileContent = file_exists($jsonFilePath) ? json_decode(file_get_contents($jsonFilePath)) : [];

        return Collection::make($fileContent)->map(function ($item) {
            return [
                'id' => $item->transactionIdentification,
                'amount' => $item->transactionAmount,
                "currency" => $item->Currency,
                "phone" => $item->senderPhone,
                "status" => $this->statusMapper($item->transactionStatus),
                "created_at" => $item->transactionDate
            ];
        })->toArray();
    }

    /**
     * @param $status
     * @return string
     */
    public function statusMapper($status): string
    {
        return match ($status) {
            '1', 1 => 'paid',
            '2', 2 => 'pending',
            '3', 3 => 'reject'
        };
    }
}
