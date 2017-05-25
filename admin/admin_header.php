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
 * @copyright       {@link http://xoops.org/ XOOPS Project}
 * @license         {@link http://www.gnu.org/licenses/gpl-2.0.html GNU GPL 2 or later}
 * @package         pedigree
 * @since
 * @author          XOOPS Module Dev Team
 */
/*
$path = dirname(dirname(dirname(__DIR__)));
include_once $path . '/mainfile.php';
include_once $path . '/include/cp_functions.php';
require_once $path . '/include/cp_header.php';

global $xoopsModule;

$moduleDirName = $GLOBALS['xoopsModule']->getVar('dirname');

//if functions.php file exist
//require_once dirname(__DIR__) . '/include/functions.php';

// Load language files
xoops_loadLanguage('admin', $moduleDirName);
xoops_loadLanguage('modinfo', $moduleDirName);
xoops_loadLanguage('main', $moduleDirName);

$pathIcon16 = '../'.$xoopsModule->getInfo('icons16');
$pathIcon32 = '../'.$xoopsModule->getInfo('icons32');
$pathModuleAdmin = $xoopsModule->getInfo('dirmoduleadmin');

include_once $GLOBALS['xoops']->path($pathModuleAdmin.'/moduleadmin.php');

*/

include_once dirname(dirname(dirname(__DIR__))) . '/mainfile.php';
include_once $GLOBALS['xoops']->path('/include/cp_header.php');
include_once dirname(__DIR__) . '/include/common.php';
include_once dirname(__DIR__) . '/include/config.php';
xoops_load('XoopsRequest');

$moduleDirName = $GLOBALS['xoopsModule']->getVar('dirname');

$pathIcon16      = '../' . $GLOBALS['xoopsModule']->getInfo('icons16');
$pathIcon32      = '../' . $GLOBALS['xoopsModule']->getInfo('icons32');
$pathModuleAdmin = $GLOBALS['xoopsModule']->getInfo('dirmoduleadmin');

//load handlers
$pedigreeTrashHandler  = xoops_getModuleHandler('trash', $moduleDirName);
$pedigreeOwnerHandler  = xoops_getModuleHandler('owner', $moduleDirName);
$pedigreeTempHandler   = xoops_getModuleHandler('temp', $moduleDirName);
$pedigreeTreeHandler   = xoops_getModuleHandler('tree', $moduleDirName);
$pedigreeFieldsHandler = xoops_getModuleHandler('fields', $moduleDirName);

$myts = MyTextSanitizer::getInstance();
if (!isset($GLOBALS['xoopsTpl']) || !($GLOBALS['xoopsTpl'] instanceof XoopsTpl)) {
    include_once $GLOBALS['xoops']->path('class/template.php');
    $GLOBALS['xoopsTpl'] = new XoopsTpl();
}

$GLOBALS['xoopsTpl']->assign('pathIcon16', $pathIcon16);
$GLOBALS['xoopsTpl']->assign('pathIcon32', $pathIcon32);
//Load languages
xoops_loadLanguage('admin', $moduleDirName);
xoops_loadLanguage('modinfo', $moduleDirName);
xoops_loadLanguage('main', $moduleDirName);
// Locad admin menu class
include_once $GLOBALS['xoops']->path($pathModuleAdmin . '/moduleadmin.php');

//xoops_cp_header();
//$adminMenu = new ModuleAdmin();
