<?php

use Xmf\Request;

require_once __DIR__ . '/functions-compat.php';

if (!function_exists('_')) {
    function _($string)
    {
        return $string;
    }
}

function get_profile($field, $user = false)
{
    global $wpdb;

    if (!$user) {
        $user = $wpdb->escape($_COOKIE[USER_COOKIE]);
    }

    return $wpdb->get_var("SELECT $field FROM $wpdb->users WHERE user_login = '$user'");
}

function mysql2date($dateformatstring, $mysqlstring, $translate = true)
{
    global $month, $weekday, $month_abbrev, $weekday_abbrev;

    $m = $mysqlstring;

    if (empty($m)) {
        return false;
    }

    $i = mktime(mb_substr($m, 11, 2), mb_substr($m, 14, 2), mb_substr($m, 17, 2), mb_substr($m, 5, 2), mb_substr($m, 8, 2), mb_substr($m, 0, 4));

    if (-1 == $i || false === $i) {
        $i = 0;
    }

    if (!empty($month) && !empty($weekday) && $translate) {
        $datemonth = $month[date('m', $i)];

        $datemonth_abbrev = $month_abbrev[$datemonth];

        $dateweekday = $weekday[date('w', $i)];

        $dateweekday_abbrev = $weekday_abbrev[$dateweekday];

        $dateformatstring = ' ' . $dateformatstring;

        $dateformatstring = preg_replace("/([^\\\])D/", '\\1' . backslashit($dateweekday_abbrev), $dateformatstring);

        $dateformatstring = preg_replace("/([^\\\])F/", '\\1' . backslashit($datemonth), $dateformatstring);

        $dateformatstring = preg_replace("/([^\\\])l/", '\\1' . backslashit($dateweekday), $dateformatstring);

        $dateformatstring = preg_replace("/([^\\\])M/", '\\1' . backslashit($datemonth_abbrev), $dateformatstring);

        $dateformatstring = mb_substr($dateformatstring, 1, mb_strlen($dateformatstring) - 1);
    }

    $j = @date($dateformatstring, $i);

    if ('U' == $dateformatstring && !is_numeric($j)) {
        $j = $i;
    }

    if (!$j) {
        // for debug purposes
        //	echo $i." ".$mysqlstring;
    }

    return $j;
}

function current_time($type, $gmt = 0)
{
    switch ($type) {
        case 'mysql':
            if ($gmt) {
                $d = gmdate('Y-m-d H:i:s');
            } else {
                $d = gmdate('Y-m-d H:i:s', (time() + (get_settings('gmt_offset') * 3600)));
            }

            return $d;
            break;
        case 'timestamp':
            if ($gmt) {
                $d = time();
            } else {
                $d = time() + (get_settings('gmt_offset') * 3600);
            }

            return $d;
            break;
    }
}

function date_i18n($dateformatstring, $unixtimestamp)
{
    global $month, $weekday, $month_abbrev, $weekday_abbrev;

    $i = $unixtimestamp;

    if ((!empty($month)) && (!empty($weekday))) {
        $datemonth = $month[date('m', $i)];

        $datemonth_abbrev = $month_abbrev[$datemonth];

        $dateweekday = $weekday[date('w', $i)];

        $dateweekday_abbrev = $weekday_abbrev[$dateweekday];

        $dateformatstring = ' ' . $dateformatstring;

        $dateformatstring = preg_replace("/([^\\\])D/", '\\1' . backslashit($dateweekday_abbrev), $dateformatstring);

        $dateformatstring = preg_replace("/([^\\\])F/", '\\1' . backslashit($datemonth), $dateformatstring);

        $dateformatstring = preg_replace("/([^\\\])l/", '\\1' . backslashit($dateweekday), $dateformatstring);

        $dateformatstring = preg_replace("/([^\\\])M/", '\\1' . backslashit($datemonth_abbrev), $dateformatstring);

        $dateformatstring = mb_substr($dateformatstring, 1, mb_strlen($dateformatstring) - 1);
    }

    $j = @date($dateformatstring, $i);

    return $j;
}

function get_weekstartend($mysqlstring, $start_of_week)
{
    $my = mb_substr($mysqlstring, 0, 4);

    $mm = mb_substr($mysqlstring, 8, 2);

    $md = mb_substr($mysqlstring, 5, 2);

    $day = mktime(0, 0, 0, $md, $mm, $my);

    $weekday = date('w', $day);

    $i = 86400;

    if ($weekday < get_settings('start_of_week')) {
        $weekday = 7 - (get_settings('start_of_week') - $weekday);
    }

    while ($weekday > get_settings('start_of_week')) {
        $weekday = date('w', $day);

        if ($weekday < get_settings('start_of_week')) {
            $weekday = 7 - (get_settings('start_of_week') - $weekday);
        }

        $day -= 86400;

        $i = 0;
    }

    $week['start'] = $day + 86400 - $i;

    // $week['end'] = $day - $i + 691199;

    $week['end'] = $week['start'] + 604799;

    return $week;
}

function get_lastpostdate($timezone = 'server')
{
    global $cache_lastpostdate, $pagenow, $wpdb;

    $add_seconds_blog = get_settings('gmt_offset') * 3600;

    $add_seconds_server = date('Z');

    $now = current_time('mysql', 1);

    if (!isset($cache_lastpostdate[$timezone])) {
        switch (mb_strtolower($timezone)) {
            case 'gmt':
                $lastpostdate = $wpdb->get_var("SELECT post_date_gmt FROM $wpdb->posts WHERE post_date_gmt <= '$now' AND post_status = 'publish' ORDER BY post_date_gmt DESC LIMIT 1");
                break;
            case 'blog':
                $lastpostdate = $wpdb->get_var("SELECT post_date FROM $wpdb->posts WHERE post_date_gmt <= '$now' AND post_status = 'publish' ORDER BY post_date_gmt DESC LIMIT 1");
                break;
            case 'server':
                $lastpostdate = $wpdb->get_var("SELECT DATE_ADD(post_date_gmt, INTERVAL '$add_seconds_server' SECOND) FROM $wpdb->posts WHERE post_date_gmt <= '$now' AND post_status = 'publish' ORDER BY post_date_gmt DESC LIMIT 1");
                break;
        }

        $cache_lastpostdate[$timezone] = $lastpostdate;
    } else {
        $lastpostdate = $cache_lastpostdate[$timezone];
    }

    return $lastpostdate;
}

function get_lastpostmodified($timezone = 'server')
{
    global $cache_lastpostmodified, $pagenow, $wpdb;

    $add_seconds_blog = get_settings('gmt_offset') * 3600;

    $add_seconds_server = date('Z');

    $now = current_time('mysql', 1);

    if (!isset($cache_lastpostmodified[$timezone])) {
        switch (mb_strtolower($timezone)) {
            case 'gmt':
                $lastpostmodified = $wpdb->get_var("SELECT post_modified_gmt FROM $wpdb->posts WHERE post_modified_gmt <= '$now' AND post_status = 'publish' ORDER BY post_modified_gmt DESC LIMIT 1");
                break;
            case 'blog':
                $lastpostmodified = $wpdb->get_var("SELECT post_modified FROM $wpdb->posts WHERE post_modified_gmt <= '$now' AND post_status = 'publish' ORDER BY post_modified_gmt DESC LIMIT 1");
                break;
            case 'server':
                $lastpostmodified = $wpdb->get_var("SELECT DATE_ADD(post_modified_gmt, INTERVAL '$add_seconds_server' SECOND) FROM $wpdb->posts WHERE post_modified_gmt <= '$now' AND post_status = 'publish' ORDER BY post_modified_gmt DESC LIMIT 1");
                break;
        }

        $lastpostdate = get_lastpostdate($timezone);

        if ($lastpostdate > $lastpostmodified) {
            $lastpostmodified = $lastpostdate;
        }

        $cache_lastpostmodified[$timezone] = $lastpostmodified;
    } else {
        $lastpostmodified = $cache_lastpostmodified[$timezone];
    }

    return $lastpostmodified;
}

function user_pass_ok($user_login, $user_pass)
{
    global $cache_userdata;

    if (empty($cache_userdata[$user_login])) {
        $userdata = get_userdatabylogin($user_login);
    } else {
        $userdata = $cache_userdata[$user_login];
    }

    return (md5($user_pass) == $userdata->user_pass);
}

function get_usernumposts($userid)
{
    global $wpdb;

    return $wpdb->get_var("SELECT COUNT(*) FROM $wpdb->posts WHERE post_author = '$userid' AND post_status = 'publish'");
}

// examine a url (supposedly from this blog) and try to
// determine the post ID it represents.
function url_to_postid($url)
{
    global $wp_rewrite;

    // First, check to see if there is a 'p=N' or 'page_id=N' to match against

    preg_match('#[?&](p|page_id)=(\d+)#', $url, $values);

    $id = (int)$values[2];

    if ($id) {
        return $id;
    }

    // Check to see if we are using rewrite rules

    $rewrite = $wp_rewrite->wp_rewrite_rules();

    // Not using rewrite rules, and 'p=N' and 'page_id=N' methods failed, so we're out of options

    if (empty($rewrite)) {
        return 0;
    }

    // $url cleanup by Mark Jaquith

    // This fixes things like #anchors, ?query=strings, missing 'www.',

    // added 'www.', or added 'index.php/' that will mess up our WP_Query

    // and return a false negative

    // Get rid of the #anchor

    $url_split = explode('#', $url);

    $url = $url_split[0];

    // Get rid of URI ?query=string

    $url_split = explode('?', $url);

    $url = $url_split[0];

    // Add 'www.' if it is absent and should be there

    if (false !== mb_strpos(get_settings('home'), '://www.') && false === mb_strpos($url, '://www.')) {
        $url = str_replace('://', '://www.', $url);
    }

    // Strip 'www.' if it is present and shouldn't be

    if (false === mb_strpos(get_settings('home'), '://www.')) {
        $url = str_replace('://www.', '://', $url);
    }

    // Strip 'index.php/' if we're not using path info permalinks

    if (false === mb_strpos($rewrite, 'index.php/')) {
        $url = str_replace('index.php/', '', $url);
    }

    if (false !== mb_strpos($url, get_settings('home'))) {
        // Chop off http://domain.com

        $url = str_replace(get_settings('home'), '', $url);
    } else {
        // Chop off /path/to/blog

        $home_path = parse_url(get_settings('home'));

        $home_path = $home_path['path'];

        $url = str_replace($home_path, '', $url);
    }

    // Trim leading and lagging slashes

    $url = trim($url, '/');

    $request = $url;

    // Done with cleanup

    // Look for matches.

    $request_match = $request;

    foreach ($rewrite as $match => $query) {
        // If the requesting file is the anchor of the match, prepend it

        // to the path info.

        if ((!empty($url)) && (0 === mb_strpos($match, $url))) {
            $request_match = $url . '/' . $request;
        }

        if (preg_match("!^$match!", $request_match, $matches)) {
            // Got a match.

            // Trim the query of everything up to the '?'.

            $query = preg_replace("!^.+\?!", '', $query);

            // Substitute the substring matches into the query.

            eval("\$query = \"$query\";");

            $query = new WP_Query($query);

            if ($query->is_single || $query->is_page) {
                return $query->post->ID;
            }
  

            return 0;
        }
    }

    return 0;
}

function maybe_unserialize($original)
{
    if (is_serialized($original)) { // don't attempt to unserialize data that wasn't serialized going in
        if (false !== $gm = @unserialize($original)) {
            return $gm;
        }
    }

    return $original;
}

function maybe_serialize($data)
{
    if (is_string($data)) {
        $data = trim($data);
    } elseif (is_array($data) || is_object($data)) {
        return serialize($data);
    }

    if (is_serialized($data)) {
        return serialize($data);
    }

    return $data;
}

function is_serialized($data)
{
    if (!is_string($data)) { // if it isn't a string, it isn't serialized
        return false;
    }

    $data = trim($data);

    if (preg_match('/^[adobis]:[0-9]+:.*[;}]/si', $data)) { // this should fetch all legitimately serialized data
        return true;
    }

    return false;
}

function is_serialized_string($data)
{
    if (!is_string($data)) { // if it isn't a string, it isn't a serialized string
        return false;
    }

    $data = trim($data);

    if (preg_match('/^s:[0-9]+:.*[;}]/si', $data)) { // this should fetch all serialized strings
        return true;
    }

    return false;
}

/* Options functions */

function get_settings($setting)
{
    global $wpdb;

    $value = wp_cache_get($setting, 'options');

    if (false === $value) {
        if (defined('WP_INSTALLING')) {
            $wpdb->hide_errors();
        }

        $row = $wpdb->get_row("SELECT option_value FROM $wpdb->options WHERE option_name = '$setting' LIMIT 1");

        if (defined('WP_INSTALLING')) {
            $wpdb->show_errors();
        }

        if (is_object($row)) { // Has to be get_row instead of get_var because of funkiness with 0, false, null values
            $value = $row->option_value;

            wp_cache_set($setting, $value, 'options');
        } else {
            return false;
        }
    }

    // If home is not set use siteurl.

    if ('home' == $setting && '' == $value) {
        return get_settings('siteurl');
    }

    if ('siteurl' == $setting || 'home' == $setting || 'category_base' == $setting) {
        $value = rtrim($value, '/');
    }

    return apply_filters('option_' . $setting, maybe_unserialize($value));
}

function get_option($option)
{
    return get_settings($option);
}

function get_user_option($option, $user = 0)
{
    global $wpdb;

    if (empty($user)) {
        $user = wp_get_current_user();
    } else {
        $user = get_userdata($user);
    }

    if (isset($user->{$wpdb->prefix . $option})) { // Blog specific
        return $user->{$wpdb->prefix . $option};
    } elseif (isset($user->{$option})) { // User specific and cross-blog
        return $user->{$option};
    }   // Blog global

    return get_option($option);
}

function form_option($option)
{
    echo wp_specialchars(get_option($option), 1);
}

function get_alloptions()
{
    global $wpdb, $wp_queries;

    $wpdb->hide_errors();

    if (!$options = $wpdb->get_results("SELECT option_name, option_value FROM $wpdb->options WHERE autoload = 'yes'")) {
        $options = $wpdb->get_results("SELECT option_name, option_value FROM $wpdb->options");
    }

    $wpdb->show_errors();

    foreach ($options as $option) {
        // "When trying to design a foolproof system,

        //  never underestimate the ingenuity of the fools :)" -- Dougal

        if ('siteurl' == $option->option_name) {
            $option->option_value = rtrim($option->option_value, '/');
        }

        if ('home' == $option->option_name) {
            $option->option_value = rtrim($option->option_value, '/');
        }

        if ('category_base' == $option->option_name) {
            $option->option_value = rtrim($option->option_value, '/');
        }

        $value = maybe_unserialize($option->option_value);

        $all_options->{$option->option_name} = apply_filters('pre_option_' . $option->option_name, $value);
    }

    return apply_filters('all_options', $all_options);
}

function update_option($option_name, $newvalue)
{
    global $wpdb;

    if (is_string($newvalue)) {
        $newvalue = trim($newvalue);
    }

    // If the new and old values are the same, no need to update.

    $oldvalue = get_option($option_name);

    if ($newvalue == $oldvalue) {
        return false;
    }

    if (false === $oldvalue) {
        add_option($option_name, $newvalue);

        return true;
    }

    $_newvalue = $newvalue;

    $newvalue = maybe_serialize($newvalue);

    wp_cache_set($option_name, $newvalue, 'options');

    $newvalue = $wpdb->escape($newvalue);

    $option_name = $wpdb->escape($option_name);

    $wpdb->query("UPDATE $wpdb->options SET option_value = '$newvalue' WHERE option_name = '$option_name'");

    if (1 == $wpdb->rows_affected) {
        do_action("update_option_{$option_name}", ['old' => $oldvalue, 'new' => $_newvalue]);

        return true;
    }

    return false;
}

function update_user_option($user_id, $option_name, $newvalue, $global = false)
{
    global $wpdb;

    if (!$global) {
        $option_name = $wpdb->prefix . $option_name;
    }

    return update_usermeta($user_id, $option_name, $newvalue);
}

// thx Alex Stapleton, http://alex.vort-x.net/blog/
function add_option($name, $value = '', $description = '', $autoload = 'yes')
{
    global $wpdb;

    // Make sure the option doesn't already exist

    if (false !== get_option($name)) {
        return;
    }

    $value = maybe_serialize($value);

    wp_cache_set($name, $value, 'options');

    $name = $wpdb->escape($name);

    $value = $wpdb->escape($value);

    $description = $wpdb->escape($description);

    $wpdb->query("INSERT INTO $wpdb->options (option_name, option_value, option_description, autoload) VALUES ('$name', '$value', '$description', '$autoload')");
}

function delete_option($name)
{
    global $wpdb;

    // Get the ID, if no ID then return

    $option_id = $wpdb->get_var("SELECT option_id FROM $wpdb->options WHERE option_name = '$name'");

    if (!$option_id) {
        return false;
    }

    $wpdb->query("DELETE FROM $wpdb->options WHERE option_name = '$name'");

    wp_cache_delete($name, 'options');

    return true;
}

function add_post_meta($post_id, $key, $value, $unique = false)
{
    global $wpdb, $post_meta_cache;

    $post_id = (int)$post_id;

    if ($unique) {
        if ($wpdb->get_var("SELECT meta_key FROM $wpdb->postmeta WHERE meta_key = '$key' AND post_id = '$post_id'")) {
            return false;
        }
    }

    $post_meta_cache[$post_id][$key][] = $value;

    $value = maybe_serialize($value);

    $value = $wpdb->escape($value);

    $wpdb->query("INSERT INTO $wpdb->postmeta (post_id,meta_key,meta_value) VALUES ('$post_id','$key','$value')");

    return true;
}

function delete_post_meta($post_id, $key, $value = '')
{
    global $wpdb, $post_meta_cache;

    $post_id = (int)$post_id;

    if (empty($value)) {
        $meta_id = $wpdb->get_var("SELECT meta_id FROM $wpdb->postmeta WHERE post_id = '$post_id' AND meta_key = '$key'");
    } else {
        $meta_id = $wpdb->get_var("SELECT meta_id FROM $wpdb->postmeta WHERE post_id = '$post_id' AND meta_key = '$key' AND meta_value = '$value'");
    }

    if (!$meta_id) {
        return false;
    }

    if (empty($value)) {
        $wpdb->query("DELETE FROM $wpdb->postmeta WHERE post_id = '$post_id' AND meta_key = '$key'");

        unset($post_meta_cache[$post_id][$key]);
    } else {
        $wpdb->query("DELETE FROM $wpdb->postmeta WHERE post_id = '$post_id' AND meta_key = '$key' AND meta_value = '$value'");

        $cache_key = $post_meta_cache[$post_id][$key];

        if ($cache_key) {
            foreach ($cache_key as $index => $data) {
                if ($data == $value) {
                    unset($post_meta_cache[$post_id][$key][$index]);
                }
            }
        }
    }

    unset($post_meta_cache[$post_id][$key]);

    return true;
}

function get_post_meta($post_id, $key, $single = false)
{
    global $wpdb, $post_meta_cache;

    $post_id = (int)$post_id;

    if (isset($post_meta_cache[$post_id][$key])) {
        if ($single) {
            return maybe_unserialize($post_meta_cache[$post_id][$key][0]);
        }
  

        return maybe_unserialize($post_meta_cache[$post_id][$key]);
    }

    $metalist = $wpdb->get_results("SELECT meta_value FROM $wpdb->postmeta WHERE post_id = '$post_id' AND meta_key = '$key'", ARRAY_N);

    $values = [];

    if ($metalist) {
        foreach ($metalist as $metarow) {
            $values[] = $metarow[0];
        }
    }

    if ($single) {
        if (count($values)) {
            $return = maybe_unserialize($values[0]);
        } else {
            return '';
        }
    } else {
        $return = $values;
    }

    return maybe_unserialize($return);
}

function update_post_meta($post_id, $key, $value, $prev_value = '')
{
    global $wpdb, $post_meta_cache;

    $post_id = (int)$post_id;

    $original_value = $value;

    $value = maybe_serialize($value);

    $value = $wpdb->escape($value);

    $original_prev = $prev_value;

    $prev_value = maybe_serialize($prev_value);

    $prev_value = $wpdb->escape($prev_value);

    if (!$wpdb->get_var("SELECT meta_key FROM $wpdb->postmeta WHERE meta_key = '$key' AND post_id = '$post_id'")) {
        return false;
    }

    if (empty($prev_value)) {
        $wpdb->query("UPDATE $wpdb->postmeta SET meta_value = '$value' WHERE meta_key = '$key' AND post_id = '$post_id'");

        $cache_key = $post_meta_cache[$post_id][$key];

        if (!empty($cache_key)) {
            foreach ($cache_key as $index => $data) {
                $post_meta_cache[$post_id][$key][$index] = $original_value;
            }
        }
    } else {
        $wpdb->query("UPDATE $wpdb->postmeta SET meta_value = '$value' WHERE meta_key = '$key' AND post_id = '$post_id' AND meta_value = '$prev_value'");

        $cache_key = $post_meta_cache[$post_id][$key];

        if (!empty($cache_key)) {
            foreach ($cache_key as $index => $data) {
                if ($data == $original_prev) {
                    $post_meta_cache[$post_id][$key][$index] = $original_value;
                }
            }
        }
    }

    return true;
}

// Deprecated.  Use get_post().
function get_postdata($postid)
{
    $post = get_post($postid);

    $postdata = [
        'ID' => $post->ID,
        'Author_ID' => $post->post_author,
        'Date' => $post->post_date,
        'Content' => $post->post_content,
        'Excerpt' => $post->post_excerpt,
        'Title' => $post->post_title,
        'Category' => $post->post_category,
        'post_status' => $post->post_status,
        'comment_status' => $post->comment_status,
        'ping_status' => $post->ping_status,
        'post_password' => $post->post_password,
        'to_ping' => $post->to_ping,
        'pinged' => $post->pinged,
        'post_name' => $post->post_name,
    ];

    return $postdata;
}

// Retrieves post data given a post ID or post object.
// Handles post caching.
function get_post(&$post, $output = OBJECT)
{
    global $post_cache, $wpdb;

    if (empty($post)) {
        $_post = $GLOBALS['post'] ?? null;
    } elseif (is_object($post)) {
        if ('static' == $post->post_status) {
            return get_page($post, $output);
        }

        if (!isset($post_cache[$post->ID])) {
            $post_cache[$post->ID] = &$post;
        }

        $_post = &$post_cache[$post->ID];
    } else {
        if ($_post = wp_cache_get($post, 'pages')) {
            return get_page($_post, $output);
        } elseif (isset($post_cache[$post])) {
            $_post = &$post_cache[$post];
        } else {
            $query = "SELECT * FROM $wpdb->posts WHERE ID = '$post' LIMIT 1";

            $_post = $wpdb->get_row($query);

            if ('static' == $_post->post_status) {
                return get_page($_post, $output);
            }

            $post_cache[$post] = &$_post;
        }
    }

    if (defined(WP_IMPORTING)) {
        unset($post_cache);
    }

    if (OBJECT == $output) {
        return $_post;
    } elseif (ARRAY_A == $output) {
        return get_object_vars($_post);
    } elseif (ARRAY_N == $output) {
        return array_values(get_object_vars($_post));
    }
  

    return $_post;
}

function get_children($post = 0, $output = OBJECT)
{
    global $post_cache, $wpdb;

    if (empty($post)) {
        if (isset($GLOBALS['post'])) {
            $post_parent = &$GLOBALS['post']->post_parent;
        } else {
            return false;
        }
    } elseif (is_object($post)) {
        $post_parent = $post->post_parent;
    } else {
        $post_parent = $post;
    }

    $post_parent = (int)$post_parent;

    $query = "SELECT * FROM $wpdb->posts WHERE post_parent = $post_parent";

    $children = $wpdb->get_results($query);

    if ($children) {
        foreach ($children as $key => $child) {
            $post_cache[$child->ID] = &$children[$key];

            $kids[$child->ID] = &$children[$key];
        }
    } else {
        return false;
    }

    if (OBJECT == $output) {
        return $kids;
    } elseif (ARRAY_A == $output) {
        foreach ($kids as $kid) {
            $weeuns[$kid->ID] = get_object_vars($kids[$kid->ID]);
        }

        return $weeuns;
    } elseif (ARRAY_N == $output) {
        foreach ($kids as $kid) {
            $babes[$kid->ID] = array_values(get_object_vars($kids[$kid->ID]));
        }

        return $babes;
    }
  

    return $kids;
}

function set_page_path($page)
{
    $page->fullpath = '/' . $page->post_name;

    $path = $page->fullpath;

    $curpage = $page;

    while (0 != $curpage->post_parent) {
        $curpage = get_page($curpage->post_parent);

        $path = '/' . $curpage->post_name . $path;
    }

    $page->fullpath = $path;

    return $page;
}

// Retrieves page data given a page ID or page object.
// Handles page caching.
function get_page(&$page, $output = OBJECT)
{
    global $wpdb;

    if (empty($page)) {
        if (isset($GLOBALS['page'])) {
            $_page = &$GLOBALS['page'];

            wp_cache_add($_page->ID, $_page, 'pages');
        } else {
            $_page = null;
        }
    } elseif (is_object($page)) {
        if ('static' != $page->post_status) {
            return get_post($page, $output);
        }

        wp_cache_add($page->ID, $page, 'pages');

        $_page = $page;
    } else {
        if (isset($GLOBALS['page']) && ($page == $GLOBALS['page']->ID)) {
            $_page = &$GLOBALS['page'];

            wp_cache_add($_page->ID, $_page, 'pages');
        } elseif ($_page = $GLOBALS['post_cache'][$page]) {
            return get_post($page, $output);
        } elseif ($_page = wp_cache_get($page, 'pages')) {
            // Got it.
        } else {
            $query = "SELECT * FROM $wpdb->posts WHERE ID= '$page' LIMIT 1";

            $_page = $wpdb->get_row($query);

            if ('static' != $_page->post_status) {
                return get_post($_page, $output);
            }

            wp_cache_add($_page->ID, $_page, 'pages');
        }
    }

    if (!isset($_page->fullpath)) {
        $_page = set_page_path($_page);

        wp_cache_replace($_page->cat_ID, $_page, 'pages');
    }

    if (OBJECT == $output) {
        return $_page;
    } elseif (ARRAY_A == $output) {
        return get_object_vars($_page);
    } elseif (ARRAY_N == $output) {
        return array_values(get_object_vars($_page));
    }
  

    return $_page;
}

function set_category_path($cat)
{
    $cat->fullpath = '/' . $cat->category_nicename;

    $path = $cat->fullpath;

    $curcat = $cat;

    while (0 != $curcat->category_parent) {
        $curcat = get_category($curcat->category_parent);

        $path = '/' . $curcat->category_nicename . $path;
    }

    $cat->fullpath = $path;

    return $cat;
}

// Retrieves category data given a category ID or category object.
// Handles category caching.
function get_category($category, $output = OBJECT)
{
    global $wpdb;

    if (empty($category)) {
        return null;
    }

    if (is_object($category)) {
        wp_cache_add($category->cat_ID, $category, 'category');

        $_category = $category;
    } else {
        if (!$_category = wp_cache_get($category, 'category')) {
            $_category = $wpdb->get_row("SELECT * FROM $wpdb->categories WHERE cat_ID = '$category' LIMIT 1");

            wp_cache_add($category, $_category, 'category');
        }
    }

    $_category = apply_filters('get_category', $_category);

    if (!isset($_category->fullpath)) {
        $_category = set_category_path($_category);

        wp_cache_replace($_category->cat_ID, $_category, 'category');
    }

    if (OBJECT == $output) {
        return $_category;
    } elseif (ARRAY_A == $output) {
        return get_object_vars($_category);
    } elseif (ARRAY_N == $output) {
        return array_values(get_object_vars($_category));
    }
  

    return $_category;
}

// Retrieves comment data given a comment ID or comment object.
// Handles comment caching.
function get_comment(&$comment, $output = OBJECT)
{
    global $comment_cache, $wpdb;

    if (empty($comment)) {
        return null;
    }

    if (is_object($comment)) {
        if (!isset($comment_cache[$comment->comment_ID])) {
            $comment_cache[$comment->comment_ID] = &$comment;
        }

        $_comment = &$comment_cache[$comment->comment_ID];
    } else {
        if (!isset($comment_cache[$comment])) {
            $_comment = $wpdb->get_row("SELECT * FROM $wpdb->comments WHERE comment_ID = '$comment' LIMIT 1");

            $comment_cache[$comment->comment_ID] = &$_comment;
        } else {
            $_comment = &$comment_cache[$comment];
        }
    }

    if (OBJECT == $output) {
        return $_comment;
    } elseif (ARRAY_A == $output) {
        return get_object_vars($_comment);
    } elseif (ARRAY_N == $output) {
        return array_values(get_object_vars($_comment));
    }
  

    return $_comment;
}

function get_catname($cat_ID)
{
    $category = get_category($cat_ID);

    return $category->cat_name;
}

function get_all_category_ids()
{
    global $wpdb;

    if (!$cat_ids = wp_cache_get('all_category_ids', 'category')) {
        $cat_ids = $wpdb->get_col("SELECT cat_ID FROM $wpdb->categories");

        wp_cache_add('all_category_ids', $cat_ids, 'category');
    }

    return $cat_ids;
}

function get_all_page_ids()
{
    global $wpdb;

    if (!$page_ids = wp_cache_get('all_page_ids', 'pages')) {
        $page_ids = $wpdb->get_col("SELECT ID FROM $wpdb->posts WHERE post_status='static'");

        wp_cache_add('all_page_ids', $page_ids, 'pages');
    }

    return $page_ids;
}

function gzip_compression()
{
    if (!get_settings('gzipcompression')) {
        return false;
    }

    if (extension_loaded('zlib')) {
        ob_start('ob_gzhandler');
    }
}

// functions to count the page generation time (from phpBB2)
// ( or just any time between timer_start() and timer_stop() )

function timer_stop($display = 0, $precision = 3)
{ //if called like timer_stop(1), will echo $timetotal
    global $timestart, $timeend;

    $mtime = microtime();

    $mtime = explode(' ', $mtime);

    $mtime = $mtime[1] + $mtime[0];

    $timeend = $mtime;

    $timetotal = $timeend - $timestart;

    $r = number_format($timetotal, $precision);

    if ($display) {
        echo $r;
    }

    return $r;
}

function weblog_ping($server = '', $path = '')
{
    global $wp_version;

    require_once ABSPATH . WPINC . '/class-IXR.php';

    // using a timeout of 3 seconds should be enough to cover slow servers

    $client = new IXR_Client($server, ((!mb_strlen(trim($path)) || ('/' == $path)) ? false : $path));

    $client->timeout = 3;

    $client->useragent .= ' -- WordPress/' . $wp_version;

    // when set to true, this outputs debug messages by itself

    $client->debug = false;

    $home = trailingslashit(get_option('home'));

    $blogname = encoding_wp2rss(get_settings('blogname'));

    if (!$client->query('weblogUpdates.extendedPing', $blogname, $home, get_bloginfo('rss2_url'))) { // then try a normal ping
        $client->query('weblogUpdates.ping', $blogname, $home);
    }
}

function generic_ping($post_id = 0)
{
    $services = get_settings('ping_sites');

    $services = preg_replace("|(\s)+|", '$1', $services); // Kill dupe lines

    $services = trim($services);

    if ('' != $services) {
        $services = explode("\n", $services);

        foreach ($services as $service) {
            weblog_ping($service);
        }
    }

    return $post_id;
}

// Send a Trackback
function trackback($trackback_url, $title, $excerpt, $ID)
{
    global $wpdb, $wp_version;

    if (empty($trackback_url)) {
        return;
    }

    $GLOBALS['doing_trackback'] = 1;

    $title = encoding_wp2rss($title);

    $excerpt = encoding_wp2rss($excerpt);

    $title = urlencode($title);

    $excerpt = urlencode($excerpt);

    $blog_name = urlencode(get_settings('blogname'));

    $tb_url = $trackback_url;

    $url = urlencode(get_permalink($ID));

    $query_string = "title=$title&url=$url&blog_name=$blog_name&excerpt=$excerpt";

    $trackback_url = parse_url($trackback_url);

    $http_request = 'POST ' . $trackback_url['path'] . ($trackback_url['query'] ? '?' . $trackback_url['query'] : '') . " HTTP/1.0\r\n";

    $http_request .= 'Host: ' . $trackback_url['host'] . "\r\n";

    $http_request .= 'Content-Type: application/x-www-form-urlencoded; charset=' . get_settings('blog_charset') . "\r\n";

    $http_request .= 'Content-Length: ' . mb_strlen($query_string) . "\r\n";

    $http_request .= 'User-Agent: WordPress/' . $wp_version;

    $http_request .= "\r\n\r\n";

    $http_request .= $query_string;

    if ('' == $trackback_url['port']) {
        $trackback_url['port'] = 80;
    }

    $fs = @fsockopen($trackback_url['host'], $trackback_url['port'], $errno, $errstr, 4);

    @fwrite($fs, $http_request);

    /*
    $debug_file = 'trackback.log';
    $fp = fopen($debug_file, 'a');
    fwrite($fp, "\n*****\nRequest:\n\n$http_request\n\nResponse:\n\n");
    while(!@feof($fs)) {
        fwrite($fp, @fgets($fs, 4096));
    }
    fwrite($fp, "\n\n");
    fclose($fp);
*/

    @fclose($fs);

    $tb_url = addslashes($tb_url);

    $wpdb->query("UPDATE $wpdb->posts SET pinged = CONCAT(pinged, '\n', '$tb_url') WHERE ID = '$ID'");

    return $wpdb->query("UPDATE $wpdb->posts SET to_ping = TRIM(REPLACE(to_ping, '$tb_url', '')) WHERE ID = '$ID'");
}

function make_url_footnote($content)
{
    preg_match_all('/<a(.+?)href=\"(.+?)\"(.*?)>(.+?)<\/a>/', $content, $matches);

    $j = 0;

    for ($i = 0, $iMax = count($matches[0]); $i < $iMax; $i++) {
        $links_summary = (!$j) ? "\n" : $links_summary;

        $j++;

        $link_match = $matches[0][$i];

        $link_number = '[' . ($i + 1) . ']';

        $link_url = $matches[2][$i];

        $link_text = $matches[4][$i];

        $content = str_replace($link_match, $link_text . ' ' . $link_number, $content);

        $link_url = (('http://' != mb_strtolower(mb_substr($link_url, 0, 7))) && ('https://' != mb_strtolower(mb_substr($link_url, 0, 8)))) ? get_settings('home') . $link_url : $link_url;

        $links_summary .= "\n" . $link_number . ' ' . $link_url;
    }

    $content = strip_tags($content);

    $content .= $links_summary;

    return $content;
}

function xmlrpc_getposttitle($content)
{
    global $post_default_title;

    if (preg_match('/<title>(.+?)<\/title>/is', $content, $matchtitle)) {
        $post_title = $matchtitle[0];

        $post_title = preg_replace('/<title>/si', '', $post_title);

        $post_title = preg_replace('/<\/title>/si', '', $post_title);
    } else {
        $post_title = $post_default_title;
    }

    return $post_title;
}

function xmlrpc_getpostcategory($content)
{
    global $post_default_category;

    if (preg_match('/<category>(.+?)<\/category>/is', $content, $matchcat)) {
        $post_category = trim($matchcat[1], ',');

        $post_category = explode(',', $post_category);
    } else {
        $post_category = $post_default_category;
    }

    return $post_category;
}

function xmlrpc_removepostdata($content)
{
    $content = preg_replace('/<title>(.+?)<\/title>/si', '', $content);

    $content = preg_replace('/<category>(.+?)<\/category>/si', '', $content);

    $content = trim($content);

    return $content;
}

function debug_fopen($filename, $mode)
{
    global $debug;

    if (1 == $debug) {
        $fp = fopen($filename, $mode);

        return $fp;
    }
  

    return false;
}

function debug_fwrite($fp, $string)
{
    global $debug;

    if (1 == $debug) {
        fwrite($fp, $string);
    }
}

function debug_fclose($fp)
{
    global $debug;

    if (1 == $debug) {
        fclose($fp);
    }
}

function spawn_pinger()
{
    global $wpdb, $wp_version;

    $doping = false;

    if ($wpdb->get_var("SELECT ID FROM $wpdb->posts WHERE TRIM(to_ping) != '' LIMIT 1")) {
        $doping = true;
    }

    if ($wpdb->get_var("SELECT post_id FROM $wpdb->postmeta WHERE meta_key = '_pingme' OR meta_key = '_encloseme' LIMIT 1")) {
        $doping = true;
    }

    if ('cgi' == mb_substr(php_sapi_name(), 0, 3)) {
        return $doping;
    }

    if ($doping) {
        $ping_url = get_settings('siteurl') . '/wp-admin/execute-pings.php';

        $parts = parse_url($ping_url);

        $argyle = @fsockopen($parts['host'], $_SERVER['SERVER_PORT'], $errno, $errstr, 0.01);

        if ($argyle) {
            fwrite($argyle, "GET {$parts['path']}?time=" . time() . " HTTP/1.0\r\nHost: {$_SERVER['HTTP_HOST']}\r\nUser-Agent: WordPress/{$wp_version}\r\n\r\n");
        }
    }
}

function do_enclose($content, $post_ID)
{
    global $wp_version, $wpdb;

    require_once ABSPATH . WPINC . '/class-IXR.php';

    $log = debug_fopen(ABSPATH . '/enclosures.log', 'a');

    $post_links = [];

    debug_fwrite($log, 'BEGIN ' . date('YmdHis', time()) . "\n");

    $pung = get_enclosed($post_ID);

    $ltrs = '\w';

    $gunk = '/#~:.?+=&%@!\-';

    $punc = '.:?\-';

    $any = $ltrs . $gunk . $punc;

    preg_match_all("{\b http : [$any] +? (?= [$punc] * [^$any] | $)}x", $content, $post_links_temp);

    debug_fwrite($log, 'Post contents:');

    debug_fwrite($log, $content . "\n");

    foreach ($post_links_temp[0] as $link_test) :
        if (!in_array($link_test, $pung, true)) : // If we haven't pung it already
            $test = parse_url($link_test);

    if (isset($test['query'])) {
        $post_links[] = $link_test;
    } elseif (('/' != $test['path']) && ('' != $test['path'])) {
        $post_links[] = $link_test;
    }

    endif;

    endforeach;

    foreach ($post_links as $url) :
        if ('' != $url && !$wpdb->get_var("SELECT post_id FROM $wpdb->postmeta WHERE post_id = '$post_ID' AND meta_key = 'enclosure' AND meta_value LIKE ('$url%')")) {
            if ($headers = wp_get_http_headers($url)) {
                $len = (int)$headers['content-length'];

                $type = $wpdb->escape($headers['content-type']);

                $allowed_types = ['video', 'audio'];

                if (in_array(mb_substr($type, 0, mb_strpos($type, '/')), $allowed_types, true)) {
                    $meta_value = "$url\n$len\n$type\n";

                    $wpdb->query(
                        "INSERT INTO `$wpdb->postmeta` ( `post_id` , `meta_key` , `meta_value` )
					VALUES ( '$post_ID', 'enclosure' , '$meta_value')"
                    );
                }
            }
        }

    endforeach;
}

function wp_get_http_headers($url, $red = 1)
{
    global $wp_version;

    @set_time_limit(60);

    if ($red > 5) {
        return false;
    }

    $parts = parse_url($url);

    $file = $parts['path'] . ($parts['query'] ? '?' . $parts['query'] : '');

    $host = $parts['host'];

    if (!isset($parts['port'])) {
        $parts['port'] = 80;
    }

    $head = "HEAD $file HTTP/1.1\r\nHOST: $host\r\nUser-Agent: WordPress/" . $wp_version . "\r\n\r\n";

    $fp = @fsockopen($host, $parts['port'], $err_num, $err_msg, 3);

    if (!$fp) {
        return false;
    }

    $response = '';

    fwrite($fp, $head);

    while (!feof($fp) && false === mb_strpos($response, "\r\n\r\n")) {
        $response .= fgets($fp, 2048);
    }

    fclose($fp);

    preg_match_all('/(.*?): (.*)\r/', $response, $matches);

    $count = count($matches[1]);

    for ($i = 0; $i < $count; $i++) {
        $key = mb_strtolower($matches[1][$i]);

        $headers[(string)$key] = $matches[2][$i];
    }

    preg_match('/.*([0-9]{3}).*/', $response, $return);

    $headers['response'] = $return[1]; // HTTP response code eg 204, 200, 404

    $code = $headers['response'];

    if (('302' == $code || '301' == $code) && isset($headers['location'])) {
        return wp_get_http_headers($headers['location'], ++$red);
    }

    return $headers;
}

// Deprecated.  Use the new post loop.
function start_wp()
{
    global $wp_query, $post;

    // Since the old style loop is being used, advance the query iterator here.

    $wp_query->next_post();

    setup_postdata($post);
}

// Setup global post data.
function setup_postdata($post)
{
    global $id, $postdata, $authordata, $day, $page, $pages, $multipage, $more, $numpages, $wp_query;

    global $pagenow;

    $id = $post->ID;

    $authordata = get_userdata($post->post_author);

    $day = mysql2date('d.m.y', $post->post_date);

    $currentmonth = mysql2date('m', $post->post_date);

    $numpages = 1;

    $page = get_query_var('page');

    if (!$page) {
        $page = 1;
    }

    if (is_single() || is_page()) {
        $more = 1;
    }

    $content = $post->post_content;

    if (preg_match('/<!--nextpage-->/', $content)) {
        if ($page > 1) {
            $more = 1;
        }

        $multipage = 1;

        $content = str_replace("\n<!--nextpage-->\n", '<!--nextpage-->', $content);

        $content = str_replace("\n<!--nextpage-->", '<!--nextpage-->', $content);

        $content = str_replace("<!--nextpage-->\n", '<!--nextpage-->', $content);

        $pages = explode('<!--nextpage-->', $content);

        $numpages = count($pages);
    } else {
        $pages[0] = $post->post_content;

        $multipage = 0;
    }

    return true;
}

// Setup global user vars.  Used by set_current_user() for back compat.
function setup_userdata($user_id = '')
{
    global $user_login, $userdata, $user_level, $user_ID, $user_email, $user_url, $user_pass_md5, $user_identity;

    if ('' == $user_id) {
        $user = wp_get_current_user();
    } else {
        $user = new WP_User($user_id);
    }

    if (0 == $user->ID) {
        return;
    }

    $userdata = $user->data;

    $user_login = $user->user_login;

    $user_level = $user->user_level;

    $user_ID = $user->ID;

    $user_email = $user->user_email;

    $user_url = $user->user_url;

    $user_pass_md5 = md5($user->user_pass);

    $user_identity = $user->display_name;
}

function is_new_day()
{
    global $day, $previousday;

    if ($day != $previousday) {
        return (1);
    }
  

    return (0);
}

// Filters: these are the core of WP's plugin architecture

function merge_filters($tag)
{
    global $wp_filter;

    if (isset($wp_filter['all'])) {
        foreach ($wp_filter['all'] as $priority => $functions) {
            if (isset($wp_filter[$tag][$priority])) {
                $wp_filter[$tag][$priority] = array_merge($wp_filter['all'][$priority], $wp_filter[$tag][$priority]);
            } else {
                $wp_filter[$tag][$priority] = array_merge($wp_filter['all'][$priority], []);
            }

            $wp_filter[$tag][$priority] = array_unique($wp_filter[$tag][$priority]);
        }
    }

    if (isset($wp_filter[$tag])) {
        uksort($wp_filter[$tag], 'strnatcasecmp');
    }
}

function apply_filters($tag, $string)
{
    global $wp_filter;

    $args = array_slice(func_get_args(), 2);

    merge_filters($tag);

    if (!isset($wp_filter[$tag])) {
        return $string;
    }

    foreach ($wp_filter[$tag] as $priority => $functions) {
        if (null !== $functions) {
            foreach ($functions as $function) {
                $all_args = array_merge([$string], $args);

                $function_name = $function['function'];

                $accepted_args = $function['accepted_args'];

                if (1 == $accepted_args) {
                    $the_args = [$string];
                } elseif ($accepted_args > 1) {
                    $the_args = array_slice($all_args, 0, $accepted_args);
                } elseif (0 == $accepted_args) {
                    $the_args = null;
                } else {
                    $the_args = $all_args;
                }

                $string = call_user_func_array($function_name, $the_args);
            }
        }
    }

    return $string;
}

function add_filter($tag, $function_to_add, $priority = 10, $accepted_args = 1)
{
    global $wp_filter;

    // check that we don't already have the same filter at the same priority

    if (isset($wp_filter[$tag][(string)$priority])) {
        foreach ($wp_filter[$tag][(string)$priority] as $filter) {
            // uncomment if we want to match function AND accepted_args

            // if ( $filter == array($function, $accepted_args) ) {

            if ($filter['function'] == $function_to_add) {
                return true;
            }
        }
    }

    // So the format is wp_filter['tag']['array of priorities']['array of ['array (functions, accepted_args)]']

    $wp_filter[$tag][(string)$priority][] = ['function' => $function_to_add, 'accepted_args' => $accepted_args];

    return true;
}

function remove_filter($tag, $function_to_remove, $priority = 10, $accepted_args = 1)
{
    global $wp_filter;

    // rebuild the list of filters

    if (isset($wp_filter[$tag][(string)$priority])) {
        $new_function_list = [];

        foreach ($wp_filter[$tag][(string)$priority] as $filter) {
            if ($filter['function'] != $function_to_remove) {
                $new_function_list[] = $filter;
            }
        }

        $wp_filter[$tag][(string)$priority] = $new_function_list;
    }

    return true;
}

// The *_action functions are just aliases for the *_filter functions, they take special strings instead of generic content

function do_action($tag, $arg = '')
{
    global $wp_filter;

    $extra_args = array_slice(func_get_args(), 2);

    if (is_array($arg)) {
        $args = array_merge($arg, $extra_args);
    } else {
        $args = array_merge([$arg], $extra_args);
    }

    merge_filters($tag);

    if (!isset($wp_filter[$tag])) {
        return;
    }

    foreach ($wp_filter[$tag] as $priority => $functions) {
        if (null !== $functions) {
            foreach ($functions as $function) {
                $function_name = $function['function'];

                $accepted_args = $function['accepted_args'];

                if (1 == $accepted_args) {
                    if (is_array($arg)) {
                        $the_args = $arg;
                    } else {
                        $the_args = [$arg];
                    }
                } elseif ($accepted_args > 1) {
                    $the_args = array_slice($args, 0, $accepted_args);
                } elseif (0 == $accepted_args) {
                    $the_args = null;
                } else {
                    $the_args = $args;
                }

                $string = call_user_func_array($function_name, $the_args);
            }
        }
    }
}

function add_action($tag, $function_to_add, $priority = 10, $accepted_args = 1)
{
    add_filter($tag, $function_to_add, $priority, $accepted_args);
}

function remove_action($tag, $function_to_remove, $priority = 10, $accepted_args = 1)
{
    remove_filter($tag, $function_to_remove, $priority, $accepted_args);
}

function get_page_uri($page_id)
{
    $page = get_page($page_id);

    $uri = urldecode($page->post_name);

    // A page cannot be it's own parent.

    if ($page->post_parent == $page->ID) {
        return $uri;
    }

    while (0 != $page->post_parent) {
        $page = get_page($page->post_parent);

        $uri = urldecode($page->post_name) . '/' . $uri;
    }

    return $uri;
}

function get_posts($args)
{
    global $wpdb;

    parse_str($args, $r);

    if (!isset($r['numberposts'])) {
        $r['numberposts'] = 5;
    }

    if (!isset($r['offset'])) {
        $r['offset'] = 0;
    }

    if (!isset($r['category'])) {
        $r['category'] = '';
    }

    if (!isset($r['orderby'])) {
        $r['orderby'] = 'post_date';
    }

    if (!isset($r['order'])) {
        $r['order'] = 'DESC';
    }

    $now = current_time('mysql');

    $posts = $wpdb->get_results(
        "SELECT DISTINCT * FROM $wpdb->posts "
        . (empty($r['category']) ? '' : ", $wpdb->post2cat ")
        . " WHERE post_date <= '$now' AND (post_status = 'publish') "
        . (empty($r['category']) ? '' : "AND $wpdb->posts.ID = $wpdb->post2cat.post_id AND $wpdb->post2cat.category_id = "
                                        . $r['category']
                                        . ' ')
        . " GROUP BY $wpdb->posts.ID ORDER BY "
        . $r['orderby']
        . ' '
        . $r['order']
        . ' LIMIT '
        . $r['offset']
        . ','
        . $r['numberposts']
    );

    update_post_caches($posts);

    return $posts;
}

function &query_posts($query)
{
    global $wp_query;

    return $wp_query->query($query);
}

function update_post_cache(&$posts)
{
    global $post_cache;

    if (!$posts) {
        return;
    }

    for ($i = 0, $iMax = count($posts); $i < $iMax; $i++) {
        $post_cache[$posts[$i]->ID] = &$posts[$i];
    }
}

function clean_post_cache($id)
{
    global $post_cache;

    if (isset($post_cache[$id])) {
        unset($post_cache[$id]);
    }
}

function update_page_cache(&$pages)
{
    global $page_cache;

    if (!$pages) {
        return;
    }

    for ($i = 0, $iMax = count($pages); $i < $iMax; $i++) {
        $page_cache[$pages[$i]->ID] = &$pages[$i];

        wp_cache_add($pages[$i]->ID, $pages[$i], 'pages');
    }
}

function clean_page_cache($id)
{
    global $page_cache;

    if (isset($page_cache[$id])) {
        unset($page_cache[$id]);
    }
}

function update_post_category_cache($post_ids)
{
    global $wpdb, $category_cache;

    if (empty($post_ids)) {
        return;
    }

    if (is_array($post_ids)) {
        $post_ids = implode(',', $post_ids);
    }

    $dogs = $wpdb->get_results("SELECT post_id, category_id FROM $wpdb->post2cat WHERE post_id IN ($post_ids)");

    if (empty($dogs)) {
        return;
    }

    foreach ($dogs as $catt) {
        $category_cache[$catt->post_id][$catt->category_id] = get_category($catt->category_id);
    }
}

function update_post_caches(&$posts)
{
    global $post_cache, $category_cache, $comment_count_cache, $post_meta_cache;

    global $wpdb;

    // No point in doing all this work if we didn't match any posts.

    if (!$posts) {
        return;
    }

    // Get the categories for all the posts

    for ($i = 0, $iMax = count($posts); $i < $iMax; $i++) {
        $post_id_array[] = $posts[$i]->ID;

        $post_cache[$posts[$i]->ID] = &$posts[$i];

        $comment_count_cache[$posts[$i]->ID] = $posts[$i]->comment_count;
    }

    $post_id_list = implode(',', $post_id_array);

    update_post_category_cache($post_id_list);

    // Get post-meta info

    if ($meta_list = $wpdb->get_results("SELECT post_id, meta_key, meta_value FROM $wpdb->postmeta WHERE post_id IN($post_id_list) ORDER BY post_id, meta_key", ARRAY_A)) {
        // Change from flat structure to hierarchical:

        $post_meta_cache = [];

        foreach ($meta_list as $metarow) {
            $mpid = (int)$metarow['post_id'];

            $mkey = $metarow['meta_key'];

            $mval = $metarow['meta_value'];

            // Force subkeys to be array type:

            if (!isset($post_meta_cache[$mpid]) || !is_array($post_meta_cache[$mpid])) {
                $post_meta_cache[$mpid] = [];
            }

            if (!isset($post_meta_cache[$mpid][(string)$mkey]) || !is_array($post_meta_cache[$mpid][(string)$mkey])) {
                $post_meta_cache[$mpid][(string)$mkey] = [];
            }

            // Add a value to the current pid/key:

            $post_meta_cache[$mpid][$mkey][] = $mval;
        }
    }
}

function update_category_cache()
{
    return true;
}

function wp_head()
{
    do_action('wp_head');
}

function wp_footer()
{
    do_action('wp_footer');
}

function is_single($post = '')
{
    global $wp_query;

    if (!$wp_query->is_single) {
        return false;
    }

    if (empty($post)) {
        return true;
    }

    $post_obj = $wp_query->get_queried_object();

    if ($post == $post_obj->ID) {
        return true;
    } elseif ($post == $post_obj->post_title) {
        return true;
    } elseif ($post == $post_obj->post_name) {
        return true;
    }

    return false;
}

function is_page($page = '')
{
    global $wp_query;

    if (!$wp_query->is_page) {
        return false;
    }

    if (empty($page)) {
        return true;
    }

    $page_obj = $wp_query->get_queried_object();

    if ($page == $page_obj->ID) {
        return true;
    } elseif ($page == $page_obj->post_title) {
        return true;
    } elseif ($page == $page_obj->post_name) {
        return true;
    }

    return false;
}

function is_attachment()
{
    global $wp_query;

    return $wp_query->is_attachment;
}

function is_preview()
{
    global $wp_query;

    return $wp_query->is_preview;
}

function is_archive()
{
    global $wp_query;

    return $wp_query->is_archive;
}

function is_date()
{
    global $wp_query;

    return $wp_query->is_date;
}

function is_year()
{
    global $wp_query;

    return $wp_query->is_year;
}

function is_month()
{
    global $wp_query;

    return $wp_query->is_month;
}

function is_day()
{
    global $wp_query;

    return $wp_query->is_day;
}

function is_time()
{
    global $wp_query;

    return $wp_query->is_time;
}

function is_author($author = '')
{
    global $wp_query;

    if (!$wp_query->is_author) {
        return false;
    }

    if (empty($author)) {
        return true;
    }

    $author_obj = $wp_query->get_queried_object();

    if ($author == $author_obj->ID) {
        return true;
    } elseif ($author == $author_obj->nickname) {
        return true;
    } elseif ($author == $author_obj->user_nicename) {
        return true;
    }

    return false;
}

function is_category($category = '')
{
    global $wp_query;

    if (!$wp_query->is_category) {
        return false;
    }

    if (empty($category)) {
        return true;
    }

    $cat_obj = $wp_query->get_queried_object();

    if ($category == $cat_obj->cat_ID) {
        return true;
    } elseif ($category == $cat_obj->cat_name) {
        return true;
    } elseif ($category == $cat_obj->category_nicename) {
        return true;
    }

    return false;
}

function is_search()
{
    global $wp_query;

    return $wp_query->is_search;
}

function is_feed()
{
    global $wp_query;

    return $wp_query->is_feed;
}

function is_trackback()
{
    global $wp_query;

    return $wp_query->is_trackback;
}

function is_admin()
{
    global $wp_query;

    return ($wp_query->is_admin || mb_strstr($_SERVER['REQUEST_URI'], 'wp-admin/'));
}

function is_home()
{
    global $wp_query;

    return $wp_query->is_home;
}

function is_404()
{
    global $wp_query;

    return $wp_query->is_404;
}

function is_comments_popup()
{
    global $wp_query;

    return $wp_query->is_comments_popup;
}

function is_paged()
{
    global $wp_query;

    return $wp_query->is_paged;
}

function in_the_loop()
{
    global $wp_query;

    return $wp_query->in_the_loop;
}

function get_query_var($var)
{
    global $wp_query;

    return $wp_query->get($var);
}

function have_posts()
{
    global $wp_query;

    return $wp_query->have_posts();
}

function rewind_posts()
{
    global $wp_query;

    return $wp_query->rewind_posts();
}

function the_post()
{
    global $wp_query;

    $wp_query->the_post();
}

function get_theme_root()
{
    return apply_filters('theme_root', ABSPATH . 'wp-content/themes');
}

function get_theme_root_uri()
{
    return apply_filters('theme_root_uri', get_settings('siteurl') . '/wp-content/themes', get_settings('siteurl'));
}

function get_stylesheet()
{
    return apply_filters('stylesheet', get_settings('stylesheet'));
}

function get_stylesheet_directory()
{
    $stylesheet = get_stylesheet();

    $stylesheet_dir = get_theme_root() . "/$stylesheet";

    return apply_filters('stylesheet_directory', $stylesheet_dir, $stylesheet);
}

function get_stylesheet_directory_uri()
{
    $stylesheet = rawurlencode(get_stylesheet());

    $stylesheet_dir_uri = get_theme_root_uri() . "/$stylesheet";

    return apply_filters('stylesheet_directory_uri', $stylesheet_dir_uri, $stylesheet);
}

function get_stylesheet_uri()
{
    $stylesheet_dir_uri = get_stylesheet_directory_uri();

    $stylesheet_uri = $stylesheet_dir_uri . '/style.css';

    return apply_filters('stylesheet_uri', $stylesheet_uri, $stylesheet_dir_uri);
}

function get_template()
{
    $template = get_settings('template');

    if (!file_exists(get_theme_root() . "/$template")) { //works for dirs too
        update_option('template', 'default');

        update_option('stylesheet', 'default');
    }

    return apply_filters('template', get_settings('template'));
}

function get_template_directory()
{
    $template = get_template();

    $template_dir = get_theme_root() . "/$template";

    return apply_filters('template_directory', $template_dir, $template);
}

function get_template_directory_uri()
{
    $template = get_template();

    $template_dir_uri = get_theme_root_uri() . "/$template";

    return apply_filters('template_directory_uri', $template_dir_uri, $template);
}

function get_theme_data($theme_file)
{
    $theme_data = implode('', file($theme_file));

    preg_match('|Theme Name:(.*)|i', $theme_data, $theme_name);

    preg_match('|Theme URI:(.*)|i', $theme_data, $theme_uri);

    preg_match('|Description:(.*)|i', $theme_data, $description);

    preg_match('|Author:(.*)|i', $theme_data, $author_name);

    preg_match('|Author URI:(.*)|i', $theme_data, $author_uri);

    preg_match('|Template:(.*)|i', $theme_data, $template);

    if (preg_match('|Version:(.*)|i', $theme_data, $version)) {
        $version = trim($version[1]);
    } else {
        $version = '';
    }

    if (preg_match('|Status:(.*)|i', $theme_data, $status)) {
        $status = trim($status[1]);
    } else {
        $status = 'publish';
    }

    $description = wptexturize(trim($description[1]));

    $name = $theme_name[1];

    $name = trim($name);

    $theme = $name;

    if ('' == $author_uri[1]) {
        $author = trim($author_name[1]);
    } else {
        $author = '<a href="' . trim($author_uri[1]) . '" title="' . __('Visit author homepage') . '">' . trim($author_name[1]) . '</a>';
    }

    return ['Name' => $name, 'Title' => $theme, 'Description' => $description, 'Author' => $author, 'Version' => $version, 'Template' => $template[1], 'Status' => $status];
}

function get_themes()
{
    global $wp_themes;

    global $wp_broken_themes;

    if (isset($wp_themes)) {
        return $wp_themes;
    }

    $themes = [];

    $wp_broken_themes = [];

    $theme_root = get_theme_root();

    $theme_loc = str_replace(ABSPATH, '', $theme_root);

    // Files in wp-content/themes directory

    $themes_dir = @dir($theme_root);

    if ($themes_dir) {
        while (false !== ($theme_dir = $themes_dir->read())) {
            if (is_dir($theme_root . '/' . $theme_dir) && is_readable($theme_root . '/' . $theme_dir)) {
                if ('.' == $theme_dir[0] || '..' == $theme_dir || 'CVS' == $theme_dir) {
                    continue;
                }

                $stylish_dir = @dir($theme_root . '/' . $theme_dir);

                $found_stylesheet = false;

                while (false !== ($theme_file = $stylish_dir->read())) {
                    if ('style.css' == $theme_file) {
                        $theme_files[] = $theme_dir . '/' . $theme_file;

                        $found_stylesheet = true;

                        break;
                    }
                }

                if (!$found_stylesheet) {
                    $wp_broken_themes[$theme_dir] = ['Name' => $theme_dir, 'Title' => $theme_dir, 'Description' => __('Stylesheet is missing.')];
                }
            }
        }
    }

    if (!$themes_dir || !$theme_files) {
        return $themes;
    }

    sort($theme_files);

    foreach ($theme_files as $theme_file) {
        if (!is_readable("$theme_root/$theme_file")) {
            $wp_broken_themes[$theme_file] = ['Name' => $theme_file, 'Title' => $theme_file, 'Description' => __('File not readable.')];

            continue;
        }

        $theme_data = get_theme_data("$theme_root/$theme_file");

        $name = $theme_data['Name'];

        $title = $theme_data['Title'];

        $description = wptexturize($theme_data['Description']);

        $version = $theme_data['Version'];

        $author = $theme_data['Author'];

        $template = $theme_data['Template'];

        $stylesheet = dirname($theme_file);

        foreach (['png', 'gif', 'jpg', 'jpeg'] as $ext) {
            if (file_exists("$theme_root/$stylesheet/screenshot.$ext")) {
                $screenshot = "screenshot.$ext";

                break;
            }
        }

        if (empty($name)) {
            $name = dirname($theme_file);

            $title = $name;
        }

        if (empty($template)) {
            if (file_exists(dirname("$theme_root/$theme_file/index.php"))) {
                $template = dirname($theme_file);
            } else {
                continue;
            }
        }

        $template = trim($template);

        if (!file_exists("$theme_root/$template/index.php")) {
            $wp_broken_themes[$name] = ['Name' => $name, 'Title' => $title, 'Description' => __('Template is missing.')];

            continue;
        }

        $stylesheet_files = [];

        $stylesheet_dir = @dir("$theme_root/$stylesheet");

        if ($stylesheet_dir) {
            while (false !== ($file = $stylesheet_dir->read())) {
                if (!preg_match('|^\.+$|', $file) && preg_match('|\.css$|', $file)) {
                    $stylesheet_files[] = "$theme_loc/$stylesheet/$file";
                }
            }
        }

        $template_files = [];

        $template_dir = @dir("$theme_root/$template");

        if ($template_dir) {
            while (false !== ($file = $template_dir->read())) {
                if (!preg_match('|^\.+$|', $file) && preg_match('|\.php$|', $file)) {
                    $template_files[] = "$theme_loc/$template/$file";
                }
            }
        }

        $template_dir = dirname($template_files[0]);

        $stylesheet_dir = dirname($stylesheet_files[0]);

        if (empty($template_dir)) {
            $template_dir = '/';
        }

        if (empty($stylesheet_dir)) {
            $stylesheet_dir = '/';
        }

        // Check for theme name collision.  This occurs if a theme is copied to

        // a new theme directory and the theme header is not updated.  Whichever

        // theme is first keeps the name.  Subsequent themes get a suffix applied.

        // The Default and Classic themes always trump their pretenders.

        if (isset($themes[$name])) {
            if (('WordPress Default' == $name || 'WordPress Classic' == $name)
                && ('default' == $stylesheet || 'classic' == $stylesheet)) {
                // If another theme has claimed to be one of our default themes, move

                // them aside.

                $suffix = $themes[$name]['Stylesheet'];

                $new_name = "$name/$suffix";

                $themes[$new_name] = $themes[$name];

                $themes[$new_name]['Name'] = $new_name;
            } else {
                $name = "$name/$stylesheet";
            }
        }

        $themes[$name] = [
'Name' => $name,
                          'Title' => $title,
                          'Description' => $description,
                          'Author' => $author,
                          'Version' => $version,
                          'Template' => $template,
                          'Stylesheet' => $stylesheet,
                          'Template Files' => $template_files,
                          'Stylesheet Files' => $stylesheet_files,
                          'Template Dir' => $template_dir,
                          'Stylesheet Dir' => $stylesheet_dir,
                          'Status' => $theme_data['Status'],
                          'Screenshot' => $screenshot,
        ];
    }

    // Resolve theme dependencies.

    $theme_names = array_keys($themes);

    foreach ($theme_names as $theme_name) {
        $themes[$theme_name]['Parent Theme'] = '';

        if ($themes[$theme_name]['Stylesheet'] != $themes[$theme_name]['Template']) {
            foreach ($theme_names as $parent_theme_name) {
                if (($themes[$parent_theme_name]['Stylesheet'] == $themes[$parent_theme_name]['Template']) && ($themes[$parent_theme_name]['Template'] == $themes[$theme_name]['Template'])) {
                    $themes[$theme_name]['Parent Theme'] = $themes[$parent_theme_name]['Name'];

                    break;
                }
            }
        }
    }

    $wp_themes = $themes;

    return $themes;
}

function get_theme($theme)
{
    $themes = get_themes();

    return $themes[$theme] ?? null;
}

function get_current_theme()
{
    $themes = get_themes();

    $theme_names = array_keys($themes);

    $current_template = get_settings('template');

    $current_stylesheet = get_settings('stylesheet');

    $current_theme = 'WordPress Default';

    if ($themes) {
        foreach ($theme_names as $theme_name) {
            if ($themes[$theme_name]['Stylesheet'] == $current_stylesheet
                && $themes[$theme_name]['Template'] == $current_template) {
                $current_theme = $themes[$theme_name]['Name'];

                break;
            }
        }
    }

    return $current_theme;
}

function get_query_template($type)
{
    $template = '';

    if (file_exists(TEMPLATEPATH . "/{$type}.php")) {
        $template = TEMPLATEPATH . "/{$type}.php";
    }

    return apply_filters("{$type}_template", $template);
}

function get_404_template()
{
    return get_query_template('404');
}

function get_archive_template()
{
    return get_query_template('archive');
}

function get_author_template()
{
    return get_query_template('author');
}

function get_category_template()
{
    $template = '';

    if (file_exists(TEMPLATEPATH . '/category-' . get_query_var('cat') . '.php')) {
        $template = TEMPLATEPATH . '/category-' . get_query_var('cat') . '.php';
    } elseif (file_exists(TEMPLATEPATH . '/category.php')) {
        $template = TEMPLATEPATH . '/category.php';
    }

    return apply_filters('category_template', $template);
}

function get_date_template()
{
    return get_query_template('date');
}

function get_home_template()
{
    $template = '';

    if (file_exists(TEMPLATEPATH . '/home.php')) {
        $template = TEMPLATEPATH . '/home.php';
    } elseif (file_exists(TEMPLATEPATH . '/index.php')) {
        $template = TEMPLATEPATH . '/index.php';
    }

    return apply_filters('home_template', $template);
}

function get_page_template()
{
    global $wp_query;

    $id = $wp_query->post->ID;

    $template = get_post_meta($id, '_wp_page_template', true);

    if ('default' == $template) {
        $template = '';
    }

    if (!empty($template) && file_exists(TEMPLATEPATH . "/$template")) {
        $template = TEMPLATEPATH . "/$template";
    } elseif (file_exists(TEMPLATEPATH . '/page.php')) {
        $template = TEMPLATEPATH . '/page.php';
    } else {
        $template = '';
    }

    return apply_filters('page_template', $template);
}

function get_paged_template()
{
    return get_query_template('paged');
}

function get_search_template()
{
    return get_query_template('search');
}

function get_single_template()
{
    return get_query_template('single');
}

function get_attachment_template()
{
    global $posts;

    $type = explode('/', $posts[0]->post_mime_type);

    if ($template = get_query_template($type[0])) {
        return $template;
    } elseif ($template = get_query_template($type[1])) {
        return $template;
    } elseif ($template = get_query_template("$type[0]_$type[1]")) {
        return $template;
    }
  

    return get_query_template('attachment');
}

function get_comments_popup_template()
{
    if (file_exists(TEMPLATEPATH . '/comments-popup.php')) {
        $template = TEMPLATEPATH . '/comments-popup.php';
    } else {
        $template = get_theme_root() . '/default/comments-popup.php';
    }

    return apply_filters('comments_popup_template', $template);
}

// Borrowed from the PHP Manual user notes. Convert entities, while
// preserving already-encoded entities:
function htmlentities2($myHTML)
{
    $translation_table = get_html_translation_table(HTML_ENTITIES, ENT_QUOTES);

    $translation_table[chr(38)] = '&';

    return preg_replace("/&(?![A-Za-z]{0,4}\w{2,3};|#[0-9]{2,3};)/", '&amp;', strtr($myHTML, $translation_table));
}

function is_plugin_page()
{
    global $plugin_page;

    if (isset($plugin_page)) {
        return true;
    }

    return false;
}

/*
add_query_arg: Returns a modified querystring by adding
a single key & value or an associative array.
Setting a key value to emptystring removes the key.
Omitting oldquery_or_uri uses the $_SERVER value.

Parameters:
add_query_arg(newkey, newvalue, oldquery_or_uri) or
add_query_arg(associative_array, oldquery_or_uri)
*/
function add_query_arg()
{
    $ret = '';

    if (is_array(func_get_arg(0))) {
        if (@func_num_args() < 2) {
            $uri = $_SERVER['REQUEST_URI'];
        } else {
            $uri = @func_get_arg(1);
        }
    } else {
        if (@func_num_args() < 3) {
            $uri = $_SERVER['REQUEST_URI'];
        } else {
            $uri = @func_get_arg(2);
        }
    }

    if ($frag = mb_strstr($uri, '#')) {
        $uri = mb_substr($uri, 0, -mb_strlen($frag));
    } else {
        $frag = '';
    }

    if (preg_match('|^https?://|i', $uri, $matches)) {
        $protocol = $matches[0];

        $uri = mb_substr($uri, mb_strlen($protocol));
    } else {
        $protocol = '';
    }

    if (mb_strstr($uri, '?')) {
        $parts = explode('?', $uri, 2);

        if (1 == count($parts)) {
            $base = '?';

            $query = $parts[0];
        } else {
            $base = $parts[0] . '?';

            $query = $parts[1];
        }
    } elseif (!empty($protocol) || mb_strstr($uri, '/')) {
        $base = $uri . '?';

        $query = '';
    } else {
        $base = '';

        $query = $uri;
    }

    parse_str($query, $qs);

    if (is_array(func_get_arg(0))) {
        $kayvees = func_get_arg(0);

        $qs = array_merge($qs, $kayvees);
    } else {
        $qs[func_get_arg(0)] = func_get_arg(1);
    }

    foreach ($qs as $k => $v) {
        if ('' != $v) {
            if ('' != $ret) {
                $ret .= '&';
            }

            $ret .= "$k=$v";
        }
    }

    $ret = $protocol . $base . $ret . $frag;

    return trim($ret, '?');
}

function remove_query_arg($key, $query)
{
    return add_query_arg($key, '', $query);
}

function load_template($file)
{
    global $posts, $post, $wp_did_header, $wp_did_template_redirect, $wp_query, $wp_rewrite, $wpdb;

    extract($wp_query->query_vars);

    require_once $file;
}

function add_magic_quotes($array)
{
    global $wpdb;

    foreach ($array as $k => $v) {
        if (is_array($v)) {
            $array[$k] = add_magic_quotes($v);
        } else {
            $array[$k] = $wpdb->escape($v);
        }
    }

    return $array;
}

function wp_remote_fopen($uri)
{
    if (ini_get('allow_url_fopen')) {
        $fp = fopen($uri, 'rb');

        if (!$fp) {
            return false;
        }

        $linea = '';

        while ($remote_read = fread($fp, 4096)) {
            $linea .= $remote_read;
        }

        fclose($fp);

        return $linea;
    } elseif (function_exists('curl_init')) {
        $handle = curl_init();

        curl_setopt($handle, CURLOPT_URL, $uri);

        curl_setopt($handle, CURLOPT_CONNECTTIMEOUT, 1);

        curl_setopt($handle, CURLOPT_RETURNTRANSFER, 1);

        $buffer = curl_exec($handle);

        curl_close($handle);

        return $buffer;
    }
  

    return false;
}

function wp($query_vars = '')
{
    global $wp;

    $wp->main($query_vars);
}

function status_header($header)
{
    if (200 == $header) {
        $text = 'OK';
    } elseif (301 == $header) {
        $text = 'Moved Permanently';
    } elseif (302 == $header) {
        $text = 'Moved Temporarily';
    } elseif (304 == $header) {
        $text = 'Not Modified';
    } elseif (404 == $header) {
        $text = 'Not Found';
    } elseif (410 == $header) {
        $text = 'Gone';
    }

    @header("HTTP/1.1 $header $text");

    @header("Status: $header $text");
}

function nocache_headers()
{
    @header('Expires: Wed, 11 Jan 1984 05:00:00 GMT');

    @header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT');

    @header('Cache-Control: no-cache, must-revalidate, max-age=0');

    @header('Pragma: no-cache');
}

function get_usermeta($user_id, $meta_key = '')
{
    global $wpdb;

    $user_id = (int)$user_id;

    if (!empty($meta_key)) {
        $meta_key = str_ireplace("a-z0-9_", '', $meta_key);

        $metas = $wpdb->get_results("SELECT meta_key, meta_value FROM $wpdb->usermeta WHERE user_id = '$user_id' AND meta_key = '$meta_key'");
    } else {
        $metas = $wpdb->get_results("SELECT meta_key, meta_value FROM $wpdb->usermeta WHERE user_id = '$user_id'");
    }

    if (empty($metas)) {
        if (empty($meta_key)) {
            return [];
        }
  

        return '';
    }

    foreach ($metas as $index => $meta) {
        @$value = unserialize($meta->meta_value);

        if (false === $value) {
            $value = $meta->meta_value;
        }

        $values[] = $value;
    }

    if (1 == count($values)) {
        return $values[0];
    }
  

    return $values;
}

function update_usermeta($user_id, $meta_key, $meta_value)
{
    global $wpdb;

    if (!is_numeric($user_id)) {
        return false;
    }

    $meta_key = preg_replace('|[^a-z0-9_]|i', '', $meta_key);

    // FIXME: usermeta data is assumed to be already escaped

    if (is_string($meta_value)) {
        $meta_value = stripslashes($meta_value);
    }

    $meta_value = maybe_serialize($meta_value);

    $meta_value = $wpdb->escape($meta_value);

    if (empty($meta_value)) {
        delete_usermeta($user_id, $meta_key);
    }

    $cur = $wpdb->get_row("SELECT * FROM $wpdb->usermeta WHERE user_id = '$user_id' AND meta_key = '$meta_key'");

    if (!$cur) {
        $wpdb->query(
            "INSERT INTO $wpdb->usermeta ( user_id, meta_key, meta_value )
		VALUES
		( '$user_id', '$meta_key', '$meta_value' )"
        );
    } elseif ($cur->meta_value != $meta_value) {
        $wpdb->query("UPDATE $wpdb->usermeta SET meta_value = '$meta_value' WHERE user_id = '$user_id' AND meta_key = '$meta_key'");
    } else {
        return false;
    }

    $user = get_userdata($user_id);

    wp_cache_delete($user_id, 'users');

    wp_cache_delete($user->user_login, 'userlogins');

    return true;
}

function delete_usermeta($user_id, $meta_key, $meta_value = '')
{
    global $wpdb;

    if (!is_numeric($user_id)) {
        return false;
    }

    $meta_key = preg_replace('|[^a-z0-9_]|i', '', $meta_key);

    if (is_array($meta_value) || is_object($meta_value)) {
        $meta_value = serialize($meta_value);
    }

    $meta_value = trim($meta_value);

    if (!empty($meta_value)) {
        $wpdb->query("DELETE FROM $wpdb->usermeta WHERE user_id = '$user_id' AND meta_key = '$meta_key' AND meta_value = '$meta_value'");
    } else {
        $wpdb->query("DELETE FROM $wpdb->usermeta WHERE user_id = '$user_id' AND meta_key = '$meta_key'");
    }

    $user = get_userdata($user_id);

    wp_cache_delete($user_id, 'users');

    wp_cache_delete($user->user_login, 'userlogins');

    return true;
}

function register_activation_hook($file, $function)
{
    $file = plugin_basename($file);

    add_action('activate_' . $file, $function);
}

function register_deactivation_hook($file, $function)
{
    $file = plugin_basename($file);

    add_action('deactivate_' . $file, $function);
}

function plugin_basename($file)
{
    $file = preg_replace('|\\\\+|', '\\\\', $file);

    $file = preg_replace('/^.*wp-content[\\\\\/]plugins[\\\\\/]/', '', $file);

    return $file;
}

function get_num_queries()
{
    global $wpdb;

    return $wpdb->num_queries;
}

function wp_nonce_url($actionurl, $action = -1)
{
    return wp_specialchars(add_query_arg('_wpnonce', wp_create_nonce($action), $actionurl));
}

function wp_nonce_field($action = -1)
{
    echo '<input type="hidden" name="_wpnonce" value="' . wp_create_nonce($action) . '">';

    wp_referer_field();
}

function wp_referer_field()
{
    $ref = wp_specialchars($_SERVER['REQUEST_URI']);

    echo '<input type="hidden" name="_wp_http_referer" value="' . $ref . '">';

    if (wp_get_original_referer()) {
        $original_ref = wp_specialchars(stripslashes(wp_get_original_referer()));

        echo '<input type="hidden" name="_wp_original_http_referer" value="' . $original_ref . '">';
    }
}

function wp_original_referer_field()
{
    echo '<input type="hidden" name="_wp_original_http_referer" value="' . wp_specialchars(stripslashes($_SERVER['REQUEST_URI'])) . '">';
}

function wp_get_referer()
{
    foreach ([$_REQUEST['_wp_http_referer'], Request::getString('HTTP_REFERER', '', 'SERVER')] as $ref) {
        if (!empty($ref)) {
            return $ref;
        }
    }

    return false;
}

function wp_get_original_referer()
{
    if (!empty($_REQUEST['_wp_original_http_referer'])) {
        return $_REQUEST['_wp_original_http_referer'];
    }

    return false;
}

function wp_explain_nonce($action)
{
    if (-1 !== $action && preg_match('/([a-z]+)-([a-z]+)(_(.+))?/', $action, $matches)) {
        $verb = $matches[1];

        $noun = $matches[2];

        $trans = [];

        $trans['update']['attachment'] = [__('Are you sure you want to edit this attachment: &quot;%s&quot;?'), 'get_the_title'];

        $trans['add']['category'] = [__('Are you sure you want to add this category?'), false];

        $trans['delete']['category'] = [__('Are you sure you want to delete this category: &quot;%s&quot;?'), 'get_catname'];

        $trans['update']['category'] = [__('Are you sure you want to edit this category: &quot;%s&quot;?'), 'get_catname'];

        $trans['delete']['comment'] = [__('Are you sure you want to delete this comment: &quot;%s&quot;?'), 'use_id'];

        $trans['unapprove']['comment'] = [__('Are you sure you want to unapprove this comment: &quot;%s&quot;?'), 'use_id'];

        $trans['approve']['comment'] = [__('Are you sure you want to approve this comment: &quot;%s&quot;?'), 'use_id'];

        $trans['update']['comment'] = [__('Are you sure you want to edit this comment: &quot;%s&quot;?'), 'use_id'];

        $trans['bulk']['comments'] = [__('Are you sure you want to bulk modify comments?'), false];

        $trans['moderate']['comments'] = [__('Are you sure you want to moderate comments?'), false];

        $trans['add']['bookmark'] = [__('Are you sure you want to add this bookmark?'), false];

        $trans['delete']['bookmark'] = [__('Are you sure you want to delete this bookmark: &quot;%s&quot;?'), 'use_id'];

        $trans['update']['bookmark'] = [__('Are you sure you want to edit this bookmark: &quot;%s&quot;?'), 'use_id'];

        $trans['bulk']['bookmarks'] = [__('Are you sure you want to bulk modify bookmarks?'), false];

        $trans['add']['page'] = [__('Are you sure you want to add this page?'), false];

        $trans['delete']['page'] = [__('Are you sure you want to delete this page: &quot;%s&quot;?'), 'get_the_title'];

        $trans['update']['page'] = [__('Are you sure you want to edit this page: &quot;%s&quot;?'), 'get_the_title'];

        $trans['edit']['plugin'] = [__('Are you sure you want to edit this plugin file: &quot;%s&quot;?'), 'use_id'];

        $trans['activate']['plugin'] = [__('Are you sure you want to activate this plugin: &quot;%s&quot;?'), 'use_id'];

        $trans['deactivate']['plugin'] = [__('Are you sure you want to deactivate this plugin: &quot;%s&quot;?'), 'use_id'];

        $trans['add']['post'] = [__('Are you sure you want to add this post?'), false];

        $trans['delete']['post'] = [__('Are you sure you want to delete this post: &quot;%s&quot;?'), 'get_the_title'];

        $trans['update']['post'] = [__('Are you sure you want to edit this post: &quot;%s&quot;?'), 'get_the_title'];

        $trans['add']['user'] = [__('Are you sure you want to add this user?'), false];

        $trans['delete']['users'] = [__('Are you sure you want to delete users?'), false];

        $trans['bulk']['users'] = [__('Are you sure you want to bulk modify users?'), false];

        $trans['update']['user'] = [__('Are you sure you want to edit this user: &quot;%s&quot;?'), 'get_author_name'];

        $trans['update']['profile'] = [__('Are you sure you want to modify the profile for: &quot;%s&quot;?'), 'get_author_name'];

        $trans['update']['options'] = [__('Are you sure you want to edit your settings?'), false];

        $trans['update']['permalink'] = [__('Are you sure you want to change your permalink structure to: %s?'), 'use_id'];

        $trans['edit']['file'] = [__('Are you sure you want to edit this file: &quot;%s&quot;?'), 'use_id'];

        $trans['edit']['theme'] = [__('Are you sure you want to edit this theme file: &quot;%s&quot;?'), 'use_id'];

        $trans['switch']['theme'] = [__('Are you sure you want to switch to this theme: &quot;%s&quot;?'), 'use_id'];

        if (isset($trans[$verb][$noun])) {
            if (!empty($trans[$verb][$noun][1])) {
                $lookup = $trans[$verb][$noun][1];

                $object = $matches[4];

                if ('use_id' != $lookup) {
                    $object = call_user_func($lookup, $object);
                }

                return sprintf($trans[$verb][$noun][0], $object);
            }
  

            return $trans[$verb][$noun][0];
        }
    }

    return __('Are you sure you want to do this?');
}

function wp_nonce_ays($action)
{
    global $pagenow, $menu, $submenu, $parent_file, $submenu_file;

    $adminurl = get_settings('siteurl') . '/wp-admin';

    if (wp_get_referer()) {
        $adminurl = wp_get_referer();
    }

    $title = __('WordPress Confirmation');

    // Remove extra layer of slashes.

    $_POST = stripslashes_deep($_POST);

    if ($_POST) {
        $q = http_build_query($_POST, null, '&');

        $q = explode(ini_get('arg_separator.output'), $q);

        $html .= "\t<form method='post' action='$pagenow'>\n";

        foreach ((array)$q as $a) {
            $v = mb_substr(mb_strstr($a, '='), 1);

            $k = mb_substr($a, 0, -(mb_strlen($v) + 1));

            $html .= "\t\t<input type='hidden' name='" . wp_specialchars(urldecode($k), 1) . "' value='" . wp_specialchars(urldecode($v), 1) . "'>\n";
        }

        $html .= "\t\t<input type='hidden' name='_wpnonce' value='" . wp_create_nonce($action) . "'>\n";

        $html .= "\t\t<div id='message' class='confirm fade'>\n\t\t<p>" . wp_explain_nonce($action) . "</p>\n\t\t<p><a href='$adminurl'>" . __('No') . "</a> <input type='submit' value='" . __('Yes') . "'></p>\n\t\t</div>\n\t</form>\n";
    } else {
        $html .= "\t<div id='message' class='confirm fade'>\n\t<p>" . wp_explain_nonce($action) . "</p>\n\t<p><a href='$adminurl'>" . __('No') . "</a> <a href='" . add_query_arg('_wpnonce', wp_create_nonce($action), $_SERVER['REQUEST_URI']) . "'>" . __('Yes') . "</a></p>\n\t</div>\n";
    }

    $html .= "</body>\n</html>";

    wp_die($html, $title);
}

function wp_die($message, $title = '')
{
    header('Content-Type: text/html; charset=utf-8');

    if (empty($title)) {
        $title = __('WordPress &rsaquo; Error');
    } ?>
    <!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
    <html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <title><?php echo $title ?></title>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
        <style media="screen" type="text/css">
            <!--
            html {
                background: #eee;
            }

            body {
                background: #fff;
                color: #000;
                font-family: Georgia, "Times New Roman", Times, serif;
                margin-left: 25%;
                margin-right: 25%;
                padding: .2em 2em;
            }

            h1 {
                color: #006;
                font-size: 18px;
                font-weight: lighter;
            }

            h2 {
                font-size: 16px;
            }

            p, li, dt {
                line-height: 140%;
                padding-bottom: 2px;
            }

            ul, ol {
                padding: 5px 5px 5px 20px;
            }

            #logo {
                margin-bottom: 2em;
            }

            -->
        </style>
    </head>
    <body>
    <h1 id="logo"><img alt="WordPress" src="<?php echo get_settings('siteurl'); ?>/wp-admin/images/wordpress-logo.png"></h1>
    <p><?php echo $message; ?></p>
    </body>
    </html>
    <?php

    die();
}

?>