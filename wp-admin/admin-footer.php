<div id="footer"><p><a href="http://wordpress.org/" id="wordpress-logo"><img src="images/wordpress-logo.png" alt="WordPress"></a></p>
    <p>
        <a href="http://codex.wordpress.org/" target="_blank"><?php _e('Documentation'); ?></a> &#8212; <a href="http://xoopsforge.com/modules/wordpress/" target="_blank"><?php _e('Support Forums'); ?></a> <br>
        <?php bloginfo('version'); ?> &#8212; <?php printf(__('%s seconds'), timer_stop(0, 2)); ?>
    </p>

</div>
<?php do_action('admin_footer', ''); ?>
<script type="text/javascript">if (typeof wpOnload == 'function') wpOnload();</script>

<?php
if (('cgi' == mb_substr(php_sapi_name(), 0, 3)) && spawn_pinger()) {
    echo '<iframe id="pingcheck" src="' . get_settings('siteurl') . '/wp-admin/execute-pings.php?time=' . time() . '" style="border:none;width:1px;height:1px;"></iframe>';
}
?>

</body>
</html>
