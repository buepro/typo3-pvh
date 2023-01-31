<?php
declare(strict_types=1);

/*
 * This file is part of the composer package buepro/typo3-pvh.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

namespace Buepro\Pvh\Tests\Functional\ViewHelpers\Core;

use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Core\Utility\VersionNumberUtility;
use TYPO3\CMS\Fluid\View\StandaloneView;
use TYPO3\TestingFramework\Core\Functional\FunctionalTestCase;

class VersionViewHelperTest extends FunctionalTestCase
{
    private const TEMPLATE_PATH = 'EXT:pvh/Tests/Functional/ViewHelpers/Core/Fixtures/Version.html';

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

    /**
     * @test
     */
    public function render(): void
    {
        $expected = (string)VersionNumberUtility::convertVersionNumberToInteger(VersionNumberUtility::getNumericTypo3Version());
        $view = GeneralUtility::makeInstance(StandaloneView::class);
        $view->setTemplatePathAndFilename(self::TEMPLATE_PATH);
        $html = $view->render();
        $xml = new \SimpleXMLElement($html);
        [$node] = $xml->xpath('//span[@id="version"]');
        $actual = trim((string)$node);
        self::assertSame($expected, $actual);
        [$node] = $xml->xpath('//span[@id="version-as"]');
        $actual = trim((string)$node);
        self::assertSame($expected, $actual);
    }
}
