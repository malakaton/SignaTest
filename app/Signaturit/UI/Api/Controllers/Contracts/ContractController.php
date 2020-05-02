<?php

namespace App\Signaturit\UI\Api\Controllers\Contracts;

use App\Signaturit\Domain\Services\Contracts\ResolveServices;
use App\Signaturit\UI\Api\Requests\Contracts\ContractRequest;
use App\Signaturit\UI\Api\Resources\Contracts\NeededRolesToWin;
use App\Signaturit\UI\Api\Resources\Contracts\WinnerResource;
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
     * Resolve a contract and get the winner of lawsuit
     *
     * @param ContractRequest $request
     * @return WinnerResource
     */
    public function resolve(ContractRequest $request): WinnerResource
    {
        return new WinnerResource(
            [
                'plaintiff' => strtoupper($request->input('plaintiff.signatures')),
                'defendant' => strtoupper($request->input('defendant.signatures')),
                'winner' => (new ResolveServices(
                    str_split(strtoupper($request->input('plaintiff.signatures'))),
                    str_split(strtoupper($request->input('defendant.signatures')))
                ))->getWinner()
            ]
        );
    }

    /**
     * Calculate and get the minimum points necessaries to win the trial, when exist empty signature on contract
     *
     * @param ContractRequest $request
     * @return NeededRolesToWin
     */
    public function getMinPointsToWin(ContractRequest $request): NeededRolesToWin
    {
        return new NeededRolesToWin(
            (new ResolveServices(
                str_split(strtoupper($request->input('plaintiff.signatures'))),
                str_split(strtoupper($request->input('defendant.signatures')))
            ))->calculatePointsForWinner()
        );
    }
}
