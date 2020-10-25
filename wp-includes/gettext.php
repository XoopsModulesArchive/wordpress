<?php
/*
   Copyright (c) 2003 Danilo Segan <danilo@kvota.net>.
   Copyright (c) 2005 Nico Kaiser <nico@siriux.net>

   This file is part of PHP-gettext.

   PHP-gettext is free software; you can redistribute it and/or modify
   it under the terms of the GNU General Public License as published by
   the Free Software Foundation; either version 2 of the License, or
   (at your option) any later version.

   PHP-gettext is distributed in the hope that it will be useful,
   but WITHOUT ANY WARRANTY; without even the implied warranty of
   MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
   GNU General Public License for more details.

   You should have received a copy of the GNU General Public License
   along with PHP-gettext; if not, write to the Free Software
   Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA

*/

/**
 * Provides a simple gettext replacement that works independently from
 * the system's gettext abilities.
 * It can read MO files and use them for translating strings.
 * The files are passed to gettext_reader as a Stream (see streams.php)
 *
 * This version has the ability to cache all strings and translations to
 * speed up the string lookup.
 * While the cache is enabled by default, it can be switched off with the
 * second parameter in the constructor (e.g. whenusing very large MO files
 * that you don't want to keep in memory)
 */
class gettext_reader
{
    //public:
    public $error = 0; // public variable that holds error code (0 if no error)
    //private:
    public $BYTEORDER = 0;        // 0: low endian, 1: big endian
    public $STREAM = null;

    public $short_circuit = false;

    public $enable_cache = false;

    public $originals = null;      // offset of original table
    public $translations = null;    // offset of translation table
    public $pluralheader = null;    // cache header field for plural forms
    public $total = 0;          // total string count
    public $table_originals = null;  // table for original strings (offsets)
    public $table_translations = null;  // table for translated strings (offsets)
    public $cache_translations = null;  // original -> translation mapping
    /* Methods */

    /**
     * Reads a 32bit Integer from the Stream
     *
     * @return int from the Stream
     */

    public function readint()
    {
        if (0 == $this->BYTEORDER) {
            // low endian

            return array_shift(unpack('V', $this->STREAM->read(4)));
        }  

        // big endian

        return array_shift(unpack('N', $this->STREAM->read(4)));
    }

    /**
     * Reads an array of Integers from the Stream
     *
     * @param mixed $count
     * @return array of Integers
     */

    public function readintarray($count)
    {
        if (0 == $this->BYTEORDER) {
            // low endian

            return unpack('V' . $count, $this->STREAM->read(4 * $count));
        }  

        // big endian

        return unpack('N' . $count, $this->STREAM->read(4 * $count));
    }

    /**
     * Constructor
     *
     * @param mixed $Reader
     * @param mixed $enable_cache
     */

    public function __construct($Reader, $enable_cache = true)
    {
        // If there isn't a StreamReader, turn on short circuit mode.

        if (!$Reader || isset($Reader->error)) {
            $this->short_circuit = true;

            return;
        }

        // Caching can be turned off

        $this->enable_cache = $enable_cache;

        // $MAGIC1 = (int)0x950412de; //bug in PHP 5.0.2, see https://savannah.nongnu.org/bugs/?func=detailitem&item_id=10565

        $MAGIC1 = (int)-1794895138;

        // $MAGIC2 = (int)0xde120495; //bug

        $MAGIC2 = (int)-569244523;

        $this->STREAM = $Reader;

        $magic = $this->readint();

        if ($magic == ($MAGIC1 & 0xFFFFFFFF)) { // to make sure it works for 64-bit platforms
            $this->BYTEORDER = 0;
        } elseif ($magic == ($MAGIC2 & 0xFFFFFFFF)) {
            $this->BYTEORDER = 1;
        } else {
            $this->error = 1; // not MO file

            return false;
        }

        // FIXME: Do we care about revision? We should.

        $revision = $this->readint();

        $this->total = $this->readint();

        $this->originals = $this->readint();

        $this->translations = $this->readint();
    }

    /**
     * Loads the translation tables from the MO file into the cache
     * If caching is enabled, also loads all strings into a cache
     * to speed up translation lookups
     */

    public function load_tables()
    {
        if (is_array($this->cache_translations)
            && is_array($this->table_originals)
            && is_array($this->table_translations)) {
            return;
        }

        /* get original and translations tables */

        $this->STREAM->seekto($this->originals);

        $this->table_originals = $this->readintarray($this->total * 2);

        $this->STREAM->seekto($this->translations);

        $this->table_translations = $this->readintarray($this->total * 2);

        if ($this->enable_cache) {
            $this->cache_translations = [];

            /* read all strings in the cache */

            for ($i = 0; $i < $this->total; $i++) {
                $this->STREAM->seekto($this->table_originals[$i * 2 + 2]);

                $original = $this->STREAM->read($this->table_originals[$i * 2 + 1]);

                $this->STREAM->seekto($this->table_translations[$i * 2 + 2]);

                $translation = $this->STREAM->read($this->table_translations[$i * 2 + 1]);

                $this->cache_translations[$original] = $translation;
            }
        }
    }

    /**
     * Returns a string from the "originals" table
     *
     * @param mixed $num
     * @return string Requested string if found, otherwise ''
     */

    public function get_original_string($num)
    {
        $length = $this->table_originals[$num * 2 + 1];

        $offset = $this->table_originals[$num * 2 + 2];

        if (!$length) {
            return '';
        }

        $this->STREAM->seekto($offset);

        $data = $this->STREAM->read($length);

        return (string)$data;
    }

    /**
     * Returns a string from the "translations" table
     *
     * @param mixed $num
     * @return string Requested string if found, otherwise ''
     */

    public function get_translation_string($num)
    {
        $length = $this->table_translations[$num * 2 + 1];

        $offset = $this->table_translations[$num * 2 + 2];

        if (!$length) {
            return '';
        }

        $this->STREAM->seekto($offset);

        $data = $this->STREAM->read($length);

        return (string)$data;
    }

    /**
     * Binary search for string
     *
     * @param mixed $string
     * @param mixed $start
     * @param mixed $end
     * @return int string number (offset in originals table)
     */

    public function find_string($string, $start = -1, $end = -1)
    {
        if ((-1 == $start) or (-1 == $end)) {
            // find_string is called with only one parameter, set start end end

            $start = 0;

            $end = $this->total;
        }

        if (abs($start - $end) <= 1) {
            // We're done, now we either found the string, or it doesn't exist

            $txt = $this->get_original_string($start);

            if ($string == $txt) {
                return $start;
            }
  

            return -1;
        } elseif ($start > $end) {
            // start > end -> turn around and start over

            return $this->find_string($string, $end, $start);
        }  

        // Divide table in two parts

        $half = (int)(($start + $end) / 2);

        $cmp = strcmp($string, $this->get_original_string($half));

        if (0 == $cmp) { // string is exactly in the middle => return it
            return $half;
        } elseif ($cmp < 0) { // The string is in the upper half
            return $this->find_string($string, $start, $half);
        }   // The string is in the lower half

        return $this->find_string($string, $half, $end);
    }

    /**
     * Translates a string
     *
     * @param mixed $string
     * @return string translated string (or original, if not found)
     */

    public function translate($string)
    {
        if ($this->short_circuit) {
            return $string;
        }

        $this->load_tables();

        if ($this->enable_cache) {
            // Caching enabled, get translated string from cache

            if (array_key_exists($string, $this->cache_translations)) {
                return $this->cache_translations[$string];
            }
  

            return $string;
        }  

        // Caching not enabled, try to find string

        $num = $this->find_string($string);

        if (-1 == $num) {
            return $string;
        }
  

        return $this->get_translation_string($num);
    }

    /**
     * Get possible plural forms from MO header
     *
     * @return string plural form header
     */

    public function get_plural_forms()
    {
        // lets assume message number 0 is header

        // this is true, right?

        $this->load_tables();

        // cache header field for plural forms

        if (!is_string($this->pluralheader)) {
            if ($this->enable_cache) {
                $header = $this->cache_translations[''];
            } else {
                $header = $this->get_translation_string(0);
            }

            if (eregi("plural-forms: ([^\n]*)\n", $header, $regs)) {
                $expr = $regs[1];
            } else {
                $expr = 'nplurals=2; plural=n == 1 ? 0 : 1;';
            }

            $this->pluralheader = $expr;
        }

        return $this->pluralheader;
    }

    /**
     * Detects which plural form to take
     *
     * @param mixed $n
     * @return int array index of the right plural form
     */

    public function select_string($n)
    {
        $string = $this->get_plural_forms();

        $string = str_replace('nplurals', '$total', $string);

        $string = str_replace('n', $n, $string);

        $string = str_replace('plural', '$plural', $string);

        $total = 0;

        $plural = 0;

        eval((string)$string);

        if ($plural >= $total) {
            $plural = $total - 1;
        }

        return $plural;
    }

    /**
     * Plural version of gettext
     *
     * @param mixed $single
     * @param mixed $plural
     * @param mixed $number
     * @return translated plural form
     */

    public function ngettext($single, $plural, $number)
    {
        if ($this->short_circuit) {
            if (1 != $number) {
                return $plural;
            }
  

            return $single;
        }

        // find out the appropriate form

        $select = $this->select_string($number);

        // this should contains all strings separated by NULLs

        $key = $single . chr(0) . $plural;

        if ($this->enable_cache) {
            if (!array_key_exists($key, $this->cache_translations)) {
                return (1 != $number) ? $plural : $single;
            }  

            $result = $this->cache_translations[$key];

            $list = explode(chr(0), $result);

            return $list[$select];
        }  

        $num = $this->find_string($key);

        if (-1 == $num) {
            return (1 != $number) ? $plural : $single;
        }  

        $result = $this->get_translation_string($num);

        $list = explode(chr(0), $result);

        return $list[$select];
    }
}
