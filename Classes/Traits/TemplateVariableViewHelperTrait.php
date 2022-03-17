<?php
declare(strict_types=1);

/*
 * This file is part of the composer package buepro/typo3-pvh.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

namespace Buepro\Pvh\Traits;

use TYPO3Fluid\Fluid\Core\Rendering\RenderingContextInterface;

/**
 * Class TemplateVariableViewHelperTrait
 *
 * @see \FluidTYPO3\Vhs\Traits\TemplateVariableViewHelperTrait
 */
trait TemplateVariableViewHelperTrait
{
    protected function registerAsArgument(): void
    {
        $this->registerArgument(
            'as',
            'string',
            'Template variable name to assign; if not specified the ViewHelper returns the processing result instead.'
        );
    }

    /**
     * @param mixed $result
     * @return mixed|string
     */
    public static function finalizeRenderStaticWithAsArgument(
        array $arguments,
        RenderingContextInterface $renderingContext,
        $result
    ) {
        if (isset($arguments['as']) && $arguments['as'] !== '') {
            $variableProvider = $renderingContext->getVariableProvider();
            $variableProvider->add($arguments['as'], $result);
            return '';
        }
        return $result ?? '';
    }
}
