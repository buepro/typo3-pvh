<?php
declare(strict_types=1);

/*
 * This file is part of the composer package buepro/typo3-pvh.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

namespace Buepro\Pvh\Tests\Functional\ViewHelpers\Iterator;

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Core\View\ViewFactoryData;
use TYPO3\CMS\Core\View\ViewFactoryInterface;
use TYPO3\CMS\Core\View\ViewInterface;
use TYPO3\TestingFramework\Core\Functional\FunctionalTestCase;

class RandomViewHelperTest extends FunctionalTestCase
{
    private const TEMPLATE_PATH = 'EXT:pvh/Tests/Functional/ViewHelpers/Iterator/Fixtures/Random.html';

    protected bool $initializeDatabase = false;

    protected array $testExtensionsToLoad = [
        'typo3conf/ext/pvh',
    ];

    private static array $subject = [1, 2, 3, 4, 5, 6, 7, 8, 9, 10];

    /**
     * @var array
     */
    private static $arguments = [
        'count' => 1,
        'shuffle' => true,
        'as' => 'as',
    ];

    private function getView(array $subject, array $arguments): ViewInterface
    {
        $view = (GeneralUtility::makeInstance(ViewFactoryInterface::class))
            ->create(new ViewFactoryData(
                null,
                null,
                null,
                self::TEMPLATE_PATH
            ));
        $view->assign('subject', $subject);
        $view->assignMultiple($arguments);
        return $view;
    }

    private function testActual(array $arguments, array $actual): void
    {
        self::assertCount($arguments['count'], $actual);
        foreach ($actual as $item) {
            // An item is contained once in the result
            self::assertCount(1, array_keys($actual, $item, true));
        }
    }

    public static function renderDataProvider(): array
    {
        return [
            'one item' => [self::$subject, self::$arguments],
            'three items' => [self::$subject, array_merge(self::$arguments, ['count' => 3])],
        ];
    }

    #[DataProvider('renderDataProvider')]
    #[Test]
    public function render(array $subject, array $arguments): void
    {
        $html = $this->getView($subject, $arguments)->render();
        $xml = new \SimpleXMLElement($html);
        [$node] = $xml->xpath('//span[@id="as"]');
        $actual = json_decode(trim((string)$node));
        self::assertIsArray($actual);
        $this->testActual($arguments, $actual);
    }

    #[Test]
    public function supportedUsage(): void
    {
        $arguments = array_merge(self::$arguments, ['count' => 3]);
        $html = $this->getView(self::$subject, $arguments)->render();
        $xml = new \SimpleXMLElement($html);
        foreach (['as', 'as-child', 'direct'] as $id) {
            [$node] = $xml->xpath('//span[@id="' . $id . '"]');
            $actual = json_decode(trim((string)$node));
            self::assertIsArray($actual);
            $this->testActual($arguments, $actual);
        }
    }

    #[Test]
    public function subjectOnly(): void
    {
        $html = $this->getView(self::$subject, self::$arguments)->render();
        $xml = new \SimpleXMLElement($html);
        [$node] = $xml->xpath('//span[@id="minimal"]');
        $actual = json_decode(trim((string)$node));
        self::assertIsArray($actual);
        $this->testActual(self::$arguments, $actual);
    }
}
