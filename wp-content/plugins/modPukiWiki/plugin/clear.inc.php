<?php
/////////////////////////////////////////////////
// PukiWiki - Yet another WikiWikiWeb clone.
//
// $Id: clear.inc.php,v 1.1 2006/03/10 18:50:37 mikhail Exp $
//
// div class="clear"を表示する
// plugin=clear

function plugin_clear_convert()
{
    return '<div class="' . PukiWikiConfig::getParam('style_prefix') . 'clear"></div>';
}
