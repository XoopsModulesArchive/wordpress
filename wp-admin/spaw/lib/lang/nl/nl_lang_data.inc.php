<?php
// ================================================
// SPAW PHP WYSIWYG editor control
// ================================================
// Dutch language file
// ================================================
// Developed: Alan Mendelevich, alan@solmetra.lt
// Copyright: Solmetra (c)2003 All rights reserved.
// Translation to dutch: Koen Koppens, k.koppens@home.nl
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
        'title' => 'Knippen',
    ],
    'copy' => [
        'title' => 'Kopiëren',
    ],
    'paste' => [
        'title' => 'Plakken',
    ],
    'undo' => [
        'title' => 'Ongedaan maken',
    ],
    'redo' => [
        'title' => 'Opnieuw',
    ],
    'hyperlink' => [
        'title' => 'Hyperlink',
    ],
    'image_insert' => [
        'title' => 'Afbeelding invoeren',
        'select' => 'Selecteren',
        'cancel' => 'Annuleren',
        'library' => 'Bibliotheek',
        'preview' => 'Voorbeeld',
        'images' => 'Afbeeldingen',
        'upload' => 'Afbeelding uploaden',
        'upload_button' => 'Upload',
        'error' => 'Fout',
        'error_no_image' => 'Selecteer een afbeelding',
        'error_uploading' => 'Er is een fout opgetreden bij het uploaden van het bestand. Probeert u het later nogmaals',
        'error_wrong_type' => 'Verkeerd afbeelding bestandstype',
        'error_no_dir' => 'De bibliotheek is niet beschikbaar',
    ],
    'image_prop' => [
        'title' => 'Afbeelding eigenschappen',
        'ok' => '   OK   ',
        'cancel' => 'Annuleren',
        'source' => 'Bron',
        'alt' => 'Alternatieve tekst',
        'align' => 'Uitlijnen',
        'left' => 'linkerkant',
        'right' => 'rechterkant',
        'top' => 'bovenkant',
        'middle' => 'midden',
        'bottom' => 'bottom',
        'absmiddle' => 'absolute midden',
        'texttop' => 'bovenkant tekst',
        'baseline' => 'Onderste lijn',
        'width' => 'Breedte',
        'height' => 'Hoogte',
        'border' => 'Rand',
        'hspace' => 'Hor. ruimte',
        'vspace' => 'Vert. ruimte',
        'error' => 'Fout',
        'error_width_nan' => 'Breedte is geen getal',
        'error_height_nan' => 'Hoogte is geen getal',
        'error_border_nan' => 'Rand is geen getal',
        'error_hspace_nan' => 'Horizontale ruimte is geen getal',
        'error_vspace_nan' => 'Vertikale ruimte is geen getal',
    ],
    'hr' => [
        'title' => 'Horizontale liniëring',
    ],
    'table_create' => [
        'title' => 'Tabel crëeren',
    ],
    'table_prop' => [
        'title' => 'Tabel eigenschappen',
        'ok' => '   OK   ',
        'cancel' => 'Annuleren',
        'rows' => 'Rijen',
        'columns' => 'Kolommen',
        'width' => 'Breedte',
        'height' => 'Hoogte',
        'border' => 'Rand',
        'pixels' => 'pixels',
        'cellpadding' => 'Cel opvulling',
        'cellspacing' => 'Cel opvulling',
        'bg_color' => 'Achtergrond kleur',
        'error' => 'Fout',
        'error_rows_nan' => 'Opgegeven rij(en) zijn geen getallen',
        'error_columns_nan' => 'Opgegeven kolom(men) zijn geen getallen',
        'error_width_nan' => 'Breedte is geen getal',
        'error_height_nan' => 'Hoogte is geen getal',
        'error_border_nan' => 'Rand is geen getal',
        'error_cellpadding_nan' => 'Cel opvulling is geen getal',
        'error_cellspacing_nan' => 'Cel opvulling is geen getal',
    ],
    'table_cell_prop' => [
        'title' => 'Cel eigenschappen',
        'horizontal_align' => 'Horizontale uitlijning',
        'vertical_align' => 'Vertikale uitlijning',
        'width' => 'Breedte',
        'height' => 'Hoogte',
        'css_class' => 'CSS klasse',
        'no_wrap' => 'Regels niet afbreken',
        'bg_color' => 'Achtergrond kleur',
        'ok' => '   OK   ',
        'cancel' => 'Annulerenl',
        'left' => 'Linkerkant',
        'center' => 'Centreren',
        'right' => 'Rechterkant',
        'top' => 'Bovenkant',
        'middle' => 'Midden',
        'bottom' => 'Onderkant',
        'baseline' => 'Onderste lijn',
        'error' => 'Fout',
        'error_width_nan' => 'Breedte is geen getal',
        'error_height_nan' => 'Hoogte is geen getal',
    ],
    'table_row_insert' => [
        'title' => 'Rij invoeren',
    ],
    'table_column_insert' => [
        'title' => 'Kolom invoeren',
    ],
    'table_row_delete' => [
        'title' => 'Rij verwijderen',
    ],
    'table_column_delete' => [
        'title' => 'Kolom verwijderen',
    ],
    'table_cell_merge_right' => [
        'title' => 'Naar rechts samenvoegen',
    ],
    'table_cell_merge_down' => [
        'title' => 'Naar beneden samenvoegen',
    ],
    'table_cell_split_horizontal' => [
        'title' => 'Cellen horizontaal splitsen',
    ],
    'table_cell_split_vertical' => [
        'title' => 'Cellen vertikaal splitsen',
    ],
    'style' => [
        'title' => 'Opmaak',
    ],
    'font' => [
        'title' => 'Lettertype',
    ],
    'fontsize' => [
        'title' => 'Grootte',
    ],
    'paragraph' => [
        'title' => 'Alinea',
    ],
    'bold' => [
        'title' => 'Vet',
    ],
    'italic' => [
        'title' => 'Schuingedrukt',
    ],
    'underline' => [
        'title' => 'Onderstrepen',
    ],
    'ordered_list' => [
        'title' => 'Genummerde opsomming',
    ],
    'bulleted_list' => [
        'title' => 'Ongesorteerde opsomming',
    ],
    'indent' => [
        'title' => 'Inspringen',
    ],
    'unindent' => [
        'title' => 'Terugspringen',
    ],
    'left' => [
        'title' => 'Links',
    ],
    'center' => [
        'title' => 'Midden',
    ],
    'right' => [
        'title' => 'Rechts',
    ],
    'fore_color' => [
        'title' => 'Voorgrond kleur',
    ],
    'bg_color' => [
        'title' => 'Achtergrond kleur',
    ],
    'design_tab' => [
        'title' => 'Schakel over naar WYSIWYG (ontwerp) modus',
    ],
    'html_tab' => [
        'title' => 'Schakel over naar HTML (kode) modus',
    ],
    'colorpicker' => [
        'title' => 'Kleur selecteren',
        'ok' => '   OK   ',
        'cancel' => 'Annuleren',
    ],
    'cleanup' => [
        'title' => 'HTML opruimen (verwijder opmaak)',
        'confirm' => 'Als u deze actie uitvoert, zal alle opmaak, lettertypen en onnodige tags worden verwijdert van de huidige opmaak. Een deel of de gehele opmaak van uw tekst zal verloren gaan.',
        'ok' => '   OK   ',
        'cancel' => 'Annuleren',
    ],
    'toggle_borders' => [
        'title' => 'Overschakeleren rand',
    ],
    'hyperlink' => [
        'title' => 'Hyperlink',
        'url' => 'URL',
        'name' => 'Naam',
        'target' => 'Doel',
        'title_attr' => 'Titel',
        'ok' => '   OK   ',
        'cancel' => 'Annuleren',
    ],
    'table_row_prop' => [
        'title' => 'Rij eigenschappen',
        'horizontal_align' => 'Horizontaal uitlijnen',
        'vertical_align' => 'Verticaal uitlijnen',
        'css_class' => 'CSS klasse',
        'no_wrap' => 'Tekst niet uitvullen',
        'bg_color' => 'Achtergrond kleur',
        'ok' => '   OK   ',
        'cancel' => 'Annuleren',
        'left' => 'Links',
        'center' => 'Midden',
        'right' => 'Rechts',
        'top' => 'Bovenkant',
        'middle' => 'Midden',
        'bottom' => 'Onderkant',
        'baseline' => 'Onderste lijn',
    ],
    'symbols' => [
        'title' => 'Speciale karakters',
        'ok' => '   OK   ',
        'cancel' => 'Annuleren',
    ],
    'templates' => [
        'title' => 'Sjablonen',
    ],
    'page_prop' => [
        'title' => 'Pagina instellingen',
        'title_tag' => 'Titel',
        'charset' => 'Karakterset',
        'background' => 'Achtergrond afbeelding',
        'bgcolor' => 'Achtergrond kleur',
        'text' => 'Tekst kleur',
        'link' => 'Link leuk',
        'vlink' => 'Bezochte link kleur',
        'alink' => 'Actieve link kleur',
        'leftmargin' => 'Marge linkerkant',
        'topmargin' => 'Marge bovenkant',
        'css_class' => 'CSS klasse',
        'ok' => '   OK   ',
        'cancel' => 'Annuleren',
    ],
    'preview' => [
        'title' => 'Voorbeeld',
    ],
    'image_popup' => [
        'title' => 'Afbeelding popup',
    ],
    'zoom' => [
        'title' => 'Zoom in',
    ],
];


