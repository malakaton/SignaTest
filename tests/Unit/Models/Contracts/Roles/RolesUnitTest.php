<?php

namespace Tests\Unit\Models\Contracts\Roles;

use App\Signaturit\Domain\Models\Contracts\Roles\RoleType;
use Tests\TestCase;

class RolesUnitTest extends TestCase
{
    /**
     * @test
     */
    public function get_all_roles(): void
    {
        $this->assertCount(
            count(RoleType::get()),
            RoleType::getValuesByAttribute('id')
        );
    }

    /**
     * @test
     */
    public function undefined_attribute_roles(): void
    {
        $this->assertNull(RoleType::get('undefined', RoleType::KING));
    }
}