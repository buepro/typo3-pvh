<?php

/*
 * This file is part of the composer package buepro/typo3-pvh.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

namespace Buepro\Pvh\ViewHelpers\Condition\Variable;

use TYPO3Fluid\Fluid\Core\Rendering\RenderingContextInterface;
use TYPO3Fluid\Fluid\Core\ViewHelper\AbstractConditionViewHelper;

/**
 * Based on EXT:vhs
 *
 * ### Condition: Value is NULL
 *
 * Condition ViewHelper which renders the `then` child if provided
 * value is NULL.
 */
class IsNullViewHelper extends AbstractConditionViewHelper
{
    public function initializeArguments()
    {
        parent::initializeArguments();
        $this->registerArgument('value', 'string', 'value to check', true);
    }

    public static function verdict(array $arguments, RenderingContextInterface $renderingContext)
    {
        return null === $arguments['value'];
    }
}
