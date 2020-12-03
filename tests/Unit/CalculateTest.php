<?php

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
    public function testCalculateAmount()
    {
        $rowData = json_decode('{"bin":"45717360","amount":"120.00","currency":"EUR"}', true);
        $rate = 0;
        $isEu = true;
        $amountFixed = $this->service->calculateAmount($rowData, $rate, $isEu);
        $this->assertEquals($amountFixed, 1.2);

        $rowData = json_decode('{"bin":"516793","amount":"50.00","currency":"USD"}', true);
        $rate = 1.2066;
        $isEu = true;
        $amountFixed = $this->service->calculateAmount($rowData, $rate, $isEu);
        $this->assertEquals($amountFixed, 0.41438753522294053);

        $rowData = json_decode('{"bin":"516793","amount":"50.00","currency":"USD"}', true);
        $rate = 1.2067;
        $isEu = true;
        $amountFixed = $this->service->calculateAmount($rowData, $rate, $isEu);
        $this->assertEquals($amountFixed, 0.4143531946631308);

        $rowData = json_decode('{"bin":"516793","amount":"50.00","currency":"USD"}', true);
        $rate = 10;
        $isEu = false;
        $amountFixed = $this->service->calculateAmount($rowData, $rate, $isEu);
        $this->assertEquals($amountFixed, 0.1);

        $rowData = json_decode('{"bin":"516793","amount":"50.00","currency":"KAD"}', true);
        $rate = 1.2067;
        $isEu = false;
        $amountFixed = $this->service->calculateAmount($rowData, $rate, $isEu);
        $this->assertEquals($amountFixed, 0.8287063893262616);

        $rowData = json_decode('{"bin":"516793","amount":"50.00","currency":"USD"}', true);
        $rate = 0;
        $isEu = false;
        $amountFixed = $this->service->calculateAmount($rowData, $rate, $isEu);
        $this->assertEquals($amountFixed, 1.0);
    }
}