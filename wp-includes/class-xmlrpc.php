<?php
// -*-c++-*-
// by Edd Dumbill (C) 1999-2001
// <edd@usefulinc.com>
// $Id: class-xmlrpc.php,v 1.2 2006/03/19 20:05:55 mikhail Exp $

# additional fixes for case of missing xml extension file by Michel Valdrighi <m@tidakada.com>

// Copyright (c) 1999,2000,2001 Edd Dumbill.
// All rights reserved.
//
// Redistribution and use in source and binary forms, with or without
// modification, are permitted provided that the following conditions
// are met:
//
//    * Redistributions of source code must retain the above copyright
//      notice, this list of conditions and the following disclaimer.
//
//    * Redistributions in binary form must reproduce the above
//      copyright notice, this list of conditions and the following
//      disclaimer in the documentation and/or other materials provided
//      with the distribution.
//
//    * Neither the name of the "XML-RPC for PHP" nor the names of its
//      contributors may be used to endorse or promote products derived
//      from this software without specific prior written permission.
//
// THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS
// "AS IS" AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT
// LIMITED TO, THE IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS
// FOR A PARTICULAR PURPOSE ARE DISCLAIMED. IN NO EVENT SHALL THE
// REGENTS OR CONTRIBUTORS BE LIABLE FOR ANY DIRECT, INDIRECT,
// INCIDENTAL, SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES
// (INCLUDING, BUT NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR
// SERVICES; LOSS OF USE, DATA, OR PROFITS; OR BUSINESS INTERRUPTION)
// HOWEVER CAUSED AND ON ANY THEORY OF LIABILITY, WHETHER IN CONTRACT,
// STRICT LIABILITY, OR TORT (INCLUDING NEGLIGENCE OR OTHERWISE)
// ARISING IN ANY WAY OUT OF THE USE OF THIS SOFTWARE, EVEN IF ADVISED
// OF THE POSSIBILITY OF SUCH DAMAGE.

# b2 fix. some servers have stupid warnings
#error_reporting(0);

if (!function_exists('logIO')) {
    function logIO($m = '', $n = '')
    {
        return (true);
    }
}

if (!function_exists('xml_parser_create')) {
    // Win 32 fix. From: "Leo West" <lwest@imaginet.fr>

    // Fix for missing extension file. From: "Michel Valdrighi" <m@tidakada.com>

    if ($WINDIR) {
        if (@dl('php3_xml.dll')) {
            define('CANUSEXMLRPC', 1);
        } else {
            define('CANUSEXMLRPC', 0);
        }
    } else {
        if (@dl('xml.so')) {
            define('CANUSEXMLRPC', 1);
        } else {
            define('CANUSEXMLRPC', 0);
        }
    }
} else {
    define('CANUSEXMLRPC', 1);
}

if (CANUSEXMLRPC == 1) {
    $xmlrpcI4 = 'i4';

    $xmlrpcInt = 'int';

    $xmlrpcBoolean = 'boolean';

    $xmlrpcDouble = 'double';

    $xmlrpcString = 'string';

    $xmlrpcDateTime = 'dateTime.iso8601';

    $xmlrpcBase64 = 'base64';

    $xmlrpcArray = 'array';

    $xmlrpcStruct = 'struct';

    $xmlrpcTypes = [
        $xmlrpcI4 => 1,
        $xmlrpcInt => 1,
        $xmlrpcBoolean => 1,
        $xmlrpcString => 1,
        $xmlrpcDouble => 1,
        $xmlrpcDateTime => 1,
        $xmlrpcBase64 => 1,
        $xmlrpcArray => 2,
        $xmlrpcStruct => 3,
    ];

    $xmlEntities = [
        'amp' => '&',
        'quot' => '"',
        'lt' => '<',
        'gt' => '>',
        'apos' => "'",
    ];

    $xmlrpcerr['unknown_method'] = 1;

    $xmlrpcstr['unknown_method'] = 'Unknown method';

    $xmlrpcerr['invalid_return'] = 2;

    $xmlrpcstr['invalid_return'] = 'Invalid return payload: enabling debugging to examine incoming payload';

    $xmlrpcerr['incorrect_params'] = 3;

    $xmlrpcstr['incorrect_params'] = 'Incorrect parameters passed to method';

    $xmlrpcerr['introspect_unknown'] = 4;

    $xmlrpcstr['introspect_unknown'] = "Can't introspect: method unknown";

    $xmlrpcerr['http_error'] = 5;

    $xmlrpcstr['http_error'] = "Didn't receive 200 OK from remote server.";

    $xmlrpcerr['no_data'] = 6;

    $xmlrpcstr['no_data'] = 'No data received from server.';

    $xmlrpcerr['no_ssl'] = 7;

    $xmlrpcstr['no_ssl'] = 'No SSL support compiled in.';

    $xmlrpcerr['curl_fail'] = 8;

    $xmlrpcstr['curl_fail'] = 'CURL error';

    $xmlrpc_defencoding = 'UTF-8';

    $xmlrpcName = 'XML-RPC for PHP';

    $xmlrpcVersion = '1.02';

    // let user errors start at 800

    $xmlrpcerruser = 800;

    // let XML parse errors start at 100

    $xmlrpcerrxml = 100;

    // formulate backslashes for escaping regexp

    $xmlrpc_backslash = chr(92) . chr(92);

    // used to store state during parsing

    // quick explanation of components:

    //   st - used to build up a string for evaluation

    //   ac - used to accumulate values

    //   qt - used to decide if quotes are needed for evaluation

    //   cm - used to denote struct or array (comma needed)

    //   isf - used to indicate a fault

    //   lv - used to indicate "looking for a value": implements

    //        the logic to allow values with no types to be strings

    //   params - used to store parameters in method calls

    //   method - used to store method name

    $_xh = [];

    function xmlrpc_entity_decode($string)
    {
        $top = preg_split('&', $string);

        $op = '';

        $i = 0;

        while ($i < count($top)) {
            if (preg_match('^([#a-zA-Z0-9]+);', $top[$i], $regs)) {
                $op .= preg_replace(
                    '^[#a-zA-Z0-9]+;',
                    xmlrpc_lookup_entity($regs[1]),
                    $top[$i]
                );
            } else {
                if (0 == $i) {
                    $op = $top[$i];
                } else {
                    $op .= '&' . $top[$i];
                }
            }

            $i++;
        }

        return $op;
    }

    function xmlrpc_lookup_entity($ent)
    {
        global $xmlEntities;

        if (isset($xmlEntities[mb_strtolower($ent)])) {
            return $xmlEntities[mb_strtolower($ent)];
        }

        if (preg_match('^#([0-9]+)$', $ent, $regs)) {
            return chr($regs[1]);
        }

        return '?';
    }

    function xmlrpc_se($parser, $name, $attrs)
    {
        global $_xh, $xmlrpcDateTime, $xmlrpcString;

        switch ($name) {
            case 'STRUCT':
            case 'ARRAY':
                $_xh[$parser]['st'] .= 'array(';
                $_xh[$parser]['cm']++;
                // this last line turns quoting off
                // this means if we get an empty array we'll
                // simply get a bit of whitespace in the eval
                $_xh[$parser]['qt'] = 0;
                break;
            case 'NAME':
                $_xh[$parser]['st'] .= "'";
                $_xh[$parser]['ac'] = '';
                break;
            case 'FAULT':
                $_xh[$parser]['isf'] = 1;
                break;
            case 'PARAM':
                $_xh[$parser]['st'] = '';
                break;
            case 'VALUE':
                $_xh[$parser]['st'] .= 'new xmlrpcval(';
                $_xh[$parser]['vt'] = $xmlrpcString;
                $_xh[$parser]['ac'] = '';
                $_xh[$parser]['qt'] = 0;
                $_xh[$parser]['lv'] = 1;
                // look for a value: if this is still 1 by the
                // time we reach the first data segment then the type is string
                // by implication and we need to add in a quote
                break;
            case 'I4':
            case 'INT':
            case 'STRING':
            case 'BOOLEAN':
            case 'DOUBLE':
            case 'DATETIME.ISO8601':
            case 'BASE64':
                $_xh[$parser]['ac'] = ''; // reset the accumulator

                if ('DATETIME.ISO8601' == $name || 'STRING' == $name) {
                    $_xh[$parser]['qt'] = 1;

                    if ('DATETIME.ISO8601' == $name) {
                        $_xh[$parser]['vt'] = $xmlrpcDateTime;
                    }
                } elseif ('BASE64' == $name) {
                    $_xh[$parser]['qt'] = 2;
                } else {
                    // No quoting is required here -- but

                    // at the end of the element we must check

                    // for data format errors.

                    $_xh[$parser]['qt'] = 0;
                }
                break;
            case 'MEMBER':
                $_xh[$parser]['ac'] = '';
                break;
            default:
                break;
        }

        if ('VALUE' != $name) {
            $_xh[$parser]['lv'] = 0;
        }
    }

    function xmlrpc_ee($parser, $name)
    {
        global $_xh, $xmlrpcTypes, $xmlrpcString;

        switch ($name) {
            case 'STRUCT':
            case 'ARRAY':
                if ($_xh[$parser]['cm'] && ',' == mb_substr($_xh[$parser]['st'], -1)) {
                    $_xh[$parser]['st'] = mb_substr($_xh[$parser]['st'], 0, -1);
                }
                $_xh[$parser]['st'] .= ')';
                $_xh[$parser]['vt'] = mb_strtolower($name);
                $_xh[$parser]['cm']--;
                break;
            case 'NAME':
                $_xh[$parser]['st'] .= $_xh[$parser]['ac'] . "' => ";
                break;
            case 'BOOLEAN':
                // special case here: we translate boolean 1 or 0 into PHP
                // constants true or false
                if ('1' == $_xh[$parser]['ac']) {
                    $_xh[$parser]['ac'] = 'true';
                } else {
                    $_xh[$parser]['ac'] = 'false';
                }
                $_xh[$parser]['vt'] = mb_strtolower($name);
            // Drop through intentionally.
            // no break
            case 'I4':
            case 'INT':
            case 'STRING':
            case 'DOUBLE':
            case 'DATETIME.ISO8601':
            case 'BASE64':
                if (1 == $_xh[$parser]['qt']) {
                    // we use double quotes rather than single so backslashification works OK

                    $_xh[$parser]['st'] .= '"' . $_xh[$parser]['ac'] . '"';
                } elseif (2 == $_xh[$parser]['qt']) {
                    $_xh[$parser]['st'] .= "base64_decode('" . $_xh[$parser]['ac'] . "')";
                } elseif ('BOOLEAN' == $name) {
                    $_xh[$parser]['st'] .= $_xh[$parser]['ac'];
                } else {
                    // we have an I4, INT or a DOUBLE

                    // we must check that only 0123456789-.<space> are characters here

                    if (!preg_match("^\-?[0123456789 \t\.]+$", $_xh[$parser]['ac'])) {
                        // TODO: find a better way of throwing an error

                        // than this!

                        error_log('XML-RPC: non numeric value received in INT or DOUBLE');

                        $_xh[$parser]['st'] .= 'ERROR_NON_NUMERIC_FOUND';
                    } else {
                        // it's ok, add it on

                        $_xh[$parser]['st'] .= $_xh[$parser]['ac'];
                    }
                }
                $_xh[$parser]['ac'] = '';
                $_xh[$parser]['qt'] = 0;
                $_xh[$parser]['lv'] = 3; // indicate we've found a value
                break;
            case 'VALUE':
                // deal with a string value
                if (mb_strlen($_xh[$parser]['ac']) > 0
                    && $_xh[$parser]['vt'] == $xmlrpcString) {
                    $_xh[$parser]['st'] .= '"' . $_xh[$parser]['ac'] . '"';
                }
                // This if() detects if no scalar was inside <VALUE></VALUE>
                // and pads an empty "".
                if ('(' == $_xh[$parser]['st'][mb_strlen($_xh[$parser]['st']) - 1]) {
                    $_xh[$parser]['st'] .= '""';
                }
                $_xh[$parser]['st'] .= ", '" . $_xh[$parser]['vt'] . "')";
                if ($_xh[$parser]['cm']) {
                    $_xh[$parser]['st'] .= ',';
                }
                break;
            case 'MEMBER':
                $_xh[$parser]['ac'] = '';
                $_xh[$parser]['qt'] = 0;
                break;
            case 'DATA':
                $_xh[$parser]['ac'] = '';
                $_xh[$parser]['qt'] = 0;
                break;
            case 'PARAM':
                $_xh[$parser]['params'][] = $_xh[$parser]['st'];
                break;
            case 'METHODNAME':
                $_xh[$parser]['method'] = preg_replace(
                    "^[\n\r\t ]+",
                    '',
                    $_xh[$parser]['ac']
                );
                break;
            case 'BOOLEAN':
                // special case here: we translate boolean 1 or 0 into PHP
                // constants true or false
                if ('1' == $_xh[$parser]['ac']) {
                    $_xh[$parser]['ac'] = 'true';
                } else {
                    $_xh[$parser]['ac'] = 'false';
                }
                $_xh[$parser]['vt'] = mb_strtolower($name);
                break;
            default:
                break;
        }

        // if it's a valid type name, set the type

        if (isset($xmlrpcTypes[mb_strtolower($name)])) {
            $_xh[$parser]['vt'] = mb_strtolower($name);
        }
    }

    function xmlrpc_cd($parser, $data)
    {
        global $_xh, $xmlrpc_backslash;

        //if (preg_match("^[\n\r \t]+$", $data)) return;

        // print "adding [${data}]\n";

        if (3 != $_xh[$parser]['lv']) {
            // "lookforvalue==3" means that we've found an entire value

            // and should discard any further character data

            if (1 == $_xh[$parser]['lv']) {
                // if we've found text and we're just in a <value> then

                // turn quoting on, as this will be a string

                $_xh[$parser]['qt'] = 1;

                // and say we've found a value

                $_xh[$parser]['lv'] = 2;
            }

            // replace characters that eval would

            // do special things with

            $_xh[$parser]['ac'] .= str_replace(
                '$',
                '\$',
                str_replace(
                    '"',
                    '\"',
                    str_replace(
                        chr(92),
                        $xmlrpc_backslash,
                        $data
                    )
                )
            );
        }
    }

    function xmlrpc_dh($parser, $data)
    {
        global $_xh;

        if ('&' == mb_substr($data, 0, 1) && ';' == mb_substr($data, -1, 1)) {
            if (1 == $_xh[$parser]['lv']) {
                $_xh[$parser]['qt'] = 1;

                $_xh[$parser]['lv'] = 2;
            }

            $_xh[$parser]['ac'] .= str_replace(
                '$',
                '\$',
                str_replace(
                    '"',
                    '\"',
                    str_replace(
                        chr(92),
                        $xmlrpc_backslash,
                        $data
                    )
                )
            );
        }
    }

    class xmlrpc_client
    {
        public $path;

        public $server;

        public $port;

        public $errno;

        public $errstring;

        public $debug = 0;

        public $username = '';

        public $password = '';

        public $cert = '';

        public $certpass = '';

        public function __construct($path, $server, $port = 0)
        {
            $this->port = $port;

            $this->server = $server;

            $this->path = $path;
        }

        public function setDebug($in)
        {
            if ($in) {
                $this->debug = 1;
            } else {
                $this->debug = 0;
            }
        }

        public function setCredentials($u, $p)
        {
            $this->username = $u;

            $this->password = $p;
        }

        public function setCertificate($cert, $certpass)
        {
            $this->cert = $cert;

            $this->certpass = $certpass;
        }

        public function send($msg, $timeout = 0, $method = 'http')
        {
            // where msg is an xmlrpcmsg

            $msg->debug = $this->debug;

            if ('https' == $method) {
                return $this->sendPayloadHTTPS(
                    $msg,
                    $this->server,
                    $this->port,
                    $timeout,
                    $this->username,
                    $this->password,
                    $this->cert,
                    $this->certpass
                );
            }
  

            return $this->sendPayloadHTTP10(
                    $msg,
                    $this->server,
                    $this->port,
                    $timeout,
                    $this->username,
                    $this->password
                );
        }

        public function sendPayloadHTTP10(
            $msg,
            $server,
            $port,
            $timeout = 0,
            $username = '',
            $password = ''
        ) {
            if (0 == $port) {
                $port = 80;
            }

            if ($timeout > 0) {
                $fp = @fsockopen(
                    $server,
                    $port,
                    $this->errno,
                    $this->errstr,
                    $timeout
                );
            }

            #      $fp=@fsockopen($server, $port,

            #		    &$this->errno, &$this->errstr, $timeout);

            else {
                $fp = @fsockopen(
                    $server,
                    $port,
                    $this->errno,
                    $this->errstr
                );
            }

            #      $fp=@fsockopen($server, $port,

            #		    &$this->errno, &$this->errstr);

            if (!$fp) {
                return 0;
            }

            // Only create the payload if it was not created previously

            if (empty($msg->payload)) {
                $msg->createPayload();
            }

            // thanks to Grant Rauscher <grant7@firstworld.net>

            // for this

            $credentials = '';

            if ('' != $username) {
                $credentials = 'Authorization: Basic ' . base64_encode($username . ':' . $password) . "\r\n";
            }

            $op = 'POST ' . $this->path . " HTTP/1.0\r\nUser-Agent: PHP XMLRPC 1.0\r\n" . 'Host: ' . $this->server . "\r\n" . $credentials . "Content-Type: text/xml\r\nContent-Length: " . mb_strlen($msg->payload) . "\r\n\r\n" . $msg->payload;

            stream_set_timeout($fp, 10);

            if (!fwrite($fp, $op, mb_strlen($op))) {
                $this->errstr = 'Write error';

                return 0;
            }

            $resp = $msg->parseResponseFile($fp);

            fclose($fp);

            return $resp;
        }

        // contributed by Justin Miller <justin@voxel.net>

        // requires curl to be built into PHP

        public function sendPayloadHTTPS(
            $msg,
            $server,
            $port,
            $timeout = 0,
            $username = '',
            $password = '',
            $cert = '',
            $certpass = ''
        ) {
            global $xmlrpcerr, $xmlrpcstr;

            if (0 == $port) {
                $port = 443;
            }

            // Only create the payload if it was not created previously

            if (empty($msg->payload)) {
                $msg->createPayload();
            }

            if (!function_exists('curl_init')) {
                $r = new xmlrpcresp(
                    0,
                    $xmlrpcerr['no_ssl'],
                    $xmlrpcstr['no_ssl']
                );

                return $r;
            }

            $curl = curl_init(
                'https://' . $server . ':' . $port . $this->path
            );

            curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);

            // results into variable

            if ($this->debug) {
                curl_setopt($curl, CURLOPT_VERBOSE, 1);
            }

            curl_setopt($curl, CURLOPT_USERAGENT, 'PHP XMLRPC 1.0');

            // required for XMLRPC

            curl_setopt($curl, CURLOPT_POST, 1);

            // post the data

            curl_setopt($curl, CURLOPT_POSTFIELDS, $msg->payload);

            // the data

            curl_setopt($curl, CURLOPT_HEADER, 1);

            // return the header too

            curl_setopt($curl, CURLOPT_HTTPHEADER, ['Content-Type: text/xml']);

            // required for XMLRPC

            if ($timeout) {
                curl_setopt(
                    $curl,
                    CURLOPT_TIMEOUT,
                    1 == $timeout ? 1 : $timeout - 1
                );
            }

            // timeout is borked

            if ($username && $password) {
                curl_setopt(
                    $curl,
                    CURLOPT_USERPWD,
                    "$username:$password"
                );
            }

            // set auth stuff

            if ($cert) {
                curl_setopt($curl, CURLOPT_SSLCERT, $cert);
            }

            // set cert file

            if ($certpass) {
                curl_setopt(
                    $curl,
                    CURLOPT_SSLCERTPASSWD,
                    $certpass
                );
            }

            // set cert password

            $result = curl_exec($curl);

            if (!$result) {
                $resp = new xmlrpcresp(
                    0,
                    $xmlrpcerr['curl_fail'],
                    $xmlrpcstr['curl_fail'] . ': ' . curl_error($curl)
                );
            } else {
                $resp = $msg->parseResponse($result);
            }

            curl_close($curl);

            return $resp;
        }
    } // end class xmlrpc_client

    class xmlrpcresp
    {
        public $xv;

        public $fn;

        public $fs;

        public $hdrs;

        public function __construct($val, $fcode = 0, $fstr = '')
        {
            if (0 != $fcode) {
                $this->xv = 0;

                $this->fn = $fcode;

                $this->fs = trim(htmlspecialchars($fstr, ENT_QUOTES | ENT_HTML5));

                logIO('O', $this->fs);
            } else {
                $this->xv = $val;

                $this->fn = 0;
            }
        }

        public function faultCode()
        {
            return $this->fn ?? 0;
        }

        public function faultString()
        {
            return $this->fs;
        }

        public function value()
        {
            return $this->xv;
        }

        public function serialize()
        {
            $rs = '<methodResponse>';

            if ($this->fn) {
                $rs .= '<fault>
  <value>
    <struct>
      <member>
        <name>faultCode</name>
        <value><int>' . $this->fn . '</int></value>
      </member>
      <member>
        <name>faultString</name>
        <value><string>' . $this->fs . '</string></value>
      </member>
    </struct>
  </value>
</fault>';
            } else {
                $rs .= '<params><param>' . $this->xv->serialize() . '</param></params>';
            }

            $rs .= '</methodResponse>';

            /* begin Logging
            $f=fopen("xmlrpc/xmlrpc.log","a+");
            fwrite($f, date("Ymd H:i:s")."\n\nResponse:\n\n".$rs);
            fclose($f);
            end Logging */

            logIO('O', $rs);

            return $rs;
        }
    }

    class xmlrpcmsg
    {
        public $payload;

        public $methodname;

        public $params = [];

        public $debug = 0;

        public function __construct($meth, $pars = 0)
        {
            $this->methodname = $meth;

            if (is_array($pars) && count($pars) > 0) {
                for ($i = 0, $iMax = count($pars); $i < $iMax; $i++) {
                    $this->addParam($pars[$i]);
                }
            }
        }

        public function xml_header()
        {
            return '<?xml version="1.0"?' . ">\n<methodCall>\n";
        }

        public function xml_footer()
        {
            return "</methodCall>\n";
        }

        public function createPayload()
        {
            $this->payload = $this->xml_header();

            $this->payload .= '<methodName>' . $this->methodname . "</methodName>\n";

            //	if (sizeof($this->params)) {

            $this->payload .= "<params>\n";

            for ($i = 0, $iMax = count($this->params); $i < $iMax; $i++) {
                $p = $this->params[$i];

                $this->payload .= "<param>\n" . $p->serialize() . "</param>\n";
            }

            $this->payload .= "</params>\n";

            // }

            $this->payload .= $this->xml_footer();

            $this->payload = str_replace("\n", "\r\n", $this->payload);
        }

        public function method($meth = '')
        {
            if ('' != $meth) {
                $this->methodname = $meth;
            }

            return $this->methodname;
        }

        public function serialize()
        {
            $this->createPayload();

            logIO('O', $this->payload);

            return $this->payload;
        }

        public function addParam($par)
        {
            $this->params[] = $par;
        }

        public function getParam($i)
        {
            return $this->params[$i];
        }

        public function getNumParams()
        {
            return count($this->params);
        }

        public function parseResponseFile($fp)
        {
            $ipd = '';

            while ($data = fread($fp, 32768)) {
                $ipd .= $data;
            }

            return $this->parseResponse($ipd);
        }

        public function parseResponse($data = '')
        {
            global $_xh, $xmlrpcerr, $xmlrpcstr;

            global $xmlrpc_defencoding;

            $parser = xml_parser_create($xmlrpc_defencoding);

            $_xh[$parser] = [];

            $_xh[$parser]['st'] = '';

            $_xh[$parser]['cm'] = 0;

            $_xh[$parser]['isf'] = 0;

            $_xh[$parser]['ac'] = '';

            $_xh[$parser]['qt'] = '';

            $_xh[$parser]['ha'] = '';

            $_xh[$parser]['ac'] = '';

            xml_parser_set_option($parser, XML_OPTION_CASE_FOLDING, true);

            xml_set_elementHandler($parser, 'xmlrpc_se', 'xmlrpc_ee');

            xml_set_character_dataHandler($parser, 'xmlrpc_cd');

            xml_set_defaultHandler($parser, 'xmlrpc_dh');

            $xmlrpc_value = new xmlrpcval();

            if ($this->debug) {
                print "<PRE>---GOT---\n" . htmlspecialchars($data, ENT_QUOTES | ENT_HTML5) . "\n---END---\n</PRE>";
            }

            if ('' == $data) {
                error_log('No response received from server.');

                $r = new xmlrpcresp(
                    0,
                    $xmlrpcerr['no_data'],
                    $xmlrpcstr['no_data']
                );

                xml_parser_free($parser);

                return $r;
            }

            // see if we got an HTTP 200 OK, else bomb

            // but only do this if we're using the HTTP protocol.

            if (preg_match('^HTTP', $data)
                && !preg_match("^HTTP/[0-9\.]+ 200 ", $data)) {
                $errstr = mb_substr($data, 0, mb_strpos($data, "\n") - 1);

                error_log('HTTP error, got response: ' . $errstr);

                $r = new xmlrpcresp(
                    0,
                    $xmlrpcerr['http_error'],
                    $xmlrpcstr['http_error'] . ' (' . $errstr . ')'
                );

                xml_parser_free($parser);

                return $r;
            }

            // if using HTTP, then gotta get rid of HTTP headers here

            // and we store them in the 'ha' bit of our data array

            if (preg_match('^HTTP', $data)) {
                $ar = explode("\r\n", $data);

                $newdata = '';

                $hdrfnd = 0;

                for ($i = 0, $iMax = count($ar); $i < $iMax; $i++) {
                    if (!$hdrfnd) {
                        if (mb_strlen($ar[$i]) > 0) {
                            $_xh[$parser]['ha'] .= $ar[$i] . "\r\n";
                        } else {
                            $hdrfnd = 1;
                        }
                    } else {
                        $newdata .= $ar[$i] . "\r\n";
                    }
                }

                $data = $newdata;
            }

            if (!xml_parse($parser, $data, count($data))) {
                // thanks to Peter Kocks <peter.kocks@baygate.com>

                if (1 == (xml_get_current_line_number($parser))) {
                    $errstr = 'XML error at line 1, check URL';
                } else {
                    $errstr = sprintf(
                        'XML error: %s at line %d',
                        xml_error_string(xml_get_error_code($parser)),
                        xml_get_current_line_number($parser)
                    );
                }

                error_log($errstr);

                $r = new xmlrpcresp(
                    0,
                    $xmlrpcerr['invalid_return'],
                    $xmlrpcstr['invalid_return']
                );

                xml_parser_free($parser);

                return $r;
            }

            xml_parser_free($parser);

            if ($this->debug) {
                print '<PRE>---EVALING---[' . mb_strlen($_xh[$parser]['st']) . " chars]---\n" . htmlspecialchars($_xh[$parser]['st'], ENT_QUOTES | ENT_HTML5) . ";\n---END---</PRE>";
            }

            if (0 == mb_strlen($_xh[$parser]['st'])) {
                // then something odd has happened

                // and it's time to generate a client side error

                // indicating something odd went on

                $r = new xmlrpcresp(
                    0,
                    $xmlrpcerr['invalid_return'],
                    $xmlrpcstr['invalid_return']
                );
            } else {
                eval('$v=' . $_xh[$parser]['st'] . '; $allOK=1;');

                if ($_xh[$parser]['isf']) {
                    $f = $v->structmem('faultCode');

                    $fs = $v->structmem('faultString');

                    $r = new xmlrpcresp(
                        $v,
                        $f->scalarval(),
                        $fs->scalarval()
                    );
                } else {
                    $r = new xmlrpcresp($v);
                }
            }

            $r->hdrs = preg_split("\r?\n", $_xh[$parser]['ha']);

            return $r;
        }
    }

    class xmlrpcval
    {
        public $me = [];

        public $mytype = 0;

        public function __construct($val = -1, $type = '')
        {
            global $xmlrpcTypes;

            $this->me = [];

            $this->mytype = 0;

            if (-1 != $val || '' != $type) {
                if ('' == $type) {
                    $type = 'string';
                }

                if (1 == $xmlrpcTypes[$type]) {
                    $this->addScalar($val, $type);
                } elseif (2 == $xmlrpcTypes[$type]) {
                    $this->addArray($val);
                } elseif (3 == $xmlrpcTypes[$type]) {
                    $this->addStruct($val);
                }
            }
        }

        public function addScalar($val, $type = 'string')
        {
            global $xmlrpcTypes, $xmlrpcBoolean;

            if (1 == $this->mytype) {
                echo '<B>xmlrpcval</B>: scalar can have only one value<br>';

                return 0;
            }

            $typeof = $xmlrpcTypes[$type];

            if (1 != $typeof) {
                echo "<B>xmlrpcval</B>: not a scalar type (${typeof})<br>";

                return 0;
            }

            if ($type == $xmlrpcBoolean) {
                if (0 == strcasecmp($val, 'true')
                    || 1 == $val
                    || (true === $val
                        && strcasecmp($val, 'false'))) {
                    $val = 1;
                } else {
                    $val = 0;
                }
            }

            if (2 == $this->mytype) {
                // we're adding to an array here

                $ar = $this->me['array'];

                $ar[] = new self($val, $type);

                $this->me['array'] = $ar;
            } else {
                // a scalar, so set the value and remember we're scalar

                $this->me[$type] = $val;

                $this->mytype = $typeof;
            }

            return 1;
        }

        public function addArray($vals)
        {
            global $xmlrpcTypes;

            if (0 != $this->mytype) {
                echo '<B>xmlrpcval</B>: already initialized as a [' . $this->kindOf() . ']<br>';

                return 0;
            }

            $this->mytype = $xmlrpcTypes['array'];

            $this->me['array'] = $vals;

            return 1;
        }

        public function addStruct($vals)
        {
            global $xmlrpcTypes;

            if (0 != $this->mytype) {
                echo '<B>xmlrpcval</B>: already initialized as a [' . $this->kindOf() . ']<br>';

                return 0;
            }

            $this->mytype = $xmlrpcTypes['struct'];

            $this->me['struct'] = $vals;

            return 1;
        }

        public function dump($ar)
        {
            reset($ar);

            while (list($key, $val) = each($ar)) {
                echo "$key => $val<br>";

                if ('array' == $key) {
                    while (list($key2, $val2) = each($val)) {
                        echo "-- $key2 => $val2<br>";
                    }
                }
            }
        }

        public function kindOf()
        {
            switch ($this->mytype) {
                case 3:
                    return 'struct';
                    break;
                case 2:
                    return 'array';
                    break;
                case 1:
                    return 'scalar';
                    break;
                default:
                    return 'undef';
            }
        }

        public function serializedata($typ, $val)
        {
            $rs = '';

            global $xmlrpcTypes, $xmlrpcBase64, $xmlrpcString, $xmlrpcBoolean;

            switch ($xmlrpcTypes[$typ]) {
                case 3:
                    // struct
                    $rs .= "<struct>\n";
                    reset($val);
                    while (list($key2, $val2) = each($val)) {
                        $rs .= "<member><name>${key2}</name>\n";

                        $rs .= $this->serializeval($val2);

                        $rs .= "</member>\n";
                    }
                    $rs .= '</struct>';
                    break;
                case 2:
                    // array
                    $rs .= "<array>\n<data>\n";
                    for ($i = 0, $iMax = count($val); $i < $iMax; $i++) {
                        $rs .= $this->serializeval($val[$i]);
                    }
                    $rs .= "</data>\n</array>";
                    break;
                case 1:
                    switch ($typ) {
                        case $xmlrpcBase64:
                            $rs .= "<${typ}>" . base64_encode($val) . "</${typ}>";
                            break;
                        case $xmlrpcBoolean:
                            $rs .= "<${typ}>" . ($val ? '1' : '0') . "</${typ}>";
                            break;
                        case $xmlrpcString:
                            $rs .= "<${typ}>" . htmlspecialchars($val, ENT_QUOTES | ENT_HTML5) . "</${typ}>";
                            break;
                        default:
                            $rs .= "<${typ}>${val}</${typ}>";
                    }
                    break;
                default:
                    break;
            }

            return $rs;
        }

        public function serialize()
        {
            return $this->serializeval($this);
        }

        public function serializeval($o)
        {
            global $xmlrpcTypes;

            $rs = '';

            $ar = $o->me;

            reset($ar);

            [$typ, $val] = each($ar);

            $rs .= '<value>';

            $rs .= $this->serializedata($typ, $val);

            $rs .= "</value>\n";

            return $rs;
        }

        public function structmem($m)
        {
            $nv = $this->me['struct'][$m];

            return $nv;
        }

        public function structreset()
        {
            reset($this->me['struct']);
        }

        public function structeach()
        {
            return each($this->me['struct']);
        }

        public function getval()
        {
            // UNSTABLE

            global $xmlrpcBoolean, $xmlrpcBase64;

            reset($this->me);

            [$a, $b] = each($this->me);

            // contributed by I Sofer, 2001-03-24

            // add support for nested arrays to scalarval

            // i've created a new method here, so as to

            // preserve back compatibility

            if (is_array($b)) {
                foreach ($b as $id => $cont) {
                    $b[$id] = $cont->scalarval();
                }
            }

            // add support for structures directly encoding php objects

            if (is_object($b)) {
                $t = get_object_vars($b);

                foreach ($t as $id => $cont) {
                    $t[$id] = $cont->scalarval();
                }

                foreach ($t as $id => $cont) {
                    eval('$b->' . $id . ' = $cont;');
                }
            }

            // end contrib

            return $b;
        }

        public function scalarval()
        {
            global $xmlrpcBoolean, $xmlrpcBase64;

            reset($this->me);

            [$a, $b] = each($this->me);

            return $b;
        }

        public function scalartyp()
        {
            global $xmlrpcI4, $xmlrpcInt;

            reset($this->me);

            [$a, $b] = each($this->me);

            if ($a == $xmlrpcI4) {
                $a = $xmlrpcInt;
            }

            return $a;
        }

        public function arraymem($m)
        {
            $nv = $this->me['array'][$m];

            return $nv;
        }

        public function arraysize()
        {
            reset($this->me);

            [$a, $b] = each($this->me);

            return count($b);
        }
    }

    // date helpers

    function iso8601_encode($timet, $utc = 0)
    {
        // return an ISO8601 encoded string

        // really, timezones ought to be supported

        // but the XML-RPC spec says:

        //

        // "Don't assume a timezone. It should be specified by the server in its

        // documentation what assumptions it makes about timezones."

        //

        // these routines always assume localtime unless

        // $utc is set to 1, in which case UTC is assumed

        // and an adjustment for locale is made when encoding

        if (!$utc) {
            $t = strftime('%Y%m%dT%H:%M:%S', $timet);
        } else {
            if (function_exists('gmstrftime')) {
                // gmstrftime doesn't exist in some versions

                // of PHP

                $t = gmstrftime('%Y%m%dT%H:%M:%S', $timet);
            } else {
                $t = strftime('%Y%m%dT%H:%M:%S', $timet - date('Z'));
            }
        }

        return $t;
    }

    function iso8601_decode($idate, $utc = 0)
    {
        // return a timet in the localtime, or UTC

        $t = 0;

        if (preg_match(
            '([0-9]{4})([0-9]{2})([0-9]{2})T([0-9]{2}):([0-9]{2}):([0-9]{2})',
            $idate,
            $regs
        )) {
            if ($utc) {
                $t = gmmktime($regs[4], $regs[5], $regs[6], $regs[2], $regs[3], $regs[1]);
            } else {
                $t = mktime($regs[4], $regs[5], $regs[6], $regs[2], $regs[3], $regs[1]);
            }
        }

        return $t;
    }

    /****************************************************************
     * xmlrpc_decode takes a message in PHP xmlrpc object format and *
     * tranlates it into native PHP types.                           *
     *                                                               *
     * author: Dan Libby (dan@libby.com)                             *
     ***************************************************************
     * @param $xmlrpc_val
     * @return array
     */

    //if (!function_exists('xmlrpc_decode')) {

    function xmlrpc_decode1($xmlrpc_val)
    {
        $kind = $xmlrpc_val->kindOf();

        if ('scalar' == $kind) {
            return $xmlrpc_val->scalarval();
        } elseif ('array' == $kind) {
            $size = $xmlrpc_val->arraysize();

            $arr = [];

            for ($i = 0; $i < $size; $i++) {
                $arr[] = xmlrpc_decode1($xmlrpc_val->arraymem($i));
            }

            return $arr;
        } elseif ('struct' == $kind) {
            $xmlrpc_val->structreset();

            $arr = [];

            while (list($key, $value) = $xmlrpc_val->structeach()) {
                $arr[$key] = xmlrpc_decode1($value);
            }

            return $arr;
        }
    }

    //}

    /****************************************************************
     * xmlrpc_encode takes native php types and encodes them into    *
     * xmlrpc PHP object format.                                     *
     * BUG: All sequential arrays are turned into structs.  I don't  *
     * know of a good way to determine if an array is sequential     *
     * only.                                                         *
     *                                                               *
     * feature creep -- could support more types via optional type   *
     * argument.                                                     *
     *                                                               *
     * author: Dan Libby (dan@libby.com)                             *
     ***************************************************************
     * @param $php_val
     * @return \xmlrpcval
     */

    //if (!function_exists('xmlrpc_encode')) {

    function xmlrpc_encode1($php_val)
    {
        global $xmlrpcInt;

        global $xmlrpcDouble;

        global $xmlrpcString;

        global $xmlrpcArray;

        global $xmlrpcStruct;

        global $xmlrpcBoolean;

        $type = gettype($php_val);

        $xmlrpc_val = new xmlrpcval();

        switch ($type) {
            case 'array':
            case 'object':
                $arr = [];
                while (list($k, $v) = each($php_val)) {
                    $arr[$k] = xmlrpc_encode1($v);
                }
                $xmlrpc_val->addStruct($arr);
                break;
            case 'integer':
                $xmlrpc_val->addScalar($php_val, $xmlrpcInt);
                break;
            case 'double':
                $xmlrpc_val->addScalar($php_val, $xmlrpcDouble);
                break;
            case 'string':
                $xmlrpc_val->addScalar($php_val, $xmlrpcString);
                break;
            // <G_Giunta_2001-02-29>
            // Add support for encoding/decoding of booleans, since they are supported in PHP
            case 'boolean':
                $xmlrpc_val->addScalar($php_val, $xmlrpcBoolean);
                break;
            // </G_Giunta_2001-02-29>
            case 'unknown type':
            default:
                // giancarlo pinerolo <ping@alt.it>
                // it has to return
                // an empty object in case (which is already
                // at this point), not a boolean.
                break;
        }

        return $xmlrpc_val;
    }

    //}
}
?>
