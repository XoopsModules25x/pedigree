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
 * Pedigree module
 *
 * @copyright       The XOOPS Project http://sourceforge.net/projects/xoops/
 * @license         GNU GPL 2 or later (http://www.gnu.org/licenses/gpl-2.0.html)
 * @package         wfdownload
 * @since           3.23
 * @author          Xoops Development Team
 * @version         svn:$id$
 */
defined("XOOPS_ROOT_PATH") || die("XOOPS root path not defined");

// This must contain the name of the folder in which reside Pedigree
define("PEDIGREE_DIRNAME", basename(dirname(__DIR__)));
define("PEDIGREE_URL", XOOPS_URL . '/modules/' . PEDIGREE_DIRNAME);
define("PEDIGREE_IMAGES_URL", PEDIGREE_URL . '/images');
define("PEDIGREE_ADMIN_URL", PEDIGREE_URL . '/admin');
define("PEDIGREE_ROOT_PATH", XOOPS_ROOT_PATH . '/modules/' . PEDIGREE_DIRNAME);

xoops_loadLanguage('common', PEDIGREE_DIRNAME);
if (!@include_once XOOPS_ROOT_PATH . "/language/" . $xoopsConfig['language'] . "/global.php") {
    include_once XOOPS_ROOT_PATH . "/language/english/global.php";
}

include_once XOOPS_ROOT_PATH . '/class/xoopsformloader.php';
include_once XOOPS_ROOT_PATH . '/class/tree.php';
include_once XOOPS_ROOT_PATH . '/class/pagenav.php';

include_once PEDIGREE_ROOT_PATH . '/include/functions.php';
//include_once PEDIGREE_ROOT_PATH . '/include/constants.php';
//include_once PEDIGREE_ROOT_PATH . '/class/session.php'; // PedigreeSession class
include_once PEDIGREE_ROOT_PATH . '/class/pedigree.php'; // PedigreePedigree class
//include_once PEDIGREE_ROOT_PATH . '/class/request.php'; // PedigreeRequest class
include_once PEDIGREE_ROOT_PATH . '/class/breadcrumb.php'; // PedigreeBreadcrumb class
include_once PEDIGREE_ROOT_PATH . '/class/tree.php'; // PedigreeObjectTree class
//include_once PEDIGREE_ROOT_PATH . '/class/xoopstree.php'; // PedigreeXoopsTree class
//include_once PEDIGREE_ROOT_PATH . '/class/formelementchoose.php'; // PedigreeFormElementChoose class

xoops_load('XoopsUserUtility');
// MyTextSanitizer object
$myts = MyTextSanitizer::getInstance();

$debug    = false;
$pedigree = PedigreePedigree::getInstance($debug);

//This is needed or it will not work in blocks.
global $pedigree_isAdmin;

// Load only if module is installed
if (is_object($pedigree->getModule())) {
    // Find if the user is admin of the module
    $pedigree_isAdmin = pedigree_userIsAdmin();
}

// Load Xoops handlers
$module_handler       = xoops_gethandler('module');
$member_handler       = xoops_gethandler('member');
$notification_handler = &xoops_gethandler('notification');
$gperm_handler        = xoops_gethandler('groupperm');
