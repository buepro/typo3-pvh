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

class PregReplaceViewHelperTest extends FunctionalTestCase
{
    private const TEMPLATE_PATH = 'EXT:pvh/Tests/Functional/ViewHelpers/Format/Fixtures/PregReplace.html';

    /**
     * @var bool Speed up this test case, it needs no database
     */
    protected $initializeDatabase = false;

    /**
     * @var array
     */
    protected $arguments = [
        'subject' => 'foo123bar',
        'pattern' => '/[0-9]{3}/',
        'replacement' => 'baz',
    ];

    /**
     * @var array
     */
    protected $testExtensionsToLoad = [
        'typo3conf/ext/pvh',
    ];

    /**
     * @test
     */
    public function render(): void
    {
        $view = GeneralUtility::makeInstance(StandaloneView::class);
        $view->setTemplatePathAndFilename(self::TEMPLATE_PATH);
        $view->assignMultiple($this->arguments);
        $html = $view->render();
        $xml = new \SimpleXMLElement($html);
        foreach (['inline', 'inline-content', 'inline-var', 'tag-content', 'tag-content-var'] as $id) {
            [$node] = $xml->xpath('//span[@id="' . $id . '"]');
            $actual = trim((string)$node);
            self::assertSame('foobazbar', $actual, 'Iteration: ' . $id);
        }
    }
}
