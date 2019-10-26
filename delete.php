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

//check for access
if (empty($GLOBALS['xoopsUser']) || !($GLOBALS['xoopsUser'] instanceof \XoopsUser)) {
    redirect_header('javascript:history.go(-1)', 3, _NOPERM . '<br>' . _MA_PEDIGREE_REGIST);
}

$GLOBALS['xoopsOption']['template_main'] = 'pedigree_delete.tpl';
include XOOPS_ROOT_PATH . '/header.php';

$id = Request::getInt('id', 0, 'GET');
if (empty($id)) {
    //re-route user if we didn't get a valid id
    $helper->redirect('', Constants::REDIRECT_DELAY_MEDIUM, _MA_PEDIGREE_INVALID_ID);
}
//query - find values for this animal
$treeHandler = $helper->getHandler('Tree');
$treeObj = $treeHandler->get($id);

if ($treeObj instanceof Pedigree\Tree) {
    $naam = $treeObj->getVar('naam', 's');
    //$namelink = "<a href=\"" . $helper->url("dog.php?id={$id}") . "\">{$naam}</a>";

    //Create form
    include XOOPS_ROOT_PATH . '/class/xoopsformloader.php';
    $form = new \XoopsThemeForm($naam, 'deletedata', 'deletepage.php', 'post');
    //hidden value current record owner
    $form->addElement(new \XoopsFormHidden('dbuser', $treeObj->getVar('user')));
    //hidden value dog ID
    $form->addElement(new \XoopsFormHidden('dogid', $id));
    $form->addElement(new \XoopsFormHidden('curname', $naam));
    $form->addElement(new \XoopsFormHiddenToken('XOOPS_TOKEN_REQUEST', Constants::TOKEN_TIMEOUT));
    $form->addElement(new \XoopsFormLabel(_MA_PEDIGREE_DELE_SURE, _MA_PEDIGREE_DEL_MSG . $helper->getConfig['animalType'] . " : <span style=\"font-weight: bold;\">{$naam}</span>?"));
    //@todo move pups() function to Tree class method
    $pups = pups($id, (int)$treeObj->getVar('roft'));
    $form->addElement(new \XoopsFormLabel(_MA_PEDIGREE_DELE_WARN, _MA_PEDIGREE_ALL . $helper->getConfig['children'] . _MA_PEDIGREE_ALL_ORPH . $pups));
    $form->addElement(new \XoopsFormButton('', 'button_id', _DELETE, 'submit'));
    //add data (form) to smarty template
    $GLOBALS['xoopsTpl']->assign('form', $form->render());
} else {
    //redirect because this animal wasn't found
    $helper->redirect('', Constants::REDIRECT_DELAY_MEDIUM, _MA_PEDIGREE_INVALID_ID);
}

//footer
include XOOPS_ROOT_PATH . '/footer.php';
