<?php declare(strict_types=1);

namespace ReviewTest\Unit;

use PHPUnit\Framework\TestCase;
use Review\Service\ReviewService;

class CalculateTest extends TestCase
{
    /**
     * @var ReviewService
     */
    private $service;

    /**
     * Constructor in every test.
     */
    protected function setUp(): void
    {
        $this->service = new ReviewService();
    }

    /**
     * @test
     */
    public function testCalculateAmountEURZeroRate(): void
    {
        $rowData = json_decode('{"bin":"45717360","amount":"120.00","currency":"EUR"}', true);
        $rate = 0;
        $isEu = true;
        $amountFixed = $this->service->calculateAmount($rowData, strval($rate), $isEu);
        $this->assertEquals($amountFixed, 1.20000);
    }

    /**
     * @test
     */
    public function testCalculateAmountUSDNonZeroRate(): void
    {
        $rowData = json_decode('{"bin":"516793","amount":"50.00","currency":"USD"}', true);
        $rate = 1.206609878678678687;
        $isEu = true;
        $amountFixed = $this->service->calculateAmount($rowData, strval($rate), $isEu);
        $this->assertEquals($amountFixed, 0.4143841425759962843878452997265860234202);
    }

    /**
     * @test
     */
    public function testCalculateAmountUSDZeroRate(): void
    {
        $rowData = json_decode('{"bin":"516793","amount":"50.00","currency":"USD"}', true);
        $rate = 0;
        $isEu = true;
        $amountFixed = $this->service->calculateAmount($rowData, strval($rate), $isEu);
        $this->assertEquals($amountFixed, 0.5);
    }

    /**
     * @test
     */
    public function testCalculateAmountUSDCustomRate(): void
    {
        $rowData = json_decode('{"bin":"516793","amount":"50.00","currency":"USD"}', true);
        $rate = 1.2067876987899787123123234;
        $isEu = false;
        $amountFixed = $this->service->calculateAmount($rowData, strval($rate), $isEu);
        $this->assertEquals($amountFixed, 0.8286461661837138886005333044135644557368);
    }

    /**
     * @test
     */
    public function testCalculateAmountUSDEuropTrue(): void
    {
        $rowData = json_decode('{"bin":"516793","amount":"500.00","currency":"EUR"}', true);
        $rate = 10.23487239848920348092834902984590;
        $isEu = true;
        $amountFixed = $this->service->calculateAmount($rowData, strval($rate), $isEu);
        $this->assertEquals($amountFixed, 5);
    }

}
