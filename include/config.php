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
 * @copyright       {@link https://xoops.org/  XOOPS Project}
 * @license         GPL 2.0 or later
 * @package         pedigree
 * @author          XOOPS Module Dev Team (https://xoops.org)
 */
function getConfig()
{
    $moduleDirName = basename(dirname(__DIR__));
    $moduleDirNameUpper = mb_strtoupper($moduleDirName);

    return (object)[
        'name' => mb_strtoupper($moduleDirName) . ' Module Configurator',
        'paths' => [
            'dirname' => $moduleDirName,
            'admin' => XOOPS_ROOT_PATH . '/modules/' . $moduleDirName . '/admin',
            'modPath' => XOOPS_ROOT_PATH . '/modules/' . $moduleDirName,
            'modUrl' => XOOPS_URL . '/modules/' . $moduleDirName,
            'uploadPath' => XOOPS_UPLOAD_PATH . '/' . $moduleDirName,
            'uploadUrl' => XOOPS_UPLOAD_URL . '/' . $moduleDirName,
        ],
        'uploadFolders' => [
            constant($moduleDirNameUpper . '_UPLOAD_PATH'),
            constant($moduleDirNameUpper . '_UPLOAD_PATH') . '/images',
            constant($moduleDirNameUpper . '_UPLOAD_PATH') . '/images/thumbnails',
            //XOOPS_UPLOAD_PATH . '/flags'
        ],
        'copyBlankFiles' => [
            constant($moduleDirNameUpper . '_UPLOAD_PATH'),
            constant($moduleDirNameUpper . '_UPLOAD_PATH') . '/images',
            constant($moduleDirNameUpper . '_UPLOAD_PATH') . '/images/thumbnails',
            //XOOPS_UPLOAD_PATH . '/flags'
        ],

        'copyTestFolders' => [
            //        constant($moduleDirNameUpper . '_UPLOAD_PATH'),
            //[
            //    constant($moduleDirNameUpper . '_PATH') . '/testdata/images',
            //    constant($moduleDirNameUpper . '_UPLOAD_PATH') . '/images',
            //]
        ],

        'templateFolders' => [
            '/templates/',
            '/templates/blocks/',
            '/templates/admin/',
        ],
        'oldFiles' => [
            '/class/request.php',
            '/class/registry.php',
            '/class/utilities.php',
            '/class/util.php',
            //            '/include/constants.php',
            //            '/include/functions.php',
            '/ajaxrating.txt',
        ],
        'oldFolders' => [
            '/images',
            '/css',
            '/js',
            '/tcpdf',
            '/images',
        ],
        'modCopyright' => "<a href='https://xoops.org' title='XOOPS Project' target='_blank'>
                     <img src='" . constant($moduleDirNameUpper . '_AUTHOR_LOGOIMG') . '\' alt=\'XOOPS Project\' /></a>',
    ];
}
