<?php
/////////////////////////////////////////////////
// PukiWiki - Yet another WikiWikiWeb clone.
//
// $Id: color.inc.php,v 1.1 2006/03/10 18:50:37 mikhail Exp $
//

function plugin_color_inline()
{
    if (3 == func_num_args()) {
        [$color, $bgcolor, $body] = func_get_args();

        if ('' == $body) {
            $body = $bgcolor;

            $bgcolor = '';
        }
    } elseif (2 == func_num_args()) {
        $bgcolor = '';

        [$color, $body] = func_get_args();
    } else {
        return false;
    }

    if ('' == $color or '' == $body) {
        return false;
    }

    if (!plugin_color_is_valid($color) or !plugin_color_is_valid($bgcolor)) {
        return $body;
    }

    if ('' != $bgcolor) {
        $color .= ';background-color:' . $bgcolor;
    }

    return "<span style=\"color:$color\">$body</span>";
}

function plugin_color_is_valid($color)
{
    return ('' == $color) or preg_match('/^(#[0-9a-f]+|[\w-]+)$/i', $color);
}
