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
 * Module: Xforms
 *
 * @package   XoopsModules\Pedigree
 * @author    XOOPS Module Development Team
 * @copyright Copyright (c) 2001-2019 {@link https://xoops.org XOOPS Project}
 * @license   https://www.gnu.org/licenses/gpl-2.0.html GNU Public License
 */

use Xmf\Request;
use XoopsModules\Pedigree;

//require_once  dirname(dirname(__DIR__)) . '/mainfile.php';
require_once __DIR__ . '/header.php';

$helper->loadLanguage('main');
// Include any common code for this module.
require_once $helper->path('include/common.php');

$GLOBALS['xoopsOption']['template_main'] = 'pedigree_adddog.tpl';

include XOOPS_ROOT_PATH . '/header.php';

$GLOBALS['xoopsTpl']->assign('page_title', _MA_PEDIGREE_ADD_OWNER_PAGETITLE);

//check for access
if (empty($GLOBALS['xoopsUser']) || !($GLOBALS['xoopsUser'] instanceof \XoopsUser)) {
    redirect_header('javascript:history.go(-1)', 3, _NOPERM . '<br>' . _MA_PEDIGREE_REGIST);
}

$f = Request::getString('f', '', 'GET');
if ('check' === $f) {
    //check for access
    /*
    $achternaam  = Request::getString('achternaam', '', 'POST');
    $voornaam    = Request::getString('voornaam', '', 'POST');
    $email       = Request::getEmail('email', '', 'POST');
    $website     = Request::getUrl('website', '', 'POST');
    $user        = Request::getString('user', '', 'POST');

    //insert into owner
    //$query = 'INSERT INTO ' . $GLOBALS['xoopsDB']->prefix('pedigree_owner') . " VALUES ('','" . $voornaam . "','" . $achternaam . "','','','','','','" . $email . "','" . $website . "','" . $user . "')";
    $query = 'INSERT INTO '
             . $GLOBALS['xoopsDB']->prefix('pedigree_owner')
             . " VALUES (0,'"
             . $GLOBALS['xoopsDB']->escape($voornaam)
             . "','"
             . $GLOBALS['xoopsDB']->escape($achternaam)
             . "','','','','','','"
             . $GLOBALS['xoopsDB']->escape($email)
             . "','"
             . $GLOBALS['xoopsDB']->escape($website)
             . "','"
             . $GLOBALS['xoopsDB']->escape($user)
             . "')";

    $GLOBALS['xoopsDB']->query($query);
    */
    $objVars = array(
        'firstname' => Request::getString('voornaam', '', 'POST'),
         'lastname' => Request::getString('achternaam', '', 'POST'),
       'emailadres' => Request::getEmail('email', '', 'POST'),
          'website' => Request::getUrl('website', '', 'POST'),
             'user' => Request::getString('user', '', 'POST')
        );

    $ownerHandler = $helper->getHandler('Owner');
    $oObj = $ownerHandler->create();
    $oObj->setVars($objVars);
    $ownerHandler->insert($oObj);

    redirect_header($helper->url(), 3, _MA_PEDIGREE_ADDED_TO_DB);
}

//create form
include XOOPS_ROOT_PATH . '/class/xoopsformloader.php';
$form = new \XoopsThemeForm(_MA_PEDIGREE_ADD_OWNER, 'breedername', 'add_breeder.php?f=check', 'post', true);
$form->addElement(new \XoopsFormHiddenToken($name = 'XOOPS_TOKEN_REQUEST', $timeout = 360));
$form->addElement(new \XoopsFormHidden('user', $GLOBALS['xoopsUser']->getVar('uid')));
//lastname
$form->addElement(new \XoopsFormText('<b>' . _MA_PEDIGREE_FLD_OWN_LNAME . '</b>', 'achternaam', $size = 50, $maxsize = 255, $value = ''));

//firstname
$form->addElement(new \XoopsFormText('<b>' . _MA_PEDIGREE_FLD_OWN_FNAME . '</b>', 'voornaam', $size = 50, $maxsize = 255, $value = ''));

//email
$form->addElement(new \XoopsFormText('<b>' . _MA_PEDIGREE_FLD_OWN_EMAIL . '</b>', 'email', $size = 50, $maxsize = 255, $value = ''));

//website
$form->addElement(new \XoopsFormText('<b>' . _MA_PEDIGREE_FLD_OWN_WEB . '</b>', 'website', $size = 50, $maxsize = 255, $value = ''));
$form->addElement(new \XoopsFormLabel(_MA_PEDIGREE_EXPLAIN, _MA_PEDIGREE_FLD_OWN_WEB_EX));

//submit button
$form->addElement(new \XoopsFormButton('', 'button_id', _MA_PEDIGREE_ADD_OWNER, 'submit'));

//add data (form) to smarty template
$GLOBALS['xoopsTpl']->assign('form', $form->render());

//footer
include XOOPS_ROOT_PATH . '/footer.php';
