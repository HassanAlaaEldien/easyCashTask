<?php

namespace App\TransactionServices\ProviderAccessor\Accessors;

use App\TransactionServices\ProviderAccessor\Contracts\ProviderFileAccessor;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

class MockingProviders implements ProviderFileAccessor
{
    /**
     * @return Collection
     */
    public function getProviders(array $customProviders = []): Collection
    {
        $path = base_path('app/TransactionServices/Providers');

        Collection::make(array_diff(scandir($path), array('.', '..')))
            ->each(function ($file) use (&$providers) {
                $fileName = Str::remove('.php', $file);
                $providers["App\\TransactionServices\\Providers\\{$fileName}"] = [
                    'name' => $fileName,
                    'path' => storage_path("mockingFiles/Data{$fileName}.json")
                ];
            });

        return Collection::make($providers)
            ->filter(fn($file, $path) => empty($customProviders) || in_array($file['name'], $customProviders));
    }
}
