<?php
/**
 * XPress - WordPress for XOOPS
 *
 * @copyright      The XOOPS project https://www.xoops.org/
 * @license        http://www.fsf.org/copyleft/gpl.html GNU public license
 * @author         Taiwen Jiang (phppp or D.J.) <php_pp@hotmail.com>
 * @since          2.04
 * @version        $Id$
 * @param mixed $text
 * @return array
 * @return array
 */
function wp_parse_tags($text)
{
    $tag_pattern = '/(<tag>(.*?)<\/tag>)/i';

    $tags_pattern = '/(<tags>(.*?)<\/tags>)/i';

    $tags = [];

    # Check for in-post <tag> </tag>

    if (preg_match_all($tag_pattern, $text, $matches)) {
        for ($m = 0, $mMax = count($matches[0]); $m < $mMax; $m++) {
            $ttags = array_filter(array_map('trim', explode(',', $matches[2][$m])));

            for ($i = 0, $iMax = count($ttags); $i < $iMax; $i++) {
                if (in_array($ttags[$i], $tags, true)) {
                    continue;
                }

                $tags[] = $ttags[$i];
            }

            $text = str_replace($matches[0][$m], $matches[2][$m], $text);
        }
    }

    # Check for <tags> </tags>

    if (preg_match($tags_pattern, $text, $matches)) {
        $ttags = array_filter(array_map('trim', explode(',', $matches[2])));

        for ($i = 0, $iMax = count($ttags); $i < $iMax; $i++) {
            if (in_array($ttags[$i], $tags, true)) {
                continue;
            }

            $tags[] = $ttags[$i];
        }

        // specified flickr items

        if (preg_match("/^flickr:[\s]*([0-9]{1,2})$/i", $tags[count($tags) - 1], $mt)) {
            array_pop($tags);
        }

        // hide techno tags

        if (preg_match('/^notag$/i', $tags[count($tags) - 1])) {
            array_pop($tags);
        }
    }

    $tags = array_map('strip_tags', $tags);

    return $tags;
}

function onaction_set_tags($post_id = 0)
{
    if (!@require_once XOOPS_ROOT_PATH . '/modules/tag/include/functions.php') {
        return false;
    }

    if (!$tagHandler = &tag_getTagHandler()) {
        return false;
    }

    global $wpdb, $table_prefix, $post;

    if (empty($post_id) && is_object($post)) {
        $post_id = $post->ID;
    } elseif (!empty($post_id)) {
        $post = get_post($post_id);
    } else {
        return false;
    }

    $tags = wp_parse_tags($post->post_content);

    require_once __DIR__ . '/functions.ini.php';

    $tags = array_map('encoding_wp2xoops', $tags);

    return $tagHandler->updateByItem($tags, $post_id, 'wordpress');
}

function onaction_get_tags($text)
{
    return $text;
}
