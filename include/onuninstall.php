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
 *
 * @package         XoopsModules\Pedigree
 * @author          XOOPS Development Team <https://xoops.org>
 * @copyright       {@link https://xoops.org/ XOOPS Project}
 * @license         GPL 2.0 or later
 * @link            https://xoops.org/
 */

use XoopsModules\Pedigree\{
    Common\Configurator,
    Helper,
    Utility
};

require dirname(__DIR__) . '/preloads/autoloader.php';

/**
 * Prepares system prior to attempting to uninstall module
 * @param \XoopsModule $module {@link XoopsModule}
 *
 * @return bool true if ready to uninstall, false if not
 */
function xoops_module_pre_uninstall_pedigree(\XoopsModule $module): bool
{
    // Do some synchronization if needed
    return true;
}

/**
 * Performs tasks required during uninstallation of the module
 * @param \XoopsModule $module {@link XoopsModule}
 *
 * @return bool true if uninstallation successful, false if not
 */
function xoops_module_uninstall_pedigree(\XoopsModule $module): bool
{
    $moduleDirName      = basename(dirname(__DIR__));
    $moduleDirNameUpper = mb_strtoupper($moduleDirName); //$capsDirName

    $helper       = Helper::getInstance();
    $configurator = new Configurator();

    //    $configurator = new Pedigree\Common\Configurator();

    // Load language files
    $helper->loadLanguage('admin');
    $helper->loadLanguage('common');
    $success = true;

    //------------------------------------------------------------------
    // Remove uploads folder (and all subfolders) if they exist
    //------------------------------------------------------------------
    if (0 < count($configurator->uploadFolders)) {
        foreach (array_keys($configurator->uploadFolders) as $i) {
           $success = $success && Utility::deleteDirectory($configurator->uploadFolders[$i]);
        }
    }

    return $success;
    //------------ END  ----------------
}
