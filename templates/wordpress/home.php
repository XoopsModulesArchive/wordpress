<?php

$GLOBALS['xoopsOption']['template_main'] = 'wp_home.html';
include __DIR__ . '/header.php';

$GLOBALS['wp_xoops_content']['posts'] = [];

// Get the author list
$data_home = [];

$start = (int)@$_GET['start'];
$limit = $GLOBALS['xoopsModuleConfig']['num_authors_index'];

// Get a list of authors order by last post
$sql = '	SELECT MAX(post_date) AS last, COUNT(ID) AS count, post_author AS uid' . "	FROM $wpdb->posts" . "	WHERE post_status = 'publish'" . '	GROUP BY post_author ORDER BY last DESC' . "	LIMIT {$start}, {$limit}";

$result = $xoopsDB->query($sql);
if ($result = $xoopsDB->query($sql)) {
    $authors = [];

    while (false !== ($myrow = $xoopsDB->fetchArray($result))) {
        $authors[$myrow['uid']] = $myrow;
    }

    $crit_user = new Criteria('uid', '(' . implode(', ', array_keys($authors)) . ')', 'IN');

    $users_obj = $memberHandler->getUsers($crit_user, true);

    $data['list'] = [];

    $time_difference = '' . (float)get_settings('time_difference') . '';

    load_functions('locale');

    foreach ($authors as $uid => $author) {
        if (!isset($users_obj[$uid])) {
            continue;
        }

        $username = $users_obj[$uid]->getVar('name');

        $username = empty($username) ? $users_obj[$uid]->getVar('uname') : $username;

        $image = $users_obj[$uid]->getVar('user_avatar');

        if (!empty($image) && 'blank.gif' != $image) {
            $image = XOOPS_URL . '/uploads/' . $image;
        } else {
            $image = '';
        }

        $m = $author['last'];

        $post_time = mktime(mb_substr($m, 11, 2), mb_substr($m, 14, 2), mb_substr($m, 17, 2), mb_substr($m, 5, 2), mb_substr($m, 8, 2), mb_substr($m, 0, 4));

        $last = XoopsLocal::formatTimestamp($post_time, 'e', $time_difference);

        $data_home['list'][] = [
            'uid' => $uid,
            'name' => $username,
            'link' => get_author_link(0, $uid, $uid),
            'desc' => strip_tags($users_obj[$uid]->getVar('bio')),
            'image' => $image,
            'last' => $last,
            'count' => (int)$author['count'],
        ];
    }

    if (count($data_home['list']) >= $limit || $start) {
        $sql = '	SELECT COUNT(DISTINCT post_author)' . "	FROM $wpdb->posts" . "	WHERE post_status = 'publish'";

        $result = $xoopsDB->query($sql);

        [$count] = $xoopsDB->fetchRow($result);

        require_once XOOPS_ROOT_PATH . '/class/pagenav.php';

        $nav = new XoopsPageNav($count, $limit, $start, 'start');

        $data_home['pagenav'] = $nav->renderNav(4);
    }
}

$xoopsTpl->assign_by_ref('authors', $data_home);
include __DIR__ . '/footer.php';
