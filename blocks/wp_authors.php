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
function b_wordpress_authors_show($options)
{
    $block['content'] = '<ul>' . list_authors_xoops($options[0], $options[1], $options[2]) . '</ul>';

    $block['content'] = encoding_wp2xoops($block['content']);

    return $block;
}

function b_wordpress_authors_edit($options)
{
    $form = _MB_WORDPRESS_COUNT . "<input type='text' name='options[0]' value='" . $options[0] . "'>";

    $form .= '<br>' . _MB_WORDPRESS_EXCLUEDEADMIN . '<input type="radio" name="options[1]" value="1"';

    if (1 == $options[1]) {
        $form .= ' checked="checked"';
    }

    $form .= '>' . _YES . '; <input type="radio" name="options[1]" value="0"';

    if (0 == $options[1]) {
        $form .= ' checked="checked"';
    }

    $form .= '>' . _NO;

    $form .= '<br>' . _MB_WORDPRESS_REALNAME . '<input type="radio" name="options[2]" value="1"';

    if (1 == $options[2]) {
        $form .= ' checked="checked"';
    }

    $form .= '>' . _YES . '; <input type="radio" name="options[2]" value="0"';

    if (0 == $options[2]) {
        $form .= ' checked="checked"';
    }

    $form .= '>' . _NO;

    return $form;
}

function list_authors_xoops($count = 10, $exclude_admin = true, $show_realname = false)
{
    /* For wp blocks */ global $wpdb, $wp_query, $wp_rewrite, $wp_roles;

    global $m, $monthnum, $year, $timedifference, $month, $month_abbrev, $weekday, $weekday_initial, $weekday_abbrev, $posts, $category_posts, $use_cache;

    $myts = MyTextSanitizer::getInstance();

    $memberHandler = xoops_getHandler('member');

    if ($exclude_admin) {
        $admins = implode(',', $memberHandler->getUsersByGroup(XOOPS_GROUP_ADMIN));
    }

    require dirname(__DIR__) . '/wp-config.php';

    $query = "SELECT COUNT(ID) AS pcount, post_author AS uid from $wpdb->posts WHERE post_status = 'publish' " . ($exclude_admin ? 'AND post_author NOT IN (' . $admins . ') ' : '') . ' GROUP BY post_author ORDER BY pcount DESC';

    $_authors = $wpdb->get_results($query);

    if (!is_array($_authors) || 0 == count($_authors)) {
        return null;
    }

    $authors = [];

    foreach ($_authors as $author) {
        $authors[$author->uid] = $author->pcount;
    }

    require_once XOOPS_ROOT_PATH . '/Frameworks/art/functions.ini.php';

    load_functions('user');

    $users = mod_getUnameFromIds(array_keys($authors));

    $author_link = '';

    foreach (array_keys($authors) as $uid) {
        $author_link .= '<li><a href="' . get_author_link(0, $uid, $uid) . '" title="Posts by ' . $users[$uid] . '">' . $users[$uid] . ' (' . (int)$authors[$uid] . ')</a></li>';
    }

    return $author_link;
}
