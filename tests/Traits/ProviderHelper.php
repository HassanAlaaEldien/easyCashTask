<?php

namespace Tests\Traits;

use Faker\Factory;

trait ProviderHelper
{
    /**
     * @param $providers
     * @param $itemsPerProvider
     * @param array $amountRange
     * @param null $status
     * @param null $currency
     * @return void
     */
    private function createProviders($providers, $itemsPerProvider, $amountRange = [], $status = null, $currency = null)
    {
        foreach ($providers as $provider) {
            $jsonFile = fopen(storage_path("mockingFiles/Data{$provider}.json"), "w");
            fwrite(
                $jsonFile,
                $this->getJsonFileContent($itemsPerProvider, $amountRange, $currency, $status, $provider)
            );
            fclose($jsonFile);
        }
    }

    /**
     * @return void
     */
    private function deleteMockingFiles(): void
    {
        // Delete all old testing files.
        $files = glob(storage_path("mockingFiles/*"));
        foreach ($files as $file) {
            if (is_file($file)) {
                unlink($file);
            }
        }
        // Then, Create Mocking Folder If It Doesn't Exist.
        if (!file_exists(storage_path("mockingFiles"))) {
            mkdir(storage_path("mockingFiles"), 0777, true);
        }
    }

    /**
     * @param array $amountRange
     * @param mixed $currency
     * @param mixed $status
     * @return array[]
     */
    private function getProvidersFileStructure(array $amountRange, mixed $currency, mixed $status): array
    {
        $faker = Factory::create();

        $providersStructure = [
            'ProviderW' => [
                "amount" => $faker->randomElement($amountRange ?: range(10, 100)),
                "currency" => ($currency ?: $faker->currencyCode()),
                "phone" => $faker->phoneNumber(),
                "status" => ($status ?: $faker->randomElement(['done', 'wait', 'nope'])),
                "created_at" => $faker->date,
                "id" => $faker->randomElement(range(2000, 10000))
            ],
            'ProviderX' => [
                "transactionAmount" => $faker->randomElement($amountRange ?: range(10, 100)),
                "Currency" => ($currency ?: $faker->currencyCode()),
                "senderPhone" => $faker->phoneNumber(),
                "transactionStatus" => ($status ?: $faker->randomElement([1, 2, 3])),
                "transactionDate" => $faker->date,
                "transactionIdentification" => $faker->uuid()

            ],
            'ProviderY' => [
                "amount" => $faker->randomElement($amountRange ?: range(10, 100)),
                "currency" => ($currency ?: $faker->currencyCode()),
                "phone" => $faker->phoneNumber(),
                "status" => ($status ?: $faker->randomElement([100, 200, 300])),
                "created_at" => $faker->date,
                "id" => $faker->uuid()
            ]
        ];

        return $providersStructure;
    }

    /**
     * @param $itemsPerProvider
     * @param array $amountRange
     * @param mixed $currency
     * @param mixed $status
     * @param string $provider
     * @return string
     */
    private function getJsonFileContent($itemsPerProvider, array $amountRange, mixed $currency, mixed $status, string $provider): string
    {
        $content = '[';
        for ($item = 0; $item < $itemsPerProvider; $item++) {
            $content .= json_encode(
                    $this->getProvidersFileStructure($amountRange, $currency, $status)[$provider]
                ) . ($item + 1 == $itemsPerProvider ? '' : ',');
        }
        $content .= ']';
        return $content;
    }
}
