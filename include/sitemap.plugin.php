<?php
// $Id: wordpress.php
// FILE		::	wordpress.php
// AUTHOR	::	Ryuji AMANO <info@ryus.biz>
// WEB		::	Ryu's Planning <http://ryus.biz>
//
// WordPress 1.5+
// D.J. http://xoopsforge.com/

function b_sitemap_wordpress()
{
    global $wpdb;

    require_once XOOPS_ROOT_PATH . '/modules/wordpress/wp-config.php';

    $xoopsDB = XoopsDatabaseFactory::getDatabaseConnection();

    $block = sitemap_get_categoires_map($xoopsDB->prefix('wp_categories'), 'cat_ID', 'category_parent', 'cat_name', 'index.php?cat=', 'cat_name');

    $block = encoding_wp2xoops_sitemap($block);

    return $block;
}

function encoding_wp2xoops_sitemap(&$block)
{
    if (is_array($block)) {
        foreach (array_keys($block) as $key) {
            encoding_wp2xoops_sitemap($block[$key]);
        }
    } else {
        $block = encoding_wp2xoops($block);
    }

    return $block;
}
