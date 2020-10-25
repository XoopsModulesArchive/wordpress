<?php
/**
 * mu handlers for XPress - WordPress for XOOPS
 *
 * Adding multi-author features to XPress
 *
 * @copyright      The XOOPS project https://www.xoops.org/
 * @license        http://www.fsf.org/copyleft/gpl.html GNU public license
 * @author         Taiwen Jiang (phppp or D.J.) <php_pp@hotmail.com>
 * @since          2.04
 * @version        $Id$
 */

// handler mu for output
function wp_mu_xoops_filter()
{
    add_filter('parse_query', 'mu_onaction_parse_query');

    $action_list = [
        //"post_link",
        'category_link',
        'feed_link',
        'year_link',
        'month_link',
        'day_link',
    ];

    foreach ($action_list as $action) {
        add_filter($action, 'mu_onaction_link_author');
    }
}

function mu_onaction_parse_query($wpquery)
{
    if ($wpquery->is_single || $wpquery->is_single) {
        unset($wpquery->query_vars['author_name'], $wpquery->query_vars['author'], $GLOBALS['wp_xoops_author']);
    }

    if (!empty($GLOBALS['wp_xoops_author'])) {
        return true;
    }

    if (!empty($wpquery->query_vars['author'])) {
        $GLOBALS['wp_xoops_author'] = (int)$wpquery->query_vars['author'];
    } elseif (!empty($wpquery->query_vars['author_name'])) {
        $GLOBALS['wp_xoops_author'] = (int)$wpquery->query_vars['author_name'];
    }
}

function mu_onaction_link_author($var = null)
{
    if (empty($GLOBALS['wp_xoops_author'])) {
        return $var;
    }

    return $var . (mb_strpos($var, '?') ? '&' : '?') . 'author=' . $GLOBALS['wp_xoops_author'];
}
