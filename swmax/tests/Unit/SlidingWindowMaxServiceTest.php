<?php

declare(strict_types=1);

namespace Tests\Unit;

use App\Services\SlidingWindowMaxService;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

final class SlidingWindowMaxServiceTest extends TestCase
{
    private SlidingWindowMaxService $svc;

    protected function setUp(): void
    {
        parent::setUp();
        $this->svc = new SlidingWindowMaxService();
    }

    public function test_basic_case_ints(): void
    {
        $arr = [1, 3, -1, -3, 5, 3, 6, 7];
        $k = 3;
        $this->assertSame([3.0, 3.0, 5.0, 5.0, 6.0, 7.0], $this->svc->compute($arr, $k));
    }

    public function test_with_floats(): void
    {
        $arr = [1.0, 3.0, -1.0, -3.0, 5.0, 3.0, 6.0, 7.2];
        $k = 3;
        $this->assertSame([3.0, 3.0, 5.0, 5.0, 6.0, 7.2], $this->svc->compute($arr, $k));
    }

    public function test_k_equals_one(): void
    {
        $arr = [4.5, -2.25, 7.0];
        $k = 1;
        $this->assertSame([4.5, -2.25, 7.0], $this->svc->compute($arr, $k));
    }

    public function test_invalid_k_zero(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->svc->compute([1.0, 2.0, 3.0], 0);
    }

    public function test_invalid_k_greater_than_n(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->svc->compute([1.0, 2.0], 3);
    }
}