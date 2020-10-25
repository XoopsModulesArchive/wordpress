<?php
// $Id: xoops_version.php,v 1.8 2005/06/03 01:35:02 phppp Exp $
//  ------------------------------------------------------------------------ //
//                XOOPS - PHP Content Management System                      //
//                    Copyright (c) 2000 XOOPS.org                           //
//                       <https://www.xoops.org>                             //
//  ------------------------------------------------------------------------ //
//  This program is free software; you can redistribute it and/or modify     //
//  it under the terms of the GNU General Public License as published by     //
//  the Free Software Foundation; either version 2 of the License, or        //
//  (at your option) any later version.                                      //
//                                                                           //
//  You may not change or alter any portion of this comment or credits       //
//  of supporting developers from this source code or any supporting         //
//  source code which is considered copyrighted (c) material of the          //
//  original comment or credit authors.                                      //
//                                                                           //
//  This program is distributed in the hope that it will be useful,          //
//  but WITHOUT ANY WARRANTY; without even the implied warranty of           //
//  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the            //
//  GNU General Public License for more details.                             //
//                                                                           //
//  You should have received a copy of the GNU General Public License        //
//  along with this program; if not, write to the Free Software              //
//  Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307 USA //
//  ------------------------------------------------------------------------ //
// Author: phppp (D.J.)                                                      //
// URL: http://xoopsforge.com, http://xoops.org.cn                           //
// ------------------------------------------------------------------------- //

if (!function_exists('dropdown_cats_options')) {
    function dropdown_cats_options($sort_column = 'ID', $sort_order = 'asc', $selected = [])
    {
        global $wpdb, $wp_query, $wp_rewrite, $wp_roles;

        require dirname(__DIR__) . '/wp-config.php';

        $myts = MyTextSanitizer::getInstance();

        $selected = is_array($selected) ? $selected : [$selected];

        $sort_column = 'cat_' . $sort_column;

        $query = "
        SELECT cat_ID, cat_name, category_nicename,category_parent
        FROM $wpdb->categories
        WHERE cat_ID > 0
        ";

        $query .= " ORDER BY $sort_column $sort_order";

        $categories = $wpdb->get_results($query);

        if ($categories) {
            foreach ($categories as $category) {
                $cat_name = encoding_wp2xoops(apply_filters('list_cats', $category->cat_name, $category));

                echo "\t<option value=\"" . $category->cat_ID . '"';

                if (in_array($category->cat_ID, $selected, true)) {
                    echo ' selected="selected"';
                }

                echo '>';

                echo htmlspecialchars($cat_name, ENT_QUOTES | ENT_HTML5);

                echo "</option>\n";
            }
        }
    }
}

function b_wordpress_comments_edit($options)
{
    $form = _MB_WORDPRESS_COUNT . ": <input type='text' name='options[0]' value='" . $options[0] . "'>";

    $form .= _MB_WORDPRESS_LENGTH . ": <input type='text' name='options[1]' value='" . $options[1] . "'>";

    $form .= '<br><br>' . _MB_WORDPRESS_CATLIST;

    $selected = array_slice($options, 2); // get allowed cats

    $isAll = (0 == count($selected) || empty($selected[0])) ? true : false;

    $form .= '<br>&nbsp;&nbsp;<select name="options[]" multiple="multiple">';

    $form .= '<option value="0" ';

    if ($isAll) {
        $form .= ' selected="selected"';
    }

    $form .= '>' . _ALL . '</option>';

    ob_start();

    dropdown_cats_options('ID', 'asc', $selected);

    $list_str = ob_get_contents();

    ob_end_clean();

    $form .= $list_str . '</select><br>';

    return $form;
}

function b_wordpress_comments_show($options)
{
    /* For wp blocks */ global $wpdb, $wp_query, $wp_rewrite, $wp_roles;

    global $m, $monthnum, $year, $timedifference, $month, $month_abbrev, $weekday, $weekday_initial, $weekday_abbrev, $posts, $category_posts, $use_cache;

    $myts = MyTextSanitizer::getInstance();

    $count = ($options[0]) ? (int)$options[0] : 10;

    $length = (int)$options[1];

    $cats = array_slice($options, 2); // get allowed cats

    require dirname(__DIR__) . '/wp-config.php';

    if ((empty($cats)) || in_array(0, $cats, true)) {
        $whichcat = '';

        $join = '';
    } else {
        $join = " LEFT JOIN {$wpdb->post2cat} ON ({$wpdb->posts}.ID = {$wpdb->post2cat}.post_id) ";

        $whichcat = ' AND ({$wpdb->post2cat}.category_id IN (' . implode(',', $cats) . '))';
    }

    $request = "SELECT {$wpdb->posts}.ID, {$wpdb->comments}.comment_ID, {$wpdb->comments}.comment_content, {$wpdb->comments}.comment_author,{$wpdb->comments}.comment_date,{$wpdb->comments}.comment_type FROM {$wpdb->posts}, {$wpdb->comments} "
               . $join
               . " WHERE {$wpdb->posts}.ID={$wpdb->comments}.comment_post_ID AND {$wpdb->posts}.post_status = 'publish' "
               . $whichcat;

    $request .= "AND {$wpdb->comments}.comment_approved = '1' ORDER BY {$wpdb->comments}.comment_date DESC LIMIT $count";

    $lcomments = $wpdb->get_results($request);

    $output = '<ul>';

    foreach ($lcomments as $lcomment) {
        if ('trackback' == $lcomment->comment_content) {
            $type = '[Track]';
        }

        if ('pingback' == $lcomment->comment_content) {
            $type = '[Ping]';
        } else {
            $type = '[Comm]';
        }

        $comment_author = htmlspecialchars($lcomment->comment_author, ENT_QUOTES | ENT_HTML5);

        $comment_content = strip_tags($lcomment->comment_content);

        if (!empty($comment_lenth)) {
            $comment_content = xoops_substr($comment_content, 0, $comment_lenth);
        }

        $comment_excerpt = $comment_content;

        if ($length > 0) {
            $comment_excerpt = xoops_substr($comment_excerpt, 0, $length);
        }

        $permalink = get_permalink($lcomment->ID) . '#comment-' . $lcomment->comment_ID;

        $output .= '<li>' . $comment_author . ': <a href="' . $permalink;

        $output .= '" title="' . $comment_author . '">' . $comment_excerpt . '</a>';

        $output .= ' <small>- ' . $type . '</small>';

        $output .= "</li>\n";
    }

    $output .= '</ul>';

    $block['content'] = encoding_wp2xoops($output);

    return $block;
}
