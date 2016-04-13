<?php
// -------------------------------------------------------------------------

require_once dirname(dirname(__DIR__)) . '/mainfile.php';

//if (file_exists(XOOPS_ROOT_PATH . "/modules/" . $xoopsModule->dirname() . "/language/" . $xoopsConfig['language'] . "/main.php")) {
//    require_once XOOPS_ROOT_PATH . "/modules/" . $xoopsModule->dirname() . "/language/" . $xoopsConfig['language'] . "/main.php";
//} else {
//    include_once XOOPS_ROOT_PATH . "/modules/" . $xoopsModule->dirname() . "/language/english/main.php";
//}
xoops_loadLanguage('main', basename(dirname(__DIR__)));

// Include any common code for this module.
require_once(XOOPS_ROOT_PATH . "/modules/" . $xoopsModule->dirname() . "/include/functions.php");
require_once(XOOPS_ROOT_PATH . "/modules/" . $xoopsModule->dirname() . "/include/class_field.php");

$xoopsOption['template_main'] = "pedigree_update.tpl";

include XOOPS_ROOT_PATH . '/header.php';
$xoopsTpl->assign('page_title', "Pedigree database - Update details");

//check for access
$xoopsModule =& XoopsModule::getByDirname("pedigree");
if (empty($xoopsUser)) {
    redirect_header("javascript:history.go(-1)", 3, _NOPERM . "<br />" . _MA_PEDIGREE_REGIST);
    exit();
}
// ( $xoopsUser->isAdmin($xoopsModule->mid() ) )

global $xoopsTpl;
global $xoopsDB;
global $xoopsModuleConfig;

//possible variables (specific variables are found in the specified IF statement
$dogid = $_POST['dogid'];
if (isset($_POST['ownerid'])) {
    $dogid = $_POST['ownerid'];
}
$table   = $_POST['dbtable'];
$field   = $_POST['dbfield'];
$dogname = $_POST['curname'];
$name    = $_POST['NAAM'];
$gender  = $_POST['roft'];

$a      = (!isset($_POST['dogid']) ? $a = '' : $a = $_POST['dogid']);
$animal = new Animal($a);

$fields = $animal->numoffields();

for ($i = 0; $i < count($fields); ++$i) {
    if ($_POST['dbfield'] == 'user' . $fields[$i]) {
        $userfield = new Field($fields[$i], $animal->getconfig());
        if ($userfield->active()) {
            $currentfield = 'user' . $fields[$i];
            $picturefield = $_FILES[$currentfield]['name'];
            if (empty($picturefield) || $picturefield == "") {
                $newvalue = $_POST['user' . $fields[$i]];
            } else {
                $newvalue = uploadedpict(0);
            }
            $sql = "UPDATE " . $xoopsDB->prefix($table) . " SET " . $field . "='" . $newvalue . "' WHERE ID='" . $dogid . "'";
            $xoopsDB->queryF($sql);

            $ch = 1;
        }
    }
}

//name
if (!empty($name)) {
    $curval = $_POST['curvalname'];
    $sql    = "UPDATE " . $xoopsDB->prefix($table) . " SET " . $field . "='" . $name . "' WHERE ID='" . $dogid . "'";
    $xoopsDB->queryF($sql);

    $ch = 1;
}
//owner
if (isset($_POST['id_owner'])) {
    $curval = $_POST['curvaleig'];
    $sql    = "UPDATE " . $xoopsDB->prefix($table) . " SET " . $field . "='" . $_POST['id_owner'] . "' WHERE ID='" . $dogid . "'";
    $xoopsDB->queryF($sql);

    $ch = 1;
}
//breeder
if (isset($_POST['id_breeder'])) {
    $curval = $_POST['curvalfok'];
    $sql    = "UPDATE " . $xoopsDB->prefix($table) . " SET " . $field . "='" . $_POST['id_breeder'] . "' WHERE ID='" . $dogid . "'";
    $xoopsDB->queryF($sql);

    $ch = 1;
}
//gender
if (!empty($_POST['roft']) || $_POST['roft'] == '0') {
    $curval = $_POST['curvalroft'];
    $sql    = "UPDATE " . $xoopsDB->prefix($table) . " SET " . $field . "='" . $_POST['roft'] . "' WHERE ID='" . $dogid . "'";
    $xoopsDB->queryF($sql);

    $ch = 1;
}
//sire - dam
if (isset($_GET['gend'])) {
    $curval = $_GET['curval'];
    //$curname = getname($curval);
    $table = "pedigree_tree";
    if ($_GET['gend'] == '0') {
        $sql = "UPDATE " . $xoopsDB->prefix($table) . " SET father='" . $_GET['thisid'] . "' WHERE ID='" . $curval . "'";
        $xoopsDB->queryF($sql);
    } else {
        $sql = "UPDATE " . $xoopsDB->prefix($table) . " SET mother='" . $_GET['thisid'] . "' WHERE ID='" . $curval . "'";
        $xoopsDB->queryF($sql);
    }

    $ch    = 1;
    $dogid = $curval;
}
//picture
if ($_POST['dbfield'] == 'foto') {
    $curval = $_POST['curvalpic'];
    $foto   = uploadedpict(0);
    $sql    = "UPDATE " . $xoopsDB->prefix($table) . " SET foto='" . $foto . "' WHERE ID='" . $dogid . "'";
    $xoopsDB->queryF($sql);

    $ch = 1;
}

//owner
//lastname
if (isset($_POST['naaml'])) {
    $curval = $_POST['curvalnamel'];
    $sql    = "UPDATE " . $xoopsDB->prefix($table) . " SET " . $field . "='" . $_POST['naaml'] . "' WHERE ID='" . $dogid . "'";
    $xoopsDB->queryF($sql);
    $chow = 1;
}
//firstname
if (isset($_POST['naamf'])) {
    $curval = $_POST['curvalnamef'];
    $sql    = "UPDATE " . $xoopsDB->prefix($table) . " SET " . $field . "='" . $_POST['naamf'] . "' WHERE ID='" . $dogid . "'";
    $xoopsDB->queryF($sql);
    $chow = 1;
}
//streetname
if (isset($_POST['street'])) {
    $curval = $_POST['curvalstreet'];
    $sql    = "UPDATE " . $xoopsDB->prefix($table) . " SET " . $field . "='" . $_POST['street'] . "' WHERE ID='" . $dogid . "'";
    $xoopsDB->queryF($sql);
    $chow = 1;
}
//housenumber
if (isset($_POST['housenumber'])) {
    $curval = $_POST['curvalhousenumber'];
    $sql    = "UPDATE " . $xoopsDB->prefix($table) . " SET " . $field . "='" . $_POST['housenumber'] . "' WHERE ID='" . $dogid . "'";
    $xoopsDB->queryF($sql);
    $chow = 1;
}
//postcode
if (isset($_POST['postcode'])) {
    $curval = $_POST['curvalpostcode'];
    $sql    = "UPDATE " . $xoopsDB->prefix($table) . " SET " . $field . "='" . $_POST['postcode'] . "' WHERE ID='" . $dogid . "'";
    $xoopsDB->queryF($sql);
    $chow = 1;
}
//city
if (isset($_POST['city'])) {
    $curval = $_POST['curvalcity'];
    $sql    = "UPDATE " . $xoopsDB->prefix($table) . " SET " . $field . "='" . $_POST['city'] . "' WHERE ID='" . $dogid . "'";
    $xoopsDB->queryF($sql);
    $chow = 1;
}
//phonenumber
if (isset($_POST['phonenumber'])) {
    $curval = $_POST['curvalphonenumber'];
    $sql    = "UPDATE " . $xoopsDB->prefix($table) . " SET " . $field . "='" . $_POST['phonenumber'] . "' WHERE ID='" . $dogid . "'";
    $xoopsDB->queryF($sql);
    $chow = 1;
}
//email
if (isset($_POST['email'])) {
    $curval = $_POST['curvalemail'];
    $sql    = "UPDATE " . $xoopsDB->prefix($table) . " SET " . $field . "='" . $_POST['email'] . "' WHERE ID='" . $dogid . "'";
    $xoopsDB->queryF($sql);
    $chow = 1;
}
//website
if (isset($_POST['web'])) {
    $curval = $_POST['curvalweb'];
    $sql    = "UPDATE " . $xoopsDB->prefix($table) . " SET " . $field . "='" . $_POST['web'] . "' WHERE ID='" . $dogid . "'";
    $xoopsDB->queryF($sql);
    $chow = 1;
}

//check for access and completion
if ($ch) {
    redirect_header("dog.php?id=" . $dogid, 1, _MD_DATACHANGED);
} elseif ($chow) {
    redirect_header("owner.php?ownid=" . $dogid, 1, _MD_DATACHANGED);
} else {
    foreach ($_POST as $key => $values) {
        $filesval .= $key . " : " . $values . "<br />";
    }

    redirect_header("dog.php?id=" . $dogid, 15, 'ERROR!!<br />' . $filesval);
}
//footer
include XOOPS_ROOT_PATH . "/footer.php";
