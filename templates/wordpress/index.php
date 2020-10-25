<?php

$GLOBALS['xoopsOption']['template_main'] = 'wp_index.html';
include __DIR__ . '/header.php';

$GLOBALS['wp_xoops_content']['posts'] = [];

if (have_posts()) :
    $post = $posts[0];
    if (wp_xoops_ismu() && empty($GLOBALS['wp_xoops_author']) && (is_single() || is_page())) {
        $GLOBALS['wp_xoops_author'] = $post->post_author;
    }
    while (have_posts()) : the_post();
        $wp_xoops_post['id'] = $GLOBALS['id'];
        $wp_xoops_post['link'] = get_permalink();
        $wp_xoops_post['title'] = the_title('', '', false);
        $wp_xoops_post['time'] = get_the_time(get_settings('date_format'));
        $wp_xoops_post['views'] = get_the_views();

        ob_start();
        the_author_posts_link();
        $wp_xoops_post['author'] = ob_get_contents();
        ob_end_clean();

        ob_start();
        the_content(__('(more...)'));
        $wp_xoops_post['content'] = ob_get_contents();
        ob_end_clean();

        ob_start();
        the_category(', ');
        $wp_xoops_post['category'] = ob_get_contents();
        ob_end_clean();

        ob_start();
        edit_post_link(__('Edit This'), '', '');
        $wp_xoops_post['edit'] = ob_get_contents();
        ob_end_clean();

        ob_start();
        previous_post_link('&laquo; %link');
        $wp_xoops_post['previous'] = ob_get_contents();
        ob_end_clean();

        ob_start();
        next_post_link('%link &raquo;');
        $wp_xoops_post['next'] = ob_get_contents();
        ob_end_clean();

        ob_start();
        wp_link_pages();
        $wp_xoops_post['page'] = ob_get_contents();
        ob_end_clean();

        ob_start();
        comments_popup_link(__('Comments (0)'), __('Comments (1)'), __('Comments (%)'));
        $wp_xoops_post['comment'] = ob_get_contents();
        ob_end_clean();

        ob_start();
        comments_template();
        $wp_xoops_post['comments'] = ob_get_contents();
        ob_end_clean();

        $GLOBALS['wp_xoops_content']['posts'][] = $wp_xoops_post;
    endwhile;

    $GLOBALS['wp_xoops_content']['lang_under'] = __('Filed under:');

    ob_start();
    posts_nav_link(' &#8212; ', __('&laquo; Previous Page'), __('Next Page &raquo;'));
    $GLOBALS['wp_xoops_content']['posts_nav'] = ob_get_contents();
    ob_end_clean();

endif;

include __DIR__ . '/footer.php';
