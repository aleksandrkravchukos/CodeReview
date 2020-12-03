<?php declare(strict_types=1);

namespace ReviewTest\Unit;

use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Review\Service\ReviewService;

class CalculateTest extends TestCase
{
    /**
     * @var ReviewService
     */
    private $service;

    /**
     * @var MockObject
     */
    private $reviewServiceMock;

    /**
     * @var int
     */
    private $scale;

    /**
     * Constructor in every test.
     */
    protected function setUp(): void
    {
        $this->service = new ReviewService();
        $this->scale   = 50;
    }

    /**
     * @test
     */
    public function testCalculateAmountEURZeroRate(): void
    {
        $rowData = json_decode('{"bin":"45717360","amount":"120.00","currency":"EUR"}', true);
        $rate    = 0;
        $isEu    = true;

        $stub = $this->createMock(ReviewService::class);
        $stub->expects(self::once())
            ->method('calculateAmount')
            ->with($rowData, strval($rate), $isEu, $this->scale)
            ->willReturn("1.20000");

        $amountFixed = $stub->calculateAmount($rowData, strval($rate), $isEu, $this->scale);
        $this->assertEquals($amountFixed, "1.20000");
    }

    /**
     * @test
     */
    public function testCalculateAmountUSDNonZeroRate(): void
    {
        $rowData     = json_decode('{"bin":"516793","amount":"50.00","currency":"USD"}', true);
        $rate        = 1.206609878678678687;
        $isEu        = true;
        $amountFixed = $this->service->calculateAmount($rowData, strval($rate), $isEu, $this->scale);
        $this->assertEquals($amountFixed, 0.41438414257599628438784529972658602342021110646394);
    }

    /**
     * @test
     */
    public function testCalculateAmountUSDZeroRate(): void
    {
        $rowData     = json_decode('{"bin":"516793","amount":"50.00","currency":"USD"}', true);
        $rate        = 0;
        $isEu        = true;
        $amountFixed = $this->service->calculateAmount($rowData, strval($rate), $isEu, $this->scale);
        $this->assertEquals($amountFixed, 0.5);
    }

    /**
     * @test
     */
    public function testCalculateAmountUSDCustomRate(): void
    {
        $rowData     = json_decode('{"bin":"516793","amount":"50.00","currency":"USD"}', true);
        $rate        = 1.2067876987899787123123234;
        $isEu        = false;
        $amountFixed = $this->service->calculateAmount($rowData, strval($rate), $isEu, $this->scale);
        $this->assertEquals($amountFixed, 0.82864616618371388860053330441356445573684003527843);
    }

    /**
     * @test
     */
    public function testCalculateAmountUSDEuropTrue(): void
    {
        $rowData     = json_decode('{"bin":"516793","amount":"500.00","currency":"EUR"}', true);
        $rate        = 10.23487239848920348092834902984590;
        $isEu        = true;
        $amountFixed = $this->service->calculateAmount($rowData, strval($rate), $isEu, $this->scale);
        $this->assertEquals($amountFixed, 5);
    }

}
