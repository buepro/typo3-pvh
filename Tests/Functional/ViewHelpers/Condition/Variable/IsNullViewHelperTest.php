<?php
declare(strict_types=1);

/*
 * This file is part of the composer package buepro/typo3-pvh.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

namespace Buepro\Pvh\Tests\Functional\ViewHelpers\Condition\Variable;

use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Fluid\View\StandaloneView;
use TYPO3\TestingFramework\Core\Functional\FunctionalTestCase;

class IsNullViewHelperTest extends FunctionalTestCase
{
    private const TEMPLATE_PATH = 'EXT:pvh/Tests/Functional/ViewHelpers/Condition/Fixtures/VariableIsNull.html';

    /**
     * @var bool Speed up this test case, it needs no database
     */
    protected $initializeDatabase = false;

    /**
     * @var array
     */
    protected $arguments = [
        'value' => null,
        'then' => 'then',
        'else' => 'else',
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

    /**
     * @test
     */
    public function rendersThenChildIfConditionNotMatched(): void
    {
        $view = GeneralUtility::makeInstance(StandaloneView::class);
        $view->setTemplatePathAndFilename(self::TEMPLATE_PATH);
        $view->assignMultiple(array_replace($this->arguments, ['value' => 'bur']) ?? []);
        $html = $view->render();
        $xml = new \SimpleXMLElement($html);
        [$node] = $xml->xpath('//span[@id="inline"]');
        $actual = trim((string)$node);
        self::assertSame('else', $actual);
    }
}
