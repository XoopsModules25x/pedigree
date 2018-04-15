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

require_once  dirname(dirname(dirname(__DIR__))) . '/mainfile.php';

if (!defined('PEDIGREE_DIRNAME')) {
    define('PEDIGREE_DIRNAME', $GLOBALS['xoopsModule']->dirname());
    define('PEDIGREE_PATH', XOOPS_ROOT_PATH . '/modules/' . PEDIGREE_DIRNAME);
    define('PEDIGREE_URL', XOOPS_URL . '/modules/' . PEDIGREE_DIRNAME);
    define('PEDIGREE_ADMIN', PEDIGREE_URL . '/admin/index.php');
    define('PEDIGREE_ROOT_PATH', XOOPS_ROOT_PATH . '/modules/' . PEDIGREE_DIRNAME);
    //@todo - the image xoopsproject_logo.png doesn't exist... Either create it or reference
    //        something like $GLOBALS['xoops']->url("www/{$pathIcon32}/xoopsmicrobutton.gif")
    define('PEDIGREE_AUTHOR_LOGOIMG', PEDIGREE_URL . '/assets/images/xoopsproject_logo.png');
}

// Define the main upload path
define('PEDIGREE_UPLOAD_URL', XOOPS_UPLOAD_URL . '/' . PEDIGREE_DIRNAME); // WITHOUT Trailing slash
define('PEDIGREE_UPLOAD_PATH', XOOPS_UPLOAD_PATH . '/' . PEDIGREE_DIRNAME); // WITHOUT Trailing slash

$uploadFolders = [
    PEDIGREE_UPLOAD_PATH,
    PEDIGREE_UPLOAD_PATH . '/images',
    PEDIGREE_UPLOAD_PATH . '/images/thumbnails'
];

// module information
$mod_copyright = "<a href='https://xoops.org' title='XOOPS Project' target='_blank'>
                     <img src='" . PEDIGREE_AUTHOR_LOGOIMG . "' alt='XOOPS Project'></a>";
