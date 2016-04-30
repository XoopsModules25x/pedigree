<?php
// -------------------------------------------------------------------------

require_once dirname(dirname(__DIR__)) . '/mainfile.php';
$moduleDirName = basename(__DIR__);
xoops_loadLanguage('main', $moduleDirName);
// Include any common code for this module.
require_once(XOOPS_ROOT_PATH . '/modules/' . $moduleDirName . '/include/common.php');

//check for access
if (!$xoopsUser instanceof XoopsUser) {
    redirect_header('javascript:history.go(-1)', 3, _NOPERM . '<br />' . _MA_PEDIGREE_REGIST);
}

$xoopsOption['template_main'] = 'pedigree_delete.tpl';
include XOOPS_ROOT_PATH . '/header.php';

$ownid     = XoopsRequest::getInt('dogid', 0, 'post');
$ownername = XoopsRequest::getString('curname', '', 'post');

if (!empty($ownername)) {
    $queryString = 'SELECT * FROM ' . $GLOBALS['xoopsDB']->prefix('pedigree_owner') . ' WHERE Id=' . $ownid;
    $result      = $GLOBALS['xoopsDB']->query($queryString);
    while (false !== ($row = $GLOBALS['xoopsDB']->fetchArray($result))) {
        //check for edit rights
        if (($xoopsUser instanceof XoopsUser) && (($xoopsUser->isAdmin($pedigree->getModule()->mid())) || ($row['user'] == $xoopsUser->getVar('uid')))) {
            $delsql = 'DELETE FROM ' . $GLOBALS['xoopsDB']->prefix('pedigree_owner') . ' WHERE Id =' . $row['Id'];
            $GLOBALS['xoopsDB']->query($delsql);
            $sql = 'UPDATE ' . $GLOBALS['xoopsDB']->prefix('pedigree_tree') . " SET id_owner = '0' where id_owner = " . $row['Id'];
            $GLOBALS['xoopsDB']->query($sql);
            $sql = 'UPDATE ' . $GLOBALS['xoopsDB']->prefix('pedigree_tree') . " SET id_breeder = '0' where id_breeder = " . $row['Id'];
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
include XOOPS_ROOT_PATH . '/footer.php';
