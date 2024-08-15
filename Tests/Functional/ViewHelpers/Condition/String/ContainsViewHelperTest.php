<?php
declare(strict_types=1);

/*
 * This file is part of the composer package buepro/typo3-pvh.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

namespace Buepro\Pvh\Tests\Functional\ViewHelpers\Condition\String;

use PHPUnit\Framework\Attributes\Test;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Fluid\View\StandaloneView;
use TYPO3\TestingFramework\Core\Functional\FunctionalTestCase;

class ContainsViewHelperTest extends FunctionalTestCase
{
    private const TEMPLATE_PATH = 'EXT:pvh/Tests/Functional/ViewHelpers/Condition/Fixtures/StringContains.html';

    protected bool $initializeDatabase = false;

    protected array $arguments = [
        'haystack' => 'foobarbaz',
        'needle' => 'bar',
        'then' => 'then',
        'else' => 'else',
    ];

    protected array $testExtensionsToLoad = [
        'typo3conf/ext/pvh',
    ];

    #[Test]
    public function rendersThenChildIfConditionMatched(): void
    {
        $view = GeneralUtility::makeInstance(StandaloneView::class);
        $view->setTemplatePathAndFilename(self::TEMPLATE_PATH);
        $view->assignMultiple($this->arguments);
        $html = $view->render();
        $xml = new \SimpleXMLElement($html);
        [$node] = $xml->xpath('//span[@id="inline"]');
        $actual = trim((string)$node);
        self::assertSame('then', $actual);
    }

    #[Test]
    public function rendersThenChildIfConditionNotMatched(): void
    {
        $view = GeneralUtility::makeInstance(StandaloneView::class);
        $view->setTemplatePathAndFilename(self::TEMPLATE_PATH);
        $view->assignMultiple(array_replace($this->arguments, ['needle' => 'bur']));
        $html = $view->render();
        $xml = new \SimpleXMLElement($html);
        [$node] = $xml->xpath('//span[@id="inline"]');
        $actual = trim((string)$node);
        self::assertSame('else', $actual);
    }
}
