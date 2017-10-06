<?php
// ------------------------------------------------------------------------- //
//                Queries - XOOPS genealogical queries                       //
// ------------------------------------------------------------------------- //

//
// Form Part
//
use Xmf\Module\Admin;

//
// Form Part
//
require dirname(__FILE__) . '/admin_header.php';

/** @var Admin $moduleAdmin */
$moduleAdmin = Admin::getInstance();
$moduleAdmin->displayNavigation(basename(__FILE__));

global $xoopsModule, $xoopsConfig;

if (file_exists(XOOPS_ROOT_PATH . '/modules/queries/language/' . $xoopsConfig['language'] . '/modinfo.php')) {
    include_once XOOPS_ROOT_PATH .  '/modules/queries/language/' . $xoopsConfig['language'] . '/modinfo.php';
} else {
    include_once XOOPS_ROOT_PATH . '/modules/queries/language/english/modinfo.php';
}

include_once XOOPS_ROOT_PATH . '/class/xoopsform/grouppermform.php';
$module_id = $xoopsModule->getVar('mid');

$item_list = array('1' => _MI_QUERIES_AD_PERM_1, '2' => _MI_QUERIES_AD_PERM_2);

$title_of_form = _MI_QUERIES_AD_PERM_TITLE;
$perm_name = 'queries_approve';

$form = new XoopsGroupPermForm($title_of_form, $module_id, $perm_name, $perm_desc);
foreach ($item_list as $item_id => $item_name) {
    $form->addItem($item_id, $item_name);
}

$moduleAdmin->addItemButton(_MD_QUERY_LIST_REVIEW, XOOPS_URL.'/modules/queries/review.php', 'button_ok');
$moduleAdmin->displayButton();

echo "<div>";
echo $form->render();
echo "</div>";

require dirname(__FILE__) . '/admin_footer.php';
