<?php
get_header() ?>
<div id="main">
    <div id="content">
        <?php if ($posts) : foreach ($posts as $post) : start_wp(); ?>
            <div class="post">
                <h2 class="post-title">
                    <em><a href="<?php the_permalink() ?>" rel="bookmark" title="Permanent Link: <?php the_title(); ?>"><?php the_title(); ?></a></em>
                    <?php the_time('l, M j Y'); ?>&nbsp;</h2>
                <p class="post-info">
                    <span class="pcat"><?php the_category(' and ') ?></span>
                    <span class="pauthor"><?php the_author() ?></span>
                    <span class="ptime"><?php the_time(); ?></span><?php edit_post_link(); ?>
                </p>
                <div class="post-content">
                    <?php the_content(); ?>
                    <!--
			<?php trackback_rdf(); ?>
		-->
                    <div class="post-footer">&nbsp;</div>
                </div>
                <p class="post-info"><a href="<?php trackback_url(display); ?> ">Trackback URI</a></a>
                    <?php comments_template(); ?>
            </div>
        <?php endforeach; else: ?>
            <p><?php _e('Sorry, no posts matched your criteria.'); ?></p>
        <?php endif; ?>
    </div>
</div>
<?php get_sidebar(); ?>
<?php get_footer(); ?>
