<?php
declare(strict_types=1);

/*
 * This file is part of the composer package buepro/typo3-pvh.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

namespace Buepro\Pvh\ViewHelpers\Iterator;

use Buepro\Pvh\Utility\IteratorUtility;
use TYPO3Fluid\Fluid\Core\ViewHelper\AbstractViewHelper;

/**
 * Select random elements from a coma separated list, array, traversable or query result.
 *
 * Usage:
 *    {pp:iterator.random(subject: someArray, count: 3, as: 'randoms')}
 *    {someArray -> pp:iterator.random(count: 3, as: 'randoms')}
 *    {pp:iterator.random(subject: someArray, count: 3) -> f:variable(name: 'randoms')}
 */
class RandomViewHelper extends AbstractViewHelper
{
    /**
     * @var bool
     */
    protected $escapeChildren = false;

    /**
     * @var bool
     */
    protected $escapeOutput = false;

    /**
     * @return void
     */
    public function initializeArguments()
    {
        $this->registerArgument(
            'subject',
            'mixed',
            'The subject Traversable/Array instance from which to select random elements'
        );
        $this->registerArgument(
            'count',
            'int',
            'Number of randomly selected elements to be returned',
            false,
            1
        );
        $this->registerArgument(
            'shuffle',
            'boolean',
            'Shuffle the selected elements',
            false,
            true
        );
        $this->registerArgument(
            'as',
            'string',
            'Template variable name to assign; if not specified the ViewHelper returns the variable instead.'
        );
    }

    /**
     * @return array|string
     */
    public function render()
    {
        /** @var array{subject: null|string|int, count: string|int, shuffle: string|bool, as: string} $arguments */
        $arguments = $this->arguments;
        $subject = $arguments['subject'] ?? $this->renderChildren();
        $subject = IteratorUtility::arrayFromArrayOrTraversableOrCSV($subject);
        $count = min((int)$arguments['count'], count($subject));
        $keys = array_rand($subject, $count);
        $keys = is_array($keys) ? $keys : [$keys];
        $result = array_intersect_key($subject, array_flip($keys));
        if ((bool)$arguments['shuffle']) {
            if (!shuffle($result)) {
                $result = array_intersect_key($subject, $keys);
            }
        }
        if (!empty($arguments['as'])) {
            $variableProvider = $this->renderingContext->getVariableProvider();
            $variableProvider->add($arguments['as'], $result);
            return '';
        }
        return $result;
    }
}
