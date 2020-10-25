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

// for Magpie cache
define('MAGPIE_CACHE_ON', 1);
define('MAGPIE_CACHE_AGE', 60 * 60); // seconds
define('MAGPIE_CACHE_DIR', XOOPS_CACHE_PATH); // not used

define('WP_BLOG_DIRNAME', 'wordpress');
define('WP_BLOG_CHARSET', 1); // WordPress charset: 0 for XOOPS _CHARSET; other for WordPress original charset

// for WordPress cache, added in WP 2.0
//define('DISABLE_CACHE', true);
define('ENABLE_CACHE', true); // Added in 2.01
define('CACHE_EXPIRATION_TIME', 60 * 15); // same as XOOPS default session time
define('CACHE_PATH', XOOPS_CACHE_PATH . '/' . WP_BLOG_DIRNAME . '/');

define('UPLOADS', WP_BLOG_DIRNAME);

/**
 * Some of the module static configs
 * Just modifiy them upon your request
 */
// Show sidebar: 0 or false - disable inline sidebar; 1 or true - display inline sidebar
$GLOBALS['xoopsModuleConfig']['show_inline_sidebar'] = 1;

// If show_inline_sidebar set to false, all following configs will be disabled

// Days for counting author's post count in sidebar, 0 for all the time
$GLOBALS['xoopsModuleConfig']['days_author'] = 0;

// Show post count in archive list of sidebar
$GLOBALS['xoopsModuleConfig']['showcount_archive'] = 1;

// Archive limit
$GLOBALS['xoopsModuleConfig']['limit_archive'] = 12;

// Show post count in category list of sidebar
$GLOBALS['xoopsModuleConfig']['showcount_category'] = 1;

// enable Xoops-wide Tag
$GLOBALS['xoopsModuleConfig']['do_tag'] = 1;

/**
 * Enable multi-user mode
 *
 * The parameter was designed to control with a module preference variable "enable_mu",
 * however I think it better to control with a hardcode parameter, like the "WP_BLOG_CHARSET"
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
// User $xoopsConfig["enable_mu"]
//define("WP_XOOPS_MU", 1);

$GLOBALS['xoopsModuleConfig']['num_posts_index'] = 5;
$GLOBALS['xoopsModuleConfig']['num_authors_index'] = 10;

// due to an improper treatement in common.php for $_SERVER['REQUEST_URI'], we have to make the change
$_SERVER['REQUEST_URI'] = preg_replace_callback(
    '/(' . preg_quote('/modules/' . WP_BLOG_DIRNAME . '/', '/') . ')(.*)index.php$/',
    create_function(
        '$matches',
        'return $matches[1].(empty($matches[2])?"index.php":$matches[2]);'
    ),
    $_SERVER['REQUEST_URI']
);

require_once XOOPS_ROOT_PATH . '/Frameworks/art/functions.ini.php';
