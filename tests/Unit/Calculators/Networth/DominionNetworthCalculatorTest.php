<?php

namespace OpenDominion\Tests\Unit\Calculators\Networth;

use Mockery as m;
use OpenDominion\Calculators\Networth\DominionNetworthCalculator;
use OpenDominion\Calculators\Networth\UnitNetworthCalculator;
use OpenDominion\Models\Dominion;
use OpenDominion\Models\Race;
use OpenDominion\Models\Unit;
use OpenDominion\Tests\BaseTestCase;

class DominionNetworthCalculatorTest extends BaseTestCase
{
    public function testCalculateMethod()
    {
        $unitNetworthCalculator = m::mock(UnitNetworthCalculator::class);
        $race = m::mock(Race::class);
        $dominion = m::mock(Dominion::class);

        $dominionNetworthCalculator = new DominionNetworthCalculator($unitNetworthCalculator);

        $units = [];
        for ($slot = 1; $slot <= 4; $slot++) {
            $unit = m::mock(Unit::class);

            $unitNetworthCalculator->shouldReceive('calculate')->with($unit)->andReturn(5);
            $dominion->shouldReceive('getAttribute')->with("military_unit{$slot}")->andReturn(100);
            $unit->shouldReceive('getAttribute')->with('slot')->andReturn($slot);

            $units[] = $unit;
        }

        $race->shouldReceive('getAttribute')->with('units')->andReturn($units);

        $dominion->shouldReceive('getAttribute')->with('race')->andReturn($race);
        $dominion->shouldReceive('getAttribute')->with('military_spies')->andReturn(25);
        $dominion->shouldReceive('getAttribute')->with('military_wizards')->andReturn(25);
        $dominion->shouldReceive('getAttribute')->with('military_archmages')->andReturn(0);

        $this->assertEquals(2250, $dominionNetworthCalculator->calculate($dominion));
    }
}
