<?php
//  ------------------------------------------------------------------------ //

include __DIR__ . '/../../mainfile.php';

$op='view';
if (isset($_GET['op'])) {
    $op = intval($_GET['op']);
}
if (isset($_POST['op'])) {
    $op = intval($_POST['op']);
}
if ($op=='print') {
    include_once XOOPS_ROOT_PATH . '/class/template.php';
    $GLOBALS['xoopsOption']['template_main'] = 'queries_print.tpl';
    $xoopsTpl = new XoopsTpl();
} else {
    $GLOBALS['xoopsOption']['template_main'] = 'queries_view.tpl';
    include XOOPS_ROOT_PATH . "/header.php";
}

// get our preferences and apply some sanity checks
$pref_date=$xoopsModuleConfig['pref_date'];
if ($pref_date=='') {
    $pref_date='l';
}

$id='0';
$uid='0';
$title='';
$querytext='';
$posted='';
$is_approved=0;
$approve_others=0;
$user_url='';
$user_name='';
$user_email='';
$edit_rights=0;
$print_ok=0;

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
}
if (isset($_POST['id'])) {
    $id = intval($_POST['id']);
    $_GET['id']=$id;
}  // make comments happy

$myts = MyTextSanitizer::getInstance();

$sql="SELECT uid, name, email, title, querytext, approved, posted ";
$sql.="FROM ".$xoopsDB->prefix('queries_query');
$sql.=" WHERE id = $id ";
$result = $xoopsDB->query($sql);
if ($result) {
    $myrow=$xoopsDB->fetchArray($result);
    if ($myrow) {
        $uid=$myrow['uid'];
        if ($uid!=0) {
            $member_handler = xoops_gethandler('member');
            $thisUser = $member_handler->getUser($uid);
            if (!is_object($thisUser) || !$thisUser->isActive()) {
                $uid=0;
            } else {
                $user_name = $thisUser->getVar('name');
                if ($user_name=='') {
                    $user_name = $thisUser->getVar('uname');
                }
                if ($thisUser->getVar('user_viewemail')) {
                    $user_email = $thisUser->getVar('email');
                } else {
                    $user_email= _MD_QUERIES_VIEW_HIDE_EMAIL;
                }
            }
        }

        if ($uid==0) {
            $user_name = $myrow['name'];
            if ($user_name=='') {
                $user_name = _MD_QUERIES_VIEW_NO_NAME;
            }
            $user_email = $myrow['email'];
            if ($user_email=='') {
                $user_email = _MD_QUERIES_VIEW_NO_EMAIL;
            }
        }

        $title=$myrow['title'];
        $querytext=$myrow['querytext'];

        $posted=formatTimeStamp($myrow['posted'], $pref_date);

        $is_approved=$myrow['approved'];

        $print_ok=1;
        $edit_rights=0;
        if (is_object($xoopsUser)) {
            $module_id = $xoopsModule->getVar('mid');
            $groups = $xoopsUser->getGroups();
            $gperm_handler = xoops_gethandler('groupperm');
            $approve_others = $gperm_handler->checkRight("queries_approve", 1, $groups, $module_id);

            $testuid = $xoopsUser->getVar('uid');

            if ($testuid==$uid) {
                $edit_rights=1;
            }
            if ($approve_others) {
                $edit_rights=1;
            }
            if ($xoopsUser->isAdmin()) {
                $edit_rights=1;
            }
        }
    } else {
        redirect_header(XOOPS_URL.'/modules/queries/index.php', 3, _MD_QUERIES_DB_NOTFOUND);
    }
}


if (isset($actions)) {
    unset($actions);
}
if ($print_ok) {
    $actions[] = array('action' => "view.php?id=$id&op=print", 'button' => 'assets/images/print.gif', 'alt' => 'Print', 'extra' => ' target="_blank" ');
}

if ($edit_rights) {
    if (!$is_approved && $approve_others) {
        $actions[] = array('action' => "edit.php?id=$id", 'button' => 'assets/images/approve.gif', 'alt' => 'Approve', 'extra' => '');
    }
    $actions[] = array('action' => "edit.php?id=$id", 'button' => 'assets/images/edit.gif', 'alt' => 'Edit', 'extra' => '');
}

if (is_array($actions)) {
    $xoopsTpl->assign('actions', $actions);
}

$xoopsTpl->assign('queries_op', $op);
$xoopsTpl->assign('queries_id', $id);
if ($uid!=0) {
    $xoopsTpl->assign('queries_uid', $uid);
}
$xoopsTpl->assign('queries_user_name', $myts->htmlSpecialChars($user_name));
$xoopsTpl->assign('queries_user_email', $user_email);
$xoopsTpl->assign('queries_posted', $posted);
$xoopsTpl->assign('queries_title', $myts->htmlSpecialChars($title));
if ($op=='print') {
    $querytext.="<br />"._MD_QUERIES_PRINT_FOOTER." ".$xoopsConfig['sitename']."<br /> ".$_SERVER['HTTP_REFERER'];
}
$xoopsTpl->assign('queries_querytext', $myts->displayTarea($querytext, 1));
$xoopsTpl->assign('xoops_pagetitle', $myts->htmlSpecialChars($xoopsModule->name()) . ' - ' . $myts->htmlSpecialChars($title));

$com_replytitle="Re: $title";

include XOOPS_ROOT_PATH . '/include/comment_view.php';
if ($op=='print') {
    $xoopsTpl->display('db:queries_print.tpl');
} else {
    include XOOPS_ROOT_PATH . '/footer.php';
}
