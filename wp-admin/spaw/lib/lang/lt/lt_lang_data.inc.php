<?php
// ================================================
// SPAW PHP WYSIWYG editor control
// ================================================
// Lithuanian language file
// ================================================
// Developed: Alan Mendelevich, alan@solmetra.lt
// Translated: Martynas Majeris martynas@solmetra.lt
//						 Saulius Okunevicius saulius@solmetra.lt
// Copyright: Solmetra (c)2003 All rights reserved.
// ------------------------------------------------
//                                www.solmetra.com
// ================================================
// v.1.0, 2003-04-04
// ================================================

// charset to be used in dialogs
$spaw_lang_charset = 'windows-1257';

// language text data array
// first dimension - block, second - exact phrase
// alternative text for toolbar buttons and title for dropdowns - 'title'

$spaw_lang_data = [
    'cut' => [
        'title' => 'Iðkirpti',
    ],
    'copy' => [
        'title' => 'Kopijuoti',
    ],
    'paste' => [
        'title' => 'Áterpti',
    ],
    'undo' => [
        'title' => 'Atðaukti',
    ],
    'redo' => [
        'title' => 'Pakartoti',
    ],
    'hyperlink' => [
        'title' => 'Nuoroda',
    ],
    'image_insert' => [
        'title' => 'Áterpti iliustracijà',
        'select' => 'Pasirinkti',
        'cancel' => 'Nutraukti',
        'library' => 'Biblioteka',
        'preview' => 'Perþiûra',
        'images' => 'Iliustracijos',
        'upload' => 'Ákelti iliustracijà',
        'upload_button' => 'Ákelti',
        'error' => 'Klaida',
        'error_no_image' => 'Paþymëkite iliustracijà',
        'error_uploading' => 'Ákeliant iliustracijà ávyko klaida. Pabandykite dar kartà vëliau.',
        'error_wrong_type' => 'Neteisingas iliustracijos failo formatas',
        'error_no_dir' => 'Biblioteka neegzistuoja',
    ],
    'image_prop' => [
        'title' => 'Iliustracijos parametrai',
        'ok' => '   GERAI   ',
        'cancel' => 'Nutraukti',
        'source' => 'Ðaltinis',
        'alt' => 'Alternatyvus tekstas',
        'align' => 'Lygiavimas',
        'left' => 'left (kairë)',
        'right' => 'right (deðinë)',
        'top' => 'top (virðus)',
        'middle' => 'middle (vidurys)',
        'bottom' => 'bottom (apaèia)',
        'absmiddle' => 'absmiddle (bendras vidurys)',
        'texttop' => 'texttop (teksto virðus)',
        'baseline' => 'baseline (teksto apaèia)',
        'width' => 'Plotis',
        'height' => 'Aukðtis',
        'border' => 'Rëmelio plotis',
        'hspace' => 'Hor. laukelis',
        'vspace' => 'Vert. laukelis',
        'error' => 'Klaida',
        'error_width_nan' => 'Nurodytas plotis nëra skaièius',
        'error_height_nan' => 'Nurodytas aukðtis nëra skaièius',
        'error_border_nan' => 'Nurodytas rëmelio plotis nëra skaièius',
        'error_hspace_nan' => 'Nurodytas horizontalaus laukelio plotis nëra skaièius',
        'error_vspace_nan' => 'Nurodytas vertikalaus laukelio plotis nëra skaièius',
    ],
    'hr' => [
        'title' => 'Horizontalus skirtukas',
    ],
    'table_create' => [
        'title' => 'Sukurti lentelæ',
    ],
    'table_prop' => [
        'title' => 'Lentelës parametrai',
        'ok' => '   GERAI   ',
        'cancel' => 'Nutraukti',
        'rows' => 'Eiluèiø',
        'columns' => 'Stulpeliø',
        'width' => 'Plotis',
        'height' => 'Aukðtis',
        'border' => 'Rëmelio plotis',
        'pixels' => 'taðk.',
        'cellpadding' => 'Laukelio atitraukimas (padding)',
        'cellspacing' => 'Tarpas tarp laukeliø',
        'bg_color' => 'Fono spalva',
        'error' => 'Klaida',
        'error_rows_nan' => 'Nurodytas eiluèiø kiekis nëra skaièius',
        'error_columns_nan' => 'Nurodytas stulpeliø kiekis nëra skaièius',
        'error_width_nan' => 'Nurodytas plotis nëra skaièius',
        'error_height_nan' => 'Nurodytas aukðtis nëra skaièius',
        'error_border_nan' => 'Nurodytas rëmelio plotis nëra skaièius',
        'error_cellpadding_nan' => 'Nurodytas laukelio atitraukimas nëra skaièius',
        'error_cellspacing_nan' => 'Nurodytas tarpas tarp laukeliø nëra skaièius',
    ],
    'table_cell_prop' => [
        'title' => 'Laukelio parametrai',
        'horizontal_align' => 'Vertikalus lygiavimas',
        'vertical_align' => 'Horizontalus lygiavimas',
        'width' => 'Plotis',
        'height' => 'Aukðtis',
        'css_class' => 'CSS klasë',
        'no_wrap' => 'Neperkeliamas',
        'bg_color' => 'Fono spalva',
        'ok' => '   GERAI   ',
        'cancel' => 'Nutraukti',
        'left' => 'Kairë',
        'center' => 'Centras',
        'right' => 'Deðinë',
        'top' => 'Virðus',
        'middle' => 'Vidurys',
        'bottom' => 'Apaèia',
        'baseline' => 'Teksto apaèia',
        'error' => 'Klaida',
        'error_width_nan' => 'Nurodytas plotis nëra skaièius',
        'error_height_nan' => 'Nurodytas aukðtis nëra skaièius',
    ],
    'table_row_insert' => [
        'title' => 'Áterpti eilutæ',
    ],
    'table_column_insert' => [
        'title' => 'Áterpti stulpelá',
    ],
    'table_row_delete' => [
        'title' => 'Paðalinti eilutæ',
    ],
    'table_column_delete' => [
        'title' => 'Paðalinti stulpelá',
    ],
    'table_cell_merge_right' => [
        'title' => 'Sujungti laukelius á deðinæ',
    ],
    'table_cell_merge_down' => [
        'title' => 'Sujungti laukelius apaèion',
    ],
    'table_cell_split_horizontal' => [
        'title' => 'Padalinti laukelá horizontaliai',
    ],
    'table_cell_split_vertical' => [
        'title' => 'Padalinti laukelá vertikaliai',
    ],
    'style' => [
        'title' => 'Stilius',
    ],
    'font' => [
        'title' => 'Ðriftas',
    ],
    'fontsize' => [
        'title' => 'Dydis',
    ],
    'paragraph' => [
        'title' => 'Paragrafas',
    ],
    'bold' => [
        'title' => 'Stambus ðriftas (Bold)',
    ],
    'italic' => [
        'title' => 'Kursyvas (Italic)',
    ],
    'underline' => [
        'title' => 'Pabrauktas (Underline)',
    ],
    'ordered_list' => [
        'title' => 'Numeruotas sàraðas',
    ],
    'bulleted_list' => [
        'title' => 'Sàraðas',
    ],
    'indent' => [
        'title' => 'Stumti á deðinæ',
    ],
    'unindent' => [
        'title' => 'Stumti á kairæ',
    ],
    'left' => [
        'title' => 'Kairë',
    ],
    'center' => [
        'title' => 'Centras',
    ],
    'right' => [
        'title' => 'Deðinë',
    ],
    'fore_color' => [
        'title' => 'Teksto spalva',
    ],
    'bg_color' => [
        'title' => 'Fono spalva',
    ],
    'design_tab' => [
        'title' => 'Perjungti á grafinio redagavimo reþimà',
    ],
    'html_tab' => [
        'title' => 'Perjungti á HTML kodo redagavimo reþimà',
    ],
    'colorpicker' => [
        'title' => 'Spalvos pasirinkimas',
        'ok' => '   GERAI   ',
        'cancel' => 'Nutraukti',
    ],
    'cleanup' => [
        'title' => 'HTML valymas (panaikinti stilius)',
        'confirm' => 'Atlikus ðá veiksmà bus panaikinti visi tekste naudojami stiliai, ðriftai ir nenaudojamos þymos. Dalis ar visas formatavimas gali bûti prarastas.',
        'ok' => '   GERAI   ',
        'cancel' => 'Nutraukti',
    ],
    'toggle_borders' => [
        'title' => 'Ájungti/iðjungti rëmelius',
    ],
    'hyperlink' => [
        'title' => 'Nuoroda',
        'url' => 'Adresas',
        'name' => 'Vardas',
        'target' => 'Kur atidaryti',
        'title_attr' => 'Pavadinimas',
        'ok' => '   GERAI   ',
        'cancel' => 'Nutraukti',
    ],
    'table_row_prop' => [
        'title' => 'Eilutës parametrai',
        'horizontal_align' => 'Horizontalus lygiavimas',
        'vertical_align' => 'Vertikalus lygiavimas',
        'css_class' => 'CSS klasë',
        'no_wrap' => 'Neperkeliamas',
        'bg_color' => 'Fono spalva',
        'ok' => '   GERAI   ',
        'cancel' => 'Nutraukti',
        'left' => 'Kairë',
        'center' => 'Centras',
        'right' => 'Deðinë',
        'top' => 'Virðus',
        'middle' => 'Vidurys',
        'bottom' => 'Apaèia',
        'baseline' => 'Teksto apaèia',
    ],
    'symbols' => [
        'title' => 'Specialûs simboliai',
        'ok' => '   GERAI   ',
        'cancel' => 'Atðaukti',
    ],
    'templates' => [
        'title' => 'Ðablonai',
    ],
    'page_prop' => [
        'title' => 'Puslapio parametrai',
        'title_tag' => 'Pavadinimas',
        'charset' => 'Simboliø rinkinys (Charset)',
        'background' => 'Fono paveiksliukas',
        'bgcolor' => 'Fono spalva',
        'text' => 'Teksto spalva',
        'link' => 'Nuorodos spalva',
        'vlink' => 'Aplankytos nuorodos spalva',
        'alink' => 'Aktyvios nuorodos spalva',
        'leftmargin' => 'Paraðtë kairëje',
        'topmargin' => 'Paraðtë virðuje',
        'css_class' => 'CSS klasë',
        'ok' => '   GERAI   ',
        'cancel' => 'Nutraukti',
    ],
    'preview' => [
        'title' => 'Perþiûra',
    ],
    'image_popup' => [
        'title' => 'Iððokantis paveiksliukas',
    ],
    'zoom' => [
        'title' => 'Mastelis',
    ],
];
