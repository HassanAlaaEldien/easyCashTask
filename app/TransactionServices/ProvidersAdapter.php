<?php

namespace App\TransactionServices;

use App\Filters\Filters;
use App\Filters\TransactionsFilters;
use App\TransactionServices\ProviderAccessor\Contracts\ProviderFileAccessor;
use Illuminate\Support\Collection;

class ProvidersAdapter
{
    /**
     * @var Collection
     */
    private Collection $providers;

    /**
     * @param ProviderFileAccessor $providerFileAccessor
     * @param array $providers
     */
    public function __construct(ProviderFileAccessor $providerFileAccessor, array $providers = [])
    {
        $this->providers = $providerFileAccessor->getProviders(array_filter($providers));
    }

    /**
     * @return array
     */
    public function listWithFilters(): array
    {
        $this->providers->each(function ($provider, $path) use (&$transactions) {
            $transactions[] = app($path)->transform($provider['path']);
        });

        $transactions = call_user_func_array('array_merge', $transactions ?? []);

        return $this->filter(new TransactionsFilters(), $transactions);
    }

    /**
     * @param Filters $filters
     * @param array $transactions
     * @return array
     */
    private function filter(Filters $filters, array $transactions): array
    {
        return $filters->apply(Collection::make($transactions));
    }
}
