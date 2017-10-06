<?php
//  ------------------------------------------------------------------------ //

include __DIR__ . '/../../mainfile.php';

$GLOBALS['xoopsOption']['template_main'] = 'queries_review.tpl';
include XOOPS_ROOT_PATH . "/header.php";
include_once XOOPS_ROOT_PATH . "/class/xoopsformloader.php";

// get our parameters
$start=0;
if (isset($_GET['start'])) {
    $start = intval($_GET['start']);
}
if (isset($opset)) {
    unset($opset);
}
if (isset($_POST['opset'])) {
    $opset = $_POST['opset'];
}


// get our preferences and apply some sanity checks
$pref_rows=$xoopsModuleConfig['pref_rows'];
if ($pref_rows<1) {
    $pref_rows=10;
}
if ($pref_rows>50) {
    $pref_rows=50;
}
$xoopsTpl->assign('pref_rows', $pref_rows);
$limit=$pref_rows;
$total=0;

function myGetUnameFromId($uid)
{
    static $thisUser=false;

    if (!is_object($thisUser)) {
        $member_handler = xoops_gethandler('member');
        $thisUser = $member_handler->getUser($uid);
    }
    $name = $thisUser->getVar('name');
    if ($name=='') {
        $name = $thisUser->getVar('uname');
    }
    $email = $thisUser->getVar('email');
    return array($name,$email);
}

function notificationTriggerById($opid)
{
    global $xoopsModuleConfig, $xoopsDB, $xoopsUser;

    $sql="SELECT title, querytext, approved FROM ".$xoopsDB->prefix('queries_query'). " WHERE id=$opid ";
    $result = $xoopsDB->query($sql);
    if ($row=$xoopsDB->fetchArray($result)) {
        $title=$row['title'];
        $querytext=$row['querytext'];
        $approved=$row['approved'];
    }

    if ($approved && !empty($xoopsModuleConfig['notification_enabled'])) {
        if (strlen($querytext)>200) {
            $querytext=substr($querytext, 0, 200).'...';
        }
        $tags = array();
        $tags['TITLE'] = $title;
        $tags['QUERYTEXT'] = $querytext;
        $tags['QUERY_URL'] = XOOPS_URL . '/modules/queries/view.php?id='.$opid;
        $tags['RECENT_URL'] = XOOPS_URL . '/modules/queries/index.php';

        $notification_handler = xoops_gethandler('notification');
        $notification_handler->triggerEvent('global', 0, 'new_query', $tags);
    }
}

if (isset($err_message)) {
    unset($err_message);
}
if (isset($message)) {
    unset($message);
}
if (isset($body)) {
    unset($body);
}

// get our parameters
global $xoopsUser, $xoopsDB;

$approval_authority=0;
$op='';
if (is_object($xoopsUser)) {
    $module_id = $xoopsModule->getVar('mid');
    $groups = $xoopsUser->getGroups();
    $gperm_handler = xoops_gethandler('groupperm');
    $approval_authority = $gperm_handler->checkRight("queries_approve", 2, $groups, $module_id);
    if (!$approval_authority) {
        $approval_authority=$xoopsUser->isAdmin();
    }
    $op='display';
}
if (!$approval_authority) {
    redirect_header("index.php", 2, _MD_QUERIES_REVIEW_ERR_1);
    exit();
}

if ($op!='') {
    if (isset($opset) && is_array($opset)) {
        $op='update';
        $check=$GLOBALS['xoopsSecurity']->check();
        if (!$check) {
            $op='display';
            $err_message = _MD_QUERIES_REVIEW_ERR_2;
        }
    }
}

if ($op=='update') {
    $delcnt=0;
    $updcnt=0;
    foreach ($opset as $opid => $act) {
        switch ($act) {
            case 'approve':
                $sql = "UPDATE " . $xoopsDB->prefix('queries_query') . " SET APPROVED=1 WHERE id=$opid ";
                $result = $xoopsDB->queryF($sql);
                if ($result) {
                    ++$updcnt;
                    notificationTriggerById($opid);
                } else {
                    $err_message = _MD_QUERIES_REVIEW_ERR_4 . ' ' . $xoopsDB->errno() . ' ' . $xoopsDB->error();
                }
                break;
            case 'delete':
                $sql = "DELETE FROM " . $xoopsDB->prefix('queries_query') . " WHERE id=$opid ";
                $result = $xoopsDB->queryF($sql);
                if ($result) {
                    ++$delcnt;
                    xoops_comment_delete($xoopsModule->getVar('mid'), $opid);
                } else {
                    $err_message = _MD_QUERIES_REVIEW_ERR_4 . ' ' . $xoopsDB->errno() . ' ' . $xoopsDB->error();
                }
                break;
            default:
                $err_message = "bad action '$act' for id '$opid'.";
                break;
        }
        if (isset($err_message)) {
            break;
        }
    }
    if ($delcnt || $updcnt) {
        $message = sprintf(_MD_QUERIES_REVIEW_UPDMSG, intval($updcnt+$delcnt));
    }

    $op='display';
}

// first a count
if ($op=='display') {
    $sql="SELECT COUNT(*) FROM ".$xoopsDB->prefix('queries_query');
    $sql.=" WHERE approved=0 ";

    $total=0;
    $result = $xoopsDB->query($sql);
    if ($result) {
        $myrow=$xoopsDB->fetchRow($result);
        $total=$myrow[0];
    }

    $id_list = array();
    $uid_list = array();
    $title_list = array();
    $querytext_list = array();
    $name_list = array();
    $email_list = array();

    $sql="SELECT id, uid, name, email, title, querytext, posted FROM ".$xoopsDB->prefix('queries_query');
    $sql.=" WHERE approved=0 ORDER BY posted";

    $result = $xoopsDB->query($sql, $limit, $start);
    if ($result) {
        while ($myrow=$xoopsDB->fetchArray($result)) {
            $id_list[]=$myrow['id'];
            $temp_uid=$myrow['uid'];
            $uid_list[]=$temp_uid;
            if ($temp_uid!=0) {
                unset($temp_array);
                $temp_array=myGetUnameFromId($temp_uid);
                $name_list[]=$temp_array[0];
                $email_list[]=$temp_array[1];
            } else {
                $name_list[]=$myrow['name'];
                $email_list[]=$myrow['email'];
            }
            $title_list[]=$myrow['title'];
            $querytext_list[]=$myrow['querytext'];
        }
    }

    $xoopsTpl->assign('ids', $id_list);
    $xoopsTpl->assign('uids', $uid_list);
    $xoopsTpl->assign('names', $name_list);
    $xoopsTpl->assign('emails', $email_list);
    $xoopsTpl->assign('titles', $title_list);
    $xoopsTpl->assign('querytext', $querytext_list);
    $xoopsTpl->assign('formtoken', $GLOBALS['xoopsSecurity']->getTokenHTML());
}

// set up pagenav
if ($total > $limit) {
    include_once XOOPS_ROOT_PATH . '/class/pagenav.php';
    $nav = new xoopsPageNav($total, $limit, $start, 'start', '');
    $xoopsTpl->assign('pagenav', $nav->renderNav());
}

if (isset($body)) {
    $xoopsTpl->assign('body', $body);
}

if (isset($message)) {
    $xoopsTpl->assign('message', $message);
}
if (isset($err_message)) {
    $xoopsTpl->assign('err_message', $err_message);
}

include XOOPS_ROOT_PATH . '/footer.php';
