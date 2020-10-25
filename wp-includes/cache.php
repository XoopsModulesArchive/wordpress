<?php

function wp_cache_add($key, $data, $flag = '', $expire = 0)
{
    global $wp_object_cache;

    return $wp_object_cache->add($key, $data, $flag, $expire);
}

function wp_cache_close()
{
    global $wp_object_cache;

    return $wp_object_cache->save();
}

function wp_cache_delete($id, $flag = '')
{
    global $wp_object_cache;

    return $wp_object_cache->delete($id, $flag);
}

function wp_cache_flush()
{
    global $wp_object_cache;

    return $wp_object_cache->flush();
}

function wp_cache_get($id, $flag = '')
{
    global $wp_object_cache;

    return $wp_object_cache->get($id, $flag);
}

function wp_cache_init()
{
    global $wp_object_cache;

    $wp_object_cache = new WP_Object_Cache();
}

function wp_cache_replace($key, $data, $flag = '', $expire = 0)
{
    global $wp_object_cache;

    return $wp_object_cache->replace($key, $data, $flag, $expire);
}

function wp_cache_set($key, $data, $flag = '', $expire = 0)
{
    global $wp_object_cache;

    return $wp_object_cache->set($key, $data, $flag, $expire);
}

define('CACHE_SERIAL_HEADER', "<?php\n/*");
define('CACHE_SERIAL_FOOTER', "*/\n?" . '>');

class WP_Object_Cache
{
    public $cache_dir;

    public $cache_enabled = false;

    public $expiration_time = 900;

    public $flock_filename = 'wp_object_cache.lock';

    public $mutex;

    public $cache = [];

    public $dirty_objects = [];

    public $non_existant_objects = [];

    public $global_groups = ['users', 'userlogins', 'usermeta'];

    public $blog_id;

    public $cold_cache_hits = 0;

    public $warm_cache_hits = 0;

    public $cache_misses = 0;

    public $secret = '';

    public function acquire_lock()
    {
        // Acquire a write lock.

        $this->mutex = @fopen($this->cache_dir . $this->flock_filename, 'wb');

        if (false === $this->mutex) {
            return false;
        }

        flock($this->mutex, LOCK_EX);

        return true;
    }

    public function add($id, $data, $group = 'default', $expire = '')
    {
        if (empty($group)) {
            $group = 'default';
        }

        if (false !== $this->get($id, $group, false)) {
            return false;
        }

        return $this->set($id, $data, $group, $expire);
    }

    public function delete($id, $group = 'default', $force = false)
    {
        if (empty($group)) {
            $group = 'default';
        }

        if (!$force && false === $this->get($id, $group, false)) {
            return false;
        }

        unset($this->cache[$group][$id]);

        $this->non_existant_objects[$group][$id] = true;

        $this->dirty_objects[$group][] = $id;

        return true;
    }

    public function flush()
    {
        if (!$this->cache_enabled) {
            return true;
        }

        if (!$this->acquire_lock()) {
            return false;
        }

        $this->rm_cache_dir();

        $this->cache = [];

        $this->dirty_objects = [];

        $this->non_existant_objects = [];

        $this->release_lock();

        return true;
    }

    public function get($id, $group = 'default', $count_hits = true)
    {
        if (empty($group)) {
            $group = 'default';
        }

        if (isset($this->cache[$group][$id])) {
            if ($count_hits) {
                $this->warm_cache_hits += 1;
            }

            return $this->cache[$group][$id];
        }

        if (isset($this->non_existant_objects[$group][$id])) {
            return false;
        }

        //  If caching is not enabled, we have to fall back to pulling from the DB.

        if (!$this->cache_enabled) {
            if (!isset($this->cache[$group])) {
                $this->load_group_from_db($group);
            }

            if (isset($this->cache[$group][$id])) {
                $this->cold_cache_hits += 1;

                return $this->cache[$group][$id];
            }

            $this->non_existant_objects[$group][$id] = true;

            $this->cache_misses += 1;

            return false;
        }

        $cache_file = $this->cache_dir . $this->get_group_dir($group) . '/' . $this->hash($id) . '.php';

        if (!file_exists($cache_file)) {
            $this->non_existant_objects[$group][$id] = true;

            $this->cache_misses += 1;

            return false;
        }

        // If the object has expired, remove it from the cache and return false to force

        // a refresh.

        $now = time();

        if ((filemtime($cache_file) + $this->expiration_time) <= $now) {
            $this->cache_misses += 1;

            $this->delete($id, $group, true);

            return false;
        }

        $this->cache[$group][$id] = unserialize(base64_decode(mb_substr(@file_get_contents($cache_file), mb_strlen(CACHE_SERIAL_HEADER), -mb_strlen(CACHE_SERIAL_FOOTER)), true));

        if (false === $this->cache[$group][$id]) {
            $this->cache[$group][$id] = '';
        }

        $this->cold_cache_hits += 1;

        return $this->cache[$group][$id];
    }

    public function get_group_dir($group)
    {
        if (false !== array_search($group, $this->global_groups, true)) {
            return $group;
        }

        return "{$this->blog_id}/$group";
    }

    public function hash($data)
    {
        if (function_exists('hash_hmac')) {
            return hash_hmac('md5', $data, $this->secret);
        }
  

        return md5($data . $this->secret);
    }

    public function load_group_from_db($group)
    {
        global $wpdb;

        if ('category' == $group) {
            $this->cache['category'] = [];

            if ($dogs = $wpdb->get_results("SELECT * FROM $wpdb->categories")) {
                foreach ($dogs as $catt) {
                    $this->cache['category'][$catt->cat_ID] = $catt;
                }

                foreach ($this->cache['category'] as $catt) {
                    $curcat = $catt->cat_ID;

                    $fullpath = '/' . $this->cache['category'][$catt->cat_ID]->category_nicename;

                    while (0 != $this->cache['category'][$curcat]->category_parent) {
                        $curcat = $this->cache['category'][$curcat]->category_parent;

                        $fullpath = '/' . $this->cache['category'][$curcat]->category_nicename . $fullpath;
                    }

                    $this->cache['category'][$catt->cat_ID]->fullpath = $fullpath;
                }
            }
        } elseif ('options' == $group) {
            $wpdb->hide_errors();

            if (!$options = $wpdb->get_results("SELECT option_name, option_value FROM $wpdb->options WHERE autoload = 'yes'")) {
                $options = $wpdb->get_results("SELECT option_name, option_value FROM $wpdb->options");
            }

            $wpdb->show_errors();

            if (!$options) {
                return;
            }

            foreach ($options as $option) {
                $this->cache['options'][$option->option_name] = $option->option_value;
            }
        }
    }

    public function make_group_dir($group, $perms)
    {
        $group_dir = $this->get_group_dir($group);

        $make_dir = '';

        foreach (preg_split('/', $group_dir) as $subdir) {
            $make_dir .= "$subdir/";

            if (!file_exists($this->cache_dir . $make_dir)) {
                if (!@mkdir($this->cache_dir . $make_dir)) {
                    break;
                }

                @chmod($this->cache_dir . $make_dir, $perms);
            }

            if (!file_exists($this->cache_dir . $make_dir . 'index.php')) {
                $file_perms = $perms & 0000666;

                @touch($this->cache_dir . $make_dir . 'index.php');

                @chmod($this->cache_dir . $make_dir . 'index.php', $file_perms);
            }
        }

        return $this->cache_dir . "$group_dir/";
    }

    public function rm_cache_dir()
    {
        $dir = $this->cache_dir;

        $dir = rtrim($dir, DIRECTORY_SEPARATOR);

        $top_dir = $dir;

        $stack = [$dir];

        $index = 0;

        while ($index < count($stack)) {
            # Get indexed directory from stack

            $dir = $stack[$index];

            $dh = @opendir($dir);

            if (!$dh) {
                return false;
            }

            while (false !== ($file = @readdir($dh))) {
                if ('.' == $file or '..' == $file) {
                    continue;
                }

                if (@is_dir($dir . DIRECTORY_SEPARATOR . $file)) {
                    $stack[] = $dir . DIRECTORY_SEPARATOR . $file;
                } elseif (@is_file($dir . DIRECTORY_SEPARATOR . $file)) {
                    @unlink($dir . DIRECTORY_SEPARATOR . $file);
                }
            }

            $index++;
        }

        $stack = array_reverse($stack);  // Last added dirs are deepest

        foreach ($stack as $dir) {
            if ($dir != $top_dir) {
                @rmdir($dir);
            }
        }
    }

    public function release_lock()
    {
        // Release write lock.

        flock($this->mutex, LOCK_UN);

        fclose($this->mutex);
    }

    public function replace($id, $data, $group = 'default', $expire = '')
    {
        if (empty($group)) {
            $group = 'default';
        }

        if (false === $this->get($id, $group, false)) {
            return false;
        }

        return $this->set($id, $data, $group, $expire);
    }

    public function set($id, $data, $group = 'default', $expire = '')
    {
        if (empty($group)) {
            $group = 'default';
        }

        if (null === $data) {
            $data = '';
        }

        $this->cache[$group][$id] = $data;

        unset($this->non_existant_objects[$group][$id]);

        $this->dirty_objects[$group][] = $id;

        return true;
    }

    public function save()
    {
        //$this->stats();

        if (!$this->cache_enabled) {
            return true;
        }

        if (empty($this->dirty_objects)) {
            return true;
        }

        // Give the new dirs the same perms as wp-content.

        $stat = stat(ABSPATH . 'wp-content');

        $dir_perms = $stat['mode'] & 0007777; // Get the permission bits.
        $file_perms = $dir_perms & 0000666; // Remove execute bits for files.

        // Make the base cache dir.

        if (!file_exists($this->cache_dir)) {
            if (!@mkdir($this->cache_dir)) {
                return false;
            }

            @chmod($this->cache_dir, $dir_perms);
        }

        if (!file_exists($this->cache_dir . 'index.php')) {
            @touch($this->cache_dir . 'index.php');

            @chmod($this->cache_dir . 'index.php', $file_perms);
        }

        if (!$this->acquire_lock()) {
            return false;
        }

        // Loop over dirty objects and save them.

        $errors = 0;

        foreach ($this->dirty_objects as $group => $ids) {
            $group_dir = $this->make_group_dir($group, $dir_perms);

            $ids = array_unique($ids);

            foreach ($ids as $id) {
                $cache_file = $group_dir . $this->hash($id) . '.php';

                // Remove the cache file if the key is not set.

                if (!isset($this->cache[$group][$id])) {
                    if (file_exists($cache_file)) {
                        @unlink($cache_file);
                    }

                    continue;
                }

                $temp_file = tempnam($group_dir, 'tmp');

                $serial = CACHE_SERIAL_HEADER . base64_encode(serialize($this->cache[$group][$id])) . CACHE_SERIAL_FOOTER;

                $fd = @fopen($temp_file, 'wb');

                if (false === $fd) {
                    $errors++;

                    continue;
                }

                fwrite($fd, $serial);

                fclose($fd);

                if (!@rename($temp_file, $cache_file)) {
                    if (@copy($temp_file, $cache_file)) {
                        @unlink($temp_file);
                    } else {
                        $errors++;
                    }
                }

                @chmod($cache_file, $file_perms);
            }
        }

        $this->dirty_objects = [];

        $this->release_lock();

        if ($errors) {
            return false;
        }

        return true;
    }

    public function stats()
    {
        echo '<p>';

        echo "<strong>Cold Cache Hits:</strong> {$this->cold_cache_hits}<br>";

        echo "<strong>Warm Cache Hits:</strong> {$this->warm_cache_hits}<br>";

        echo "<strong>Cache Misses:</strong> {$this->cache_misses}<br>";

        echo '</p>';

        foreach ($this->cache as $group => $cache) {
            echo '<p>';

            echo "<strong>Group:</strong> $group<br>";

            echo '<strong>Cache:</strong>';

            echo '<pre>';

            print_r($cache);

            echo '</pre>';

            if (isset($this->dirty_objects[$group])) {
                echo '<strong>Dirty Objects:</strong>';

                echo '<pre>';

                print_r(array_unique($this->dirty_objects[$group]));

                echo '</pre>';

                echo '</p>';
            }
        }
    }

    public function __construct()
    {
        global $blog_id;

        if (defined('DISABLE_CACHE')) {
            return;
        }

        if (!defined('ENABLE_CACHE')) {
            return;
        }

        // Disable the persistent cache if safe_mode is on.

        if (ini_get('safe_mode') && !defined('ENABLE_CACHE')) {
            return;
        }

        if (defined('CACHE_PATH')) {
            $this->cache_dir = CACHE_PATH;
        } else { // Using the correct separator eliminates some cache flush errors on Windows
            $this->cache_dir = ABSPATH . 'wp-content' . DIRECTORY_SEPARATOR . 'cache' . DIRECTORY_SEPARATOR;
        }

        if (is_writable($this->cache_dir) && is_dir($this->cache_dir)) {
            $this->cache_enabled = true;
        } else {
            if (is_writable(ABSPATH . 'wp-content')) {
                $this->cache_enabled = true;
            }
        }

        if (defined('CACHE_EXPIRATION_TIME')) {
            $this->expiration_time = CACHE_EXPIRATION_TIME;
        }

        if (defined('WP_SECRET')) {
            $this->secret = WP_SECRET;
        } else {
            $this->secret = DB_PASSWORD . DB_USER . DB_NAME . DB_HOST . ABSPATH;
        }

        $this->blog_id = $this->hash($blog_id);
    }
}
