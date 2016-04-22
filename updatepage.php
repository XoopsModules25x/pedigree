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
$xoopsModule = XoopsModule::getByDirname("pedigree");
if (empty($xoopsUser)) {
    redirect_header("javascript:history.go(-1)", 3, _NOPERM . "<br />" . _MA_PEDIGREE_REGIST);
    exit();
}
// ( $xoopsUser->isAdmin($xoopsModule->mid() ) )

global $xoopsTpl;
global $xoopsDB;
global $xoopsModuleConfig;

//possible variables (specific variables are found in the specified IF statement
$dogid = XoopsRequest::getInt('dogid', 0, 'post');
if (isset($_POST['ownerid'])) {
    $dogid = XoopsRequest::getInt('ownerid', 0, 'post');
}
$table   = XoopsRequest::getString('dbtable', '', 'post');
$field   = XoopsRequest::getString('dbfield', '', 'post');
$field   = $xoopsDB->escape('`' . $field . '`');
$dogname = XoopsRequest::getString('curname', '', 'post');
$name    = XoopsRequest::getString('NAAM', '', 'post');
$gender  = XoopsRequest::getString('roft', '', 'post');

if ('pedigree_' !== substr($table, 0, 9)) {
    redirect_header(XOOPS_URL, 3, _NOPERM);
}

$animal = new Animal($dogid);

$fields = $animal->numoffields();

for ($i = 0; $i < count($fields); ++$i) {
    if ($_POST['dbfield'] == 'user' . $fields[$i]) {
        $userfield = new Field($fields[$i], $animal->getconfig());
        if ($userfield->active()) {
            $currentfield = 'user' . $fields[$i];
            $picturefield = $_FILES[$currentfield]['name'];
            if (empty($picturefield) || $picturefield == "") {
                $newvalue = XoopsRequest::getString('user' . $fields[$i], '', 'post');
            } else {
                $newvalue = uploadedpict(0);
            }
            $sql = "UPDATE " . $xoopsDB->prefix($table) . " SET " . $field . "='" . $xoopsDB->escape($newvalue) . "' WHERE ID='" . $dogid . "'";
            $xoopsDB->queryF($sql);

            $ch = 1;
        }
    }
}

//name
if (!empty($name)) {
    $curval = XoopsRequest::getString('curvalname', '', 'post');
    $sql    = "UPDATE " . $xoopsDB->prefix($table) . " SET " . $field . "='" . $xoopsDB->escape($name) . "' WHERE ID='" . $dogid . "'";
    $xoopsDB->queryF($sql);

    $ch = 1;
}
//owner
if (isset($_POST['id_owner'])) {
    $curval = XoopsRequest::getInt('curvaleig', 0, 'post');
    $sql    = "UPDATE " . $xoopsDB->prefix($table) . " SET " . $field . "='" . $_POST['id_owner'] . "' WHERE ID='" . $dogid . "'";
    $xoopsDB->queryF($sql);

    $ch = 1;
}
//breeder
if (isset($_POST['id_breeder'])) {
    $curval = XoopsRequest::getInt('curvalfok', 0, 'post');
    $id_breeder = XoopsRequest::getInt('id_breeder', 0, 'post');
    $sql    = "UPDATE " . $xoopsDB->prefix($table) . " SET " . $field . "='" . $id_breeder . "' WHERE ID='" . $dogid . "'";
    $xoopsDB->queryF($sql);

    $ch = 1;
}
//gender
if (!empty($_POST['roft']) || $_POST['roft'] == '0') {
    $curval = XoopsRequest::getInt('curvalroft', 0, 'post');
    $roft = XoopsRequest::getInt('roft', 0, 'post');
    $sql    = "UPDATE " . $xoopsDB->prefix($table) . " SET " . $field . "='" . $roft . "' WHERE ID='" . $dogid . "'";
    $xoopsDB->queryF($sql);

    $ch = 1;
}
//sire - dam
if (isset($_GET['gend'])) {
    $curval = XoopsRequest::getInt('curval', 0, 'get');
    $thisid = XoopsRequest::getInt('thisid', 0, 'get');
    //$curname = getname($curval);
    $table = "pedigree_tree";
    if ($_GET['gend'] == '0') {
        $sql = "UPDATE " . $xoopsDB->prefix($table) . " SET father='" . $thisid . "' WHERE ID='" . $curval . "'";
        $xoopsDB->queryF($sql);
    } else {
        $sql = "UPDATE " . $xoopsDB->prefix($table) . " SET mother='" . $thisid . "' WHERE ID='" . $curval . "'";
        $xoopsDB->queryF($sql);
    }

    $ch    = 1;
    $dogid = $curval;
}
//picture
if ($_POST['dbfield'] == 'foto') {
    $curval = XoopsRequest::getString('curvalpic', '', 'post');
    $foto   = uploadedpict(0);
    $sql    = "UPDATE " . $xoopsDB->prefix($table) . " SET foto='" . $xoopsDB->escape($foto) . "' WHERE ID='" . $dogid . "'";
    $xoopsDB->queryF($sql);

    $ch = 1;
}

//owner
//lastname
if (isset($_POST['naaml'])) {
    $curval = XoopsRequest::getString('curvalnamel', '', 'post');
    $naaml = XoopsRequest::getString('naaml', '', 'post');
    $sql = "UPDATE " . $xoopsDB->prefix($table) . " SET " . $field . "='"
        . $xoopsDB->escape($naaml) . "' WHERE ID='" . $dogid . "'";
    $xoopsDB->queryF($sql);
    $chow = 1;
}
//firstname
if (isset($_POST['naamf'])) {
    $curval = XoopsRequest::getString('curvalnamef', '', 'post');
    $naaml = XoopsRequest::getString('naamf', '', 'post');
    $sql    = "UPDATE " . $xoopsDB->prefix($table) . " SET " . $field . "='"
        . $xoopsDB->escape($naamf) . "' WHERE ID='" . $dogid . "'";
    $xoopsDB->queryF($sql);
    $chow = 1;
}
//streetname
if (isset($_POST['street'])) {
    $curval = XoopsRequest::getString('curvalstreet', '', 'post');
    $street = $xoopsDB->escape(XoopsRequest::getString('street', '', 'post'));
    $sql    = "UPDATE " . $xoopsDB->prefix($table) . " SET " . $field . "='" . $street . "' WHERE ID='" . $dogid . "'";
    $xoopsDB->queryF($sql);
    $chow = 1;
}
//housenumber
if (isset($_POST['housenumber'])) {
    $curval = XoopsRequest::getString('curvalhousenumber', '', 'post');
    $housenumber = $xoopsDB->escape(XoopsRequest::getString('housenumber', '', 'post'));
    $sql    = "UPDATE " . $xoopsDB->prefix($table) . " SET " . $field . "='" . $housenumber . "' WHERE ID='" . $dogid . "'";
    $xoopsDB->queryF($sql);
    $chow = 1;
}
//postcode
if (isset($_POST['postcode'])) {
    $curval = XoopsRequest::getString('curvalpostcode', '', 'post');
    $postcode = $xoopsDB->escape(XoopsRequest::getString('postcode', '', 'post'));
    $sql    = "UPDATE " . $xoopsDB->prefix($table) . " SET " . $field . "='" . $postcode . "' WHERE ID='" . $dogid . "'";
    $xoopsDB->queryF($sql);
    $chow = 1;
}
//city
if (isset($_POST['city'])) {
    $curval = XoopsRequest::getString('curvalcity', '', 'post');
    $city = $xoopsDB->escape(XoopsRequest::getString('city', '', 'post'));
    $sql    = "UPDATE " . $xoopsDB->prefix($table) . " SET " . $field . "='" . $city . "' WHERE ID='" . $dogid . "'";
    $xoopsDB->queryF($sql);
    $chow = 1;
}
//phonenumber
if (isset($_POST['phonenumber'])) {
    $curval = XoopsRequest::getString('curvalphonenumber', '', 'post');
    $phonenumber = $xoopsDB->escape(XoopsRequest::getString('phonenumber', '', 'post'));
    $sql    = "UPDATE " . $xoopsDB->prefix($table) . " SET " . $field . "='" . $phonenumber . "' WHERE ID='" . $dogid . "'";
    $xoopsDB->queryF($sql);
    $chow = 1;
}
//email
if (isset($_POST['email'])) {
    $curval = XoopsRequest::getString('curvalemail', '', 'post');
    $email = $xoopsDB->escape(XoopsRequest::getEmail('email', '', 'post'));
    $sql    = "UPDATE " . $xoopsDB->prefix($table) . " SET " . $field . "='" . $email . "' WHERE ID='" . $dogid . "'";
    $xoopsDB->queryF($sql);
    $chow = 1;
}
//website
if (isset($_POST['web'])) {
    $curval = XoopsRequest::getString('curvalweb', '', 'post');
    $web = $xoopsDB->escape(XoopsRequest::getUrl('web', '', 'post'));
    $sql    = "UPDATE " . $xoopsDB->prefix($table) . " SET " . $field . "='" . $web . "' WHERE ID='" . $dogid . "'";
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
        $filesval .= $key . " : " . XoopsRequest::getString($values) . "<br />";
    }

    redirect_header("dog.php?id=" . $dogid, 15, 'ERROR!!<br />' . $filesval);
}
//footer
include XOOPS_ROOT_PATH . "/footer.php";
