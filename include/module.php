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
function wp_update_convert_encoding(&$text)
{
    $to = $GLOBALS['update_convert_encoding_to'];

    $from = $GLOBALS['update_convert_encoding_from'];

    require_once ABSPATH . 'wp-admin/upgrade-functions.php';

    load_functions('locale');

    $text = addslashes(deslash(XoopsLocal::convert_encoding($text, $to, $from)));

    return $text;
}

function xoops_module_pre_update_wordpress(&$module)
{
    return true;
}

function xoops_module_update_wordpress(&$module, $oldversion)
{
    wp_updateXoopsConfig();

    wp_setModuleConfig($module);

    //$oldversion = $module->getVar('version');

    global $wpdb, $wp_rewrite, $wp_queries, $table_prefix, $wp_db_version, $wp_roles;

    require_once XOOPS_ROOT_PATH . '/modules/' . $module->getVar('dirname') . '/wp-config.php';

    require_once ABSPATH . 'wp-admin/upgrade-functions.php';

    // Cleanup options data for rss

    $result = $GLOBALS['xoopsDB']->queryF(
        '	DELETE FROM ' . $GLOBALS['xoopsDB']->prefix('wp_options') . "	WHERE `option_name` LIKE 'rss_%' AND LENGTH(`option_name`) > 35"
    );

    if (!$result) {
        $module->setMessage('Could not cleanup rss options');
    }

    wp_cache_flush();

    make_db_current_silent();

    upgrade_all();

    wp_cache_flush();

    if ($oldversion < 150):

        $result = $GLOBALS['xoopsDB']->queryF(
            'CREATE TABLE ' . $GLOBALS['xoopsDB']->prefix('wp_views') . "(
		`post_id` bigint(20) NOT NULL default '0',
		`post_views` bigint(20) NOT NULL default '0'
		)"
        );

    if (!$result) {
        $module->setMessage('Could not create wp_views');
    }

    $drop_list = ['backlist'];

    foreach ($drop_list as $drop) {
        $result = $GLOBALS['xoopsDB']->queryF('DROP TABLE IF EXISTS ' . $GLOBALS['xoopsDB']->prefix('wp_' . $drop));

        if (!$result) {
            $module->setMessage('Could not drop wp_' . $drop);
        } else {
            $module->setMessage('Dropped wp_' . $drop);
        }
    }

    update_option('fileupload_minlevel', '3');

    update_option('ping_sites', 'http://rpc.pingomatic.com/\nhttp://ping.xoopsforge.com/');

    update_option('rss_language', _LANGCODE);

    $wpdb->query("INSERT INTO $wpdb->links (link_url, link_name, link_category, link_rss) VALUES ('http://boren.nu/', 'Ryan', 1, 'http://boren.nu/feed/');");

    $wpdb->query("INSERT INTO $wpdb->links (link_url, link_name, link_category, link_rss) VALUES ('http://photomatt.net/', 'Matt', 1, 'http://xml.photomatt.net/feed/');");

    $wpdb->query("INSERT INTO $wpdb->links (link_url, link_name, link_category, link_rss) VALUES ('http://xoopsforge.com/', 'XForge', 1, 'http://xoopsforge.com/modules/wordpress/wp-rss.php');");

    // posts encoding and views update

    $sql = 'SELECT * FROM ' . $GLOBALS['xoopsDB']->prefix('wp_posts');

    $result = $GLOBALS['xoopsDB']->query($sql);

    while (false !== ($row = $GLOBALS['xoopsDB']->fetchArray($result))) {
        $ID = $row['ID'];

        $post_views = empty($row['post_views']) ? 0 : $row['post_views'];

        $GLOBALS['xoopsDB']->queryF('INSERT INTO ' . $GLOBALS['xoopsDB']->prefix('wp_views') . " (post_id, post_views) VALUES ($ID, $post_views);");
    }

    @$GLOBALS['xoopsDB']->queryF("ALERT TABLE $wpdb->posts DROP `post_views`");

    wp_content_encoding(wp_blog_charset(), _CHARSET, $module);

    endif;

    if ($oldversion < 152):

        /* add missing option */ update_option('gmt_offset', $GLOBALS['xoopsConfig']['default_TZ'], '', false);

    endif;

    if ($oldversion < 201):

        /* add new option for uploads */ update_option('uploads_use_yearmonth_folders', 1);

    update_option('upload_path', $module->getVar('dirname'));

    endif;

    if ($oldversion < 205):
        $request = "SELECT ID, post_content FROM {$wpdb->posts}  WHERE post_status = 'publish' ";

    if ($lposts = $wpdb->get_results($request)) {
        require_once __DIR__ . '/functions.tag.php';

        global $post;

        foreach ($lposts as $lpost) {
            $post = $lpost;

            onaction_set_tags();
        }
    }

    endif;

    return true;
}

function xoops_module_pre_install_wordpress($module)
{
    wp_setModuleConfig($module);

    return true;
}

function xoops_module_install_WordPress($module)
{
    wp_updateXoopsConfig();

    /* Set post permissions */

    $module_id = $module->getVar('mid');

    $gpermHandler = xoops_getHandler('groupperm');

    // user can post draft

    $gpermHandler->addRight('global', 1, XOOPS_GROUP_USERS, $module->getVar('mid'));

    // user can publish

    $gpermHandler->addRight('global', 2, XOOPS_GROUP_USERS, $module->getVar('mid'));

    $ori_error_level = ini_get('error_reporting');

    define('ERROR_REPORTING_WORDPRESS', E_ERROR);

    define('WP_INSTALLING', true);

    ob_start();

    global $wpdb, $wp_rewrite, $wp_queries, $table_prefix, $wp_db_version, $wp_roles;

    require_once XOOPS_ROOT_PATH . '/modules/' . $module->getVar('dirname') . '/wp-config.php';

    require_once ABSPATH . 'wp-admin/upgrade-functions.php';

    wp_cache_flush();

    make_db_current_silent();

    populate_options();

    populate_roles();

    ob_end_clean();

    error_reporting($ori_error_level);

    update_option('blogname', _MI_WORDPRESS_NAME);

    update_option('blogdescription', _MI_WORDPRESS_DESC);

    update_option('gmt_offset', $GLOBALS['xoopsConfig']['default_TZ']);

    update_option('rss_language', _LANGCODE);

    update_option('blog_charset', wp_blog_charset());

    update_option('siteurl', XOOPS_URL . '/modules/' . $module->getVar('dirname'));

    update_option('admin_email', $GLOBALS['xoopsConfig']['adminmail']);

    update_option('fileupload_realpath', XOOPS_UPLOAD_PATH . '/' . $module->getVar('dirname'));

    update_option('fileupload_url', XOOPS_URL . '/uploads/' . $module->getVar('dirname'));

    update_option('ping_sites', 'http://rpc.pingomatic.com/\nhttp://ping.xoopsforge.com/');

    /* add new option for uploads */

    update_option('uploads_use_yearmonth_folders', 1);

    update_option('upload_path', $module->getVar('dirname'));

    /* activate the tag plugin */

    $plugin_current = 'flickr.php';

    update_option('active_plugins', [$plugin_current]);

    include ABSPATH . 'wp-content/plugins/' . $plugin_current;

    do_action('activate_' . $plugin_current);

    // Now drop in some default links

    $wpdb->query("INSERT INTO $wpdb->linkcategories (cat_id, cat_name) VALUES (1, '" . addslashes(__('Blogroll')) . "')");

    $wpdb->query("INSERT INTO $wpdb->links (link_url, link_name, link_category, link_rss) VALUES ('http://boren.nu/', 'Ryan', 1, 'http://boren.nu/feed/');");

    $wpdb->query("INSERT INTO $wpdb->links (link_url, link_name, link_category, link_rss) VALUES ('http://photomatt.net/', 'Matt', 1, 'http://xml.photomatt.net/feed/');");

    $wpdb->query("INSERT INTO $wpdb->links (link_url, link_name, link_category, link_rss) VALUES ('http://xoopsforge.com/', 'XForge', 1, 'http://xoopsforge.com/modules/wordpress/wp-rss.php');");

    // Default category

    $wpdb->query("INSERT INTO $wpdb->categories (cat_ID, cat_name, category_nicename, category_count, category_description) VALUES ('0', '" . $wpdb->escape(__('Uncategorized')) . "', '" . sanitize_title(__('Uncategorized')) . "', '1', '')");

    // It is hard to believe but we have to consider the module being installed on Xoops installation, which some variables or objects are not available !

    $ID = is_object($GLOBALS['xoopsUser']) ? $GLOBALS['xoopsUser']->getVar('uid') : 1;

    if (is_object($GLOBALS['xoopsUser'])) {
        $theUser = &$GLOBALS['xoopsUser'];
    } else {
        $memberHandler = xoops_getHandler('member');

        $theUser = $memberHandler->getUser($ID);
    }

    // Change uid field

    $wpdb->query("ALTER TABLE $wpdb->posts CHANGE `post_author` `post_author` mediumint(8) NOT NULL DEFAULT '0'");

    // First post

    $now = date('Y-m-d H:i:s');

    $now_gmt = gmdate('Y-m-d H:i:s');

    $wpdb->query(
        "INSERT INTO $wpdb->posts (post_author, post_date, post_date_gmt, post_content, post_excerpt, post_title, post_category, post_name, post_modified, post_modified_gmt, comment_count, to_ping, pinged, post_content_filtered) VALUES ('$ID', '$now', '$now_gmt', '" . $wpdb->escape(
            __('Welcome to WordPress. This is your first post. Edit or delete it, then start blogging!')
        ) . "', '', '" . $wpdb->escape(__('Hello world!')) . "', '0', '" . $wpdb->escape(__('hello-world')) . "', '$now', '$now_gmt', '1', '', '', '')"
    );

    $wpdb->query("INSERT INTO $wpdb->post2cat (`rel_id`, `post_id`, `category_id`) VALUES (1, 1, 1)");

    // Default comment

    $wpdb->query(
        "INSERT INTO $wpdb->comments (comment_post_ID, comment_author, comment_author_email, comment_author_url, comment_author_IP, comment_date, comment_date_gmt, comment_content) VALUES ('1', '"
        . $wpdb->escape(__('Mr XPress'))
        . "', '', 'http://xoopsforge.com', '127.0.0.1', '$now', '$now_gmt', '"
        . $wpdb->escape(__('Hi, this is a comment.<br>To delete a comment, just log in, and view the posts\' comments, there you will have the option to edit or delete them.'))
        . "')"
    );

    // First Page

    $wpdb->query(
        "INSERT INTO $wpdb->posts (post_author, post_date, post_date_gmt, post_content, post_excerpt, post_title, post_category, post_name, post_modified, post_modified_gmt, post_status, to_ping, pinged, post_content_filtered) VALUES ('$ID', '$now', '$now_gmt', '" . $wpdb->escape(
            __('This is an example of a WordPress page, you could edit this to put information about yourself or your site so readers know where you are coming from. You can create as many pages like this one or sub-pages as you like and manage all of your content inside of WordPress.')
        ) . "', '', '" . $wpdb->escape(__('About')) . "', '0', '" . $wpdb->escape(__('about')) . "', '$now', '$now_gmt', 'static', '', '', '')"
    );

    generate_page_rewrite_rules();

    $GLOBALS['update_convert_encoding_to'] = wp_blog_charset();

    $GLOBALS['update_convert_encoding_from'] = _CHARSET;

    // Set up admin user

    $user_login = $theUser->getVar('uname');

    $user_login = encoding_xoops2wp($user_login);

    $display_name = $theUser->getVar('name') ? encoding_xoops2wp($theUser->getVar('name')) : $user_login;

    $user_nicename = $ID;

    $password = $theUser->getVar('pass');

    $admin_email = $theUser->getVar('email');

    $wpdb->query("INSERT INTO $wpdb->users (ID, user_login, user_pass, user_email, user_registered, display_name, user_nicename) VALUES ( $ID, '$user_login', '$password', '$admin_email', NOW(), '$display_name', '$user_nicename')");

    $wpdb->query("INSERT INTO $wpdb->usermeta (user_id, meta_key, meta_value) VALUES ($ID, '{$table_prefix}user_level', '10');");

    $admin_caps = serialize(['administrator' => true]);

    $wpdb->query("INSERT INTO $wpdb->usermeta (user_id, meta_key, meta_value) VALUES ($ID, '{$table_prefix}capabilities', '{$admin_caps}');");

    return true;
}

function wp_updateXoopsConfig()
{
    $GLOBALS['xoopsDB']->queryF('ALTER TABLE ' . $GLOBALS['xoopsDB']->prefix('config') . ' CHANGE `conf_name` `conf_name` varchar(64) NOT NULL default ' . $GLOBALS['xoopsDB']->quoteString(''));

    $GLOBALS['xoopsDB']->queryF('ALTER TABLE ' . $GLOBALS['xoopsDB']->prefix('config') . ' CHANGE `conf_title` `conf_title` varchar(64) NOT NULL default ' . $GLOBALS['xoopsDB']->quoteString(''));

    $GLOBALS['xoopsDB']->queryF('ALTER TABLE ' . $GLOBALS['xoopsDB']->prefix('config') . ' CHANGE `conf_desc` `conf_desc` varchar(64) NOT NULL default ' . $GLOBALS['xoopsDB']->quoteString(''));

    $GLOBALS['xoopsDB']->queryF('ALTER TABLE ' . $GLOBALS['xoopsDB']->prefix('configcategory') . ' CHANGE `confcat_name` `confcat_name` varchar(64) NOT NULL default ' . $GLOBALS['xoopsDB']->quoteString(''));

    $GLOBALS['xoopsDB']->queryF('ALTER TABLE ' . $GLOBALS['xoopsDB']->prefix('xoopsnotifications') . ' CHANGE `not_category` `not_category` varchar(64) NOT NULL default ' . $GLOBALS['xoopsDB']->quoteString(''));

    $GLOBALS['xoopsDB']->queryF('ALTER TABLE ' . $GLOBALS['xoopsDB']->prefix('xoopsnotifications') . ' CHANGE `not_event` `not_event` varchar(64) NOT NULL default ' . $GLOBALS['xoopsDB']->quoteString(''));

    return true;
}

function wp_setModuleConfig($module)
{
    $modconfig = &$module->getInfo('config');

    $count = count($modconfig);

    for ($i = 0; $i < $count; $i++) {
        if ('theme_set' == $modconfig[$i]['name']) {
            $modconfig[$i]['options'][_NONE] = '0';

            foreach ($GLOBALS['xoopsConfig']['theme_set_allowed'] as $theme) {
                $modconfig[$i]['options'][$theme] = $theme;
            }

            break;
        }
    }

    //$module->setInfo("config", $modconfig);

    return true;
}

function wp_content_encoding($to, $from, &$module)
{
    if (empty($to) || !strcasecmp($to, $from)) {
        return false;
    }

    global $wpdb, $wp_rewrite, $wp_queries, $table_prefix;

    $module = is_object($module) ? $module : $GLOBALS['xoopsModule'];

    require_once XOOPS_ROOT_PATH . '/modules/' . $module->getVar('dirname') . '/wp-config.php';

    $GLOBALS['update_convert_encoding_to'] = $to;

    $GLOBALS['update_convert_encoding_from'] = $from;

    // posts encoding and views update

    $vars = ['post_title', 'post_content', 'post_excerpt', 'post_name', 'post_content_filtered'];

    $id = 'ID';

    $table = $wpdb->posts;

    $items = $wpdb->get_results("SELECT $id, " . implode(',', $vars) . " FROM $table");

    if ($items) {
        foreach ($items as $item) {
            $var_string = [];

            foreach ($vars as $var) {
                $var_string[] = $var . " = '" . wp_update_convert_encoding($item->$var) . "'";
            }

            $var_string = implode(',', $var_string);

            $wpdb->query("UPDATE $table SET " . $var_string . " WHERE $id = '" . $item->$id . "'");
        }
    }

    // category encoding

    $vars = ['cat_name', 'category_nicename', 'category_description'];

    $id = 'cat_ID';

    $table = $wpdb->categories;

    $items = $wpdb->get_results("SELECT $id, " . implode(',', $vars) . " FROM $table");

    if ($items) {
        foreach ($items as $item) {
            $var_string = [];

            foreach ($vars as $var) {
                $var_string[] = $var . " = '" . wp_update_convert_encoding($item->$var) . "'";
            }

            $var_string = implode(',', $var_string);

            $wpdb->query("UPDATE $table SET " . $var_string . " WHERE $id = '" . $item->$id . "'");
        }
    }

    // comments encoding

    $vars = ['comment_content', 'comment_author'];

    $id = 'comment_ID';

    $table = $wpdb->comments;

    $items = $wpdb->get_results("SELECT $id, " . implode(',', $vars) . " FROM $table");

    if ($items) {
        foreach ($items as $item) {
            $var_string = [];

            foreach ($vars as $var) {
                $var_string[] = $var . " = '" . wp_update_convert_encoding($item->$var) . "'";
            }

            $var_string = implode(',', $var_string);

            $wpdb->query("UPDATE $table SET " . $var_string . " WHERE $id = '" . $item->$id . "'");
        }
    }

    // linkcats encoding

    $vars = ['cat_name'];

    $id = 'cat_id';

    $table = $wpdb->linkcategories;

    $items = $wpdb->get_results("SELECT $id, " . implode(',', $vars) . " FROM $table");

    if ($items) {
        foreach ($items as $item) {
            $var_string = [];

            foreach ($vars as $var) {
                $var_string[] = $var . " = '" . wp_update_convert_encoding($item->$var) . "'";
            }

            $var_string = implode(',', $var_string);

            $wpdb->query("UPDATE $table SET " . $var_string . " WHERE $id = '" . $item->$id . "'");
        }
    }

    // links encoding

    $vars = ['link_name', 'link_description', 'link_notes'];

    $id = 'link_id';

    $table = $wpdb->links;

    $items = $wpdb->get_results("SELECT $id, " . implode(',', $vars) . " FROM $table");

    if ($items) {
        foreach ($items as $item) {
            $var_string = [];

            foreach ($vars as $var) {
                $var_string[] = $var . " = '" . wp_update_convert_encoding($item->$var) . "'";
            }

            $var_string = implode(',', $var_string);

            $wpdb->query("UPDATE $table SET " . $var_string . " WHERE $id = '" . $item->$id . "'");
        }
    }

    // options encoding

    $vars = ['option_value', 'option_description'];

    $id = 'option_id';

    $table = $wpdb->options;

    $items = $wpdb->get_results("SELECT $id, " . implode(',', $vars) . " FROM $table");

    if ($items) {
        foreach ($items as $item) {
            $var_string = [];

            foreach ($vars as $var) {
                $var_string[] = $var . " = '" . wp_update_convert_encoding($item->$var) . "'";
            }

            $var_string = implode(',', $var_string);

            $wpdb->query("UPDATE $table SET " . $var_string . " WHERE $id = '" . $item->$id . "'");
        }
    }

    // users encoding

    $vars = ['user_login', 'user_firstname', 'user_lastname', 'user_nickname', 'user_description'];

    $id = 'ID';

    $table = $wpdb->users;

    $items = $wpdb->get_results("SELECT $id, " . implode(',', $vars) . " FROM $table");

    if ($items) {
        foreach ($items as $item) {
            $var_string = [];

            foreach ($vars as $var) {
                $var_string[] = $var . " = '" . wp_update_convert_encoding($item->$var) . "'";
            }

            $var_string = implode(',', $var_string);

            $sql = "UPDATE $table SET " . $var_string . " WHERE $id = '" . $item->$id . "'";

            $wpdb->query($sql);
        }
    }

    return true;
}
