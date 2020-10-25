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

function b_wordpress_content_edit($options)
{
    $form = _MB_WORDPRESS_COUNT . ": <input type='text' name='options[0]' value='" . $options[0] . "'>";

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

function b_wordpress_content_show($options)
{
    /* For wp blocks */ global $wpdb, $wp_query, $wp_rewrite, $wp_roles;

    global $m, $monthnum, $year, $timedifference, $month, $month_abbrev, $weekday, $weekday_initial, $weekday_abbrev, $posts, $category_posts, $use_cache;

    require dirname(__DIR__) . '/wp-config.php';

    $myts = MyTextSanitizer::getInstance();

    $count = ($options[0]) ? (int)$options[0] : 10;

    $cats = array_slice($options, 1); // get allowed cats

    $select = "{$wpdb->posts}.*";

    if ((empty($cats)) || in_array(0, $cats, true)) {
        $whichcat = '';

        $join = '';

        $cat_param = '';
    } else {
        $join = " LEFT JOIN {$wpdb->post2cat} ON ({$wpdb->posts}.ID = {$wpdb->post2cat}.post_id) ";

        $whichcat = ' AND ({$wpdb->post2cat}.category_id IN (' . implode(',', $cats) . '))';
    }

    $request = "SELECT $select FROM {$wpdb->posts}" . $join . " WHERE post_status = 'publish' " . $whichcat;

    $request .= " ORDER BY post_date DESC LIMIT 0, $count";

    /* get posts*/

    $wp_query->posts = $wpdb->get_results($request);

    update_post_caches($wp_query->posts);

    $wp_query->posts = apply_filters('the_posts', $wp_query->posts);

    $wp_query->post_count = count($wp_query->posts);

    if ($wp_query->post_count > 0) {
        $wp_query->post = $wp_query->posts[0];
    }

    /* done */

    $content = '';

    if (have_posts()) :
        while (have_posts()) :
            the_post();

    //$post["title"] = encoding_wp2xoops(get_the_title());

    //$post["title"] = empty($post["title"])?_MB_WORDPRESS_NOTITLE:$post["title"];

    //$post["link"] = get_permalink();

    ob_start();

    the_content(__('(more...)'));

    $the_content = ob_get_contents();

    ob_end_clean();

    //$post["content"] = encoding_wp2xoops($the_content);

    ob_start();

    the_author_posts_link();

    $author_link = ob_get_contents();

    ob_end_clean();

    ob_start();

    comments_popup_link(__('Comments (0)'), __('Comments (1)'), __('Comments (%)'));

    $comments_popup_link = ob_get_contents();

    ob_end_clean();

    //$post["author"] = encoding_wp2xoops($author_link);

    //$post["time"] = encoding_wp2xoops(get_the_time(get_settings('date_format')));

    //$post["views"] = get_the_views();

    //$post["category"] = encoding_wp2xoops(get_the_category_list(', '));

    //$block["posts"][] = $post;

    $content .= '
			<div class="post">
				<h2><a href="' . get_permalink() . '" rel="bookmark" title="Permanent Link to ' . get_the_title() . '">' . get_the_title() . '</a></h2>
				<small>' . get_the_time(get_settings('date_format')) . ' &#8212; ' . $author_link . ' (' . get_the_views() . ')</small>
				<div class="entry">
					' . $the_content . '
				</div>
				<p class="postmetadata">' . __('Filed under:') . ' ' . get_the_category_list(', ') . ' <strong>|</strong> ' . '  ' . $comments_popup_link . '</p>
			</div>
			';

    endwhile;

    $block['content'] = empty($content) ? '' : encoding_wp2xoops($content);

    //$block["indexNav"] = intval($options[1]);

    endif;

    return $block;
}
