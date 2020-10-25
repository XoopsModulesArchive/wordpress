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
function b_wordpress_sidebar_show($options)
{
    /* For wp blocks */ global $wpdb, $wp_query, $wp_rewrite, $wp_roles;

    global $m, $monthnum, $year, $timedifference, $month, $month_abbrev, $weekday, $weekday_initial, $weekday_abbrev, $posts, $category_posts, $use_cache;

    require dirname(__DIR__) . '/wp-config.php';

    if (!empty($options[0])) {
        ob_start();

        wp_list_pages();

        $block['pages'] = ob_get_contents();

        ob_end_clean();
    }

    if (!empty($options[1])) {
        ob_start();

        wp_get_links('before=<li>&after=</li>');

        $block['links'] = ob_get_contents();

        ob_end_clean();
    }

    if (!empty($options[2])) {
        ob_start();

        list_cats(
            $optionall = 1,
            $all = 'All',
            $sort_column = 'ID',
            $sort_order = 'asc',
            $file = '',
            $list = true,
            $optiondates = 0,
            $optioncount = 1,
            $hide_empty = 0,
            $use_desc_for_title = 1,
            $children = true,
            $child_of = 0,
            $categories = 0,
            $recurse = 0,
            $feed = '',
            $feed_image = '',
            $exclude = '',
            $hierarchical = true
        );

        $block['cats'] = ob_get_contents();

        ob_end_clean();
    }

    if (!empty($options[3])) {
        $block['meta'] = '<li><a href="http://wordpress.org/" title="Powered by WordPress, state-of-the-art semantic personal publishing platform." target="_blank">WordPress</a></li>'
                         . '<li><a href="http://xoops.org/" title="Powered by XOOPS, state-of-the-art Content Management Portal." target="_blank">XOOPS</a></li>';

        ob_start();

        wp_meta();

        $meta = ob_get_contents();

        ob_end_clean();

        $block['meta'] .= $meta;
    }

    if (!empty($options[4])) {
        include ABSPATH . WPINC . '/locale.php';

        ob_start();

        // types: daily, weekly, monthly, postbypost

        get_archives($type = $options[4], $limit = 10, $format = 'html', $before = '', $after = '', $show_post_count = true);

        $block['archives'] = ob_get_contents();

        ob_end_clean();
    }

    foreach (array_keys($block) as $key) {
        $block[$key] = encoding_wp2xoops($block[$key]);
    }

    $block['op'] = '<li>'
                   . '<a href="'
                   . get_settings('siteurl')
                   . '/wp-admin/post.php" title="'
                   . constant('_MB_WORDPRESS_SUBMIT')
                   . '">'
                   . constant('_MB_WORDPRESS_SUBMIT')
                   . '</a>'
                   . '</li>'
                   . '<li>'
                   . '<a href="'
                   . get_settings('siteurl')
                   . '/wp-admin/edit.php" title="'
                   . constant(
                       '_MB_WORDPRESS_ADMIN'
                   )
                   . '">'
                   . constant('_MB_WORDPRESS_ADMIN')
                   . '</a>'
                   . '</li>'
                   . '<li>'
                   . '<a href="'
                   . get_settings('siteurl')
                   . '/readme.html" title="Readme !">ReadMe !</a>'
                   . '</li>'
                   . '<li>'
                   . '<a href="'
                   . get_settings('siteurl')
                   . '/?style=w" title="'
                   . constant('_MB_WORDPRESS_WPMODE')
                   . '">'
                   . constant('_MB_WORDPRESS_WPMODE')
                   . '</a>'
                   . '</li>';

    $block['siteurl'] = get_settings('siteurl');

    return $block;
}

function b_wordpress_sidebar_edit($options)
{
    $form = _MB_WORDPRESS_PAGES . '<input type="radio" name="options[0]" value="1"';

    if (1 == $options[0]) {
        $form .= ' checked="checked"';
    }

    $form .= '>' . _YES . '; <input type="radio" name="options[0]" value="0"';

    if (0 == $options[0]) {
        $form .= ' checked="checked"';
    }

    $form .= '>' . _NO;

    $form .= '<br>' . _MB_WORDPRESS_LINKS . '<input type="radio" name="options[1]" value="1"';

    if (1 == $options[1]) {
        $form .= ' checked="checked"';
    }

    $form .= '>' . _YES . '; <input type="radio" name="options[1]" value="0"';

    if (0 == $options[1]) {
        $form .= ' checked="checked"';
    }

    $form .= '>' . _NO;

    $form .= '<br>' . _MB_WORDPRESS_CATS . '<input type="radio" name="options[2]" value="1"';

    if (1 == $options[2]) {
        $form .= ' checked="checked"';
    }

    $form .= '>' . _YES . '; <input type="radio" name="options[2]" value="0"';

    if (0 == $options[2]) {
        $form .= ' checked="checked"';
    }

    $form .= '>' . _NO;

    $form .= '<br>' . _MB_WORDPRESS_META . '<input type="radio" name="options[3]" value="1"';

    if (1 == $options[3]) {
        $form .= ' checked="checked"';
    }

    $form .= '>' . _YES . '; <input type="radio" name="options[3]" value="0"';

    if (0 == $options[3]) {
        $form .= ' checked="checked"';
    }

    $form .= '>' . _NO;

    $form .= '<br>' . _MB_WORDPRESS_ARCHIVES . '<input type="radio" name="options[4]" value=""';

    if (empty($options[4])) {
        $form .= ' checked="checked"';
    }

    $form .= '>' . _NO . '; <input type="radio" name="options[4]" value="daily"';

    if ('daily' == $options[4]) {
        $form .= ' checked="checked"';
    }

    $form .= '>' . _MB_WORDPRESS_ARCHIVES_DAILY . '; <input type="radio" name="options[4]" value="weekly"';

    if ('weekly' == $options[4]) {
        $form .= ' checked="checked"';
    }

    $form .= '>' . _MB_WORDPRESS_ARCHIVES_WEEKLY . '; <input type="radio" name="options[4]" value="monthly"';

    if ('monthly' == $options[4]) {
        $form .= ' checked="checked"';
    }

    $form .= '>' . _MB_WORDPRESS_ARCHIVES_MONTHLY . '; <input type="radio" name="options[4]" value="postbypost"';

    if ('postbypost' == $options[4]) {
        $form .= ' checked="checked"';
    }

    $form .= '>' . _MB_WORDPRESS_ARCHIVES_POSTLY;

    return $form;
}
