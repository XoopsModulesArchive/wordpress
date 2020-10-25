<?php
get_header(); ?>
    <div id="main">
        <div id="content">
            <div class="post">
                <?php if ($posts) { ?>
                    <?php $post = $posts[0]; /* Hack. Set $post so that the_date() works. */ ?>
                    <?php if (is_day()) { ?>
                        <h2 class="post-title"><em><?php the_time('l, F jS, Y'); ?></em> &nbsp;</h2>
                        <p class="post-info">Daily Archive</p>
                    <?php } elseif (is_month()) { ?>
                        <h2 class="post-title"><em><?php the_time('F Y'); ?></em> &nbsp;</h2>
                        <p class="post-info">Monthly Archive</p>
                    <?php } elseif (is_year()) { ?>
                        <h2 class="post-title"><em><?php the_time('Y'); ?></em> &nbsp;</h2>
                        <p class="post-info">Yearly Archive</p>
                    <?php } ?>
                <?php } ?>
            </div>
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
                        <p class="post-info-co">
                            <span class="feedback"><?php comments_popup_link('No Responses &#187;', 'One Response &#187;', '% Responses &#187;'); ?></span>
                        </p>
                        <!--
			<?php trackback_rdf(); ?>
		-->
                        <div class="post-footer">&nbsp;</div>
                    </div>
                    <?php comments_template(); ?>
                </div>

            <?php endforeach; else: ?>
                <p><?php _e('Sorry, no posts matched your criteria.'); ?></p>
            <?php endif; ?>
            <p align="center"><?php posts_nav_link() ?></p>
        </div>
    </div>
<?php get_sidebar(); ?>
<?php get_footer(); ?>
