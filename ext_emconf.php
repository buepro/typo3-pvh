<?php

/*
 * This file is part of the composer package buepro/typo3-pvh.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

$EM_CONF[$_EXTKEY] = [
    'title' => 'PVH: Pizpalue View Helpers',
    'description' => 'A collection of view helpers used by pizpalue. Contains copies from vhs view helpers by Claus Due.',
    'category' => 'misc',
    'version' => '2.0.1-dev',
    'state' => 'stable',
    'author' => 'Roman Büchler',
    'author_email' => 'rb@buechler.pro',
    'constraints' => [
        'depends' => [
            'typo3' => '11.5.0-12.99.99',
        ],
    ],
    'autoload' => [
        'psr-4' => [
            'Buepro\\Pvh\\' => 'Classes/'
        ],
    ],
];
