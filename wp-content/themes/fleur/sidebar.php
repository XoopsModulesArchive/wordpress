<div id="sidebar">
    <?php if (is_home()) { ?>
        <h2><?php _e('About the Site:'); ?></h2>
        <ul>
            <li><?php bloginfo('description'); ?></li>
        </ul>
    <?php } ?>

    <h2>Pages</h2>
    <ul><?php wp_list_pages('title_li='); ?></ul>
    <h2><?php _e('Categories:'); ?></h2>
    <ul><?php wp_list_cats('optioncount=1'); ?></ul>

    <h2><label for="s"><?php _e('Search:'); ?></label></h2>
    <ul>
        <li>
            <form id="searchform" method="get" action="<?php echo $_SERVER['PHP_SELF']; ?>" style="text-align:center">
                <p><input type="text" name="s" id="s" value="<?php echo wp_specialchars($s, 1); ?>" size="15"></p>
                <p><input type="submit" name="submit" value="<?php _e('Search'); ?>"></p>
            </form>
        </li>
    </ul>
    <h2><?php _e('Monthly:'); ?></h2>
    <ul><?php wp_get_archives('type=monthly&show_post_count=true'); ?></ul>

    <?php if (is_home()) { ?>
        <h2>Links</h2>
        <ul>
            <?php get_links_list('name'); ?>
        </ul>

        <h2>Feed on RSS</h2>
        <ul>
            <li>
                <a href="<?php bloginfo('rss2_url'); ?>">Posts</a> | <a href="<?php bloginfo('comments_rss2_url'); ?>">Comments</a>
            </li>
        </ul>
        <h2><?php _e('Meta'); ?></h2>
        <ul>
            <?php wp_register(); ?>
            <li><?php wp_loginout(); ?></li>
            <li><a href="http://validator.w3.org/check/referer" title="<?php _e('This page validates as XHTML 1.0 Transitional'); ?>"><?php _e('Valid <abbr title="eXtensible HyperText Markup Language">XHTML</abbr>'); ?></a></li>
            <li><a href="http://jigsaw.w3.org/css-validator/check/referer" title="valid css">Valid CSS</a></li>
            <li><a href="http://gmpg.org/xfn/"><abbr title="XHTML Friends Network">XFN</abbr></a></li>
            <li><a href="http://wordpress.org/" title="<?php _e('Powered by WordPress, state-of-the-art semantic personal publishing platform.'); ?>">WordPress</a></li>
            <?php wp_meta(); ?>
        </ul>
    <?php } ?>
</div>
