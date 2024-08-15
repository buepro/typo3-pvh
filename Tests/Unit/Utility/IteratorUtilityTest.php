<?php
declare(strict_types=1);

/*
 * This file is part of the composer package buepro/typo3-pvh.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

namespace Buepro\Pvh\Tests\Unit\Utility;

use Buepro\Pvh\Utility\IteratorUtility;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use TYPO3\TestingFramework\Core\Unit\UnitTestCase;

class IteratorUtilityTest extends UnitTestCase
{
    public static function arrayFromArrayOrTraversableOrCSVDataProvider(): array
    {
        $traversable = new \ArrayObject(['foo1' => 'foo', 'bar1' => 'bar']);
        return [
            'candidate is integer' => [[], 5, true],
            'candidate is array with associative keys' =>
                [['foo1' => 'foo', 'bar1' => 'bar'], ['foo1' => 'foo', 'bar1' => 'bar'], true],
            'candidate is string' => [['foobar'], 'foobar', true],
            'candidate is csv' => [['foo', 'bar'], 'foo, bar', true],
            'candidate is array' => [['foo', 'bar'], ['foo', 'bar'], true],
            'candidate is traversable' => [['foo1' => 'foo', 'bar1' => 'bar'], $traversable, true],
            'candidate is traversable and keys are not preserved' => [['foo', 'bar'], $traversable, false],
        ];
    }

    /**
     * @param mixed $candidate
     */
    #[DataProvider('arrayFromArrayOrTraversableOrCSVDataProvider')]
    #[Test]
    public function arrayFromArrayOrTraversableOrCSV(array $expected, $candidate, bool $useKeys = true): void
    {
        self::assertSame($expected, IteratorUtility::arrayFromArrayOrTraversableOrCSV($candidate, $useKeys));
    }
}
