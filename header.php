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
 * pedigree module
 *
 * @copyright       {@link http://xoops.org/  XOOPS Project}
 * @license         {@link http://www.gnu.org/licenses/gpl-2.0.html GNU GPL 2 or later}
 * @package         pedigree
 * @author          Xoops Module Dev Team
 */
require_once dirname(dirname(__DIR__)) . '/mainfile.php';
require_once __DIR__ . '/include/common.php';

$moduleDirName = basename(__DIR__);
xoops_loadLanguage('main', $moduleDirName);

require_once $GLOBALS['xoops']->path("modules/{$moduleDirName}/class/animal.php");
xoops_load('XoopsRequest');

if (!isset($GLOBALS['xoopsTpl']) || !($GLOBALS['xoopsTpl'] instanceof XoopsTpl)) {
    include_once $GLOBALS['xoops']->path('class/template.php');
    $GLOBALS['xoopsTpl'] = new XoopsTpl();
}
//$xoops = Xoops::getInstance();
//$xoopsTpl = $xoops->tpl();

$GLOBALS['xoopsTpl']->assign('mod_url', PEDIGREE_URL); //<{$mod_url}>

// uncomment the below line only if you are using Protector 3.x module
// and you trust your users when uploading files, it is recommended to not allow anonymous uploads if you do so!!
//define('PROTECTOR_SKIP_FILESCHECKER', true);
