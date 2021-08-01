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
 * @package         XoopsModules\Pedigree
 * @copyright       {@link https://xoops.org/ XOOPS Project}
 * @license         {@link http://www.gnu.org/licenses/gpl-2.0.html GNU GPL 2 or later}
 * @author          XOOPS Module Dev Team
 */

use Xmf\Module\Admin;
use XoopsModules\Pedigree\{
    Helper
};

require_once dirname(dirname(dirname(__DIR__))) . '/include/cp_header.php';
require_once $GLOBALS['xoops']->path('www/class/xoopsformloader.php');

require_once dirname(__DIR__) . '/include/common.php';
//require_once dirname(__DIR__) . '/include/config.php';

$moduleDirName      = basename(dirname(__DIR__));
$moduleDirNameUpper = mb_strtoupper($moduleDirName);

/** @var Helper $helper */
$helper = Helper::getInstance();

/** @var Xmf\Module\Admin $adminObject */
$adminObject   = Admin::getInstance();
$pathIcon16    = Admin::iconUrl('', 16);
$pathIcon32    = Admin::iconUrl('', 32);
$pathModIcon32 = $helper->getModule()->getInfo('modicons32');


// Load language files
$helper->loadLanguage('admin');
$helper->loadLanguage('modinfo');
$helper->loadLanguage('common');

/** @var MyTextSanitizer $myts */
$myts = MyTextSanitizer::getInstance();

if (!isset($GLOBALS['xoopsTpl']) || !($GLOBALS['xoopsTpl'] instanceof \XoopsTpl)) {
    require_once $GLOBALS['xoops']->path('class/template.php');
    $xoopsTpl = new XoopsTpl();
}
