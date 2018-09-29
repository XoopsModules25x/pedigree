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
 * @copyright       {@link https://xoops.org/ XOOPS Project}
 * @license         {@link http://www.gnu.org/licenses/gpl-2.0.html GNU GPL 2 or later}
 * @package         pedigree
 * @since
 * @author          XOOPS Module Dev Team
 */
/*
$path = dirname(dirname(dirname(__DIR__)));
require_once $path . '/mainfile.php';
require_once $path . '/include/cp_functions.php';
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

require_once $GLOBALS['xoops']->path($pathModuleAdmin.'/moduleadmin.php');

*/

use XoopsModules\Pedigree;

require_once  dirname(dirname(dirname(__DIR__))) . '/include/cp_header.php';
require_once $GLOBALS['xoops']->path('www/class/xoopsformloader.php');

require_once  dirname(__DIR__) . '/include/common.php';
//require_once  dirname(__DIR__) . '/include/config.php';

$moduleDirName = basename(dirname(__DIR__));
/** @var Pedigree\Helper $helper */
$helper = Pedigree\Helper::getInstance();

/** @var Xmf\Module\Admin $adminObject */
$adminObject = \Xmf\Module\Admin::getInstance();

/** @var \XoopsPersistableObjectHandler $registryHandler */
$registryHandler  = new Pedigree\RegistryHandler($db);

// Load language files
$helper->loadLanguage('admin');
$helper->loadLanguage('modinfo');
$helper->loadLanguage('common');

//xoops_cp_header();
