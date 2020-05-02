<?php

namespace App\Signaturit\UI\Api\Requests\Contracts;

use App\Signaturit\Domain\Rules\Contracts\ContractRule;
use Illuminate\Foundation\Http\FormRequest;

class ContractRequest extends FormRequest
{

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            //Plaintiff
            'plaintiff.signatures' => [
                'required',
                'string',
                new ContractRule()
            ],
            //Defendant
            'defendant.signatures' => [
                'required',
                'string',
                new ContractRule()
            ]
        ];
    }
}
