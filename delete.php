<?php
// -------------------------------------------------------------------------

use XoopsModules\Pedigree;


//require_once  dirname(dirname(__DIR__)) . '/mainfile.php';
require_once __DIR__ . '/header.php';
$moduleDirName = basename(__DIR__);
xoops_loadLanguage('main', $moduleDirName);
// Include any common code for this module.
require_once XOOPS_ROOT_PATH . '/modules/' . $moduleDirName . '/include/common.php';

$GLOBALS['xoopsOption']['template_main'] = 'pedigree_delete.tpl';

require_once XOOPS_ROOT_PATH . '/header.php';

//get module configuration
/** @var XoopsModuleHandler $moduleHandler */
$moduleHandler = xoops_getHandler('module');
$module        = $moduleHandler->getByDirname($moduleDirName);
$configHandler = xoops_getHandler('config');
$moduleConfig  = $configHandler->getConfigsByCat(0, $module->getVar('mid'));

//check for access
$xoopsModule = XoopsModule::getByDirname($moduleDirName);
if (empty($GLOBALS['xoopsUser']) || !($GLOBALS['xoopsUser'] instanceof \XoopsUser)) {
    redirect_header('javascript:history.go(-1)', 3, _NOPERM . '<br>' . _MA_PEDIGREE_REGIST);
}

global $xoopsTpl;
global $xoopsDB;
global $xoopsModuleConfig;

$id = $_GET['id'];
//query (find values for this dog (and format them))
$sql = 'SELECT pname, user, roft FROM ' . $GLOBALS['xoopsDB']->prefix('pedigree_registry') . ' WHERE id=' . $id;
$result      = $GLOBALS['xoopsDB']->query($sql);

while (false !== ($row = $GLOBALS['xoopsDB']->fetchArray($result))) {
    //ID
    $id = $row['id'];
    //name
    $pname     = htmlentities(stripslashes($row['pname']), ENT_QUOTES);
    $namelink = '<a href="dog.php?id=' . $row['id'] . '">' . stripslashes($row['pname']) . '</a>';
    //user who entered the info
    $dbuser = $row['user'];
    $roft   = $row['roft'];
}

//create form
require_once XOOPS_ROOT_PATH . '/class/xoopsformloader.php';
$form = new \XoopsThemeForm($pname, 'deletedata', 'deletepage.php', 'post', true);
//hidden value current record owner
$form->addElement(new \XoopsFormHidden('dbuser', $dbuser));
//hidden value dog ID
$form->addElement(new \XoopsFormHidden('dogid', $_GET['id']));
$form->addElement(new \XoopsFormHidden('curname', $pname));
$form->addElement(new \XoopsFormHiddenToken($name = 'XOOPS_TOKEN_REQUEST', $timeout = 360));
$form->addElement(new \XoopsFormLabel(_MA_PEDIGREE_DELE_SURE, _MA_PEDIGREE_DEL_MSG . $moduleConfig['animalType'] . ' : <b>' . $pname . '</b>?'));
$pups = pups($_GET['id'], $roft);
$form->addElement(new \XoopsFormLabel(_MA_PEDIGREE_DELE_WARN, _MA_PEDIGREE_ALL . $moduleConfig['children'] . _MA_PEDIGREE_ALL_ORPH . $pups));
$form->addElement(new \XoopsFormButton('', 'button_id', _DELETE, 'submit'));
//add data (form) to smarty template
$xoopsTpl->assign('form', $form->render());

//footer
require_once XOOPS_ROOT_PATH . '/footer.php';
