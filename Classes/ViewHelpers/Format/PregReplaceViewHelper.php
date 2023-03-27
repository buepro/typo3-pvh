<?php
declare(strict_types=1);

/*
 * This file is part of the composer package buepro/typo3-pvh.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

namespace Buepro\Pvh\ViewHelpers\Format;

use Buepro\Pvh\Traits\TemplateVariableViewHelperTrait;
use TYPO3Fluid\Fluid\Core\Rendering\RenderingContextInterface;
use TYPO3Fluid\Fluid\Core\ViewHelper\AbstractViewHelper;
use TYPO3Fluid\Fluid\Core\ViewHelper\Traits\CompileWithRenderStatic;

/**
 * Based on EXT:vhs
 *
 * ### PregReplace regular expression ViewHelper
 *
 * Implementation of `preg_replace`.
 */
class PregReplaceViewHelper extends AbstractViewHelper
{
    use CompileWithRenderStatic;
    use TemplateVariableViewHelperTrait;

    public function initializeArguments(): void
    {
        $this->registerArgument('subject', 'string', 'String to match with the regex pattern or patterns');
        $this->registerArgument('pattern', 'string', 'Regex pattern to match against', true);
        $this->registerArgument('replacement', 'string', 'String to replace matches with', true);
        $this->registerAsArgument();
    }

    /**
     * @return mixed|string
     */
    public static function renderStatic(
        array $arguments,
        \Closure $renderChildrenClosure,
        RenderingContextInterface $renderingContext
    ) {
        /** @var array{subject: ?string, pattern: string, replacement: string} $arguments */
        $subject = $arguments['subject'] ?? $renderChildrenClosure() ?? '';
        $result = preg_replace($arguments['pattern'], $arguments['replacement'], $subject);
        return static::finalizeRenderStaticWithAsArgument($arguments, $renderingContext, $result);
    }
}
