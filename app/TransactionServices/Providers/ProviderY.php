<?php

namespace App\TransactionServices\Providers;

use App\TransactionServices\Contracts\TransactionProvider;
use Illuminate\Support\Collection;

class ProviderY implements TransactionProvider
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
                'id' => $item->id,
                'amount' => $item->amount,
                "currency" => $item->currency,
                "phone" => $item->phone,
                "status" => $this->statusMapper($item->status),
                "created_at" => $item->created_at
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
            '100', 100 => 'paid',
            '200', 200 => 'pending',
            '300', 300 => 'reject'
        };
    }
}
