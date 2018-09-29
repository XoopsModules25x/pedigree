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
 * pedigree module for XOOPS
 *
 * @copyright   {@link http://sourceforge.net/projects/xoops/ The XOOPS Project}
 * @license     {@link http://www.fsf.org/copyleft/gpl.html GNU public license}
 * @package     pedigree
 * @author      XOOPS Module Dev Team
 */

use XoopsModules\Pedigree;

//require_once  dirname(dirname(__DIR__)) . '/mainfile.php';
//require_once __DIR__ . '/header.php';

//$moduleDirName = basename(__DIR__);
xoops_loadLanguage('main', $moduleDirName);

// Include any common code for this module.
require_once XOOPS_ROOT_PATH . '/modules/' . $moduleDirName . '/include/common.php';

//$GLOBALS['xoopsOption']['template_main'] = 'pedigree_welcome.tpl';
//require_once $GLOBALS['xoops']->path('/header.php');

$myts = \MyTextSanitizer::getInstance(); // MyTextSanitizer object

//query to count dogs
$treeHandler = Pedigree\Helper::getInstance()->getHandler('Tree');
$numdogs     = $treeHandler->getCount();
/*
$result = $GLOBALS['xoopsDB']->query("select count(*) from " . $GLOBALS['xoopsDB']->prefix("pedigree_registry"));
list($numdogs) = $GLOBALS['xoopsDB']->fetchRow($result);
*/
/*
//get module configuration
$moduleHandler = xoops_getHandler('module');
$module        = $moduleHandler->getByDirname($moduleDirName);
$configHandler = xoops_getHandler('config');
$moduleConfig  = $configHandler->getConfigsByCat(0, $module->getVar('mid'));
*/
$word = $myts->displayTarea(strtr($helper->getConfig('welcome'), [
    '[numanimals]'  => '[b]' . $numdogs . ' [/b]',
    '[animalType]'  => '[b]' . $helper->getConfig('animalType') . '[/b]',
    '[animalTypes]' => $helper->getConfig('animalTypes'),
]));

$GLOBALS['xoopsTpl']->assign('welcome', _MA_PEDIGREE_WELCOME);
$GLOBALS['xoopsTpl']->assign('word', $word);
//comments and footer

//require_once $GLOBALS['xoops']->path('/footer.php');
