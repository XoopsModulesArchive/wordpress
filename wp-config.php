<?php

require_once dirname(__DIR__, 2) . '/mainfile.php';
require_once XOOPS_ROOT_PATH . '/modules/wordpress/include/xoops-wp.config.php';

// ** MySQL settings ** //
define('DB_NAME', 'wp');     // The name of the database
define('DB_USER', 'root');     // Your MySQL username
define('DB_PASSWORD', 'root'); // ...and password
define('DB_HOST', 'localhost');     // 99% chance you won't need to change this value

// Change the prefix if you want to have multiple blogs in a single database.
global $table_prefix;
$table_prefix = $GLOBALS['xoopsDB']->prefix('wp') . '_';

// Change this to localize WordPress.  A corresponding MO file for the
// chosen language must be installed to wp-includes/languages.
// For example, install de.mo to wp-includes/languages and set WPLANG to 'de'
// to enable German language support.
define('WPLANG', WP_LANG);

/* Stop editing */

define('ABSPATH', __DIR__ . '/');
require_once ABSPATH . 'wp-settings.php';
