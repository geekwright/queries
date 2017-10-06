<?php

$modversion['dirname'] = basename(__DIR__);

$modversion['name'] = _MI_QUERIES_NAME;
$modversion['version'] = '1.0.0';
$modversion['description'] = _MI_QUERIES_DESC;
$modversion['credits']             = 'Richard Griffith';
$modversion['min_php']             = '5.3.7';
$modversion['min_xoops']           = '2.5.9';
$modversion['system_menu']         = 1;
$modversion['help']                = 'page=help';
$modversion['license']             = 'GNU GPL v2 or higher';
$modversion['license_url']         = XOOPS_URL . '/modules/' . $modversion['dirname'] . '/docs/license.txt';
$modversion['official']            = 0;
$modversion['image']               = 'assets/images/logoModule.png';

// About stuff
$modversion['module_status']       = 'Alpha';
$modversion['release_date']        = '09/20/2017';

$modversion['developer_lead']      = 'geekwright';
$modversion['module_website_url']  = 'https://xoops.org';
$modversion['module_website_name'] = 'XOOPS';
$modversion['developer_email']     = 'richard@geekwright.com';


// Admin things
$modversion['hasAdmin'] = 1;
$modversion['adminindex'] = 'admin/index.php';
$modversion['adminmenu'] = 'admin/menu.php';

// Menu
$modversion['hasMain'] = 1;

// Search
$modversion['hasSearch'] = 1;
$modversion['search']['file'] = 'include/search.php';
$modversion['search']['func'] = 'queries_search';


// comments
$modversion['hasComments'] = 1;
$modversion['comments']['itemName'] = 'id';
$modversion['comments']['pageName'] = 'view.php';

$modversion['comments']['callbackFile'] = 'include/comment_functions.php';
// $modversion['comments']['callback']['approve'] = 'mylinks_com_approve';
$modversion['comments']['callback']['update'] = 'queries_com_update';

// notification
$modversion['hasNotification'] = 1;
$modversion['notification']['lookup_file'] = 'include/notification.inc.php';
$modversion['notification']['lookup_func'] = 'queries_notify_iteminfo';

$modversion['notification']['category'][1] = array(
    'name'           => 'global',
    'title'          => _MI_QUERIES_GLOBAL_NOTIFY,
    'description'    => _MI_QUERIES_GLOBAL_NOTIFY_DSC,
    'subscribe_from' => array('index.php','view.php', 'edit.php'),
);

$modversion['notification']['category'][2] = array(
    'name'           => 'query',
    'title'          => _MI_QUERIES_SINGLE_NOTIFY,
    'description'    => _MI_QUERIES_SINGLE_NOTIFY_DSC,
    'subscribe_from' => array('view.php'),
    'item_name'      => 'id',
    'allow_bookmark' => 0,
);

$modversion['notification']['event'][1] = array(
    'name'          => 'new_query',
    'category'      => 'global',
    'title'         => _MI_QUERIES_NEW_NOTIFY,
    'caption'       => _MI_QUERIES_NEW_NOTIFYCAP,
    'description'   => _MI_QUERIES_NEW_NOTIFYDSC,
    'mail_template' => 'new_query_notify',
    'mail_subject'  => _MI_QUERIES_NEW_NOTIFYSBJ,
);

$modversion['notification']['event'][2] = array(
    'name'          => 'approval_needed',
    'category'      => 'global',
    'title'         => _MI_QUERIES_NEW_NEED_APPROVAL,
    'caption'       => _MI_QUERIES_NEW_NEED_APPROVAL_CAP,
    'description'   => _MI_QUERIES_NEW_NEED_APPROVAL_DSC,
    'mail_template' => 'need_approval_notify',
    'mail_subject'  => _MI_QUERIES_NEW_NEED_APPROVAL_SBJ,
    'admin_only'    => 1,
);


// Config

$modversion['config'][1]['name'] = 'pref_date';
$modversion['config'][1]['title'] = '_MI_QUERIES_PREF_DATE';
$modversion['config'][1]['description'] = '_MI_QUERIES_PREF_DATE_DSC';
$modversion['config'][1]['formtype'] = 'textbox';
$modversion['config'][1]['valuetype'] = 'text';
$modversion['config'][1]['default'] = 's';

$modversion['config'][2]['name'] = 'pref_rows';
$modversion['config'][2]['title'] = '_MI_QUERIES_PREF_ROWS';
$modversion['config'][2]['description'] = '_MI_QUERIES_PREF_ROWS_DSC';
$modversion['config'][2]['formtype'] = 'textbox';
$modversion['config'][2]['valuetype'] = 'int';
$modversion['config'][2]['default'] = 25;

$modversion['config'][3]['name'] = 'postanon';
$modversion['config'][3]['title'] = '_MI_QUERIES_POST_ANON';
$modversion['config'][3]['description'] = '_MI_QUERIES_POST_ANON_DSC';
$modversion['config'][3]['formtype'] = 'yesno';
$modversion['config'][3]['valuetype'] = 'int';
$modversion['config'][3]['default'] = 0;

$modversion['config'][4]['name'] = 'captcha';
$modversion['config'][4]['title'] = '_MI_QUERIES_CAPTCHA';
$modversion['config'][4]['description'] = '_MI_QUERIES_CAPTCHA_DSC';
$modversion['config'][4]['formtype'] = 'yesno';
$modversion['config'][4]['valuetype'] = 'int';
$modversion['config'][4]['default'] = 0;



$modversion['sqlfile']['mysql'] = 'sql/mysql.sql';
$modversion['tables'] = array(
    'queries_query',
);

if (is_object($GLOBALS['xoopsModule']) && $GLOBALS['xoopsModule']->getVar('dirname') == $modversion['dirname']) {
    $modHelper = Xmf\Module\Helper::getHelper($modversion['dirname']);
    $permHelper = new Xmf\Module\Helper\Permission($modversion['dirname']);

    if (is_object($GLOBALS['xoopsUser']) || $modHelper->getConfig('postanon', false)) {
        $modversion['sub'][] = array(
            'name' => _MI_QUERIES_MENU_ADD,
            'url' => 'edit.php',
        );
    }
    if ($permHelper->checkPermission('queries_approve', 2)) {
        $modversion['sub'][] = array(
            'name' => _MI_QUERIES_MENU_REVIEW,
            'url' => 'review.php',
        );
    }
}

// Templates
$modversion['templates'][1]['file'] = 'queries_index.tpl';
$modversion['templates'][1]['description'] = 'Module Index';

$modversion['templates'][2]['file'] = 'queries_edit.tpl';
$modversion['templates'][2]['description'] = 'Edit Query';

$modversion['templates'][3]['file'] = 'queries_view.tpl';
$modversion['templates'][3]['description'] = 'Show Query';

$modversion['templates'][4]['file'] = 'queries_list.tpl';
$modversion['templates'][4]['description'] = 'List Surnames';

$modversion['templates'][5]['file'] = 'queries_review.tpl';
$modversion['templates'][5]['description'] = 'Approval Reviews';

$modversion['templates'][6]['file'] = 'queries_print.tpl';
$modversion['templates'][6]['description'] = 'Print Query';
