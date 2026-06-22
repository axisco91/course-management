<?php

namespace Tests\Unit;

use App\Http\Controllers\Api\BillController;
use App\Services\AdvisorCommissionService;
use App\Services\UserCommissionService;
use Illuminate\Http\Request;
use PHPUnit\Framework\TestCase;
use ReflectionMethod;

class BillControllerTest extends TestCase
{
    /**
     * @dataProvider normalizeBooleanFilterProvider
     */
    public function testNormalizeBooleanFilterAcceptsCommonUiValues($value, ?int $expected): void
    {
        $controller = new BillController(
            new AdvisorCommissionService(),
            new UserCommissionService()
        );

        $method = new ReflectionMethod($controller, 'normalizeBooleanFilter');
        $method->setAccessible(true);

        $this->assertSame($expected, $method->invoke($controller, $value));
    }

    public function normalizeBooleanFilterProvider(): array
    {
        return [
            'si text' => ['Si', 1],
            'si accent' => ['Sí', 1],
            'no text' => ['No', 0],
            'numeric one string' => ['1', 1],
            'numeric zero string' => ['0', 0],
            'true string' => ['true', 1],
            'false string' => ['false', 0],
            'integer one' => [1, 1],
            'integer zero' => [0, 0],
            'boolean true' => [true, 1],
            'boolean false' => [false, 0],
            'blank string' => ['', null],
            'invalid text' => ['pendiente', null],
            'null value' => [null, null],
        ];
    }

    /**
     * @dataProvider normalizeChargedFilterProvider
     */
    public function testNormalizeChargedFilterSupportsPaidAlias(array $query, ?int $expected): void
    {
        $controller = new BillController(
            new AdvisorCommissionService(),
            new UserCommissionService()
        );

        $method = new ReflectionMethod($controller, 'normalizeChargedFilter');
        $method->setAccessible(true);

        $request = new Request($query);

        $this->assertSame($expected, $method->invoke($controller, $request));
    }

    public function normalizeChargedFilterProvider(): array
    {
        return [
            'paid alias yes' => [['paid' => '1'], 1],
            'paid alias no' => [['paid' => '0'], 0],
            'charged fallback yes' => [['charged' => 'true'], 1],
            'charged fallback no' => [['charged' => 'false'], 0],
            'paid has priority over charged' => [['paid' => '0', 'charged' => '1'], 0],
            'missing filters' => [[], null],
        ];
    }
}
