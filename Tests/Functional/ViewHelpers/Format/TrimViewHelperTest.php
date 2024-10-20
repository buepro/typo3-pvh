<?php
declare(strict_types=1);

/*
 * This file is part of the composer package buepro/typo3-pvh.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

namespace Buepro\Pvh\Tests\Functional\ViewHelpers\Format;

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Core\View\ViewFactoryData;
use TYPO3\CMS\Core\View\ViewFactoryInterface;
use TYPO3\TestingFramework\Core\Functional\FunctionalTestCase;

class TrimViewHelperTest extends FunctionalTestCase
{
    private const TEMPLATE_PATH = 'EXT:pvh/Tests/Functional/ViewHelpers/Format/Fixtures/Trim.html';

    protected bool $initializeDatabase = false;

    protected array $testExtensionsToLoad = [
        'typo3conf/ext/pvh',
    ];

    public static function renderDataProvider(): array
    {
        return [
            'trim without characters' => ['  trimmed ', null, 'trimmed'],
            'trim specific character 1' => [ 'ztrimmedy', 'zy', 'trimmed'],
            'trim specific character 2' => [ 'ztzrimmeydy', 'zy', 'tzrimmeyd'],
        ];
    }

    #[DataProvider('renderDataProvider')]
    #[Test]
    public function render(string $content, ?string $characters, string $expected): void
    {
        $view = (GeneralUtility::makeInstance(ViewFactoryInterface::class))
            ->create(new ViewFactoryData(
                null,
                null,
                null,
                self::TEMPLATE_PATH
            ));
        $view->assign('content', $content);
        if ($characters !== null) {
            $view->assign('characters', $characters);
        }
        $html = $view->render();
        $xml = new \SimpleXMLElement($html);
        $id = $characters !== null ? 'characters' : 'no-characters';
        foreach (['param', 'var'] as $usage) {
            [$node] = (array) $xml->xpath('//span[@id="' . $id . '-' . $usage . '"]');
            $actual = trim((string)$node);
            self::assertSame($expected, $actual);
        }
    }
}
