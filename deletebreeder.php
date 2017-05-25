<?php
// -------------------------------------------------------------------------

require_once dirname(dirname(__DIR__)) . '/mainfile.php';
$moduleDirName = basename(__DIR__);
xoops_loadLanguage('main', $moduleDirName);
// Include any common code for this module.
require_once(XOOPS_ROOT_PATH . '/modules/' . $moduleDirName . '/include/common.php');

$xoopsOption['template_main'] = 'pedigree_delete.tpl';

include XOOPS_ROOT_PATH . '/header.php';

//get module configuration
$moduleHandler = xoops_getHandler('module');
$module        = $moduleHandler->getByDirname('pedigree');
$configHandler = xoops_getHandler('config');
$moduleConfig  = $configHandler->getConfigsByCat(0, $module->getVar('mid'));

//check for access
$xoopsModule = XoopsModule::getByDirname('pedigree');
if (empty($xoopsUser)) {
    redirect_header('javascript:history.go(-1)', 3, _NOPERM . '<br />' . _MA_PEDIGREE_REGIST);
}

global $xoopsTpl;
global $xoopsDB;
global $xoopsModuleConfig;

$id = XoopsRequest::getInt('id', 0, 'GET');
//query (find values for this dog (and format them))
$queryString = 'SELECT lastname, firstname, user FROM ' . $GLOBALS['xoopsDB']->prefix('pedigree_owner') . ' WHERE Id=' . $id;
$result      = $GLOBALS['xoopsDB']->query($queryString);

while (false !== ($row = $GLOBALS['xoopsDB']->fetchArray($result))) {
    //ID
//    $id = $row['Id'];
    //name
    $naam     = htmlentities(stripslashes($row['lastname']) . ', ' . stripslashes($row['firstname']), ENT_QUOTES);
    $namelink = "<a href=\"owner.php?ownid=" . $row['Id'] . "\">" . stripslashes($row['lastname']) . ', ' . stripslashes($row['firstname']) . '</a>';
    //user who entered the info
    $dbuser = $row['user'];
}

//create form
include XOOPS_ROOT_PATH . '/class/xoopsformloader.php';
$form = new XoopsThemeForm($naam, 'deletedata', 'deletebreederpage.php', 'POST');
//hidden value current record owner
$form->addElement(new XoopsFormHidden('dbuser', $dbuser));
//hidden value dog ID
$form->addElement(new XoopsFormHidden('dogid', $id));
$form->addElement(new XoopsFormHidden('curname', $naam));
$form->addElement(new XoopsFormHiddenToken($name = 'XOOPS_TOKEN_REQUEST', $timeout = 360));
$form->addElement(new XoopsFormLabel(_MA_PEDIGREE_DELE_SURE, _MA_PEDIGREE_DELE_CONF_OWN . '<b>' . $naam . '</b>?'));
$breeder = PedigreeUtilities::breederof($id, 1);
if ('' != $breeder) {
    $form->addElement(new XoopsFormLabel(_MA_PEDIGREE_DELE_WARN, strtr(_MA_PEDIGREE_DELE_WARN_BREEDER, array('[animalTypes]' => $moduleConfig['animalTypes'])) . '<br /><br />' . $breeder));
}
$owner = PedigreeUtilities::breederof($id, 0);
if ($owner != '') {
    $form->addElement(new XoopsFormLabel(_MA_PEDIGREE_DELE_WARN, strtr(_MA_PEDIGREE_DELE_WARN_OWNER, array('[animalTypes]' => $moduleConfig['animalTypes'])) . '<br /><br />' . $owner));
}
$form->addElement(new XoopsFormButton('', 'button_id', _MA_PEDIGREE_BTN_DELE, 'submit'));
//add data (form) to smarty template
$xoopsTpl->assign('form', $form->render());

//footer
include XOOPS_ROOT_PATH . '/footer.php';
