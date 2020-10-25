<?php

require_once __DIR__ . '/admin.php';
$title = __('New Page');
$parent_file = 'post.php';
$editing = true;
require_once __DIR__ . '/admin-header.php';
?>

<?php if (isset($_GET['saved'])) : ?>
    <div id="message" class="updated fade"><p><strong><?php _e('Page saved.') ?></strong> <a href="edit-pages.php"><?php _e('Manage pages'); ?></a> | <a href="<?php echo get_page_link($_GET['saved']); ?>"><?php _e('View page'); ?> &raquo;</a></p></div>
<?php endif; ?>

<?php
if (current_user_can('edit_pages')) {
    $action = 'post';

    $post = get_default_post_to_edit();

    $post->post_status = 'static';

    require __DIR__ . '/edit-page-form.php';
}
?>

<?php require __DIR__ . '/admin-footer.php'; ?>
