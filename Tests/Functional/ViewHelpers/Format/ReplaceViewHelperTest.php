<?php
declare(strict_types=1);

/*
 * This file is part of the composer package buepro/typo3-pvh.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

namespace Buepro\Pvh\Tests\Functional\ViewHelpers\Format;

use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Fluid\View\StandaloneView;
use TYPO3\TestingFramework\Core\Functional\FunctionalTestCase;

class ReplaceViewHelperTest extends FunctionalTestCase
{
    private const TEMPLATE_PATH = 'EXT:pvh/Tests/Functional/ViewHelpers/Format/Fixtures/Replace.html';

    /**
     * @var bool Speed up this test case, it needs no database
     */
    protected $initializeDatabase = false;

    /**
     * @var array
     */
    protected $arguments = [
        'substring' => '',
        'replacement' => '',
        'caseSensitive' => true
    ];

    /**
     * @var array
     */
    protected $testExtensionsToLoad = [
        'typo3conf/ext/pvh',
    ];

    public function renderDataProvider(): array
    {
        return [
            'replace all' => [
                'foobarfoobarFOoBaR',
                array_merge($this->arguments, ['substring' => 'foo', 'replacement' => 'lu']),
                'lubarlubarFOoBaR'
            ],
            'replace not case sensitive' => [
                'foobarfoobarFOoBaR',
                array_merge($this->arguments, ['substring' => 'foo', 'replacement' => 'lu', 'caseSensitive' => false]),
                'lubarlubarluBaR'
            ],
        ];
    }

    /**
     * @dataProvider renderDataProvider
     * @test
     */
    public function render(string $content, array $arguments, string $expected): void
    {
        $view = GeneralUtility::makeInstance(StandaloneView::class);
        $view->setTemplatePathAndFilename(self::TEMPLATE_PATH);
        $view->assign('content', $content);
        $view->assignMultiple($arguments);
        $html = $view->render();
        $xml = new \SimpleXMLElement($html);
        [$node] = $xml->xpath('//span[@id="replaced"]');
        $actual = trim((string)$node);
        self::assertSame($expected, $actual);
    }
}
