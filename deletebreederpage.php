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

//check for access
$xoopsModule =& XoopsModule::getByDirname("pedigree");
if (empty($xoopsUser)) {
    redirect_header("javascript:history.go(-1)", 3, _NOPERM . "<br />" . _MA_PEDIGREE_REGIST);
    exit();
}

global $xoopsTpl, $xoopsDB, $xoopsUser;

$ownid     = $_POST['dogid'];
$ownername = $_POST['curname'];

if (!empty($ownername)) {
    $queryString = "SELECT * from " . $xoopsDB->prefix("pedigree_owner") . " WHERE ID=" . $ownid;
    $result      = $xoopsDB->query($queryString);
    while ($row = $xoopsDB->fetchArray($result)) {
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
        if ($access == "1") {
            $delsql = "DELETE FROM " . $xoopsDB->prefix("pedigree_owner") . " WHERE ID =" . $row['ID'];
            mysql_query($delsql);
            $sql = "UPDATE " . $xoopsDB->prefix("pedigree_tree") . " SET id_owner = '0' where id_owner = " . $row['ID'];
            mysql_query($sql);
            $sql = "UPDATE " . $xoopsDB->prefix("pedigree_tree") . " SET id_breeder = '0' where id_breeder = " . $row['ID'];
            mysql_query($sql);
            $ch = 1;
        }
    }
}

if ($ch) {
    redirect_header("index.php", 1, _MD_DATACHANGED);
} else {
    redirect_header("owner.php?ownid=" . $ownid, 1, "ERROR!!");
}
//footer
include XOOPS_ROOT_PATH . "/footer.php";
