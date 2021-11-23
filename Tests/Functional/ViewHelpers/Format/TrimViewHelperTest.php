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

class TrimViewHelperTest extends FunctionalTestCase
{
    private const TEMPLATE_PATH = 'EXT:pvh/Tests/Functional/ViewHelpers/Format/Fixtures/Trim.html';

    /**
     * @var bool Speed up this test case, it needs no database
     */
    protected $initializeDatabase = false;

    /**
     * @var array
     */
    protected $testExtensionsToLoad = [
        'typo3conf/ext/pvh',
    ];

    public function renderDataProvider(): array
    {
        return [
            'trim without characters' => ['  trimmed ', null, 'trimmed'],
            'trim specific character 1' => [ 'ztrimmedy', 'zy', 'trimmed'],
            'trim specific character 2' => [ 'ztzrimmeydy', 'zy', 'tzrimmeyd'],
        ];
    }

    /**
     * @dataProvider renderDataProvider
     * @test
     */
    public function render(string $content, ?string $characters, string $expected): void
    {
        $view = GeneralUtility::makeInstance(StandaloneView::class);
        $view->setTemplatePathAndFilename(self::TEMPLATE_PATH);
        $view->assign('content', $content);
        if ($characters !== null) {
            $view->assign('characters', $characters);
        }
        $html = $view->render();
        $xml = new \SimpleXMLElement($html);
        $id = $characters !== null ? 'characters' : 'no-characters';
        foreach (['param', 'var'] as $usage) {
            [$node] = $xml->xpath('//span[@id="' . $id . '-' . $usage . '"]');
            $actual = trim((string)$node);
            self::assertSame($expected, $actual);
        }
    }
}
