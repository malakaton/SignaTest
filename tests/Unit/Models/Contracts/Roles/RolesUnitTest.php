<?php

namespace Tests\Unit;

use App\Signaturit\Domain\Models\Contracts\Roles\RoleType;
use Tests\TestCase;

class RolesUnitTest extends TestCase
{
    /**
     * @test
     */
    public function get_all_roles()
    {
        $this->assertCount(
            count(RoleType::get()),
            RoleType::getValuesByAttribute('id')
        );
    }

    /**
     * @test
     */
    public function undefined_attribute_roles()
    {
        $this->assertNull(RoleType::get('undefined', RoleType::KING));
    }
}