<?php
declare(strict_types=1);

/*
 * This file is part of the composer package buepro/typo3-pvh.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

namespace Buepro\Pvh\Tests\Functional\ViewHelpers\Core;

use PHPUnit\Framework\Attributes\Test;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Core\Utility\VersionNumberUtility;
use TYPO3\CMS\Core\View\ViewFactoryData;
use TYPO3\CMS\Core\View\ViewFactoryInterface;
use TYPO3\TestingFramework\Core\Functional\FunctionalTestCase;

class VersionViewHelperTest extends FunctionalTestCase
{
    private const TEMPLATE_PATH = 'EXT:pvh/Tests/Functional/ViewHelpers/Core/Fixtures/Version.html';

    protected bool $initializeDatabase = false;

    protected array $testExtensionsToLoad = [
        'typo3conf/ext/pvh',
    ];

    #[Test]
    public function render(): void
    {
        $expected = (string)VersionNumberUtility::convertVersionNumberToInteger(VersionNumberUtility::getNumericTypo3Version());
        $view = (GeneralUtility::makeInstance(ViewFactoryInterface::class))
            ->create(new ViewFactoryData(
                null,
                null,
                null,
                self::TEMPLATE_PATH
            ));
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
