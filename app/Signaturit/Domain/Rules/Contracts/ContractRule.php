<?php

namespace App\Signaturit\Domain\Rules\Contracts;

use App\Signaturit\Domain\Models\Contracts\Roles\RoleType;
use Illuminate\Contracts\Validation\Rule;

class ContractRule implements Rule
{
    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value): bool
    {
        $countEmptySigns = 0;

        foreach (str_split($value) as $signature) {
            $countEmptySigns += ($signature === RoleType::EMPTY_SIGNATURE) ? 1 : 0;

            if (($countEmptySigns > 1) || !RoleType::get('id', strtoupper($signature))) {
                return false;
            }
        }

        return true;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message(): string
    {
        return __('validation.signature');
    }
}
