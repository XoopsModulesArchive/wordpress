<?php

$wpvarstoreset = ['action', 'standalone', 'option_group_id'];
for ($i = 0, $iMax = count($wpvarstoreset); $i < $iMax; $i += 1) {
    $wpvar = $wpvarstoreset[$i];

    if (!isset($$wpvar)) {
        if (empty($_POST[(string)$wpvar])) {
            if (empty($_GET[(string)$wpvar])) {
                $$wpvar = '';
            } else {
                $$wpvar = $_GET[(string)$wpvar];
            }
        } else {
            $$wpvar = $_POST[(string)$wpvar];
        }
    }
}
?>

    <br clear="all">

<?php if (isset($_GET['updated'])) : ?>
    <div id="message" class="updated fade"><p><strong><?php _e('Options saved.') ?></strong></p></div>
<?php endif; ?>
