<?php

namespace App\Filters;

use Illuminate\Support\Collection;

class TransactionsFilters extends Filters
{
    /**
     * @param $value
     * @return Collection
     */
    public function statusCode($value): Collection
    {
        return $this->builder->where('status', $value);
    }

    /**
     * @param $value
     * @return Collection
     */
    public function amountMin($value): Collection
    {
        return $this->builder->where('amount', '>=', $value);
    }

    /**
     * @param $value
     * @return Collection
     */
    public function amountMax($value): Collection
    {
        return $this->builder->where('amount', '<=', $value);
    }

    /**
     * @param $value
     * @return Collection
     */
    public function currency($value): Collection
    {
        return $this->builder->where('currency', $value);
    }
}
