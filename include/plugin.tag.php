<?php

/**
 * Tag info
 *
 * @copyright      The XOOPS project https://www.xoops.org/
 * @license        http://www.fsf.org/copyleft/gpl.html GNU public license
 * @author         Taiwen Jiang (phppp or D.J.) <php_pp@hotmail.com>
 * @since          1.00
 * @version        $Id$
 */
if (!defined('XOOPS_ROOT_PATH')) {
    exit();
}

/**
 * Get item fields:
 * title
 * content
 * time
 * link
 * uid
 * uname
 * tags
 *
 *
 *
 * @param mixed $items
 * @return    bool
 */
function wordpress_tag_iteminfo(&$items)
{
    if (empty($items) || !is_array($items)) {
        return false;
    }

    $items_id = [];

    foreach (array_keys($items) as $cat_id) {
        // Some handling here to build the link upon catid

        // catid is not used in article, so just skip it

        foreach (array_keys($items[$cat_id]) as $item_id) {
            // In wordpress, the item_id is "art_id"

            $items_id[] = (int)$item_id;
        }
    }

    global $wpdb, $wp_rewrite, $wp_queries, $table_prefix, $wp_db_version, $wp_roles;

    require_once XOOPS_ROOT_PATH . '/modules/wordpress/wp-config.php';

    $sql = '	SELECT ID, post_title, post_date, post_author ' . "	FROM {$wpdb->posts} " . "	WHERE post_status = 'publish' " . '		AND ID IN (' . implode(', ', $items_id) . ')';

    if (!$lposts = $wpdb->get_results($sql)) {
        return false;
    }

    $myts = MyTextSanitizer::getInstance();

    $posts = [];

    $time_diff = get_settings('time_difference') * 3600;

    foreach ($lposts as $lpost) {
        $post = [];

        $post['title'] = encoding_wp2xoops(htmlspecialchars($lpost->post_title, ENT_QUOTES | ENT_HTML5));

        $m = $lpost->post_date;

        $post['time'] = mktime(mb_substr($m, 11, 2), mb_substr($m, 14, 2), mb_substr($m, 17, 2), mb_substr($m, 5, 2), mb_substr($m, 8, 2), mb_substr($m, 0, 4)) - $time_diff;

        $post['link'] = '?p=' . $lpost->ID;

        $post['uid'] = $lpost->post_author;

        $posts[$lpost->ID] = $post;
    }

    $lposts = null;

    foreach (array_keys($items) as $cat_id) {
        foreach (array_keys($items[$cat_id]) as $item_id) {
            $items[$cat_id][$item_id] = $posts[$item_id];
        }
    }
}

/**
 * Remove orphan tag-item links
 *
 * @param mixed $mid
 * @return void
 */
function wordpress_tag_synchronization($mid)
{
    global $wpdb, $wp_queries, $table_prefix, $wp_db_version;

    require_once XOOPS_ROOT_PATH . '/modules/wordpress/wp-config.php';

    $linkHandler = xoops_getModuleHandler('link', 'tag');

    /* clear tag-item links */

    if ($linkHandler->mysql_major_version() >= 4):
        $sql = "	DELETE FROM {$linkHandler->table}"
               . '	WHERE '
               . "		tag_modid = {$mid}"
               . '		AND '
               . '		( tag_itemid NOT IN '
               . '			( SELECT DISTINCT ID '
               . "				FROM {$wpdb->posts} "
               . "				WHERE post_status = 'publish'"
               . '			) '
               . '		)'; else:
        $sql = "	DELETE {$linkHandler->table} FROM {$linkHandler->table}"
               . "	LEFT JOIN {$wpdb->posts} AS aa ON {$linkHandler->table}.tag_itemid = aa.ID "
               . '	WHERE '
               . "		tag_modid = {$mid}"
               . '		AND '
               . '		( aa.ID IS NULL'
               . "			OR aa.post_status <> 'publish'"
               . '		)';

    endif;

    if (!$result = $linkHandler->db->queryF($sql)) {
        //xoops_error($linkHandler->db->error());
    }
}
