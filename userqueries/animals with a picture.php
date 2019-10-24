<?php
/*
 You may not change or alter any portion of this comment or credits
 of supporting developers from this source code or any supporting source code
 which is considered copyrighted (c) material of the original comment or credit authors.

 This program is distributed in the hope that it will be useful,
 but WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 */
/**
 * Pedigree module for XOOPS
 *
 * @copyright       Copyright (c) 2001-2019 {@link https://xoops.org XOOPS Project}
 * @license         https://www.gnu.org/licenses/gpl-2.0.html GNU Public License
 * @package         \XoopsModules\Pedigree\Class
 * @author          XOOPS Module Development Team (https://xoops.org)
 */

use XoopsModules\Pedigree;

/**
 * @var \XoopsModules\Pedigree\Helper $helper
 * @var \XoopsModules\Pedigree\TreeHandler $treeHandler
 */
$helper      = XoopsModules\Pedigree\Helper::getInstance();
$treeHandler = $helper->getHandler('Tree');
$criteria    = new \Criteria('foto', '', '<>');
$treeObjs    = $treeHandler->getAll($criteria);
$countPic    = $treeHandler->getCount($criteria);

$form        = 'This is an example of a userquery.<br><br>Shown below are the animals in your database that have a picture.<hr>';

foreach ($treeObjs as $treeObj) {
    $form .= '<a href="pedigree.php?pedid=' . $treeObj->getVar('id') . '">' . $treeObj->getVar('naam') . '</a><br>';
}
/*
$sql      = 'SELECT id, naam FROM ' . $GLOBALS['xoopsDB']->prefix('pedigree_tree') . " WHERE foto != ''";
$result   = $GLOBALS['xoopsDB']->query($sql);
$countPic = 0;
while (false !== ($row = $GLOBALS['xoopsDB']->fetchArray($result))) {
    $form .= '<a href="pedigree.php?pedid=' . $row['id'] . '">' . $row['naam'] . '</a><br>';
    ++$countPic;
}
*/
$form .= "<hr>There are a total of {$countPic} animals with a picture";
