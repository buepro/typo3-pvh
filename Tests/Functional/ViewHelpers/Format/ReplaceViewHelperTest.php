<?php
declare(strict_types=1);

/*
 * This file is part of the composer package buepro/typo3-pvh.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

namespace Buepro\Pvh\Tests\Functional\ViewHelpers\Format;

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Core\View\ViewFactoryData;
use TYPO3\CMS\Core\View\ViewFactoryInterface;
use TYPO3\TestingFramework\Core\Functional\FunctionalTestCase;

class ReplaceViewHelperTest extends FunctionalTestCase
{
    private const TEMPLATE_PATH = 'EXT:pvh/Tests/Functional/ViewHelpers/Format/Fixtures/Replace.html';

    protected bool $initializeDatabase = false;

    protected static array $arguments = [
        'substring' => '',
        'replacement' => '',
        'caseSensitive' => true
    ];

    protected array $testExtensionsToLoad = [
        'typo3conf/ext/pvh',
    ];

    public static function renderDataProvider(): array
    {
        return [
            'replace all' => [
                'foobarfoobarFOoBaR',
                array_merge(self::$arguments, ['substring' => 'foo', 'replacement' => 'lu']),
                'lubarlubarFOoBaR'
            ],
            'replace not case sensitive' => [
                'foobarfoobarFOoBaR',
                array_merge(self::$arguments, ['substring' => 'foo', 'replacement' => 'lu', 'caseSensitive' => false]),
                'lubarlubarluBaR'
            ],
        ];
    }

    #[DataProvider('renderDataProvider')]
    #[Test]
    public function render(string $content, array $arguments, string $expected): void
    {
        $view = (GeneralUtility::makeInstance(ViewFactoryInterface::class))
            ->create(new ViewFactoryData(
                null,
                null,
                null,
                self::TEMPLATE_PATH
            ));
        $view->assign('content', $content);
        $view->assignMultiple($arguments);
        $html = $view->render();
        $xml = new \SimpleXMLElement($html);
        foreach (['inline-only', 'inline-content', 'tag-only', 'tag-content'] as $tagId) {
            [$node] = $xml->xpath('//span[@id="' . $tagId . '"]');
            $actual = trim((string)$node);
            self::assertSame($expected, $actual);
        }
    }
}
