<?php
/*
 * You may not change or alter any portion of this comment or credits
 * of supporting developers from this source code or any supporting source code
 * which is considered copyrighted (c) material of the original comment or credit authors.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 */

/**
 * @copyright    The XOOPS Project http://sourceforge.net/projects/xoops/
 * @license      GNU GPL 2 or later (http://www.gnu.org/licenses/gpl-2.0.html)
 * @package
 * @since
 * @author       XOOPS Development Team
 * @version      $Id $
 */
/*
$path = dirname(dirname(dirname(__DIR__)));
include_once $path . '/mainfile.php';
include_once $path . '/include/cp_functions.php';
require_once $path . '/include/cp_header.php';

global $xoopsModule;

$thisModuleDir = $GLOBALS['xoopsModule']->getVar('dirname');

//if functions.php file exist
//require_once dirname(__DIR__) . '/include/functions.php';

// Load language files
xoops_loadLanguage('admin', $thisModuleDir);
xoops_loadLanguage('modinfo', $thisModuleDir);
xoops_loadLanguage('main', $thisModuleDir);

$pathIcon16 = '../'.$xoopsModule->getInfo('icons16');
$pathIcon32 = '../'.$xoopsModule->getInfo('icons32');
$pathModuleAdmin = $xoopsModule->getInfo('dirmoduleadmin');

include_once $GLOBALS['xoops']->path($pathModuleAdmin.'/moduleadmin.php');

*/

include_once dirname(dirname(dirname(__DIR__))) . '/mainfile.php';
include_once XOOPS_ROOT_PATH . '/include/cp_header.php';
include_once dirname(__DIR__) . '/include/config.php';
include_once dirname(__DIR__) . '/include/functions.php';
include_once dirname(__DIR__) . '/include/common.php';

$thisDirname = $GLOBALS['xoopsModule']->getVar('dirname');

$pathIcon16      = '../' . $xoopsModule->getInfo('icons16');
$pathIcon32      = '../' . $xoopsModule->getInfo('icons32');
$pathModuleAdmin = $GLOBALS['xoopsModule']->getInfo('dirmoduleadmin');
//load handlers
$pedigreeTrashHandler  =& xoops_getModuleHandler('trash', $thisDirname);
$pedigreeOwnerHandler  =& xoops_getModuleHandler('owner', $thisDirname);
$pedigreeTempHandler   =& xoops_getModuleHandler('temp', $thisDirname);
$pedigreeTreeHandler   =& xoops_getModuleHandler('tree', $thisDirname);
$pedigreeFieldsHandler =& xoops_getModuleHandler('fields', $thisDirname);

$myts =& MyTextSanitizer::getInstance();
if (!isset($xoopsTpl) || !is_object($xoopsTpl)) {
    include_once(XOOPS_ROOT_PATH . "/class/template.php");
    $xoopsTpl = new XoopsTpl();
}

$xoopsTpl->assign('pathIcon16', $pathIcon16);
$xoopsTpl->assign('pathIcon32', $pathIcon32);
//Load languages
xoops_loadLanguage('admin', $thisDirname);
xoops_loadLanguage('modinfo', $thisDirname);
xoops_loadLanguage('main', $thisDirname);
// Locad admin menu class
include_once $GLOBALS['xoops']->path($pathModuleAdmin . '/moduleadmin.php');

xoops_cp_header();
$adminMenu = new ModuleAdmin();
