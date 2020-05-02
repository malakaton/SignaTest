<?php

namespace App\Signaturit\UI\Api\Controllers\Contracts;

use App\Signaturit\Domain\Services\Contracts\ResolveServices;
use App\Signaturit\UI\Api\Requests\Contracts\ContractRequest;
use App\Signaturit\UI\Api\Resources\Contracts\ContractResource;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class ContractController extends BaseController
{
    use AuthorizesRequests;
    use DispatchesJobs;
    use ValidatesRequests;

    /**
     * Resolve a contract and get the winner
     *
     * @param ContractRequest $request
     * @return ContractResource
     */
    public function resolve(ContractRequest $request): ContractResource
    {
        return new ContractResource(
            (new ResolveServices(
                str_split(strtoupper($request->input('plaintiff.signatures'))),
                str_split(strtoupper($request->input('defendant.signatures')))
            ))->getWinner()
        );
    }
}
