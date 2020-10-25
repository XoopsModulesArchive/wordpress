#
# Table Structure `wp_views`
#

CREATE TABLE wp_views (
    post_id    BIGINT(20) UNSIGNED NOT NULL DEFAULT '0',
    post_views BIGINT(20) UNSIGNED NOT NULL DEFAULT '0',
    KEY post_id (post_id)
);
