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

function &wp_search($queryarray, $andor, $limit, $offset, $userid)
{
    global $xoopsDB, $myts;

    global $month, $wpdb, $wp_roles;

    require_once XOOPS_ROOT_PATH . '/modules/wordpress/wp-config.php';

    $myts = MyTextSanitizer::getInstance();

    $time_difference = get_settings('time_difference');

    $now = date('Y-m-d H:i:s', (time() + ($time_difference * 3600)));

    $where = "(post_status = 'publish') AND (post_date <= '" . $now . "')";

    if (is_array($queryarray) && $count = count($queryarray)) {
        $str_query = [];

        for ($i = 0; $i < $count; $i++) {
            $str_query[] = "(post_title LIKE '%" . encoding_xoops2wp($queryarray[$i]) . "%' OR post_content LIKE '%" . encoding_xoops2wp($queryarray[$i]) . "%')";
        }

        $where .= ' AND ' . implode(" $andor ", $str_query);
    }

    if ($userid) {
        $userid = (int)$userid;

        $where .= ' AND (post_author=' . $userid . ')';
    }

    $request = 'SELECT * FROM ' . $xoopsDB->prefix('wp_posts') . ' WHERE ' . $where;

    $request .= ' ORDER BY post_date DESC';

    $result = $xoopsDB->query($request, $limit, $offset);

    $ret = [];

    $i = 0;

    while (false !== ($myrow = $xoopsDB->fetchArray($result))) {
        $ret[$i]['link'] = str_replace(get_settings('home') . '/', '', get_permalink(($myrow['ID'])));

        $ret[$i]['title'] = htmlspecialchars(encoding_wp2xoops($myrow['post_title']), ENT_QUOTES | ENT_HTML5);

        $date_str = $myrow['post_date'];

        $yyyy = mb_substr($date_str, 0, 4);

        $mm = mb_substr($date_str, 5, 2);

        $dd = mb_substr($date_str, 8, 2);

        $hh = mb_substr($date_str, 11, 2);

        $nn = mb_substr($date_str, 14, 2);

        $ss = mb_substr($date_str, 17, 2);

        $ret[$i]['time'] = mktime($hh, $nn, $ss, $mm, $dd, $yyyy);

        $ret[$i]['uid'] = $myrow['post_author'];

        $ret[$i]['page'] = htmlspecialchars(encoding_wp2xoops($myrow['post_title']), ENT_QUOTES | ENT_HTML5);

        $i++;
    }

    return $ret;
}
