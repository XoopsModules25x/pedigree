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
 * @package   XoopsModules\Pedigree
 * @author    XOOPS Module Development Team
 * @copyright Copyright (c) 2001-2019 {@link https://xoops.org XOOPS Project}
 * @license   https://www.gnu.org/licenses/gpl-2.0.html GNU Public License
 */

use Xmf\Request;

//require_once  \dirname(__DIR__, 2) . '/mainfile.php';
require_once __DIR__ . '/header.php';
$moduleDirName = basename(__DIR__);
xoops_loadLanguage('main', $moduleDirName);
// Include any common code for this module.
require_once XOOPS_ROOT_PATH . '/modules/' . $moduleDirName . '/include/common.php';

$GLOBALS['xoopsOption']['template_main'] = 'pedigree_delete.tpl';

require XOOPS_ROOT_PATH . '/header.php';

//check for access
$xoopsModule = XoopsModule::getByDirname($moduleDirName);
if (empty($GLOBALS['xoopsUser']) || !($GLOBALS['xoopsUser'] instanceof \XoopsUser)) {
    redirect_header('javascript:history.go(-1)', 3, _NOPERM . '<br>' . _MA_PEDIGREE_REGIST);
}

global $xoopsTpl, $xoopsDB, $xoopsUser;

$ownid     = Request::getInt('dogid', 0, 'post');
$ownername = Request::getString('curname', '', 'post');

if (!empty($ownername)) {
    $sql = 'SELECT * FROM ' . $GLOBALS['xoopsDB']->prefix('pedigree_owner') . ' WHERE id=' . $ownid;
    $result      = $GLOBALS['xoopsDB']->query($sql);
    while (false !== ($row = $GLOBALS['xoopsDB']->fetchArray($result))) {
        //check for edit rights
        $access      = 0;
        $xoopsModule = XoopsModule::getByDirname($moduleDirName);
        if (!empty($xoopsUser)) {
            if ($xoopsUser->isAdmin($xoopsModule->mid())) {
                $access = 1;
            }
            if ($row['user'] == $xoopsUser->getVar('uid')) {
                $access = 1;
            }
        }
        if ('1' == $access) {
            $delsql = 'DELETE FROM ' . $GLOBALS['xoopsDB']->prefix('pedigree_owner') . ' WHERE id =' . $row['id'];
            $GLOBALS['xoopsDB']->query($delsql);
            $sql = 'UPDATE ' . $GLOBALS['xoopsDB']->prefix('pedigree_registry') . " SET id_owner = '0' where id_owner = " . $row['id'];
            $GLOBALS['xoopsDB']->query($sql);
            $sql = 'UPDATE ' . $GLOBALS['xoopsDB']->prefix('pedigree_registry') . " SET id_breeder = '0' where id_breeder = " . $row['id'];
            $GLOBALS['xoopsDB']->query($sql);
            $ch = 1;
        }
    }
}

if ($ch) {
    redirect_header('index.php', 1, _MD_DATACHANGED);
} else {
    redirect_header('owner.php?ownid=' . $ownid, 1, 'ERROR!!');
}
//footer
require XOOPS_ROOT_PATH . '/footer.php';
