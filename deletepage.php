<?php
// -------------------------------------------------------------------------

require_once dirname(dirname(__DIR__)) . '/mainfile.php';
$moduleDirName = basename(__DIR__);
xoops_loadLanguage('main', $moduleDirName);
// Include any common code for this module.
require_once XOOPS_ROOT_PATH . '/modules/' . $moduleDirName . '/include/common.php';
require_once XOOPS_ROOT_PATH . '/modules/' . $pedigree->getModule()->dirname() . '/class/field.php';

$xoopsOption['template_main'] = 'pedigree_delete.tpl';
include XOOPS_ROOT_PATH . '/header.php';

//check for access
if (!$xoopsUser instanceof XoopsUser) {
    redirect_header('javascript:history.go(-1)', 3, _NOPERM . '<br />' . _MA_PEDIGREE_REGIST);
}

$dogid   = XoopsRequest::getInt('dogid', 0, 'post');
$dogname = XoopsRequest::getString('curname', '', 'post');

if (!empty($dogname)) {
    $queryString = 'SELECT * FROM ' . $GLOBALS['xoopsDB']->prefix('pedigree_tree') . ' WHERE Id=' . $dogid;
    $result      = $GLOBALS['xoopsDB']->query($queryString);
    while (false !== ($row = $GLOBALS['xoopsDB']->fetchArray($result))) {
        //check for edit rights
        if (($xoopsUser instanceof XoopsUser) && (($xoopsUser->isAdmin($pedigree->getModule()->mid())) || ($row['user'] == $xoopsUser->getVar('uid')))) {
            $sql = 'INSERT INTO ' . $GLOBALS['xoopsDB']->prefix('pedigree_trash') . ' SELECT * FROM ' . $GLOBALS['xoopsDB']->prefix('pedigree_tree') . ' WHERE ' . $GLOBALS['xoopsDB']->prefix('pedigree_tree') . ".Id='" . $dogid . "'";
            $GLOBALS['xoopsDB']->query($sql);
            $delsql = 'DELETE FROM ' . $GLOBALS['xoopsDB']->prefix('pedigree_tree') . " WHERE Id ='" . $row['Id'] . "'";
            $GLOBALS['xoopsDB']->query($delsql);
            if ($row['roft'] == '0') {
                $sql = 'UPDATE ' . $GLOBALS['xoopsDB']->prefix('pedigree_tree') . " SET father = '0' where father = '" . $row['Id'] . "'";
            } else {
                $sql = 'UPDATE ' . $GLOBALS['xoopsDB']->prefix('pedigree_tree') . " SET mother = '0' where mother = '" . $row['Id'] . "'";
            }
            $GLOBALS['xoopsDB']->query($sql);
            $ch = 1;
        }
    }
}

if ($ch) {
    redirect_header('index.php', 2, _MD_DATACHANGED);
} else {
    redirect_header('dog.php?Id=' . $dogid, 1, 'ERROR!!');
}
//footer
include XOOPS_ROOT_PATH . '/footer.php';
