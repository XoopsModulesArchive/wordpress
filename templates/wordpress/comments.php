<?php

if (function_exists('flickrrelated')) {
    @flickrrelated($id);
}
?>

<?php // Do not delete these lines
if ('comments.php' == basename($_SERVER['SCRIPT_FILENAME'])) {
    die ('Please do not load this page directly. Thanks!');
}

if (!empty($post->post_password)) { // if there's a password
if ($_COOKIE['wp-postpass_' . COOKIEHASH] != $post->post_password) {  // and it doesn't match the cookie
?>

    <p class="nocomments">This post is password protected. Enter the password to view comments.<p>

    <?php
    return;
    }
    }

    /* This variable is for alternating comment background */
    $oddcomment = 'alt';
    ?>

    <!-- You can start editing here. -->

    <?php if ($comments) : ?>
    <h3 id="comments"><?php comments_number(__('No Comments'), __('1 Comment'), __('% Comments')); ?></h3>

    <ol class="commentlist">

        <?php foreach ($comments as $comment) : ?>

            <li class="<?php echo $oddcomment; ?>" id="comment-<?php comment_ID() ?>">
                <?php if (!empty($GLOBALS['wp_xoops_author']) && $comment->user_id == $GLOBALS['wp_xoops_author']) { ?>
                <div style="padding: 2px 5px; border: 1px solid #aaa; background-color: #eee;">
                    <?php }
                    php ?>
                    <?php comment_type(__('Comment'), __('Trackback'), __('Pingback')); ?> <?php _e('by'); ?> <?php comment_author_link() ?>:
                    <?php if ($comment->comment_approved == '0') : ?>
                        <em>Your comment is awaiting moderation.</em>
                    <?php endif; ?>
                    <br>

                    <small class="commentmetadata"><a href="#comment-<?php comment_ID() ?>" title=""><?php comment_date(get_settings('date_format')) ?> @ <?php comment_time() ?></a> <?php edit_comment_link(__('Edit Comment'), ' |'); ?></small>

                    <?php comment_text() ?>
                    <?php if (!empty($GLOBALS['wp_xoops_author']) && $comment->user_id == $GLOBALS['wp_xoops_author']) { ?>
                </div>
            <?php } ?>

            </li>

            <?php /* Changes every other comment to a different class */
            if ('alt' == $oddcomment) {
                $oddcomment = '';
            } else {
                $oddcomment = 'alt';
            }
            ?>

        <?php endforeach; /* end for each comment */ ?>

    </ol>

<?php else : // this is displayed if there are no comments so far ?>

    <?php if ('open' == $post->comment_status) : ?>
        <!-- If comments are open, but there are no comments. -->

    <?php else : // comments are closed ?>
        <!-- If comments are closed. -->
        <p class="nocomments"><?php _e('Sorry, comments are closed for this item.'); ?></p>

    <?php endif; ?>
<?php endif; ?>


<?php if ('open' == $post->comment_status) : ?>

    <h3 id="respond"><?php _e('Leave a comment'); ?></h3>

    <?php if (get_option('comment_registration') && !$user_ID) : ?>
        <p><a href="<?php echo get_option('siteurl'); ?>/wp-login.php?redirect_to=<?php the_permalink(); ?>"><?php _e('Sorry, you must be logged in to post a comment.'); ?></a></p>
    <?php else : ?>

        <form action="<?php echo get_option('siteurl'); ?>/wp-comments-post.php" method="post" id="commentform">

            <?php if ($user_ID) : ?>

                <p>Logged in as <a href="<?php echo get_option('siteurl'); ?>/wp-admin/profile.php"><?php echo $user_identity; ?></a>. <a href="<?php echo get_option('siteurl'); ?>/wp-login.php?action=logout" title="Log out of this account">Logout &raquo;</a></p>

            <?php else : ?>

                <p><input type="text" name="author" id="author" value="<?php echo $comment_author; ?>" size="22" tabindex="1">
                    <label for="author"><small><?php _e('Name'); ?><?php if ($req) {
                                _e('(required)');
                            } ?></small></label></p>

                <p><input type="text" name="email" id="email" value="<?php echo $comment_author_email; ?>" size="22" tabindex="2">
                    <label for="email"><small><?php _e('Mail (will not be published)'); ?><?php if ($req) {
                                _e('(required)');
                            } ?></small></label></p>

                <p><input type="text" name="url" id="url" value="<?php echo $comment_author_url; ?>" size="22" tabindex="3">
                    <label for="url"><small><?php _e('Website'); ?></small></label></p>

            <?php endif; ?>

            <!--<p><small><strong>XHTML:</strong> You can use these tags: <?php echo allowed_tags(); ?></small></p>-->

            <p><textarea name="comment" id="comment" cols="50" rows="10" tabindex="4"></textarea></p>

            <p><input name="submit" type="submit" id="submit" tabindex="5" value="<?php _e('Say It!'); ?>">
                <input type="hidden" name="comment_post_ID" value="<?php echo $id; ?>">
                <input type="hidden" name="post_from_xoops" value="1">
            </p>
            <?php do_action('comment_form', $post->ID); ?>

        </form>

    <?php endif; // If registration required and not logged in ?>

<?php endif; // if you delete this the sky will fall on your head ?>
