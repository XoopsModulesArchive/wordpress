<?php
// =========================================================
// SPAW PHP WYSIWYG editor control
// =========================================================
// German language file
// =========================================================
// Developed: Alan Mendelevich, alan@solmetra.lt
// German translation: Simon Schmitz, schmitz@unitedfuor.com
// Corrections: Matthias Höschele, matthias.hoeschele@gmx.net
// Copyright: Solmetra (c)2003 All rights reserved.
// ---------------------------------------------------------
//                                www.solmetra.com
// =========================================================
// v.1.0, 2003-04-10
// =========================================================

// charset to be used in dialogs
$spaw_lang_charset = 'iso-8859-1';

// language text data array
// first dimension - block, second - exact phrase
// alternative text for toolbar buttons and title for dropdowns - 'title'

$spaw_lang_data = [
    'cut' => [
        'title' => 'Ausschneiden',
    ],
    'copy' => [
        'title' => 'Kopieren',
    ],
    'paste' => [
        'title' => 'Einfügen',
    ],
    'undo' => [
        'title' => 'Rückgängig',
    ],
    'redo' => [
        'title' => 'Wiederherstellen',
    ],
    'hyperlink' => [
        'title' => 'Hyperlink',
    ],
    'image_insert' => [
        'title' => 'Bild einfügen',
        'select' => 'Auswählen',
        'cancel' => 'Abbrechen',
        'library' => 'Bibliothek',
        'preview' => 'Vorschau',
        'images' => 'Bild',
        'upload' => 'Bild Hochladen',
        'upload_button' => 'Hochladen',
        'error' => 'Fehler',
        'error_no_image' => 'Wählen Sie bitte ein Bild',
        'error_uploading' => 'Ein Fehler trat bei der Übertragung der Datei auf.  Bitte Versuchen Sie es später noch einmal.',
        'error_wrong_type' => 'Unzulässiger Dateityp',
        'error_no_dir' => 'Bibliothek ist physikalisch vorhanden',
    ],
    'image_prop' => [
        'title' => 'Bildeigenschaften',
        'ok' => '   OK   ',
        'cancel' => 'Abbrechen',
        'source' => 'Quelle',
        'alt' => 'Alternativer Text',
        'align' => 'Ausrichtung',
        'left' => 'Links',
        'right' => 'Rechts',
        'top' => 'Oben',
        'middle' => 'Mitte',
        'bottom' => 'Unten',
        'absmiddle' => 'Absolute Mitte',
        'texttop' => 'TextTop',
        'baseline' => 'Grundlinie',
        'width' => 'Breite',
        'height' => 'Höhe',
        'border' => 'Rand',
        'hspace' => 'Horizontaler Abstand',
        'vspace' => 'Vertikaler Abstand',
        'error' => 'Fehler',
        'error_width_nan' => 'Die Breite ist keine Zahl',
        'error_height_nan' => 'Die Höhe ist keine Zahl',
        'error_border_nan' => 'Der Rand ist keine Zahl',
        'error_hspace_nan' => 'Horizontaler Abstand ist keine Zahl',
        'error_vspace_nan' => 'Vertikaler Abstand ist keine Zahl',
    ],
    'hr' => [
        'title' => 'Horizontale Linie',
    ],
    'table_create' => [
        'title' => 'Tabelle erstellen',
    ],
    'table_prop' => [
        'title' => 'Tabelleneigenschaften',
        'ok' => '   OK   ',
        'cancel' => 'Abbrechen',
        'rows' => 'Zeilen',
        'columns' => 'Spalten',
        'width' => 'Breite',
        'height' => 'Höhe',
        'border' => 'Rand',
        'pixels' => 'Pixel',
        'cellpadding' => 'Zellauffüllung',
        'cellspacing' => 'Zellabstand',
        'bg_color' => 'Hintergrundfarbe',
        'error' => 'Fehler',
        'error_rows_nan' => 'Die Zeilenanzahl ist keine Zahl',
        'error_columns_nan' => 'Die Spaltenanzahl ist keine Zahl',
        'error_width_nan' => 'Die Breite ist keine Zahl',
        'error_height_nan' => 'Die Höhe ist keine Zahl',
        'error_border_nan' => 'Die Randbreite ist keine Zahl',
        'error_cellpadding_nan' => 'Zellauffüllung ist keine Zahl',
        'error_cellspacing_nan' => 'Zellabstand ist keine Zahl',
    ],
    'table_cell_prop' => [
        'title' => 'Zelleigenschaften',
        'horizontal_align' => 'Horizontale Ausrichtung',
        'vertical_align' => 'Vertikale Ausrichtung',
        'width' => 'Breite',
        'height' => 'Höhe',
        'css_class' => 'CSS Klasse',
        'no_wrap' => 'Zeilenumbruch verhindern',
        'bg_color' => 'Hintergrundfarbe',
        'ok' => '   OK   ',
        'cancel' => 'Abbrechen',
        'left' => 'Links',
        'center' => 'Zentriert',
        'right' => 'Rechts',
        'top' => 'Oben',
        'middle' => 'Mitte',
        'bottom' => 'Unten',
        'baseline' => 'Grundlinie',
        'error' => 'Fehler',
        'error_width_nan' => 'Die Breite ist keine Zahl',
        'error_height_nan' => 'Die Höhe ist keine Zahl',
    ],
    'table_row_insert' => [
        'title' => 'Zeile einfügen',
    ],
    'table_column_insert' => [
        'title' => 'Spalte einfügen',
    ],
    'table_row_delete' => [
        'title' => 'Zeile löschen',
    ],
    'table_column_delete' => [
        'title' => 'Spalte löschen',
    ],
    'table_cell_merge_right' => [
        'title' => 'Zelle mit rechts daneben liegender verbinden.',
    ],
    'table_cell_merge_down' => [
        'title' => 'Zelle mit darunter liegender verbinden.',
    ],
    'table_cell_split_horizontal' => [
        'title' => 'Zelle horizontal aufteilen',
    ],
    'table_cell_split_vertical' => [
        'title' => 'Zelle vertikal aufteilen',
    ],
    'style' => [
        'title' => 'Style',
    ],
    'font' => [
        'title' => 'Schrift',
    ],
    'fontsize' => [
        'title' => 'Grösse',
    ],
    'paragraph' => [
        'title' => 'Punkt',
    ],
    'bold' => [
        'title' => 'Fett',
    ],
    'italic' => [
        'title' => 'Kursiv',
    ],
    'underline' => [
        'title' => 'Unterstrichen',
    ],
    'ordered_list' => [
        'title' => 'Nummerierung',
    ],
    'bulleted_list' => [
        'title' => 'Aufzählung',
    ],
    'indent' => [
        'title' => 'Einzug vergrössern',
    ],
    'unindent' => [
        'title' => 'Einzug verkleinern',
    ],
    'left' => [
        'title' => 'Links',
    ],
    'center' => [
        'title' => 'Zentriert',
    ],
    'right' => [
        'title' => 'Rechts',
    ],
    'fore_color' => [
        'title' => 'Schriftfarbe',
    ],
    'bg_color' => [
        'title' => 'Hintergrundfarbe',
    ],
    'design_tab' => [
        'title' => 'Zum WYSIWYG (Design) Modus wechseln',
    ],
    'html_tab' => [
        'title' => 'Zum HTML (Quelltext) Modus wechseln',
    ],
    'colorpicker' => [
        'title' => 'Farbpipette',
        'ok' => '   OK   ',
        'cancel' => 'Abbrechen',
    ],
    // <<<<<<<<< NEW >>>>>>>>>
    'cleanup' => [
        'title' => 'HTML Säuberung (Stile entfernen)',
        'confirm' => 'Das Ausführen dieser Aktion wird alle Stile, Schriften und nutzlose Tags vom aktuellen Inhalt entfernen. Die Formatierung kann teilweise oder vollständig verloren gehen.',
        'ok' => '   OK   ',
        'cancel' => 'Abbrechen',
    ],
    'toggle_borders' => [
        'title' => 'Ränder anzeigen',
    ],
    'hyperlink' => [
        'title' => 'Hyperlink',
        'url' => 'URL',
        'name' => 'Name',
        'target' => 'Ziel',
        'title_attr' => 'Titel',
        'ok' => '   OK   ',
        'cancel' => 'Abbrechen',
    ],
    'table_row_prop' => [
        'title' => 'Zeileneigenschaften',
        'horizontal_align' => 'Horizontale Ausrichtung',
        'vertical_align' => 'Vertikale Ausrichtung',
        'css_class' => 'CSS Klasse',
        'no_wrap' => 'Zeilenumbruch verhindern',
        'bg_color' => 'Hintergrundfarbe',
        'ok' => '   OK   ',
        'cancel' => 'Abbrechen',
        'left' => 'Links',
        'center' => 'Zentriert',
        'right' => 'Rechts',
        'top' => 'Oben',
        'middle' => 'Mitte',
        'bottom' => 'Unten',
        'baseline' => 'Grundlinie',
    ],
    'symbols' => [
        'title' => 'Sonderzeichen',
        'ok' => '   OK   ',
        'cancel' => 'Abbrechen',
    ],
    'symbols' => [
        'title' => 'Sonderzeichen',
        'ok' => '   OK   ',
        'cancel' => 'Abbrechen',
    ],
    'templates' => [
        'title' => 'Templates',
    ],
    'page_prop' => [
        'title' => 'Seiteneigenschaften',
        'title_tag' => 'Titel',
        'charset' => 'Dokumentkodierung',
        'background' => 'Hintergrundbild',
        'bgcolor' => 'Hintergrundfarbe',
        'text' => 'Textfarbe',
        'link' => 'Linkfarbe',
        'vlink' => 'Besuchter-Link-Farbe',
        'alink' => 'Aktiver-Link-Farbe',
        'leftmargin' => 'Linker Rand',
        'topmargin' => 'Oberer Rand',
        'css_class' => 'CSS Klasse',
        'ok' => '   OK   ',
        'cancel' => 'Abbrechen',
    ],
    'preview' => [
        'title' => 'Vorschau',
    ],
    'image_popup' => [
        'title' => 'Bild-Popup',
    ],
    'zoom' => [
        'title' => 'Zoom',
    ],
];



