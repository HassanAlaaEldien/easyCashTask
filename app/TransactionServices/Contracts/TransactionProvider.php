<?php

namespace App\TransactionServices\Contracts;

interface TransactionProvider
{
    /**
     * @return array
     */
    public function transform(string $jsonFilePath): array;

    /**
     * @return string
     */
    public function statusMapper($status): string;
}
