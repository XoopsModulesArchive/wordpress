<?php
// ================================================
// SPAW PHP WYSIWYG editor control
// ================================================
// Polish language file
// ================================================
// Developed: Alan Mendelevich, alan@solmetra.lt
// Copyright: Solmetra (c)2003 All rights reserved.
// Polish translation: Jacek Czekala (jacek_czekala@post.pl)
// ------------------------------------------------
//                                www.solmetra.com
// ================================================
// v.1.0, 2003-06-28
// ================================================

// charset to be used in dialogs
$spaw_lang_charset = 'iso-8859-2';

// language text data array
// first dimension - block, second - exact phrase
// alternative text for toolbar buttons and title for dropdowns - 'title'

$spaw_lang_data = [
    'cut' => [
        'title' => 'Wytnij',
    ],
    'copy' => [
        'title' => 'Kopiuj',
    ],
    'paste' => [
        'title' => 'Wklej',
    ],
    'undo' => [
        'title' => 'Cofnij',
    ],
    'redo' => [
        'title' => 'Ponów',
    ],
    'hyperlink' => [
        'title' => 'Odsy³acz',
    ],
    'image_insert' => [
        'title' => 'Wstaw obrazek',
        'select' => 'Wybierz',
        'cancel' => 'Anuluj',
        'library' => 'Biblioteka',
        'preview' => 'Podgl±d',
        'images' => 'Obrazki',
        'upload' => 'Wy¶lij obrazek',
        'upload_button' => 'Wy¶lij',
        'error' => 'B³±d',
        'error_no_image' => 'Proszê wybraæ obrazek',
        'error_uploading' => 'Przy wysy³aniu obrazka wyst±pi³ b³±d. Proszê spróbowaæ pó¼niej.',
        'error_wrong_type' => 'Niew³a¶ciwy typ pliku obrazka',
        'error_no_dir' => 'Brak biblioteki obrazków',
    ],
    'image_prop' => [
        'title' => 'W³a¶ciwo¶ci obrazka',
        'ok' => '   OK   ',
        'cancel' => 'Anuluj',
        'source' => '¦cie¿ka',
        'alt' => 'Tekst alternatywny',
        'align' => 'Wyrównanie',
        'left' => 'left',
        'right' => 'right',
        'top' => 'top',
        'middle' => 'middle',
        'bottom' => 'bottom',
        'absmiddle' => 'absmiddle',
        'texttop' => 'texttop',
        'baseline' => 'baseline',
        'width' => 'Szeroko¶æ',
        'height' => 'Wysoko¶æ',
        'border' => 'Obramowanie',
        'hspace' => 'Odstêp poziomy',
        'vspace' => 'Odstêp pionowy',
        'error' => 'B³±d',
        'error_width_nan' => 'Szeroko¶æ nie jest liczb±',
        'error_height_nan' => 'Wysoko¶æ nie jest liczb±',
        'error_border_nan' => 'Ramka nie jest liczb±',
        'error_hspace_nan' => 'Odstêp poziomy nie jest liczb±',
        'error_vspace_nan' => 'Odstêp pionowy nie jest liczb±',
    ],
    'hr' => [
        'title' => 'Linia pozioma',
    ],
    'table_create' => [
        'title' => 'Wstaw tabelê',
    ],
    'table_prop' => [
        'title' => 'W³a¶ciwo¶ci tabeli',
        'ok' => '   OK   ',
        'cancel' => 'Anuluj',
        'rows' => 'Liczba wierszy',
        'columns' => 'Liczba kolumn',
        'width' => 'Szeroko¶æ',
        'height' => 'Wysoko¶æ',
        'border' => 'Obramowanie',
        'pixels' => 'pikseli',
        'cellpadding' => 'Margines komórki',
        'cellspacing' => 'Obramowanie komórki',
        'bg_color' => 'Kolor t³a',
        'error' => 'B³±d',
        'error_rows_nan' => 'Liczba wierszy nie jest liczb±',
        'error_columns_nan' => 'Liczba kolumn nie jest liczb±',
        'error_width_nan' => 'Szeroko¶æ nie jest liczb±',
        'error_height_nan' => 'Wysoko¶æ nie jest liczb±',
        'error_border_nan' => 'Obramowanie nie jest liczb±',
        'error_cellpadding_nan' => 'Margines komórki nie jest liczb±',
        'error_cellspacing_nan' => 'Obramowanie komórki nie jest liczb±',
    ],
    'table_cell_prop' => [
        'title' => 'W³a¶ciwo¶ci komórki',
        'horizontal_align' => 'Wyrównanie w poziomie',
        'vertical_align' => 'Wyrównanie w pionie',
        'width' => 'Szeroko¶æ',
        'height' => 'Wysoko¶æ',
        'css_class' => 'styl CSS',
        'no_wrap' => 'Blokuj dzielenie akapitu',
        'bg_color' => 'Kolor t³a',
        'ok' => '   OK   ',
        'cancel' => 'Anuluj',
        'left' => 'Do lewej',
        'center' => 'Do ¶rodka',
        'right' => 'Do prawej',
        'top' => 'Do góry',
        'middle' => 'Do ¶rodka',
        'bottom' => 'Do do³u',
        'baseline' => 'Do linii bazowej',
        'error' => 'B³±d',
        'error_width_nan' => 'Szeroko¶æ nie jest liczb±',
        'error_height_nan' => 'Wysoko¶æ nie jest liczb±',
    ],
    'table_row_insert' => [
        'title' => 'Wstaw wiersz',
    ],
    'table_column_insert' => [
        'title' => 'Wstaw kolumnê',
    ],
    'table_row_delete' => [
        'title' => 'Usuñ wiersz',
    ],
    'table_column_delete' => [
        'title' => 'Usuñ kolumnê',
    ],
    'table_cell_merge_right' => [
        'title' => 'Po³±cz z praw±',
    ],
    'table_cell_merge_down' => [
        'title' => 'Po³±cz z doln±',
    ],
    'table_cell_split_horizontal' => [
        'title' => 'Podziel komórkê w poziomie',
    ],
    'table_cell_split_vertical' => [
        'title' => 'Podziel komórkê w pionie',
    ],
    'style' => [
        'title' => 'Styl',
    ],
    'font' => [
        'title' => 'Czcionka',
    ],
    'fontsize' => [
        'title' => 'Rozmiar',
    ],
    'paragraph' => [
        'title' => 'Akapit',
    ],
    'bold' => [
        'title' => 'Pogrubienie',
    ],
    'italic' => [
        'title' => 'Kursywa',
    ],
    'underline' => [
        'title' => 'Podkre¶lenie',
    ],
    'ordered_list' => [
        'title' => 'Numerowanie',
    ],
    'bulleted_list' => [
        'title' => 'Wypunktowanie',
    ],
    'indent' => [
        'title' => 'Zwiêksz wciêcie',
    ],
    'unindent' => [
        'title' => 'Zmniejsz wciêcie',
    ],
    'left' => [
        'title' => 'Wyrównaj do lewej',
    ],
    'center' => [
        'title' => 'Wy¶rodkuj',
    ],
    'right' => [
        'title' => 'Wyrównaj do prawej',
    ],
    'fore_color' => [
        'title' => 'Kolor czcionki',
    ],
    'bg_color' => [
        'title' => 'Kolor t³a',
    ],
    'design_tab' => [
        'title' => 'Prze³±cz w tryb podgl±du (WYSIWYG)',
    ],
    'html_tab' => [
        'title' => 'Prze³±cz w tryb HTML (kod)',
    ],
    'colorpicker' => [
        'title' => 'Wybór koloru',
        'ok' => '   OK   ',
        'cancel' => 'Anuluj',
    ],
    'cleanup' => [
        'title' => 'czyszczenie HTML (usuwanie styli)',
        'confirm' => 'Przeprowadzenie tej operacji usunie wszystkie style, okre¶lenia czcionek i zbêdne znaczniki z bie¿±cej tre¶ci. Czê¶æ lub ca³o¶æ formatowania mo¿e zostaæ utracona.',
        'ok' => '   OK   ',
        'cancel' => 'Anuluj',
    ],
    'toggle_borders' => [
        'title' => 'Prze³±cz obramowania',
    ],
    'hyperlink' => [
        'title' => 'Odsy³acz',
        'url' => 'Adres URL',
        'name' => 'Nazwa',
        'target' => 'Okno docelowe',
        'title_attr' => 'Tytu³',
        'ok' => '   OK   ',
        'cancel' => 'Anuluj',
    ],
    'table_row_prop' => [
        'title' => 'W³a¶ciwosci wiersza',
        'horizontal_align' => 'Wyrównanie w poziomie',
        'vertical_align' => 'Wyrównanie w pionie',
        'css_class' => 'styl CSS',
        'no_wrap' => 'Blokuj dzielenie akapitu',
        'bg_color' => 'Kolor t³a',
        'ok' => '   OK   ',
        'cancel' => 'Anuluj',
        'left' => 'Wyrównaj do lewej',
        'center' => 'Do ¶rodka',
        'right' => 'Do prawej',
        'top' => 'Do góry',
        'middle' => 'Do ¶rodka',
        'bottom' => 'Do do³u',
        'baseline' => 'Do linii bazowej',
    ],
    'symbols' => [
        'title' => 'Znaki specjalne',
        'ok' => '   OK   ',
        'cancel' => 'Anuluj',
    ],
    'templates' => [
        'title' => 'Szablony',
    ],
    'page_prop' => [
        'title' => 'W³a¶ciwo¶ci strony',
        'title_tag' => 'Tyty³',
        'charset' => 'Strona kodowa',
        'background' => 'Obraz t³a',
        'bgcolor' => 'Kolor t³a',
        'text' => 'Kolor tekstu',
        'link' => 'Kolor odsy³acza',
        'vlink' => 'Kolor wybranego odsy³acza',
        'alink' => 'Kolor aktywnego odsy³acza',
        'leftmargin' => 'Margines lewy',
        'topmargin' => 'Margines górny',
        'css_class' => 'Styl CSS',
        'ok' => '   OK   ',
        'cancel' => 'Anuluj',
    ],
    'preview' => [
        'title' => 'Podgl±d',
    ],
    'image_popup' => [
        'title' => 'Image popup',
    ],
    'zoom' => [
        'title' => 'Zoom',
    ],
];


