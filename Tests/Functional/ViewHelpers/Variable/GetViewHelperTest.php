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
use TYPO3\CMS\Extbase\Persistence\ObjectStorage;
use TYPO3\CMS\Fluid\View\StandaloneView;
use TYPO3\TestingFramework\Core\Functional\FunctionalTestCase;

class GetViewHelperTest extends FunctionalTestCase
{
    private const TEMPLATE_PATH = 'EXT:pvh/Tests/Functional/ViewHelpers/Variable/Fixtures/Get.html';

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
            'return null if variable does not exist' => [
                ['name' => 'void'],
                null,
            ],
            'return direct value if exists' => [
                [
                    'name' => 'test',
                    'test' => 1,
                ],
                1
            ],
            'return nested value if root exists' => [
                [
                    'name' => 'test.test',
                    'test' => ['test' => 1],
                ],
                1
            ],
            'return nested value using raw keys if root exists' => [
                [
                    'name' => 'test.test',
                    'useRawKeys => true',
                    'test' => ['test' => 1],
                ],
                1
            ],
            'return nested value if root exists and members are numeric' => [
                [
                    'name' => 'test.1',
                    'useRawKeys => true',
                    'test' => [1, 2],
                ],
                2
            ],
            'return null and suppress exception on invalid property getting' => [
                [
                    'name' => 'test.void',
                    'test' => new \stdClass,
                ],
                null
            ],
            'return null and suppress exception on non existing object storage property' => [
                [
                    'name' => 'storage.15',
                    'storage' => new ObjectStorage(),
                ],
                null
            ]
        ];
    }

    /**
     * @dataProvider renderDataProvider
     * @test
     * @param mixed|null $expected
     * @throws \Exception
     */
    public function render(array $arguments, $expected): void
    {
        $view = GeneralUtility::makeInstance(StandaloneView::class);
        $view->setTemplatePathAndFilename(self::TEMPLATE_PATH);
        $view->assignMultiple($arguments);
        $html = $view->render();
        $xml = new \SimpleXMLElement($html);
        [$node] = $xml->xpath('//span[@id="direct"]');
        $actual = json_decode(trim((string)$node), true);
        self::assertSame($expected, $actual);
    }
}
