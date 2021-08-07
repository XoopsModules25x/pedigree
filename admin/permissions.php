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
 * Module: Pedigree
 *
 * @package         XoopsModules\Pedigree
 * @copyright       2011-2019 XOOPS Project (https://xoops.org)
 * @license         GNU GPL 2 or later (https://www.gnu.org/licenses/gpl-2.0.html)
 * @author          XOOPS Module Dev Team (https://xoops.org)
 * @todo            Refactor this code - it currently doesn't work as intended
 */

use Xmf\Module\Admin;
use Xmf\Request;
use XoopsModules\Pedigree\{
    Constants,
    Helper
};
/** @var Helper $helper */
/** @var Admin $adminObject */

require_once __DIR__ . '/admin_header.php';
//xoops_cp_header();
require_once XOOPS_ROOT_PATH . '/class/xoopsform/grouppermform.php';
//require_once XOOPS_ROOT_PATH."/class/xoopsform/FormHiddenToken.php";

if (!empty($_POST['submit'])) {
    $helper->redirect('admin/permissions.php', Constants::REDIRECT_DELAY_SHORT, _MP_GPERMUPDATED);
}

$adminObject->displayNavigation(basename(__FILE__));

$permission                = Request::getInt('permission', 1, 'POST');
$selected                  = ['', '', ''];
$selected[$permission - 1] = ' selected';

echo '
<form method="post" name="fselperm" action="' . $helper->url('admin/permissions.php') . '">
    <table border=0>
        <tr>
            <td>
                <select name="permission" onChange="document.fselperm.submit()">
                    <option value="1"' . $selected[0] . '>' . _AM_PEDIGREE_PERMISSIONS_ACCESS . '</option>
                    <option value="2"' . $selected[1] . '>' . _AM_PEDIGREE_PERMISSIONS_SUBMIT . '</option>
                    <option value="3"' . $selected[2] . '>' . _AM_PEDIGREE_PERMISSIONS_VIEW . '</option>
                </select>
            </td>
        </tr>
    </table>
</form>';

//$module_id = $xoopsModule->getVar('mid');
$module_id = $helper->getModule()->getVar('mid');

switch ($permission) {
    case 1:
        $formTitle = _AM_PEDIGREE_PERMISSIONS_ACCESS;
        $permName  = 'pedigree_access';
        $permDesc  = '';
        break;
    case 2:
        $formTitle = _AM_PEDIGREE_PERMISSIONS_SUBMIT;
        $permName  = 'pedigree_submit';
        $permDesc  = '';
        break;
    case 3:
        $formTitle = _AM_PEDIGREE_PERMISSIONS_VIEW;
        $permName  = 'pedigree_view';
        $permDesc  = '';
        break;
}

$permform = new \XoopsGroupPermForm($formTitle, $module_id, $permName, $permDesc, 'admin/permissions.php');
//    $xdir_catHandler= xoops_getModuleHandler('xdirectory_xdir_cat', $xoopsModule->getVar("dirname"));
$criteria = new \CriteriaCompo();
$criteria->setSort('title');
$criteria->setOrder('ASC');
//    $xdir_cat_arr = $xdir_catHandler->getObjects($criteria);

//foreach (array_keys($xdir_cat_arr) as $xdir_cat_id => $xdir_cat)
foreach (array_keys($xdir_cat_arr) as $i) {
    //$permform->addItem($xdir_cat_id, $xdir_cat["xdir_cat_title"], $xdir_cat["xdir_cat_pid"]);
    $permform->addItem($xdir_cat_arr[$i]->getVar('cid'), $xdir_cat_arr[$i]->getVar('title'));
}
echo $permform->render();
echo "<br><br><br><br>\n";
unset($permform);

require_once __DIR__ . '/admin_footer.php';
