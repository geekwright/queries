<?php
//  ------------------------------------------------------------------------ //

include __DIR__ . '/../../mainfile.php';

$GLOBALS['xoopsOption']['template_main'] = 'surnames_print.tpl';
include XOOPS_ROOT_PATH . "/header.php";
// include_once XOOPS_ROOT_PATH . "/language/".$xoopsConfig['language']."/calendar.php";

// get our preferences and apply some sanity checks
$xoopsTpl->assign('title', $xoopsModuleConfig['title']);
$xoopsTpl->assign('page_message', $xoopsModuleConfig['view_message']);

$id='0';
$uid='0';
$name='';
$surname='';
$notes='';
$is_approved=0;
$approve_others=0;
$user_url='';
$user_email='';
$edit_rights=0;
$print_ok=0;

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
}
if (isset($_POST['id'])) {
    $id = intval($_POST['id']);
}

$sql="SELECT uid, surname, notes, approved ";
$sql.="FROM ".$xoopsDB->prefix('surnames');
$sql.=" WHERE id = $id ";
$result = $xoopsDB->query($sql);
if ($result) {
    $myrow=$xoopsDB->fetchArray($result);
    $uid=$myrow['uid'];
    $member_handler = xoops_gethandler('member');
    $thisUser = $member_handler->getUser($uid);
    if (!is_object($thisUser) || !$thisUser->isActive()) {
        redirect_header("index.php", 3, 'Error');
        exit();
    }
    $name = htmlSpecialChars($thisUser->getVar('name'));
    if ($name=='') {
        $name = htmlSpecialChars($thisUser->getVar('uname'));
    }
    $user_url=$thisUser->getVar('url');
    if ($thisUser->getVar('user_viewemail')) {
        $user_email=$thisUser->getVar('email');
    } else {
        $user_email='';
    }
    $user_location=$thisUser->getVar('user_from');
    $user_sig=$thisUser->getVar('user_sig');

    $surname=$myrow['surname'];
    $surname=stripslashes($surname);
    $surname=htmlspecialchars($surname, ENT_QUOTES);
    $notes=$myrow['notes'];
    $notes=stripslashes($notes);
    $notes=htmlspecialchars($notes, ENT_QUOTES);
    $is_approved=$myrow['approved'];

    $print_ok=1;
    $edit_rights=0;
    if (is_object($xoopsUser)) {
        $module_id = $xoopsModule->getVar('mid');
        $groups = $xoopsUser->getGroups();
        $gperm_handler = xoops_gethandler('groupperm');
        $approve_others = $gperm_handler->checkRight("surnames_approve", 1, $groups, $module_id);

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

    $myuid='0';
    if (is_object($xoopsUser)) {
        $myuid=$xoopsUser->getVar('uid');
    }
    $sql="SELECT surname FROM ".$xoopsDB->prefix('surnames');
    $sql.=" WHERE uid = $uid AND surname!='$surname' AND (approved=1 OR uid=$myuid) ";
    $result = $xoopsDB->query($sql);
    $surname_list = array();
    if ($result) {
        while ($myrow=$xoopsDB->fetchArray($result)) {
            $surname_list[]=$myrow['surname'];
        }
    }
}

if (isset($actions)) {
    unset($actions);
}
if ($print_ok) {
    $actions[] = array('action' => 'print.php', 'key' => 'id', 'key_value' => $id, 'button' => 'assets/images/print.gif');
}

if ($edit_rights) {
    if (!$is_approved && $approve_others) {
        $actions[] = array('action' => 'edit.php', 'key' => 'id', 'key_value' => $id, 'button' => 'assets/images/approve.gif');
    }
    $actions[] = array('action' => 'edit.php', 'key' => 'id', 'key_value' => $id, 'button' => 'assets/images/edit.gif');
}

$xoopsTpl->assign('pref_cols', 3);
if (is_array($surname_list) && count($surname_list)) {
    $xoopsTpl->assign('surnames', $surname_list);
}
if (is_array($actions)) {
    $xoopsTpl->assign('actions', $actions);
}


$xoopsTpl->assign('id', $id);
$xoopsTpl->assign('uid', $uid);
$xoopsTpl->assign('name', $name);
$xoopsTpl->assign('surname', $surname);
if ($notes!='') {
    $xoopsTpl->assign('notes', $notes);
}
if ($user_url!='') {
    $xoopsTpl->assign('user_url', $user_url);
}
if ($user_email!='') {
    $xoopsTpl->assign('user_email', $user_email);
}
if ($user_location!='') {
    $xoopsTpl->assign('user_location', $user_location);
}
if ($user_sig!='') {
    $xoopsTpl->assign('user_sig', $user_sig);
}

include XOOPS_ROOT_PATH . '/include/comment_view.php';
include XOOPS_ROOT_PATH . "/footer.php";
