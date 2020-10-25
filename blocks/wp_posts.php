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

function b_wordpress_posts_edit($options)
{
    $form = _MB_WORDPRESS_COUNT . ": <input type='text' name='options[0]' value='" . $options[0] . "'>";

    $form .= _MB_WORDPRESS_REDNEW . ": <input type='text' name='options[1]' value='" . $options[1] . "'>";

    $form .= _MB_WORDPRESS_GREENNEW . ": <input type='text' name='options[2]' value='" . $options[2] . "'>";

    $form .= '<br><br>' . _MB_WORDPRESS_CATLIST;

    $selected = array_slice($options, 3); // get allowed cats

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

function b_wordpress_posts_show($options)
{
    $block = [];

    /* For wp blocks */ global $wpdb, $wp_query, $wp_rewrite, $wp_roles;

    global $m, $monthnum, $year, $timedifference, $month, $month_abbrev, $weekday, $weekday_initial, $weekday_abbrev, $posts, $category_posts, $use_cache;

    require dirname(__DIR__) . '/wp-config.php';

    $myts = MyTextSanitizer::getInstance();

    $count = ($options[0]) ? (int)$options[0] : 10;

    $cats = array_slice($options, 3); // get allowed cats

    if ((empty($cats)) || in_array(0, $cats, true)) {
        $whichcat = '';

        $join = '';

        $cat_param = '';
    } else {
        $join = " LEFT JOIN {$wpdb->post2cat} ON ({$wpdb->posts}.ID = {$wpdb->post2cat}.post_id) ";

        $whichcat = " AND ({$wpdb->post2cat}.category_id IN (" . implode(',', $cats) . '))';
    }

    $request = "SELECT {$wpdb->posts}.ID, {$wpdb->posts}.post_title, {$wpdb->posts}.post_date, {$wpdb->posts}.post_author FROM {$wpdb->posts}" . $join . " WHERE post_status = 'publish' " . $whichcat;

    $request .= " ORDER BY post_date DESC LIMIT 0, $count";

    if (!$lposts = $wpdb->get_results($request)) {
        return $block;
    }

    $authors = [];

    $posts = [];

    $time_difference = get_settings('time_difference') * 3600;

    foreach ($lposts as $lpost) {
        $post = [];

        $post['title'] = encoding_wp2xoops(htmlspecialchars($lpost->post_title, ENT_QUOTES | ENT_HTML5));

        $post['new'] = '';

        if ($options[1] || $options[2]) {
            $m = $lpost->post_date;

            $elapse = time() + $time_difference - mktime(mb_substr($m, 11, 2), mb_substr($m, 14, 2), mb_substr($m, 17, 2), mb_substr($m, 5, 2), mb_substr($m, 8, 2), mb_substr($m, 0, 4));

            if ($elapse < $options[1] * 60 * 60 * 24) {
                $post['new'] = 'red';
            } elseif ($elapse < $options[2] * 60 * 60 * 24) {
                $post['new'] = 'green';
            }
        }

        $post['link'] = get_permalink($lpost->ID);

        $post['author'] = $lpost->post_author;

        $authors[$lpost->post_author] = 1;

        $posts[] = $post;
    }

    require_once XOOPS_ROOT_PATH . '/Frameworks/art/functions.ini.php';

    load_functions('user');

    $users = mod_getUnameFromIds(array_keys($authors), true, true);

    foreach (array_keys($posts) as $key) {
        $posts[$key]['author'] = $users[$posts[$key]['author']];
    }

    $block = &$posts;

    return $block;
}
