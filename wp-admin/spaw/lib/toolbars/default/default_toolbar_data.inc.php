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
                    'name' => 'hyperlink',
                    'type' => SPAW_TBI_BUTTON,
                ],
                [
                    'name' => 'image_insert',
                    'type' => SPAW_TBI_BUTTON,
                ],
                [
                    'name' => 'image_prop',
                    'type' => SPAW_TBI_BUTTON,
                ],
                [
                    'name' => 'hr',
                    'type' => SPAW_TBI_BUTTON,
                ],
                [
                    'name' => 'vertical_separator',
                    'type' => SPAW_TBI_IMAGE,
                ],
                [
                    'name' => 'table_create',
                    'type' => SPAW_TBI_BUTTON,
                ],
                [
                    'name' => 'table_prop',
                    'type' => SPAW_TBI_BUTTON,
                ],
                [
                    'name' => 'table_cell_prop',
                    'type' => SPAW_TBI_BUTTON,
                ],
                [
                    'name' => 'table_row_insert',
                    'type' => SPAW_TBI_BUTTON,
                ],
                [
                    'name' => 'table_column_insert',
                    'type' => SPAW_TBI_BUTTON,
                ],
                [
                    'name' => 'table_row_delete',
                    'type' => SPAW_TBI_BUTTON,
                ],
                [
                    'name' => 'table_column_delete',
                    'type' => SPAW_TBI_BUTTON,
                ],
                [
                    'name' => 'table_cell_merge_right',
                    'type' => SPAW_TBI_BUTTON,
                ],
                [
                    'name' => 'table_cell_merge_down',
                    'type' => SPAW_TBI_BUTTON,
                ],
                [
                    'name' => 'table_cell_split_horizontal',
                    'type' => SPAW_TBI_BUTTON,
                ],
                [
                    'name' => 'table_cell_split_vertical',
                    'type' => SPAW_TBI_BUTTON,
                ],
            ], // data
        ],
        [
            'settings' => [
                'align' => 'left',
                'valign' => 'top',
            ],
            'data' => [
                /*
                            array(
                              'name' => 'style',
                              'type' => SPAW_TBI_DROPDOWN
                            ),
                */
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
                    'name' => 'ordered_list',
                    'type' => SPAW_TBI_BUTTON,
                ],
                [
                    'name' => 'bulleted_list',
                    'type' => SPAW_TBI_BUTTON,
                ],
                [
                    'name' => 'vertical_separator',
                    'type' => SPAW_TBI_IMAGE,
                ],
                [
                    'name' => 'indent',
                    'type' => SPAW_TBI_BUTTON,
                ],
                [
                    'name' => 'unindent',
                    'type' => SPAW_TBI_BUTTON,
                ],
                [
                    'name' => 'vertical_separator',
                    'type' => SPAW_TBI_IMAGE,
                ],
                [
                    'name' => 'left',
                    'type' => SPAW_TBI_BUTTON,
                ],
                [
                    'name' => 'center',
                    'type' => SPAW_TBI_BUTTON,
                ],
                [
                    'name' => 'right',
                    'type' => SPAW_TBI_BUTTON,
                ],
                [
                    'name' => 'vertical_separator',
                    'type' => SPAW_TBI_IMAGE,
                ],
                [
                    'name' => 'fore_color',
                    'type' => SPAW_TBI_BUTTON,
                ],
                [
                    'name' => 'bg_color',
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
                [
                    'name' => 'toggle_borders',
                    'type' => SPAW_TBI_BUTTON,
                ],
            ], // data
        ],
    ],

    'bottom_design' => [
        [
            'settings' => [
                'align' => 'right',
                'valign' => 'top',
            ],
            'data' => [
                [
                    'name' => 'design_tab_on',
                    'type' => SPAW_TBI_IMAGE,
                ],
                [
                    'name' => 'html_tab',
                    'type' => SPAW_TBI_BUTTON,
                ],
            ], // data
        ],
    ],

    'bottom_html' => [
        [
            'settings' => [
                'align' => 'right',
                'valign' => 'top',
            ],
            'data' => [
                [
                    'name' => 'design_tab',
                    'type' => SPAW_TBI_BUTTON,
                ],
                [
                    'name' => 'html_tab_on',
                    'type' => SPAW_TBI_IMAGE,
                ],
            ], // data
        ],
    ],
];
