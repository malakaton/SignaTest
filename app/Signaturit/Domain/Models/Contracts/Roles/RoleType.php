<?php

namespace App\Signaturit\Domain\Models\Contracts\Roles;

use App\Models\BasicModel;

class RoleType extends BasicModel
{
    public const KING = 'K';
    public const NOTARY = 'N';
    public const VALIDATOR = 'V';
    public const EMPTY_SIGNATURE = '#';

    public function getTypes(): array
    {
        return [
            [ 'id' => self::EMPTY_SIGNATURE, 'name' => __("roles.empty"), 'points' => 0],
            [ 'id' => self::VALIDATOR, 'name' => __("roles.validator"), 'points' => 1],
            [ 'id' => self::NOTARY, 'name' => __("roles.notary"), 'points' => 2],
            [ 'id' => self::KING, 'name' => __("roles.king"), 'points' => 5 ]
        ];
    }
}
