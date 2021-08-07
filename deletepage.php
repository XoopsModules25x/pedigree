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
 * @package   \XoopsModules\Pedigree
 * @copyright Copyright (c) 2001-2019 {@link https://xoops.org XOOPS Project}
 * @license   https://www.gnu.org/licenses/gpl-2.0.html GNU Public License
 * @author    XOOPS Module Development Team
 */

use Xmf\Request;
use XoopsModules\Pedigree;
use XoopsModules\Pedigree\Constants;

require_once __DIR__ . '/header.php';
/** @var XoopsModules\Pedigree\Helper $helper */
$helper->loadLanguage('main');

// Include any common code for this module.
require_once $helper->path('include/common.php');

$GLOBALS['xoopsOption']['template_main'] = 'pedigree_delete.tpl';
require XOOPS_ROOT_PATH . '/header.php';

//check XOOPS security token
if (!$GLOBALS['xoopsSecurity']->check()) {
    $helper->redirect('', Constants::REDIRECT_DELAY_MEDIUM, implode('<br>', $GLOBALS['xoopsSecurity']->getErrors()));
}

//check for authorized user access
if (empty($GLOBALS['xoopsUser']) || !($GLOBALS['xoopsUser'] instanceof \XoopsUser)) {
    redirect_header('javascript:history.go(-1)', 3, _NOPERM . '<br>' . _MA_PEDIGREE_REGIST);
}

$dogid   = Request::getInt('dogid', 0, 'post');
$dogname = Request::getString('curname', '', 'post');

if (!empty($dogname)) {
    $treeHandler = $helper->getHandler('Tree');
    $treeObj     = $treeHandler->get($dogid);
    if ($treeObj instanceof Pedigree\Tree && !$treeObj->isNew()) {
        $treeValues   = $treeObj->getValues();
        $trashHandler = $helper->getHandler('Trash');
        $trashObj     = $trashHandler->create();
        $trashObj->setVars($treeValues);
        $trashHandler->insert($trashObj);
        $treeHandler->delete($treeObj);
        if (Constants::MALE == $treeValues['roft']) {
            $criteria = new \Criteria('father', $dogid);
            $treeHandler->updateAll('father', 0, $criteria);
        } else {
            $criteria = new \Criteria('mother', $dogid);
            $treeHandler->updateAll('mother', 0, $criteria);
        }
        $helper->redirect('index.php', Constants::REDIRECT_DELAY_MEDIUM, _MD_DATACHANGED);
    } else {
        //@todo display more descriptive error message
        $helper->redirect("dog.php?id={$dogid}", Constants::REDIRECT_DELAY_SHORT, 'ERROR!!');
    }
    /*
    $sql = 'SELECT * FROM ' . $GLOBALS['xoopsDB']->prefix('pedigree_registry') . ' WHERE id=' . $dogid;
    $result = $GLOBALS['xoopsDB']->query($sql);
    while (false !== ($row = $GLOBALS['xoopsDB']->fetchArray($result))) {
        //check for edit rights
        $access = 0;
        $xoopsModule = XoopsModule::getByDirname($moduleDirName);
        if ($helper->isUserAdmin() || $row['user'] == $GLOBALS['xoopsUser']->getVar('uid')) {
            $sql = 'INSERT INTO ' . $GLOBALS['xoopsDB']->prefix('pedigree_trash') . ' SELECT * FROM ' . $GLOBALS['xoopsDB']->prefix('pedigree_registry') . ' WHERE ' . $GLOBALS['xoopsDB']->prefix('pedigree_registry') . ".id='" . $dogid . "'";
            $GLOBALS['xoopsDB']->query($sql);
            $delsql = 'DELETE FROM ' . $GLOBALS['xoopsDB']->prefix('pedigree_registry') . " WHERE id ='" . $row['id'] . "'";
            $GLOBALS['xoopsDB']->query($delsql);
            if ('0' == $row['roft']) {
                $sql = 'UPDATE ' . $GLOBALS['xoopsDB']->prefix('pedigree_registry') . " SET father = '0' where father = '" . $row['id'] . "'";
            } else {
                $sql = 'UPDATE ' . $GLOBALS['xoopsDB']->prefix('pedigree_registry') . " SET mother = '0' where mother = '" . $row['id'] . "'";
            }
            $GLOBALS['xoopsDB']->query($sql);
            $changed = true;
        }
    }
    */
} else {
    //@todo dog name was empty so send user back to try again
}
//footer
require XOOPS_ROOT_PATH . '/footer.php';
