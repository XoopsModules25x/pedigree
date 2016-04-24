<?php
/*
 You may not change or alter any portion of this comment or credits
 of supporting developers from this source code or any supporting source code
 which is considered copyrighted (c) material of the original comment or credit authors.

 This program is distributed in the hope that it will be useful,
 but WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
*/
/**
 * animal module for XOOPS
 *
 * @copyright       {@link http://sourceforge.net/projects/thmod/ The TXMod XOOPS Project}
 * @copyright       {@link http://sourceforge.net/projects/xoops/ The XOOPS Project}
 * @license         GPL 2.0 or later
 * @package         pedigree
 * @author          XOOPS Mod Development Team
 * @version         $Id: $
 *
 * @todo            : move hard coded language strings to language files
 */

require_once dirname(dirname(__DIR__)) . '/mainfile.php';

$moduleDirName = basename(__DIR__);
xoops_loadLanguage('main', $moduleDirName);

//check for access
$xoopsModule = XoopsModule::getByDirname($moduleDirName);
if (empty($GLOBALS['xoopsUser']) || !($GLOBALS['xoopsUser'] instanceof XoopsUser)) {
    redirect_header('javascript:history.go(-1)', 3, _NOPERM . '<br />' . _MA_PEDIGREE_REGIST);
}

xoops_load('XoopsRequest');
xoops_load('PedigreeAnimal', $moduleDirName);

// Include any common code for this module.
require_once(XOOPS_ROOT_PATH . '/modules/' . $moduleDirName . '/include/common.php');
require_once $GLOBALS['xoops']->path("modules/{$moduleDirName}/include/class_field.php");

/*
$xoopsOption['template_main'] = "pedigree_update.tpl";

include $GLOBALS['xoops']->path('/header.php');
$GLOBALS['xoopsTpl']->assign('page_title', "Pedigree database - Update details");
*/
//possible variables (specific variables are found in the specified IF statement
if (isset($_POST['dogid'])) {
    $dogId = XoopsRequest::getInt('dogid', 0, 'POST');
}
if (isset($_POST['ownerid'])) {
    $ownerId = XoopsRequest::getInt('ownerid', 0, 'post');
}
/*
$table   = $_POST['dbtable'];
$field   = $_POST['dbfield'];
$dogname = $_POST['curname'];
$name    = $_POST['NAAM'];
$gender  = $_POST['roft'];
*/
$table   = XoopsRequest::getString('dbtable', '', 'POST');
$field   = XoopsRequest::getString('dbfield', '', 'POST');
$field   = $GLOBALS['xoopsDB']->escape('`' . $field . '`');
$dogname = XoopsRequest::getString('curname', '', 'POST');
$name    = XoopsRequest::getString('NAAM', '', 'POST');
//$gender   = XoopsRequest::getInt('roft', 0, 'POST');
$gender   = XoopsRequest::getString('roft', '', 'post'); //Richard
$id_owner = XoopsRequest::getInt('id_owner', 0, 'POST');

//$id       = (!isset($_POST['dogid']) ? $id = '' : $id = $_POST['dogid']);
$animal = new PedigreeAnimal($dogid);

$fields = $animal->getNumOfFields();

for ($i = 0, $iMax = count($fields); $i < $iMax; ++$i) {
    if ($_POST['dbfield'] == 'user' . $fields[$i]) {
        $userField = new Field($fields[$i], $animal->getConfig());
        if ($userField->isActive()) {
            $currentfield = 'user' . $fields[$i];
            $picturefield = $_FILES[$currentfield]['name'];
            if (empty($picturefield)) {
                $newvalue = $_POST['user' . $fields[$i]];
            } else {
                $newvalue = PedigreeUtilities::uploadPicture(0);
            }
            $sql = 'UPDATE ' . $GLOBALS['xoopsDB']->prefix($table) . " SET {$field}='{$newvalue}' WHERE ID='{$dogid}'";
            $GLOBALS['xoopsDB']->queryF($sql);

            $ch = 1;
        }
    }
}

//name
if (!empty($name)) {
    $curval = XoopsRequest::getString('curvalname', '', 'POST');
    //    $sql    = 'UPDATE ' . $GLOBALS['xoopsDB']->prefix($table) . " SET {$field}='{$name}' WHERE ID='{$dogid}'";
    $sql = 'UPDATE ' . $GLOBALS['xoopsDB']->prefix($table) . ' SET ' . $field . "='" . $GLOBALS['xoopsDB']->escape($name) . "' WHERE ID='" . $dogid . "'";
    $GLOBALS['xoopsDB']->queryF($sql);

    $ch = 1;
}
//owner
if (isset($_POST['id_owner'])) {
    $curval = XoopsRequest::getInt('curvaleig', 0, 'POST');
    //    $sql    = 'UPDATE ' . $GLOBALS['xoopsDB']->prefix($table) . " SET {$field}='" . $_POST['id_owner'] . "' WHERE ID='{$dogid}'";
    $sql = 'UPDATE ' . $GLOBALS['xoopsDB']->prefix($table) . ' SET ' . $field . "='" . $_POST['id_owner'] . "' WHERE ID='" . $dogid . "'";
    $GLOBALS['xoopsDB']->queryF($sql);

    $ch = 1;
}
//breeder
if (isset($_POST['id_breeder'])) {
    $curval = XoopsRequest::getInt('curvalfok', 0, 'POST');
    //    $sql    = 'UPDATE ' . $GLOBALS['xoopsDB']->prefix($table) . " SET {$field}='" . $_POST['id_breeder'] . "' WHERE ID='{$dogid}'";
    $id_breeder = XoopsRequest::getInt('id_breeder', 0, 'post');
    $sql        = 'UPDATE ' . $GLOBALS['xoopsDB']->prefix($table) . ' SET ' . $field . "='" . $id_breeder . "' WHERE ID='" . $dogid . "'";
    $GLOBALS['xoopsDB']->queryF($sql);

    $ch = 1;
}
//gender
if (!empty($_POST['roft']) || $_POST['roft'] == '0') {
    $curval = $_POST['curvalroft'];
    $curval = XoopsRequest::getInt('curvalroft', 0, 'POST');
    //    $sql    = 'UPDATE ' . $GLOBALS['xoopsDB']->prefix($table) . " SET {$field}='" . $_POST['roft'] . "' WHERE ID='{$dogid}'";
    $roft = XoopsRequest::getInt('roft', 0, 'post');
    $sql  = 'UPDATE ' . $GLOBALS['xoopsDB']->prefix($table) . ' SET ' . $field . "='" . $roft . "' WHERE ID='" . $dogid . "'";
    $GLOBALS['xoopsDB']->queryF($sql);

    $ch = 1;
}
//sire - dam
if (isset($_GET['gend'])) {
    $curval = XoopsRequest::getInt('curval', 0, 'get');
    $thisid = XoopsRequest::getInt('thisid', 0, 'get');
    //$curname = getName($curval);
    $table = 'pedigree_tree';
    if (0 == XoopsRequest::getInt('gend', '', 'GET')) {
        $sql = 'UPDATE ' . $GLOBALS['xoopsDB']->prefix($table) . " SET father='" . $thisid . "' WHERE ID='{$curval}'";
        $GLOBALS['xoopsDB']->queryF($sql);
    } else {
        $sql = 'UPDATE ' . $GLOBALS['xoopsDB']->prefix($table) . " SET mother='" . $thisid . "' WHERE ID='{$curval}'";
        $GLOBALS['xoopsDB']->queryF($sql);
    }

    $ch    = 1;
    $dogid = $curval;
}
//picture
if ($_POST['dbfield'] === 'foto') {
    $curval = XoopsRequest::getString('curvalpic', '', 'POST');
    $foto   = PedigreeUtilities::uploadPicture(0);
    $sql    = 'UPDATE ' . $GLOBALS['xoopsDB']->prefix($table) . " SET foto='" . $GLOBALS['xoopsDB']->escape($foto) . "' WHERE ID='{$dogid}'";
    $GLOBALS['xoopsDB']->queryF($sql);

    $ch = 1;
}

//owner
//lastname
if (isset($_POST['naaml'])) {
    //    $curval = $_POST['curvalnamel'];
    //    $sql    = 'UPDATE ' . $GLOBALS['xoopsDB']->prefix($table) . ' SET ' . $field . "='" . $_POST['naaml'] . "' WHERE ID='" . $dogid . "'";
    $curval = XoopsRequest::getString('curvalnamel', '', 'POST');
    $naaml  = XoopsRequest::getString('naaml', '', 'POST');
    $sql    = 'UPDATE ' . $GLOBALS['xoopsDB']->prefix($table) . ' SET ' . $field . "='" . $GLOBALS['xoopsDB']->escape($naaml) . "' WHERE ID='" . $dogid . "'";
    $GLOBALS['xoopsDB']->queryF($sql);
    $chow = 1;
}
//firstname
if (isset($_POST['naamf'])) {
    //    $curval = $_POST['curvalnamef'];
    //    $sql    = 'UPDATE ' . $GLOBALS['xoopsDB']->prefix($table) . ' SET ' . $field . "='" . $_POST['naamf'] . "' WHERE ID='" . $dogid . "'";
    $curval = XoopsRequest::getString('curvalnamef', '', 'POST');
    $naaml  = XoopsRequest::getString('naamf', '', 'POST');
    $sql    = 'UPDATE ' . $GLOBALS['xoopsDB']->prefix($table) . ' SET ' . $field . "='" . $GLOBALS['xoopsDB']->escape($naamf) . "' WHERE ID='" . $dogid . "'";
    $GLOBALS['xoopsDB']->query($sql);
    $chow = 1;
}
//streetname
if (isset($_POST['street'])) {
    //    $curval = $_POST['curvalstreet'];
    //    $sql    = 'UPDATE ' . $GLOBALS['xoopsDB']->prefix($table) . ' SET ' . $field . "='" . $_POST['street'] . "' WHERE ID='" . $dogid . "'";
    $curval = XoopsRequest::getString('curvalstreet', '', 'POST');
    $street = $GLOBALS['xoopsDB']->escape(XoopsRequest::getString('street', '', 'POST'));
    $sql    = 'UPDATE ' . $GLOBALS['xoopsDB']->prefix($table) . ' SET ' . $field . "='" . $street . "' WHERE ID='" . $dogid . "'";
    $GLOBALS['xoopsDB']->queryF($sql);
    $chow = 1;
}
//housenumber
if (isset($_POST['housenumber'])) {
    //    $curval = $_POST['curvalhousenumber'];
    //    $sql    = 'UPDATE ' . $GLOBALS['xoopsDB']->prefix($table) . ' SET ' . $field . "='" . $_POST['housenumber'] . "' WHERE ID='" . $dogid . "'";
    $curval      = XoopsRequest::getString('curvalhousenumber', '', 'POST');
    $housenumber = $GLOBALS['xoopsDB']->escape(XoopsRequest::getString('housenumber', '', 'POST'));
    $sql         = 'UPDATE ' . $GLOBALS['xoopsDB']->prefix($table) . ' SET ' . $field . "='" . $housenumber . "' WHERE ID='" . $dogid . "'";
    $GLOBALS['xoopsDB']->queryF($sql);
    $chow = 1;
}
//postcode
if (isset($_POST['postcode'])) {
    //    $curval = $_POST['curvalpostcode'];
    //    $sql    = 'UPDATE ' . $GLOBALS['xoopsDB']->prefix($table) . ' SET ' . $field . "='" . $_POST['postcode'] . "' WHERE ID='" . $dogid . "'";
    $curval   = XoopsRequest::getString('curvalpostcode', '', 'POST');
    $postcode = $GLOBALS['xoopsDB']->escape(XoopsRequest::getString('postcode', '', 'POST'));
    $sql      = 'UPDATE ' . $GLOBALS['xoopsDB']->prefix($table) . ' SET ' . $field . "='" . $postcode . "' WHERE ID='" . $dogid . "'";
    $GLOBALS['xoopsDB']->query($sql);
    $chow = 1;
}
//city
if (isset($_POST['city'])) {
    //    $curval = $_POST['curvalcity'];
    //    $sql    = 'UPDATE ' . $GLOBALS['xoopsDB']->prefix($table) . ' SET ' . $field . "='" . $_POST['city'] . "' WHERE ID='" . $dogid . "'";
    $curval = XoopsRequest::getString('curvalcity', '', 'POST');
    $city   = $GLOBALS['xoopsDB']->escape(XoopsRequest::getString('city', '', 'POST'));
    $sql    = 'UPDATE ' . $GLOBALS['xoopsDB']->prefix($table) . ' SET ' . $field . "='" . $city . "' WHERE ID='" . $dogid . "'";
    $GLOBALS['xoopsDB']->query($sql);
    $chow = 1;
}
//phonenumber
if (isset($_POST['phonenumber'])) {
    //    $curval = $_POST['curvalphonenumber'];
    //    $sql    = 'UPDATE ' . $GLOBALS['xoopsDB']->prefix($table) . ' SET ' . $field . "='" . $_POST['phonenumber'] . "' WHERE ID='" . $dogid . "'";
    $curval      = XoopsRequest::getString('curvalphonenumber', '', 'POST');
    $phonenumber = $GLOBALS['xoopsDB']->escape(XoopsRequest::getString('phonenumber', '', 'POST'));
    $sql         = 'UPDATE ' . $GLOBALS['xoopsDB']->prefix($table) . ' SET ' . $field . "='" . $phonenumber . "' WHERE ID='" . $dogid . "'";
    $GLOBALS['xoopsDB']->query($sql);
    $chow = 1;
}
//email
if (isset($_POST['email'])) {
    //    $curval = $_POST['curvalemail'];
    //    $sql    = 'UPDATE ' . $GLOBALS['xoopsDB']->prefix($table) . ' SET ' . $field . "='" . $_POST['email'] . "' WHERE ID='" . $dogid . "'";
    $curval = XoopsRequest::getString('curvalemail', '', 'POST');
    $email  = $GLOBALS['xoopsDB']->escape(XoopsRequest::getEmail('email', '', 'POST'));
    $sql    = 'UPDATE ' . $GLOBALS['xoopsDB']->prefix($table) . ' SET ' . $field . "='" . $email . "' WHERE ID='" . $dogid . "'";
    $GLOBALS['xoopsDB']->query($sql);
    $chow = 1;
}
//website
if (isset($_POST['web'])) {
    //    $curval = $_POST['curvalweb'];
    //    $sql    = 'UPDATE ' . $GLOBALS['xoopsDB']->prefix($table) . ' SET ' . $field . "='" . $_POST['web'] . "' WHERE ID='" . $dogid . "'";
    $curval = XoopsRequest::getString('curvalweb', '', 'POST');
    $web    = $GLOBALS['xoopsDB']->escape(XoopsRequest::getUrl('web', '', 'POST'));
    $sql    = 'UPDATE ' . $GLOBALS['xoopsDB']->prefix($table) . ' SET ' . $field . "='" . $web . "' WHERE ID='" . $dogid . "'";
    $GLOBALS['xoopsDB']->query($sql);
    $chow = 1;
}

//check for access and completion
if ($ch) {
    redirect_header('dog.php?id=' . $dogid, 1, _MD_DATACHANGED);
} elseif ($chow) {
    redirect_header('owner.php?ownid=' . $dogid, 1, _MD_DATACHANGED);
} else {
    foreach ($_POST as $key => $values) {
        $filesval .= $key . ' : ' . XoopsRequest::getString($values) . '<br />';
    }

    redirect_header('dog.php?id=' . $dogid, 15, 'ERROR!!<br />' . $filesval);
}
//footer
include XOOPS_ROOT_PATH . '/footer.php';
