<?php declare(strict_types=1);

namespace ReviewTest\Unit;

use PHPUnit\Framework\TestCase;
use Review\Exception\ReviewException;
use Review\Service\ReviewService;

/**
 * Class InvestmentTest
 */
class ReviewServiceTest extends TestCase
{

    /**
     * @var ReviewService
     */
    private $service;

    protected function setUp(): void
    {
        $this->service = new ReviewService();
    }

    /**
     * @test
     */
    public function someTest(): void
    {

    }
}