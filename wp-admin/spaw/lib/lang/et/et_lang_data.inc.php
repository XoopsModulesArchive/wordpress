<?php
// ================================================
// SPAW PHP WYSIWYG editor control
// ================================================
// English language file
// ================================================
// Developed: Alan Mendelevich, alan@solmetra.lt
// Copyright: Solmetra (c)2003 All rights reserved.
// Estonian translation: Maku, maktak@phpnuke-est.net
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
        'title' => 'Lõike',
    ],
    'copy' => [
        'title' => 'Kopeeri',
    ],
    'paste' => [
        'title' => 'Kleebi',
    ],
    'undo' => [
        'title' => 'Samm Tagasi',
    ],
    'redo' => [
        'title' => 'Samm Edasi',
    ],
    'hyperlink' => [
        'title' => 'Hüperlink',
    ],
    'image_insert' => [
        'title' => 'Lisa Pilt',
        'select' => 'Vali',
        'cancel' => 'Loobu',
        'library' => 'Teek',
        'preview' => 'Eelvaade',
        'images' => 'Pildid',
        'upload' => 'Pildi üleslaadimine',
        'upload_button' => 'Üleslaadimine',
        'error' => 'Viga',
        'error_no_image' => 'Palun valige pilt',
        'error_uploading' => 'Viga faili üleslaadimisega. Proovige hiljem uuesti',
        'error_wrong_type' => 'Valge pildi failitüüp',
        'error_no_dir' => 'Teek ei eksisteeri füüsiliselt',
    ],
    'image_prop' => [
        'title' => 'Pildi Seaded',
        'ok' => '   OK   ',
        'cancel' => 'Loobu',
        'source' => 'Lähe',
        'alt' => 'Alternatiivne Tekst',
        'align' => 'Joondamine',
        'left' => 'vasak',
        'right' => 'parem',
        'top' => 'ülal',
        'middle' => 'keskel',
        'bottom' => 'põhjas',
        'absmiddle' => 'absmiddle',
        'texttop' => 'texttop',
        'baseline' => 'äärejoon',
        'width' => 'Laius',
        'height' => 'Kõrgus',
        'border' => 'Serv',
        'hspace' => 'Hor. vahe',
        'vspace' => 'Vert. vahe',
        'error' => 'Viga',
        'error_width_nan' => 'Laius ei ole number',
        'error_height_nan' => 'Kõrgus ei ole number',
        'error_border_nan' => 'Serv ei ole number',
        'error_hspace_nan' => 'Horisontaalide vahe ei ole number',
        'error_vspace_nan' => 'Vertikaalide vahe ei ole number',
    ],
    'hr' => [
        'title' => 'Horisontaalide Reegel',
    ],
    'table_create' => [
        'title' => 'Loo tabel',
    ],
    'table_prop' => [
        'title' => 'Tabeli seaded',
        'ok' => '   OK   ',
        'cancel' => 'Loobu',
        'rows' => 'Ridu',
        'columns' => 'Tulpi',
        'width' => 'Laius',
        'height' => 'Kõrgus',
        'border' => 'Serv',
        'pixels' => 'pikselit',
        'cellpadding' => 'Elemendi polsterdus',
        'cellspacing' => 'Elementide vahe',
        'bg_color' => 'Taustavärv',
        'error' => 'Viga',
        'error_rows_nan' => 'Ridade arv ei ole number',
        'error_columns_nan' => 'Tulpade arv ei ole number',
        'error_width_nan' => 'Laius ei ole number',
        'error_height_nan' => 'Kõrgus ei ole number',
        'error_border_nan' => 'Serv ei ole number',
        'error_cellpadding_nan' => 'Elemendi polsterdus ei ole number',
        'error_cellspacing_nan' => 'Elementide vahe ei ole number',
    ],
    'table_cell_prop' => [
        'title' => 'Elemendi seaded',
        'horizontal_align' => 'Horisontaalne joondamine',
        'vertical_align' => 'Vertikaalne joondamine',
        'width' => 'Laius',
        'height' => 'Kõrgus',
        'css_class' => 'CSS klass',
        'no_wrap' => 'Mähkimine väljas',
        'bg_color' => 'Tausta värv',
        'ok' => '   OK   ',
        'cancel' => 'Loobu',
        'left' => 'Vasakul',
        'center' => 'Keskel',
        'right' => 'Paremal',
        'top' => 'Ülal',
        'middle' => 'Keskel',
        'bottom' => 'Põhjas',
        'baseline' => 'Äärejoon',
        'error' => 'Viga',
        'error_width_nan' => 'Laius ei ole number',
        'error_height_nan' => 'Kõrgus ei ole number',
    ],
    'table_row_insert' => [
        'title' => 'Lisa rida',
    ],
    'table_column_insert' => [
        'title' => 'Lisa tulp',
    ],
    'table_row_delete' => [
        'title' => 'Kustuta rida',
    ],
    'table_column_delete' => [
        'title' => 'Kustuta tulp',
    ],
    'table_cell_merge_right' => [
        'title' => 'Sulandu/Ühine paremale',
    ],
    'table_cell_merge_down' => [
        'title' => 'Sulandu/Ühine alla',
    ],
    'table_cell_split_horizontal' => [
        'title' => 'Poolita element horisontaalselt',
    ],
    'table_cell_split_vertical' => [
        'title' => 'Poolita element vertikaalselt',
    ],
    'style' => [
        'title' => 'Stiil',
    ],
    'font' => [
        'title' => 'Kirjastiil',
    ],
    'fontsize' => [
        'title' => 'Suurus',
    ],
    'paragraph' => [
        'title' => 'Paragrahv',
    ],
    'bold' => [
        'title' => 'Rasvane',
    ],
    'italic' => [
        'title' => 'Kaldkiri',
    ],
    'underline' => [
        'title' => 'Allajoonitud',
    ],
    'ordered_list' => [
        'title' => 'Korrapärane Nimekiri',
    ],
    'bulleted_list' => [
        'title' => 'Täppidega Nimekiri',
    ],
    'indent' => [
        'title' => 'Süvendatud',
    ],
    'unindent' => [
        'title' => 'Süvendamata',
    ],
    'left' => [
        'title' => 'Vasakul',
    ],
    'center' => [
        'title' => 'Keskel',
    ],
    'right' => [
        'title' => 'Paremal',
    ],
    'fore_color' => [
        'title' => 'Pealmine värv',
    ],
    'bg_color' => [
        'title' => 'Tausta värv',
    ],
    'design_tab' => [
        'title' => 'Lülitu WYSIWYG (kujundus) moodi',
    ],
    'html_tab' => [
        'title' => 'Lülitu HTML (kood) moodi',
    ],
    'colorpicker' => [
        'title' => 'Värvivalija',
        'ok' => '   OK   ',
        'cancel' => 'Loobu',
    ],
    'cleanup' => [
        'title' => 'HTML puhastamine (eemaldab stiilid)',
        'confirm' => 'Selle tegemine eemaldab stiilid, kirjastiilid ja ebavajalikud tag-id, mõned või kõik vormindused võivad kaotsi minna.',
        'ok' => '   OK   ',
        'cancel' => 'Loobu',
    ],
    'toggle_borders' => [
        'title' => 'Servad',
    ],
    'hyperlink' => [
        'title' => 'Hüperlink',
        'url' => 'URL',
        'name' => 'Nimi',
        'target' => 'Sihtmärk',
        'title_attr' => 'Tiitel',
        'ok' => '   OK   ',
        'cancel' => 'Loobu',
    ],
    'table_row_prop' => [
        'title' => 'Rea seaded',
        'horizontal_align' => 'Horisontaalne joondamine',
        'vertical_align' => 'Vertikaalne joondamine',
        'css_class' => 'CSS klass',
        'no_wrap' => 'Mähkimine väljas',
        'bg_color' => 'Tausta värv',
        'ok' => '   OK   ',
        'cancel' => 'Loobu',
        'left' => 'Vasakul',
        'center' => 'Keskel',
        'right' => 'Paremal',
        'top' => 'Ülal',
        'middle' => 'Keskel',
        'bottom' => 'Põhjas',
        'baseline' => 'Äärejoon',
    ],
    'symbols' => [
        'title' => 'Spetsiaalsed tähemärgid',
        'ok' => '   OK   ',
        'cancel' => 'Loobu',
    ],
    'templates' => [
        'title' => 'Mallid',
    ],
    'page_prop' => [
        'title' => 'Lehe seaded',
        'title_tag' => 'Tiitel',
        'charset' => 'Märgistik',
        'background' => 'Taustapilt',
        'bgcolor' => 'Taustavärv',
        'text' => 'Teksti värv',
        'link' => 'Lingi värv',
        'vlink' => 'Külastatud lingi värv',
        'alink' => 'Aktiivse lingi värv',
        'leftmargin' => 'Piiraja Vasemal',
        'topmargin' => 'Piiraja Ülal',
        'css_class' => 'CSS klass',
        'ok' => '   OK   ',
        'cancel' => 'Loobu',
    ],
    'preview' => [
        'title' => 'Eelvaade',
    ],
    'image_popup' => [
        'title' => 'Pildi popup',
    ],
    'zoom' => [
        'title' => 'Suurendus',
    ],
];
