<?php
/////////////////////////////////////////////////
// PukiWiki - Yet another WikiWikiWeb clone.
//
// $Id: aname.inc.php,v 1.1 2006/03/10 18:50:37 mikhail Exp $
//

function plugin_aname_inline()
{
    $args = func_get_args();

    return call_user_func_array('plugin_aname_convert', $args);
}

function plugin_aname_convert()
{
    global $script, $vars;

    if (func_num_args() < 1) {
        return false;
    }

    $args = func_get_args();

    $id = array_shift($args);

    if (!preg_match('/^[A-Za-z][\w\-]*$/', $id)) {
        return false;
    }

    $body = count($args) ? preg_replace('/<\/?a[^>]*>/', '', array_pop($args)) : '';

    $class = in_array('super', $args, true) ? 'anchor_super' : 'anchor';

    $url = in_array('full', $args, true) ? "$script?" . rawurlencode($vars['page']) : '';

    $attr_id = in_array('noid', $args, true) ? '' : " id=\"$id\"";

    return "<a class=\"$class\"$attr_id href=\"$url#$id\" title=\"$id\">$body</a>";
}
