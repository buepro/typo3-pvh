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

class EliminateViewHelperTest extends FunctionalTestCase
{
    private const TEMPLATE_PATH = 'EXT:pvh/Tests/Functional/ViewHelpers/Format/Fixtures/Eliminate.html';

    protected bool $initializeDatabase = false;

    protected static array $arguments = [
        'caseSensitive' => true,
        'characters' => null,
        'strings' => null,
        'whitespace' => false,
        'whitespaceBetweenHtmlTags' => false,
        'tabs' => false,
        'unixBreaks' => false,
        'windowsBreaks' => false,
        'digits' => false,
        'letters' => false,
        'nonAscii' => false
    ];

    protected array $testExtensionsToLoad = [
        'typo3conf/ext/pvh',
    ];

    public static function renderDataProvider(): array
    {
        return [
            'remove non ascii code' => ['fooøæåbar', array_merge(self::$arguments, ['nonAscii' => true]), 'foobar'],
            'remove letters' => ['foo123bar', array_merge(self::$arguments, ['letters' => true]), '123'],
            'remove letters case sensitive' => [
                'FOO123bar',
                array_merge(self::$arguments, ['letters' => true, 'caseSensitive' => false]),
                '123'
            ],
            'remove digits' => ['foo123bar', array_merge(self::$arguments, ['digits' => true]), 'foobar'],
            'remove windows cr' => ["breaks\rbreaks", array_merge(self::$arguments, ['windowsBreaks' => true]), 'breaksbreaks'],
            'remove unix breaks' => ["breaks\nbreaks", array_merge(self::$arguments, ['unixBreaks' => true]), 'breaksbreaks'],
            'remove tabs' => ['tabs	tabs', array_merge(self::$arguments, ['tabs' => true]), 'tabstabs'],
            'remove white space' => [' trim med ', array_merge(self::$arguments, ['whitespace' => true]), 'trimmed'],
            'remove whitespace between html tags' => [
                ' <p> Foo </p> <p> Bar </p> ',
                array_merge(self::$arguments, ['whitespaceBetweenHtmlTags' => true]),
                '<p> Foo </p><p> Bar </p>'
            ],
            'remove characters case sensitive' => [
                'ABCdef',
                array_merge(self::$arguments, ['characters' => 'abc', 'caseSensitive' => false]),
                'def'
            ],
            'remove character' => ['abcdef', array_merge(self::$arguments, ['characters' => 'abc']), 'def'],
            'remove multibyte character' => ['aäæå本bc', array_merge(self::$arguments, ['characters' => 'æ本']), 'aäåbc'],
            'remove array characters' => ['abcdef', array_merge(self::$arguments, ['characters' => ['a', 'b', 'c']]), 'def'],
            'remove string case sensitive' => [
                'aBcDeFgHijkl',
                array_merge(self::$arguments, ['strings' => 'abc,def,ghi', 'caseSensitive' => false]),
                'jkl'
            ],
            'remove strings' => ['abcdefghijkl', array_merge(self::$arguments, ['strings' => 'abc,def,ghi']), 'jkl'],
            'remove string array' => ['abcdefghijkl', array_merge(self::$arguments, ['strings' => ['abc', 'def', 'ghi']]), 'jkl'],
        ];
    }

    #[DataProvider('renderDataProvider')]
    #[Test]
    public function render(string $content, array $arguments, string $expected): void
    {
        $view = (GeneralUtility::makeInstance(ViewFactoryInterface::class))
            ->create(new ViewFactoryData(
                null,
                null,
                null,
                self::TEMPLATE_PATH
            ));
        $view->assign('content', $content);
        $view->assignMultiple($arguments);
        $html = $view->render();
        $xml = new \SimpleXMLElement($html);
        foreach (['inline-only', 'inline-content', 'tag-only', 'tag-content'] as $tagId) {
            [$node] = $xml->xpath('//span[@id="' . $tagId . '"]');
            $actual = trim((string)$node);
            self::assertSame($expected, $actual);
        }
    }
}
