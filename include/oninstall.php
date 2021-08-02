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
 * animal module for xoops
 *
 * @package         XoopsModules\Pedigree
 * @copyright       The TXMod XOOPS Project https://sourceforge.net/projects/thmod/
 * @copyright       XOOPS Project (https://xoops.org)
 * @license         GPL 2.0 or later
 * @author          XOOPS Development Team <https://xoops.org>
 */

use Xmf\Module\Helper\Permission;
use XoopsModules\Pedigree\{
    Common\Configurator,
    Constants,
    Helper,
    Utility
};
require dirname(__DIR__) . '/preloads/autoloader.php';

/**
 * Prepares system prior to attempting to install module
 * @param \XoopsModule $module {@link XoopsModule}
 *
 * @return bool true if ready to install, false if not
 */
function xoops_module_pre_install_pedigree(\XoopsModule $module)
{
    //check for minimum XOOPS version
    $xoopsSuccess = Utility::checkVerXoops($module);

    // check for minimum PHP version
    $phpSuccess = Utility::checkVerPhp($module);

    // delete the SQL tables if they exist, check to make sure they were deleted (DROPPED)
    /** @TODO: use Xmf\Tables to handle dropping tables from dB */
    $sqlSuccess = true;
    if (false !== $xoopsSuccess && false !== $phpSuccess) {
        $moduleTables = $module->getInfo('tables');
        foreach ($moduleTables as $table) {
            $success = $GLOBALS['xoopsDB']->queryF('DROP TABLE IF EXISTS ' . $GLOBALS['xoopsDB']->prefix($table) . ';');
            $success = false === $success || true;
            $sqlSuccess = $sqlSuccess && $success;
        }
    }

    return $xoopsSuccess && $phpSuccess && $sqlSuccess;
}

/**
 * Performs tasks required during installation of the module
 * @param \XoopsModule $module {@link XoopsModule}
 *
 * @return bool true if installation successful, false if not
 */
function xoops_module_install_pedigree(\XoopsModule $module)
{
    $helper        = Helper::getInstance();
    $moduleDirName = $helper->getDirname();
    $configurator  = new Configurator();

    // Load language files
    $helper->loadLanguage('admin');
    $helper->loadLanguage('modinfo');

    // default Permission Settings ----------------------
    $mid = $module->getVar('mid');

    // access rights ------------------------------------------
    $permHandler = new Permission($moduleDirName);
    $permHandler->savePermissionForItem($moduleDirName . '_approve', 1, [XOOPS_GROUP_ADMIN]);
    $permHandler->savePermissionForItem($moduleDirName . '_submit', 1, [XOOPS_GROUP_ADMIN]);
    $permHandler->savePermissionForItem($moduleDirName . '_view', 1, [XOOPS_GROUP_ADMIN, XOOPS_GROUP_USERS, XOOPS_GROUP_ANONYMOUS]);

    //$moduleName = $module->getVar('name');
    /** @var \XoopsGroupPermHandler $grouppermHandler */
    /*
        $grouppermHandler = xoops_getHandler('groupperm');
        // access rights ------------------------------------------
        $grouppermHandler->addRight($moduleDirName . '_approve', 1, XOOPS_GROUP_ADMIN, $moduleId);
        $grouppermHandler->addRight($moduleDirName . '_submit', 1, XOOPS_GROUP_ADMIN, $moduleId);
        $grouppermHandler->addRight($moduleDirName . '_view', 1, XOOPS_GROUP_ADMIN, $moduleId);
        $grouppermHandler->addRight($moduleDirName . '_view', 1, XOOPS_GROUP_USERS, $moduleId);
        $grouppermHandler->addRight($moduleDirName . '_view', 1, XOOPS_GROUP_ANONYMOUS, $moduleId);
    */
    //  ---  CREATE FOLDERS ---------------
    if (count($configurator->uploadFolders) > 0) {
        //    foreach (array_keys($GLOBALS['uploadFolders']) as $i) {
        foreach (array_keys($configurator->uploadFolders) as $i) {
            Utility::createFolder($configurator->uploadFolders[$i]);
        }
    }
    //  ---  COPY blank.png FILES ---------------
    if (count($configurator->copyBlankFiles) > 0) {
        $file = $helper->path('assets/images/blank.png');
        foreach (array_keys($configurator->copyBlankFiles) as $i) {
            $dest = $configurator->copyBlankFiles[$i] . '/blank.png';
            Utility::copyFile($file, $dest);
        }
    }

    //  ---  COPY blank.png FILES ---------------
    if (count($configurator->copyBlankFiles) > 0) {
        $file = dirname(__DIR__) . '/assets/images/blank.png';
        foreach (array_keys($configurator->copyBlankFiles) as $i) {
            $dest = $configurator->copyBlankFiles[$i] . '/blank.png';
            Utility::copyFile($file, $dest);
        }
    }

    //  ---  COPY test folder files ---------------
    if (count($configurator->copyTestFolders) > 0) {
        //$file =  dirname(__DIR__) . '/testdata/images/';
        foreach (array_keys($configurator->copyTestFolders) as $i) {
            $src  = $configurator->copyTestFolders[$i][0];
            $dest = $configurator->copyTestFolders[$i][1];
            Utility::rcopy($src, $dest);
        }
    }

    //delete .html entries from the tpl table
    $sql = 'DELETE FROM ' . $GLOBALS['xoopsDB']->prefix('tplfile') . " WHERE `tpl_module` = '" . $moduleDirName . "' AND `tpl_file` LIKE '%.html'";
    $GLOBALS['xoopsDB']->queryF($sql);

    return true;
}
