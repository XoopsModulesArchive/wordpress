<?php
// $Id: block.inc.php,v 1.1 2006/03/10 18:50:37 mikhail Exp $

/*
 * countdown.inc.php
 * License: GPL
 * Author: nao-pon http://hypweb.net
 * XOOPS Module Block Plugin
 *
 */

function plugin_block_convert()
{
    $params = ['end' => false, 'clear' => false, 'left' => false, 'center' => false, 'right' => false, 'around' => false, 'width' => '', 'w' => '', 'class' => false, '_args' => [], '_done' => false];
    array_walk(func_get_args(), 'block_check_arg', $params);

    // end
    if ($params['end']) {
        return '</div>' . "\n";
    }
    // clear
    if ($params['clear']) {
        return '<div style="clear:both"></div>' . "\n";
    }

    if ($params['left']) {
        $align = 'left';
    }
    if ($params['center']) {
        $align = 'center';
    }
    if ($params['right']) {
        $align = 'right';
    }

    $around = $params['around'];
    $width  = $params['w'];
    if (!$width) {
        $width = $params['width'];
    }

    if (preg_match("/^[\d]+%?$/", $width)) {
        $width = (false === strpos($width, '%')) ? $width . 'px' : $width;
        $width = 'width:' . $width . ';';
    } else {
        $width = '';
    }

    if ($params['around']) {
        $style = " style='float:{$align};{$width}'";
    } else {
        if ($params['left']) {
            $style = " align='left' style='{$width}'";
        } elseif ($params['right']) {
            $style = " align='right' style='{$width}'";
        } else {
            $style = " align='center' style='{$width}'";
        }
    }
    //$clear = ($around)? "" : "<div style='clear:both;'></div>\n";

    return "<div{$style} class=\"wiki_body_block\">";
}

//オプションを解析する
function block_check_arg($val, $key, &$params)
{
    if ($val == '') {
        $params['_done'] = true;
        return;
    }

    if (!$params['_done']) {
        foreach (array_keys($params) as $key) {
            if (strpos($val, ':')) // PHP4.3.4＋Apache2 環境で何故かApacheが落ちるとの報告があったので
            {
                [$_val, $thisval] = explode(':', $val);
            } else {
                $_val    = $val;
                $thisval = null;
            }
            if (strtolower($_val) == $key) {
                if (!empty($thisval)) {
                    $params[$key] = $thisval;
                } else {
                    $params[$key] = true;
                }
                return;
            }
        }
        $params['_done'] = true;
    }
    $params['_args'][] = $val;
}


