<?php

// $Id: img.inc.php,v 1.1 2006/03/10 18:50:37 mikhail Exp $
function plugin_img_convert()
{
    if (2 != func_num_args()) {
        return;
    }

    $aryargs = func_get_args();

    $url = $aryargs[0];

    $align = mb_strtoupper($aryargs[1]);

    if ('R' == $align || 'RIGHT' == $align) {
        $align = 'right';
    } elseif ('L' == $align || 'LEFT' == $align) {
        $align = 'left';
    } else {
        return '<br style="clear:both">';
    }

    if (!is_url($url) or preg_match('/(\.gif|\.png|\.jpeg|\.jpg)$/i', $url)) {
        return;
    }

    return "<div style=\"float:$align;padding:.5em 1.5em .5em 1.5em\"><img src=\"$url\" alt=\"\"></div>";
}
