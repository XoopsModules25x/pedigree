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

include_once 'header.php';

// Include any common code for this module.
require_once(XOOPS_ROOT_PATH . "/modules/" . $xoopsModule->dirname() . "/include/functions.php");

// Get all HTTP post or get parameters into global variables that are prefixed with "param_"
//import_request_variables("gp", "param_");
extract($_GET, EXTR_PREFIX_ALL, "param");
extract($_POST, EXTR_PREFIX_ALL, "param");

$xoopsOption['template_main'] = "pedigree_owner.html";

include XOOPS_ROOT_PATH . '/header.php';
$xoopsTpl->assign('page_title', "Pedigree database - View Owner/Breeder details");

//get module configuration
$module_handler =& xoops_gethandler('module');
$module         =& $module_handler->getByDirname("pedigree");
$config_handler =& xoops_gethandler('config');
$moduleConfig   =& $config_handler->getConfigsByCat(0, $module->getVar('mid'));

global $xoopsTpl, $xoopsDB, $xoopsModuleConfig, $xoopsModule;

$pathIcon16 = $xoopsModule->getInfo('icons16');

xoops_load('XoopsUserUtility');

$ownid = $_GET['ownid'];

//query
$queryString = "SELECT * from " . $xoopsDB->prefix("pedigree_owner") . " WHERE ID=" . $ownid;
$result      = $xoopsDB->query($queryString);

while ($row = $xoopsDB->fetchArray($result)) {
    //id
    $id = $row['ID'];

    //name
    $naam = stripslashes($row['firstname']) . " " . stripslashes($row['lastname']);

    //lastname
    $naaml = stripslashes($row['lastname']);

    //firstname
    $naamf = stripslashes($row['firstname']);

    //email
    $email = $row['emailadres'];

    //homepage
    $homepage = $row['website'];
    $check    = substr($homepage, 0, 7);
    if ($check != "http://") {
        $homepage = "http://" . $homepage;
    }

    //Owner of
    $owner = breederof($row['ID'], 0);

    //Breeder of
    $breeder = breederof($row['ID'], 1);

    //entered into the database by
    $dbuser = XoopsUserUtility::getUnameFromId($row['user']);

    //check for edit rights
    $access      = 0;
    $xoopsModule =& XoopsModule::getByDirname("pedigree");
    if (!empty($xoopsUser)) {
        if ($xoopsUser->isAdmin($xoopsModule->mid())) {
            $access = 1;
        }
        if ($row['user'] == $xoopsUser->getVar("uid")) {
            $access = 1;
        }
    }

    //lastname
    $items[] = array(
        'header' => _MA_PEDIGREE_OWN_LNAME,
        'data'   => "<a href=\"owner.php?ownid=" . $row['ID'] . "\">" . $naaml . "</a>",
        'edit'   => "<a href=\"updateowner.php?id=" . $row['ID'] . "&fld=nl\"><img src=' " . $pathIcon16 . "/edit.png' border='0' alt=_EDIT title=_EDIT /></a>"
    );

    //firstname
    $items[] = array(
        'header' => _MA_PEDIGREE_OWN_FNAME,
        'data'   => "<a href=\"owner.php?ownid=" . $row['ID'] . "\">" . $naamf . "</a>",
        'edit'   => "<a href=\"updateowner.php?id=" . $row['ID'] . "&fld=nf\"><img src=' " . $pathIcon16 . "/edit.png' border='0' alt=_EDIT title=_EDIT /></a>"
    );

    //email
    $items[] = array(
        'header' => _MA_PEDIGREE_FLD_OWN_EMAIL,
        'data'   => "<a href=\"mailto:" . $email . "\">" . $email . "</a>",
        'edit'   => "<a href=\"updateowner.php?id=" . $row['ID'] . "&fld=em\"><img src=' " . $pathIcon16 . "/edit.png' border='0' alt=_EDIT title=_EDIT /></a>"
    );
    //homepage
    $items[] = array(
        'header' => _MA_PEDIGREE_FLD_OWN_WEB,
        'data'   => "<a href=\"" . $homepage . "\" target=\"_blank\">" . $homepage . "</a>",
        'edit'   => "<a href=\"updateowner.php?id=" . $row['ID'] . "&fld=we\"><img src=' " . $pathIcon16 . "/edit.png' border='0' alt=_EDIT title=_EDIT /></a>"
    );
    //owner of
    $items[] = array(
        'header' => _MA_PEDIGREE_OWN_OWN,
        'data'   => $owner,
        'edit'   => ""
    );
    //breeder of
    $items[] = array(
        'header' => _MA_PEDIGREE_OWN_BRE,
        'data'   => $breeder,
        'edit'   => ""
    );

    //database user
    $items[] = array(
        'header' => _MA_PEDIGREE_FLD_DBUS,
        'data'   => $dbuser,
        'edit'   => ""
    );

}

//add data to smarty template
$xoopsTpl->assign("access", $access);
$xoopsTpl->assign("dogs", $items);
$xoopsTpl->assign("name", $naam);
$xoopsTpl->assign("id", $id);
//$xoopsTpl->assign("delete", _MA_PEDIGREE_BTN_DELE);
$xoopsTpl->assign("delete", "<img src=' " . $pathIcon16 . "/delete.png' border='0' alt=_DELETE title=_DELETE />");

//comments and footer
include XOOPS_ROOT_PATH . "/footer.php";
