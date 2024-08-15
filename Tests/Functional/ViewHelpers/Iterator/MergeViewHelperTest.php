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
use TYPO3\CMS\Fluid\View\StandaloneView;
use TYPO3\TestingFramework\Core\Functional\FunctionalTestCase;

class MergeViewHelperTest extends FunctionalTestCase
{
    private const TEMPLATE_PATH = 'EXT:pvh/Tests/Functional/ViewHelpers/Iterator/Fixtures/Merge.html';

    protected bool $initializeDatabase = false;

    protected array $testExtensionsToLoad = [
        'typo3conf/ext/pvh',
    ];

    public static function renderDataProvider(): array
    {
        return [
            'numeric keys' => [
                [
                    'a' => ['fooa', 'bara'],
                    'b' => ['foob'],
                ],
                ['foob', 'bara'],
            ],
            'string keys' => [
                [
                    'a' => ['a' => 'fooa', 'b' => 'bara'],
                    'b' => ['a' => 'foob', 'c' => 'bazb'],
                ],
                ['a' => 'foob', 'b' => 'bara', 'c' => 'bazb'],
            ],
            'hierarchical numeric keys' => [
                [
                    'a' => [['fooaa', 'baraa'], 'bara'],
                    'b' => [['foobb']],
                ],
                [['foobb', 'baraa'], 'bara'],
            ],
            'hierarchical string keys' => [
                [
                    'a' => ['a' => ['aa' => 'fooaa', 'bb' => 'baraa'], 'b' => 'bara'],
                    'b' => ['a' => ['bb' => 'barbb'], 'c' => 'bazb'],
                ],
                ['a' => ['aa' => 'fooaa', 'bb' => 'barbb'], 'b' => 'bara', 'c' => 'bazb'],
            ],
        ];
    }

    #[DataProvider('renderDataProvider')]
    #[Test]
    public function render(array $arguments, array $expected): void
    {
        $view = GeneralUtility::makeInstance(StandaloneView::class);
        $view->setTemplatePathAndFilename(self::TEMPLATE_PATH);
        $view->assignMultiple($arguments);
        $html = $view->render();
        $xml = new \SimpleXMLElement($html);
        [$node] = $xml->xpath('//span[@id="as"]');
        $actual = json_decode(trim((string)$node), true);
        self::assertSame($expected, $actual);
    }

    #[Test]
    public function supportedUsage(): void
    {
        $arguments = [ 'a' => ['fooa', 'bara'], 'b' => ['foob']];
        $expected = ['foob', 'bara'];
        $view = GeneralUtility::makeInstance(StandaloneView::class);
        $view->setTemplatePathAndFilename(self::TEMPLATE_PATH);
        $view->assignMultiple($arguments);
        $html = $view->render();
        $xml = new \SimpleXMLElement($html);
        foreach (['as', 'direct'] as $id) {
            [$node] = $xml->xpath('//span[@id="' . $id . '"]');
            $actual = json_decode(trim((string)$node), true);
            self::assertSame($expected, $actual);
        }
    }
}
