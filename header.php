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
 * Wfdownloads module
 *
 * @copyright       The XOOPS Project http://sourceforge.net/projects/xoops/
 * @license         GNU GPL 2 or later (http://www.gnu.org/licenses/gpl-2.0.html)
 * @package         wfdownload
 * @since           3.23
 * @author          Xoops Development Team
 * @version         svn:$id$
 */
include_once dirname(dirname(__DIR__)) . '/mainfile.php';
include_once __DIR__ . '/include/common.php';

xoops_loadLanguage('main', basename(dirname(__DIR__)));

if (!isset($xoopsTpl) || !is_object($xoopsTpl)) {
    include_once(XOOPS_ROOT_PATH . "/class/template.php");
    $xoopsTpl = new XoopsTpl();
}
//$xoops = Xoops::getInstance();
//$xoopsTpl = $xoops->tpl();


$xoopsTpl->assign('mod_url', PEDIGREE_URL); //<{$mod_url}>


// uncomment the below line only if you are using Protector 3.x module
// and you trust your users when uploading files, it is recommended to not allow anonymous uploads if you do so!!
//define('PROTECTOR_SKIP_FILESCHECKER', true);
