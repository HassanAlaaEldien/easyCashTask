<?php

namespace App\TransactionServices\ProviderAccessor\Contracts;

use Illuminate\Support\Collection;

interface ProviderFileAccessor
{
    /**
     * @return Collection
     */
    public function getProviders(array $customProviders = []): Collection;
}
