<?php
// -------------------------------------------------------------------------

use Xmf\Request;
use XoopsModules\Pedigree\{
    Utility
};

//require_once \dirname(__DIR__, 2) . '/mainfile.php';
require_once __DIR__ . '/header.php';
$moduleDirName = basename(__DIR__);
xoops_loadLanguage('main', $moduleDirName);
// Include any common code for this module.
require_once XOOPS_ROOT_PATH . '/modules/' . $moduleDirName . '/include/common.php';

$GLOBALS['xoopsOption']['template_main'] = 'pedigree_delete.tpl';

require_once XOOPS_ROOT_PATH . '/header.php';

//get module configuration
/** @var \XoopsModuleHandler $moduleHandler */
$moduleHandler = xoops_getHandler('module');
$module        = $moduleHandler->getByDirname($moduleDirName);
/** @var \XoopsConfigHandler $configHandler */
$configHandler = xoops_getHandler('config');
$moduleConfig  = $configHandler->getConfigsByCat(0, $module->getVar('mid'));

//check for access
$xoopsModule = XoopsModule::getByDirname($moduleDirName);
if (empty($GLOBALS['xoopsUser']) || !($GLOBALS['xoopsUser'] instanceof \XoopsUser)) {
    redirect_header('<script>javascript:history.go(-1)</script>', 3, _NOPERM . '<br>' . _MA_PEDIGREE_REGIST);
}

global $xoopsTpl;
global $xoopsDB;
global $xoopsModuleConfig;

$id = Request::getInt('id', 0, 'GET');
//query (find values for this dog (and format them))
$sql    = 'SELECT lastname, firstname, user FROM ' . $GLOBALS['xoopsDB']->prefix('pedigree_owner') . ' WHERE id=' . $id;
$result = $GLOBALS['xoopsDB']->query($sql);

while (false !== ($row = $GLOBALS['xoopsDB']->fetchArray($result))) {
    //ID
    $id = $row['id'];
    //name
    $pname    = htmlentities(stripslashes($row['lastname']) . ', ' . stripslashes($row['firstname']), ENT_QUOTES);
    $namelink = '<a href="owner.php?ownid=' . $row['id'] . '">' . stripslashes($row['lastname']) . ', ' . stripslashes($row['firstname']) . '</a>';
    //user who entered the info
    $dbuser = $row['user'];
}

//create form
require_once XOOPS_ROOT_PATH . '/class/xoopsformloader.php';
$form = new \XoopsThemeForm($pname, 'deletedata', 'deletebreederpage.php', 'post', true);
//hidden value current record owner
$form->addElement(new \XoopsFormHidden('dbuser', $dbuser));
//hidden value dog ID
$form->addElement(new \XoopsFormHidden('dogid', $_GET['id']));
$form->addElement(new \XoopsFormHidden('curname', $pname));
$form->addElement(new \XoopsFormHiddenToken($name = 'XOOPS_TOKEN_REQUEST', $timeout = 360));
$form->addElement(new \XoopsFormLabel(_MA_PEDIGREE_DELE_SURE, _MA_PEDIGREE_DELE_CONF_OWN . '<b>' . $pname . '</b>?'));
$breeder = Utility::breederof($_GET['id'], 1);
if ('' != $breeder) {
    $form->addElement(new \XoopsFormLabel(_MA_PEDIGREE_DELE_WARN, strtr(_MA_PEDIGREE_DELE_WARN_BREEDER, ['[animalTypes]' => $helper->getConfig('animalTypes')]) . '<br><br>' . $breeder));
}
$owner = Utility::breederof($_GET['id'], 0);
if ('' != $owner) {
    $form->addElement(new \XoopsFormLabel(_MA_PEDIGREE_DELE_WARN, strtr(_MA_PEDIGREE_DELE_WARN_OWNER, ['[animalTypes]' => $helper->getConfig('animalTypes')]) . '<br><br>' . $owner));
}
$form->addElement(new \XoopsFormButton('', 'button_id', _DELETE, 'submit'));
//add data (form) to smarty template
$xoopsTpl->assign('form', $form->render());

//footer
require_once XOOPS_ROOT_PATH . '/footer.php';
