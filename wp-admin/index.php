<?php

require_once __DIR__ . '/admin.php';
$title = __('Dashboard');
require_once __DIR__ . '/admin-header.php';
require_once ABSPATH . WPINC . '/rss-functions.php';

$today = current_time('mysql', 1);
?>

    <div class="wrap">

        <h2><?php _e('Dashboard'); ?></h2>

        <div id="zeitgeist">
            <h2><?php _e('Latest Activity'); ?></h2>

            <?php
            /*
            $rss = @fetch_rss('http://feeds.technorati.com/cosmos/rss/?url='. trailingslashit(get_option('home')) .'&partner=wordpress');
            if ( isset($rss->items) && 0 != count($rss->items) ) {
            ?>
            <div id="incominglinks">
            <h3><?php _e('Incoming Links'); ?> <cite><a href="http://www.technorati.com/search/<?php echo trailingslashit(get_option('home')); ?>?partner=wordpress"><?php _e('More'); ?> &raquo;</a></cite></h3>
            <ul>
            <?php
            $rss->items = array_slice($rss->items, 0, 10);
            foreach ($rss->items as $item ) {
            ?>
                <li><a href="<?php echo wp_filter_kses($item['link']); ?>"><?php echo wp_specialchars(encoding_rss2wp($item['title'])); ?></a></li>
            <?php } ?>
            </ul>
            </div>
            <?php }
            */

            ?>

            <?php
            $rss = @fetch_rss('http://feeds.technorati.com/feed/posts/tag/xoops');
            if (isset($rss->items) && 0 != count($rss->items)) {
                ?>
                <div id="incominglinks">
                    <h3><?php _e('Incoming Links'); ?> <cite><a href="http://www.technorati.com/search/xoops" target="_blank"><?php _e('More'); ?> &raquo;</a></cite></h3>
                    <ul>
                        <?php
                        $rss->items = array_slice($rss->items, 0, 10);

                foreach ($rss->items as $item) {
                    ?>
                            <li><a href="<?php echo wp_filter_kses($item['link']); ?>"><?php echo wp_specialchars(encoding_rss2wp($item['title'])); ?></a></li>
                        <?php
                } ?>
                    </ul>
                </div>
            <?php
            } ?>

            <?php
            $comments = $wpdb->get_results("SELECT comment_author, comment_author_url, comment_ID, comment_post_ID FROM $wpdb->comments WHERE comment_approved = '1' ORDER BY comment_date_gmt DESC LIMIT 5");
            $numcomments = $wpdb->get_var("SELECT COUNT(*) FROM $wpdb->comments WHERE comment_approved = '0'");

            if ($comments || $numcomments) :
                ?>
                <div>
                    <h3><?php _e('Comments'); ?> <a href="edit-comments.php" title="<?php _e('More comments...'); ?>">&raquo;</a></h3>

                    <?php if ($numcomments) : ?>
                        <p><strong><a href="moderation.php"><?php echo sprintf(__('Comments in moderation (%s)'), number_format($numcomments)); ?> &raquo;</a></strong></p>
                    <?php endif; ?>

                    <ul>
                        <?php
                        if ($comments) {
                            foreach ($comments as $comment) {
                                echo '<li>' . sprintf(__('%1$s on %2$s'), get_comment_author_link(), '<a href="' . get_permalink($comment->comment_post_ID) . '#comment-' . $comment->comment_ID . '">' . get_the_title($comment->comment_post_ID) . '</a>');

                                edit_comment_link(__('Edit'), ' <small>(', ')</small>');

                                echo '</li>';
                            }
                        }
                        ?>
                    </ul>
                </div>
            <?php endif; ?>


            <?php
            if ($recentposts = $wpdb->get_results("SELECT ID, post_title FROM $wpdb->posts WHERE post_status = 'publish' AND post_date_gmt < '$today' ORDER BY post_date DESC LIMIT 5")) :
                ?>
                <div>
                    <h3><?php _e('Posts'); ?> <a href="edit.php" title="<?php _e('More posts...'); ?>">&raquo;</a></h3>
                    <ul>
                        <?php
                        foreach ($recentposts as $post) {
                            if ('' == $post->post_title) {
                                $post->post_title = sprintf(__('Post #%s'), $post->ID);
                            }

                            echo "<li><a href='post.php?action=edit&amp;post=$post->ID'>";

                            the_title();

                            echo '</a></li>';
                        }
                        ?>
                    </ul>
                </div>
            <?php endif; ?>

            <?php
            if ($scheduled = $wpdb->get_results("SELECT ID, post_title, post_date_gmt FROM $wpdb->posts WHERE post_status = 'publish' AND post_date_gmt > '$today' ORDER BY post_date ASC")) :
                ?>
                <div>
                    <h3><?php _e('Scheduled Entries:') ?></h3>
                    <ul>
                        <?php
                        foreach ($scheduled as $post) {
                            if ('' == $post->post_title) {
                                $post->post_title = sprintf(__('Post #%s'), $post->ID);
                            }

                            echo '<li>' . sprintf(__('%1$s in %2$s'), "<a href='post.php?action=edit&amp;post=$post->ID' title='" . __('Edit this post') . "'>$post->post_title</a>", human_time_diff(current_time('timestamp', 1), strtotime($post->post_date_gmt . ' GMT'))) . '</li>';
                        }
                        ?>
                    </ul>
                </div>
            <?php endif; ?>

            <div>
                <h3><?php _e('Blog Stats'); ?></h3>
                <?php
                $numposts = $wpdb->get_var("SELECT COUNT(*) FROM $wpdb->posts WHERE post_status = 'publish'");
                if ($numposts > 0) {
                    $numposts = number_format($numposts);
                }

                $numcomms = $wpdb->get_var("SELECT COUNT(*) FROM $wpdb->comments WHERE comment_approved = '1'");
                if ($numcomms > 0) {
                    $numcomms = number_format($numcomms);
                }

                $numcats = $wpdb->get_var("SELECT COUNT(*) FROM $wpdb->categories");
                if ($numcats > 0) {
                    $numcats = number_format($numcats);
                }
                ?>
                <p><?php printf(
                    __('There are currently %1$s <a href="%2$s" title="Posts">posts</a> and %3$s <a href="%4$s" title="Comments">comments</a>, contained within %5$s <a href="%6$s" title="categories">categories</a>.'),
                    $numposts,
                    'edit.php',
                    $numcomms,
                    'edit-comments.php',
                    $numcats,
                    'categories.php'
                ); ?></p>
            </div>

            <?php do_action('activity_box_end'); ?>
        </div>

        <h3><?php _e('Welcome to WordPress'); ?></h3>

        <p><?php _e('Use these links to get started:'); ?></p>

        <ul>
            <li><a href="post.php"><?php _e('Write a post'); ?></a></li>
            <li><a href="profile.php"><?php _e('Update your profile or change your password'); ?></a></li>
            <li><a href="link-add.php"><?php _e('Add a link to your blogroll'); ?></a></li>
            <li><a href="themes.php"><?php _e('Change your site&#8217;s look or theme'); ?></a></li>
        </ul>

        <p><?php _e(
                        "Below is the latest news from the official WordPress development blog, click on a title to read the full entry. If you need help with WordPress please see our <a href='http://codex.wordpress.org/' target='_blank'>great documentation</a> or if that doesn't help visit the <a href='http://wordpress.org/support/' target='_blank'>support forums</a>."
                    ); ?></p>
        <?php
        $rss1 = @fetch_rss('http://wordpress.org/development/feed/');
        $rss2 = @fetch_rss('http://xoops.org/backend.php');
        $rss3 = @fetch_rss('http://xoopsforge.com/modules/wordpress/wp-rss2.php');
        $rss_items = [];
        if (is_object($rss1)) {
            $rss_items = array_merge($rss_items, array_slice($rss1->items, 0, 2));
        }
        if (is_object($rss2)) {
            $rss_items = array_merge($rss_items, array_slice($rss2->items, 0, 2));
        }
        if (is_object($rss3)) {
            $rss_items = array_merge($rss_items, array_slice($rss3->items, 0, 2));
        }
        ?>
        <?php
        if (isset($rss_items) || 0 != count($rss_items)) {
            ?>
            <h3><?php _e('WordPress Development Blog'); ?></h3>
            <?php
            foreach ($rss_items as $item) {
                ?>
                <h4><a href='<?php echo wp_filter_kses($item['link']); ?>'><?php echo wp_specialchars(encoding_rss2wp($item['title'])); ?></a> &#8212; <?php echo sprintf(__('%s ago'), human_time_diff(strtotime($item['pubdate'], time()))); ?></h4>
                <p><?php echo encoding_rss2wp($item['description']); ?></p>
                <?php
            }
        }
        ?>


        <?php
        unset($rss);
        $rss = @fetch_rss('http://xoopsforge.com/modules/planet/xml.php/rss/');
        $rss_items = [];
        if (is_object($rss)) {
            $rss_items = array_slice($rss->items, 0, 10);
        }
        if (isset($rss_items) && 0 != count($rss_items)) {
            //if ( $count>0 ) {
            ?>
            <div id="planetnews">
                <h3><?php _e('Other WordPress News'); ?> <a href="http://xoopsforge.com/modules/planet/" target='_blank'><?php _e('more'); ?> &raquo;</a></h3>
                <ul>
                    <?php
                    //$rss->items = array_slice($rss->items, 0, 20);
                    foreach ($rss_items as $item) {
                        ?>
                        <li><a href='<?php echo wp_filter_kses($item['link']); ?>'><?php echo wp_specialchars(encoding_rss2wp($item['title'])); ?></a></li>
                        <?php
                    } ?>
                </ul>
            </div>
            <?php
        }
        ?>
        <div style="clear: both">&nbsp;
            <br clear="all">
        </div>
    </div>

<?php
require __DIR__ . '/admin-footer.php';
?>
