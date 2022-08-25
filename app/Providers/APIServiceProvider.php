<?php

namespace App\Providers;

use App\Exceptions\ApiHandler;
use App\Http\Responses\ApiResponder;
use App\Http\Responses\ResponsesInterface;
use Illuminate\Contracts\Debug\ExceptionHandler;
use Illuminate\Support\ServiceProvider;

class APIServiceProvider extends ServiceProvider
{
    /**
     * Number of items per page in each collection.
     *
     * @var int
     */
    public const ItemsPerPage = 25;

    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        // Use the ApiResponder as the concrete implementation for the ResponsesInterface
        $this->app->bind(ResponsesInterface::class, ApiResponder::class);

        // Use the ApiHandler as the main exception handler
        $this->app->singleton(ExceptionHandler::class, ApiHandler::class);
    }
}
