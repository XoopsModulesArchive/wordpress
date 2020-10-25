<?php
// =========================================================
// SPAW PHP WYSIWYG editor control
// =========================================================
// German language file
// =========================================================
// Developed: Alan Mendelevich, alan@solmetra.lt
// Turkish translation: Zeki Erkmen, erkmen@t-online.de
// Copyright: Solmetra (c)2003 All rights reserved.
// ---------------------------------------------------------
//                                www.solmetra.com
// =========================================================
// v.1.0, 2003-04-10
// =========================================================

// charset to be used in dialogs
$spaw_lang_charset = 'iso-8859-9';

// language text data array
// first dimension - block, second - exact phrase
// alternative text for toolbar buttons and title for dropdowns - 'title'

$spaw_lang_data = [
    'cut' => [
        'title' => 'Kes',
    ],
    'copy' => [
        'title' => 'Kopyala',
    ],
    'paste' => [
        'title' => 'Ekle',
    ],
    'undo' => [
        'title' => 'Geri al',
    ],
    'redo' => [
        'title' => 'Tekrarla',
    ],
    'hyperlink' => [
        'title' => 'Link ekle',
    ],
    'image_insert' => [
        'title' => 'Resim ekle',
        'select' => 'Seç',
        'cancel' => 'Ýptal',
        'library' => 'Kütüphane',
        'preview' => 'Ön izle',
        'images' => 'Resim',
        'upload' => 'Resim yükle',
        'upload_button' => 'Yükle',
        'error' => 'Fehler',
        'error_no_image' => 'Lütfen bir resim seçiniz',
        'error_uploading' => 'Dosya yükleme iþleminde bir hata oluþtu. Lütfen biraz sonra tekrar deneyiniz.',
        'error_wrong_type' => 'Yanlýþ resim tipi',
        'error_no_dir' => 'Kütüphane fiziksel olarak mevcut',
    ],
    'image_prop' => [
        'title' => 'Resim ayarlarý',
        'ok' => '   OK   ',
        'cancel' => 'Ýptal',
        'source' => 'Kaynak',
        'alt' => 'Alternatif Metin',
        'align' => 'Konum',
        'left' => 'Sol',
        'right' => 'Sað',
        'top' => 'Yukarda',
        'middle' => 'Ortada',
        'bottom' => 'Alt kýsýmda',
        'absmiddle' => 'Merkezde',
        'texttop' => 'Metin üstü',
        'baseline' => 'Çizgi üzeri',
        'width' => 'Geniþlik',
        'height' => 'Yükseklik',
        'border' => 'Çerceve',
        'hspace' => 'Yatay boþluk',
        'vspace' => 'Dikey boþluk',
        'error' => 'Hata',
        'error_width_nan' => 'Geniþlik sayý deðil',
        'error_height_nan' => 'Yükseklik sayý deðil',
        'error_border_nan' => 'Çerceve sayý deðil',
        'error_hspace_nan' => 'Yatay boþluk sayý deðil',
        'error_vspace_nan' => 'Dikey boþluk sayý deðil',
    ],
    'hr' => [
        'title' => 'Yatay çizgi',
    ],
    'table_create' => [
        'title' => 'Tabela oluþtur',
    ],
    'table_prop' => [
        'title' => 'Tabela özellikleri',
        'ok' => '   OK   ',
        'cancel' => 'Ýptal et',
        'rows' => 'Satýrlar',
        'columns' => 'Haneler',
        'width' => 'Geniþlik',
        'height' => 'Yükseklik',
        'border' => 'Çerceve',
        'pixels' => 'Pixel',
        'cellpadding' => 'Hücreyi dolumu',
        'cellspacing' => 'Hücre mesafesi',
        'bg_color' => 'Arka ekran rengi',
        'error' => 'Hata',
        'error_rows_nan' => 'Satýr rakam deðil',
        'error_columns_nan' => 'Hane rakam deðil',
        'error_width_nan' => 'Geniþlik rakam deðil',
        'error_height_nan' => 'Yükseklik rakam deðil',
        'error_border_nan' => 'Çerceve rakam deðil',
        'error_cellpadding_nan' => 'Hücre dolumu rakam deðil',
        'error_cellspacing_nan' => 'Hücre mesafesi rakam deðil',
    ],
    'table_cell_prop' => [
        'title' => 'Hücre özelliði',
        'horizontal_align' => 'Yatay konumu',
        'vertical_align' => 'Dikey konumu',
        'width' => 'Geniþlik',
        'height' => 'Yükseklik',
        'css_class' => 'CSS sýnýfý',
        'no_wrap' => 'Paketsiz',
        'bg_color' => 'Arka ekran rengi',
        'ok' => '   OK   ',
        'cancel' => 'Ýptal et',
        'left' => 'Sol',
        'center' => 'Merkezi',
        'right' => 'Sað',
        'top' => 'Üst kýsým',
        'middle' => 'Orta',
        'bottom' => 'Alt kýsým',
        'baseline' => 'Çizgi üstü',
        'error' => 'Hata',
        'error_width_nan' => 'Geniþlik rakam deðil',
        'error_height_nan' => 'Yükseklik rakam deðil',
    ],
    'table_row_insert' => [
        'title' => 'Satýr ekle',
    ],
    'table_column_insert' => [
        'title' => 'Hane ekle',
    ],
    'table_row_delete' => [
        'title' => 'Satýr sil',
    ],
    'table_column_delete' => [
        'title' => 'Hane sil',
    ],
    'table_cell_merge_right' => [
        'title' => 'Hücreyi sað taraf ile birleþtir.',
    ],
    'table_cell_merge_down' => [
        'title' => 'Hücereyi alt taraf ile birleþtir.',
    ],
    'table_cell_split_horizontal' => [
        'title' => 'Hücreyi yatay olarak böl',
    ],
    'table_cell_split_vertical' => [
        'title' => 'Hücreyi dikey olarak böl',
    ],
    'style' => [
        'title' => 'Düzenleme',
    ],
    'font' => [
        'title' => 'Yazý',
    ],
    'fontsize' => [
        'title' => 'Büyüklüðü',
    ],
    'paragraph' => [
        'title' => 'Paraðraf',
    ],
    'bold' => [
        'title' => 'Kalýn',
    ],
    'italic' => [
        'title' => 'Yatay ince',
    ],
    'underline' => [
        'title' => 'Alt çizgili',
    ],
    'ordered_list' => [
        'title' => 'Numarasal',
    ],
    'bulleted_list' => [
        'title' => 'Listesel',
    ],
    'indent' => [
        'title' => 'Dýþa çek',
    ],
    'unindent' => [
        'title' => 'Ýçe çek',
    ],
    'left' => [
        'title' => 'Sol',
    ],
    'center' => [
        'title' => 'Merkez',
    ],
    'right' => [
        'title' => 'Sað',
    ],
    'fore_color' => [
        'title' => 'Yazý rengi',
    ],
    'bg_color' => [
        'title' => 'Arka ekran rengi',
    ],
    'design_tab' => [
        'title' => 'Design Modüsüne geç',
    ],
    'html_tab' => [
        'title' => 'HTML Modüsüne geç',
    ],
    'colorpicker' => [
        'title' => 'Renk seçimi',
        'ok' => '   OK   ',
        'cancel' => 'Ýptal et',
    ],
    'cleanup' => [
        'title' => 'HTML temizleyeçi',
        'confirm' => 'Bu seçenek HTML formatlarýný (Style) Ýçeriðinizden siler. Bu komutu seçmekle ya tüm ya da bazý Style blocklarý metin içerisinden silinir ',
        'ok' => '   OK   ',
        'cancel' => 'Ýptal',
    ],
    'toggle_borders' => [
        'title' => 'Toggle borders',
    ],
    'hyperlink' => [
        'title' => 'Link ekle',
        'url' => 'URL',
        'name' => 'Adý',
        'target' => 'Hedef',
        'title_attr' => 'Baþlýk',
        'ok' => '   OK   ',
        'cancel' => 'Ýptal',
    ],
    'table_row_prop' => [
        'title' => 'Satýr özellikleri',
        'horizontal_align' => 'Yatay konum',
        'vertical_align' => 'Dikey konum',
        'css_class' => 'CSS Klasý',
        'no_wrap' => 'Paketsiz',
        'bg_color' => 'Arka ekran rengi',
        'ok' => '   OK   ',
        'cancel' => 'Ýptal',
        'left' => 'Sol',
        'center' => 'Merkez',
        'right' => 'Sað',
        'top' => 'Üst',
        'middle' => 'Orta',
        'bottom' => 'Alt',
        'baseline' => 'Çizgi üstü',
    ],
    'symbols' => [
        'title' => 'Özel karekterler',
        'ok' => '   OK   ',
        'cancel' => 'Ýptal',
    ],
    'templates' => [
        'title' => 'Kalýplar',
    ],
    'page_prop' => [
        'title' => 'Sayfa özelliði',
        'title_tag' => 'Baþlýk',
        'charset' => 'Metin Karekteri',
        'background' => 'Arka plan resmi',
        'bgcolor' => 'Arka plan rengi',
        'text' => 'Yazý rengi',
        'link' => 'Link rengi',
        'vlink' => 'Uðranýlmýþ link rengi',
        'alink' => 'Actif link rengi',
        'leftmargin' => 'Sol kenar',
        'topmargin' => 'Üst kenar',
        'css_class' => 'CSS Klasý',
        'ok' => '   OK   ',
        'cancel' => 'Ýptal',
    ],
    'preview' => [
        'title' => 'Ön gösterim',
    ],
    'image_popup' => [
        'title' => 'Resim popup',
    ],
    'zoom' => [
        'title' => 'Büyülteç',
    ],
];


