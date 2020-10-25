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
$GLOBALS['wp_xoops_content']['home_url'] = get_settings('home');
$GLOBALS['wp_xoops_content']['home_name'] = get_bloginfo('name');
$GLOBALS['wp_xoops_content']['home_description'] = get_bloginfo('description');
$GLOBALS['wp_xoops_content']['rss2_posts'] = get_bloginfo('rss2_url');
$GLOBALS['wp_xoops_content']['rss2_comments'] = get_bloginfo('comments_rss2_url');

//ob_start();
//include (TEMPLATEPATH . '/searchform.php');
//$GLOBALS["wp_xoops_content"]["searchform"] = ob_get_contents();
//ob_end_clean();

if (!empty($GLOBALS['xoopsModuleConfig']['show_inline_sidebar'])):

    $title = '';
    if (is_category()) {
        $title = single_cat_title('', false);
    } elseif (is_day()) {
        $title = get_the_time('l, F jS, Y');
    } elseif (is_month()) {
        $title = get_the_time('F, Y');
    } elseif (is_year()) {
        $title = get_the_time('Y');
    } elseif (is_search()) {
        $title = wp_specialchars($s);
    }
    $GLOBALS['wp_xoops_content']['title'] = $title;
    $GLOBALS['wp_xoops_content']['page'] = wp_list_pages('title_li=&echo=0');

    ob_start();
    wp_get_archives('type=monthly&show_post_count=' . (!empty($GLOBALS['xoopsModuleConfig']['showcount_archive'])) . '&limit=' . $GLOBALS['xoopsModuleConfig']['limit_archive']);
    $GLOBALS['wp_xoops_content']['archive'] = ob_get_contents();
    ob_end_clean();

    ob_start();
    wp_list_cats('sort_column=name&hierarchical=1&optioncount=' . (!empty($GLOBALS['xoopsModuleConfig']['showcount_category'])));
    $GLOBALS['wp_xoops_content']['category'] = ob_get_contents();
    ob_end_clean();

    ob_start();
    get_links_list();
    $GLOBALS['wp_xoops_content']['link'] = ob_get_contents();
    ob_end_clean();

    $limit = empty($GLOBALS['wp_xoops_author']) ? $GLOBALS['xoopsModuleConfig']['num_posts_index'] : $GLOBALS['xoopsModuleConfig']['num_posts'];
    // Get recent posts and comments
    $request = "	SELECT {$wpdb->posts}.ID, {$wpdb->posts}.post_title, {$wpdb->posts}.post_date, {$wpdb->posts}.post_author "
               . "	FROM {$wpdb->posts} "
               . "	WHERE post_status = 'publish' "
               . (!empty($GLOBALS['wp_xoops_author']) ? '	AND	post_author = ' . $GLOBALS['wp_xoops_author'] : '')
               . "	ORDER BY {$wpdb->posts}.post_date DESC"
               . '	LIMIT '
               . $limit;

    $posts_mu = [];

    if ($lposts = $wpdb->get_results($request)) {
        foreach ($lposts as $post) {
            $post_mu = [];

            $post_mu['title'] = the_title('', '', false);

            $post_mu['time'] = get_the_time();

            $post_mu['link'] = get_permalink($post->ID);

            $post_mu['author'] = $post->post_author;

            $posts_mu[] = $post_mu;
        }
    }

    $request = "	SELECT {$wpdb->posts}.ID, {$wpdb->comments}.comment_ID, {$wpdb->comments}.comment_content, {$wpdb->comments}.comment_author, {$wpdb->comments}.comment_date, {$wpdb->comments}.comment_type "
               . "	FROM {$wpdb->posts}, {$wpdb->comments} "
               . ' 	WHERE '
               . "		{$wpdb->posts}.ID = {$wpdb->comments}.comment_post_ID AND {$wpdb->posts}.post_status = 'publish' "
               . (!empty($GLOBALS['wp_xoops_author']) ? "	AND	{$wpdb->posts}.post_author = " . $GLOBALS['wp_xoops_author'] : '')
               . "	AND	{$wpdb->comments}.comment_approved = '1'"
               . "	ORDER BY {$wpdb->comments}.comment_date DESC"
               . '	LIMIT '
               . $GLOBALS['xoopsModuleConfig']['num_comments'];

    $comments_mu = [];
    if ($lcomments = $wpdb->get_results($request)) {
        foreach ($lcomments as $comment) {
            $comm_mu = [];

            ob_start();

            comment_type(__('Comment'), __('Trackback'), __('Pingback'));

            $comm_mu['type'] = ob_get_contents();

            ob_end_clean();

            $comm_mu['author'] = get_comment_author_link();

            $comment_content = strip_tags($comment->comment_content);

            if (!empty($GLOBALS['xoopsModuleConfig']['length_comment'])) {
                $comment_content = xoops_substr($comment_content, 0, $GLOBALS['xoopsModuleConfig']['length_comment']);
            }

            $comm_mu['content'] = $comment_content;

            $comm_mu['link'] = get_permalink($comment->ID) . '#comment-' . $comment->comment_ID;

            $comm_mu['time'] = get_comment_date();

            $comments_mu[] = $comm_mu;
        }
    }
    $GLOBALS['wp_xoops_content']['recent']['posts'] = $posts_mu;
    $GLOBALS['wp_xoops_content']['recent']['comments'] = $comments_mu;

    // Get the current author info
    if (!empty($GLOBALS['wp_xoops_author'])) {
        $GLOBALS['wp_xoops_content']['author_id'] = $GLOBALS['wp_xoops_author'];

        $GLOBALS['wp_xoops_content']['author_name'] = the_author('', false);

        $GLOBALS['wp_xoops_content']['author_description'] = get_the_author_description();

        $user_obj = &$GLOBALS['memberHandler']->getUser($GLOBALS['wp_xoops_author']);

        $image = $user_obj->getVar('user_avatar');

        if (!empty($image) && 'blank.gif' != $image) {
            $image = XOOPS_URL . '/uploads/' . $image;
        } else {
            $image = '';
        }

        $GLOBALS['wp_xoops_content']['author_image'] = $image;
    }
endif;

$data = encoding_wp2xoops($GLOBALS['wp_xoops_content']);

if (!empty($GLOBALS['xoopsModuleConfig']['show_inline_sidebar'])):
    $data['show_inline_sidebar'] = 1;
    $data['title'] = empty($data['title']) ? '' : sprintf(_MD_WORDPRESS_NAV_TITLE, $data['title']);

    // Get a list of authors order by posts in the past time (30 days?)
    $period_for_stats = $GLOBALS['xoopsModuleConfig']['days_author'] * 24 * 3600;
    $time_start = empty($period_for_stats) ? '' : gmdate('Y-m-d H:i:s', (time() - $period_for_stats));
    if (empty($GLOBALS['wp_xoops_author']) && !empty($GLOBALS['xoopsModuleConfig']['num_authors'])) {
        $request = '	SELECT COUNT(ID) AS pcount, post_author' . "	FROM $wpdb->posts" . "	WHERE post_status = 'publish'" . "		AND post_date > '" . $time_start . "'" . '	GROUP BY post_author ORDER BY pcount DESC' . '	LIMIT ' . $GLOBALS['xoopsModuleConfig']['num_authors'];

        $authors_mu = [];

        if ($lauthors = $wpdb->get_results($request)) {
            foreach ($lauthors as $author) {
                $authors_mu[$author->post_author] = $author->pcount;
            }
        }

        //require_once XOOPS_ROOT_PATH."/Frameworks/art/functions.php";

        load_functions('user');

        $users = mod_getUnameFromIds(array_keys($authors_mu));

        foreach ($authors_mu as $uid => $count) {
            $data['recent']['authors'][] = [
                'link' => get_author_link(0, $uid, $uid),
                'name' => $users[$uid],
                'count' => (int)$count,
            ];
        }
    }
endif;

//xoops_message($data);
$xoopsTpl->assign_by_ref('wp_content', $data);
require XOOPS_ROOT_PATH . '/footer.php';
