<?php
// -------------------------------------------------------------------------

require_once dirname(dirname(__DIR__)) . '/mainfile.php';

$moduleDirName = basename(__DIR__);
xoops_loadLanguage('main', $moduleDirName);
xoops_load('XoopsRequest');
// Include any common code for this module.
require_once XOOPS_ROOT_PATH . "/modules/{$moduleDirName}/include/common.php";

//check for access
if (!$xoopsUser instanceof XoopsUser) {
    redirect_header('index.php', 3, _NOPERM . '<br />' . _MA_PEDIGREE_REGIST);
}

$xoopsOption['template_main'] = 'pedigree_adddog.tpl';
include XOOPS_ROOT_PATH . '/header.php';

//@todo - move language string to language file
$xoopsTpl->assign('page_title', 'Pedigree database - Add owner/breeder');

global $xoopsTpl, $xoopsUser, $xoopsDB;

$f = XoopsRequest::getCmd('f', '', 'POST');
if ('check' === $f) {
    //check security token here
    if (!$GLOBALS['xoopsSecurity']->check()) {
        redirect_header($_SERVER['PHP_SELF'], 3, implode('<br>', $GLOBALS['xoopsSecurity']->getErrors()));
    }

    $achternaam = XoopsRequest::getString('achternaam', '', 'POST');
    $voornaam   = XoopsRequest::getString('voornaam', '', 'POST');
    $email      = XoopsRequest::getEmail('email', '', 'POST');
    $website    = XoopsRequest::getUrl('website', '', 'POST');
    $user       = XoopsRequest::getString('user', '', 'POST');

    //insert into owner
    //$query = 'INSERT INTO ' . $GLOBALS['xoopsDB']->prefix('pedigree_owner') . " VALUES ('','" . $voornaam . "','" . $achternaam . "','','','','','','" . $email . "','" . $website . "','" . $user . "')";
    $query = 'INSERT INTO ' . $GLOBALS['xoopsDB']->prefix('pedigree_owner') . " VALUES ('','" . $GLOBALS['xoopsDB']->escape($voornaam) . "','" . $GLOBALS['xoopsDB']->escape($achternaam) . "','','','','','','" . $GLOBALS['xoopsDB']->escape($email) . "','" . $GLOBALS['xoopsDB']->escape($website) . "','" . $GLOBALS['xoopsDB']->escape($user) . "')";

    $GLOBALS['xoopsDB']->query($query);
    //@todo - move language string to language file
    redirect_header('index.php', 1, 'The data has been stored.');
}

//check for access
$xoopsModule = XoopsModule::getByDirname('pedigree');

//create form
include XOOPS_ROOT_PATH . '/class/xoopsformloader.php';
$form = new XoopsThemeForm(_MA_PEDIGREE_ADD_OWNER, 'breedername', 'add_breeder.php?f=check', 'POST');
$form->addElement(new XoopsFormHiddenToken($name = 'XOOPS_TOKEN_REQUEST', $timeout = 360));
$form->addElement(new XoopsFormHidden('user', $xoopsUser->getVar('uid')));
//lastname
$form->addElement(new XoopsFormText('<b>' . _MA_PEDIGREE_FLD_OWN_LNAME . '</b>', 'achternaam', $size = 50, $maxsize = 255, $value = ''));

//firstname
$form->addElement(new XoopsFormText('<b>' . _MA_PEDIGREE_FLD_OWN_FNAME . '</b>', 'voornaam', $size = 50, $maxsize = 255, $value = ''));

//email
$form->addElement(new XoopsFormText('<b>' . _MA_PEDIGREE_FLD_OWN_EMAIL . '</b>', 'email', $size = 50, $maxsize = 255, $value = ''));

//website
$form->addElement(new XoopsFormText('<b>' . _MA_PEDIGREE_FLD_OWN_WEB . '</b>', 'website', $size = 50, $maxsize = 255, $value = ''));
$form->addElement(new XoopsFormLabel(_MA_PEDIGREE_EXPLAIN, _MA_PEDIGREE_FLD_OWN_WEB_EX));

//submit button
$form->addElement(new XoopsFormButton('', 'button_id', _MA_PEDIGREE_ADD_OWNER, 'submit'));

//add data (form) to smarty template
$xoopsTpl->assign('form', $form->render());

//footer
include XOOPS_ROOT_PATH . '/footer.php';
