<?php
// ================================================
// SPAW PHP WYSIWYG editor control
// ================================================
// Finnish language file
// ================================================
// Developed: Alan Mendelevich, alan@solmetra.lt
// Copyright: Solmetra (c)2003 All rights reserved.
// Finnish translation: Teemu Joensuu teemu.joensuu@saunalahti.fi
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
        'title' => 'Leikkaa',
    ],
    'copy' => [
        'title' => 'Kopioi',
    ],
    'paste' => [
        'title' => 'Liitä',
    ],
    'undo' => [
        'title' => 'Kumoa',
    ],
    'redo' => [
        'title' => 'Tee uudelleen',
    ],
    'hyperlink' => [
        'title' => 'Linkki',
    ],
    'image_insert' => [
        'title' => 'Lisää kuva',
        'select' => 'Valitse',
        'cancel' => 'Peruuta',
        'library' => 'Kirjasto',
        'preview' => 'Esikatselu',
        'images' => 'Kuvat',
        'upload' => 'Lähetä kuva palvelimelle',
        'upload_button' => 'Lähetä',
        'error' => 'Virhe',
        'error_no_image' => 'Et valinnut kuvaa listalta.',
        'error_uploading' => 'Kuvan palvelimelle lähetyksessä esiintyi virhe. Yritä myöhemmin uudelleen.',
        'error_wrong_type' => 'Lähettämäsi tiedosto ei ollut tuettua tiedostomuotoa',
        'error_no_dir' => 'Kirjastoa ei ole fyysisesti olemassa.',
    ],
    'image_prop' => [
        'title' => 'Kuvan ominaisuudet',
        'ok' => '   OK   ',
        'cancel' => 'Peruuta',
        'source' => 'Kuva',
        'alt' => 'Kuvaus',
        'align' => 'Suhde tekstiin',
        'left' => 'kuva vasemmalla, teksti kiertää',
        'right' => 'kuva oikealla, teksti kiertää',
        'top' => 'teksti asettuu kuvan yläreunaan',
        'middle' => 'teksti asettuu kuvan keskikork.',
        'bottom' => 'teksti asettuu kuvan alareunaan',
        'absmiddle' => 'absmiddle',
        'texttop' => 'texttop',
        'baseline' => 'baseline',
        'width' => 'Leveys',
        'height' => 'Korkeus',
        'border' => 'Reunus',
        'hspace' => 'Vaakas. tyhjä tila',
        'vspace' => 'Pystys. tyhjä tila',
        'error' => 'Virhe',
        'error_width_nan' => 'Leveyden arvo ei ole numero',
        'error_height_nan' => 'Korkeuden arvo ei ole numero',
        'error_border_nan' => 'Reunuksen arvo ei ole numero',
        'error_hspace_nan' => 'Vaakasuoran tyhjän tilan arvo ei ole numero',
        'error_vspace_nan' => 'Pystysuoran tyhjän tilan arvo ei ole numero',
    ],
    'hr' => [
        'title' => 'Vaakaviiva',
    ],
    'table_create' => [
        'title' => 'Luo taulukko',
    ],
    'table_prop' => [
        'title' => 'Taulukon ominaisuudet',
        'ok' => '   OK   ',
        'cancel' => 'Peruuta',
        'rows' => 'Rivejä',
        'columns' => 'Sarakkeita',
        'width' => 'Leveys',
        'height' => 'Korkeus',
        'border' => 'Reunaviiva',
        'pixels' => 'kuvapistettä',
        'cellpadding' => 'Tekstin etäisyys solun reunasta',
        'cellspacing' => 'Solujen välinen tyhjä tila',
        'bg_color' => 'Taustaväri',
        'error' => 'Virhe',
        'error_rows_nan' => 'Rivimäärän arvo ei ole numero',
        'error_columns_nan' => 'Sarakemäärän arvo ei ole numero',
        'error_width_nan' => 'Leveyden arvo ei ole numero',
        'error_height_nan' => 'Korkeuden arvo ei ole numero',
        'error_border_nan' => 'Reunuksen arvo ei ole numero',
        'error_cellpadding_nan' => 'Tekstin etäisyys solun reunasta -kentän arvo ei ole numero',
        'error_cellspacing_nan' => 'Solujen välinen tyhjä tila -arvo ei ole numero',
    ],
    'table_cell_prop' => [
        'title' => 'Taulukon solun ominaisuudet',
        'horizontal_align' => 'Tasaus vaakasuunnassa',
        'vertical_align' => 'Tasaus pystysuunnassa',
        'width' => 'Leveys',
        'height' => 'Korkeus',
        'css_class' => 'CSS luokka',
        'no_wrap' => 'No wrap',
        'bg_color' => 'Taustaväri',
        'ok' => '   OK   ',
        'cancel' => 'Peruuta',
        'left' => 'Vasen',
        'center' => 'Keskitä',
        'right' => 'Oikea',
        'top' => 'Ylös',
        'middle' => 'Keskelle',
        'bottom' => 'Alas',
        'baseline' => 'Baseline',
        'error' => 'Virhe',
        'error_width_nan' => 'Leveyden arvo ei ole numero',
        'error_height_nan' => 'Korkeuden arvo ei ole numero',
    ],
    'table_row_insert' => [
        'title' => 'Lisää rivi taulukkoon',
    ],
    'table_column_insert' => [
        'title' => 'Lisää sarake taulukkoon',
    ],
    'table_row_delete' => [
        'title' => 'Poista rivi taulukosta',
    ],
    'table_column_delete' => [
        'title' => 'Poista sarake taulukosta',
    ],
    'table_cell_merge_right' => [
        'title' => 'Yhdistä oikealla puolella olevaan soluun',
    ],
    'table_cell_merge_down' => [
        'title' => 'Yhdistä alapuolella olevaan soluun',
    ],
    'table_cell_split_horizontal' => [
        'title' => 'Jaa solu vaakasuunnassa',
    ],
    'table_cell_split_vertical' => [
        'title' => 'Jaa solu pystysuunnassa',
    ],
    'style' => [
        'title' => 'Tyyli',
    ],
    'font' => [
        'title' => 'Fontti',
    ],
    'fontsize' => [
        'title' => 'Koko',
    ],
    'paragraph' => [
        'title' => 'Kappale',
    ],
    'bold' => [
        'title' => 'Lihavoi',
    ],
    'italic' => [
        'title' => 'Kursivoi',
    ],
    'underline' => [
        'title' => 'Alleviivaa',
    ],
    'ordered_list' => [
        'title' => 'Numeroitu luettelo',
    ],
    'bulleted_list' => [
        'title' => 'Luettelomerkit',
    ],
    'indent' => [
        'title' => 'Sisennä',
    ],
    'unindent' => [
        'title' => 'Poista sisennystä',
    ],
    'left' => [
        'title' => 'Tasaa vasempaan reunaan',
    ],
    'center' => [
        'title' => 'Keskitä',
    ],
    'right' => [
        'title' => 'Tasaa oikeaan reunaan',
    ],
    'fore_color' => [
        'title' => 'Tekstin väri',
    ],
    'bg_color' => [
        'title' => 'Tekstin taustaväri',
    ],
    'design_tab' => [
        'title' => 'Vaihda sisältöeditorin tekstinkäsittelyn kaltaiseen  WYSIWYG (design) -tilaan.',
    ],
    'html_tab' => [
        'title' => 'Vaihda HTML-kooditilaan',
    ],
    'colorpicker' => [
        'title' => 'Värivalitsin',
        'ok' => '   OK   ',
        'cancel' => 'Peruuta',
    ],
    'cleanup' => [
        'title' => 'HTML-koodin puhdistus (poistaa tyylimäärittelyt)',
        'confirm' => 'Tämä toiminto poistaa tämän sivun sisällöstä kaikki tyylimäärittelyt, fonttimäärittelyt ja tarpeettomat komennot. Kaikki tekstin muotoilu tai osa muotoilusta voi kadota.',
        'ok' => '   OK   ',
        'cancel' => 'Peruuta',
    ],
    'toggle_borders' => [
        'title' => 'Näytä/Piilota reunuksettomien taulukkojen reunat',
    ],
    'hyperlink' => [
        'title' => 'Linkki',
        'url' => 'Kohdeosoite (URL)',
        'name' => 'Nimi',
        'target' => 'Target (kohdeikkuna)',
        'title_attr' => 'Otsikko',
        'ok' => '   OK   ',
        'cancel' => 'Peruuta',
    ],
    'table_row_prop' => [
        'title' => 'Taulukon rivin ominaisuudet',
        'horizontal_align' => 'Tasaus vaakasuunnassa',
        'vertical_align' => 'Tasaus Pystysuunnassa',
        'css_class' => 'CSS luokka',
        'no_wrap' => 'No wrap',
        'bg_color' => 'Taustaväri',
        'ok' => '   OK   ',
        'cancel' => 'Peruuta',
        'left' => 'Vasen',
        'center' => 'Keskitä',
        'right' => 'Oikea',
        'top' => 'Ylös',
        'middle' => 'Keskelle',
        'bottom' => 'Alas',
        'baseline' => 'Alareunaan',
    ],
    'symbols' => [
        'title' => 'Erikoismerkit',
        'ok' => '   OK   ',
        'cancel' => 'Peruuta',
    ],
    'templates' => [
        'title' => 'Ulkoasupohjat',
    ],
    'page_prop' => [
        'title' => 'Sivun ominaisuudet',
        'title_tag' => 'Otsikko (Title)',
        'charset' => 'Charset',
        'background' => 'Taustakuva',
        'bgcolor' => 'Taustaväri',
        'text' => 'Tekstin väri',
        'link' => 'Linkin väri',
        'vlink' => 'Vieraillun linkin väri',
        'alink' => 'Aktiivisen linkin väri',
        'leftmargin' => 'Vasen reunus',
        'topmargin' => 'Yläreunus',
        'css_class' => 'CSS luokka',
        'ok' => '   OK   ',
        'cancel' => 'Peruuta',
    ],
    'preview' => [
        'title' => 'Esikatselu',
    ],
    'image_popup' => [
        'title' => 'Ponnahduskuva',
    ],
    'zoom' => [
        'title' => 'Zoomaa',
    ],
];


