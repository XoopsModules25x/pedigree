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

$xoopsOption['template_main'] = "pedigree_adddog.tpl";

include XOOPS_ROOT_PATH . '/header.php';
$xoopsTpl->assign('page_title', "Pedigree database - Add owner/breeder");

//check for access
$xoopsModule =& XoopsModule::getByDirname("pedigree");
if (empty($xoopsUser)) {
    redirect_header("index.php", 3, _NOPERM . "<br />" . _MA_PEDIGREE_REGIST);
    exit();
}

$f = isset($_GET['f']) ? $_GET['f'] : '';
if ($f == "check") {
    check();
}

function check()
{
    global $xoopsTpl, $xoopsUser, $xoopsDB, $xoopsModuleConfig;
    //check for access
    $xoopsModule =& XoopsModule::getByDirname("pedigree");
    if (empty($xoopsUser)) {
        redirect_header("javascript:history.go(-1)", 3, _NOPERM . "<br />" . _MA_PEDIGREE_REGIST);
        exit();
    }
    $achternaam = $_POST['achternaam'];
    $voornaam   = $_POST['voornaam'];
    $email      = $_POST['email'];
    $website    = $_POST['website'];
    $user       = $_POST['user'];
    //insert into owner
    $query = "INSERT INTO " . $xoopsDB->prefix("pedigree_owner") . " VALUES ('','" . $voornaam . "','" . $achternaam . "','','','','','','" . $email . "','" . $website . "','" . $user . "')";
    $xoopsDB->query($query);
    redirect_header("index.php", 1, "The data has been stored.");
}

global $xoopsTpl, $xoopsUser, $xoopsDB;
//check for access
$xoopsModule =& XoopsModule::getByDirname("pedigree");
if (empty($xoopsUser)) {
    redirect_header("javascript:history.go(-1)", 3, _NOPERM . "<br />" . _MA_PEDIGREE_REGIST);
    exit();
}
//create form
include XOOPS_ROOT_PATH . "/class/xoopsformloader.php";
$form = new XoopsThemeForm(_MA_PEDIGREE_ADD_OWNER, 'breedername', 'add_breeder.php?f=check', 'POST');
$form->addElement(new XoopsFormHiddenToken($name = 'XOOPS_TOKEN_REQUEST', $timeout = 360));
$form->addElement(new XoopsFormHidden('user', $xoopsUser->getVar("uid")));
//lastname
$form->addElement(new XoopsFormText("<b>" . _MA_PEDIGREE_FLD_OWN_LNAME . "</b>", 'achternaam', $size = 50, $maxsize = 255, $value = ''));

//firstname
$form->addElement(new XoopsFormText("<b>" . _MA_PEDIGREE_FLD_OWN_FNAME . "</b>", 'voornaam', $size = 50, $maxsize = 255, $value = ''));

//email
$form->addElement(new XoopsFormText("<b>" . _MA_PEDIGREE_FLD_OWN_EMAIL . "</b>", 'email', $size = 50, $maxsize = 255, $value = ''));

//website
$form->addElement(new XoopsFormText("<b>" . _MA_PEDIGREE_FLD_OWN_WEB . "</b>", 'website', $size = 50, $maxsize = 255, $value = ''));
$form->addElement(new XoopsFormLabel(_MA_PEDIGREE_EXPLAIN, _MA_PEDIGREE_FLD_OWN_WEB_EX));

//submit button
$form->addElement(new XoopsFormButton('', 'button_id', _MA_PEDIGREE_ADD_OWNER, 'submit'));

//add data (form) to smarty template
$xoopsTpl->assign("form", $form->render());

//footer
include XOOPS_ROOT_PATH . "/footer.php";
