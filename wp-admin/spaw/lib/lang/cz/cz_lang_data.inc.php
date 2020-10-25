<?php
// ================================================
// SPAW PHP WYSIWYG editor control
// ================================================
// Czech language file
// ================================================
// Developed: Alan Mendelevich, alan@solmetra.lt
// Copyright: Solmetra (c)2003 All rights reserved.
// Czech translation: BrM (BrM@bridlicna.cz)
// ------------------------------------------------
//                                www.solmetra.com
// ================================================
// v.1.0, 2003-03-20
// ================================================

// charset to be used in dialogs
$spaw_lang_charset = 'iso-8859-2';

// language text data array
// first dimension - block, second - exact phrase
// alternative text for toolbar buttons and title for dropdowns - 'title'

$spaw_lang_data = [
    'cut' => [
        'title' => 'Vyjmout',
    ],
    'copy' => [
        'title' => 'Kopírovat',
    ],
    'paste' => [
        'title' => 'Vložit',
    ],
    'undo' => [
        'title' => 'Zpìt',
    ],
    'redo' => [
        'title' => 'Vpøed',
    ],
    'hyperlink' => [
        'title' => 'Hyperlink',
    ],
    'image_insert' => [
        'title' => 'Vložit obrázek',
        'select' => 'Výbìr',
        'cancel' => 'Zrušit',
        'library' => 'Knihovna',
        'preview' => 'Náhled',
        'images' => 'Obrázek',
        'upload' => 'Upload obrázek',
        'upload_button' => 'Upload',
        'error' => 'Chyba',
        'error_no_image' => 'Vyberte obrázek prosím',
        'error_uploading' => 'V prùbìhu uploadu došlo k chybì. Opakujte akci znovu',
        'error_wrong_type' => 'chybný formát obrázku',
        'error_no_dir' => 'Knihovna fyzicky neexistuje',
    ],
    'image_prop' => [
        'title' => 'Vlastnosti obrázku',
        'ok' => '   OK   ',
        'cancel' => 'Storno',
        'source' => 'Zdroj',
        'alt' => 'Alternativní text',
        'align' => 'Zarovnání',
        'left' => 'vlevo',
        'right' => 'vpravo',
        'top' => 'Horní',
        'middle' => 'Støední',
        'bottom' => 'Spodní',
        'absmiddle' => 'absolutní støed',
        'texttop' => 'text-nahoru',
        'baseline' => 'baseline',
        'width' => 'Šíøka',
        'height' => 'Výška',
        'border' => 'Okraje',
        'hspace' => 'Hor. space',
        'vspace' => 'Vert. space',
        'error' => 'Chyba',
        'error_width_nan' => 'Šíøka není èíslo',
        'error_height_nan' => 'Výška není èíslo',
        'error_border_nan' => 'Okraj není èíslo',
        'error_hspace_nan' => 'Horizontální rozteè není èíslo',
        'error_vspace_nan' => 'Vertikální rozteè není èíslo',
    ],
    'hr' => [
        'title' => 'Horizontal rule',
    ],
    'table_create' => [
        'title' => 'Vytvoø tabulku',
    ],
    'table_prop' => [
        'title' => 'Vlastnosti tabulky',
        'ok' => '   OK   ',
        'cancel' => 'Storno',
        'rows' => 'Øádkù',
        'columns' => 'Sloupcù',
        'width' => 'Šíøka',
        'height' => 'Výška',
        'border' => 'Okraje',
        'pixels' => 'pixelù',
        'cellpadding' => 'Odsazení v buòce',
        'cellspacing' => 'Vzdálenost bunìk',
        'bg_color' => 'Barva pozadí',
        'error' => 'Chyba',
        'error_rows_nan' => 'Øádky nejsou èíslo',
        'error_columns_nan' => 'Sloupce nejsou èíslo',
        'error_width_nan' => 'Šíøka není èíslo',
        'error_height_nan' => 'Výška není èíslo',
        'error_border_nan' => 'Okraje nejsou èíslo',
        'error_cellpadding_nan' => 'Odsazení v buòce není èíslo',
        'error_cellspacing_nan' => 'Vzdálenost buòek není èíslo',
    ],
    'table_cell_prop' => [
        'title' => 'Vlastnosti buòky',
        'horizontal_align' => 'Horizontální zarovnání',
        'vertical_align' => 'Vertikální zarovnání',
        'width' => 'Šíøka',
        'height' => 'Výška',
        'css_class' => 'CSS tøída',
        'no_wrap' => 'nezalamovat',
        'bg_color' => 'Barva pozadí',
        'ok' => '   OK   ',
        'cancel' => 'Zrušit',
        'left' => 'Vlevo',
        'center' => 'Na støed',
        'right' => 'Vpravo',
        'top' => 'Nahoru',
        'middle' => 'Doprostøed',
        'bottom' => 'Dolù',
        'baseline' => 'Základní linka',
        'error' => 'Chyba',
        'error_width_nan' => 'Šíøka není èíslo',
        'error_height_nan' => 'Výška není èíslo',
    ],
    'table_row_insert' => [
        'title' => 'Vložit øádek',
    ],
    'table_column_insert' => [
        'title' => 'Vložit sloupec',
    ],
    'table_row_delete' => [
        'title' => 'Vymaž øádek',
    ],
    'table_column_delete' => [
        'title' => 'Vymaž sloupec',
    ],
    'table_cell_merge_right' => [
        'title' => 'Slouèit vpravo',
    ],
    'table_cell_merge_down' => [
        'title' => 'Slouèit dolù',
    ],
    'table_cell_split_horizontal' => [
        'title' => 'Rozdìlit buòku horizontálnì',
    ],
    'table_cell_split_vertical' => [
        'title' => 'Rozdìlit buòku vertikálnì',
    ],
    'style' => [
        'title' => 'Styl',
    ],
    'font' => [
        'title' => 'Font',
    ],
    'fontsize' => [
        'title' => 'Velikost',
    ],
    'paragraph' => [
        'title' => 'Odstavec',
    ],
    'bold' => [
        'title' => 'Tuèné',
    ],
    'italic' => [
        'title' => 'Kurziva',
    ],
    'underline' => [
        'title' => 'Podtržení',
    ],
    'ordered_list' => [
        'title' => 'Èíslování',
    ],
    'bulleted_list' => [
        'title' => 'Odøážky',
    ],
    'indent' => [
        'title' => 'Odsazení',
    ],
    'unindent' => [
        'title' => 'Zrušit odsazení',
    ],
    'left' => [
        'title' => 'Vlevo',
    ],
    'center' => [
        'title' => 'Na støed',
    ],
    'right' => [
        'title' => 'Vpravo',
    ],
    'fore_color' => [
        'title' => 'Barva popøedí',
    ],
    'bg_color' => [
        'title' => 'Barva pozadí',
    ],
    'design_tab' => [
        'title' => 'Pøepnout do WYSIWYG módu',
    ],
    'html_tab' => [
        'title' => 'Pøepnout do HTML módu',
    ],
    'colorpicker' => [
        'title' => 'Paleta barev',
        'ok' => '   OK   ',
        'cancel' => 'Storno',
    ],
    // <<<<<<<<< NEW >>>>>>>>>
    'cleanup' => [
        'title' => 'HTML kontrola (odstraní styly)',
        'confirm' => 'Provedením akce odstraníte všechny styly, fonty a zbyteèné tagy z aktuálního obsahu. Nìkteré, nebo všechno formátování bude odstranìno.',
        'ok' => '   OK   ',
        'cancel' => 'Storno',
    ],
    'toggle_borders' => [
        'title' => 'Upravit okraje',
    ],
    'hyperlink' => [
        'title' => 'Hyperlink',
        'url' => 'URL',
        'name' => 'Jméno',
        'target' => 'Cíl',
        'title_attr' => 'Název',
        'ok' => '   OK   ',
        'cancel' => 'Storno',
    ],
    'table_row_prop' => [
        'title' => 'Vlastnosti øádku',
        'horizontal_align' => 'Horizontální zarovnání',
        'vertical_align' => 'Vertikální zarovnání',
        'css_class' => 'CSS class',
        'no_wrap' => 'Nezalamovat',
        'bg_color' => 'Barva pozadí',
        'ok' => '   OK   ',
        'cancel' => 'Storno',
        'left' => 'Vlevo',
        'center' => 'Na støed',
        'right' => 'Vpravo',
        'top' => 'Horní',
        'middle' => 'Støední',
        'bottom' => 'Spodní',
        'baseline' => 'Základní linka',
    ],
    'symbols' => [
        'title' => 'Speciální znaky',
        'ok' => '   OK   ',
        'cancel' => 'Storno',
    ],
    'symbols' => [
        'title' => 'Speciální znaky',
        'ok' => '   OK   ',
        'cancel' => 'Storno',
    ],
    'templates' => [
        'title' => 'Šablony',
    ],
    'page_prop' => [
        'title' => 'Vlastnosti stránky',
        'title_tag' => 'Název',
        'charset' => 'Kódování',
        'background' => 'Obrázek pozadí',
        'bgcolor' => 'Barva pozadí',
        'text' => 'Barva textu',
        'link' => 'Barva odkazu',
        'vlink' => 'Barva navštíveného odkazu',
        'alink' => 'Barva aktivního odkazu',
        'leftmargin' => 'Levý okraj',
        'topmargin' => 'Horní okraj',
        'css_class' => 'CSS class',
        'ok' => '   OK   ',
        'cancel' => 'Storno',
    ],
    'preview' => [
        'title' => 'Náhled',
    ],
    'image_popup' => [
        'title' => 'Pøekrývání obrázkù',
    ],
    'zoom' => [
        'title' => 'Pøiblížení',
    ],
];


