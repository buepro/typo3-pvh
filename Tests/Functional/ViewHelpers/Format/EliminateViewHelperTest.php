<?php
declare(strict_types=1);

/*
 * This file is part of the composer package buepro/typo3-pvh.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

namespace Buepro\Pvh\Tests\Functional\ViewHelpers\Format;

use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Fluid\View\StandaloneView;
use TYPO3\TestingFramework\Core\Functional\FunctionalTestCase;

class EliminateViewHelperTest extends FunctionalTestCase
{
    private const TEMPLATE_PATH = 'EXT:pvh/Tests/Functional/ViewHelpers/Format/Fixtures/Eliminate.html';

    /**
     * @var bool Speed up this test case, it needs no database
     */
    protected $initializeDatabase = false;

    /**
     * @var array
     */
    protected $arguments = [
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

    /**
     * @var array
     */
    protected $testExtensionsToLoad = [
        'typo3conf/ext/pvh',
    ];

    public function renderDataProvider(): array
    {
        return [
            'remove non ascii code' => ['fooøæåbar', array_merge($this->arguments, ['nonAscii' => true]), 'foobar'],
            'remove letters' => ['foo123bar', array_merge($this->arguments, ['letters' => true]), '123'],
            'remove letters case sensitive' => [
                'FOO123bar',
                array_merge($this->arguments, ['letters' => true, 'caseSensitive' => false]),
                '123'
            ],
            'remove digits' => ['foo123bar', array_merge($this->arguments, ['digits' => true]), 'foobar'],
            'remove windows cr' => ["breaks\rbreaks", array_merge($this->arguments, ['windowsBreaks' => true]), 'breaksbreaks'],
            'remove unix breaks' => ["breaks\nbreaks", array_merge($this->arguments, ['unixBreaks' => true]), 'breaksbreaks'],
            'remove tabs' => ['tabs	tabs', array_merge($this->arguments, ['tabs' => true]), 'tabstabs'],
            'remove white space' => [' trim med ', array_merge($this->arguments, ['whitespace' => true]), 'trimmed'],
            'remove whitespace between html tags' => [
                ' <p> Foo </p> <p> Bar </p> ',
                array_merge($this->arguments, ['whitespaceBetweenHtmlTags' => true]),
                '<p> Foo </p><p> Bar </p>'
            ],
            'remove characters case sensitive' => [
                'ABCdef',
                array_merge($this->arguments, ['characters' => 'abc', 'caseSensitive' => false]),
                'def'
            ],
            'remove character' => ['abcdef', array_merge($this->arguments, ['characters' => 'abc']), 'def'],
            'remove multibyte character' => ['aäæå本bc', array_merge($this->arguments, ['characters' => 'æ本']), 'aäåbc'],
            'remove array characters' => ['abcdef', array_merge($this->arguments, ['characters' => ['a', 'b', 'c']]), 'def'],
            'remove string case sensitive' => [
                'aBcDeFgHijkl',
                array_merge($this->arguments, ['strings' => 'abc,def,ghi', 'caseSensitive' => false]),
                'jkl'
            ],
            'remove strings' => ['abcdefghijkl', array_merge($this->arguments, ['strings' => 'abc,def,ghi']), 'jkl'],
            'remove string array' => ['abcdefghijkl', array_merge($this->arguments, ['strings' => ['abc', 'def', 'ghi']]), 'jkl'],
        ];
    }

    /**
     * @dataProvider renderDataProvider
     * @test
     */
    public function render(string $content, array $arguments, string $expected): void
    {
        $view = GeneralUtility::makeInstance(StandaloneView::class);
        $view->setTemplatePathAndFilename(self::TEMPLATE_PATH);
        $view->assign('content', $content);
        $view->assignMultiple($arguments);
        $html = $view->render();
        $xml = new \SimpleXMLElement($html);
        [$node] = $xml->xpath('//span[@id="eliminated"]');
        $actual = trim((string)$node);
        self::assertSame($expected, $actual);
    }
}
