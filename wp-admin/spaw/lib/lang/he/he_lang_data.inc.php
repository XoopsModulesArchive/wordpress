<?php
// ================================================
// SPAW PHP WYSIWYG editor control
// ================================================
// Hebrew language file
// ================================================
// Developed: Alan Mendelevich, alan@solmetra.lt
// Translation to Hebrew: Yaron Gonen (lord_gino@yahoo.com)
// Copyright: Solmetra (c)2003 All rights reserved.
// ------------------------------------------------
//                                www.solmetra.com
// ================================================
// v.1.0, 2003-03-20
// ================================================

// charset to be used in dialogs
$spaw_lang_charset = 'windows-1255';

// text direction for the language
$spaw_lang_direction = 'rtl';

// language text data array
// first dimension - block, second - exact phrase
// alternative text for toolbar buttons and title for dropdowns - 'title'

$spaw_lang_data = [
    'cut' => [
        'title' => 'גזור',
    ],
    'copy' => [
        'title' => 'העתק',
    ],
    'paste' => [
        'title' => 'הדבק',
    ],
    'undo' => [
        'title' => 'בטל',
    ],
    'redo' => [
        'title' => 'בצע שוב',
    ],
    'hyperlink' => [
        'title' => 'היפר קישור',
    ],
    'image_insert' => [
        'title' => 'הכנס תמונה',
        'select' => '  בחר  ',
        'cancel' => '  בטל  ',
        'library' => 'ספריה',
        'preview' => 'תצוגה מקדימה',
        'images' => 'תמונות',
        'upload' => 'העלה תמונה',
        'upload_button' => 'העלה',
        'error' => 'שגיאה',
        'error_no_image' => 'בחר תמונה',
        'error_uploading' => 'ארעה שגיאה בעת העלאת התמונה. אנא נסה שוב מאוחר יותר.',
        'error_wrong_type' => 'סוג קובץ תמונה שגוי',
        'error_no_dir' => 'הספריה אינה קיימת',
    ],
    'image_prop' => [
        'title' => 'אפשרויות תמונה',
        'ok' => '  אוקי  ',
        'cancel' => '  בטל  ',
        'source' => 'מקור',
        'alt' => 'טקסט אלטרנטיבי',
        'align' => 'הצמדה',
        'left' => 'שמאל',
        'right' => 'ימין',
        'top' => 'למעלה',
        'middle' => 'אמצע',
        'bottom' => 'למטה',
        'absmiddle' => 'מרכז',
        'texttop' => 'texttop',
        'baseline' => 'baseline',
        'width' => 'רוחב',
        'height' => 'גובה',
        'border' => 'קו גבול',
        'hspace' => 'מרווח אפקי',
        'vspace' => 'מרווח אנכי',
        'error' => 'שגיאה',
        'error_width_nan' => 'הרוחב אינו מספר',
        'error_height_nan' => 'הגובה אינו מספר',
        'error_border_nan' => 'הגבול אינו מספר',
        'error_hspace_nan' => 'מרווח אפקי אינו מספר',
        'error_vspace_nan' => 'מרווח אנכי אינו מספר',
    ],
    'hr' => [
        'title' => 'קו אפקי',
    ],
    'table_create' => [
        'title' => 'צור טבלה',
    ],
    'table_prop' => [
        'title' => 'אפשרויות טבלה',
        'ok' => '  אוקי  ',
        'cancel' => '  בטל  ',
        'rows' => 'שורות',
        'columns' => 'עמודות',
        'width' => 'רוחב',
        'height' => 'גובה',
        'border' => 'גבול',
        'pixels' => 'פיקסלים',
        'cellpadding' => 'דיפון תא',
        'cellspacing' => 'ריווח תא',
        'bg_color' => 'צבע רקע',
        'error' => 'שגיאה',
        'error_rows_nan' => 'השורות אינן מספר',
        'error_columns_nan' => 'העמודות אינן מספר',
        'error_width_nan' => 'הרוחב אינן מספר',
        'error_height_nan' => 'הגובה אינו מספר',
        'error_border_nan' => 'הגבול אינו מספר',
        'error_cellpadding_nan' => 'דיפון התא אינו מספר',
        'error_cellspacing_nan' => 'ריווח התא אינו מספר',
    ],
    'table_cell_prop' => [
        'title' => 'אפשרויות תא',
        'horizontal_align' => 'הצמדה אפקית',
        'vertical_align' => 'הצמדה אנכית',
        'width' => 'רוחב',
        'height' => 'גובה',
        'css_class' => 'CSS class',
        'no_wrap' => 'ללא שבירת שורות',
        'bg_color' => 'צבע רקע',
        'ok' => '  אוקי  ',
        'cancel' => '  בטל  ',
        'left' => 'שמאל',
        'center' => 'מרכז',
        'right' => 'ימין',
        'top' => 'למעלה',
        'middle' => 'אמצע',
        'bottom' => 'למטה',
        'baseline' => 'קו התחלה',
        'error' => 'שגיאה',
        'error_width_nan' => 'הרוחב אינו מספר',
        'error_height_nan' => 'גובה אינו מספר',
    ],
    'table_row_insert' => [
        'title' => 'הכנס רשומה',
    ],
    'table_column_insert' => [
        'title' => 'הכנס עמודה',
    ],
    'table_row_delete' => [
        'title' => 'מחק רשומה',
    ],
    'table_column_delete' => [
        'title' => 'מחק עמודה',
    ],
    'table_cell_merge_right' => [
        'title' => 'מזג תאים ימינה',
    ],
    'table_cell_merge_down' => [
        'title' => 'מזג תאים למטה',
    ],
    'table_cell_split_horizontal' => [
        'title' => 'פצל תא אפקית',
    ],
    'table_cell_split_vertical' => [
        'title' => 'פצל תא אנכית',
    ],
    'style' => [
        'title' => 'סגנון',
    ],
    'font' => [
        'title' => 'גופן',
    ],
    'fontsize' => [
        'title' => 'גודל',
    ],
    'paragraph' => [
        'title' => 'פיסקה',
    ],
    'bold' => [
        'title' => 'מודגש',
    ],
    'italic' => [
        'title' => 'נטוי',
    ],
    'underline' => [
        'title' => 'קו תחתי',
    ],
    'ordered_list' => [
        'title' => 'רשימה ממוספרת',
    ],
    'bulleted_list' => [
        'title' => 'רשימה',
    ],
    'indent' => [
        'title' => 'הכנס פנימה',
    ],
    'unindent' => [
        'title' => 'הוצא',
    ],
    'left' => [
        'title' => 'שמאל',
    ],
    'center' => [
        'title' => 'מרכז',
    ],
    'right' => [
        'title' => 'ימין',
    ],
    'fore_color' => [
        'title' => 'צבע קדמי',
    ],
    'bg_color' => [
        'title' => 'צבע רקע',
    ],
    'design_tab' => [
        'title' => 'עיצוב המסמך',
    ],
    'html_tab' => [
        'title' => 'ערוך קוד Html',
    ],
    'colorpicker' => [
        'title' => 'בחר צבע',
        'ok' => '  אוקי  ',
        'cancel' => '  בטל  ',
    ],
    // <<<<<<<<< NEW >>>>>>>>>
    'cleanup' => [
        'title' => 'ניקוי Html (הסר סגנונות)',
        'confirm' => 'ביצוע פעולה זו יסיר את כל הסגנונות, גופנים וכל התאגים הלא שימושיים ממסמך זה. חלק או כל העיצובים יאבדו.',
        'ok' => '  אוקי  ',
        'cancel' => '  בטל  ',
    ],
    'toggle_borders' => [
        'title' => 'חיזוק גבולות',
    ],
    'hyperlink' => [
        'title' => 'היפר קישור',
        'url' => 'URL',
        'name' => 'שם',
        'target' => 'מטרה',
        'title_attr' => 'כותרת',
        'ok' => '  אוקי  ',
        'cancel' => '  בטל  ',
    ],
    'table_row_prop' => [
        'title' => 'תכונות שורה',
        'horizontal_align' => 'הצמדה אופקית',
        'vertical_align' => 'הצמדה אנכית',
        'css_class' => 'CSS class',
        'no_wrap' => 'ללא שבירת שורות',
        'bg_color' => 'צבע רקע',
        'ok' => '  אוקי  ',
        'cancel' => '  בטל  ',
        'left' => 'שמאל',
        'center' => 'מרכז',
        'right' => 'ימין',
        'top' => 'למעלה',
        'middle' => 'אמצע',
        'bottom' => 'למטה',
        'baseline' => 'קו התחלה',
    ],
    'symbols' => [
        'title' => 'תווים מיוחדים',
        'ok' => '  אוקי  ',
        'cancel' => '  בטל  ',
    ],
    'templates' => [
        'title' => 'תבניות',
    ],
    'page_prop' => [
        'title' => 'תכונות דף',
        'title_tag' => 'כותרת',
        'charset' => 'Charset',
        'background' => 'תמונת רקע',
        'bgcolor' => 'צבע רקע',
        'text' => 'צבע טקסט',
        'link' => 'צבע קישור',
        'vlink' => 'צבע קישור שהיו בו כבר',
        'alink' => 'צבע קישור פעיל',
        'leftmargin' => 'שוליים שמאליים',
        'topmargin' => 'שוליים עליונים',
        'css_class' => 'CSS class',
        'ok' => '  אוקי  ',
        'cancel' => '  בטל  ',
    ],
    'preview' => [
        'title' => 'תצוגה מקדימה',
    ],
    'image_popup' => [
        'title' => 'תמונה קופצת',
    ],
    'zoom' => [
        'title' => 'זום',
    ],
];


