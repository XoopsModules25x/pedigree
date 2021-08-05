<?php
/*
 You may not change or alter any portion of this comment or credits of
 supporting developers from this source code or any supporting source code
 which is considered copyrighted (c) material of the original comment or credit
 authors.

 This program is distributed in the hope that it will be useful, but
 WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 */
/**
 * Module: Pedigree
 *
 * @package   XoopsModules\Pedigree
 * @author    XOOPS Module Development Team
 * @copyright Copyright (c) 2001-2019 {@link https://xoops.org XOOPS Project}
 * @license   https://www.gnu.org/licenses/gpl-2.0.html GNU Public License
 */

use Xmf\Request;
use XoopsModules\Pedigree;

require_once __DIR__ . '/header.php';
/** @var XoopsModules\Pedigree\Helper $helper */
$helper->loadLanguage('main');

include XOOPS_ROOT_PATH . '/header.php';

// Include any common code for this module.
require_once XOOPS_ROOT_PATH . '/modules/' . $moduleDirName . '/include/common.php';

$naar = Request::getCmd('naar', '', 'POST');
$van = Request::getString('van', '', 'POST');

echo "<form method=\"post\" action=\"convert.php\" method=\"post\">\n"
   . "convert:<input type=\"text\" name=\"van\">\n"
   . "to:<input type=\"text\" name=\"naar\">\n"
   . "<input type=\"submit\"></form>\n";

   //@todo refactor code to use Tree object access methods
if ('' != $_POST['naar']) {
    $query = 'UPDATE ' . $GLOBALS['xoopsDB']->prefix('pedigree_tree') . " SET user4 = '" . $naar . "' WHERE user4 = '" . $van . "'";
    echo $query . '<br>';
    $GLOBALS['xoopsDB']->query($query);
}

$result = $GLOBALS['xoopsDB']->query("SELECT user4, COUNT('user4') AS X FROM " . $GLOBALS['xoopsDB']->prefix('pedigree_tree') . " GROUP BY 'user4'");
$count = 0;
$total = 0;
while (false !== ($row = $GLOBALS['xoopsDB']->fetchArray($result))) {
    ++$count;
    echo $row['user4'] . ' - ' . $row['X'] . '<br>';
    $total += $row['X'];
}
echo '<hr>' . $count . '-' . $total;

//comments and footer
include XOOPS_ROOT_PATH . '/footer.php';
