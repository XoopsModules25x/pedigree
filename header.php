<?php
/*
 You may not change or alter any portion of this comment or credits of
 supporting developers from this source code or any supporting source code
 which is considered copyrighted (c) material of the original comment or credit
 authors.

 This program is distributed in the hope that it will be useful, but
 WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 */

/**
 * Module: Pedigree
 *
 * @package         \XoopsModules\Pedigree
 * @copyright       Copyright (c) 2001-2019 {@link https://xoops.org XOOPS Project}
 * @license         https://www.gnu.org/licenses/gpl-2.0.html GNU Public License
 * @author          XOOPS Module Development Team
 */

use Xmf\Module\Admin;
use XoopsModules\Pedigree\{
    Helper
};
/** @var Helper $helper */

require_once \dirname(__DIR__, 2) . '/mainfile.php';
require_once __DIR__ . '/include/common.php';

$helper        = Helper::getInstance();
$moduleDirName = $helper->getDirname();
$helper->loadLanguage('main');

$pathIcon16 = Admin::iconUrl('', 16);

if (!isset($GLOBALS['xoopsTpl']) || !($GLOBALS['xoopsTpl'] instanceof \XoopsTpl)) {
    require_once $GLOBALS['xoops']->path('class/template.php');
    $GLOBALS['xoopsTpl'] = new \XoopsTpl();
}
//$xoops = Xoops::getInstance();
//$xoopsTpl = $xoops->tpl();

$GLOBALS['xoopsTpl']->assign('mod_url', $helper->url()); //template <{$mod_url}>

// uncomment the below line only if you are using Protector 3.x module
// and you trust your users when uploading files, it is recommended to not allow anonymous uploads if you do so!!
//define('PROTECTOR_SKIP_FILESCHECKER', true);
