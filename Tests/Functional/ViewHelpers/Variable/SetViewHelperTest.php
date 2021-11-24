<?php
declare(strict_types=1);

/*
 * This file is part of the composer package buepro/typo3-pvh.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

namespace Buepro\Pvh\Tests\Functional\ViewHelpers\Variable;

use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Fluid\View\StandaloneView;
use TYPO3\TestingFramework\Core\Functional\FunctionalTestCase;

class SetViewHelperTest extends FunctionalTestCase
{
    private const TEMPLATE_PATH = 'EXT:pvh/Tests/Functional/ViewHelpers/Variable/Fixtures/Set.html';

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
            'can set variable' => [
                [],
                ['name' => 'test', 'value' => false],
                ['name' => 'test', 'value' => false, 'test' => false],
            ],
            'can set variable in existing array value' => [
                ['test' => $object = new \ArrayObject(['test' => true])],
                ['name' => 'test.test', 'value' => false],
                ['name' => 'test.test', 'value' => false, 'test' => ['test' => false]],
            ],
            'ignore nested variable if root does not exist' => [
                ['test' => new \ArrayObject(['test' => true])],
                ['name' => 'doesnotexist.test', 'value' => false],
                ['test' => ['test' => true], 'name' => 'doesnotexist.test', 'value' => false],
            ],
            'ignore nested variable if root property name is invalid' => [
                ['test' => $object = new \ArrayObject(['test' => true])],
                ['name' => 'test.test.test', 'value' => false],
                ['test' => ['test' => true], 'name' => 'test.test.test', 'value' => false],
            ],
        ];
    }

    /**
     * @dataProvider renderDataProvider
     * @test
     * @param mixed|null $expected
     * @throws \Exception
     */
    public function render(array $variables, array $arguments, $expected): void
    {
        $view = GeneralUtility::makeInstance(StandaloneView::class);
        $view->setTemplatePathAndFilename(self::TEMPLATE_PATH);
        $view->assignMultiple($variables);
        $view->assignMultiple($arguments);
        $html = $view->render();
        $xml = new \SimpleXMLElement($html);
        [$node] = $xml->xpath('//span[@id="vars"]');
        $actual = json_decode(trim((string)$node), true);
        self::assertSame($expected, $actual);
    }

    /**
     * @test
     */
    public function canSetVariableWithValueFromTagContent(): void
    {
        $view = GeneralUtility::makeInstance(StandaloneView::class);
        $view->setTemplatePathAndFilename(self::TEMPLATE_PATH);
        $view->assignMultiple([
            'testTagContent' => true,
            'name' => 'testTagContent',
            'value' => false,
        ]);
        $html = $view->render();
        $xml = new \SimpleXMLElement($html);
        [$node] = $xml->xpath('//span[@id="vars-tag-content"]');
        $actual = json_decode(trim((string)$node), true);
        self::assertSame([
            'name' => 'testTagContent',
            'value' => false,
            'testTagContent' => false,
        ], $actual);
    }
}
