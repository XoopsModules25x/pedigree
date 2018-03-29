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

use Xmf\Request;

//require_once __DIR__ . '/../../mainfile.php';
require_once __DIR__ . '/header.php';

$moduleDirName = basename(__DIR__);
xoops_loadLanguage('main', $moduleDirName);

//check for access
//$xoopsModule = XoopsModule::getByDirname($moduleDirName);
if (empty($GLOBALS['xoopsUser']) || !($GLOBALS['xoopsUser'] instanceof \XoopsUser)) {
    redirect_header('javascript:history.go(-1)', 3, _NOPERM . '<br>' . _MA_PEDIGREE_REGIST);
}

xoops_load('PedigreeAnimal', $moduleDirName);

// Include any common code for this module.
require_once $GLOBALS['xoops']->path("modules/{$moduleDirName}/include/common.php");
require_once $GLOBALS['xoops']->path("modules/{$moduleDirName}/class/field.php");

/*
$GLOBALS['xoopsOption']['template_main'] = "pedigree_update.tpl";

include $GLOBALS['xoops']->path('/header.php');
$GLOBALS['xoopsTpl']->assign('page_title', "Pedigree database - Update details");
*/

//@todo need to check XOOPS security token here...

//possible variables (specific variables are found in the specified IF statement
$dogid   = Request::getInt('dogid', 0, 'POST');
$ownerId = Request::getInt('ownerid', 0, 'POST');

/*
$table   = $_POST['dbtable'];
$field   = $_POST['dbfield'];
$dogname = $_POST['curname'];
$name    = $_POST['naam'];
$gender  = $_POST['roft'];
*/
$table   = Request::getString('dbtable', '', 'POST');
$field   = Request::getString('dbfield', '', 'POST');
$field   = $GLOBALS['xoopsDB']->escape('`' . $field . '`');
$dogname = Request::getString('curname', '', 'POST');
$name    = Request::getString('naam', '', 'POST');
//$gender   = Request::getInt('roft', 0, 'POST');
$gender   = Request::getString('roft', '', 'POST'); //Richard
$id_owner = Request::getInt('id_owner', 0, 'POST');

//$id       = (!isset($_POST['dogid']) ? $id = '' : $id = $_POST['dogid']);
$animal = new PedigreeAnimal($dogid);
$fields = $animal->getNumOfFields();

for ($i = 0, $iMax = count($fields); $i < $iMax; ++$i) {
    if ('user' . $fields[$i] === $_POST['dbfield']) {
        $userField = new Field($fields[$i], $animal->getConfig());
        if ($userField->isActive()) {
            $currentfield = 'user' . $fields[$i];
            $pictureField = $_FILES[$currentfield]['name'];
            if (empty($pictureField)) {
                $newvalue = $_POST['user' . $fields[$i]];
            } else {
                $newvalue = PedigreeUtility::uploadPicture(0);
            }
            $sql = 'UPDATE ' . $GLOBALS['xoopsDB']->prefix($table) . " SET {$field}='{$newvalue}' WHERE id='{$dogid}'";
            $GLOBALS['xoopsDB']->queryF($sql);

            $ch = 1;
        }
    }
}

//name
if (!empty($name)) {
    $curval = Request::getString('curvalname', '', 'POST');
    //    $sql    = 'UPDATE ' . $GLOBALS['xoopsDB']->prefix($table) . " SET {$field}='{$name}' WHERE id='{$dogid}'";
    $sql = 'UPDATE ' . $GLOBALS['xoopsDB']->prefix($table) . ' SET ' . $field . "='" . $GLOBALS['xoopsDB']->escape($name) . "' WHERE id='" . $dogid . "'";
    $GLOBALS['xoopsDB']->queryF($sql);

    $ch = 1;
}
//owner
if (isset($_POST['id_owner'])) {
    $curval = Request::getInt('curvaleig', 0, 'POST');
    //    $sql    = 'UPDATE ' . $GLOBALS['xoopsDB']->prefix($table) . " SET {$field}='" . $_POST['id_owner'] . "' WHERE id='{$dogid}'";
    $sql = 'UPDATE ' . $GLOBALS['xoopsDB']->prefix($table) . ' SET ' . $field . "='" . $_POST['id_owner'] . "' WHERE id='" . $dogid . "'";
    $GLOBALS['xoopsDB']->queryF($sql);

    $ch = 1;
}
//breeder
if (isset($_POST['id_breeder'])) {
    $curval = Request::getInt('curvalfok', 0, 'POST');
    //    $sql    = 'UPDATE ' . $GLOBALS['xoopsDB']->prefix($table) . " SET {$field}='" . $_POST['id_breeder'] . "' WHERE id='{$dogid}'";
    $id_breeder = Request::getInt('id_breeder', 0, 'post');
    $sql        = 'UPDATE ' . $GLOBALS['xoopsDB']->prefix($table) . ' SET ' . $field . "='" . $id_breeder . "' WHERE id='" . $dogid . "'";
    $GLOBALS['xoopsDB']->queryF($sql);

    $ch = 1;
}
//gender
if (!empty($_POST['roft']) || '0' == $_POST['roft']) {
    $curval = $_POST['curvalroft'];
    $curval = Request::getInt('curvalroft', 0, 'POST');
    //    $sql    = 'UPDATE ' . $GLOBALS['xoopsDB']->prefix($table) . " SET {$field}='" . $_POST['roft'] . "' WHERE id='{$dogid}'";
    $roft = Request::getInt('roft', 0, 'post');
    $sql  = 'UPDATE ' . $GLOBALS['xoopsDB']->prefix($table) . ' SET ' . $field . "='" . $roft . "' WHERE id='" . $dogid . "'";
    $GLOBALS['xoopsDB']->queryF($sql);

    $ch = 1;
}
//sire - dam
if (isset($_GET['gend'])) {
    $curval = Request::getInt('curval', 0, 'GET');
    $thisid = Request::getInt('thisid', 0, 'GET');
    //$curname = PedigreeUtility::getName($curval);
    $table = 'pedigree_tree';
    if (0 == Request::getInt('gend', '', 'GET')) {
        $sql = 'UPDATE ' . $GLOBALS['xoopsDB']->prefix($table) . " SET father='" . $thisid . "' WHERE id='{$curval}'";
        $GLOBALS['xoopsDB']->queryF($sql);
    } else {
        $sql = 'UPDATE ' . $GLOBALS['xoopsDB']->prefix($table) . " SET mother='" . $thisid . "' WHERE id='{$curval}'";
        $GLOBALS['xoopsDB']->queryF($sql);
    }

    $ch    = 1;
    $dogid = $curval;
}
//picture
if ('foto' === $_POST['dbfield']) {
    $curval = Request::getString('curvalpic', '', 'POST');
    $foto   = PedigreeUtility::uploadPicture(0);
    $sql    = 'UPDATE ' . $GLOBALS['xoopsDB']->prefix($table) . " SET foto='" . $GLOBALS['xoopsDB']->escape($foto) . "' WHERE id='{$dogid}'";
    $GLOBALS['xoopsDB']->queryF($sql);

    $ch = 1;
}

//owner
//lastname
if (isset($_POST['naaml'])) {
    //    $curval = $_POST['curvalnamel'];
    //    $sql    = 'UPDATE ' . $GLOBALS['xoopsDB']->prefix($table) . ' SET ' . $field . "='" . $_POST['naaml'] . "' WHERE id='" . $dogid . "'";
    $curval = Request::getString('curvalnamel', '', 'POST');
    $naaml  = Request::getString('naaml', '', 'POST');
    $sql    = 'UPDATE ' . $GLOBALS['xoopsDB']->prefix($table) . ' SET ' . $field . "='" . $GLOBALS['xoopsDB']->escape($naaml) . "' WHERE id='" . $dogid . "'";
    $GLOBALS['xoopsDB']->queryF($sql);
    $chow = 1;
}
//firstname
if (isset($_POST['naamf'])) {
    //    $curval = $_POST['curvalnamef'];
    //    $sql    = 'UPDATE ' . $GLOBALS['xoopsDB']->prefix($table) . ' SET ' . $field . "='" . $_POST['naamf'] . "' WHERE id='" . $dogid . "'";
    $curval = Request::getString('curvalnamef', '', 'POST');
    $naaml  = Request::getString('naamf', '', 'POST');
    $sql    = 'UPDATE ' . $GLOBALS['xoopsDB']->prefix($table) . ' SET ' . $field . "='" . $GLOBALS['xoopsDB']->escape($naamf) . "' WHERE id='" . $dogid . "'";
    $GLOBALS['xoopsDB']->query($sql);
    $chow = 1;
}
//streetname
if (isset($_POST['street'])) {
    //    $curval = $_POST['curvalstreet'];
    //    $sql    = 'UPDATE ' . $GLOBALS['xoopsDB']->prefix($table) . ' SET ' . $field . "='" . $_POST['street'] . "' WHERE id='" . $dogid . "'";
    $curval = Request::getString('curvalstreet', '', 'POST');
    $street = $GLOBALS['xoopsDB']->escape(Request::getString('street', '', 'POST'));
    $sql    = 'UPDATE ' . $GLOBALS['xoopsDB']->prefix($table) . ' SET ' . $field . "='" . $street . "' WHERE id='" . $dogid . "'";
    $GLOBALS['xoopsDB']->queryF($sql);
    $chow = 1;
}
//housenumber
if (isset($_POST['housenumber'])) {
    //    $curval = $_POST['curvalhousenumber'];
    //    $sql    = 'UPDATE ' . $GLOBALS['xoopsDB']->prefix($table) . ' SET ' . $field . "='" . $_POST['housenumber'] . "' WHERE id='" . $dogid . "'";
    $curval      = Request::getString('curvalhousenumber', '', 'POST');
    $housenumber = $GLOBALS['xoopsDB']->escape(Request::getString('housenumber', '', 'POST'));
    $sql         = 'UPDATE ' . $GLOBALS['xoopsDB']->prefix($table) . ' SET ' . $field . "='" . $housenumber . "' WHERE id='" . $dogid . "'";
    $GLOBALS['xoopsDB']->queryF($sql);
    $chow = 1;
}
//postcode
if (isset($_POST['postcode'])) {
    //    $curval = $_POST['curvalpostcode'];
    //    $sql    = 'UPDATE ' . $GLOBALS['xoopsDB']->prefix($table) . ' SET ' . $field . "='" . $_POST['postcode'] . "' WHERE id='" . $dogid . "'";
    $curval   = Request::getString('curvalpostcode', '', 'POST');
    $postcode = $GLOBALS['xoopsDB']->escape(Request::getString('postcode', '', 'POST'));
    $sql      = 'UPDATE ' . $GLOBALS['xoopsDB']->prefix($table) . ' SET ' . $field . "='" . $postcode . "' WHERE id='" . $dogid . "'";
    $GLOBALS['xoopsDB']->query($sql);
    $chow = 1;
}
//city
if (isset($_POST['city'])) {
    //    $curval = $_POST['curvalcity'];
    //    $sql    = 'UPDATE ' . $GLOBALS['xoopsDB']->prefix($table) . ' SET ' . $field . "='" . $_POST['city'] . "' WHERE id='" . $dogid . "'";
    $curval = Request::getString('curvalcity', '', 'POST');
    $city   = $GLOBALS['xoopsDB']->escape(Request::getString('city', '', 'POST'));
    $sql    = 'UPDATE ' . $GLOBALS['xoopsDB']->prefix($table) . ' SET ' . $field . "='" . $city . "' WHERE id='" . $dogid . "'";
    $GLOBALS['xoopsDB']->query($sql);
    $chow = 1;
}
//phonenumber
if (isset($_POST['phonenumber'])) {
    //    $curval = $_POST['curvalphonenumber'];
    //    $sql    = 'UPDATE ' . $GLOBALS['xoopsDB']->prefix($table) . ' SET ' . $field . "='" . $_POST['phonenumber'] . "' WHERE id='" . $dogid . "'";
    $curval      = Request::getString('curvalphonenumber', '', 'POST');
    $phonenumber = $GLOBALS['xoopsDB']->escape(Request::getString('phonenumber', '', 'POST'));
    $sql         = 'UPDATE ' . $GLOBALS['xoopsDB']->prefix($table) . ' SET ' . $field . "='" . $phonenumber . "' WHERE id='" . $dogid . "'";
    $GLOBALS['xoopsDB']->query($sql);
    $chow = 1;
}
//email
if (isset($_POST['email'])) {
    //    $curval = $_POST['curvalemail'];
    //    $sql    = 'UPDATE ' . $GLOBALS['xoopsDB']->prefix($table) . ' SET ' . $field . "='" . $_POST['email'] . "' WHERE id='" . $dogid . "'";
    $curval = Request::getString('curvalemail', '', 'POST');
    $email  = $GLOBALS['xoopsDB']->escape(Request::getEmail('email', '', 'POST'));
    $sql    = 'UPDATE ' . $GLOBALS['xoopsDB']->prefix($table) . ' SET ' . $field . "='" . $email . "' WHERE id='" . $dogid . "'";
    $GLOBALS['xoopsDB']->query($sql);
    $chow = 1;
}
//website
if (isset($_POST['web'])) {
    //    $curval = $_POST['curvalweb'];
    //    $sql    = 'UPDATE ' . $GLOBALS['xoopsDB']->prefix($table) . ' SET ' . $field . "='" . $_POST['web'] . "' WHERE id='" . $dogid . "'";
    $curval = Request::getString('curvalweb', '', 'POST');
    $web    = $GLOBALS['xoopsDB']->escape(Request::getUrl('web', '', 'POST'));
    $sql    = 'UPDATE ' . $GLOBALS['xoopsDB']->prefix($table) . ' SET ' . $field . "='" . $web . "' WHERE id='" . $dogid . "'";
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
        $filesval .= $key . ' : ' . Request::getString($values) . '<br>';
    }

    redirect_header('dog.php?id=' . $dogid, 15, 'ERROR!!<br>' . $filesval);
}
//footer
include XOOPS_ROOT_PATH . '/footer.php';
