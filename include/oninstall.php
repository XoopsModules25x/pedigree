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
 * @copyright       The TXMod XOOPS Project http://sourceforge.net/projects/thmod/
 * @copyright       XOOPS Project (https://xoops.org)
 * @license         GPL 2.0 or later
 * @package         animal
 * @since           2.5.x
 * @author          XOOPS Development Team ( name@site.com ) - ( https://xoops.org )
 */

$indexFile = 'index.php';

$blankFile = XOOPS_ROOT_PATH . '/modules/TDMCreate/assets/images/icons/blank.gif';
global $xoopsModule;
//Creation du dossier "uploads" pour le module à la racine du site
$module_uploads = XOOPS_ROOT_PATH . '/uploads/' . $xoopsModule->dirname();
if (!is_dir($module_uploads)) {
    if (!mkdir($module_uploads, 0777) && !is_dir($module_uploads)) {
        throw new \RuntimeException(sprintf('Directory "%s" was not created', $module_uploads));
    }
}
chmod($module_uploads, 0777);
copy($indexFile, XOOPS_ROOT_PATH . '/uploads/' . $xoopsModule->dirname() . '/index.php');

//Creation du fichier pedigree_trash dans uploads
$module_uploads = XOOPS_ROOT_PATH . '/uploads/' . $xoopsModule->dirname() . '/pedigree_trash';
if (!is_dir($module_uploads)) {
    if (!mkdir($module_uploads, 0777) && !is_dir($module_uploads)) {
        throw new \RuntimeException(sprintf('Directory "%s" was not created', $module_uploads));
    }
}
chmod($module_uploads, 0777);
copy($indexFile, XOOPS_ROOT_PATH . '/uploads/' . $xoopsModule->dirname() . '/pedigree_trash/index.php');

//Creation du fichier owner dans uploads
$module_uploads = XOOPS_ROOT_PATH . '/uploads/' . $xoopsModule->dirname() . '/owner';
if (!is_dir($module_uploads)) {
    if (!mkdir($module_uploads, 0777) && !is_dir($module_uploads)) {
        throw new \RuntimeException(sprintf('Directory "%s" was not created', $module_uploads));
    }
}
chmod($module_uploads, 0777);
copy($indexFile, XOOPS_ROOT_PATH . '/uploads/' . $xoopsModule->dirname() . '/owner/index.php');

//Creation du fichier pedigree_temp dans uploads
$module_uploads = XOOPS_ROOT_PATH . '/uploads/' . $xoopsModule->dirname() . '/pedigree_temp';
if (!is_dir($module_uploads)) {
    if (!mkdir($module_uploads, 0777) && !is_dir($module_uploads)) {
        throw new \RuntimeException(sprintf('Directory "%s" was not created', $module_uploads));
    }
}
chmod($module_uploads, 0777);
copy($indexFile, XOOPS_ROOT_PATH . '/uploads/' . $xoopsModule->dirname() . '/pedigree_temp/index.php');

//Creation du fichier pedigree dans uploads
$module_uploads = XOOPS_ROOT_PATH . '/uploads/' . $xoopsModule->dirname() . '/pedigree';
if (!is_dir($module_uploads)) {
    if (!mkdir($module_uploads, 0777) && !is_dir($module_uploads)) {
        throw new \RuntimeException(sprintf('Directory "%s" was not created', $module_uploads));
    }
}
chmod($module_uploads, 0777);
copy($indexFile, XOOPS_ROOT_PATH . '/uploads/' . $xoopsModule->dirname() . '/pedigree/index.php');

//Creation du fichier pedigree_config dans uploads
$module_uploads = XOOPS_ROOT_PATH . '/uploads/' . $xoopsModule->dirname() . '/pedigree_config';
if (!is_dir($module_uploads)) {
    if (!mkdir($module_uploads, 0777) && !is_dir($module_uploads)) {
        throw new \RuntimeException(sprintf('Directory "%s" was not created', $module_uploads));
    }
}
chmod($module_uploads, 0777);
copy($indexFile, XOOPS_ROOT_PATH . '/uploads/' . $xoopsModule->dirname() . '/pedigree_config/index.php');
