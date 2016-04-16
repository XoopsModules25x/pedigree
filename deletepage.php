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
require_once(XOOPS_ROOT_PATH . "/modules/" . $xoopsModule->dirname() . "/include/class_field.php");

$xoopsOption['template_main'] = "pedigree_delete.tpl";

include XOOPS_ROOT_PATH . '/header.php';

//check for access
$xoopsModule = XoopsModule::getByDirname("pedigree");
if (empty($xoopsUser)) {
    redirect_header("javascript:history.go(-1)", 3, _NOPERM . "<br />" . _MA_PEDIGREE_REGIST);
    exit();
}

global $xoopsTpl, $xoopsDB, $xoopsUser;

$dogid   = $_POST['dogid'];
$dogname = $_POST['curname'];

if (!empty($dogname)) {
    $queryString = "SELECT * from " . $xoopsDB->prefix("pedigree_tree") . " WHERE ID=" . $dogid;
    $result      = $xoopsDB->query($queryString);
    while ($row = $xoopsDB->fetchArray($result)) {
        //check for edit rights
        $access      = 0;
        $xoopsModule = XoopsModule::getByDirname("pedigree");
        if (!empty($xoopsUser)) {
            if ($xoopsUser->isAdmin($xoopsModule->mid())) {
                $access = 1;
            }
            if ($row['user'] == $xoopsUser->getVar("uid")) {
                $access = 1;
            }
        }
        if ($access == "1") {
            $sql = "INSERT INTO " . $xoopsDB->prefix("pedigree_trash") . " SELECT * FROM " . $xoopsDB->prefix("pedigree_tree") . " WHERE " . $xoopsDB->prefix("pedigree_tree") . ".ID='" . $dogid . "'";
            $xoopsDB->queryF($sql);
            $delsql = "DELETE FROM " . $xoopsDB->prefix("pedigree_tree") . " WHERE ID ='" . $row['ID'] . "'";
            $xoopsDB->queryF($delsql);
            if ($row['roft'] == "0") {
                $sql = "UPDATE " . $xoopsDB->prefix("pedigree_tree") . " SET father = '0' where father = '" . $row['ID'] . "'";
            } else {
                $sql = "UPDATE " . $xoopsDB->prefix("pedigree_tree") . " SET mother = '0' where mother = '" . $row['ID'] . "'";
            }
            $xoopsDB->queryF($sql);
            $ch = 1;
        }
    }
}

if ($ch) {
    redirect_header("index.php", 2, _MD_DATACHANGED);
} else {
    redirect_header("dog.php?id=" . $dogid, 1, "ERROR!!");
}
//footer
include XOOPS_ROOT_PATH . "/footer.php";
