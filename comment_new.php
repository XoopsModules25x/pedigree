<?php
/*
 * You may not change or alter any portion of this comment or credits
 * of supporting developers from this source code or any supporting source code
 * which is considered copyrighted (c) material of the original comment or credit authors.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 */

/**
 * @copyright      {@link https://xoops.org/ XOOPS Project}
 * @license        {@link https://www.gnu.org/licenses/gpl-2.0.html GNU GPL 2 or later}
 * @author         XOOPS Development Team
 */

use Xmf\Request;

require_once \dirname(__DIR__, 2) . '/mainfile.php';
$com_itemid = Request::getInt('com_itemid', 0, 'GET');
if ($com_itemid > 0) {
    // Get link title
    $sql            = 'SELECT pname FROM ' . $GLOBALS['xoopsDB']->prefix('pedigree_registry') . ' WHERE id=' . $com_itemid . ' ';
    $result         = $GLOBALS['xoopsDB']->query($sql);
    $row            = $GLOBALS['xoopsDB']->fetchArray($result);
    $com_replytitle = stripslashes($row['pname']);
    require XOOPS_ROOT_PATH . '/include/comment_new.php';
}
