<?php

/*
 * This file is part of the composer package buepro/typo3-pvh.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

defined('TYPO3') or die('Access denied.');

(static function () {
    /**
     * Register "pvh" as global fluid namespace
     */
    $GLOBALS['TYPO3_CONF_VARS']['SYS']['fluid']['namespaces']['pvh'][] = 'Buepro\\Pvh\\ViewHelpers';
})();
