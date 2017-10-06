<?php
//  ------------------------------------------------------------------------ //

include __DIR__ . '/../../mainfile.php';
$GLOBALS['xoopsOption']['template_main'] = 'queries_edit.tpl';
include XOOPS_ROOT_PATH . '/header.php';
include_once XOOPS_ROOT_PATH . '/class/xoopsformloader.php';

function cleaner($string)
{
    $string=stripcslashes($string);
    $string=html_entity_decode($string);
    $string=strip_tags($string);
    $string=trim($string);
    $string=stripslashes($string);
    return $string;
}

function zeroint($val)
{
    if ($val) {
        $ret = $val;
    } else {
        $ret = '0';
    }
    return $val;
}

function notificationTrigger($id, $title, $querytext, $approved)
{
    if (empty($GLOBALS['xoopsModuleConfig']['notification_enabled'])) {
        return;
    }

    $event = $approved ? 'new_query' : 'approval_needed';

    $qtext = cleaner($querytext);
    if (strlen($qtext) > 200) {
        $qtext = substr($qtext, 0, 200) . '...';
    }

    $tags = array();
    $tags['TITLE'] = cleaner($title);
    $tags['QUERYTEXT'] = $qtext;
    $tags['QUERY_URL'] = XOOPS_URL . '/modules/queries/view.php?id=' . $id;
    $tags['RECENT_URL'] = XOOPS_URL . '/modules/queries/index.php';

    $notification_handler = xoops_gethandler('notification');
    $notification_handler->triggerEvent('global', 0, $event, $tags);

}

// get our preferences and apply some sanity checks
global $xoopsModuleConfig, $xoopsDB, $xoopsUser;

$postanon=$xoopsModuleConfig['postanon'];
$use_captcha=$xoopsModuleConfig['captcha'];

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
$myuserid=0;
if ($xoopsUser) {
    $myuserid = $xoopsUser->getVar('uid');
}

$op='display';
$t_op='';
if (isset($_POST['submit'])) {
    $t_op = $_POST['submit'];
    if ($t_op == _MD_QUERIES_EDIT_BUTTON) {
        $op='update';
    }
}

$query_id=0;
if (isset($_POST['id'])) {
    $query_id = intval($_POST['id']);
}

$query_uid=0;
if (isset($_POST['uid'])) {
    $query_uid = intval($_POST['uid']);
}

$query_name='';
if (isset($_POST['name'])) {
    $query_name = $_POST['name'];
}
$query_email='';
if (isset($_POST['email'])) {
    $query_email = $_POST['email'];
}

$query_title='';
if (isset($_POST['title'])) {
    $query_title = $_POST['title'];
}
$query_querytext='';
if (isset($_POST['querytext'])) {
    $query_querytext = $_POST['querytext'];
}

$query_conversion_data_1='';
if (isset($_POST['conversion_data_1'])) {
    $query_conversion_data_1 = $_POST['conversion_data_1'];
}
$query_conversion_data_2='';
if (isset($_POST['conversion_data_2'])) {
    $query_conversion_data_2 = $_POST['conversion_data_2'];
}
$query_approved=0;
if (isset($_POST['approved'])) {
    $query_approved = $_POST['approved'];
}
$query_posted='';
if (isset($_POST['posted'])) {
    $query_posted = $_POST['posted'];
}

$query_comment_count=0;
if (isset($_POST['comment_count'])) {
    $query_comment_count = $_POST['comment_count'];
}

// passed in id, set all values from database, overrides post data
$id=0;
if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
}

if ($id!=0) {
    $sql="SELECT id, uid, name, email, title, querytext, comment_count, approved, FROM_UNIXTIME(posted) as postdate, conversion_data_1, conversion_data_2";
    $sql.=" FROM ".$xoopsDB->prefix('queries_query');
    $sql.=" WHERE id = $id ";
    $result = $xoopsDB->query($sql);
    if ($result) {
        $myrow=$xoopsDB->fetchArray($result);
        $query_id = $myrow['id'];
        $query_uid = $myrow['uid'];
        $query_name=$myrow['name'];
        $query_email=$myrow['email'];
        $query_title=$myrow['title'];
        $query_querytext=$myrow['querytext'];
        $query_conversion_data_1=$myrow['conversion_data_1'];
        $query_conversion_data_2=$myrow['conversion_data_2'];
        $query_approved=$myrow['approved'];
        $query_posted=$myrow['postdate'];
        $query_comment_count=$myrow['comment_count'];
    } else {
        $query_id=0;
    }
}

// clean up data
    $query_id=intval($query_id);
    $query_uid=intval($query_uid);
    $query_name=cleaner($query_name);
    $query_email=cleaner($query_email);
    $query_title=cleaner($query_title);
    $query_querytext=cleaner($query_querytext);
    $query_conversion_data_1=cleaner($query_conversion_data_1);
    $query_conversion_data_2=cleaner($query_conversion_data_2);
    $query_approved=intval($query_approved);
    if ($query_approved) {
        $query_approved=1;
    }
    $query_posted=cleaner($query_posted);
    $query_comment_count=intval($query_comment_count);

// permissions

// leave if anonymous posting is not allowed
if ($myuserid==0 && !$postanon) {
    redirect_header(XOOPS_URL.'/user.php', 3, _MD_QUERIES_NO_ANON);
}

// leave if trying anonymous edit
if ($myuserid==0 && $query_id!=0) {
    redirect_header(XOOPS_URL.'/modules/queries/index.php', 3, _NOPERM);
}


$module_id = $xoopsModule->getVar('mid');
if (is_object($xoopsUser)) {
    $groups = $xoopsUser->getGroups();
} else {
    $groups = XOOPS_GROUP_ANONYMOUS;
}

$gperm_handler = xoops_gethandler('groupperm');

$approve_own = $gperm_handler->checkRight("queries_approve", 0, $groups, $module_id);
$approve_others = $gperm_handler->checkRight("queries_approve", 1, $groups, $module_id);

// leave if no permission to edit others and id differs
if ($approve_others==0 && $myuserid!=$query_uid && $query_id!=0) {
    redirect_header(XOOPS_URL.'/modules/queries/index.php', 3, _NOPERM);
}

$user_mode='anon';
if ($myuserid!=0) {
    $user_mode='reg';
    if ($approve_others) {
        $user_mode='editor';
    }
    if ($xoopsUser->isAdmin()) {
        $user_mode='admin';
    }
}

if ($op=='update') {
    $check=$GLOBALS['xoopsSecurity']->check();

    if (!$check) {
        $op='display';
        $err_message = _MD_QUERIES_TOKEN_ERR;
    }
}

// required fields check
if ($op == 'update') {
    if ($query_name == '' && $user_mode=='anon') {
        $op='display';
        $err_message = sprintf(_MD_QUERIES_REQUIRED_ERR, _MD_QUERIES_NAME);
    } elseif ($query_title == '') {
        $op='display';
        $err_message = sprintf(_MD_QUERIES_REQUIRED_ERR, _MD_QUERIES_TITLE);
    } elseif ($query_querytext == '') {
        $op='display';
        $err_message = sprintf(_MD_QUERIES_REQUIRED_ERR, _MD_QUERIES_QUERYTEXT);
    }
}

if ($op=='update' && $useCaptcha) {
    xoops_load('XoopsCaptcha');
    $xoopsCaptcha = XoopsCaptcha::getInstance();
    if (!$xoopsCaptcha->verify()) {
        $op = 'display';
        $err_message = $xoopsCaptcha->getMessage();
    }
}

if ($op == 'update') {
    // escape any strings
    $query_name = $xoopsDB->escape($query_name);
    $query_email = $xoopsDB->escape($query_email);
    $query_title = $xoopsDB->escape($query_title);
    $query_querytext = $xoopsDB->escape($query_querytext);
    $query_conversion_data_1 = $xoopsDB->escape($query_conversion_data_1);
    $query_conversion_data_2 = $xoopsDB->escape($query_conversion_data_2);
    $query_posted = $xoopsDB->escape($query_posted);

    $setapprove='0';
    if ($approve_own) {
        $setapprove = '1';
    }


    // blank name and email if not annoymous
    if ($query_uid != 0) {
        $query_name = '';
        $query_email = '';
    }

    // determine if we are editing or adding
    if ($query_id==0) {
        // insert new post
        $sql ="INSERT INTO ".$xoopsDB->prefix('queries_query').' ';

        if ($user_mode=='anon') {
            $sql.=" (uid, name, email, title, querytext, approved, posted) ";
            $sql.="VALUES (0, '$query_name', '$query_email', '$query_title', '$query_querytext', $setapprove, UNIX_TIMESTAMP() )";
            $query_approved=$approve_own;
        }
        if ($user_mode=='reg') {
            $sql.=" (uid, title, querytext, approved, posted) ";
            $sql.="VALUES ($query_uid, '$query_title', '$query_querytext', $setapprove, UNIX_TIMESTAMP() )";
            $query_approved=$approve_own;
        }
        if ($user_mode=='editor') {
            $sql.=" (uid, name, email, title, querytext, approved, posted) ";
            $sql.="VALUES ($query_uid, '$query_name', '$query_email', '$query_title', '$query_querytext', $query_approved, UNIX_TIMESTAMP() )";
        }
        if ($user_mode=='admin') {
            $timestamp=$query_posted;
            if ($query_posted=="") {
                $timestamp='';
            } else {
                $timestamp="'$query_posted'";
            }
            $sql.=" (uid, name, email, title, querytext, comment_count, approved, posted, conversion_data_1, conversion_data_2) ";
            $sql.="VALUES ($query_uid, '$query_name', '$query_email', '$query_title', '$query_querytext', $query_comment_count, $query_approved, UNIX_TIMESTAMP($timestamp), '$query_conversion_data_1', '$query_conversion_data_1') ";
        }

        $result = $xoopsDB->queryF($sql);
        if ($result) {
            notificationTrigger($xoopsDB->getInsertId(), $query_title, $query_querytext, $query_approved);
            $message = _MD_QUERIES_EDIT_MSG_ADD;
            if (!$query_approved) {
                $message .= ' '._MD_QUERIES_EDIT_MSG_PENDING;
            }
            redirect_header(XOOPS_URL.'/modules/queries/index.php', 3, $message);
        } else {
            $err_message = _MD_QUERIES_DB_INS_ERR .' '.$xoopsDB->errno() . ' ' . $xoopsDB->error();
        }
    } else {
        $sql ="UPDATE ".$xoopsDB->prefix('queries_query').' ';

        if ($user_mode=='reg') {
            $sql.=" SET title='$query_title', querytext='$query_querytext', approved=$setapprove ";
            $query_approved=$approve_own;
        }
        if ($user_mode=='editor') {
            $sql.=" SET uid=$query_uid, name='$query_name', email='$query_email', title='$query_title', querytext='$query_querytext', approved=$query_approved ";
        }
        if ($user_mode=='admin') {
            $timestamp=$query_posted;
            if ($query_posted=="") {
                $timestamp='';
            } else {
                $timestamp="'$query_posted'";
            }
            $sql.=" SET uid=$query_uid, name='$query_name', email='$query_email', title='$query_title', querytext='$query_querytext', comment_count=$query_comment_count, approved=$query_approved, posted=UNIX_TIMESTAMP($timestamp), conversion_data_1='$query_conversion_data_1', conversion_data_2='$query_conversion_data_2' ";
        }
        $sql.=" WHERE id=$query_id ";
        $result = $xoopsDB->queryF($sql);
        if ($result) {
            notificationTrigger($xoopsDB->getInsertId(), $query_title, $query_querytext, $query_approved);
            $message = _MD_QUERIES_EDIT_MSG_UPD;
            if (!$query_approved) {
                $message .= ' '._MD_QUERIES_EDIT_MSG_PENDING;
            }
            redirect_header(XOOPS_URL.'/modules/queries/index.php', 3, $message);
        } else {
            $err_message = _MD_QUERIES_DB_UPD_ERR .' '.$xoopsDB->errno() . ' ' . $xoopsDB->error();
        }
    }
    // clean any escaped stuff
    $query_name=cleaner($query_name);
    $query_email=cleaner($query_email);
    $query_title=cleaner($query_title);
    $query_querytext=cleaner($query_querytext);
    $query_conversion_data_1=cleaner($query_conversion_data_1);
    $query_conversion_data_2=cleaner($query_conversion_data_2);
    $query_posted=cleaner($query_posted);

    $op='display';
}

if ($op=='display') {
// determine if we are editing or adding
    if ($query_id == 0) {
        // new post
        $query_uid = $myuserid;
        if ($approve_own) {
            $query_approved = 1;
        }
    }
    $token = 1;
    $joiner1 = '<br /><br /><span style="font-weight:normal;">';
    $joiner2 = '</span>';
    $form = new XoopsThemeForm('Submit Query', 'form1', 'edit.php', 'POST', $token);

    if ($approve_others) {
        // caption, name, include_annon, size (1 for dropdown), multiple
        $caption = _MD_QUERIES_USER . $joiner1 . _MD_QUERIES_USER_DSC . $joiner2;
        $form->addElement(new XoopsFormSelectUser($caption, 'uid', true, $query_uid, 1, false));
    }
    if ($user_mode == 'reg') {
        $caption = _MD_QUERIES_USER;
        $myname = $xoopsUser->getVar('name');
        if ($myname == '') {
            $myname = $xoopsUser->getVar('uname');
        }
        $form->addElement(new XoopsFormLabel($caption, $myname));
        $form->addElement(new XoopsFormHidden('uid', $myuserid));
    }
    if ($user_mode != 'reg') {
        $caption = _MD_QUERIES_NAME . $joiner1 . _MD_QUERIES_NAME_DSC . $joiner2;
        $form->addElement(new XoopsFormText($caption, 'name', 20, 30, htmlspecialchars($query_name, ENT_QUOTES)));
        $caption = _MD_QUERIES_EMAIL . $joiner1 . _MD_QUERIES_EMAIL_DSC . $joiner2;
        $form->addElement(new XoopsFormText($caption, 'email', 20, 30, htmlspecialchars($query_email, ENT_QUOTES)));
    }

    $caption = _MD_QUERIES_TITLE . $joiner1 . _MD_QUERIES_TITLE_DSC . $joiner2;
    $form->addElement(new XoopsFormText($caption, 'title', 50, 100, htmlspecialchars($query_title, ENT_QUOTES)));

    $caption = _MD_QUERIES_QUERYTEXT . $joiner1 . _MD_QUERIES_QUERYTEXT_DSC . $joiner2;
    $form->addElement(new XoopsFormTextArea($caption, 'querytext', $query_querytext, 20, 65), false);

    if ($use_captcha) {
        $form->addElement(new XoopsFormCaptcha());
    }

    if ($approve_others) {
        $caption = _MD_QUERIES_APPROVED;
        $checked_value = 1;
        $checkbox = new XoopsFormCheckBox($caption, 'approved', $query_approved);
        $checkbox->addOption($checked_value, _MD_QUERIES_APPROVED_DSC);
        $form->addElement($checkbox);
    }

    if ($user_mode == 'admin') {
        $caption = _MD_QUERIES_POSTED . $joiner1 . _MD_QUERIES_POSTED_DSC . $joiner2;
        $form->addElement(new XoopsFormText($caption, 'posted', 30, 50, htmlspecialchars($query_posted, ENT_QUOTES)));

        $caption = _MD_QUERIES_COMMENTS . $joiner1 . _MD_QUERIES_COMMENTS_DSC . $joiner2;
        $form->addElement(new XoopsFormText($caption, 'comment_count', 10, 15, htmlspecialchars($query_comment_count, ENT_QUOTES)));

        $caption = _MD_QUERIES_CONV1 . $joiner1 . _MD_QUERIES_CONV1_DSC . $joiner2;
        $form->addElement(new XoopsFormText($caption, 'conversion_data_1', 50, 250, htmlspecialchars($query_conversion_data_1, ENT_QUOTES)));

        $caption = _MD_QUERIES_CONV2 . $joiner1 . _MD_QUERIES_CONV2_DSC . $joiner2;
        $form->addElement(new XoopsFormText($caption, 'conversion_data_2', 50, 250, htmlspecialchars($query_conversion_data_2, ENT_QUOTES)));
    }

    $form->addElement(new XoopsFormHidden('id', $query_id));

    $form->addElement(new XoopsFormButton(_MD_QUERIES_EDIT_CAPTION, 'submit', _MD_QUERIES_EDIT_BUTTON, 'submit'));

    //$form->display();
    $body = $form->render();
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
if (isset($debug)) {
    $xoopsTpl->assign('debug', $debug);
}

include XOOPS_ROOT_PATH . '/footer.php';
