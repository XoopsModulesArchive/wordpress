<?php
// ================================================
// SPAW PHP WYSIWYG editor control
// ================================================
// French language file
// ================================================
// Developed: Alan Mendelevich, alan@solmetra.lt
// Copyright: Solmetra (c)2003 All rights reserved.
// Translation to French: Laurent Fasnacht(lf@o-t.ch)
// ------------------------------------------------
//                                www.solmetra.com
// ================================================
// v.1.0, 2003-04-16
// ================================================

// charset to be used in dialogs
$spaw_lang_charset = 'iso-8859-1';

// language text data array
// first dimension - block, second - exact phrase
// alternative text for toolbar buttons and title for dropdowns - 'title'

$spaw_lang_data = [
    'cut' => [
        'title' => 'Couper',
    ],
    'copy' => [
        'title' => 'Copier',
    ],
    'paste' => [
        'title' => 'Coller',
    ],
    'undo' => [
        'title' => 'Annuler',
    ],
    'redo' => [
        'title' => 'Refaire',
    ],
    'hyperlink' => [
        'title' => 'Lien hypertexte',
    ],
    'image_insert' => [
        'title' => 'Ins&eacute;rer une image',
        'select' => 'S&eacute;lectionner',
        'cancel' => 'Annuler',
        'library' => 'Biblioth&egrave;que',
        'preview' => 'Pr&eacute;visualiser',
        'images' => 'Images',
        'upload' => 'Uploader l\'image',
        'upload_button' => 'Uploader',
        'error' => 'Erreur',
        'error_no_image' => 'Veuillez s&eacute;lectionner une image',
        'error_uploading' => 'Impossible d\'uploader. Veuillez r&eacute;essayer plus tard.',
        'error_wrong_type' => 'Mauvais type d\'image',
        'error_no_dir' => 'La biblioth&egrave;que n\'existe pas physiquement',
    ],
    'image_prop' => [
        'title' => 'Propri&eacute;t&eacute;s de l\'image',
        'ok' => '   OK   ',
        'cancel' => 'Annuler',
        'source' => 'Source',
        'alt' => 'Texte alternatif',
        'align' => 'Alignement',
        'left' => 'gauche',
        'right' => 'droite',
        'top' => 'haut',
        'middle' => 'milieu',
        'bottom' => 'bas',
        'absmiddle' => 'Alignement milieu image = milieu texte',
        'texttop' => 'Haut de ligne',
        'baseline' => 'Bas de ligne',
        'width' => 'Width',
        'height' => 'Hauteur',
        'border' => 'Bordure',
        'hspace' => 'Espacement horizontal',
        'vspace' => 'Espacement vertical',
        'error' => 'Erreur',
        'error_width_nan' => 'Largeur non num&eacute;rique',
        'error_height_nan' => 'Hauteur non num&eacute;rique',
        'error_border_nan' => 'Bordure non num&eacute;rique',
        'error_hspace_nan' => 'Espacement horizontal non num&eacute;rique',
        'error_vspace_nan' => 'Espacement vertical non num&eacute;rique',
    ],
    'hr' => [
        'title' => 'Trait de s&eacute;paration horizonal',
    ],
    'table_create' => [
        'title' => 'Cr&eacute;er un tableau',
    ],
    'table_prop' => [
        'title' => 'Propri&eacute;t&eacute;s du tableau',
        'ok' => '   OK   ',
        'cancel' => 'Annuler',
        'rows' => 'Lignes',
        'columns' => 'Colonnes',
        'width' => 'Largeur',
        'height' => 'Hauteur',
        'border' => 'Bordure',
        'pixels' => 'pixels',
        'cellpadding' => 'Marge de cellule',
        'cellspacing' => 'Espace entre cellules',
        'bg_color' => 'Couleur de fond',
        'error' => 'Erreur',
        'error_rows_nan' => 'Lignes non num&eacute;rique',
        'error_columns_nan' => 'Colonnes non num&eacute;rique',
        'error_width_nan' => 'Largeur non num&eacute;rique',
        'error_height_nan' => 'Hauteur non num&eacute;rique',
        'error_border_nan' => 'Bordure non num&eacute;rique',
        'error_cellpadding_nan' => 'Marge non num&eacute;rique',
        'error_cellspacing_nan' => 'Espace non num&eacute;rique',
    ],
    'table_cell_prop' => [
        'title' => 'Propri&eacute;t&eacute; de la cellule',
        'horizontal_align' => 'Alignement horizontal',
        'vertical_align' => 'Alignement vertical',
        'width' => 'Largeur',
        'height' => 'Hauteur',
        'css_class' => 'Classe CSS',
        'no_wrap' => 'Pas de saut de ligne automatique',
        'bg_color' => 'Couleur de fond',
        'ok' => '   OK   ',
        'cancel' => 'Annuler',
        'left' => 'Gauche',
        'center' => 'Centre',
        'right' => 'Droite',
        'top' => 'Haut',
        'middle' => 'Milieu',
        'bottom' => 'Bas',
        'baseline' => 'Bas de ligne',
        'error' => 'Erreur',
        'error_width_nan' => 'Largeur non num&eacute;rique',
        'error_height_nan' => 'Hauteur non num&eacute;rique',
    ],
    'table_row_insert' => [
        'title' => 'Ins&eacute;rer ligne',
    ],
    'table_column_insert' => [
        'title' => 'Ins&eacute;rer colonne',
    ],
    'table_row_delete' => [
        'title' => 'Supprimer ligne',
    ],
    'table_column_delete' => [
        'title' => 'Supprimer colonne',
    ],
    'table_cell_merge_right' => [
        'title' => 'Fusionner avec cellule de droite',
    ],
    'table_cell_merge_down' => [
        'title' => 'Fusionner avec cellule du bas',
    ],
    'table_cell_split_horizontal' => [
        'title' => 'Partager la cellule horizontalement',
    ],
    'table_cell_split_vertical' => [
        'title' => 'Partager la cellule verticalement',
    ],
    'style' => [
        'title' => 'Style',
    ],
    'font' => [
        'title' => 'Police',
    ],
    'fontsize' => [
        'title' => 'Taille',
    ],
    'paragraph' => [
        'title' => 'Paragraphe',
    ],
    'bold' => [
        'title' => 'Gras',
    ],
    'italic' => [
        'title' => 'Italique',
    ],
    'underline' => [
        'title' => 'Soulign&eacute;',
    ],
    'ordered_list' => [
        'title' => 'Num&eacute;ros',
    ],
    'bulleted_list' => [
        'title' => 'Puces',
    ],
    'indent' => [
        'title' => 'Augmenter la marge &agrave; droite',
    ],
    'unindent' => [
        'title' => 'Diminuer la marge &agrave; droite',
    ],
    'left' => [
        'title' => 'Gauche',
    ],
    'center' => [
        'title' => 'Centr&eacute;',
    ],
    'right' => [
        'title' => 'Droite',
    ],
    'fore_color' => [
        'title' => 'Couleur',
    ],
    'bg_color' => [
        'title' => 'Couleur de fond',
    ],
    'design_tab' => [
        'title' => 'Passer en mode WYSIWYG (dessin)',
    ],
    'html_tab' => [
        'title' => 'Passer en mode HTML (code)',
    ],
    'colorpicker' => [
        'title' => 'Choix de couleur',
        'ok' => '   OK   ',
        'cancel' => 'Annuler',
    ],
    // <<<<<<<<< NEW >>>>>>>>>
    'cleanup' => [
        'title' => 'Nettoyer le code HTML (enlever les styles)',
        'confirm' => 'Valider cette action supprimera tous les styles, polices, et tags html inutiles du contenu actuel. Tout ou une partie de votre formattage pourra &ecirc;tre perdu.',
        'ok' => '   OK   ',
        'cancel' => 'Annuler',
    ],
    'toggle_borders' => [
        'title' => 'Activer/d&eacute;sactiver bordures',
    ],
    'hyperlink' => [
        'title' => 'Hyperlien',
        'url' => 'URL',
        'name' => 'Nom',
        'target' => 'Cible',
        'title_attr' => 'Titre',
        'ok' => '   OK   ',
        'cancel' => 'Annuler',
    ],
    'table_row_prop' => [
        'title' => 'Propri&eacute;t&eacute; de ligne',
        'horizontal_align' => 'Alignement horizontal',
        'vertical_align' => 'Alignement vertical',
        'css_class' => 'Classe CSS',
        'no_wrap' => 'Pas de sauts &agrave; la ligne',
        'bg_color' => 'Couleur de fond',
        'ok' => '   OK   ',
        'cancel' => 'Annuler',
        'left' => 'Gauche',
        'center' => 'Centre',
        'right' => 'Droite',
        'top' => 'Haut',
        'middle' => 'Millieu',
        'bottom' => 'Bas',
        'baseline' => 'Bas de ligne',
    ],
    'symbols' => [
        'title' => 'Caract&egrave;res sp&eacute;ciaux',
        'ok' => '   OK   ',
        'cancel' => 'Annuler',
    ],
    'templates' => [
        'title' => 'Mod&egrave;les',
    ],
    'page_prop' => [
        'title' => 'Propri&eacute;t&eacute;s de la page',
        'title_tag' => 'Titre',
        'charset' => 'Jeu de charact&egrave;re',
        'background' => 'Image de fond',
        'bgcolor' => 'Couleur de fond',
        'text' => 'Couleur de texte',
        'link' => 'Couleur de lien',
        'vlink' => 'Couleur de lien visit&eacute;',
        'alink' => 'Couleur de lien actif',
        'leftmargin' => 'Marge gauche',
        'topmargin' => 'Marge haut',
        'css_class' => 'Classe CSS',
        'ok' => '   OK   ',
        'cancel' => 'Annuler',
    ],
    'preview' => [
        'title' => 'Pr&eacute;visualiser',
    ],
    'image_popup' => [
        'title' => 'Popup image',
    ],
    'zoom' => [
        'title' => 'Zoom',
    ],
];


