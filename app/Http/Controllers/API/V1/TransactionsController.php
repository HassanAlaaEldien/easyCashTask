<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use App\Http\Responses\ResponsesInterface;
use App\TransactionServices\ProviderAccessor\Contracts\ProviderFileAccessor;
use App\TransactionServices\ProvidersAdapter;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class TransactionsController extends Controller
{
    /**
     * @var ResponsesInterface $responder
     */
    protected ResponsesInterface $responder;

    /**
     * @var ProviderFileAccessor $providerAccessor
     */
    protected ProviderFileAccessor $providerAccessor;

    /**
     * @param ResponsesInterface $responder
     */
    public function __construct(ResponsesInterface $responder, ProviderFileAccessor $providerFileAccessor)
    {
        $this->responder = $responder;
        $this->providerAccessor = $providerFileAccessor;
    }

    /**
     * Handle the request for listing/filtering transactions.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function index(Request $request): JsonResponse
    {
        $providerAdapter = new ProvidersAdapter($this->providerAccessor, [$request->get('provider')]);

        return $this->responder->respond(['data' => $providerAdapter->listWithFilters()]);
    }
}
