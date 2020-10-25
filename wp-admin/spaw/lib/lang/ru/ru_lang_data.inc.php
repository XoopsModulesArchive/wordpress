<?php
// ================================================
// SPAW PHP WYSIWYG editor control
// ================================================
// Russian language file
// ================================================
// Developed: Alan Mendelevich, alan@solmetra.lt
// Copyright: Solmetra (c)2003 All rights reserved.
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
        'title' => 'Вырезать',
    ],
    'copy' => [
        'title' => 'Копировать',
    ],
    'paste' => [
        'title' => 'Вставить',
    ],
    'undo' => [
        'title' => 'Отменить',
    ],
    'redo' => [
        'title' => 'Повторить',
    ],
    'hyperlink' => [
        'title' => 'Ссылка',
    ],
    'image_insert' => [
        'title' => 'Вставить изображение',
        'select' => 'Вставить',
        'cancel' => 'Отменить',
        'library' => 'Библиотека',
        'preview' => 'Просмотр',
        'images' => 'Изображения',
        'upload' => 'Загрузить изображение',
        'upload_button' => 'Загрузить',
        'error' => 'Ошибка',
        'error_no_image' => 'Выберите изображение',
        'error_uploading' => 'Во время загрузки произошла ошибка. Попробуйте еще раз.',
        'error_wrong_type' => 'Неправильный тип изображения',
        'error_no_dir' => 'Библиотека не существует',
    ],
    'image_prop' => [
        'title' => 'Параметры изображения',
        'ok' => 'ГОТОВО',
        'cancel' => 'Отменить',
        'source' => 'Источник',
        'alt' => 'Краткое описание',
        'align' => 'Выравнивание',
        'left' => 'слева (left)',
        'right' => 'справа (right)',
        'top' => 'сверху (top)',
        'middle' => 'в центре (middle)',
        'bottom' => 'снизу (bottom)',
        'absmiddle' => 'абс. центр (absmiddle)',
        'texttop' => 'сверху (texttop)',
        'baseline' => 'снизу (baseline)',
        'width' => 'Ширина',
        'height' => 'Высота',
        'border' => 'Рамка',
        'hspace' => 'Гор. поля',
        'vspace' => 'Верт. поля',
        'error' => 'Ошибка',
        'error_width_nan' => 'Ширина не является числом',
        'error_height_nan' => 'Высота не является числом',
        'error_border_nan' => 'Рамка не является числом',
        'error_hspace_nan' => 'Горизонтаьные поля не является числом',
        'error_vspace_nan' => 'Вертикальные поля не является числом',
    ],
    'hr' => [
        'title' => 'Горизонтальная линия',
    ],
    'table_create' => [
        'title' => 'Создать таблицу',
    ],
    'table_prop' => [
        'title' => 'Параметры таблицы',
        'ok' => 'ГОТОВО',
        'cancel' => 'Отменить',
        'rows' => 'Строки',
        'columns' => 'Столбцы',
        'width' => 'Ширина',
        'height' => 'Высота',
        'border' => 'Рамка',
        'pixels' => 'пикс.',
        'cellpadding' => 'Отступ от рамки',
        'cellspacing' => 'Растояние между ячейками',
        'bg_color' => 'Цвет фона',
        'error' => 'Ошибка',
        'error_rows_nan' => 'Строки не является числом',
        'error_columns_nan' => 'Столбцы не является числом',
        'error_width_nan' => 'Ширина не является числом',
        'error_height_nan' => 'Высота не является числом',
        'error_border_nan' => 'Рамка не является числом',
        'error_cellpadding_nan' => 'Отступ от рамки не является числом',
        'error_cellspacing_nan' => 'Растояние между ячейками не является числом',
    ],
    'table_cell_prop' => [
        'title' => 'Параметры ячейки',
        'horizontal_align' => 'Горизонтальное выравнивание',
        'vertical_align' => 'Вертикальное выравнивание',
        'width' => 'Ширина',
        'height' => 'Высота',
        'css_class' => 'Стиль',
        'no_wrap' => 'Без переноса',
        'bg_color' => 'Цвет фона',
        'ok' => 'ГОТОВО',
        'cancel' => 'Отменить',
        'left' => 'Слева',
        'center' => 'В центре',
        'right' => 'Справа',
        'top' => 'Сверху',
        'middle' => 'В центре',
        'bottom' => 'Снизу',
        'baseline' => 'Базовая линия текста',
        'error' => 'Ошибка',
        'error_width_nan' => 'Ширина не является числом',
        'error_height_nan' => 'Высота не является числом',
    ],
    'table_row_insert' => [
        'title' => 'Вставить строку',
    ],
    'table_column_insert' => [
        'title' => 'Вставить столбец',
    ],
    'table_row_delete' => [
        'title' => 'Удалить строку',
    ],
    'table_column_delete' => [
        'title' => 'Удалить столбец',
    ],
    'table_cell_merge_right' => [
        'title' => 'Объединить вправо',
    ],
    'table_cell_merge_down' => [
        'title' => 'Объединить влево',
    ],
    'table_cell_split_horizontal' => [
        'title' => 'Разделить по горизонтали',
    ],
    'table_cell_split_vertical' => [
        'title' => 'Разделить по вертикали',
    ],
    'style' => [
        'title' => 'Стиль',
    ],
    'font' => [
        'title' => 'Шрифт',
    ],
    'fontsize' => [
        'title' => 'Размер',
    ],
    'paragraph' => [
        'title' => 'Абзац',
    ],
    'bold' => [
        'title' => 'Жирный',
    ],
    'italic' => [
        'title' => 'Курсив',
    ],
    'underline' => [
        'title' => 'Подчеркнутый',
    ],
    'ordered_list' => [
        'title' => 'Упорядоченный список',
    ],
    'bulleted_list' => [
        'title' => 'Неупорядоченный список',
    ],
    'indent' => [
        'title' => 'Увеличить отступ',
    ],
    'unindent' => [
        'title' => 'Уменьшить отступ',
    ],
    'left' => [
        'title' => 'Выравнивание слева',
    ],
    'center' => [
        'title' => 'Выравнивание по центру',
    ],
    'right' => [
        'title' => 'Выравнивание справа',
    ],
    'fore_color' => [
        'title' => 'Цвет текста',
    ],
    'bg_color' => [
        'title' => 'Цвет фона',
    ],
    'design_tab' => [
        'title' => 'Переключиться в режим макетирования (WYSIWYG)',
    ],
    'html_tab' => [
        'title' => 'Переключиться в режим редактирования кода (HTML)',
    ],
    'colorpicker' => [
        'title' => 'Выбор цвета',
        'ok' => 'ГОТОВО',
        'cancel' => 'Отменить',
    ],
    'cleanup' => [
        'title' => 'Чистка HTML',
        'confirm' => 'Эта операция уберет все стили, шрифты и ненужные тэги из текущего содержимого редактора. Часть или все ваше форматиолвание может быть утеряно.',
        'ok' => 'ГОТОВО',
        'cancel' => 'Отменить',
    ],
    'toggle_borders' => [
        'title' => 'Включить рамки',
    ],
    'hyperlink' => [
        'title' => 'Гиперссылка',
        'url' => 'Адрес',
        'name' => 'Имя',
        'target' => 'Цель',
        'title_attr' => 'Название',
        'ok' => 'ГОТОВО',
        'cancel' => 'Отменить',
    ],
    'table_row_prop' => [
        'title' => 'Параметры строки',
        'horizontal_align' => 'Горизонтальное выравнивание',
        'vertical_align' => 'Вертикальное выравнивание',
        'css_class' => 'Стиль',
        'no_wrap' => 'Без переноса',
        'bg_color' => 'Цвет фона',
        'ok' => 'ГОТОВО',
        'cancel' => 'Отменить',
        'left' => 'Слева',
        'center' => 'В центре',
        'right' => 'Справа',
        'top' => 'Сверху',
        'middle' => 'В центре',
        'bottom' => 'Снизу',
        'baseline' => 'Базовая линия текста',
    ],
    'symbols' => [
        'title' => 'Спец. символы',
        'ok' => 'ГОТОВО',
        'cancel' => 'Отменить',
    ],
    'templates' => [
        'title' => 'Шаблоны',
    ],
    'page_prop' => [
        'title' => 'Параметры страницы',
        'title_tag' => 'Заголовок',
        'charset' => 'Набор символов',
        'background' => 'Фоновое изображение',
        'bgcolor' => 'Цвет фона',
        'text' => 'Цвет текста',
        'link' => 'Цвет ссылок',
        'vlink' => 'Цвет посщенных ссылок',
        'alink' => 'Цвет активных ссылок',
        'leftmargin' => 'Отступ слева',
        'topmargin' => 'Отступ сверху',
        'css_class' => 'Стиль',
        'ok' => 'ГОТОВО',
        'cancel' => 'Отменить',
    ],
    'preview' => [
        'title' => 'Предварительный просмотр',
    ],
    'image_popup' => [
        'title' => 'Popup изображения',
    ],
    'zoom' => [
        'title' => 'Увеличение',
    ],
];


