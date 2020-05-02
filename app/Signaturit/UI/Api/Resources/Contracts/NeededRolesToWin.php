<?php

namespace App\Signaturit\UI\Api\Resources\Contracts;

use Illuminate\Http\Resources\Json\JsonResource;

class NeededRolesToWin extends JsonResource
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
            'result' => $this->resource
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