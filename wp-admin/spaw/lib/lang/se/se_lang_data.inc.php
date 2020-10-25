<?php
// ================================================
// SPAW PHP WYSIWYG editor control
// ================================================
// Swedish language file
// ================================================
// Developed: Alan Mendelevich, alan@solmetra.lt
// Copyright: Solmetra (c)2003 All rights reserved.
// Swedish translation: Tomas Jogin - tomas@jogin.com
// ------------------------------------------------
//                                www.solmetra.com
// ================================================
// v.1.0, 2003-03-20
// ================================================

// charset to be used in dialogs
$spaw_lang_charset = 'iso-8859-1';

// language text data array
// first dimension - block, second - exact phrase
// alternative text for toolbar buttons and title for dropdowns - 'title'

$spaw_lang_data = [
    'cut' => [
        'title' => 'Klipp ut',
    ],
    'copy' => [
        'title' => 'Kopiera',
    ],
    'paste' => [
        'title' => 'Klistra in',
    ],
    'undo' => [
        'title' => 'Ångra',
    ],
    'redo' => [
        'title' => 'Gör om',
    ],
    'hyperlink' => [
        'title' => 'Länk',
    ],
    'image_insert' => [
        'title' => 'Infoga bild',
        'select' => 'Infoga',
        'cancel' => 'Avbryt',
        'library' => 'Bildbibliotek',
        'preview' => 'Förhandsgranska',
        'images' => 'Bilder',
        'upload' => 'Ladda upp bild',
        'upload_button' => 'Ladda upp',
        'error' => 'Fel',
        'error_no_image' => 'Välj en bild',
        'error_uploading' => 'Ett fel uppstod vid fil-uppladdningen. Var god försök igen senare.',
        'error_wrong_type' => 'Fel bildtyp',
        'error_no_dir' => 'Bildbiblioteket finns inte',
    ],
    'image_prop' => [
        'title' => 'Bildegenskaper',
        'ok' => '   OK   ',
        'cancel' => 'Avbryt',
        'source' => 'Källa',
        'alt' => 'Beskrivning',
        'align' => 'Justering',
        'left' => 'vänster',
        'right' => 'höger',
        'top' => 'toppen',
        'middle' => 'mitten',
        'bottom' => 'botten',
        'absmiddle' => 'absmiddle',
        'texttop' => 'texttop',
        'baseline' => 'baseline',
        'width' => 'Bredd',
        'height' => 'Höjd',
        'border' => 'Kantlinje',
        'hspace' => 'Horisontell marginal',
        'vspace' => 'Vertikal marginal',
        'error' => 'Fel',
        'error_width_nan' => 'Bredd är inte ett nummer',
        'error_height_nan' => 'Höjd är inte ett nummer',
        'error_border_nan' => 'Kantlinje är inte ett nummer',
        'error_hspace_nan' => 'Horisontell marginal är inte ett nummer',
        'error_vspace_nan' => 'Vertikal marginal är inte ett nummer',
    ],
    'hr' => [
        'title' => 'Horisontell linje',
    ],
    'table_create' => [
        'title' => 'Skapa tabell',
    ],
    'table_prop' => [
        'title' => 'Tabellegenskaper',
        'ok' => '   OK   ',
        'cancel' => 'Avbryt',
        'rows' => 'Rader',
        'columns' => 'Kolumner',
        'width' => 'Bredd',
        'height' => 'Höjd',
        'border' => 'Kantlinje',
        'pixels' => 'pixlar',
        'cellpadding' => 'Fältmarginal',
        'cellspacing' => 'Fältavstånd',
        'bg_color' => 'Bakgrundsfärg',
        'error' => 'Fel',
        'error_rows_nan' => 'Rader är inte ett nummer',
        'error_columns_nan' => 'Kolumner är inte ett nummer',
        'error_width_nan' => 'Bredd är inte ett nummer',
        'error_height_nan' => 'Höjd är inte ett nummer',
        'error_border_nan' => 'Kantlinje är inte ett nummer',
        'error_cellpadding_nan' => 'Fältmarginal är inte ett nummer',
        'error_cellspacing_nan' => 'Fältavstånd är inte ett nummer',
    ],
    'table_cell_prop' => [
        'title' => 'Fältegenskaper',
        'horizontal_align' => 'Horisontell justering',
        'vertical_align' => 'Vertikal justering',
        'width' => 'Bredd',
        'height' => 'Höjd',
        'css_class' => 'CSS-klass',
        'no_wrap' => 'Ej automatisk radbrytning',
        'bg_color' => 'Bakgrundsfärg',
        'ok' => '   OK   ',
        'cancel' => 'Avbryt',
        'left' => 'Vänster',
        'center' => 'Mitten',
        'right' => 'Höger',
        'top' => 'Toppen',
        'middle' => 'Mitten',
        'bottom' => 'Botten',
        'baseline' => 'Baslinje',
        'error' => 'Fel',
        'error_width_nan' => 'Bredd är inte ett nummer',
        'error_height_nan' => 'Höjd är inte ett nummer',
    ],
    'table_row_insert' => [
        'title' => 'Infoga rad',
    ],
    'table_column_insert' => [
        'title' => 'Infoga kolumn',
    ],
    'table_row_delete' => [
        'title' => 'Radera rad',
    ],
    'table_column_delete' => [
        'title' => 'Radera kolumn',
    ],
    'table_cell_merge_right' => [
        'title' => 'Sammanfoga till höger',
    ],
    'table_cell_merge_down' => [
        'title' => 'Sammanfoga nedåt',
    ],
    'table_cell_split_horizontal' => [
        'title' => 'Dela fält horisontellt',
    ],
    'table_cell_split_vertical' => [
        'title' => 'Dela fält vertikalt',
    ],
    'style' => [
        'title' => 'Stilmall',
    ],
    'font' => [
        'title' => 'Teckensnitt',
    ],
    'fontsize' => [
        'title' => 'Storlek',
    ],
    'paragraph' => [
        'title' => 'Stycke',
    ],
    'bold' => [
        'title' => 'Fetstil',
    ],
    'italic' => [
        'title' => 'Kursiv',
    ],
    'underline' => [
        'title' => 'Understruken',
    ],
    'ordered_list' => [
        'title' => 'Sorterad lista',
    ],
    'bulleted_list' => [
        'title' => 'Osorterad lista',
    ],
    'indent' => [
        'title' => 'Indrag',
    ],
    'unindent' => [
        'title' => 'Ta bort indrag',
    ],
    'left' => [
        'title' => 'Vänster',
    ],
    'center' => [
        'title' => 'Mitten',
    ],
    'right' => [
        'title' => 'Höger',
    ],
    'fore_color' => [
        'title' => 'Förgrundsfärg',
    ],
    'bg_color' => [
        'title' => 'Bakgrundsfärg',
    ],
    'design_tab' => [
        'title' => 'Byt till layout-läge',
    ],
    'html_tab' => [
        'title' => 'Byt till HTML-läge',
    ],
    'colorpicker' => [
        'title' => 'Färgväljare',
        'ok' => '   OK   ',
        'cancel' => 'Avbryt',
    ],
    'cleanup' => [
        'title' => 'Rensa HTML',
        'confirm' => 'Detta rensar dokumentet från överflödiga stilformateringar och uppmärkningar. Vissa eller alla stilformateringar kan försvinna.',
        'ok' => '   OK   ',
        'cancel' => 'Avbryt',
    ],
    'toggle_borders' => [
        'title' => 'Visa/göm kantlinjer',
    ],
    'hyperlink' => [
        'title' => 'Infoga länk',
        'url' => 'Adress',
        'name' => 'Namn',
        'target' => 'Fönster',
        'title_attr' => 'Titel',
        'ok' => '   OK   ',
        'cancel' => 'Avbryt',
    ],
    'table_row_prop' => [
        'title' => 'Radegenskaper',
        'horizontal_align' => 'Horisontell justering',
        'vertical_align' => 'Vertikal justering',
        'css_class' => 'CSS-klass',
        'no_wrap' => 'Ej automatisk radbrytning',
        'bg_color' => 'Bakgrundsfärg',
        'ok' => '   OK   ',
        'cancel' => 'Avbryt',
        'left' => 'Vänster',
        'center' => 'Mitten',
        'right' => 'Höger',
        'top' => 'Toppen',
        'middle' => 'Mitten',
        'bottom' => 'Botten',
        'baseline' => 'Baslinje',
    ],
    'symbols' => [
        'title' => 'Speciella tecken',
        'ok' => '   OK   ',
        'cancel' => 'Avbryt',
    ],
    'templates' => [
        'title' => 'Mallar',
    ],
    'page_prop' => [
        'title' => 'Sidegenskaper',
        'title_tag' => 'Titel',
        'charset' => 'Teckenuppsättning',
        'background' => 'Bakgrundsbild',
        'bgcolor' => 'Bakgrundsfärg',
        'text' => 'Textfärg',
        'link' => 'Länkfärg',
        'vlink' => 'Färg på besökta länkar',
        'alink' => 'Färg på markerade länkar',
        'leftmargin' => 'Vänstermarginal',
        'topmargin' => 'Topmarginal',
        'css_class' => 'CSS-klass',
        'ok' => '   OK   ',
        'cancel' => 'Avbryt',
    ],
    'preview' => [
        'title' => 'Förhandsgranska',
    ],
    'image_popup' => [
        'title' => 'Bild-popup',
    ],
    'zoom' => [
        'title' => 'Zoom',
    ],
];
