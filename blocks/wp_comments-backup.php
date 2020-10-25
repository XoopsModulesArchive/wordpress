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
        $myts = MyTextSanitizer::getInstance();

        $selected = is_array($selected) ? $selected : [$selected];

        $sort_column = 'cat_' . $sort_column;

        $table_categories = $GLOBALS['xoopsDB']->prefix('wp') . '_categories';

        $query = "
        SELECT cat_ID, cat_name, category_nicename,category_parent
        FROM $table_categories
        WHERE cat_ID > 0
        ";

        $query .= " ORDER BY category_parent ASC, $sort_column $sort_order";

        $ret = '';

        if (!$result = $GLOBALS['xoopsDB']->query($query)) {
            return $ret;
        }

        while (false !== ($myrow = $GLOBALS['xoopsDB']->fetchArray($result))) {
            $ret .= "\t<option value=\"" . $myrow['cat_ID'] . '"';

            if (in_array($myrow['cat_ID'], $selected, true)) {
                $ret .= ' selected="selected"';
            }

            $ret .= '>';

            $ret .= htmlspecialchars(encoding_wp2xoops($myrow['cat_name']), ENT_QUOTES | ENT_HTML5);

            $ret .= "</option>\n";
        }

        return $ret;
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

    $form .= dropdown_cats_options('ID', 'asc', $selected);

    $form .= '</select><br>';

    return $form;
}

function b_wordpress_comments_show($options)
{
    /* For wp blocks */

    /*
    global $wpdb, $wp_query, $wp_rewrite, $wp_roles;
    global $m, $monthnum, $year, $timedifference, $month, $month_abbrev, $weekday, $weekday_initial, $weekday_abbrev, $posts, $category_posts, $use_cache;
    */

    $myts = MyTextSanitizer::getInstance();

    $count = ($options[0]) ? (int)$options[0] : 10;

    $length = (int)$options[1];

    $cats = array_slice($options, 2); // get allowed cats

    //require __DIR__.'/../wp-config.php';

    $table_post2cat = $GLOBALS['xoopsDB']->prefix('wp') . '_post2cat';

    $table_posts = $GLOBALS['xoopsDB']->prefix('wp') . '_posts';

    $table_comments = $GLOBALS['xoopsDB']->prefix('wp') . '_comments';

    if ((empty($cats)) || in_array(0, $cats, true)) {
        $whichcat = '';

        $join = '';
    } else {
        $join = " LEFT JOIN {$table_post2cat} ON ({$table_posts}.ID = {$table_post2cat}.post_id) ";

        $whichcat = ' AND ({$table_post2cat}.category_id IN (' . implode(',', $cats) . '))';
    }

    $request = "SELECT {$table_posts}.ID, {$wpdb->comments}.comment_ID, {$table_comments}.comment_content, {$table_comments}.comment_author,{$table_comments}.comment_date,{$table_comments}.comment_type FROM {$table_posts}, {$table_comments} "
               . $join
               . " WHERE {$table_posts}.ID={$table_comments}.comment_post_ID AND {$table_posts}.post_status = 'publish' "
               . $whichcat;

    $request .= "AND {$table_comments}.comment_approved = '1' ORDER BY {$table_comments}.comment_date DESC LIMIT $count";

    $ret = '';

    if (!$result = $GLOBALS['xoopsDB']->query($request)) {
        return $ret;
    }

    $ret .= '<ul>';

    while (false !== ($myrow = $GLOBALS['xoopsDB']->fetchArray($result))) {
        if ('trackback' == $myrow['comment_content']) {
            $type = '[Track]';
        }

        if ('pingback' == $myrow['comment_content']) {
            $type = '[Ping]';
        } else {
            $type = '[Comm]';
        }

        $comment_author = htmlspecialchars(encoding_wp2xoops($myrow['comment_author']), ENT_QUOTES | ENT_HTML5);

        $comment_content = encoding_wp2xoops(strip_tags($myrow['comment_content']));

        if (!empty($comment_lenth)) {
            $comment_content = xoops_substr($comment_content, 0, $comment_lenth);
        }

        $comment_excerpt = $comment_content;

        if ($length > 0) {
            $comment_excerpt = xoops_substr($comment_excerpt, 0, $length);
        }

        $permalink = XOOPS_URL . '/modules/wordpress/?p=' . $myrow['ID'] . '#comment-' . $myrow['comment_ID'];

        $ret .= '<li>' . $comment_author . ': <a href="' . $permalink;

        $ret .= '" title="' . $comment_author . '">' . $comment_excerpt . '</a>';

        $ret .= ' <small>- ' . $type . '</small>';

        $ret .= "</li>\n";
    }

    $ret .= '</ul>';

    $block['content'] = $ret;

    return $block;
}
