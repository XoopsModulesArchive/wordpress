<?php
// ================================================
// SPAW PHP WYSIWYG editor control
// ================================================
// Default toolbar data file
// ================================================
// Developed: Alan Mendelevich, alan@solmetra.lt
// Copyright: Solmetra (c)2003 All rights reserved.
// ------------------------------------------------
//                                www.solmetra.com
// ================================================
// v.1.0, 2003-03-22
// ================================================

// array to hold toolbar definitions
// first dimension - toolbar location (top, left, right, bottom)
// second dimension - toolbar row/column
// third dimension - settings/data
// fourth dimension - setting/toolbar item
// toolbar item: name - item name, type - item type (button, dropdown, separator, etc.)

$spaw_toolbar_data = [
    'top_design' => [
        [
            'settings' => [
                'align' => 'left',
                'valign' => 'top',
            ],
            'data' => [
                [
                    'name' => 'cut',
                    'type' => SPAW_TBI_BUTTON,
                ],
                [
                    'name' => 'copy',
                    'type' => SPAW_TBI_BUTTON,
                ],
                [
                    'name' => 'paste',
                    'type' => SPAW_TBI_BUTTON,
                ],
                [
                    'name' => 'vertical_separator',
                    'type' => SPAW_TBI_IMAGE,
                ],
                [
                    'name' => 'undo',
                    'type' => SPAW_TBI_BUTTON,
                ],
                [
                    'name' => 'redo',
                    'type' => SPAW_TBI_BUTTON,
                ],
                [
                    'name' => 'vertical_separator',
                    'type' => SPAW_TBI_IMAGE,
                ],
                [
                    'name' => 'bold',
                    'type' => SPAW_TBI_BUTTON,
                ],
                [
                    'name' => 'italic',
                    'type' => SPAW_TBI_BUTTON,
                ],
                [
                    'name' => 'underline',
                    'type' => SPAW_TBI_BUTTON,
                ],
                [
                    'name' => 'vertical_separator',
                    'type' => SPAW_TBI_IMAGE,
                ],
                [
                    'name' => 'cleanup',
                    'type' => SPAW_TBI_BUTTON,
                ],
            ], // data
        ],
    ],
];
