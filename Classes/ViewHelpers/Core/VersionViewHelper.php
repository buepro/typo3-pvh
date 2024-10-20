<?php
declare(strict_types=1);

/*
 * This file is part of the composer package buepro/typo3-pvh.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

namespace Buepro\Pvh\ViewHelpers\Core;

use TYPO3\CMS\Core\Utility\VersionNumberUtility;
use TYPO3Fluid\Fluid\Core\ViewHelper\AbstractViewHelper;

/**
 * To get the TYPO3 version. Usage:
 * - {pvh:core.version()}
 * - {pvh:core.version(as: 'v')}
 */
class VersionViewHelper extends AbstractViewHelper
{
    /**
     * @return void
     */
    public function initializeArguments()
    {
        $this->registerArgument(
            'as',
            'string',
            'Variable name to assign.'
        );
    }

    /**
     * @return string
     */
    public function render(): string
    {
        /** @var array{as: ?string} $arguments */
        $arguments = $this->arguments;
        $result = VersionNumberUtility::convertVersionNumberToInteger(VersionNumberUtility::getNumericTypo3Version());
        if (!empty($arguments['as'])) {
            $variableProvider = $this->renderingContext->getVariableProvider();
            $variableProvider->add($arguments['as'], $result);
            return '';
        }
        return (string)$result;
    }
}
