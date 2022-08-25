<?php

namespace Tests;

use App\TransactionServices\ProviderAccessor\Accessors\MockingProviders;
use App\TransactionServices\ProviderAccessor\Contracts\ProviderFileAccessor;
use Tests\Traits\ProviderHelper;

class ListTransactionsTest extends TestCase
{
    use ProviderHelper;

    /**
     * A data provider for filtering by specific provider.
     *
     * @return array
     */
    public function providersFields(): array
    {
        return [
            [[3, [], 9]],
            [[3, ['provider' => 'ProviderX'], 3]],
            [[3, ['provider' => 'WrongProvider'], 0]]
        ];
    }

    /**
     * A data provider for filtering by specific amount.
     *
     * @return array
     */
    public function amountsFields(): array
    {
        return [
            [[10, 79, 5]],
            [[51, 79, 0]],
            [[10, 200, 6]],
            [[0, 50, 5]],
        ];
    }

    /**
     * A data provider for filtering by specific currency.
     *
     * @return array
     */
    public function currencyFields(): array
    {
        return [
            [['EGP', 2]],
            [['USD', 1]],
            [['AED', 0]]
        ];
    }

    /**
     * A data provider for filtering by combination of attributes.
     *
     * @return array
     */
    public function allAttributesFields(): array
    {
        return [
            [['EGP', 'ProviderW', 100, 400, 'paid', 1]],
            [['EGP', 'ProviderW', 100, 300, 'pending', 0]]
        ];
    }

    /**
     * Sends a get request to the list transactions endpoint.
     *
     * @param array $parameters
     * @return ListTransactionsTest
     */
    private function hitListTransactionsEndpoint($parameters = []): ListTransactionsTest
    {
        return $this->get(route('transactions.index', $parameters));
    }

    /**
     * @test
     *
     * @dataProvider providersFields
     *
     * @param $field
     */
    function a_non_authorized_user_can_list_transactions_and_filter_by_provider($field)
    {
        // At first, we have to clear any old test files then create our providers fake data.
        $this->deleteMockingFiles();
        $this->createProviders(['ProviderW', 'ProviderX', 'ProviderY'], $field[0]);

        // We have to mock our data source before hitting our endpoint.
        $this->app->bind(ProviderFileAccessor::class, MockingProviders::class);

        // Then we will hit list transactions endpoint.
        $response = $this->hitListTransactionsEndpoint($field[1]);

        // After that, we have to check that response returns 200.
        $response->assertResponseOk();

        // Also, check our json structure is unified.
        $response->seeJsonStructure([
            'data' => [
                '*' => ['id', 'amount', 'currency', 'phone', 'status', 'created_at']
            ],
            'status_code'
        ]);

        // Finally, we have to make sure that our transactions' response count equals pre-created transactions' count.
        $this->assertCount($field[2], $response->response->decodeResponseJson()['data']);
    }

    /**
     * @test
     */
    function a_non_authorized_user_can_list_transactions_and_filter_by_status_code()
    {
        // At first, we have to clear any old test files then create our providers fake data.
        $this->deleteMockingFiles();
        $this->createProviders(['ProviderW'], 1, [], 'done');
        $this->createProviders(['ProviderX'], 1, [], 2);
        $this->createProviders(['ProviderY'], 1, [], 100);

        // We have to mock our data source before hitting our endpoint.
        $this->app->bind(ProviderFileAccessor::class, MockingProviders::class);

        // Then we will hit list transactions endpoint.
        $response = $this->hitListTransactionsEndpoint(['statusCode' => 'paid']);

        // After that, we have to check that response returns 200.
        $response->assertResponseOk();

        // Also, check our json structure is unified.
        $response->seeJsonStructure([
            'data' => [
                '*' => ['id', 'amount', 'currency', 'phone', 'status', 'created_at']
            ],
            'status_code'
        ]);

        // Finally, we have to make sure that our transactions' response count equals pre-created transactions' count.
        $this->assertCount(2, $response->response->decodeResponseJson()['data']);
    }

    /**
     * @test
     *
     * @dataProvider amountsFields
     *
     * @param $field
     */
    function a_non_authorized_user_can_list_transactions_and_filter_by_amount($field)
    {
        // At first, we have to clear any old test files then create our providers fake data.
        $this->deleteMockingFiles();
        $this->createProviders(['ProviderW'], 3, range(10, 30));
        $this->createProviders(['ProviderX'], 2, range(10, 50));
        $this->createProviders(['ProviderY'], 1, range(80, 160));

        // We have to mock our data source before hitting our endpoint.
        $this->app->bind(ProviderFileAccessor::class, MockingProviders::class);

        // Then we will hit list transactions endpoint.
        $response = $this->hitListTransactionsEndpoint(['amountMin' => $field[0], 'amountMax' => $field[1]]);

        // After that, we have to check that response returns 200.
        $response->assertResponseOk();

        // Also, check our json structure is unified.
        $response->seeJsonStructure([
            'data' => [
                '*' => ['id', 'amount', 'currency', 'phone', 'status', 'created_at']
            ],
            'status_code'
        ]);

        // Finally, we have to make sure that our transactions' response count equals pre-created transactions' count.
        $this->assertCount($field[2], $response->response->decodeResponseJson()['data']);
    }

    /**
     * @test
     *
     * @dataProvider currencyFields
     *
     * @param $field
     */
    function a_non_authorized_user_can_list_transactions_and_filter_by_currency($field)
    {
        // At first, we have to clear any old test files then create our providers fake data.
        $this->deleteMockingFiles();
        $this->createProviders(['ProviderW'], 1, [], null, 'EGP');
        $this->createProviders(['ProviderX'], 1, [], null, 'EGP');
        $this->createProviders(['ProviderY'], 1, [], null, 'USD');

        // We have to mock our data source before hitting our endpoint.
        $this->app->bind(ProviderFileAccessor::class, MockingProviders::class);

        // Then we will hit list transactions endpoint.
        $response = $this->hitListTransactionsEndpoint(['currency' => $field[0]]);

        // After that, we have to check that response returns 200.
        $response->assertResponseOk();

        // Also, check our json structure is unified.
        $response->seeJsonStructure([
            'data' => [
                '*' => ['id', 'amount', 'currency', 'phone', 'status', 'created_at']
            ],
            'status_code'
        ]);

        // Finally, we have to make sure that our transactions' response count equals pre-created transactions' count.
        $this->assertCount($field[1], $response->response->decodeResponseJson()['data']);
    }

    /**
     * @test
     *
     * @dataProvider allAttributesFields
     *
     * @param $field
     */
    function a_non_authorized_user_can_list_transactions_and_filter_by_all_attributes($field)
    {
        // At first, we have to clear any old test files then create our providers fake data.
        $this->deleteMockingFiles();
        $this->createProviders(['ProviderW'], 1, range(100, 400), 'done', 'EGP');
        $this->createProviders(['ProviderX'], 1, [], null, 'EGP');
        $this->createProviders(['ProviderY'], 1, [], null, 'USD');

        // We have to mock our data source before hitting our endpoint.
        $this->app->bind(ProviderFileAccessor::class, MockingProviders::class);

        // Then we will hit list transactions endpoint.
        $response = $this->hitListTransactionsEndpoint(
            ['currency' => $field[0], 'provider' => $field[1], 'amountMin' => $field[2], 'amountMax' => $field[3], 'statusCode' => $field[4]]
        );

        // After that, we have to check that response returns 200.
        $response->assertResponseOk();

        // Also, check our json structure is unified.
        $response->seeJsonStructure([
            'data' => [
                '*' => ['id', 'amount', 'currency', 'phone', 'status', 'created_at']
            ],
            'status_code'
        ]);

        // Finally, we have to make sure that our transactions' response count equals pre-created transactions' count.
        $this->assertCount($field[5], $response->response->decodeResponseJson()['data']);
    }
}
