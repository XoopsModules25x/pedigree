<?php
// -------------------------------------------------------------------------

use Xmf\Request;

//require_once __DIR__ . '/../../mainfile.php';
require_once __DIR__ . '/header.php';
$moduleDirName = basename(__DIR__);
xoops_loadLanguage('main', $moduleDirName);
// Include any common code for this module.
require_once XOOPS_ROOT_PATH . '/modules/' . $moduleDirName . '/include/common.php';
require_once XOOPS_ROOT_PATH . '/modules/' . $xoopsModule->dirname() . '/include/class_field.php';

$GLOBALS['xoopsOption']['template_main'] = 'pedigree_delete.tpl';

include XOOPS_ROOT_PATH . '/header.php';

//check for access
$xoopsModule = XoopsModule::getByDirname($moduleDirName);
if (empty($GLOBALS['xoopsUser']) || !($GLOBALS['xoopsUser'] instanceof XoopsUser)) {
    redirect_header('javascript:history.go(-1)', 3, _NOPERM . '<br>' . _MA_PEDIGREE_REGIST);
}

global $xoopsTpl, $xoopsDB, $xoopsUser;

$dogid   = Request::getInt('dogid', 0, 'post');
$dogname = Request::getString('curname', '', 'post');

if (!empty($dogname)) {
    $queryString = 'SELECT * FROM ' . $GLOBALS['xoopsDB']->prefix('pedigree_tree') . ' WHERE id=' . $dogid;
    $result      = $GLOBALS['xoopsDB']->query($queryString);
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
            $sql = 'INSERT INTO ' . $GLOBALS['xoopsDB']->prefix('pedigree_trash') . ' SELECT * FROM ' . $GLOBALS['xoopsDB']->prefix('pedigree_tree') . ' WHERE ' . $GLOBALS['xoopsDB']->prefix('pedigree_tree') . ".id='" . $dogid . "'";
            $GLOBALS['xoopsDB']->query($sql);
            $delsql = 'DELETE FROM ' . $GLOBALS['xoopsDB']->prefix('pedigree_tree') . " WHERE id ='" . $row['id'] . "'";
            $GLOBALS['xoopsDB']->query($delsql);
            if ('0' == $row['roft']) {
                $sql = 'UPDATE ' . $GLOBALS['xoopsDB']->prefix('pedigree_tree') . " SET father = '0' where father = '" . $row['id'] . "'";
            } else {
                $sql = 'UPDATE ' . $GLOBALS['xoopsDB']->prefix('pedigree_tree') . " SET mother = '0' where mother = '" . $row['id'] . "'";
            }
            $GLOBALS['xoopsDB']->query($sql);
            $ch = 1;
        }
    }
}

if ($ch) {
    redirect_header('index.php', 2, _MD_DATACHANGED);
} else {
    redirect_header('dog.php?id=' . $dogid, 1, 'ERROR!!');
}
//footer
include XOOPS_ROOT_PATH . '/footer.php';
