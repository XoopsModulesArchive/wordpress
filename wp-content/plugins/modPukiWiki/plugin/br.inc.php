<?php
/////////////////////////////////////////////////
// PukiWiki - Yet another WikiWikiWeb clone.
//
// $Id: br.inc.php,v 1.1 2006/03/10 18:50:37 mikhail Exp $
//

function plugin_br_convert()
{
    return '<br class="' . PukiWikiConfig::getParam('style_prefix') . 'spacer">';
}
