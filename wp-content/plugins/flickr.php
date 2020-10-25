<?php
/*
Plugin Name: .[XPress] Tags and Images
Plugin URI: http://xoopsforge.com/
Description: This plugin provides the XOOPS Tag input API as well as integration with Technorati tags and related Flickr images, to display related images to your post; Cache is enabled. Originated <a href="http://www.broobles.com/scripts/simpletags/">"SimpleTags" by Broobles</a> and <a href="http://ppleyard.org.uk/archives/2005/02/10/flickr-wordpress-plugin-release.html">"Flickr Related Images" by David Appleyard</a>
Version: 1.0
Author: D.J. (phppp)
Author URI: http://xoopsforge.com

 */

/*
Plugin Name: Flickr Images
Plugin URI: http://ppleyard.org.uk/archives/2005/02/10/flickr-wordpress-plugin-release.html
Description: This plugin provides integration with Flickr images, to find related images to your post
Version: 1.0
Author: David Appleyard
Author URI: http://davidappleyard.org.uk

For Installation Instructions, please visit:
http://ppleyard.org.uk/archives/2005/02/10/flickr-wordpress-plugin-release.html

 */

// Options
$GLOBALS['flickr_maxheight'] = @get_option('flickr_maxheight');
// No optioins set
if (empty($GLOBALS['flickr_maxheight'])) {
    $GLOBALS['flickr_cache'] = 60 * 60 * 24; // cache time: 24 hours
    $GLOBALS['flickr_maxrows'] = 2; // Maximum rows to display; 0 for no limit
    $GLOBALS['flickr_maxcols'] = 3; // images per row
    $GLOBALS['flickr_maxheight'] = 100; // in px
    $GLOBALS['flickr_maxwidth'] = 120; // in px
    $GLOBALS['flickr_display'] = true;

    $GLOBALS['wptag_display'] = true;
} else {
    $GLOBALS['flickr_cache'] = get_option('flickr_cache');

    $GLOBALS['flickr_maxrows'] = get_option('flickr_maxrows');

    $GLOBALS['flickr_maxcols'] = get_option('flickr_maxcols');

    $GLOBALS['flickr_maxwidth'] = get_option('flickr_maxwidth');

    $GLOBALS['flickr_display'] = get_option('flickr_display');

    $GLOBALS['wptag_display'] = get_option('wptag_display');

    /*
    $GLOBALS['wptag_url'] = get_option('wptag_url');
    $GLOBALS['wptag_title'] = get_option('wptag_title');
    $GLOBALS['wptag_tagpattern'] = get_option('wptag_tagpattern');
    $GLOBALS['wptag_tagspattern'] = get_option('wptag_tagspattern');
    */
}
$GLOBALS['wptag_url'] = "<a href='http://technorati.com/tag/%s' rel='tag' target='techno'>%s</a>";
//$GLOBALS['wptag_title'] = 'Technorati tags';
$GLOBALS['wptag_title'] = '<a href="http://technorati.com/tag/xoops" rel="tag"  title="Technorati tags" target="techno"><img src="' . XOOPS_URL . '/modules/wordpress/images/techno.gif" alt="Technorati tags"></a>';
$GLOBALS['wptag_tagpattern'] = '/(<tag>(.*?)<\/tag>)/i';
$GLOBALS['wptag_tagspattern'] = '/(<tags>(.*?)<\/tags>)/i';

function flickr_fetchContent($url)
{
    if ($data = flickr_fetchCURL($url)) {
        return $data;
    }

    if ($data = flickr_fetchSnoopy($url)) {
        return $data;
    }

    $data = flickr_fetchFopen($url);

    return $data;
}

function flickr_fetchSnoopy($url)
{
    require_once XOOPS_ROOT_PATH . '/class/snoopy.php';

    $snoopy = new Snoopy();

    $data = '';

    if (@$snoopy->fetch($url)) {
        $data = (is_array($snoopy->results)) ? implode("\n", $snoopy->results) : $snoopy->results;
    }

    return $data;
}

function flickr_fetchCURL($url)
{
    if (!function_exists('curl_init')) {
        return false;
    }

    $ch = curl_init();    // initialize curl handle
    curl_setopt($ch, CURLOPT_URL, $url); // set url to post to
    curl_setopt($ch, CURLOPT_FAILONERROR, 1);

    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1); // allow redirects
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); // return into a variable
    curl_setopt($ch, CURLOPT_TIMEOUT, 30); // times out after 31s
    $data = curl_exec($ch); // run the whole process
    curl_close($ch);

    return $data;
}

function flickr_fetchFopen($url)
{
    if (!$fp = @fopen($url, 'rb')) {
        return false;
    }

    $data = '';

    while (!feof($fp)) {
        $data .= fgets($fp, 1024);
    }

    fclose($fp);

    return $data;
}

function flickr_get_items($thepostid)
{
    require_once ABSPATH . WPINC . '/rss-functions.php';

    $flickr_items = [];

    $tags = $GLOBALS['flickr_tags'];

    if (!is_array($tags) || 0 == count($tags)) {
        return [];
    }

    $rows = 0;

    foreach ($tags as $tag) {
        $feed = 'http://flickr.com/services/feeds/photos_public.gne?tags=' . urlencode(encoding_wp2rss($tag)) . '&format=rss_200';

        $rss = @fetch_rss($feed);

        if (empty($rss)) {
            continue;
        }

        $items = [];

        foreach ($rss->items as $_item) {
            if (preg_match("/\<img[\s]*src=\"(.*)\"[\s]+width=\"([0-9]+)[^0-9]*\"[\s]+height=\"([0-9]+)[^0-9]*\"[\s]+alt=\"(.*)\"/Ui", $_item['description'], $args)) {
                $item['src'] = $args[1];

                $item['width'] = $args[2];

                $item['height'] = $args[3];

                $item['alt'] = $args[4];
            } else {
                continue;
            }

            $item['title'] = $_item['title'];

            $item['link'] = $_item['link'];

            $items[] = $item;

            unset($item);
        }

        unset($rss);

        if (count($items)) {
            $keys = flickr_rand_array(0, count($items) - 1, $GLOBALS['flickr_maxcols']);

            foreach ($keys as $key) {
                $_item = &$items[$key];

                if ($_item['width'] > $GLOBALS['flickr_maxwidth']) {
                    $_item['height'] *= ($GLOBALS['flickr_maxwidth'] / $_item['width']);

                    $_item['width'] = $GLOBALS['flickr_maxwidth'];
                }

                if ($_item['height'] > $GLOBALS['flickr_maxheight']) {
                    $_item['width'] *= ($GLOBALS['flickr_maxheight'] / $_item['height']);

                    $_item['height'] = $GLOBALS['flickr_maxheight'];
                }

                $title = $tag . ': ' . (empty($_item['title']) ? $_item['alt'] : $_item['title']);

                $title = encoding_rss2wp($title);

                $title = htmlspecialchars($title, ENT_QUOTES);

                $flickr_items[] = '<a href="' . $_item['link'] . '" title="' . $title . '" target="techno"><img src="' . $_item['src'] . '" alt="' . $title . '" width="' . (int)$_item['width'] . 'px" height="' . (int)$_item['height'] . 'px"></a>';
            }

            $rows++;
        }

        if ($GLOBALS['flickr_maxrows'] > 0 && $GLOBALS['flickr_maxrows'] <= $rows) {
            break;
        }
    }

    return $flickr_items;
}

// from http://php.net/manual/en/function.rand.php
function flickr_rand_array($min, $max, $num)
{
    $ret = [];

    while (count($ret) < min($num, $max - $min + 1)) {
        do {
            $a = mt_rand($min, $max);
        } while (in_array($a, $ret, true));

        $ret[] = $a;
    }

    return ($ret);
}

function flickr_clear_cache($thepostid = null)
{
    if (!empty($thepostid)) {
        if (file_exists(XOOPS_CACHE_PATH . '/wordpress.flickr.' . $thepostid . '.php')) {
            unlink(XOOPS_CACHE_PATH . '/wordpress.flickr.' . $thepostid . '.php');

            return;
        }
    }

    require_once XOOPS_ROOT_PATH . '/class/xoopslists.php';

    $files = XoopsLists::getFileListAsArray(XOOPS_CACHE_PATH);

    foreach ($files as $file => $name) {
        if (preg_match("/^wordpress\.flickr\.[0-9]+\.php$/i", $name, $matches)) {
            unlink(XOOPS_CACHE_PATH . '/' . $name);
        }
    }

    return true;
}

function flickr_get_cache($thepostid)
{
    $file_flickr = XOOPS_CACHE_PATH . '/wordpress.flickr.' . $thepostid . '.php';

    $flickr_items = [];

    if (@include($file_flickr)) {
        if (time() - $flickr['cache'] < $GLOBALS['flickr_cache']) {
            $flickr_items = unserialize($flickr['items']);

            $flickr_items = array_map('base64_decode', $flickr_items);

            return count($flickr_items) ? $flickr_items : null;
        }
    }

    return $flickr_items;
}

function flickr_set_cache($thepostid, $flickr_items)
{
    $file_flickr = XOOPS_CACHE_PATH . '/wordpress.flickr.' . $thepostid . '.php';

    if (!$fp = fopen($file_flickr, 'wb')) {
        return false;
    }

    $file_content = "<?php\n";

    $file_content .= "\t\$flickr[\"cache\"] = '" . time() . "';\n";

    $file_content .= "\t\$flickr[\"items\"] = '" . serialize(array_map('base64_encode', $flickr_items)) . "';\n";

    $file_content .= "\treturn \$flickr;\n";

    $file_content .= '?>';

    fwrite($fp, $file_content);

    fclose($fp);

    return true;
}

function flickr_update()
{
    if (empty($_GET['update']) || empty($_GET['p'])) {
        return;
    }

    return flickr_clear_cache(empty($_GET['p']));
}

function flickrrelated($thepostid)
{
    $thepostid = (int)$thepostid;

    if (empty($thepostid)) {
        return false;
    }

    if (isset($GLOBALS['flickr_display']) && empty($GLOBALS['flickr_display'])) {
        return true;
    }

    $flickr_items = flickr_get_cache($thepostid);

    if (null === $flickr_items) {
        return false;
    }

    if (0 == count($flickr_items)) {
        $flickr_items = flickr_get_items($thepostid);

        flickr_set_cache($thepostid, $flickr_items);
    }

    if (0 == count($flickr_items)) {
        return false;
    }

    $QUERY_STRING_array = explode('&', xoops_getenv('QUERY_STRING'));

    $QUERY_STRING_new = '';

    foreach ($QUERY_STRING_array as $QUERY) {
        if (!empty($QUERY) && 'update=' != mb_substr($QUERY, 0, 7)) {
            $QUERY_STRING_new .= $QUERY . '&';
        }
    }

    $QUERY_STRING_new = htmlspecialchars($QUERY_STRING_new, ENT_QUOTES | ENT_HTML5);

    $output = '<div class="flickr"><h3><a href="' . xoops_getenv('PHP_SELF') . '?' . $QUERY_STRING_new . '&amp;update=1" title="Click to refresh">Flickr Images</a></h3></div>' . '<table cellpadding="3"><tr>';

    $count = 0;

    foreach ($flickr_items as $item) {
        if ($count > 0 && (0 == $count % $GLOBALS['flickr_maxcols'])) {
            $output .= '</tr><tr>';
        }

        $output .= '<td>' . $item . '</td>';

        $count++;
    }

    $output .= '</tr></table><br>';

    echo $output;
}

add_action('admin_menu', 'show_flickr_options_page');

add_action('plugins_loaded', 'flickr_update');

function show_flickr_options_page()
{
    add_options_page(__('Flickr', 'flickr'), __('Flickr', 'flickr'), 8, __FILE__, 'flickr_options_page');
}

function flickr_options_page()
{
    $options = [
        // plugin options
        'wptag_display',
        'flickr_display',
        // flickr options
        'flickr_cache',
        'flickr_maxrows',
        'flickr_maxcols',
        'flickr_maxheight',
        'flickr_maxwidth',
        // tag options
        //,'wptag_url', 'wptag_title', 'wptag_tagpattern', 'wptag_tagspattern'
    ];

    foreach ($options as $option) {
        ${$option} = htmlspecialchars($GLOBALS[$option], ENT_QUOTES);
    }

    if (isset($_POST['submitted'])) {
        foreach ($options as $option) {
            update_option($option, $_POST[$option]);
        }
    }

    if (isset($_POST['clear_cache'])) {
        flickr_clear_cache();
    }

    $formaction = $_SERVER['PHP_SELF'] . '?page=flickr.php'; ?>
    <div class="wrap">
        <h2><?php _e('Flickr Options', 'flickr'); ?></h2>

        <form name="flickr_options" method="post" action="<?php echo $formaction; ?>">
            <input type="hidden" name="submitted" value="1">

            <fieldset class="options">
                <legend>
                    <label><?php _e('Flickr options', 'flickr'); ?></label>
                </legend>

                <p>
                    <?php
                    _e('The options to control flickr images on your blog pages. Visit <a href="http://xoopsforge.com">XForge</a> for more information', 'flickr'); ?></p>

                <table width="100%" cellspacing="2" cellpadding="5" class="editform">

                    <tr>
                        <th width="33%" valign="top" scope="row"><?php _e('Display tags:', 'flickr'); ?> </th>
                        <td>
                            <input type="checkbox" name="wptag_display" value="1" <?php echo empty($wptag_display) ? '' : 'checked'; ?>>
                        </td>
                    </tr>

                    <tr>
                        <th width="33%" valign="top" scope="row"><?php _e('Display images:', 'flickr'); ?> </th>
                        <td>
                            <input type="checkbox" name="flickr_display" value="1" <?php echo empty($flickr_display) ? '' : 'checked'; ?>>
                        </td>
                    </tr>

                    <tr>
                        <th width="33%" valign="top" scope="row"><?php _e('Cache time:', 'flickr'); ?> </th>
                        <td>
                            <input type="text" name="flickr_cache" value="<?php echo $flickr_cache; ?>"><br>
                            <i><?php _e('Cache time for flickr items (in seconds). Default is 1 day', 'flickr'); ?></i>
                        </td>
                    </tr>

                    <tr>
                        <th width="33%" valign="top" scope="row"><?php _e('Maximum rows:', 'flickr'); ?> </th>
                        <td>
                            <input type="text" name="flickr_maxrows" value="<?php echo $flickr_maxrows; ?>"><br>
                            <i><?php _e('Rows of images to display. One row per tag. 0 for no limit', 'flickr'); ?></i>
                        </td>
                    </tr>

                    <tr>
                        <th width="33%" valign="top" scope="row"><?php _e('Maximum columns:', 'flickr'); ?> </th>
                        <td>
                            <input type="text" name="flickr_maxcols" value="<?php echo $flickr_maxcols; ?>"><br>
                            <i><?php _e('Images to display for each row (tag). 0 for no limit', 'flickr'); ?></i>
                        </td>
                    </tr>

                    <tr>
                        <th width="33%" valign="top" scope="row"><?php _e('Maximum height:', 'flickr'); ?> </th>
                        <td>
                            <input type="text" name="flickr_maxheight" value="<?php echo $flickr_maxheight; ?>"><br>
                            <i><?php _e('Image maximum height', 'flickr'); ?></i>
                        </td>
                    </tr>

                    <tr>
                        <th width="33%" valign="top" scope="row"><?php _e('Maximum width:', 'flickr'); ?> </th>
                        <td>
                            <input type="text" name="flickr_maxwidth" value="<?php echo $flickr_maxwidth; ?>"><br>
                            <i><?php _e('Image maximum width', 'flickr'); ?></i>
                        </td>
                    </tr>

                    <!--
				<tr> 
					<th width="33%" valign="top" scope="row"><?php _e('Tag url:', 'flickr'); ?> </th> 
					<td>
						<input type="text" name="wptag_url" value="<?php echo $wptag_url; ?>" size="60"><br>
						<i><?php _e('Tags full link', 'flickr'); ?></i>
					</td> 
				</tr>

				<tr> 
					<th width="33%" valign="top" scope="row"><?php _e('Tag title:', 'flickr'); ?> </th> 
					<td>
						<input type="text" name="wptag_title" value="<?php echo $wptag_title; ?>" size="60"><br>
						<i><?php _e('Title for tags', 'flickr'); ?></i>
					</td> 
				</tr>

				<tr> 
					<th width="33%" valign="top" scope="row"><?php _e('Tag pattern:', 'flickr'); ?> </th> 
					<td>
						<input type="text" name="wptag_tagpattern" value="<?php echo $wptag_tagpattern; ?>" size="60"><br>
						<i><?php _e('pattern for tag inside content', 'flickr'); ?></i>
					</td> 
				</tr>

				<tr> 
					<th width="33%" valign="top" scope="row"><?php _e('Tags pattern:', 'flickr'); ?> </th> 
					<td>
						<input type="text" name="wptag_tagspattern" value="<?php echo $wptag_tagspattern; ?>" size="60"><br>
						<i><?php _e('pattern for tags at the end of content', 'flickr'); ?></i>
					</td> 
				</tr>
-->

                    <tr>
                        <th width="33%" valign="top" scope="row"><?php _e('Clear cache:', 'flickr'); ?> </th>
                        <td>
                            <input type="checkbox" name="clear_cache" value="1" "checked"><br>
                            <i><?php _e('To delete all cached files', 'flickr'); ?></i>
                        </td>
                    </tr>
                </table>
            </fieldset>


            <p class="submit">
                <input type="submit" name="Submit" value="<?php _e('Update Options &raquo;', 'flickr'); ?>">
            </p>
        </form>
    </div>

<?php
}

/*
Plugin Name: SimpleTags
Plugin URI: http://www.broobles.com/scripts/simpletags/
Description: Allows you to create a list of Technorati tags at the bottom of your post by providing a comma separated list of tags between the &lt;tags&gt; tags. You can use it with any blogging tool/method, not just when posting from WordPress itself (doesn't use custom fields). Supports multiple words within tags. Also allows in-post tagging of words by enclosing them in &lt;tag&gt; tags.
Version: 1.1
Date: July 16th, 2005
Author: Broobles
Author URI: http://www.broobles.com
Contributors: Artur Ortega
*/

function wp_tags($text)
{
    $wptag_url = $GLOBALS['wptag_url'];

    $tag_pattern = $GLOBALS['wptag_tagpattern'];

    $tags_pattern = $GLOBALS['wptag_tagspattern'];

    $tags_count = 0;

    $taglist_exists = 0;    # Set to 1 if the tags list is present

    $tags = [];

    # Check for in-post <tag> </tag>

    if (preg_match_all($tag_pattern, $text, $matches)) {
        for ($m = 0, $mMax = count($matches[0]); $m < $mMax; $m++) {
            $ttags = array_filter(array_map('trim', explode(',', $matches[2][$m])));

            for ($i = 0, $iMax = count($ttags); $i < $iMax; $i++) {
                if (in_array($ttags[$i], $tags, true)) {
                    continue;
                }

                $tags[] = $ttags[$i];
            }

            $text = str_replace($matches[0][$m], $matches[2][$m], $text);
        }
    }

    # Check for <tags> </tags>

    if (preg_match($tags_pattern, $text, $matches)) {
        $taglist_exists = 1;

        $ttags = array_filter(array_map('trim', explode(',', $matches[2])));

        for ($i = 0, $iMax = count($ttags); $i < $iMax; $i++) {
            if (in_array($ttags[$i], $tags, true)) {
                continue;
            }

            $tags[] = $ttags[$i];
        }

        // specified flickr items

        if (preg_match("/^flickr:[\s]*([0-9]{1,2})$/i", $tags[count($tags) - 1], $mt)) {
            $rows = (int)$mt[1];

            if (empty($rows)) {
                $GLOBALS['flickr_display'] = false;
            } else {
                $GLOBALS['flickr_maxrows'] = $rows;
            }

            array_pop($tags);
        }

        // hide techno tags

        if (preg_match('/^notag$/i', $tags[count($tags) - 1])) {
            $GLOBALS['wptag_display'] = false;

            array_pop($tags);
        }
    }

    $tags = array_map('strip_tags', $tags);

    $GLOBALS['flickr_tags'] = $tags;

    if (isset($GLOBALS['wptag_display']) && empty($GLOBALS['wptag_display'])) {
        $text = preg_replace($tags_pattern, '', $text);

        return $text;
    }

    $tags_count = count($tags);

    # If tags were found, include them in the post

    if ($tags_count > 0) {
        if (!empty($GLOBALS['xoopsModuleConfig']['do_tag']) && @require_once XOOPS_ROOT_PATH . '/modules/tag/include/tagbar.php') {
            for ($i = 0; $i < $tags_count; $i++) {
                $tags[$i] = '<a href="' . XOOPS_URL . '/modules/wordpress/view.tag.php?term=' . urlencode(encoding_wp2xoops($tags[$i])) . '">' . $tags[$i] . '</a>';
            }
        } else {
            for ($i = 0; $i < $tags_count; $i++) {
                $tags[$i] = sprintf($wptag_url, urlencode($tags[$i]), $tags[$i]);
            }
        }

        $technotags = '<div class="wp_tags"><span class="tag_title">' . $GLOBALS['wptag_title'] . '</span> <span class="tag_item">' . implode(', ', $tags) . '</span></div>';

        if (1 == $taglist_exists) {
            $text = preg_replace($tags_pattern, $technotags, $text);
        } else {
            $text .= $technotags;
        }
    }

    return $text;
}

add_filter('the_content', 'wp_tags');

// prevent duplicated tag handling
add_action('plugins_loaded', 'wp_skip_wptag_hanlders');

$GLOBALS['wp_wptagHandlers'] = ['simpleTags'];
function wp_skip_wptag_hanlders()
{
    foreach ($GLOBALS['wp_wptagHandlers'] as $handler) {
        if (function_exists($handler)) {
            remove_filter('the_content', $handler);
        }
    }
}

?>
