<?php
/**
 * webtrees: online genealogy
 * Copyright (C) 2019 webtrees development team
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 * You should have received a copy of the GNU General Public License
 * along with this program. If not, see <http://www.gnu.org/licenses/>.
 */
declare(strict_types=1);

namespace Fisharebest\Webtrees\Census;

/**
 * Test harness for the class CensusOfWales
 */
class CensusOfWalesTest extends \Fisharebest\Webtrees\TestCase
{
    /**
     * Test the census place
     *
     * @covers \Fisharebest\Webtrees\Census\CensusOfWales
     *
     * @return void
     */
    public function testPlace(): void
    {
        $census = new CensusOfWales();

        $this->assertSame('Wales', $census->censusPlace());
    }

    /**
     * Test the census dates
     *
     * @covers \Fisharebest\Webtrees\Census\CensusOfWales
     *
     * @return void
     */
    public function testAllDates(): void
    {
        $census = new CensusOfWales();

        $census_dates = $census->allCensusDates();

        $this->assertCount(9, $census_dates);
        $this->assertInstanceOf(\Fisharebest\Webtrees\Census\CensusOfWales1841::class, $census_dates[0]);
        $this->assertInstanceOf(\Fisharebest\Webtrees\Census\CensusOfWales1851::class, $census_dates[1]);
        $this->assertInstanceOf(\Fisharebest\Webtrees\Census\CensusOfWales1861::class, $census_dates[2]);
        $this->assertInstanceOf(\Fisharebest\Webtrees\Census\CensusOfWales1871::class, $census_dates[3]);
        $this->assertInstanceOf(\Fisharebest\Webtrees\Census\CensusOfWales1881::class, $census_dates[4]);
        $this->assertInstanceOf(\Fisharebest\Webtrees\Census\CensusOfWales1891::class, $census_dates[5]);
        $this->assertInstanceOf(\Fisharebest\Webtrees\Census\CensusOfWales1901::class, $census_dates[6]);
        $this->assertInstanceOf(\Fisharebest\Webtrees\Census\CensusOfWales1911::class, $census_dates[7]);
        $this->assertInstanceOf(\Fisharebest\Webtrees\Census\RegisterOfWales1939::class, $census_dates[8]);
    }
}
