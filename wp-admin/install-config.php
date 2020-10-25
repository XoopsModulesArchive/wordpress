<?php

require_once dirname(__DIR__, 3) . '/mainfile.php';
if ($xoopsUser) {
    $loc = XOOPS_URL . '/modules/' . basename(dirname(__DIR__));
} else {
    $loc = XOOPS_URL . '/';
}
redirect_header($loc, 1, 'This function is not available in XOOPS Environment.');
