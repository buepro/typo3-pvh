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
 * Trims $content by stripping off $characters (string list
 * of individual chars to strip off, default is all whitespaces).
 */
class TrimViewHelper extends AbstractViewHelper
{
    /**
     * @return void
     */
    public function initializeArguments()
    {
        $this->registerArgument('content', 'string', 'String to trim');
        $this->registerArgument('characters', 'string', 'List of characters to trim, no separators, e.g. "abc123"');
    }

    /**
     * Trims content by stripping off $characters
     *
     * @return string
     */
    public function render()
    {
        /** @var array{content: ?string, characters: ?string} $arguments */
        $arguments = $this->arguments;
        $content = $arguments['content'] ?? $this->renderChildren();
        if (!is_string($content)) {
            throw new \InvalidArgumentException('The content must be a string or a string-formatted string', 1729353296);
        }
        if (!empty($arguments['characters'])) {
            $content = trim($content, $arguments['characters']);
        } else {
            $content = trim($content);
        }
        return $content;
    }
}
