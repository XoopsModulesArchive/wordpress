<?php

require_once dirname(__DIR__, 3) . '/wp-config.php';

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <title><?php _e('Rich Editor Help') ?></title>
    <link rel="stylesheet" href="<?php echo get_settings('siteurl') ?>/wp-admin/wp-admin.css?version=<?php bloginfo('version'); ?>" type="text/css">
    <style type="text/css">
        #wphead {
            padding-top: 5px;
            padding-bottom: 5px;
            padding-left: 15px;
            font-size: 90%;
        }

        #adminmenu {
            padding-top: 2px;
            padding-bottom: 2px;
            padding-left: 15px;
            font-size: 94%;
        }

        #user_info {
            margin-top: 15px;
        }

        h2 {
            font-size: 2em;
            border-bottom-width: .5em;
            margin-top: 12px;
            margin-bottom: 2px;
        }

        h3 {
            font-size: 1.1em;
            margin-top: 20px;
            margin-bottom: 0px;
        }

        #flipper {
            margin: 5px 10px 3px;
        }

        #flipper div p {
            margin-top: 0.4em;
            margin-bottom: 0.8em;
            text-align: justify;
        }

        th {
            text-align: center;
        }

        .top th {
            text-decoration: underline;
        }

        .top .key {
            text-align: center;
            width: 36px;
        }

        .top .action {
            text-align: left;
        }

        .align {
            border-left: 3px double #333;
            border-right: 3px double #333;
        }

        #keys p {
            display: inline-block;
            margin: 0px;
            padding: 0px;
        }

        #keys .left {
            text-align: left;
        }

        #keys .center {
            text-align: center;
        }

        #keys .right {
            text-align: right;
        }

        td b {
            font-family: "Times New Roman" Times serif;
        }

        #buttoncontainer {
            text-align: center;
        }

        #buttoncontainer a, #buttoncontainer a:hover {
            border-bottom: 0px;
        }
    </style>
    <script type="text/javascript">
        window.onkeydown = window.onkeypress = function (e) {
            e = e ? e : window.event;
            if (e.keyCode == 27 && !e.shiftKey && !e.controlKey && !e.altKey) {
                window.close();
            }
        }

        function d(id) {
            return document.getElementById(id);
        }

        function flipTab(n) {
            for (i = 1; i <= 4; i++) {
                c = d('content' + i.toString());
                t = d('tab' + i.toString());
                if (n == i) {
                    c.className = '';
                    t.className = 'current';
                } else {
                    c.className = 'hidden';
                    t.className = '';
                }
            }
        }
    </script>
</head>
<body>
<div class="zerosize"></div>
<div id="wphead"><h1><?php echo get_bloginfo('blogtitle'); ?></h1></div>
<div id="user_info"><p><strong><?php _e('Rich Editor Help') ?></strong></p></div>
<ul id="adminmenu">
    <li><a id="tab1" href="javascript:flipTab(1)" title="<?php _e('Basics of Rich Editing') ?>" accesskey="1" class="current"><?php _e('Basics') ?></a></li>
    <li><a id="tab2" href="javascript:flipTab(2)" title="<?php _e('Advanced use of the Rich Editor') ?>" accesskey="2"><?php _e('Advanced') ?></a></li>
    <li><a id="tab3" href="javascript:flipTab(3)" title="<?php _e('Hotkeys') ?>" accesskey="3"><?php _e('Hotkeys') ?></a></li>
    <li><a id="tab4" href="javascript:flipTab(4)" title="<?php _e('About the software') ?>" accesskey="4"><?php _e('About') ?></a></li>
</ul>

<div id="flipper" class="wrap">

    <div id="content1">
        <h2><?php _e('Rich Editing Basics') ?></h2>
        <p><?php _e(
    '<em>Rich editing</em>, also called WYSIWYG for What You See Is What You Get, means your text is formatted as you type. The rich editor creates HTML code behind the scenes while you concentrate on writing. Font styles, links and images all appear just as they will on the internet.'
) ?></p>
        <p><?php _e(
                'WordPress includes TinyMCE, a rich editor that works well in most web browsers used today. It is powerful but it has limitations. Pasting text from other word processors may not give the results you expect. If you do not like the way the rich editor works, you may turn it off in the My Profile form, under Users in the admin menu.'
            ) ?></p>
        <p><?php _e('Because HTML code depends on the less-than character (&lt;) to render web pages, this character is reserved for HTML code. If you want a "<" to be visible on your site, you must encode it as "&amp;lt;" without the quotes.') ?></p>
    </div>

    <div id="content2" class="hidden">
        <h2><?php _e('Advanced Rich Editing') ?></h2>
        <h3><?php _e('Images and Attachments') ?></h3>
        <p><?php _e(
                'Some (not all) browsers allow you to drag images and other items directly into the editor. Most <a href="http://www.mozilla.org/products/firefox/" title="Mozilla.org, home of the Firefox web browser" target="_blank">Firefox</a> users can drag images from the uploading box (directly below the editor) and see their images instantly, complete with a link. If you cannot do this, use your clipboard Copy and Paste functions to insert the image and link tags. The rich editor will display the images after you have saved the post or used the HTML Editor to refresh the display.'
            ) ?></p>
        <h3><?php _e('HTML in the Rich Editor') ?></h3>
        <p><?php _e(
                'When you want to include HTML elements that are not generated by the toolbar buttons, you must enter it by hand. Examples are &lt;pre> and &lt;code>. Simply type the code into the editor. If the code is valid and allowed by the editor, you should see it rendered the next time you update the display, usually by saving or using the HTML Editor. If you want to display "&lt;" on the web, you must encode it as "&amp;lt;" in the editor.'
            ) ?></p>
        <h3><?php _e('The HTML Editor') ?></h3>
        <p><?php _e(
                'The editor will not always understand your intentions as your editing gets more complex. Use the HTML Editor to sort out any rough spots, such as extra elements or attributes. WordPress will strip all empty &lt;p> tags and &lt;br> tags in favor of simple newline characters. However, it will preserve any tag such as this: &lt;p class="anyclass">&lt;/p>. When using the HTML editor, all less-thans are double-encoded: &amp;amp;lt;. This ensures they display as &amp;lt; in the rich editor and &lt; on the web.'
            ) ?></p>
    </div>

    <div id="content3" class="hidden">
        <h2><?php _e('Writing at Full Speed') ?></h2>
        <p><?php _e('Rather than reaching for your mouse to click on the toolbar, use these access keys. Windows and Linux use Alt+&lt;letter>. Macintosh uses Ctrl+&lt;letter>.') ?></p>
        <table id="keys" width="100%" border="0">
            <tr class="top">
                <th class="key center"><?php _e('Key') ?></th>
                <th class="left"><?php _e('Action') ?></th>
                <th class="key center"><?php _e('Key') ?></th>
                <th class="left"><?php _e('Action') ?></th>
            </tr>
            <tr>
                <th>b</th>
                <td><strong><?php _e('Bold') ?></strong></td>
                <th>f</th>
                <td class="align left"><?php _e('Align Left') ?></td>
            </tr>
            <tr>
                <th>i</th>
                <td><em><?php _e('Italic') ?></em></td>
                <th>c</th>
                <td class="align center"><?php _e('Align Center') ?></td>
            </tr>
            <tr>
                <th>d</th>
                <td><strike><?php _e('Strikethrough') ?></strike></td>
                <th>r</th>
                <td class="align right"><?php _e('Align Right') ?></td>
            </tr>
            <tr>
                <th>l</th>
                <td><b>&bull;</b> <?php _e('List') ?></td>
                <th>a</th>
                <td><?php _e('Insert <span class="anchor">Anchor</span>') ?></td>
            </tr>
            <tr>
                <th>o</th>
                <td>1. <?php _e('List') ?></td>
                <th>s</th>
                <td><?php _e('Unlink Anchor') ?></td>
            </tr>
            <tr>
                <th>q</th>
                <td>&rarr;<?php _e('Quote/Indent') ?></td>
                <th>m</th>
                <td><?php _e('Insert Image') ?></td>
            </tr>
            <tr>
                <th>w</th>
                <td>&larr;<?php _e('Unquote/Outdent') ?></td>
                <th>t</th>
                <td><?php _e('Insert "More" Tag') ?></td>
            </tr>
            <tr>
                <th>u</th>
                <td><?php _e('Undo') ?></td>
                <th>e</th>
                <td><?php _e('Edit HTML') ?></td>
            </tr>
            <tr>
                <th>y</th>
                <td><?php _e('Redo') ?></td>
                <th>h</th>
                <td><?php _e('Open Help') ?></td>
            </tr>
        </table>
    </div>

    <div id="content4" class="hidden">
        <h2><?php _e('About TinyMCE'); ?></h2>
        <p><?php printf(__('Version: %s'), '2.0RC4') ?></p>
        <p><?php printf(
                __('TinyMCE is a platform independent web based Javascript HTML WYSIWYG editor control released as Open Source under %sLGPL</a>	by Moxiecode Systems AB. It has the ability to convert HTML TEXTAREA fields or other HTML elements to editor instances.'),
                '<a href="' . get_bloginfo('home') . '/wp-includes/js/tinymce/license.txt" target="_blank" title="' . __('GNU Library General Public Licence') . '">'
            ) ?></p>
        <p><?php _e('Copyright &copy; 2005, <a href="http://www.moxiecode.com" target="_blank">Moxiecode Systems AB</a>, All rights reserved.') ?></p>
        <p><?php _e('For more information about this software visit the <a href="http://tinymce.moxiecode.com" target="_blank">TinyMCE website</a>.') ?></p>

        <div id="buttoncontainer">
            <a href="http://www.moxiecode.com" target="_new"><img src="http://tinymce.moxiecode.com/images/gotmoxie.png" alt="<?php _e('Got Moxie?') ?>" border="0"></a>
            <a href="http://sourceforge.net/projects/tinymce/" target="_blank"><img src="http://sourceforge.net/sflogo.php?group_id=103281" alt="<?php _e('Hosted By Sourceforge') ?>" border="0"></a>
            <a href="http://www.freshmeat.net/projects/tinymce" target="_blank"><img src="http://tinymce.moxiecode.com/images/fm.gif" alt="<?php _e('Also on freshmeat') ?>" border="0"></a>
        </div>

    </div>

</div>

</body>
</html>

