<?php
/**
 * XPress - WordPress for XOOPS
 *
 * Adding multi-author features to XPress
 *
 * @copyright      The XOOPS project https://www.xoops.org/
 * @license        http://www.fsf.org/copyleft/gpl.html GNU public license
 * @author         Taiwen Jiang (phppp or D.J.) <php_pp@hotmail.com>
 * @since          2.04
 * @version        $Id$
 */
$xoops_module_header = '
	<link rel="stylesheet" type="text/css" href="' . XOOPS_URL . '/modules/' . $xoopsModule->getVar('dirname') . '/templates/style.css">
	<link rel="alternate" type="application/rss+xml" title="' . $xoopsModule->getVar('name') . ' entries" href="' . XOOPS_URL . '/modules/' . $xoopsModule->getVar('dirname') . '/feed/">
	<link rel="alternate" type="application/rss+xml" title="' . $xoopsModule->getVar('name') . ' comments" href="' . XOOPS_URL . '/modules/' . $xoopsModule->getVar('dirname') . '/feed/">
	';
$GLOBALS['xoopsOption']['xoops_module_header'] = $xoops_module_header;
$GLOBALS['xoopsOption']['xoops_pagetitle'] = $GLOBALS['xoopsModule']->getVar('name') . ' ' . encoding_wp2xoops(wp_title('&raquo;', false));
require XOOPS_ROOT_PATH . '/header.php';
$xoopsTpl->assign('xoops_module_header', $xoops_module_header);
$xoopsTpl->assign('xoops_pagetitle', $GLOBALS['xoopsOption']['xoops_pagetitle']);
$GLOBALS['wp_xoops_content'] = [];
