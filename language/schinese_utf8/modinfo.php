<?php

if (!defined('WP_LANG')) {
    define('WP_LANG', 'zh_CN');

    define('WP_MO_CHARSET', 'utf-8');
}
// The name of this module
define('_MI_WORDPRESS_NAME', 'WordPress');

// A brief description of this module
define('_MI_WORDPRESS_DESC', 'XOOPS社区博客');

define('_MI_WORDPRESS_ADMENU_INDEX', '首页');
define('_MI_WORDPRESS_ADMENU_PERMISSION', '权限');
define('_MI_WORDPRESS_ADMENU_BLOCK', '区块');
define('_MI_WORDPRESS_ADMENU_OPTION', '选项');

// Sub menu titles
define('_MI_WORDPRESS_MENU_SUBMIT', '发表文章');

// Configs
define('_MI_WORDPRESS_ENABLE_MU', '启用多用户模式');
define('_MI_WORDPRESS_ENABLE_MU_DESC', '支持每个用户有自己独立的显示页面');

define('_MI_WORDPRESS_NUM_POSTS', '导航栏中显示的文章数');
define('_MI_WORDPRESS_NUM_POSTS_DESC', '');

define('_MI_WORDPRESS_NUM_COMMENTS', '导航栏中显示的评论数');
define('_MI_WORDPRESS_NUM_COMMENTS_DESC', '');

define('_MI_WORDPRESS_NUM_AUTHORS', '导航栏中显示的作者数');
define('_MI_WORDPRESS_NUM_AUTHORS_DESC', '');

define('_MI_WORDPRESS_LENGTH_COMMENT', '导航栏中显示的评论长度');
define('_MI_WORDPRESS_LENGTH_COMMENT_DESC', '');

define('_MI_WORDPRESS_STYLE', '界面样式');
define('_MI_WORDPRESS_STYLE_DESC', 'Xoops, Wordpress, 或用户可选择模式。如果启用了多用户模式，请选择Xoops样式');
/*
define("_MI_WORDPRESS_THEMESET", "Module theme set");
define("_MI_WORDPRESS_THEMESET_DESC", "Module-wide, select '"._NONE."' will use site-wide theme");
*/
define('_MI_WORDPRESS_RSSCHARSET', 'RSS 编码');
define('_MI_WORDPRESS_RSSCHARSET_DESC', '生成 RSS/ATOM feeds 文件的编码');

define('_MI_WORDPRESS_SHOWEXCERPT', '显示摘要');
define('_MI_WORDPRESS_SHOWEXCERPT_DESC', '在列表页面显示摘要而不是显示全文');

// Block Name
define('_MI_WORDPRESS_BLOCK_CALENDAR', '日历');
define('_MI_WORDPRESS_BLOCK_SIDEBAR', '导航');
define('_MI_WORDPRESS_BLOCK_AUTHORS', '作者列表');
define('_MI_WORDPRESS_BLOCK_POSTS', '最新文章');
define('_MI_WORDPRESS_BLOCK_CONTENT', '最新内容');
define('_MI_WORDPRESS_BLOCK_COMMENTS', '最新评论');
