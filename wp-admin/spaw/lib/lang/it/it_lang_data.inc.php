<?php
// ====================================================
// SPAW PHP WYSIWYG editor control
// ====================================================
// English language file
// ====================================================
// Developed: Alan Mendelevich, alan@solmetra.lt
// Copyright: Solmetra (c)2003 All rights reserved.
// Italian translation: Omar Di Marzio - omar@networking.it
// ----------------------------------------------------
//                                www.solmetra.com
// ====================================================
// v.1.0, 2003-04-11
// ====================================================

// charset to be used in dialogs
$spaw_lang_charset = 'iso-8859-1';

// language text data array
// first dimension - block, second - exact phrase
// alternative text for toolbar buttons and title for dropdowns - 'title'

$spaw_lang_data = [
    'cut' => [
        'title' => 'Taglia',
    ],
    'copy' => [
        'title' => 'Copia',
    ],
    'paste' => [
        'title' => 'Incolla',
    ],
    'undo' => [
        'title' => 'Indietro',
    ],
    'redo' => [
        'title' => 'Avanti',
    ],
    'hyperlink' => [
        'title' => 'Hyperlink',
    ],
    'image_insert' => [
        'title' => 'Inserisci immagine',
        'select' => 'Seleziona',
        'cancel' => 'Cancella',
        'library' => 'Libreria',
        'preview' => 'Preview',
        'images' => 'Immagini',
        'upload' => 'Upload immagine',
        'upload_button' => 'Upload',
        'error' => 'Error',
        'error_no_image' => 'Immagine non selezionata',
        'error_uploading' => 'Errore in upload. Per favore, ritenta ancora',
        'error_wrong_type' => 'Tipo immagine errata',
        'error_no_dir' => 'La directory delle immagini non esiste fisicamente',
    ],
    'image_prop' => [
        'title' => 'Proprietà immagine',
        'ok' => '   OK   ',
        'cancel' => 'Cancella',
        'source' => 'Sorgente',
        'alt' => 'Testo alternativo (ALT TAG)',
        'align' => 'Allineamento',
        'left' => 'sinistra',
        'right' => 'destra',
        'top' => 'sopra',
        'middle' => 'metà altezza',
        'bottom' => 'sotto',
        'absmiddle' => 'centro assoluto',
        'texttop' => 'testo sopra',
        'baseline' => 'in linea alla base',
        'width' => 'Larghezza',
        'height' => 'Altezza',
        'border' => 'Bordo',
        'hspace' => 'spazio orizontale',
        'vspace' => 'spazio verticale',
        'error' => 'Errore',
        'error_width_nan' => 'Width non è un numero',
        'error_height_nan' => 'Height non è un numero',
        'error_border_nan' => 'Border non è un numero',
        'error_hspace_nan' => 'Horizontal space non è un numero',
        'error_vspace_nan' => 'Vertical space non è un numero',
    ],
    'hr' => [
        'title' => 'Righello orizontale',
    ],
    'table_create' => [
        'title' => 'Crea tabella',
    ],
    'table_prop' => [
        'title' => 'proprietà tabella',
        'ok' => '   OK   ',
        'cancel' => 'Cancela',
        'rows' => 'Righe',
        'columns' => 'Colonne',
        'width' => 'Larghezza',
        'height' => 'Altezza',
        'border' => 'Bordo',
        'pixels' => 'pixel',
        'cellpadding' => 'Cell padding',
        'cellspacing' => 'Cell spacing',
        'bg_color' => 'Colore bordo',
        'error' => 'Errore',
        'error_rows_nan' => 'Rows is not a number',
        'error_columns_nan' => 'Columns is not a number',
        'error_width_nan' => 'Width non è un numero',
        'error_height_nan' => 'Height non è un numero',
        'error_border_nan' => 'Border non è un numero',
        'error_cellpadding_nan' => 'Cell padding non è un numero',
        'error_cellspacing_nan' => 'Cell spacing non è un numero',
    ],
    'table_cell_prop' => [
        'title' => 'Proprietà cella',
        'horizontal_align' => 'Allineamento orizontale',
        'vertical_align' => 'Allineamento verticale',
        'width' => 'Width',
        'height' => 'Height',
        'css_class' => 'classe CSS',
        'no_wrap' => 'No wrap',
        'bg_color' => 'Colore sfondo',
        'ok' => '   OK   ',
        'cancel' => 'Cancela',
        'left' => 'Sinistra',
        'center' => 'Centro',
        'right' => 'Destra',
        'top' => 'Sopra',
        'middle' => 'Metà altezza',
        'bottom' => 'Sotto',
        'baseline' => 'in linea alla base',
        'error' => 'Errore',
        'error_width_nan' => 'Width non è un numero',
        'error_height_nan' => 'Height non è un numero',
    ],
    'table_row_insert' => [
        'title' => 'Inserisci righa',
    ],
    'table_column_insert' => [
        'title' => 'Inserisci colonna',
    ],
    'table_row_delete' => [
        'title' => 'Cancella righa',
    ],
    'table_column_delete' => [
        'title' => 'Cancella colonna',
    ],
    'table_cell_merge_right' => [
        'title' => 'Unisci righe',
    ],
    'table_cell_merge_down' => [
        'title' => 'Unisci sotto',
    ],
    'table_cell_split_horizontal' => [
        'title' => 'Spezza le celle orizontalmente',
    ],
    'table_cell_split_vertical' => [
        'title' => 'Spezza le celle verticalmente',
    ],
    'style' => [
        'title' => 'Stile',
    ],
    'font' => [
        'title' => 'Carattere',
    ],
    'fontsize' => [
        'title' => 'Dimensione',
    ],
    'paragraph' => [
        'title' => 'Paragrapo',
    ],
    'bold' => [
        'title' => 'Grassetto',
    ],
    'italic' => [
        'title' => 'Corsivo',
    ],
    'underline' => [
        'title' => 'Sottolineato',
    ],
    'ordered_list' => [
        'title' => 'Lista ordinata',
    ],
    'bulleted_list' => [
        'title' => 'Elenco puntato',
    ],
    'indent' => [
        'title' => 'Rientranza a destra',
    ],
    'unindent' => [
        'title' => 'Rientranza a sinistra',
    ],
    'left' => [
        'title' => 'Sinistra',
    ],
    'center' => [
        'title' => 'Centro',
    ],
    'right' => [
        'title' => 'Destra',
    ],
    'fore_color' => [
        'title' => 'Colore primo piano',
    ],
    'bg_color' => [
        'title' => 'Colore di sfondo',
    ],

    'design_tab' => [
        'title' => 'Cambia in modalità WYSIWYG (grafica)',
    ],
    'html_tab' => [
        'title' => 'Cambia in modalità HTML (codice)',
    ],
    'colorpicker' => [
        'title' => 'Raccoglitore di colori',
        'ok' => '   OK   ',
        'cancel' => 'Cancella',
    ],
    // <<<<<<<<< NEW >>>>>>>>>
    'cleanup' => [
        'title' => 'HTML cleanup (remove styles)',
        'confirm' => 'Performing this action will remove all styles, fonts and useless tags from the current content. Some or all your formatting may be lost.',
        'ok' => '   OK   ',
        'cancel' => 'Cancel',
    ],
    'toggle_borders' => [
        'title' => 'Toggle borders',
    ],
    'hyperlink' => [
        'title' => 'Hyperlink',
        'url' => 'URL',
        'name' => 'Name',
        'target' => 'Target',
        'title_attr' => 'Title',
        'ok' => '   OK   ',
        'cancel' => 'Cancel',
    ],
    'table_row_prop' => [
        'title' => 'Row properties',
        'horizontal_align' => 'Horizontal align',
        'vertical_align' => 'Vertical align',
        'css_class' => 'CSS class',
        'no_wrap' => 'No wrap',
        'bg_color' => 'Background color',
        'ok' => '   OK   ',
        'cancel' => 'Cancel',
        'left' => 'Left',
        'center' => 'Center',
        'right' => 'Right',
        'top' => 'Top',
        'middle' => 'Middle',
        'bottom' => 'Bottom',
        'baseline' => 'Baseline',
    ],
    'symbols' => [
        'title' => 'Special characters',
        'ok' => '   OK   ',
        'cancel' => 'Cancel',
    ],
    'symbols' => [
        'title' => 'Special characters',
        'ok' => '   OK   ',
        'cancel' => 'Cancel',
    ],
    'templates' => [
        'title' => 'Templates',
    ],
    'page_prop' => [
        'title' => 'Page properties',
        'title_tag' => 'Title',
        'charset' => 'Charset',
        'background' => 'Background image',
        'bgcolor' => 'Background color',
        'text' => 'Text color',
        'link' => 'Link color',
        'vlink' => 'Visited link color',
        'alink' => 'Active link color',
        'leftmargin' => 'Left margin',
        'topmargin' => 'Top margin',
        'css_class' => 'CSS class',
        'ok' => '   OK   ',
        'cancel' => 'Cancel',
    ],
    'preview' => [
        'title' => 'Preview',
    ],
    'image_popup' => [
        'title' => 'Image popup',
    ],
    'zoom' => [
        'title' => 'Zoom',
    ],
];


