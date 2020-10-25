<?php
// ================================================
// SPAW PHP WYSIWYG editor control
// ================================================
// Bulgarian language file
// ================================================
// Developed: Alan Mendelevich, alan@solmetra.lt
// Copyright: Solmetra (c)2003 All rights reserved.
// Translated: Atanas Tchobanov, atanas@webdressy.com
// ------------------------------------------------
//                                www.solmetra.com
// ================================================
// v.1.0, 2003-04-10
// ================================================

// charset to be used in dialogs
$spaw_lang_charset = 'windows-1251';

// language text data array
// first dimension - block, second - exact phrase
// alternative text for toolbar buttons and title for dropdowns - 'title'

$spaw_lang_data = [
    'cut' => [
        'title' => 'Отрежи',
    ],
    'copy' => [
        'title' => 'Копирай',
    ],
    'paste' => [
        'title' => 'Вмъкни',
    ],
    'undo' => [
        'title' => 'Отмени',
    ],
    'redo' => [
        'title' => 'Повтори',
    ],
    'hyperlink' => [
        'title' => 'Линк',
    ],
    'image_insert' => [
        'title' => 'Вмъкни картинка',
        'select' => 'Вмъкни',
        'cancel' => 'Отмени',
        'library' => 'Библиотека',
        'preview' => 'Преглед',
        'images' => 'Картинки',
        'upload' => 'Изпрати картинка',
        'upload_button' => 'Изпрати',
        'error' => 'Грешка',
        'error_no_image' => 'Изберете картинка',
        'error_uploading' => 'Грешка при изпращането. Пробвайте пак.',
        'error_wrong_type' => 'Неправилен тип картинка',
        'error_no_dir' => 'Библиотеката не съществува',
    ],
    'image_prop' => [
        'title' => 'Параметри на картинката',
        'ok' => 'ГОТОВО',
        'cancel' => 'Отмени',
        'source' => 'Източник',
        'alt' => 'Кратко описание',
        'align' => 'Подравняване',
        'left' => 'наляво (left)',
        'right' => 'надясно (right)',
        'top' => 'горе (top)',
        'middle' => 'в центъра (middle)',
        'bottom' => 'долу (bottom)',
        'absmiddle' => 'абс. център (absmiddle)',
        'texttop' => 'отгоре (texttop)',
        'baseline' => 'отдолу (baseline)',
        'width' => 'Ширина',
        'height' => 'Височина',
        'border' => 'Рамка',
        'hspace' => 'Гор. разстояние',
        'vspace' => 'Верт. разстояние',
        'error' => 'Грешка',
        'error_width_nan' => 'Ширината трябва да е числена стойност',
        'error_height_nan' => 'Височината трябва да е числена стойност',
        'error_border_nan' => 'Рамката трябва да е числена стойност',
        'error_hspace_nan' => 'Хоризонталните полета трябва да са числена стойност',
        'error_vspace_nan' => 'Вертикалните полета трябва да са числена стойност',
    ],
    'hr' => [
        'title' => 'Хоризонтална линия',
    ],
    'table_create' => [
        'title' => 'Създай таблица',
    ],
    'table_prop' => [
        'title' => 'Параметри на таблицата',
        'ok' => 'ГОТОВО',
        'cancel' => 'Отмени',
        'rows' => 'Редове',
        'columns' => 'Колони',
        'width' => 'Ширина',
        'height' => 'Височина',
        'border' => 'Рамка',
        'pixels' => 'пикс.',
        'cellpadding' => 'Отстъп от рамката',
        'cellspacing' => 'Разстояние между клетките',
        'bg_color' => 'Цвят на фона',
        'error' => 'Грешка',
        'error_rows_nan' => 'Редовете трябва да са числена стойност',
        'error_columns_nan' => 'Колоните трябва да са числена стойност',
        'error_width_nan' => 'Ширината трябва да е числена стойност',
        'error_height_nan' => 'Височината трябва да е числена стойност',
        'error_border_nan' => 'Рамката трябва да е числена стойност',
        'error_cellpadding_nan' => 'Отстъпът от рамката трябва да е числена стойност',
        'error_cellspacing_nan' => 'Разстоянието между клетките трябва да е числена стойност',
    ],
    'table_cell_prop' => [
        'title' => 'Параметри на клетката',
        'horizontal_align' => 'Хоризонтално подравняване',
        'vertical_align' => 'Вертикално подравняване',
        'width' => 'Ширина',
        'height' => 'Височина',
        'css_class' => 'Стил',
        'no_wrap' => 'Без преноси',
        'bg_color' => 'Цвят на фона',
        'ok' => 'ГОТОВО',
        'cancel' => 'Отмени',
        'left' => 'Наляво',
        'center' => 'В центъра',
        'right' => 'Надясно',
        'top' => 'Отгоре',
        'middle' => 'В центъра',
        'bottom' => 'Отдолу',
        'baseline' => 'На базовата линия на текста',
        'error' => 'Грешка',
        'error_width_nan' => 'Ширината трябва да е числена стойност',
        'error_height_nan' => 'Височината трябва да е числена стойност',
    ],
    'table_row_insert' => [
        'title' => 'Вмъкни ред',
    ],
    'table_column_insert' => [
        'title' => 'Вмъкни колона',
    ],
    'table_row_delete' => [
        'title' => 'Премахни ред',
    ],
    'table_column_delete' => [
        'title' => 'Премахни колона',
    ],
    'table_cell_merge_right' => [
        'title' => 'Обедини надясно',
    ],
    'table_cell_merge_down' => [
        'title' => 'Обедини наляво',
    ],
    'table_cell_split_horizontal' => [
        'title' => 'Раздели хоризонтално',
    ],
    'table_cell_split_vertical' => [
        'title' => 'Раздели вертикално',
    ],
    'style' => [
        'title' => 'Стил',
    ],
    'font' => [
        'title' => 'Шрифт',
    ],
    'fontsize' => [
        'title' => 'Размер',
    ],
    'paragraph' => [
        'title' => 'параграф',
    ],
    'bold' => [
        'title' => 'Получер',
    ],
    'italic' => [
        'title' => 'Курсив',
    ],
    'underline' => [
        'title' => 'Подчертан',
    ],
    'ordered_list' => [
        'title' => 'Пронумерован списък',
    ],
    'bulleted_list' => [
        'title' => 'Обикновен списък',
    ],
    'indent' => [
        'title' => 'Увеличи отстъпа',
    ],
    'unindent' => [
        'title' => 'Намали отстъпа',
    ],
    'left' => [
        'title' => 'Подравняване наляво',
    ],
    'center' => [
        'title' => 'Подравняване по центъра',
    ],
    'right' => [
        'title' => 'Подравняване надясно',
    ],
    'fore_color' => [
        'title' => 'Цвят на текста',
    ],
    'bg_color' => [
        'title' => 'Цвят на фона',
    ],
    'design_tab' => [
        'title' => 'Превключи в режим на макетиране (WYSIWYG)',
    ],
    'html_tab' => [
        'title' => 'Превключи в режим на редактиране на кода (HTML)',
    ],
    'colorpicker' => [
        'title' => 'Избор на цвят',
        'ok' => 'ГОТОВО',
        'cancel' => 'Отмени',
    ],
    'cleanup' => [
        'title' => 'Очистване на HTML',
        'confirm' => 'Тази операция премахва всички стилове, шрифтове и ненужни тагове от съдържанието в редактора. Форматирането може да бъде загубено частично или изцяло.',
        'ok' => 'ГОТОВО',
        'cancel' => 'Отмени',
    ],
    'toggle_borders' => [
        'title' => 'Включи рамката',
    ],
    'hyperlink' => [
        'title' => 'Линк',
        'url' => 'Адрес',
        'name' => 'Име',
        'target' => 'Цел',
        'title_attr' => 'Название',
        'ok' => 'ГОТОВО',
        'cancel' => 'Отмени',
    ],
    'table_row_prop' => [
        'title' => 'Параметри на реда',
        'horizontal_align' => 'Хоризонтално подравняване',
        'vertical_align' => 'Вертикално подравняване',
        'css_class' => 'Стил',
        'no_wrap' => 'Без преноси',
        'bg_color' => 'Цвят на фона',
        'ok' => 'ГОТОВО',
        'cancel' => 'Отмени',
        'left' => 'Отляво',
        'center' => 'В центъра',
        'right' => 'Отдясно',
        'top' => 'Отгоре',
        'middle' => 'В центъра',
        'bottom' => 'Отдолу',
        'baseline' => 'По базовата линия на текста',
    ],
    'symbols' => [
        'title' => 'Спец. символи',
        'ok' => 'ГОТОВО',
        'cancel' => 'Отмени',
    ],
    'templates' => [
        'title' => 'Графични модели',
    ],
    'page_prop' => [
        'title' => 'Параметри на страницата',
        'title_tag' => 'Заглавие',
        'charset' => 'Кодова таблица',
        'background' => 'Фонова картинка',
        'bgcolor' => 'Цвят на фона',
        'text' => 'Цвят на текста',
        'link' => 'Цвят на линка',
        'vlink' => 'Цвят на посетените линкове',
        'alink' => 'Цвят на активните линкове',
        'leftmargin' => 'Отстъп отляво',
        'topmargin' => 'Отстъп отгоре',
        'css_class' => 'Стил',
        'ok' => 'ГОТОВО',
        'cancel' => 'Отмени',
    ],
    'preview' => [
        'title' => 'Предварителен преглед',
    ],
    'image_popup' => [
        'title' => 'Popup картинка',
    ],
    'zoom' => [
        'title' => 'Увеличение',
    ],
];


