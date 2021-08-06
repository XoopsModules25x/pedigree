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

$form        = 'The following animals have been found in your database with a slash. Any escape characters have been removed.<hr>';
$sql = 'SELECT * FROM ' . $GLOBALS['xoopsDB']->prefix('pedigree_registry') . " WHERE pname LIKE '%\'%'";
$result      = $GLOBALS['xoopsDB']->query($sql);
while (false !== ($row = $GLOBALS['xoopsDB']->fetchArray($result))) {
    $form .= '<a href="pedigree.php?pedid=' . $row['id'] . '">' . $row['pname'] . '</a><br>';
    $sql  = 'UPDATE ' . $GLOBALS['xoopsDB']->prefix('pedigree_registry') . ' SET pname = "' . stripslashes($row['pname']) . "\" WHERE id = '" . $row['id'] . "'";
    $GLOBALS['xoopsDB']->queryF($sql);
}
