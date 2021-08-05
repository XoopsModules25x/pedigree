<?php
// -------------------------------------------------------------------------

use Xmf\Request;
use XoopsModules\Pedigree;

//require_once \dirname(__DIR__, 2) . '/mainfile.php';
require_once __DIR__ . '/header.php';

$moduleDirName = basename(__DIR__);
xoops_loadLanguage('main', $moduleDirName);
// Include any common code for this module.
require_once XOOPS_ROOT_PATH . '/modules/' . $xoopsModule->dirname() . '/include/common.php';

$GLOBALS['xoopsOption']['template_main'] = 'pedigree_adddog.tpl';

require_once XOOPS_ROOT_PATH . '/header.php';

//@todo - move language string to language file
$xoopsTpl->assign('page_title', 'Pedigree database - Add owner/breeder');

//check for access
$xoopsModule = XoopsModule::getByDirname($moduleDirName);
if (empty($xoopsUser)) {
    redirect_header('index.php', 3, _NOPERM . '<br>' . _MA_PEDIGREE_REGIST);
}

$f = \Xmf\Request::getString('f', '', 'GET');
if ('check' === $f) {
    check();
}

function check()
{
    global $xoopsTpl, $xoopsUser, $xoopsDB, $xoopsModuleConfig;
    //check for access
    $xoopsModule = XoopsModule::getByDirname($moduleDirName);
    if (empty($GLOBALS['xoopsUser']) || !($GLOBALS['xoopsUser'] instanceof \XoopsUser)) {
        redirect_header('<script>javascript:history.go(-1)</script>', 3, _NOPERM . '<br>' . _MA_PEDIGREE_REGIST);
    }
    $achternaam = Request::getString('achternaam', '', 'POST');
    $voornaam   = Request::getString('voornaam', '', 'POST');
    $email      = Request::getEmail('email', '', 'POST');
    $website    = Request::getUrl('website', '', 'POST');
    $user       = Request::getString('user', '', 'POST');

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
    //@todo - move language string to language file
    redirect_header('index.php', 1, 'The data has been stored.');
}

global $xoopsTpl, $xoopsUser, $xoopsDB;
//check for access
$xoopsModule = XoopsModule::getByDirname($moduleDirName);
if (empty($GLOBALS['xoopsUser']) || !($GLOBALS['xoopsUser'] instanceof \XoopsUser)) {
    redirect_header('<script>javascript:history.go(-1)</script>', 3, _NOPERM . '<br>' . _MA_PEDIGREE_REGIST);
}
//create form
require_once XOOPS_ROOT_PATH . '/class/xoopsformloader.php';
$form = new \XoopsThemeForm(_MA_PEDIGREE_ADD_OWNER, 'breedername', 'add_breeder.php?f=check', 'post', true);
$form->addElement(new \XoopsFormHiddenToken($name = 'XOOPS_TOKEN_REQUEST', $timeout = 360));
$form->addElement(new \XoopsFormHidden('user', $xoopsUser->getVar('uid')));
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
$xoopsTpl->assign('form', $form->render());

//footer
require_once XOOPS_ROOT_PATH . '/footer.php';
