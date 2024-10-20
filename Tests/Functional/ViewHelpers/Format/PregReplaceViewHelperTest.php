<?php
declare(strict_types=1);

/*
 * This file is part of the composer package buepro/typo3-pvh.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

namespace Buepro\Pvh\Tests\Functional\ViewHelpers\Format;

use PHPUnit\Framework\Attributes\Test;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Core\View\ViewFactoryData;
use TYPO3\CMS\Core\View\ViewFactoryInterface;
use TYPO3\TestingFramework\Core\Functional\FunctionalTestCase;

class PregReplaceViewHelperTest extends FunctionalTestCase
{
    private const TEMPLATE_PATH = 'EXT:pvh/Tests/Functional/ViewHelpers/Format/Fixtures/PregReplace.html';

    protected bool $initializeDatabase = false;

    protected array $arguments = [
        'subject' => 'foo123bar',
        'pattern' => '/[0-9]{3}/',
        'replacement' => 'baz',
    ];

    protected array $testExtensionsToLoad = [
        'typo3conf/ext/pvh',
    ];

    #[Test]
    public function render(): void
    {
        $view = (GeneralUtility::makeInstance(ViewFactoryInterface::class))
            ->create(new ViewFactoryData(
                null,
                null,
                null,
                self::TEMPLATE_PATH
            ));
        $view->assignMultiple($this->arguments);
        $html = $view->render();
        $xml = new \SimpleXMLElement($html);
        foreach (['inline', 'inline-content', 'inline-var', 'tag-content', 'tag-content-var'] as $id) {
            [$node] = $xml->xpath('//span[@id="' . $id . '"]');
            $actual = trim((string)$node);
            self::assertSame('foobazbar', $actual, 'Iteration: ' . $id);
        }
    }
}
