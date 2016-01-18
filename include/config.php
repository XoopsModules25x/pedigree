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
 * @copyright       The XOOPS Project http://sourceforge.net/projects/xoops/
 * @license         GPL 2.0 or later
 * @package         animal
 * @since           2.5.x
 * @author          XOOPS Development Team ( name@site.com ) - ( http://xoops.org )
 * @version         $Id: const_entete.php 9860 2012-07-13 10:41:41Z txmodxoops $
 */

require_once dirname(dirname(dirname(__DIR__))) . '/mainfile.php';

global $xoopsModule;
if (!defined('PEDIGREE_MODULE_PATH')) {
    define("PEDIGREE_DIRNAME", $xoopsModule->dirname());
    define("PEDIGREE_PATH", XOOPS_ROOT_PATH . "/modules/" . PEDIGREE_DIRNAME);
    define("PEDIGREE_URL", XOOPS_URL . "/modules/" . PEDIGREE_DIRNAME);
    define("PEDIGREE_ADMIN", PEDIGREE_URL . "/admin/index.php");
    define("PEDIGREE_AUTHOR_LOGOIMG", PEDIGREE_URL . "/assets/images/xoopsproject_logo.png");

}

// Define here the place where main upload path

$img_dir = $GLOBALS['xoopsModuleConfig']['uploaddir'];

define("PEDIGREE_UPLOAD_URL", XOOPS_UPLOAD_URL . '/' . PEDIGREE_DIRNAME); // WITHOUT Trailing slash
//define("PEDIGREE_UPLOAD_PATH", $img_dir); // WITHOUT Trailing slash
define("PEDIGREE_UPLOAD_PATH", XOOPS_UPLOAD_PATH . '/' . PEDIGREE_DIRNAME); // WITHOUT Trailing slash


$uploadFolders = array(
    PEDIGREE_UPLOAD_PATH,
    PEDIGREE_UPLOAD_PATH . '/images',
    PEDIGREE_UPLOAD_PATH . '/images/thumbnails'
);

// module information
$mod_copyright
    = "<a href='http://xoops.org' title='XOOPS Project' target='_blank'>
                     <img src='" . PEDIGREE_AUTHOR_LOGOIMG . "' alt='XOOPS Project' /></a>";
