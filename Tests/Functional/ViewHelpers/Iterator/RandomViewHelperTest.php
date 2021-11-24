<?php
declare(strict_types=1);

/*
 * This file is part of the composer package buepro/typo3-pvh.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

namespace Buepro\Pvh\Tests\Functional\ViewHelpers\Iterator;

use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Fluid\View\StandaloneView;
use TYPO3\TestingFramework\Core\Functional\FunctionalTestCase;

class RandomViewHelperTest extends FunctionalTestCase
{
    private const TEMPLATE_PATH = 'EXT:pvh/Tests/Functional/ViewHelpers/Iterator/Fixtures/Random.html';

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
     * @var int[]
     */
    private $subject = [1, 2, 3, 4, 5, 6, 7, 8, 9, 10];

    /**
     * @var array
     */
    private $arguments = [
        'count' => 1,
        'shuffle' => true,
        'as' => 'as',
    ];

    private function getView(array $subject, array $arguments): StandaloneView
    {
        $view = GeneralUtility::makeInstance(StandaloneView::class);
        $view->setTemplatePathAndFilename(self::TEMPLATE_PATH);
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

    public function renderDataProvider(): array
    {
        return [
            'one item' => [$this->subject, $this->arguments],
            'three items' => [$this->subject, array_merge($this->arguments, ['count' => 3])],
        ];
    }

    /**
     * @dataProvider renderDataProvider
     * @test
     */
    public function render(array $subject, array $arguments): void
    {
        $html = $this->getView($subject, $arguments)->render();
        $xml = new \SimpleXMLElement($html);
        [$node] = $xml->xpath('//span[@id="as"]');
        $actual = json_decode(trim((string)$node));
        $this->testActual($arguments, $actual);
    }

    /**
     * @test
     */
    public function supportedUsage(): void
    {
        $arguments = array_merge($this->arguments, ['count' => 3]);
        $html = $this->getView($this->subject, $arguments)->render();
        $xml = new \SimpleXMLElement($html);
        foreach (['as', 'as-child', 'direct'] as $id) {
            [$node] = $xml->xpath('//span[@id="' . $id . '"]');
            $actual = json_decode(trim((string)$node));
            $this->testActual($arguments, $actual);
        }
    }

    /**
     * @test
     */
    public function subjectOnly(): void
    {
        $html = $this->getView($this->subject, $this->arguments)->render();
        $xml = new \SimpleXMLElement($html);
        [$node] = $xml->xpath('//span[@id="minimal"]');
        $actual = json_decode(trim((string)$node));
        $this->testActual($this->arguments, $actual);
    }
}
