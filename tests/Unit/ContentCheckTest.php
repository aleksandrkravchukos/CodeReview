<?php declare(strict_types=1);

namespace ReviewTest\Unit;

use GuzzleHttp\Client;
use PHPUnit\Framework\TestCase;
use Review\Service\ReviewService;

class ContentCheckTest extends TestCase
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
        $this->service = new ReviewService(new Client);
    }

    /**
     * @test
     */
    public function testIsJsonString(): void
    {
        $validContent    = '{"bin":"45717360","amount":"100.00","currency":"EUR"}';
        $notValidContent = '{"bin":"45717360", amount":"100.00","currency":"EUR"};';

        $isValidContent = $this->service->isJsonString($validContent);
        $this->assertEquals($isValidContent, true);

        $isValidContent = $this->service->isJsonString($notValidContent);
        $this->assertEquals($isValidContent, false);
    }

    /**
     * @test
     */
    public function testValidateRowData(): void
    {
        $validData = $this->service->validate(json_decode('{"bin":"45717360","amount":"100.00","currency":"EUR"}', true));
        $this->assertEquals($validData, true);

        $notValidData = $this->service->validate(json_decode('{"bins":"45717360","amounts":"100.00","currency":"EUR"}', true));

        $this->assertEquals($notValidData, false);
    }

    /**
     * @test
     */
    public function testisEuropeanCode(): void
    {
        $alpha2 = $this->service->isEuropeanCode('UA');
        $this->assertEquals($alpha2, false);

        $alpha2 = $this->service->isEuropeanCode('BY');
        $this->assertEquals($alpha2, false);

        $alpha2 = $this->service->isEuropeanCode('IT');
        $this->assertEquals($alpha2, true);
    }
}
