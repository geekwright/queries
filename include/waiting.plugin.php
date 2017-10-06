<?php
function b_waiting_queries()
{
    $xoopsDB = XoopsDatabaseFactory::getDatabaseConnection();
    $ret = array() ;

    $block = array();
    $result = $xoopsDB->query("SELECT COUNT(*) FROM ".$xoopsDB->prefix('queries_query')." WHERE approved=0");
    if ($result) {
        $block['adminlink'] = XOOPS_URL . '/modules/queries/review.php' ;
        list($block['pendingnum']) = $xoopsDB->fetchRow($result);
        $block['lang_linkname'] = _PI_WAITING_QUERIES_REVIEW ;
    }
    $ret[] = $block ;

    return $ret;
}
