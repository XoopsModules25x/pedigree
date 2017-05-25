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
 * @copyright       XOOPS Project (http://xoops.org)
 * @license         GNU GPL 2 or later (http://www.gnu.org/licenses/gpl-2.0.html)
 * @package         pedigree
 * @since           3.23
 * @author          Xoops Module Dev Team
 */
defined('XOOPS_ROOT_PATH') || exit('Restricted Access');
include_once __DIR__ . '/config.php';

// This must contain the name of the folder in which reside Pedigree
//define('PEDIGREE_DIRNAME', basename(dirname(__DIR__)));
//define('PEDIGREE_URL', XOOPS_URL . '/modules/' . PEDIGREE_DIRNAME);
//define('PEDIGREE_IMAGES_URL', PEDIGREE_URL . '/images');
//define('PEDIGREE_ADMIN_URL', PEDIGREE_URL . '/admin');
//define('PEDIGREE_ROOT_PATH', XOOPS_ROOT_PATH . '/modules/' . PEDIGREE_DIRNAME);
//define('PEDIGREE_AUTHOR_LOGOIMG', PEDIGREE_URL . '/assets/images/xoopsproject_logo.png');

//xoops_loadLanguage('common', PEDIGREE_DIRNAME);
xoops_loadLanguage('global');

include_once XOOPS_ROOT_PATH . '/class/xoopsformloader.php';
include_once XOOPS_ROOT_PATH . '/class/tree.php';
include_once XOOPS_ROOT_PATH . '/class/pagenav.php';

//include_once PEDIGREE_ROOT_PATH . '/include/functions.php';
//include_once PEDIGREE_ROOT_PATH . '/include/constants.php';
//include_once PEDIGREE_ROOT_PATH . '/class/session.php'; // PedigreeSession class
include_once PEDIGREE_ROOT_PATH . '/class/pedigree.php'; // PedigreePedigree class
include_once PEDIGREE_ROOT_PATH . '/class/breadcrumb.php'; // PedigreeBreadcrumb class
include_once PEDIGREE_ROOT_PATH . '/class/tree.php'; // PedigreeTree class
//include_once PEDIGREE_ROOT_PATH . '/class/xoopstree.php'; // PedigreeXoopsTree class
//include_once PEDIGREE_ROOT_PATH . '/class/formelementchoose.php'; // PedigreeFormElementChoose class
include_once PEDIGREE_ROOT_PATH . '/class/utilities.php'; // PedigreeUtilities class
include_once PEDIGREE_ROOT_PATH . '/class/animal.php'; // PedigreeAnimal class

xoops_load('XoopsUserUtility');
// MyTextSanitizer object
$myts = MyTextSanitizer::getInstance();

$debug    = false;
$pedigree = PedigreePedigree::getInstance($debug); //get module helper class

//This is needed or it will not work in blocks.
global $pedigree_isAdmin;

// Load only if module is installed
if (is_object($pedigree->getModule())) {
    // Find if the user is admin of the module
    $pedigree_isAdmin = PedigreeUtilities::userIsAdmin();
}

// Load Xoops handlers
$moduleHandler       = xoops_getHandler('module');
$memberHandler       = xoops_getHandler('member');
$notificationHandler = xoops_getHandler('notification');
$gpermHandler        = xoops_getHandler('groupperm');
