<?php

namespace App\Signaturit\UI\Api\Resources\Contracts;

use Illuminate\Http\Resources\Json\JsonResource;

class ContractResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request): array
    {
        return [
            'winner' => $this->resource
        ];
    }

    /**
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function with($request): array
    {
        return [
            'meta' => [ 'success' => true, 'errors' => []],
        ];
    }
}