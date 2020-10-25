<?php
/////////////////////////////////////////////////
// PukiWiki - Yet another WikiWikiWeb clone.
//
// $Id: ruby.inc.php,v 1.1 2006/03/10 18:50:37 mikhail Exp $
//

function plugin_ruby_inline()
{
    if (2 != func_num_args()) {
        return false;
    }

    [$ruby, $body] = func_get_args();

    if ('' == $ruby or '' == $body) {
        return false;
    }

    $s_ruby = htmlspecialchars($ruby, ENT_QUOTES | ENT_HTML5);

    return "<ruby><rb>$body</rb><rp>(</rp><rt>$s_ruby</rt><rp>)</rp></ruby>";
}
