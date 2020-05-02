<?php

namespace Tests\Unit\Services\Contracts;

use App\Signaturit\Domain\Models\Contracts\Roles\RoleType;
use App\Signaturit\Domain\Services\Contracts\ResolveServices;
use Tests\TestCase;

class ResolveServiceUnitTest extends TestCase
{
    protected $mockedService;
    protected $params;

    public function setUp(): void
    {
        parent::setUp();

        $this->params['plaintiff']['signatures'] = ['N', 'N', 'V'];
        $this->params['defendant']['signatures'] = ['V', 'V', 'V'];
        $this->mockedService = $this->createMock(ResolveServices::class);
    }
    /**
     * Dummy test to interact with mockery
     *
     * @test
     */
    public function set_plaintiff_as_winner(): void
    {
        $this->mockedService->expects($this->once())
            ->method('getWinner')
            ->willReturn(ResolveServices::PLAINTIFF_WINS);

        $this->assertEquals(ResolveServices::PLAINTIFF_WINS, $this->mockedService->getWinner());
    }

    /**
     * @test
     */
    public function get_closest_point_from_value(): void
    {
        $serviceReflection = new \ReflectionClass(ResolveServices::class);

        $method = $serviceReflection->getMethod('getClosestPoint');
        $method->setAccessible(true);
        $result = $method->invoke(
            new ResolveServices($this->params['plaintiff']['signatures'], $this->params['defendant']['signatures']),
            RoleType::get('id', RoleType::KING)['points']
        );

        $this->assertEquals($result, RoleType::get('id', RoleType::KING)['points']);
    }
}