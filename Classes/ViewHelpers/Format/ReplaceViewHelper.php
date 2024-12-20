<?php
declare(strict_types=1);

/*
 * This file is part of the composer package buepro/typo3-pvh.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

namespace Buepro\Pvh\ViewHelpers\Format;

use TYPO3Fluid\Fluid\Core\ViewHelper\AbstractViewHelper;

/**
 * Copied from EXT:vhs
 *
 * Replaces $substring in $content with $replacement.
 */
class ReplaceViewHelper extends AbstractViewHelper
{
    public function initializeArguments(): void
    {
        $this->registerArgument('content', 'string', 'Content in which to perform replacement');
        $this->registerArgument('substring', 'string', 'Substring to replace', true);
        $this->registerArgument('replacement', 'string', 'Replacement to insert', false, '');
        $this->registerArgument('count', 'integer', 'Maximum number of times to perform replacement', false, null);
        $this->registerArgument('caseSensitive', 'boolean', 'If true, perform case-sensitive replacement', false, true);
    }

    /**
     * @return string
     */
    public function render()
    {
        /** @var array{content: ?string, substring: string, replacement: string, count: ?int, caseSensitive: bool} $arguments */
        $arguments = $this->arguments;
        $content = $arguments['content'] ?? $this->renderChildren();
        if (!is_string($content)) {
            throw new \InvalidArgumentException('The content must be a string or a string-formatted string', 1729353423);
        }
        $substring = $arguments['substring'];
        $replacement = $arguments['replacement'];
        $caseSensitive = $arguments['caseSensitive'];
        $function = (true === $caseSensitive ? 'str_replace' : 'str_ireplace');
        $result = $function($substring, $replacement, $content, $arguments['count']);
        if (!is_string($result)) {
            return '';
        }
        return $result;
    }
}
