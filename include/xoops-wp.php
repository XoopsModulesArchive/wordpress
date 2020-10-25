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

// One of the most fantastic tricky solutions that make XPress work painlessly, awarded 20 cents!
if (defined('ERROR_REPORTING_WORDPRESS')) {
    error_reporting(ERROR_REPORTING_WORDPRESS);
}

require_once XOOPS_ROOT_PATH . '/modules/wordpress/include/xoops-wp.config.php';

require_once XOOPS_ROOT_PATH . '/modules/wordpress/include/functions.ini.php';
wp_xoops_filter();

//if(defined("WP_XOOPS_MU") && WP_XOOPS_MU){
if (wp_xoops_ismu()) {
    $GLOBALS['wp_xoops_author'] = (int)@$_REQUEST['author'];

    require_once XOOPS_ROOT_PATH . '/modules/wordpress/include/functions.mu.php';

    wp_mu_xoops_filter();
} else {
    $GLOBALS['wp_xoops_author'] = null;
}
