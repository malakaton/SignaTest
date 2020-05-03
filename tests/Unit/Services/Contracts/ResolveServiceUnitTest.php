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
        $resolveService = new ResolveServices(
            $this->params['plaintiff']['signatures'],
            $this->params['defendant']['signatures']
        );
        $serviceReflection = new \ReflectionClass($resolveService);

        $property = $serviceReflection->getProperty('loserRoles');
        $property->setAccessible(true);
        $property->setValue($resolveService, $this->params['plaintiff']['signatures']);

        $method = $serviceReflection->getMethod('getClosestPoint');
        $method->setAccessible(true);
        $result = $method->invoke(
            $resolveService,
            4
        );

        $this->assertEquals($result, RoleType::get('id', RoleType::NOTARY)['points']);
    }

    /**
     * @test
     */
    public function get_total_points_and_how_many_points_to_win(): void
    {
        $this->params['plaintiff']['signatures'] = ['N', '#', 'V'];
        $this->params['defendant']['signatures'] = ['N', 'V', 'V'];
        $serviceReflection = new \ReflectionClass(ResolveServices::class);

        $method = $serviceReflection->getMethod('getTotalPoints');
        $method->setAccessible(true);
        $pointsPlaintiff = $method->invoke(
            new ResolveServices($this->params['plaintiff']['signatures'], $this->params['defendant']['signatures']),
            $this->params['plaintiff']['signatures']
        );
        $pointsDefendant = $method->invoke(
            new ResolveServices($this->params['plaintiff']['signatures'], $this->params['defendant']['signatures']),
            $this->params['defendant']['signatures']
        );

        $this->assertEquals(4, $pointsDefendant);
        $this->assertEquals(3, $pointsPlaintiff);

        $methodWinner = $serviceReflection->getMethod('findRoleTypesToWin');
        $methodWinner->setAccessible(true);
        $winner = $methodWinner->invoke(
            new ResolveServices($this->params['plaintiff']['signatures'], $this->params['defendant']['signatures']),
            ($pointsDefendant - $pointsPlaintiff)
        );

        $resolveServices = new ResolveServices(
            $this->params['plaintiff']['signatures'],
            $this->params['defendant']['signatures']
        );

        $this->assertEquals(['N'], $winner);
        $this->assertEquals('N', $resolveServices->calculatePointsForWinner());
    }

    /**
     * @test
     */
    public function get_winner_of_lawsuit_if_king_appears(): void
    {
        $this->params['plaintiff']['signatures'] = ['K', 'K', 'V'];
        $this->params['defendant']['signatures'] = ['N', 'N', 'N', 'N', 'N', 'V'];

        $resolveServices = new ResolveServices(
            $this->params['plaintiff']['signatures'],
            $this->params['defendant']['signatures']
        );

        $this->assertEquals(ResolveServices::DEFENDANT_WINS, $resolveServices->getWinner());
    }

    /**
     * For example if you need 8 points to win to the opposite, the result will be KNV, that's not correct
     * because when king signature appears, the V signature is null. You will receive the follow result: NNNN
     *
     * @test
     */
    public function unset_king_from_roles_to_get_min_points_to_win_validator(): void
    {
        $this->params['plaintiff']['signatures'] = ['N', '#', 'V'];
        $this->params['defendant']['signatures'] = ['K', 'K', 'K'];

        $serviceReflection = new \ReflectionClass(ResolveServices::class);

        $method = $serviceReflection->getMethod('unsetKingFromRolesValidator');
        $method->setAccessible(true);
        $roles = $method->invoke(
            new ResolveServices($this->params['plaintiff']['signatures'], $this->params['defendant']['signatures']),
            8
        );
        $expected = RoleType::getValuesByAttribute('points');
        unset($expected[3]);

        $this->assertEquals($expected, $roles);
    }

    /**
     * @test
     */
    public function unset_king_from_roles_where_exist_validator_signature_on_loser_validator(): void
    {
        $this->params['plaintiff']['signatures'] = ['N', '#', 'V'];
        $this->params['defendant']['signatures'] = ['N', 'N', 'N', 'V'];

        $resolveService = new ResolveServices(
            $this->params['plaintiff']['signatures'],
            $this->params['defendant']['signatures']
        );
        $serviceReflection = new \ReflectionClass($resolveService);

        $propertyLoser = $serviceReflection->getProperty('loserRoles');
        $propertyLoser->setAccessible(true);
        $propertyLoser->setValue($resolveService, $this->params['plaintiff']['signatures']);
        $method = $serviceReflection->getMethod('findRoleTypesToWin');
        $method->setAccessible(true);
        $roles = $method->invoke(
            $resolveService,
            4
        );

        $this->assertEquals($resolveService->calculatePointsForWinner(), implode($roles));
    }

    /**
     * @test
     */
    public function check_has_signature(): void
    {
        $serviceReflection = new \ReflectionClass(ResolveServices::class);

        $method = $serviceReflection->getMethod('checkHasSignature');
        $method->setAccessible(true);
        $result = $method->invoke(
            new ResolveServices($this->params['plaintiff']['signatures'], $this->params['defendant']['signatures']),
            $this->params['plaintiff']['signatures'],
            'N'
        );

        $this->assertTrue($result);
    }
}