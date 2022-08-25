<?php

namespace App\Filters;

use Illuminate\Http\Request;
use Illuminate\Support\Collection;

abstract class Filters
{
    /**
     * @var Request
     */
    protected $request;

    /**
     * @var Collection
     */
    protected $builder;

    /**
     * Filters constructor.
     */
    public function __construct()
    {
        $this->request = request();
    }

    /**
     * Applies filters to the Builder
     *
     * @param Collection $builder
     *
     * @return array
     */
    public function apply(Collection $builder): array
    {
        $this->builder = $builder;

        foreach ($this->filters() as $filter => $value) {
            if (method_exists($this, $filter) && (!empty($value) || $value == 0))
                $this->builder = call_user_func([$this, $filter], $value);
        }

        return array_values($this->builder->toArray());
    }

    /**
     * Gets the filters from the request inputs.
     *
     * @return  array
     */
    public function filters()
    {
        return $this->request->all();
    }
}
