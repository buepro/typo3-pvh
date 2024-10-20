<?php
declare(strict_types=1);

/*
 * This file is part of the composer package buepro/typo3-pvh.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

namespace Buepro\Pvh\Tests\Functional\ViewHelpers\Variable;

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Core\View\ViewFactoryData;
use TYPO3\CMS\Core\View\ViewFactoryInterface;
use TYPO3\TestingFramework\Core\Functional\FunctionalTestCase;

class SetViewHelperTest extends FunctionalTestCase
{
    private const TEMPLATE_PATH = 'EXT:pvh/Tests/Functional/ViewHelpers/Variable/Fixtures/Set.html';

    protected bool $initializeDatabase = false;

    protected array $testExtensionsToLoad = [
        'typo3conf/ext/pvh',
    ];

    public static function renderDataProvider(): array
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

    #[DataProvider('renderDataProvider')]
    #[Test]
    public function render(array $variables, array $arguments, array $expected): void
    {
        $view = (GeneralUtility::makeInstance(ViewFactoryInterface::class))
            ->create(new ViewFactoryData(
                null,
                null,
                null,
                self::TEMPLATE_PATH
            ));
        $view->assignMultiple($variables);
        $view->assignMultiple($arguments);
        $html = $view->render();
        $xml = new \SimpleXMLElement($html);
        [$node] = $xml->xpath('//span[@id="vars"]');
        $actual = json_decode(trim((string)$node), true);
        self::assertSame($expected, $actual);
    }

    #[Test]
    public function canSetVariableWithValueFromTagContent(): void
    {
        $view = (GeneralUtility::makeInstance(ViewFactoryInterface::class))
            ->create(new ViewFactoryData(
                null,
                null,
                null,
                self::TEMPLATE_PATH
            ));
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
