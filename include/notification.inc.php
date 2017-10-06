<?php
// $Id: notification.inc.php,v 1.3.2.1 2005/01/06 22:57:45 praedator Exp $
//  ------------------------------------------------------------------------ //
//                XOOPS - PHP Content Management System                      //
//                    Copyright (c) 2000 XOOPS.org                           //
//                       <http://www.xoops.org/>                             //
//  ------------------------------------------------------------------------ //
//  This program is free software; you can redistribute it and/or modify     //
//  it under the terms of the GNU General Public License as published by     //
//  the Free Software Foundation; either version 2 of the License, or        //
//  (at your option) any later version.                                      //
//                                                                           //
//  You may not change or alter any portion of this comment or credits       //
//  of supporting developers from this source code or any supporting         //
//  source code which is considered copyrighted (c) material of the          //
//  original comment or credit authors.                                      //
//                                                                           //
//  This program is distributed in the hope that it will be useful,          //
//  but WITHOUT ANY WARRANTY; without even the implied warranty of           //
//  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the            //
//  GNU General Public License for more details.                             //
//                                                                           //
//  You should have received a copy of the GNU General Public License        //
//  along with this program; if not, write to the Free Software              //
//  Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307 USA //
//  ------------------------------------------------------------------------ //
if (!defined('QUERIES_NOTIFY_ITEMINFO')) {
    define('QUERIES_NOTIFY_ITEMINFO', 1);

    function queries_notify_iteminfo($category, $item_id)
    {
        $module_handler = xoops_gethandler('module');
        $module = $module_handler->getByDirname('queries');

        if ($category=='global') {
            $item['name'] = '?';
            $item['url'] = XOOPS_URL . '/modules/queries/view.php?id=' . $item_id;
            return $item;
        }

        global $xoopsDB;
        if ($category=='query') {
            $sql = "SELECT title FROM ".$xoopsDB->prefix('queries_query'). " WHERE id=$item_id";
            $row = $xoopsDB->fetchArray($xoopsDB->query($sql));

            $item['name'] = $row['title'];
            $item['url']  = XOOPS_URL."/modules/queries/view.php?id=".$item_id;

            return $item;
        }

        $item['name'] = $category;
        $item['url'] = XOOPS_URL . '/modules/queries/view.php?id=' . $item_id;
        return $item;
    }
}
