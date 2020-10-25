<?php

if (empty($wp)) {
    require_once __DIR__ . '/wp-config.php';

    wp('tb=1');
}

function trackback_response($error = 0, $error_message = '')
{
    header('Content-Type: text/xml; charset=' . get_option('blog_charset'));

    if ($error) {
        echo '<?xml version="1.0" encoding="utf-8"?' . ">\n";

        echo "<response>\n";

        echo "<error>1</error>\n";

        echo "<message>$error_message</message>\n";

        echo '</response>';

        die();
    }  

    echo '<?xml version="1.0" encoding="utf-8"?' . ">\n";

    echo "<response>\n";

    echo "<error>0</error>\n";

    echo '</response>';
}

// trackback is done by a POST
$request_array = 'HTTP_POST_VARS';

if (!$_GET['tb_id']) {
    $tb_id = explode('/', $_SERVER['REQUEST_URI']);

    $tb_id = (int)$tb_id[count($tb_id) - 1];
}

$tb_url = $_POST['url'];
$title = $_POST['title'];
$excerpt = $_POST['excerpt'];
$blog_name = $_POST['blog_name'];
$charset = $_POST['charset'];

if ($charset) {
    $charset = mb_strtoupper(trim($charset));
} else {
    $charset = 'ASCII, UTF-8, ISO-8859-1, JIS, EUC-JP, SJIS';
}

if (function_exists('encoding_rss2wp')) { // For international trackbacks
    $title = encoding_rss2wp($title, $_POST['charset']);

    $excerpt = encoding_rss2wp($excerpt, $_POST['charset']);

    $blog_name = encoding_rss2wp($blog_name, $_POST['charset']);
} elseif (function_exists('mb_convert_encoding')) { // For international trackbacks
    $title = mb_convert_encoding($title, get_settings('blog_charset'), $charset);

    $excerpt = mb_convert_encoding($excerpt, get_settings('blog_charset'), $charset);

    $blog_name = mb_convert_encoding($blog_name, get_settings('blog_charset'), $charset);
}

if (is_single() || is_page()) {
    $tb_id = $posts[0]->ID;
}

if (!(int)$tb_id) {
    trackback_response(1, 'I really need an ID for this to work.');
}

if (empty($title) && empty($tb_url) && empty($blog_name)) {
    // If it doesn't look like a trackback at all...

    wp_redirect(get_permalink($tb_id));

    exit;
}

if (!empty($tb_url) && !empty($title) && !empty($tb_url)) {
    header('Content-Type: text/xml; charset=' . get_option('blog_charset'));

    $pingstatus = $wpdb->get_var("SELECT ping_status FROM $wpdb->posts WHERE ID = $tb_id");

    if ('open' != $pingstatus) {
        trackback_response(1, 'Sorry, trackbacks are closed for this item.');
    }

    $title = wp_specialchars(strip_tags($title));

    $excerpt = strip_tags($excerpt);

    if (function_exists('mb_strcut')) { // For international trackbacks
        $excerpt = mb_strcut($excerpt, 0, 252, get_settings('blog_charset')) . '...';

        $title = mb_strcut($title, 0, 250, get_settings('blog_charset')) . '...';
    } else {
        $excerpt = (mb_strlen($excerpt) > 255) ? mb_substr($excerpt, 0, 252) . '...' : $excerpt;

        $title = (mb_strlen($title) > 250) ? mb_substr($title, 0, 250) . '...' : $title;
    }

    $comment_post_ID = $tb_id;

    $comment_author = $blog_name;

    $comment_author_email = '';

    $comment_author_url = $tb_url;

    $comment_content = "<strong>$title</strong>\n\n$excerpt";

    $comment_type = 'trackback';

    $dupe = $wpdb->get_results("SELECT * FROM $wpdb->comments WHERE comment_post_ID = '$comment_post_ID' AND comment_author_url = '$comment_author_url'");

    if ($dupe) {
        trackback_response(1, 'We already have a ping from that URI for this post.');
    }

    $commentdata = compact('comment_post_ID', 'comment_author', 'comment_author_email', 'comment_author_url', 'comment_content', 'comment_type');

    wp_new_comment($commentdata);

    do_action('trackback_post', $wpdb->insert_id);

    trackback_response(0);
}
