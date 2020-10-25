<?php
/////////////////////////////////////////////////
// PukiWiki - Yet another WikiWikiWeb clone.
//
// $Id: size.inc.php,v 1.1 2006/03/10 18:50:37 mikhail Exp $
//

function plugin_size_inline()
{
    if (2 != func_num_args()) {
        return false;
    }

    [$size, $body] = func_get_args();

    if ('' == $size or '' == $body) {
        return false;
    }

    if (!preg_match('/^\d+$/', $size)) {
        return $body;
    }

    $s_size = htmlspecialchars($size, ENT_QUOTES | ENT_HTML5);

    return "<span style=\"font-size:{$s_size}px;display:inline-block;line-height:130%;text-indent:0px\">$body</span>";
}
