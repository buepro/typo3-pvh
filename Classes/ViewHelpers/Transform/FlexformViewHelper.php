<?php
declare(strict_types=1);

/*
 * This file is part of the composer package buepro/typo3-pvh.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

namespace Buepro\Pvh\ViewHelpers\Transform;

use TYPO3\CMS\Core\Service\FlexFormService;
use TYPO3Fluid\Fluid\Core\ViewHelper\AbstractViewHelper;

/**
 * Transforms flexform data to an array.
 *
 *  Usage:
 *     {pvh:transform.flexform(data: pi_flexform, as: 'pi_flexform_transformed')}
 */
class FlexformViewHelper extends AbstractViewHelper
{
    /**
     * @var bool
     */
    protected $escapeChildren = false;

    /**
     * @var bool
     */
    protected $escapeOutput = false;

    public function __construct(
        private FlexFormService $flexFormService
    ) {
    }

    /**
     * @return void
     */
    public function initializeArguments()
    {
        $this->registerArgument(
            'data',
            'string',
            'The flexform data',
            true
        );
        $this->registerArgument(
            'as',
            'string',
            'Template variable name to assign; if not specified the ViewHelper returns the variable instead.'
        );
    }

    /**
     * @return array|mixed|string
     */
    public function render()
    {
        /** @var array{data: string, as: string} $arguments */
        $arguments = $this->arguments;
        $transformed = $this->flexFormService->convertFlexFormContentToArray($arguments['data']);
        if (!empty($arguments['as'])) {
            $variableProvider = $this->renderingContext->getVariableProvider();
            $variableProvider->add($arguments['as'], $transformed);
            return '';
        }
        return $transformed;
    }
}
