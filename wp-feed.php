<?php

if (empty($doing_rss)) {
    $doing_rss = 1;

    require __DIR__ . '/wp-blog-header.php';
}

// Remove the pad, if present.
$feed = ltrim($feed, '_');

if ('' == $feed || 'feed' == $feed) {
    $feed = 'rss2';
}

if (is_single() || (1 == $withcomments)) {
    require ABSPATH . 'wp-commentsrss2.php';
} else {
    switch ($feed) {
        case 'atom':
            require ABSPATH . 'wp-atom.php';
            break;
        case 'rdf':
            require ABSPATH . 'wp-rdf.php';
            break;
        case 'rss':
            require ABSPATH . 'wp-rss.php';
            break;
        case 'rss2':
            require ABSPATH . 'wp-rss2.php';
            break;
        case 'comments-rss2':
            require ABSPATH . 'wp-commentsrss2.php';
            break;
    }
}
