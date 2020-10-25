<?php
/////////////////////////////////////////////////
// PukiWiki - Yet another WikiWikiWeb clone.
//
// $Id: showrss.inc.php,v 1.1 2006/03/10 18:50:37 mikhail Exp $
//
// Modified version by PANDA <panda@arino.jp>
//

/**
 * showrss プラグイン (Created by hiro_do3ob@yahoo.co.jp)
 *
 * ライセンスは PukiWiki 本体と同じく GNU General Public License (GPL) です。
 * http://www.gnu.org/licenses/gpl.txt
 *
 * pukiwiki用のプラグインです。
 * pukiwiki1.3.2以上で動くと思います。
 *
 * 今のところ動作させるためにはPHP の xml extension が必須です。PHPに組み込まれてない場合はそっけないエラーが出ると思います。
 * 正規表現 or 文字列関数でなんとかならなくもなさげなんですが需要ってどれくらいあるのかわからいので保留です。
 *
 * version: Id:showrss.inc.php,v 1.40 2003/03/18 11:52:58 hiro Exp
 */

// showrssプラグインが使用可能かどうかを表示
function plugin_showrss_action()
{
    $xml_extension = extension_loaded('xml');

    $mbstring_extension = extension_loaded('mbstring');

    $xml_msg = $xml_extension ? 'xml extension is loaded' : 'COLOR(RED){xml extension is not loaded}';

    $mbstring_msg = $mbstring_extension ? 'mbstring extension is loaded' : 'COLOR(RED){mbstring extension is not loaded}';

    $showrss_info = '';

    $showrss_info .= "| xml parser | $xml_msg |\n";

    $showrss_info .= "| multibyte | $mbstring_msg |\n";

    return ['msg' => 'showrss_info', 'body' => convert_html($showrss_info)];
}

function plugin_showrss_convert()
{
    if (0 == func_num_args()) {
        // 引数がない場合はエラー

        return "<p>showrss: no parameter(s).</p>\n";
    }

    if (!extension_loaded('xml')) {
        // xml 拡張機能が有効でない場合。

        return "<p>showrss: xml extension is not loaded</p>\n";
    }

    $array = func_get_args();

    $rssurl = $tmplname = $usecache = $usetimestamp = '';

    switch (func_num_args()) {
        case 4:
            $usetimestamp = trim($array[3]);
            // no break
        case 3:
            $usecache = $array[2];
            // no break
        case 2:
            $tmplname = mb_strtolower(trim($array[1]));
            // no break
        case 1:
            $rssurl = trim($array[0]);
    }

    // RSS パスの値チェック

    if (!PukiWikiFunc::is_url($rssurl)) {
        return '<p>showrss: syntax error. ' . htmlspecialchars($rssurl, ENT_QUOTES | ENT_HTML5) . "</p>\n";
    }

    $class = "ShowRSS_html_$tmplname";

    if (!class_exists($class)) {
        $class = 'ShowRSS_html';
    }

    [$rss, $time] = plugin_showrss_get_rss($rssurl, $usecache);

    if (false === $rss) {
        return "<p>showrss: cannot get rss from server.</p>\n";
    }

    $obj = new $class($rss);

    $timestamp = '';

    if ($usetimestamp > 0) {
        $time = PukiWikiFunc::get_date('Y/m/d H:i:s', $time);

        $timestamp = "<p style=\"font-size:10px; font-weight:bold\">Last-Modified:$time</p>";
    }

    return $obj->toString($timestamp);
}

// rss配列からhtmlを作る
class ShowRSS_html
{
    public $items = [];

    public $class = '';

    public function __construct($rss)
    {
        foreach ($rss as $date => $items) {
            foreach ($items as $item) {
                $link = $item['LINK'];

                $title = $item['TITLE'];

                $passage = PukiWikiFunc::get_passage($item['_TIMESTAMP']);

                $link = "<a href=\"$link\" title=\"$title $passage\">$title</a>";

                $this->items[$date][] = $this->format_link($link);
            }
        }
    }

    public function format_link($link)
    {
        return "$link<br>\n";
    }

    public function format_list($date, $str)
    {
        return $str;
    }

    public function format_body($str)
    {
        return $str;
    }

    public function toString($timestamp)
    {
        $retval = '';

        foreach ($this->items as $date => $items) {
            $retval .= $this->format_list($date, implode('', $items));
        }

        $retval = $this->format_body($retval);

        return <<<EOD
<div{$this->class}>
$retval$timestamp
</div>
EOD;
    }
}

class ShowRSS_html_menubar extends ShowRSS_html
{
    public $class;

    public function __construct($rss)
    {
        parent::__construct($rss);

        $this->class = ' class="' . PukiWikiConfig::getParam('style_prefix') . 'small"';
    }

    public function format_link($link)
    {
        return "<li>$link</li>\n";
    }

    public function format_body($str)
    {
        return '<ul class="' . PukiWikiConfig::getParam('style_prefix') . "recent_list\">\n$str</ul>\n";
    }
}

class ShowRSS_html_recent extends ShowRSS_html
{
    public $class;

    public function __construct($rss)
    {
        parent::__construct($rss);

        $this->class = ' class="' . PukiWikiConfig::getParam('style_prefix') . 'small"';
    }

    public function format_link($link)
    {
        return "<li>$link</li>\n";
    }

    public function format_list($date, $str)
    {
        return "<strong>$date</strong>\n<ul class=\"" . PukiWikiConfig::getParam('style_prefix') . "recent_list\">\n$str</ul>\n";
    }
}

class ShowRSS_html_antenna extends ShowRSS_html
{
    public $items = [];

    public $class = '';

    public function __construct($rss)
    {
        foreach ($rss as $date => $items) {
            foreach ($items as $item) {
                $link = $item['LINK'];

                $title = $item['TITLE'];

                $dstr = date('m/d H:i', $item['_TIMESTAMP']);

                $passage = PukiWikiFunc::get_passage($item['_TIMESTAMP']);

                $link = "<a href=\"$link\" title=\"$title $passage\" target=\"_blank\"><small style=\"font-size:60%;\">$dstr :</small> $title </a>";

                $this->items[$date][] = $this->format_link($link);
            }
        }
    }

    public function format_list($date, $str)
    {
        return $str;
    }
}

// rssを取得する
function plugin_showrss_get_rss($target, $usecache)
{
    $buf = '';

    $time = null;

    if ($usecache) {
        // 期限切れのキャッシュをクリア

        plugin_showrss_cache_expire($usecache);

        // キャッシュがあれば取得する

        $filename = MOD_PUKI_CACHE_DIR . PukiWikiFunc::encode($target) . '.tmp';

        if (is_readable($filename)) {
            $buf = implode('', file($filename));

            $time = filemtime($filename) - MOD_PUKI_LOCALZONE;
        }
    }

    if (null === $time) {
        // rss本体を取得

        $data = PukiWikiFunc::http_request($target);

        if (200 !== $data['rc']) {
            return [false, 0];
        }

        $buf = $data['data'];

        $time = MOD_PUKI_UTIME;

        // キャッシュを保存

        if ($usecache) {
            $fp = fopen($filename, 'wb');

            fwrite($fp, $buf);

            fclose($fp);
        }
    }

    // parse

    $obj = new ShowRSS_XML();

    return [$obj->parse($buf), $time];
}

// 期限切れのキャッシュをクリア
function plugin_showrss_cache_expire($usecache)
{
    $expire = $usecache * 60 * 60; // Hour

    $dh = dir(MOD_PUKI_CACHE_DIR);

    while (false !== ($file = $dh->read())) {
        if ('.tmp' != mb_substr($file, -4)) {
            continue;
        }

        $file = MOD_PUKI_CACHE_DIR . $file;

        $last = time() - filemtime($file);

        if ($last > $expire) {
            unlink($file);
        }
    }

    $dh->close();
}

// rssを取得・配列化
class ShowRSS_XML
{
    public $items;

    public $item;

    public $is_item;

    public $tag;

    public function parse($buf)
    {
        // 初期化

        $this->items = [];

        $this->item = [];

        $this->is_item = false;

        $this->tag = '';

        $xml_parser = xml_parser_create();

        xml_set_elementHandler($xml_parser, [&$this, 'start_element'], [&$this, 'end_element']);

        xml_set_character_dataHandler($xml_parser, [&$this, 'character_data']);

        if (!xml_parse($xml_parser, $buf, 1)) {
            return (sprintf(
                'XML error: %s at line %d in %s',
                xml_error_string(xml_get_error_code($xml_parser)),
                xml_get_current_line_number($xml_parser),
                $buf
            ));
        }

        xml_parser_free($xml_parser);

        return $this->items;
    }

    public function escape($str)
    {
        // RSS中の "&lt; &gt; &amp;" などを 一旦 "< > &" に戻し、 ＜ "&amp;" が "&amp;amp;" になっちゃうの対策

        // その後もっかい"< > &"などを"&lt; &gt; &amp;"にする  ＜ XSS対策？

        //		$str = strtr($str, array_flip(get_html_translation_table(ENT_COMPAT)));

        $str = htmlspecialchars($str, ENT_QUOTES | ENT_HTML5);

        // 文字コード変換

        $str = mb_convert_encoding($str, MOD_PUKI_SOURCE_ENCODING, 'auto');

        return trim($str);
    }

    // タグ開始

    public function start_element($parser, $name, $attrs)
    {
        if ($this->is_item) {
            $this->tag = $name;
        } elseif ('ITEM' == $name) {
            $this->is_item = true;
        }
    }

    // タグ終了

    public function end_element($parser, $name)
    {
        if (!$this->is_item or 'ITEM' != $name) {
            return;
        }

        $item = array_map([&$this, 'escape'], $this->item);

        $this->item = [];

        if (array_key_exists('DC:DATE', $item)) {
            $time = plugin_showrss_get_timestamp($item['DC:DATE']);
        } elseif (array_key_exists('PUBDATE', $item)) {
            $time = plugin_showrss_get_timestamp($item['PUBDATE']);
        }

        //		else if (array_key_exists('DESCRIPTION',$item)

        //			and ($description = trim($item['DESCRIPTION'])) != ''

        //			and ($time = strtotime($description)) != -1)

        //		{

        //			$time -= MOD_PUKI_LOCALZONE;

        //		}

        else {
            $time = time() - MOD_PUKI_LOCALZONE;
        }

        $item['_TIMESTAMP'] = $time;

        $date = PukiWikiFunc::get_date('Y-m-d', $item['_TIMESTAMP']);

        $this->items[$date][] = $item;

        $this->is_item = false;
    }

    // キャラクタ

    public function character_data($parser, $data)
    {
        if (!$this->is_item) {
            return;
        }

        if (!array_key_exists($this->tag, $this->item)) {
            $this->item[$this->tag] = '';
        }

        $this->item[$this->tag] .= $data;
    }
}

function plugin_showrss_get_timestamp($str)
{
    if ('' == ($str = trim($str))) {
        return MOD_PUKI_UTIME;
    }

    if (!preg_match('/(\d{4}-\d{2}-\d{2})T(\d{2}:\d{2}:\d{2})(([+-])(\d{2}):(\d{2}))?/', $str, $matches)) {
        $time = strtotime($str);

        return (-1 == $time) ? MOD_PUKI_UTIME : $time - MOD_PUKI_LOCALZONE;
    }

    $str = $matches[1];

    $time = strtotime($matches[1] . ' ' . $matches[2]);

    if (!empty($matches[3])) {
        $diff = ($matches[5] * 60 + $matches[6]) * 60;

        $time += ('-' == $matches[4] ? $diff : -$diff);
    }

    return $time;
}
