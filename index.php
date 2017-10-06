<?php
//  ------------------------------------------------------------------------ //

include __DIR__ . '/../../mainfile.php';
$GLOBALS['xoopsOption']['template_main'] = 'queries_index.tpl';
include XOOPS_ROOT_PATH . "/header.php";

// get our parameters
$start=0;
if (isset($_GET['start'])) {
    $start = intval($_GET['start']);
}

// get our preferences and apply some sanity checks
$pref_date=$xoopsModuleConfig['pref_date'];
if ($pref_date=='') {
    $pref_date='l';
}

$pref_rows=$xoopsModuleConfig['pref_rows'];
if ($pref_rows<1) {
    $pref_rows=10;
}
if ($pref_rows>200) {
    $pref_rows=200;
}
$xoopsTpl->assign('pref_rows', $pref_rows);

$limit=$pref_rows;

// get the data
$myuserid='0';
if (is_object($xoopsUser)) {
    $myuserid = $xoopsUser->getVar('uid');
}
// first a count
$sql="SELECT COUNT(*) FROM ".$xoopsDB->prefix('queries_query');
$sql.=" WHERE approved=1 OR (uid=$myuserid AND uid!=0) ";

$total=0;
$result = $xoopsDB->query($sql);
if ($result) {
    $myrow=$xoopsDB->fetchRow($result);
    $total=$myrow[0];
}

if (isset($query_id)) {
    unset($query_id);
}

$sql="SELECT id, title, posted, querytext FROM ".$xoopsDB->prefix('queries_query');
$sql.=" WHERE approved=1 OR (uid=$myuserid AND uid!=0) ORDER BY posted DESC";

$result = $xoopsDB->query($sql, $limit, $start);
if ($result) {
    while ($myrow=$xoopsDB->fetchArray($result)) {
        $query_ids[]=$myrow['id'];
        $query_list[]=$myrow['title'];
        $query_date[]=formatTimeStamp($myrow['posted'], $pref_date);
        $query_text[]=htmlspecialchars($myrow['querytext'], ENT_QUOTES);
    }
}

$xoopsTpl->assign('q_ids', $query_ids);
$xoopsTpl->assign('q_titles', $query_list);
$xoopsTpl->assign('q_dates', $query_date);
$xoopsTpl->assign('q_text', $query_text);

// set up pagenav
if ($total > $limit) {
    include_once XOOPS_ROOT_PATH . '/class/pagenav.php';
    $nav = new xoopsPageNav($total, $limit, $start, 'start', '');
    $xoopsTpl->assign('pagenav', $nav->renderNav());
}

include XOOPS_ROOT_PATH . '/footer.php';
