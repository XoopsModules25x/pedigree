<?php
// -------------------------------------------------------------------------

require_once dirname(dirname(__DIR__)) . '/mainfile.php';

/*
if (file_exists(XOOPS_ROOT_PATH . "/modules/" . $xoopsModule->dirname() . "/language/" . $xoopsConfig['language'] . "/main.php")) {
    require_once XOOPS_ROOT_PATH . "/modules/" . $xoopsModule->dirname() . "/language/" . $xoopsConfig['language'] . "/main.php";
} else {
    include_once XOOPS_ROOT_PATH . "/modules/" . $xoopsModule->dirname() . "/language/english/main.php";
}
*/

xoops_loadLanguage('main', basename(dirname(__DIR__)));

// Include any common code for this module.
require_once(XOOPS_ROOT_PATH . "/modules/" . $xoopsModule->dirname() . "/include/functions.php");

$xoopsOption['template_main'] = "pedigree_delete.tpl";

include XOOPS_ROOT_PATH . '/header.php';

//get module configuration
$module_handler =& xoops_gethandler('module');
$module         =& $module_handler->getByDirname("pedigree");
$config_handler =& xoops_gethandler('config');
$moduleConfig   =& $config_handler->getConfigsByCat(0, $module->getVar('mid'));

//check for access
$xoopsModule =& XoopsModule::getByDirname("pedigree");
if (empty($xoopsUser)) {
    redirect_header("javascript:history.go(-1)", 3, _NOPERM . "<br />" . _MA_PEDIGREE_REGIST);
    exit();
}

global $xoopsTpl;
global $xoopsDB;
global $xoopsModuleConfig;

$id = $_GET['id'];
//query (find values for this dog (and format them))
$queryString = "SELECT ID, NAAM, user, roft from " . $xoopsDB->prefix("pedigree_tree") . " WHERE ID=" . $id;
$result      = $xoopsDB->query($queryString);

while ($row = $xoopsDB->fetchArray($result)) {
    //ID
    $id = $row['ID'];
    //name
    $naam     = htmlentities(stripslashes($row['NAAM']), ENT_QUOTES);
    $namelink = "<a href=\"dog.php?id=" . $row['ID'] . "\">" . stripslashes($row['NAAM']) . "</a>";
    //user who entered the info
    $dbuser = $row['user'];
    $roft   = $row['roft'];
}

//create form
include XOOPS_ROOT_PATH . "/class/xoopsformloader.php";
$form = new XoopsThemeForm($naam, 'deletedata', 'deletepage.php', 'POST');
//hidden value current record owner
$form->addElement(new XoopsFormHidden('dbuser', $dbuser));
//hidden value dog ID
$form->addElement(new XoopsFormHidden('dogid', $_GET['id']));
$form->addElement(new XoopsFormHidden('curname', $naam));
$form->addElement(new XoopsFormHiddenToken($name = 'XOOPS_TOKEN_REQUEST', $timeout = 360));
$form->addElement(new XoopsFormLabel(_MA_PEDIGREE_DELE_SURE, _MA_PEDIGREE_DEL_MSG . $moduleConfig['animalType'] . " : <b>" . $naam . "</b> ?"));
$pups = pups($_GET['id'], $roft);
$form->addElement(new XoopsFormLabel(_MA_PEDIGREE_DELE_WARN, _MA_PEDIGREE_ALL . $moduleConfig['children'] . _MA_PEDIGREE_ALL_ORPH . $pups));
$form->addElement(new XoopsFormButton('', 'button_id', _MA_PEDIGREE_BTN_DELE, 'submit'));
//add data (form) to smarty template
$xoopsTpl->assign("form", $form->render());

//footer
include XOOPS_ROOT_PATH . "/footer.php";
