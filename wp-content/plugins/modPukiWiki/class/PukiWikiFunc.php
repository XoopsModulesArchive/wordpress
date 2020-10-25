<?php
/////////////////////////////////////////////////
// PukiWiki - Yet another WikiWikiWeb clone.
//
// modPukiWiki汎用関数クラス群
//  インスタンス化せずにメンバー関数を呼び出す
//
// 修正元ファイル：PukiWiki 1.4のfile.php func.php html.php proxy.phpから利用関数のみを抽出。
//
class PukiWikiFunc
{
    // 文字列がURLかどうか

    public function is_url($str, $only_http = false)
    {
        $scheme = $only_http ? 'https?' : 'https?|ftp|news';

        return preg_match('/^(' . $scheme . ')(:\/\/[-_.!~*\'()a-zA-Z0-9;\/?:\@&=+\$,%#]*)$/', $str);
    }

    // 文字列がInterWikiNameかどうか

    public function is_interwiki($str)
    {
        $InterWikiName = PukiWikiConfig::getParam('InterWikiName');

        return preg_match("/^$InterWikiName$/", $str);
    }

    // 文字列がページ名かどうか

    public function is_pagename($str)
    {
        $BracketName = PukiWikiConfig::getParam('BracketName');

        $WikiName = PukiWikiConfig::getParam('WikiName');

        $is_pagename = (!self::is_interwiki($str) and preg_match("/^(?!\/)$BracketName$(?<!\/$)/", $str) and !preg_match('/(^|\/)\.{1,2}(\/|$)/', $str));

        if (defined('MOD_PUKI_SOURCE_ENCODING')) {
            if (MOD_PUKI_SOURCE_ENCODING == 'UTF-8') {
                $is_pagename = ($is_pagename and preg_match('/^(?:[\x00-\x7F]|(?:[\xC0-\xDF][\x80-\xBF])|(?:[\xE0-\xEF][\x80-\xBF][\x80-\xBF]))+$/', $str)); // UTF-8
            } elseif (MOD_PUKI_SOURCE_ENCODING == 'EUC-JP') {
                $is_pagename = ($is_pagename and preg_match('/^(?:[\x00-\x7F]|(?:[\x8E\xA1-\xFE][\xA1-\xFE])|(?:\x8F[\xA1-\xFE][\xA1-\xFE]))+$/', $str)); // EUC-JP
            }
        }

        return $is_pagename;
    }

    // ページのファイル名を得る

    public function get_filename($page)
    {
        if (MOD_PUKI_WIKI_VER == '1.3') {
            $page = self::add_bracket(self::strip_bracket($page));
        }

        return MOD_PUKI_WIKI_DATA_DIR . self::encode($page) . '.txt';
    }

    // ページが存在するか

    public function is_page($page, $reload = false)
    {
        return file_exists(self::get_filename($page));
    }

    // ローカルページのファイル名を得る

    public function get_local_filename($page)
    {
        return MOD_PUKI_DATA_DIR . self::encode($page) . '.txt';
    }

    // ローカルページが存在するか

    public function is_local_page($page, $reload = false)
    {
        return file_exists(self::get_local_filename($page));
    }

    // ページ名のエンコード

    public function encode($key)
    {
        return ('' == $key) ? '' : mb_strtoupper(implode('', unpack('H*0', $key)));
    }

    // ページ名のデコード

    public function decode($key)
    {
        return ('' == $key) ? '' : mb_substr(pack('H*', '20202020' . $key), 4);
    }

    // [[ ]] を削除する

    public function strip_bracket($str)
    {
        if (preg_match('/^\[\[(.*)\]\]$/', $str, $match)) {
            $str = $match[1];
        }

        return $str;
    }

    // [[ ]] を付加する

    public function add_bracket($str)
    {
        $WikiName = PukiWikiConfig::getParam('WikiName');

        if (!preg_match('/^' . $WikiName . '$/', $str)) {
            if (!preg_match("/\[\[.*\]\]/", $str)) {
                $str = '[[' . $str . ']]';
            }
        }

        return $str;
    }

    // HTMLタグを削除する

    public function strip_htmltag($str)
    {
        $_symbol_noexists = PukiWikiConfig::getParam('_symbol_noexists');

        $noexists_pattern = '#<span class="' . PukiWikiConfig::getParam('style_prefix') . 'noexists">([^<]*)<a[^>]+>' . preg_quote($_symbol_noexists, '#') . '</a></span>#';

        $str = preg_replace($noexists_pattern, '$1', $str);

        return preg_replace('/<[^>]+>/', '', $str);
    }

    // リンクを付加する

    public function make_link($string, $page = '')
    {
        static $converter;

        if (!isset($converter)) {
            $converter = new PukiWikiInlineConverter();
        }

        $_converter = $converter->get_clone($converter); // copy

        return $_converter->convert($string, $page);
    }

    // 見出しを生成 (注釈やHTMLタグを除去)

    public function make_heading(&$str, $strip = true)
    {
        $NotePattern = PukiWikiConfig::getParam('NotePattern');

        // 見出しの固有ID部を削除

        $id = '';

        if (preg_match('/^(\*{0,6})(.*?)\[#([A-Za-z][\w-]+)\](.*?)$/m', $str, $matches)) {
            $str = $matches[2] . $matches[4];

            $id = $matches[3];
        } else {
            $str = preg_replace('/^\*{0,6}/', '', $str);
        }

        if ($strip) {
            $str = self::strip_htmltag(self::make_link(preg_replace($NotePattern, '', $str)));
        }

        return $id;
    }

    // CSV形式の文字列を配列に

    public function csv_explode($separator, $string)
    {
        $_separator = preg_quote($separator, '/');

        if (!preg_match_all('/("[^"]*(?:""[^"]*)*"|[^' . $_separator . ']*)' . $_separator . '/', $string . $separator, $matches)) {
            return [];
        }

        $retval = [];

        foreach ($matches[1] as $str) {
            $len = mb_strlen($str);

            if ($len > 1 and '"' == $str[0] and '"' == $str[$len - 1]) {
                $str = str_replace('""', '"', mb_substr($str, 1, -1));
            }

            $retval[] = $str;
        }

        return $retval;
    }

    // 配列をCSV形式の文字列に

    public function csv_implode($glue, $pieces)
    {
        $_glue = ('' != $glue) ? '\\' . $glue[0] : '';

        $arr = [];

        foreach ($pieces as $str) {
            if (preg_match("[$_glue\"\n\r]", $str)) {
                $str = '"' . str_replace('"', '""', $str) . '"';
            }

            $arr[] = $str;
        }

        return implode($glue, $arr);
    }

    // 現在時刻をマイクロ秒で

    public function getmicrotime()
    {
        [$usec, $sec] = explode(' ', microtime());

        return ((float)$sec + (float)$usec);
    }

    // 日時を得る

    public function get_date($format, $timestamp = null)
    {
        $time = $timestamp ?? MOD_PUKI_UTIME;

        $time += MOD_PUKI_ZONETIME;

        $format = preg_replace('/(?<!\\\)T/', preg_replace('/(.)/', '\\\$1', MOD_PUKI_ZONE), $format);

        return date($format, $time);
    }

    // 日時文字列を作る

    public function format_date($val, $paren = false)
    {
        $date_format = PukiWikiConfig::getParam('date_format');

        $time_format = PukiWikiConfig::getParam('time_format');

        $weeklabels = PukiWikiConfig::getParam('weeklabels');

        $val += MOD_PUKI_ZONETIME;

        $ins_date = date($date_format, $val);

        $ins_time = date($time_format, $val);

        $ins_week = '(' . $weeklabels[date('w', $val)] . ')';

        $ins = "$ins_date $ins_week $ins_time";

        return $paren ? "($ins)" : $ins;
    }

    // 経過時刻文字列を作る

    public function get_passage($time, $paren = true)
    {
        static $units = ['m' => 60, 'h' => 24, 'd' => 1];

        $time = max(0, (MOD_PUKI_UTIME - $time) / 60); //minutes

        foreach ($units as $unit => $card) {
            if ($time < $card) {
                break;
            }

            $time /= $card;
        }

        $time = floor($time) . $unit;

        return $paren ? "($time)" : $time;
    }

    public function http_request($url, $method = 'GET', $headers = '', $post = [])
    {
        $use_proxy = PukiWikiConfig::getParam('use_proxy');

        $proxy_host = PukiWikiConfig::getParam('proxy_host');

        $proxy_port = PukiWikiConfig::getParam('proxy_port');

        $rc = [];

        $arr = parse_url($url);

        $via_proxy = $use_proxy and self::via_proxy($arr['host']);

        // query

        $arr['query'] = isset($arr['query']) ? '?' . $arr['query'] : '';

        // port

        $arr['port'] = $arr['port'] ?? 80;

        $url = $via_proxy ? $arr['scheme'] . '://' . $arr['host'] . ':' . $arr['port'] : '';

        $url .= $arr['path'] ?: '/';

        $url .= $arr['query'];

        $query = $method . ' ' . $url . " HTTP/1.0\r\n";

        $query .= 'Host: ' . $arr['host'] . "\r\n";

        $query .= "User-Agent: modPukiWiki/0.1\r\n";

        // Basic 認証用

        if (isset($arr['user']) and isset($arr['pass'])) {
            $query .= 'Authorization: Basic ' . base64_encode($arr['user'] . ':' . $arr['pass']) . "\r\n";
        }

        $query .= $headers;

        // POST 時は、urlencode したデータとする

        if ('POST' == mb_strtoupper($method)) {
            if (is_array($post)) {
                $POST = [];

                foreach ($post as $name => $val) {
                    $POST[] = $name . '=' . urlencode($val);
                }

                $data = implode('&', $POST);

                $query .= "Content-Type: application/x-www-form-urlencoded\r\n";

                $query .= 'Content-Length: ' . mb_strlen($data) . "\r\n";

                $query .= "\r\n";

                $query .= $data;
            } else {
                $query .= 'Content-Length: ' . mb_strlen($post) . "\r\n";

                $query .= "\r\n";

                $query .= $post;
            }
        } else {
            $query .= "\r\n";
        }

        $fp = fsockopen(
            $via_proxy ? $proxy_host : $arr['host'],
            $via_proxy ? $proxy_port : $arr['port'],
            $errno,
            $errstr,
            30
        );

        if (!$fp) {
            return [
                'query' => $query, // Query String
                'rc' => $errno, // エラー番号
                'header' => '',     // Header
                'data' => $errstr, // エラーメッセージ
            ];
        }

        fwrite($fp, $query);

        $response = '';

        while (!feof($fp)) {
            if ($_response = fgets($fp, 4096)) {
                $response .= $_response;
            } else {
                return [
                    'query' => $query, // Query String
                    'rc' => 408,    // エラー番号
                    'header' => '',     // Header
                    'data' => 'Request Time-out', // エラーメッセージ
                ];
            }
        }

        fclose($fp);

        $resp = explode("\r\n\r\n", $response, 2);

        $rccd = explode(' ', $resp[0], 3); // array('HTTP/1.1','200','OK\r\n...')

        return [
            'query' => $query,             // Query String
            'rc' => (int)$rccd[1], // Response Code
            'header' => $resp[0],           // Header
            'data' => $resp[1], // Data
        ];
    }

    // プロキシを経由する必要があるかどうか判定

    public function via_proxy($host)
    {
        $use_proxy = PukiWikiConfig::getParam('use_proxy');

        $no_proxy = PukiWikiConfig::getParam('no_proxy');

        static $ip_pattern = '/^(\d{1,3}\.\d{1,3}\.\d{1,3}\.\d{1,3})(?:\/(.+))?$/';

        if (!$use_proxy) {
            return false;
        }

        $ip = gethostbyname($host);

        $l_ip = ip2long($ip);

        $valid = (is_int($l_ip) and long2ip($l_ip) == $ip); // valid ip address

        foreach ($no_proxy as $network) {
            if ($valid and preg_match($ip_pattern, $network, $matches)) {
                $l_net = ip2long($matches[1]);

                $mask = array_key_exists(2, $matches) ? $matches[2] : 32;

                $mask = is_numeric($mask) ? pow(2, 32) - pow(2, 32 - $mask) : // "10.0.0.0/8"
                    ip2long($mask);                 // "10.0.0.0/255.0.0.0"
                if (($l_ip & $mask) == $l_net) {
                    return false;
                }
            } else {
                if (preg_match('/' . preg_quote($network, '/') . '/', $host)) {
                    return false;
                }
            }
        }

        return true;
    }

    // 共通リンクディレクトリの処理(該当フルネームを返す:ブラケットなし) by nao-pon

    public function get_real_pagename($page)
    {
        static $real_pages = [];

        $page = self::strip_bracket($page);

        if (isset($real_pages[$page])) {
            return $real_pages[$page];
        }

        $real_pages[$page] = false;

        foreach (PukiWikiConfig::getParam('wiki_common_dirs') as $dir) {
            $check = $dir . $page;

            if (self::is_page($check)) {
                $real_pages[$page] = $check;

                break;
            }
        }

        return $real_pages[$page];
    }

    //ページ名からページIDを求める(PukiWikiMod専用)

    public function get_pgid_by_name($page)
    {
        global $xoopsDB;

        static $page_id = [];

        $page = addslashes(self::strip_bracket($page));

        if (!empty($page_id[$page])) {
            return $page_id[$page];
        }

        $query = 'SELECT * FROM ' . $xoopsDB->prefix('pukiwikimod_pginfo') . " WHERE name='$page' LIMIT 1;";

        $res = $xoopsDB->query($query);

        if (!$res) {
            return 0;
        }

        $ret = $GLOBALS['xoopsDB']->fetchRow($res);

        $page_id[$page] = $ret[0];

        return $ret[0];
    }
}
