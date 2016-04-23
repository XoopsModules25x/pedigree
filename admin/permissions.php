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
 * xdirectory module for xoops
 *
 * @copyright       The TXMod XOOPS Project http://sourceforge.net/projects/thmod/
 * @copyright       The XOOPS Project http://sourceforge.net/projects/xoops/
 * @license         GPL 2.0 or later
 * @package         xdirectory
 * @since           2.5.x
 * @author          XOOPS Development Team ( name@site.com ) - ( http://xoops.org )
 * @version         $Id: const_entete.php 9860 2012-07-13 10:41:41Z txmodxoops $
 */

include_once __DIR__ . '/admin_header.php';
//xoops_cp_header();
include_once XOOPS_ROOT_PATH . "/class/xoopsform/grouppermform.php";
//include_once XOOPS_ROOT_PATH."/class/xoopsform/FormHiddenToken.php";

if (!empty($_POST["submit"])) {
    redirect_header(XOOPS_URL . "/modules/" . $xoopsModule->dirname() . "/admin/permissions.php", 1, _MP_GPERMUPDATED);
}

$permissions_admin = new ModuleAdmin();
echo $permissions_admin->addNavigation("permissions.php");

$permission                = animal_CleanVars($_POST, 'permission', 1, 'int');
$selected                  = array("", "", "");
$selected[$permission - 1] = " selected";

echo "
<form method=\"post\" name=\"fselperm\" action=\"permissions.php\">
    <table border=0>
        <tr>
            <td>
                <select name=\"permission\" onChange=\"document.fselperm.submit()\">
                    <option value=\"1\"" . $selected[0] . ">" . _AM_PEDIGREE_PERMISSIONS_ACCESS . "</option>
                    <option value=\"2\"" . $selected[1] . ">" . _AM_PEDIGREE_PERMISSIONS_SUBMIT . "</option>
                    <option value=\"3\"" . $selected[2] . ">" . _AM_PEDIGREE_PERMISSIONS_VIEW . "</option>
                </select>
            </td>
        </tr>
    </table>
</form>";

$module_id = $xoopsModule->getVar("mid");

switch ($permission) {
    case 1:
        $formTitle = _AM_PEDIGREE_PERMISSIONS_ACCESS;
        $permName  = "xdirectory_access";
        $permDesc  = "";
        break;
    case 2:
        $formTitle = _AM_PEDIGREE_PERMISSIONS_SUBMIT;
        $permName  = "xdirectory_submit";
        $permDesc  = "";
        break;
    case 3:
        $formTitle = _AM_PEDIGREE_PERMISSIONS_VIEW;
        $permName  = "xdirectory_view";
        $permDesc  = "";
        break;
}

$permform = new XoopsGroupPermForm($formTitle, $module_id, $permName, $permDesc, "admin/permissions.php");
//	$xdir_cat_Handler= xoops_getModuleHandler('xdirectory_xdir_cat', $xoopsModule->getVar("dirname"));
$criteria = new CriteriaCompo();
$criteria->setSort('title');
$criteria->setOrder('ASC');
//    $xdir_cat_arr = $xdir_cat_Handler->getObjects($criteria);

//foreach (array_keys($xdir_cat_arr) as $xdir_cat_id => $xdir_cat)
foreach (array_keys($xdir_cat_arr) as $i) {
    //$permform->addItem($xdir_cat_id, $xdir_cat["xdir_cat_title"], $xdir_cat["xdir_cat_pid"]);
    $permform->addItem($xdir_cat_arr[$i]->getVar('cid'), $xdir_cat_arr[$i]->getVar('title'));
}
echo $permform->render();
echo "<br /><br /><br /><br />\n";
unset ($permform);

include_once __DIR__ . '/admin_footer.php';
