<?php
/////////////////////////////////////////////////
// PukiWiki - Yet another WikiWikiWeb clone.
//
// $Id: PukiWikiLink.php,v 1.1 2006/03/10 18:50:32 mikhail Exp $
//
// modPukiWikiリンク生成用クラス群
// 修正元ファイル：PukiWiki 1.4のmake_link.php
// ORG: make_link.php,v 1.2 2004/09/19 14:05:30 henoheno Exp $
//

//インライン要素を置換する
class PukiWikiInlineConverter
{
    public $converters; // as array()

    public $pattern;

    public $pos;

    public $result;

    public function get_clone($obj)
    {
        static $clone_func;

        if (!isset($clone_func)) {
            if (version_compare(PHP_VERSION, '5.0.0', '<')) {
                $clone_func = create_function('$a', 'return $a;');
            } else {
                $clone_func = create_function('$a', 'return clone $a;');
            }
        }

        return $clone_func($obj);
    }

    public function __clone()
    {
        $converters = [];

        foreach ($this->converters as $key => $converter) {
            $converters[$key] = $this->get_clone($converter);
        }

        $this->converters = $converters;
    }

    public function __construct($converters = null, $excludes = null)
    {
        if (null === $converters) {
            $converters = [
                'plugin',        // インラインプラグイン
                'note',          // 注釈
                'url',           // URL
                'url_interwiki', // URL (interwiki definition)
                'mailto',        // mailto:
                'interwikiname', // InterWikiName
                'autolink',      // AutoLink
                'bracketname',   // BracketName
                'wikiname',      // WikiName
                'autolink_a',    // AutoLink(アルファベット)
            ];
        }

        if (null !== $excludes) {
            $converters = array_diff($converters, $excludes);
        }

        $this->converters = [];

        $patterns = [];

        $start = 1;

        foreach ($converters as $name) {
            $classname = "PukiWikiLink_$name";

            $converter = new $classname($start);

            $pattern = $converter->get_pattern();

            if (false === $pattern) {
                continue;
            }

            $patterns[] = "(\n$pattern\n)";

            $this->converters[$start] = $converter;

            $start += $converter->get_count();

            $start++;
        }

        $this->pattern = implode('|', $patterns);
    }

    public function convert($string, $page)
    {
        $this->page = $page;

        $this->result = [];

        $string = preg_replace_callback("/{$this->pattern}/x", [&$this, 'replace'], $string);

        $arr = explode("\x08", PukiWikiConfig::applyRules(htmlspecialchars($string, ENT_QUOTES | ENT_HTML5)));

        $retval = '';

        while (count($arr)) {
            $retval .= array_shift($arr) . array_shift($this->result);
        }

        return $retval;
    }

    public function replace($arr)
    {
        $obj = $this->get_converter($arr);

        $this->result[] = (null !== $obj and false !== $obj->set($arr, $this->page)) ? $obj->toString() : PukiWikiConfig::applyRules(htmlspecialchars($arr[0], ENT_QUOTES | ENT_HTML5));

        return "\x08"; //処理済みの部分にマークを入れる
    }

    public function get_objects($string, $page)
    {
        preg_match_all("/{$this->pattern}/x", $string, $matches, PREG_SET_ORDER);

        $arr = [];

        foreach ($matches as $match) {
            $obj = $this->get_converter($match);

            if (false !== $obj->set($match, $page)) {
                $arr[] = $this->get_clone($obj);

                if ('' != $obj->body) {
                    $arr = array_merge($arr, $this->get_objects($obj->body, $page));
                }
            }
        }

        return $arr;
    }

    public function get_converter($arr)
    {
        foreach (array_keys($this->converters) as $start) {
            if ($arr[$start] == $arr[0]) {
                return $this->converters[$start];
            }
        }

        return null;
    }
}

//インライン要素集合のベースクラス
class PukiWikiLink
{
    public $start;   // 括弧の先頭番号(0オリジン)
    public $text;    // マッチした文字列全体
    public $type;

    public $page;

    public $name;

    public $body;

    public $alias;

    // constructor

    public function __construct($start)
    {
        $this->start = $start;
    }

    // マッチに使用するパターンを返す

    public function get_pattern()
    {
    }

    // 使用している括弧の数を返す ((?:...)を除く)

    public function get_count()
    {
    }

    // マッチしたパターンを設定する

    public function set($arr, $page)
    {
    }

    // 文字列に変換する

    public function toString()
    {
    }

    //private

    // マッチした配列から、自分に必要な部分だけを取り出す

    public function splice($arr)
    {
        $count = $this->get_count() + 1;

        $arr = array_pad(array_splice($arr, $this->start, $count), $count, '');

        $this->text = $arr[0];

        return $arr;
    }

    // 基本パラメータを設定する

    public function setParam($page, $name, $body, $type = '', $alias = '')
    {
        static $converter = null;

        $this->page = $page;

        $this->name = $name;

        $this->body = $body;

        $this->type = $type;

        if (PukiWikiFunc::is_url($alias) && preg_match('/\.(gif|png|jpe?g)$/i', $alias)) {  //BugTrack 669
            $alias = htmlspecialchars($alias, ENT_QUOTES | ENT_HTML5);

            $alias = "<img src=\"$alias\" alt=\"$name\">";
        } elseif ('' != $alias) {
            if (null === $converter) {
                $converter = new PukiWikiInlineConverter(['plugin']);
            }

            $alias = PukiWikiConfig::applyRules($converter->convert($alias, $page));

            $alias = preg_replace('#</?a[^>]*>#i', '', $alias);  //BugTrack 669
        }

        $this->alias = $alias;

        return true;
    }

    // ページ名のリンクを作成

    public function make_pagelink($page, $alias = '', $anchor = '', $refer = '')
    {
        $s_page = htmlspecialchars(PukiWikiFunc::strip_bracket($page), ENT_QUOTES | ENT_HTML5);

        $s_alias = ('' == $alias) ? $s_page : $alias;

        if ('' == $page) {
            return "<a href=\"$anchor\">$s_alias</a>";
        }

        $r_page = rawurlencode($page);

        $r_refer = ('' == $refer) ? '' : '&amp;refer=' . rawurlencode($refer);

        if (PukiWikiConfig::getParam('LocalShowURL')) {
            if (PukiWikiFunc::is_local_page($page)) {
                $passage = '';

                $title = PukiWikiConfig::getParam('link_compact') ? '' : " title=\"$s_page$passage\"";

                $url = sprintf(PukiWikiConfig::getParam('LocalShowURL'), $r_page . $anchor);

                return "<a href=\"$url\"$title>$s_alias</a>";
            }
        }

        if (defined('MOD_PUKI_WIKI_URL')) {
            if (PukiWikiFunc::is_page($page)) {
                $passage = '';

                $title = PukiWikiConfig::getParam('link_compact') ? '' : " title=\"$s_page$passage\"";

                if (defined('XOOPS_URL') and MOD_PUKI_WIKI_VER == '1.3' and PukiWikiConfig::getParam('use_static_url')) {
                    return '<a href="' . XOOPS_URL . '/modules/pukiwiki/' . PukiWikiFunc::get_pgid_by_name($page) . ".html{$anchor}\"$title>$s_alias</a>";
                }
  

                return '<a href="' . MOD_PUKI_WIKI_URL . "?$r_page$anchor\"$title>$s_alias</a>";
            }  

            $retval = "$s_alias<a href=\"" . MOD_PUKI_WIKI_URL . "?cmd=edit&amp;page=$r_page$r_refer\">" . PukiWikiConfig::getParam('_symbol_noexists') . '</a>';

            if (!PukiWikiConfig::getParam('link_compact')) {
                $retval = '<span class="' . PukiWikiConfig::getParam('style_prefix') . "noexists\">$retval</span>";
            }

            return $retval;
        }
  

        return $s_alias;
    }
}

// インラインプラグイン
class PukiWikiLink_plugin extends PukiWikiLink
{
    public $pattern;

    public $plain;

    public $param;

    public function __construct($start)
    {
        parent::__construct($start);
    }

    public function get_pattern()
    {
        $this->pattern = <<<EOD
&
(      # (1) plain
 (\w+) # (2) plugin name
 (?:
  \(
   ((?:(?!\)[;{]).)*) # (3) parameter
  \)
 )?
)
EOD;

        return <<<EOD
{$this->pattern}
(?:
 \{
  ((?:(?R)|(?!};).)*) # (4) body
 \}
)?
;
EOD;
    }

    public function get_count()
    {
        return 4;
    }

    public function set($arr, $page)
    {
        [$all, $this->plain, $name, $this->param, $body] = $this->splice($arr);

        // 本来のプラグイン名およびパラメータを取得しなおす PHP4.1.2 (?R)対策

        if (preg_match("/^{$this->pattern}/x", $all, $matches) and $matches[1] != $this->plain) {
            [, $this->plain, $name, $this->param] = $matches;
        }

        return parent::setParam($page, $name, $body, 'plugin');
    }

    public function toString()
    {
        $body = ('' == $this->body) ? '' : PukiWikiFunc::make_link($this->body);

        // プラグイン呼び出し

        if (PukiWikiPlugin::exist_plugin_inline($this->name)) {
            $str = PukiWikiPlugin::do_plugin_inline($this->name, $this->param, $body);

            if (false !== $str) { //成功
                return $str;
            }
        }

        // プラグインが存在しないか、変換に失敗

        $body = ('' == $body) ? ';' : "\{$body};";

        return PukiWikiConfig::applyRules(htmlspecialchars('&' . $this->plain, ENT_QUOTES | ENT_HTML5) . $body);
    }
}

// url
class PukiWikiLink_url extends PukiWikiLink
{
    public function __construct($start)
    {
        parent::__construct($start);
    }

    public function get_pattern()
    {
        $s1 = $this->start + 1;

        if (PukiWikiConfig::getParam('autourllink')) {
            return <<<EOD
(\[\[             # (1) open bracket
 ((?:(?!\]\]).)+) # (2) alias
 (>|:)            # (3) separator
)?
(                 # (4) url
 (?:https?|ftp|news):\/\/[!~*'();\/?:\@&=+\$,%#\w.-]+
)
(?($s1)\]\])      # close bracket
EOD;
        }
  

        return <<<EOD
(\[\[             # (1) open bracket
 ((?:(?!\]\]).)+) # (2) alias
 (>|:)            # (3) separator
)
(                 # (4) url
 (?:https?|ftp|news):\/\/[!~*'();\/?:\@&=+\$,%#\w.-]+
)
(?($s1)\]\])      # close bracket
EOD;
    }

    public function get_count()
    {
        return 4;
    }

    public function set($arr, $page)
    {
        [, , $alias, $separator, $name] = $this->splice($arr);

        $this->separator = $separator;

        return parent::setParam($page, htmlspecialchars($name, ENT_QUOTES | ENT_HTML5), '', 'url', '' == $alias ? $name : $alias);
    }

    public function toString()
    {
        if ('>' == $this->separator) {
            return "<a href=\"{$this->name}\">{$this->alias}</a>";
        }  

        $target = '';

        if ($target = PukiWikiConfig::getParam('link_target')) {
            $target = " target=\"{$target}\"";
        }

        return "<a href=\"{$this->name}\"{$target}>{$this->alias}</a>";
    }
}

// url (InterWiki definition type)
class PukiWikiLink_url_interwiki extends PukiWikiLink
{
    public function __construct($start)
    {
        parent::__construct($start);
    }

    public function get_pattern()
    {
        return <<<EOD
\[       # open bracket
(        # (1) url
 (?:(?:https?|ftp|news):\/\/|\.\.?\/)[!~*'();\/?:\@&=+\$,%#\w.-]*
)
\s
([^\]]+) # (2) alias
\]       # close bracket
EOD;
    }

    public function get_count()
    {
        return 2;
    }

    public function set($arr, $page)
    {
        [, $name, $alias] = $this->splice($arr);

        return parent::setParam($page, htmlspecialchars($name, ENT_QUOTES | ENT_HTML5), '', 'url', $alias);
    }

    public function toString()
    {
        return "<a href=\"{$this->name}\">{$this->alias}</a>";
    }
}

//mailto:
class PukiWikiLink_mailto extends PukiWikiLink
{
    public $is_image;

    public $image;

    public function __construct($start)
    {
        parent::__construct($start);
    }

    public function get_pattern()
    {
        $s1 = $this->start + 1;

        if (PukiWikiConfig::getParam('autourllink')) {
            return <<<EOD
(?:
 \[\[
 ((?:(?!\]\]).)+)(?:>|:)  # (1) alias
)?
([\w.-]+@[\w-]+\.[\w.-]+) # (2) mailto
(?($s1)\]\])              # close bracket if (1)
EOD;
        }
  

        return <<<EOD
(?:
 \[\[
 ((?:(?!\]\]).)+)(?:>|:)  # (1) alias
)
([\w.-]+@[\w-]+\.[\w.-]+) # (2) mailto
(?($s1)\]\])              # close bracket if (1)
EOD;
    }

    public function get_count()
    {
        return 2;
    }

    public function set($arr, $page)
    {
        [, $alias, $name] = $this->splice($arr);

        return parent::setParam($page, $name, '', 'mailto', '' == $alias ? $name : $alias);
    }

    public function toString()
    {
        return "<a href=\"mailto:{$this->name}\">{$this->alias}</a>";
    }
}

// BracketName
class PukiWikiLink_bracketname extends PukiWikiLink
{
    public $anchor;

    public $refer;

    public function __construct($start)
    {
        parent::__construct($start);
    }

    public function get_pattern()
    {
        $WikiName = PukiWikiConfig::getParam('WikiName');

        $BracketName = PukiWikiConfig::getParam('BracketName');

        $s2 = $this->start + 2;

        return <<<EOD
\[\[                     # open bracket
(?:((?:(?!\]\]).)+)>)?   # (1) alias
(\[\[)?                  # (2) open bracket
(                        # (3) PageName
 (?:$WikiName)
 |
 (?:$BracketName)
)?
(\#(?:[a-zA-Z][\w-]*)?)? # (4) anchor
(?($s2)\]\])             # close bracket if (2)
\]\]                     # close bracket
EOD;
    }

    public function get_count()
    {
        return 4;
    }

    public function set($arr, $page)
    {
        $WikiName = PukiWikiConfig::getParam('WikiName');

        [, $alias, , $name, $this->anchor] = $this->splice($arr);

        if ('' == $name and '' == $this->anchor) {
            return false;
        }

        if ('' != $name and preg_match("/^$WikiName$/", $name)) {
            return parent::setParam($page, $name, '', 'pagename', $alias);
        }

        if ('' == $alias) {
            $alias = $name . $this->anchor;
        }

        if ('' == $name) {
            if ('' == $this->anchor) {
                return false;
            }
        } else {
            if (!(PukiWikiFunc::is_pagename($name))) {
                return false;
            }
        }

        return parent::setParam($page, $name, '', 'pagename', $alias);
    }

    public function toString()
    {
        return $this->make_pagelink(
            $this->name,
            $this->alias,
            $this->anchor,
            $this->page
        );
    }
}

// WikiName
class PukiWikiLink_wikiname extends PukiWikiLink
{
    public function __construct($start)
    {
        parent::__construct($start);
    }

    public function get_pattern()
    {
        $WikiName = PukiWikiConfig::getParam('WikiName');

        $nowikiname = PukiWikiConfig::getParam('nowikiname');

        return $nowikiname ? false : "($WikiName)";
    }

    public function get_count()
    {
        return 1;
    }

    public function set($arr, $page)
    {
        [$name] = $this->splice($arr);

        return parent::setParam($page, $name, '', 'pagename', $name);
    }

    public function toString()
    {
        return $this->make_pagelink(
            $this->name,
            $this->alias,
            '',
            $this->page
        );
    }

    public function make_pagelink($page, $alias = '', $anchor = '', $refer = '')
    {
        $s_page = htmlspecialchars(PukiWikiFunc::strip_bracket($page), ENT_QUOTES | ENT_HTML5);

        $s_alias = ('' == $alias) ? $s_page : $alias;

        if ('' == $page) {
            return "<a href=\"$anchor\">$s_alias</a>";
        }

        $r_page = rawurlencode($page);

        $r_refer = ('' == $refer) ? '' : '&amp;refer=' . rawurlencode($refer);

        if (PukiWikiConfig::getParam('LocalShowURL')) {
            if (PukiWikiFunc::is_local_page($page)) {
                $passage = '';

                $title = PukiWikiConfig::getParam('link_compact') ? '' : " title=\"$s_page$passage\"";

                $url = sprintf(PukiWikiConfig::getParam('LocalShowURL'), $r_page . $anchor);

                return "<a href=\"$url\"$title>$s_alias</a>";
            }
        }

        if (defined('MOD_PUKI_WIKI_URL')) {
            if (PukiWikiFunc::is_page($page)) {
                $passage = '';

                $title = PukiWikiConfig::getParam('link_compact') ? '' : " title=\"$s_page$passage\"";

                if (defined('XOOPS_URL') and MOD_PUKI_WIKI_VER == '1.3' and PukiWikiConfig::getParam('use_static_url')) {
                    return '<a href="' . XOOPS_URL . '/modules/pukiwiki/' . PukiWikiFunc::get_pgid_by_name($page) . ".html{$anchor}\"$title>$s_alias</a>";
                }
  

                return '<a href="' . MOD_PUKI_WIKI_URL . "?$r_page$anchor\"$title>$s_alias</a>";
            }  

            // ページ作成リンクをつけないオプション追加 by nao-pon

            if (PukiWikiConfig::getParam('makepage_link')) {
                return $s_alias;
            }

            $retval = "$s_alias<a href=\"" . MOD_PUKI_WIKI_URL . "?cmd=edit&amp;page=$r_page$r_refer\">" . PukiWikiConfig::getParam('_symbol_noexists') . '</a>';

            if (PukiWikiConfig::getParam('link_compact')) {
                $retval = '<span class="' . PukiWikiConfig::getParam('style_prefix') . "noexists\">$retval</span>";
            }

            return $retval;
        }
  

        return $s_alias;
    }
}

// AutoLink
class PukiWikiLink_autolink extends PukiWikiLink
{
    public $forceignorepages = [];

    public $auto;

    public $auto_a; // alphabet only

    public function __construct($start)
    {
        parent::__construct($start);

        $autolink = PukiWikiConfig::getParam('autolink');

        $autolink_data = PukiWikiConfig::getParam('autolink_dat');

        // AutoLinkデータを予めチェックするようにした by nao-pon

        //if (!$autolink or !file_exists(MOD_PUKI_WIKI_CACHE_DIR.'autolink.dat'))

        if (!$autolink or !$autolink_data) {
            return;
        }

        // AutoLinkデータを予めチェックするようにした by nao-pon

        //@list($auto,$auto_a,$forceignorepages) = file(MOD_PUKI_WIKI_CACHE_DIR.'autolink.dat');

        @list($auto, $auto_a, $forceignorepages) = $autolink_data;

        $this->auto = $auto;

        $this->auto_a = $auto_a;

        $this->forceignorepages = explode("\t", trim($forceignorepages));
    }

    public function get_pattern()
    {
        return isset($this->auto) ? "({$this->auto})" : false;
    }

    public function get_count()
    {
        return 1;
    }

    public function set($arr, $page)
    {
        $WikiName = PukiWikiConfig::getParam('WikiName');

        [$name] = $this->splice($arr);

        // 共通リンクディレクトリ対応 by nao-pon

        $alias = $name;

        // 無視リストに含まれている、あるいは存在しないページを捨てる

        // 共通リンクディレクトリ対応 by nao-pon

        //if (in_array($name,$this->forceignorepages) or PukiWikiFunc::is_page($name))

        if (in_array($name, $this->forceignorepages, true)) {
            return false;
        }

        // 共通リンクディレクトリを探す by nao-pon

        if (!PukiWikiFunc::is_page($name)) {
            if (!$name = PukiWikiFunc::get_real_pagename($name)) {
                return false;
            }
        }

        // 共通リンクディレクトリ対応 by nao-pon

        //return parent::setParam($page,$name,'','pagename',$name);

        return parent::setParam($page, $name, '', 'pagename', $alias);
    }

    public function toString()
    {
        return $this->make_pagelink(
            $this->name,
            $this->alias,
            '',
            $this->page
        );
    }
}

class PukiWikiLink_autolink_a extends PukiWikiLink_autolink
{
    public function __construct($start)
    {
        parent::__construct($start);
    }

    public function get_pattern()
    {
        return isset($this->auto_a) ? "({$this->auto_a})" : false;
    }
}

// 注釈
class PukiWikiLink_note extends PukiWikiLink
{
    public function __construct($start)
    {
        parent::__construct($start);
    }

    public function get_pattern()
    {
        return <<<EOD
\(\(
 ((?:(?R)|(?!\)\)).)*) # (1) note body
\)\)
EOD;
    }

    public function get_count()
    {
        return 1;
    }

    public function set($arr, $page)
    {
        global $_PukiWikiFootExplain;

        static $note_id = 0;

        [, $body] = $this->splice($arr);

        $id = ++$note_id;

        $note = PukiWikiFunc::make_link($body);

        $style_small = PukiWikiConfig::getParam('style_prefix') . 'small';

        $style_super = PukiWikiConfig::getParam('style_prefix') . 'note_super';

        $_PukiWikiFootExplain[$id] = <<<EOD
<a id="notefoot_$id" href="#notetext_$id" class="$style_super">*$id</a>
<span class="$style_small">$note</span>
<br>
EOD;

        $name = "<a id=\"notetext_$id\" href=\"#notefoot_$id\" class=\"" . PukiWikiConfig::getParam('style_prefix') . "note_super\">*$id</a>";

        return parent::setParam($page, $name, $body);
    }

    public function toString()
    {
        return $this->name;
    }
}

//InterWikiName
class PukiWikiLink_interwikiname extends PukiWikiLink
{
    public $url = '';

    public $param = '';

    public $anchor = '';

    public function __construct($start)
    {
        parent::__construct($start);
    }

    public function get_pattern()
    {
        $s2 = $this->start + 2;

        $s5 = $this->start + 5;

        return <<<EOD
\[\[                  # open bracket
(?:
 ((?:(?!\]\]).)+)>    # (1) alias
)?
(\[\[)?               # (2) open bracket
((?:(?!\s|:|\]\]).)+) # (3) InterWiki
(?<! > | >\[\[ )      # not '>' or '>[['
:                     # separator
(                     # (4) param
 (\[\[)?              # (5) open bracket
 (?:(?!>|\]\]).)+
 (?($s5)\]\])         # close bracket if (5)
)
(?($s2)\]\])          # close bracket if (2)
\]\]                  # close bracket
EOD;
    }

    public function get_count()
    {
        return 5;
    }

    public function set($arr, $page)
    {
        [, $alias, , $name, $this->param] = $this->splice($arr);

        if (preg_match('/^([^#]+)(#[A-Za-z][\w-]*)$/', $this->param, $matches)) {
            [, $this->param, $this->anchor] = $matches;
        }

        $url = $this->get_interwiki_url($name, $this->param);

        $this->url = (false === $url) ? MOD_PUKI_WIKI_URL . '?' . rawurlencode('[[' . $name . ':' . $this->param . ']]') : htmlspecialchars($url, ENT_QUOTES | ENT_HTML5);

        return parent::setParam(
            $page,
            htmlspecialchars($name . ':' . $this->param, ENT_QUOTES | ENT_HTML5),
            '',
            'InterWikiName',
            '' == $alias ? $name . ':' . $this->param : $alias
        );
    }

    public function toString()
    {
        return "<a href=\"{$this->url}{$this->anchor}\" title=\"{$this->name}\">{$this->alias}</a>";
    }

    public function get_interwiki_url($name, $param)
    {
        static $interwikinames;

        static $encode_aliases = ['sjis' => 'SJIS', 'euc' => 'EUC-JP', 'utf8' => 'UTF-8'];

        $WikiName = PukiWikiConfig::getParam('WikiName');

        if (!isset($interwikinames)) {
            $interwikinames = [];

            foreach (PukiWikiConfig::getInteWikiArray() as $line) {
                if (preg_match('/\[((?:(?:https?|ftp|news):\/\/|\.\.?\/)[!~*\'();\/?:\@&=+\$,%#\w.-]*)\s([^\]]+)\]\s?([^\s]*)/', $line, $matches)) {
                    $interwikinames[$matches[2]] = [$matches[1], $matches[3]];
                }
            }
        }

        if (!array_key_exists($name, $interwikinames)) {
            return false;
        }

        [$url, $opt] = $interwikinames[$name];

        // 文字エンコーディング

        switch ($opt) {
            // YukiWiki系
            case 'yw':
                if (!preg_match("/$WikiName/", $param)) {
                    $param = '[[' . mb_convert_encoding($param, 'SJIS', MOD_PUKI_SOURCE_ENCODING) . ']]';
                }
                //			$param = htmlspecialchars($param);
                break;
            // moin系
            case 'moin':
                $param = str_replace('%', '_', rawurlencode($param));
                break;
            // 内部文字エンコーディングのままURLエンコード
            case '':
            case 'std':
                $param = rawurlencode($param);
                break;
            // URLエンコードしない
            case 'asis':
            case 'raw':
                //			$param = htmlspecialchars($param);
                break;
            default:
                // エイリアスの変換
                if (array_key_exists($opt, $encode_aliases)) {
                    $opt = $encode_aliases[$opt];
                }
                // 指定された文字コードへエンコードしてURLエンコード
                $param = rawurlencode(mb_convert_encoding($param, $opt, 'auto'));
        }

        // パラメータを置換

        if (false !== mb_strpos($url, '$1')) {
            $url = str_replace('$1', $param, $url);
        } else {
            $url .= $param;
        }

        return $url;
    }
}
