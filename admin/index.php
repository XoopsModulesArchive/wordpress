<?php
// $Id: xoops_version.php,v 1.8 2005/06/03 01:35:02 phppp Exp $
//  ------------------------------------------------------------------------ //
//                XOOPS - PHP Content Management System                      //
//                    Copyright (c) 2000 XOOPS.org                           //
//                       <https://www.xoops.org>                             //
//  ------------------------------------------------------------------------ //
//  This program is free software; you can redistribute it and/or modify     //
//  it under the terms of the GNU General Public License as published by     //
//  the Free Software Foundation; either version 2 of the License, or        //
//  (at your option) any later version.                                      //
//                                                                           //
//  You may not change or alter any portion of this comment or credits       //
//  of supporting developers from this source code or any supporting         //
//  source code which is considered copyrighted (c) material of the          //
//  original comment or credit authors.                                      //
//                                                                           //
//  This program is distributed in the hope that it will be useful,          //
//  but WITHOUT ANY WARRANTY; without even the implied warranty of           //
//  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the            //
//  GNU General Public License for more details.                             //
//                                                                           //
//  You should have received a copy of the GNU General Public License        //
//  along with this program; if not, write to the Free Software              //
//  Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307 USA //
//  ------------------------------------------------------------------------ //
// Author: phppp (D.J.)                                                      //
// URL: http://xoopsforge.com, http://xoops.org.cn                           //
// ------------------------------------------------------------------------- //
require __DIR__ . '/admin_header.php';
xoops_cp_header();
loadModuleAdminMenu(0);

echo '
	<style type="text/css">
	label,text {
		display: block;
		float: left;
		margin-bottom: 2px;
	}
	label {
		text-align: right;
		width: 150px;
		padding-right: 20px;
	}
	br {
		clear: left;
	}
	</style>
';

echo "<fieldset><legend style='font-weight: bold; color: #900;'>" . _AM_WORDPRESS_CONFIG . '</legend>';
echo "<div style='padding: 8px;'>";
echo '<label>' . '<strong>PHP Version:</strong>' . ':</label><text>' . phpversion() . '</text><br>';
echo '<label>' . '<strong>MySQL Version:</strong>' . ':</label><text>' . $GLOBALS['xoopsDB']->getServerVersion() . '</text><br>';
echo '<label>' . '<strong>XOOPS Version:</strong>' . ':</label><text>' . XOOPS_VERSION . '</text><br>';
echo '<label>' . '<strong>Wordpress Version:</strong>' . ':</label><text>' . $xoopsModule->getInfo('version') . '</text><br>';
echo '</div>';
echo "<div style='padding: 8px;'>";
echo '<label>safemode:</label><text>';
echo (ini_get('safe_mode')) ? 'ON' : 'OFF';
echo '</text><br>';
echo '<label>register_globals:</label><text>';
echo (ini_get('register_globals')) ? 'ON' : 'OFF';
echo '</text><br>';
echo '<label>magic_quotes_gpc:</label><text>';
echo (ini_get('magic_quotes_gpc')) ? 'ON' : 'OFF';
echo '</text><br>';
echo '<label>XML extension:</label><text>';
echo (extension_loaded('xml')) ? 'ON' : 'OFF';
echo '</text><br>';
echo '<label>MB extension:</label><text>';
echo (extension_loaded('mbstring')) ? 'ON' : 'OFF';
echo '</text><br>';
echo '</div>';
echo '</fieldset><br>';

$sql = 'SELECT COUNT(DISTINCT post_author) AS count_author, COUNT(*) AS count_article FROM ' . $xoopsDB->prefix('wp_posts');
$result = $xoopsDB->query($sql);
if ($myrow = $xoopsDB->fetchArray($result)) {
    $count_article = $myrow['count_article'];

    $count_author = $myrow['count_author'];
}
$sql = 'SELECT COUNT(*) AS count_category FROM ' . $xoopsDB->prefix('wp_categories');
$result = $xoopsDB->query($sql);
if ($myrow = $xoopsDB->fetchArray($result)) {
    $count_category = $myrow['count_category'];
}

echo "<fieldset><legend style='font-weight: bold; color: #900;'>" . _AM_WORDPRESS_STATS . '</legend>';
echo "<div style='padding: 8px;'>";
echo '<label>' . _AM_WORDPRESS_CATEGORIES . ':</label><text>' . $count_category;
echo '</text><br>';
echo '<label>' . _AM_WORDPRESS_ARTICLES . ':</label><text>' . $count_article;
echo '</text><br>';
echo '<label>' . _AM_WORDPRESS_AUTHORS . ':</label><text>' . $count_author;
echo '</text>';
echo '</div>';
echo '</fieldset>';

xoops_cp_footer();
