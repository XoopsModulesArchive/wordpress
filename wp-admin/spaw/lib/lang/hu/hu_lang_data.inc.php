<?php
// ================================================
// SPAW PHP WYSIWYG editor control
// ================================================
// English language file
// ================================================
// Developed: Alan Mendelevich, alan@solmetra.lt
// Translated: Bagoly Sándor Zsigmond, sasa@networldtrading.com
// Copyright: Solmetra (c)2003 All rights reserved.
// ------------------------------------------------
//                                www.solmetra.com
// ================================================
// v.1.0, 2003-05-20
// ================================================

// charset to be used in dialogs
$spaw_lang_charset = 'iso-8859-1';

// language text data array
// first dimension - block, second - exact phrase
// alternative text for toolbar buttons and title for dropdowns - 'title'

$spaw_lang_data = [
    'cut' => [
        'title' => 'Kivágás',
    ],
    'copy' => [
        'title' => 'Másolás',
    ],
    'paste' => [
        'title' => 'Beillesztés',
    ],
    'undo' => [
        'title' => 'Visszavonás',
    ],
    'redo' => [
        'title' => 'Mégis',
    ],
    'hyperlink' => [
        'title' => 'Hiperhivatkozás',
    ],
    'image_insert' => [
        'title' => 'Kép beszúrás',
        'select' => 'Kiválaszt',
        'cancel' => 'Mégse',
        'library' => 'Könyvtár',
        'preview' => 'Elõnézet',
        'images' => 'Képek',
        'upload' => 'Kép feltöltése',
        'upload_button' => 'Feltöltés',
        'error' => 'Hiba',
        'error_no_image' => 'Kérem válasszon képet',
        'error_uploading' => 'Hiba lépett fel a feltöltés folyamatában. Kérjük próbálja késõbb.',
        'error_wrong_type' => 'Hibás képtípus',
        'error_no_dir' => 'A könyvtár fizikailag nem létezik',
    ],
    'image_prop' => [
        'title' => 'Kép tulajdonságai',
        'ok' => '   OK   ',
        'cancel' => 'Mégse',
        'source' => 'Forrás',
        'alt' => 'Alternatív szöveg',
        'align' => 'Igazítás',
        'left' => 'Balra',
        'right' => 'Jobbra',
        'top' => 'Tetejére',
        'middle' => 'Középre',
        'bottom' => 'Aljára',
        'absmiddle' => 'Teljesen középre',
        'texttop' => 'Teljesen a tetejére',
        'baseline' => 'Alapvonalra',
        'width' => 'Szélesség',
        'height' => 'Magasság',
        'border' => 'Szegély',
        'hspace' => 'Vízszintes hely',
        'vspace' => 'Függõleges hely',
        'error' => 'Hiba',
        'error_width_nan' => 'Szélesség nem egy szám',
        'error_height_nan' => 'Magasság nem egy szám',
        'error_border_nan' => 'Szegély nem egy szám',
        'error_hspace_nan' => 'Vízszintes hely nem egy szám',
        'error_vspace_nan' => 'Függõleges hely nem egy szám',
    ],
    'hr' => [
        'title' => 'Vízszintes vonal',
    ],
    'table_create' => [
        'title' => 'Táblázatot létrehoz',
    ],
    'table_prop' => [
        'title' => 'Táblázat tulajdonságai',
        'ok' => '   OK   ',
        'cancel' => 'Mégse',
        'rows' => 'Sorok',
        'columns' => 'Oszlopok',
        'width' => 'Szélesség',
        'height' => 'Magasság',
        'border' => 'Szegély',
        'pixels' => 'pixelek',
        'cellpadding' => 'Cella kitöltése',
        'cellspacing' => 'Cellák közötti hely',
        'bg_color' => 'Háttérszín',
        'error' => 'Hiba',
        'error_rows_nan' => 'Sorok nem egy szám',
        'error_columns_nan' => 'Oszlopok nem egy szám',
        'error_width_nan' => 'Szélesség nem egy szám',
        'error_height_nan' => 'Magasság nem egy szám',
        'error_border_nan' => 'Szegély nem egy szám',
        'error_cellpadding_nan' => 'Cella kitöltése nem egy szám',
        'error_cellspacing_nan' => 'Cellák közötti hely nem egy szám',
    ],
    'table_cell_prop' => [
        'title' => 'Cella tulajdonságai',
        'horizontal_align' => 'Vízszintesre zárás',
        'vertical_align' => 'Függõlegesre zárás',
        'width' => 'Szélesség',
        'height' => 'Magasság',
        'css_class' => 'CSS osztály',
        'no_wrap' => 'Nincs csomagolás',
        'bg_color' => 'Háttérszín',
        'ok' => '   OK   ',
        'cancel' => 'Mégse',
        'left' => 'Balra zárás',
        'center' => 'Középre zárás',
        'right' => 'Jobbra zárás',
        'top' => 'Tetejére',
        'middle' => 'Középre',
        'bottom' => 'Aljára',
        'baseline' => 'Alapvonal',
        'error' => 'Hiba',
        'error_width_nan' => 'Szélesség nem egy szám',
        'error_height_nan' => 'Magasság nem egy szám',
    ],
    'table_row_insert' => [
        'title' => 'Sor beszúrás',
    ],
    'table_column_insert' => [
        'title' => 'Oszlop beszúrás',
    ],
    'table_row_delete' => [
        'title' => 'Sor törlése',
    ],
    'table_column_delete' => [
        'title' => 'Oszlop törlése',
    ],
    'table_cell_merge_right' => [
        'title' => 'Cellák egyesítése jobbra',
    ],
    'table_cell_merge_down' => [
        'title' => 'Cellák egyesítése lefele',
    ],
    'table_cell_split_horizontal' => [
        'title' => 'Cellák vízszintes szétszakítása ',
    ],
    'table_cell_split_vertical' => [
        'title' => 'Cellák függõleges szétkaszítása',
    ],
    'style' => [
        'title' => 'Stílus',
    ],
    'font' => [
        'title' => 'Betûtípus',
    ],
    'fontsize' => [
        'title' => 'Méret',
    ],
    'paragraph' => [
        'title' => 'Bekezdés',
    ],
    'bold' => [
        'title' => 'Félkövér',
    ],
    'italic' => [
        'title' => 'Dõlt',
    ],
    'underline' => [
        'title' => 'Aláhúzott',
    ],
    'ordered_list' => [
        'title' => 'Számozás',
    ],
    'bulleted_list' => [
        'title' => 'Felsorolás',
    ],
    'indent' => [
        'title' => 'Behúzás növelése',
    ],
    'unindent' => [
        'title' => 'Behúzás csökkentése',
    ],
    'left' => [
        'title' => 'Balra igazítás',
    ],
    'center' => [
        'title' => 'Középre igazítás',
    ],
    'right' => [
        'title' => 'Jobbra igazítás',
    ],
    'fore_color' => [
        'title' => 'Szín',
    ],
    'bg_color' => [
        'title' => 'Háttérszín',
    ],
    'design_tab' => [
        'title' => 'Váltás a WYSWYG (desing) módra',
    ],
    'html_tab' => [
        'title' => 'Váltás a HTML (kód) módra',
    ],
    'colorpicker' => [
        'title' => 'Színválasztó',
        'ok' => '   OK   ',
        'cancel' => 'Mégse',
    ],
    'cleanup' => [
        'title' => 'HTML tisztítás (stílusokat megszüntet)',
        'confirm' => 'Ezzel a cselekedettel törli az alkalmazott stílusokat, betûtípusokat és a fölösleges adatokat a jelen dokumentumban. Valamennyi vagy minden formázás el fog veszni.',
        'ok' => '   OK   ',
        'cancel' => 'Mégse',
    ],
    'toggle_borders' => [
        'title' => 'Szegély megmutatása',
    ],
    'hyperlink' => [
        'title' => 'Hiperhivatkozás',
        'url' => 'Hivatkozott cím (URL)',
        'name' => 'Név',
        'target' => 'Cél',
        'title_attr' => 'Cím',
        'ok' => '   OK   ',
        'cancel' => 'Mégse',
    ],
    'table_row_prop' => [
        'title' => 'Sor tulajdonságai',
        'horizontal_align' => 'Vízszintes igazítás',
        'vertical_align' => 'Függleges igazítás align',
        'css_class' => 'CSS osztály',
        'no_wrap' => 'Nincs csomagolás',
        'bg_color' => 'Háttérszín',
        'ok' => '   OK   ',
        'cancel' => 'Mégse',
        'left' => 'Balra',
        'center' => 'Középre',
        'right' => 'Jobbra',
        'top' => 'Tetejére',
        'middle' => 'Középre',
        'bottom' => 'Aljára',
        'baseline' => 'Alapvonalra',
    ],
    'symbols' => [
        'title' => 'Speciális karakterek',
        'ok' => '   OK   ',
        'cancel' => 'Mégse',
    ],
    'templates' => [
        'title' => 'Sablonok',
    ],
    'page_prop' => [
        'title' => 'Oldal tulajdonságok',
        'title_tag' => 'Címe',
        'charset' => 'Karakter típus',
        'background' => 'Háttérkép',
        'bgcolor' => 'Háttérszín',
        'text' => 'Szöveg színe',
        'link' => 'Hivatkozás színe',
        'vlink' => 'Látogatott hivatkozás színe',
        'alink' => 'Aktív hivatkozás színe',
        'leftmargin' => 'Bal margó',
        'topmargin' => 'Tetõ margó',
        'css_class' => 'CSS osztály',
        'ok' => '   OK   ',
        'cancel' => 'Mégse',
    ],
    'preview' => [
        'title' => 'Elõnézet',
    ],
    'image_popup' => [
        'title' => 'Elõugró kép',
    ],
    'zoom' => [
        'title' => 'Nagyítás',
    ],
];


