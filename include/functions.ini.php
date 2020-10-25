<?php

/**
 * XPress - WordPress for XOOPS
 *
 * Adding multi-author features to XPress
 *
 * @copyright      The XOOPS project https://www.xoops.org/
 * @license        http://www.fsf.org/copyleft/gpl.html GNU public license
 * @author         Taiwen Jiang (phppp or D.J.) <php_pp@hotmail.com>
 * @since          2.04
 * @version        $Id$
 */
require_once XOOPS_ROOT_PATH . '/Frameworks/art/functions.ini.php';

function wp_blog_charset($skip_cache = false)
{
    static $wp_blog_charset;

    if (isset($wp_blog_charset) && empty($skip_cache)) {
        return $wp_blog_charset;
    }

    if (!defined('WP_BLOG_CHARSET') || 0 == constant('WP_BLOG_CHARSET')) {
        global $xlanguage;

        if ($skip_cache) {
            return (!empty($xlanguage['action']) && !empty($xlanguage['charset_base'])) ? $xlanguage['charset_base'] : _CHARSET;
        }

        $wp_blog_charset = (!empty($xlanguage['action']) && !empty($xlanguage['charset_base'])) ? $xlanguage['charset_base'] : _CHARSET;
    } else {
        $GLOBALS['blog_charset_skip_filter'] = true;

        $blog_charset = get_settings('blog_charset');

        if ($skip_cache) {
            return empty($blog_charset) ? WP_MO_CHARSET : $blog_charset;
        }

        $wp_blog_charset = empty($blog_charset) ? WP_MO_CHARSET : $blog_charset;
    }

    return $wp_blog_charset;
}

function wp_rss_charset()
{
    static $wp_rss_charset;

    if (isset($wp_rss_charset)) {
        return $wp_rss_charset;
    }

    $rss_charset = 0;

    if (is_object($GLOBALS['xoopsModule']) && WP_BLOG_DIRNAME == $GLOBALS['xoopsModule']->getVar('dirname')) {
        $rss_charset = (int)$GLOBALS['xoopsModuleConfig']['rss_charset'];
    }

    switch ($rss_charset) {
        case 1:
            $wp_rss_charset = wp_blog_charset();
            break;
        case 2:
            global $xlanguage;
            $wp_rss_charset = !empty($xlanguage['action']) && !empty($xlanguage['charset_base']) ? $xlanguage['charset_base'] : _CHARSET;
            break;
        default:
            $wp_rss_charset = 'UTF-8';
            break;
    }

    return $wp_rss_charset;
}

/*
function encoding_wp2xoops_l10n($text)
{
    $blog_charset = wp_blog_charset();
    return XoopsLocal::convert_encoding($text, $blog_charset, WP_MO_CHARSET);
}
*/

function encoding_wp2xoops($text)
{
    global $xlanguage;

    $to_charset = !empty($xlanguage['action']) && !empty($xlanguage['charset_base']) ? $xlanguage['charset_base'] : _CHARSET;

    $from_charset = wp_blog_charset();

    $text = _encoding_wp_xoops($text, $to_charset, $from_charset);

    return $text;
}

function encoding_xoops2wp($text)
{
    global $xlanguage;

    $from_charset = !empty($xlanguage['action']) && !empty($xlanguage['charset_base']) ? $xlanguage['charset_base'] : _CHARSET;

    $to_charset = wp_blog_charset();

    $text = _encoding_wp_xoops($text, $to_charset, $from_charset);

    return $text;
}

function encoding_rss2wp($text, $from = 'utf-8')
{
    $from = empty($from) ? 'utf-8' : $from;

    $to_charset = wp_blog_charset(true);

    $text = _encoding_wp_xoops($text, $to_charset, $from);

    return $text;
}

function encoding_wp2rss($text)
{
    $to = wp_rss_charset();

    $from = wp_blog_charset(true);

    $text = _encoding_wp_xoops($text, $to, $from);

    return $text;
}

function _encoding_wp_xoops(&$text, $to, $from)
{
    load_functions('locale');

    if (is_array($text)) {
        foreach (array_keys($text) as $key) {
            _encoding_wp_xoops($text[$key], $to, $from);
        }
    } else {
        $text = XoopsLocal::convert_encoding($text, $to, $from);
    }

    return $text;
}

function get_currentuserinfo()
{
    global $user_login, $userdata, $user_level, $user_ID, $user_email, $user_url, $user_pass_md5, $user_identity, $current_user;

    if (defined('XMLRPC_REQUEST') && XMLRPC_REQUEST) {
        return false;
    }

    if (!is_object($GLOBALS['xoopsUser'])) {
        wp_set_current_user(0);

        return false;
    }

    $user_login = $GLOBALS['xoopsUser']->getVar('uname');

    $user_login = encoding_xoops2wp($user_login);

    $userdata = get_userdatabylogin($user_login);

    //$user_level  = $userdata->user_level;

    $user_ID = $userdata->ID;

    $user_email = $userdata->user_email;

    $user_url = $userdata->user_url;

    $user_pass_md5 = $userdata->user_pass;

    $user_identity = $userdata->display_name;

    if (empty($current_user)) {
        $current_user = wp_set_current_user($user_ID);
    }
}

function get_userdata($user_id, $calLevel = false)
{
    global $wpdb, $table_prefix, $wp_roles;

    $user_id = (int)$user_id;

    if (0 == $user_id) {
        return false;
    }

    $user = wp_cache_get($user_id, 'users');

    if (is_object($user) && $user->ID == $user_id && (!empty($user->user_level) || empty($calLevel))) {
        return $user;
    }

    if (is_object($GLOBALS['xoopsUser']) && $user_id == $GLOBALS['xoopsUser']->getVar('uid')) {
        $xoopsuser = &$GLOBALS['xoopsUser'];
    } else {
        $memberHandler = xoops_getHandler('member');

        $xoopsuser = $memberHandler->getUser($user_id);

        if (!is_object($xoopsuser) || $xoopsuser->getVar('level') < 1) {
            return false;
        }
    }

    //$user->user_level = get_userlevel($xoopsuser);

    $user->ID = $xoopsuser->getVar('uid');

    $user->user_login = encoding_xoops2wp($xoopsuser->getVar('uname'));

    $user->display_name = $xoopsuser->getVar('name') ? encoding_xoops2wp($xoopsuser->getVar('name')) : $user->user_login;

    $user->user_nicename = $user->ID;

    $user->user_email = $xoopsuser->getVar('email');

    $user->user_url = XOOPS_URL . '/userinfo.php?uid=' . $xoopsuser->getVar('uid');

    $user->user_pass = $xoopsuser->getVar('pass');

    $user->user_description = encoding_xoops2wp($xoopsuser->getVar('bio'));

    $capabilities = $wp_roles->roles;

    $role = get_userrole($xoopsuser);

    $user->{$table_prefix . 'capabilities'} = [$role => $capabilities[$role]['capabilities']];

    $wpdb->hide_errors();

    $metavalues = $wpdb->get_results("SELECT meta_key, meta_value FROM $wpdb->usermeta WHERE user_id = '$user_id'");

    $wpdb->show_errors();

    if ($metavalues) {
        foreach ($metavalues as $meta) {
            @$value = unserialize($meta->meta_value);

            if (false === $value) {
                $value = $meta->meta_value;
            }

            $user->{$meta->meta_key} = $value;

            // We need to set user_level from meta, not row

            if ($wpdb->prefix . 'user_level' == $meta->meta_key) {
                $user->user_level = $meta->meta_value;
            }
        } // end foreach
    } //end if

    $user->description = $user->user_description;

    if (empty($user->user_level) && !empty($calLevel)) {
        $user->user_level = get_userlevel($xoopsuser);
    }

    // For backwards compat.

    if (isset($user->first_name)) {
        $user->user_firstname = $user->first_name;
    }

    if (isset($user->last_name)) {
        $user->user_lastname = $user->last_name;
    }

    wp_cache_add($user_id, $user, 'users');

    //wp_cache_add($user->user_login, $user, 'userlogins');

    return $user;
}

function get_userdatabylogin($user_login)
{
    global $wpdb;

    if (empty($user_login)) {
        return false;
    }

    $userdata = wp_cache_get($user_login, 'userlogins');

    if ($userdata) {
        return $userdata;
    }

    if (is_object($GLOBALS['xoopsUser']) && encoding_xoops2wp($GLOBALS['xoopsUser']->getVar('uname')) == $user_login) {
        $uid = $GLOBALS['xoopsUser']->getVar('uid');
    } else {
        $criteria = new CriteriaCompo(new Criteria('uname', encoding_wp2xoops($user_login)));

        $criteria->add(new Criteria('level', 0, '>'));

        $criteria->setLimit(1);

        $memberHandler = xoops_getHandler('member');

        if (!$users = $memberHandler->getUsers($criteria)) {
            return false;
        }

        $uid = $users[0]->getVar('uid');
    }

    $user = get_userdata($uid);

    //wp_cache_add($user->ID, $user, 'users');

    wp_cache_add($user->user_login, $user, 'userlogins');

    return $user;
}

function get_userrole($xoops_user)
{
    $role = 'subscriber';

    if ($xoops_user->isAdmin()) {
        return 'administrator';
    }

    if (!is_object($xoops_user)) {
        $groups = [XOOPS_GROUP_ANONYMOUS];
    } else {
        $groups = $xoops_user->groups();
    }

    $groupstring = '(' . implode(',', $groups) . ')';

    $criteria = new CriteriaCompo(new Criteria('gperm_modid', wp_get_moduleid()));

    $criteria->add(new Criteria('gperm_groupid', $groupstring, 'IN'));

    $gpermHandler = xoops_getHandler('groupperm');

    $perms = $gpermHandler->getObjects($criteria, true);

    $perm_levels = [
        1 => 'contributor',    // regular contributor
        2 => 'author',        // author, publish post
        3 => 'editor', // moderator
    ];

    $level = 0;

    foreach ($perms as $gperm_id => $gperm) {
        if (!empty($perm_levels[$gperm->getVar('gperm_itemid')]) && $gperm->getVar('gperm_itemid') > $level) {
            $level = $gperm->getVar('gperm_itemid');
        }
    }

    $role = empty($perm_levels[$level]) ? $role : $perm_levels[$level];

    return $role;
}

function get_userlevel($xoops_user)
{
    static $level;

    //define("WP_LEVEL_VIEW", 1);

    define('WP_LEVEL_DRAFT', 1);

    define('WP_LEVEL_POST', 2);

    define('WP_LEVEL_MODERATE', 3);

    if (!is_object($xoops_user)) {
        $groups = [XOOPS_GROUP_ANONYMOUS];

        return 0;
    }

    if ($xoops_user->isAdmin()) {
        return 10;
    }

    if (isset($level[$xoops_user->getVar('uid')])) {
        return $level[$xoops_user->getVar('uid')];
    }

    $groups = $xoops_user->groups();

    $groupstring = '(' . implode(',', $groups) . ')';

    $criteria = new CriteriaCompo(new Criteria('gperm_modid', wp_get_moduleid()));

    $criteria->add(new Criteria('gperm_groupid', $groupstring, 'IN'));

    $gpermHandler = xoops_getHandler('groupperm');

    $perms = &$gpermHandler->getObjects($criteria, true);

    //$level_upload = min(WP_LEVEL_MODERATE-1, get_settings('fileupload_minlevel'));

    $perm_levels = [
        1 => WP_LEVEL_DRAFT, // create draft,  create draft
        2 => WP_LEVEL_POST, // create post
        3 => WP_LEVEL_MODERATE, // moderate comments
    ];

    $level = 0;

    foreach ($perms as $gperm_id => $gperm) {
        if ($perm_levels[$gperm->getVar('gperm_itemid')] > $level) {
            $level = $perm_levels[$gperm->getVar('gperm_itemid')];
        }
    }

    unset($perms);

    return $level;
}

function wp_mail($to, $subject, $message, $from_email = '', $from_name = '')
{
    $xoopsMailer = getMailer();

    $xoopsMailer->useMail();

    $xoopsMailer->setToEmails($to);

    $xoopsMailer->setFromEmail(empty($from_email) ? $GLOBALS['xoopsConfig']['adminmail'] : $from_email);

    $xoopsMailer->setFromName(empty($from_name) ? $GLOBALS['xoopsConfig']['sitename'] : $from_name);

    $xoopsMailer->setSubject($subject);

    $xoopsMailer->setBody($message);

    return $xoopsMailer->send();
}

function wp_login($username, $password, $already_md5 = false)
{
    if (is_object($GLOBALS['xoopsUser'])) {
        return true;
    }
  

    return false;
}

function auth_redirect($force = false, $message = '')
{
    if (!is_object($GLOBALS['xoopsUser'])) {
        header('Expires: Wed, 11 Jan 1984 05:00:00 GMT');

        header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT');

        header('Cache-Control: no-cache, must-revalidate, max-age=0');

        header('Pragma: no-cache');

        header('Location: ' . get_settings('siteurl') . '/wp-login.php?redirect_to=' . urlencode($_SERVER['REQUEST_URI']));

        exit();
    } elseif (!empty($force)) {
        $redirect = xoops_getenv('HTTP_REFERER');

        $redirect = (0 === mb_strpos($redirect, XOOPS_URL)) ? $redirect : XOOPS_URL . '/' . $redirect;

        redirect_header($redirect, 2, encoding_wp2xoops($message));
    }
}

// Set and retrieves post views given a post ID or post object.
function the_views($post_id = 0)
{
    echo get_the_views($post_id);
}

// Retrieves post views given a post ID or post object.
function get_the_views($post_id = 0)
{
    static $post_cache_views;

    if (empty($post_id)) {
        if (isset($GLOBALS['post'])) {
            $post_id = $GLOBALS['post']->ID;
        }
    }

    $post_id = (int)$post_id;

    if (0 == $post_id) {
        return null;
    }

    if (!isset($post_cache_views[$post_id])) {
        $sql = 'SELECT post_views FROM ' . $GLOBALS['xoopsDB']->prefix('wp_views') . " WHERE post_id=$post_id";

        if (!$result = $GLOBALS['xoopsDB']->query($sql)) {
            $post_cache_views[$post_id] = 0;
        } else {
            $row = $GLOBALS['xoopsDB']->fetchArray($result);

            $post_cache_views[$post_id] = $row['post_views'];
        }
    }

    return sprintf(__('Views: %d'), (int)$post_cache_views[$post_id]);
}

function onaction_set_the_views($content)
{
    if (empty($_GET['feed']) && empty($GLOBALS['feed']) && empty($GLOBALS['doing_trackback']) && empty($GLOBALS['doing_rss']) && empty($_POST) && is_single()) {
        set_the_views();
    }

    return $content;
}

// Set post views given a post ID or post object.
function set_the_views($post_id = 0)
{
    static $views;

    $post_id = (int)$post_id;

    if (empty($post_id) && isset($GLOBALS['post'])) {
        $post_id = $GLOBALS['post']->ID;
    }

    if (0 == $post_id || !empty($views[$post_id])) {
        return null;
    }

    $sql = 'SELECT post_views FROM ' . $GLOBALS['xoopsDB']->prefix('wp_views') . " WHERE post_id=$post_id";

    $exist = false;

    if ($result = $GLOBALS['xoopsDB']->query($sql)) {
        while (false !== ($row = $GLOBALS['xoopsDB']->fetchArray($result))) {
            $exist = true;

            break;
        }
    }

    if ($exist) {
        $sql = 'UPDATE ' . $GLOBALS['xoopsDB']->prefix('wp_views') . " SET post_views=post_views+1 WHERE post_id=$post_id";
    } else {
        $sql = 'INSERT INTO ' . $GLOBALS['xoopsDB']->prefix('wp_views') . " (post_id, post_views) VALUES ($post_id, 1)";
    }

    if ($result = $GLOBALS['xoopsDB']->queryF($sql)) {
        $views[$post_id] = 1;
    }

    return true;
}

function onaction_edit_post($post_id = 0)
{
    if (function_exists('flickr_clear_cache')) { // tricky!
        flickr_clear_cache($post_id);
    }

    return true;
}

function onaction_gettext($text_translated, $text = '')
{
    if (empty($text_translated)) {
        return $text;
    }

    $blog_charset = wp_blog_charset();

    load_functions('locale');

    return XoopsLocal::convert_encoding($text_translated, $blog_charset, WP_MO_CHARSET);
}

// Insert user information into wp-users when he publishes his first article
// adapted from wp-includes/registration-functions.php

function onaction_wp_register_user($post_id = null)
{
    global $wpdb, $table_prefix;

    $post = get_post($post_id);

    $user = get_userdata($post->post_author, true);

    if (!$wpdb->get_results("SELECT user_login FROM $wpdb->users WHERE ID = $user->ID LIMIT 1")) {
        $result = $wpdb->query(
            "INSERT INTO $wpdb->users 
			( ID, user_login, display_name, user_nicename )
		VALUES 
			( $user->ID, '" . addslashes($user->user_login) . "', '" . addslashes($user->display_name) . "', '" . addslashes($user->user_nicename) . "' )"
        );

        wp_cache_delete($user->ID, 'users');

        wp_cache_delete($user->user_login, 'userlogins');

        do_action('user_register', $user->ID);
    }

    return $user->ID;
}

function the_author_link_xoops($idmode = '')
{
    global $id, $authordata;

    if (!isset($authordata)) {
        global $wp_query;

        $author = empty($wp_query->query_vars['author']) ? (empty($wp_query->query_vars['author_name']) ? 0 : $wp_query->query_vars['author_name']) : $wp_query->query_vars['author'];

        $authordata = get_userdata($author);
    }

    echo '<a href="' . XOOPS_URL . '/userinfo.php?uid=' . $authordata->ID . '" title="' . wp_specialchars(the_author($idmode, false)) . '">' . the_author($idmode, false) . '</a>';
}

// handler IO between XOOPS and WordPress
function wp_xoops_filter()
{
    add_action('gettext', 'onaction_gettext');

    add_action('publish_post', 'onaction_wp_register_user');

    add_action('edit_post', 'onaction_edit_post');

    add_action('the_content', 'onaction_set_the_views');

    if (!empty($GLOBALS['xoopsModuleConfig']['do_tag'])) {
        require_once __DIR__ . '/functions.tag.php';

        add_action('publish_post', 'onaction_set_tags');

        add_action('edit_post', 'onaction_set_tags');

        add_action('the_content', 'onaction_get_tags');
    }

    add_filter('the_content', 'wp_filter_xoopsdecode', 1);

    add_filter('get_the_excerpt', 'wp_filter_xoopsdecode', 1);

    add_filter('comment_text', 'wp_filter_xoopsdecode', 1);

    add_filter('pre_option_siteurl', 'onaction_pre_option_siteurl');

    add_filter('pre_option_home', 'onaction_pre_option_home');

    add_filter('option_home', 'onaction_home');

    add_filter('option_siteurl', 'onaction_siteurl');

    add_filter('option_blog_charset', 'onaction_blog_charset');

    add_filter('template_directory', 'set_template_directory');

    // Escape homepage for single user mode

    add_filter('home_template', 'onaction_home_template');

    $action_list = ['option_blogdescription', 'option_blogname', 'the_title_rss', 'the_content', 'get_the_excerpt', 'comment_author_rss', 'get_comment_text', 'the_category_rss'];

    foreach ($action_list as $action) {
        add_filter($action, 'onaction_rss_encoding');
    }
}

function wp_filter_xoopsdecode(&$text)
{
    $patterns[] = "/\[quote]/sU";

    $replacements[] = '<div class="xoopsQuote"><blockquote>';

    $patterns[] = "/\[\/quote]/sU";

    $replacements[] = '</blockquote></div>';

    $text = preg_replace($patterns, $replacements, $text);

    $myts = MyTextSanitizer::getInstance();

    $text = $myts->displayTarea($text, $html = 1/*, $smiley = 1, $xcode = 1, $image = 1, $br = 1*/);

    return $text;
}

function onaction_rss_encoding($var = '')
{
    static $action;

    $action = $action ?? (!empty($_GET['feed']) || !empty($GLOBALS['feed']) || !empty($GLOBALS['doing_trackback']) || !empty($GLOBALS['doing_rss']));

    return ($action) ? encoding_wp2rss($var) : $var;
}

function wp_upload_dir()
{
    if ($path = trim(get_settings('upload_path'))) {
        $dir = XOOPS_UPLOAD_PATH . '/' . $path;

        $url = XOOPS_UPLOAD_URL . '/' . $path;
    } elseif (defined('UPLOADS')) {
        $dir = XOOPS_UPLOAD_PATH . '/' . UPLOADS;

        $url = XOOPS_UPLOAD_URL . '/' . UPLOADS;
    } else {
        $dir = XOOPS_UPLOAD_PATH . '/' . WP_BLOG_DIRNAME;

        $url = XOOPS_UPLOAD_URL . '/' . WP_BLOG_DIRNAME;
    }

    // Give the new dirs the same perms as XOOPS_UPLOAD_PATH.

    $stat = stat(XOOPS_UPLOAD_PATH);

    $dir_perms = $stat['mode'] & 0000777;  // Get the permission bits.

    if (get_settings('uploads_use_yearmonth_folders')) {
        // Generate the yearly and monthly dirs

        $time = current_time('mysql');

        $y = mb_substr($time, 0, 4);

        $m = mb_substr($time, 5, 2);

        $dir .= "/$y/$m";

        $url .= "/$y/$m";
    }

    // Make sure we have an uploads dir

    if (!wp_mkdir_p($dir)) {
        $message = sprintf(__('Unable to create directory %s. Is its parent directory writable by the server?'), $dir);

        return ['error' => $message];
    }

    $uploads = ['path' => $dir, 'url' => $url, 'error' => false];

    return apply_filters('upload_dir', $uploads);
}

function onaction_pre_option_siteurl($var = '')
{
    return onaction_siteurl($var);
}

function onaction_pre_option_home($var = '')
{
    return onaction_siteurl($var);
}

function onaction_home($var = '')
{
    return onaction_siteurl($var);
}

function onaction_siteurl($var = '')
{
    return XOOPS_URL . '/modules/' . WP_BLOG_DIRNAME;
}

function onaction_home_template($var = '')
{
    if (!wp_xoops_ismu()) {
        return null;
    }
  

    return $var;
}

function onaction_blog_charset($charset = '')
{
    if (!empty($GLOBALS['blog_charset_skip_filter'])) {
        $GLOBALS['blog_charset_skip_filter'] = false;
    } elseif (!empty($_GET['feed']) || !empty($GLOBALS['feed']) || !empty($GLOBALS['doing_trackback']) || !empty($GLOBALS['doing_rss'])) {
        $charset = wp_rss_charset();
    } else {
        $charset = wp_blog_charset();
    }

    return $charset;
}

// Set the template style
// Convert _POST from XOOPS
function set_template_directory($template_dir = '', $template = '')
{
    // Ideally the following treatment should be done by setting "accept-charset" attribute in "form" tag, however its compatibility with browsers is unexpectable

    if (!empty($_POST['post_from_xoops'])) {
        unset($_POST['post_from_xoops']);

        foreach ($_POST as $key => $val) {
            if (is_array($_POST[$key])) {
                foreach (array_keys($_POST[$key]) as $_key) {
                    $_POST[$key][$_key] = encoding_xoops2wp($_POST[$key][$_key]);
                }
            } else {
                $_POST[$key] = encoding_xoops2wp($val);
            }
        }
    }

    switch ($GLOBALS['xoopsModuleConfig']['style']) {
        // fixed as XOOPS
        case 1:
            $style = 1;
            break;
        // fixed as WP
        case 2:
            $style = 0;
            break;
        // selectable
        default:
            $style = $_GET['style'] ?? ($_COOKIE['wp_style'] ?? '');
            $style = ('w' == $style) ? 0 : 1;
            if (isset($_GET['style'])) {
                setcookie('wp_style', $_GET['style']);
            }
            break;
    }

    if (empty($style)) {
        return $template_dir;
    }  

    /*
    if(!empty($GLOBALS["xoopsModuleConfig"]['theme_set']) && $GLOBALS["xoopsOption"]["pagetype"] != "admin" && is_object($GLOBALS['xoopsModule']) && $GLOBALS['xoopsModule']->getVar("dirname") == "wordpress"){
        //$GLOBALS["xoopsConfig"]['theme_set'] = $GLOBALS["xoopsModuleConfig"]['theme_set'];
    }
    */

    return ABSPATH . 'templates/wordpress';
}

function wp_the_excerpt()
{
    if (empty($GLOBALS['post']->post_excerpt)) {
        return;
    }

    ob_start();

    the_excerpt();

    $excerpt = ob_get_contents();

    ob_end_clean();

    echo '[' . __('Excerpt') . ']: ' . $excerpt;
}

function wp_the_content($echo = true)
{
    ob_start();

    if (empty($GLOBALS['xoopsModuleConfig']['show_excerpt'])) {
        the_content(__('(more...)'));
    } else {
        the_excerpt();

        echo '<a href="' . get_permalink() . '">' . __('(more...)') . '</a>';
    }

    $content = ob_get_contents();

    ob_end_clean();

    if (empty($echo)) {
        return $content;
    }

    echo $content;
}

function wp_get_moduleid()
{
    static $mid;

    if (!empty($mid)) {
        return $mid;
    }

    if (is_object($GLOBALS['xoopsModule']) && WP_BLOG_DIRNAME == $GLOBALS['xoopsModule']->getVar('dirname')) {
        $mid = $GLOBALS['xoopsModule']->getVar('mid');
    } else {
        $moduleHandler = xoops_getHandler('module');

        $module = $moduleHandler->getByDirname(WP_BLOG_DIRNAME);

        $mid = $module->getVar('mid');

        unset($module);
    }

    return $mid;
}

function wp_get_user_profile($format = 's', $uid = null)
{
    global $xoopsUser;

    if (null === $uid) {
        $user = &$xoopsUser;
    } elseif ($uid > 0) {
        $memberHandler = xoops_getHandler('member');

        $user = $memberHandler->getUser($uid);
    } else {
        return null;
    }

    return is_object($user) ? encoding_xoops2wp($user->getVar('bio', $format)) : null;
}

/**
 * Enable multi-user mode
 *
 * Available features:
 * Each author has a separate page;
 * All contents including posts/comments are author oriented.
 *
 * Limitations (TODO):
 * Category structure is controlled by admin only
 * An author does not have the possibility to have his/her own templates
 * Link management is module-wide, not per author
 */
function wp_xoops_ismu()
{
    return !empty($GLOBALS['xoopsModuleConfig']['enable_mu']);
    /*
    (defined("WP_XOOPS_MU") && constant("WP_XOOPS_MU"))
    */
}
