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
use XoopsModules\Pedigree;

//require_once \dirname(__DIR__, 2) . '/mainfile.php';
require_once __DIR__ . '/header.php';

$moduleDirName = basename(__DIR__);
xoops_loadLanguage('main', $moduleDirName);

//check for access
//$xoopsModule = XoopsModule::getByDirname($moduleDirName);
if (empty($GLOBALS['xoopsUser']) || !($GLOBALS['xoopsUser'] instanceof \XoopsUser)) {
    redirect_header('<script>javascript:history.go(-1)</script>', 3, _NOPERM . '<br>' . _MA_PEDIGREE_REGIST);
}

// Include any common code for this module.
require_once $GLOBALS['xoops']->path("modules/{$moduleDirName}/include/common.php");

/*
$GLOBALS['xoopsOption']['template_main'] = "pedigree_update.tpl";

require_once $GLOBALS['xoops']->path('/header.php');
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
$name    = $_POST['pname'];
$gender  = $_POST['roft'];
*/
$table   = Request::getString('dbtable', '', 'POST');
$field   = Request::getString('dbfield', '', 'POST');
$field   = $GLOBALS['xoopsDB']->escape('`' . $field . '`');
$dogname = Request::getString('curname', '', 'POST');
$name    = Request::getString('pname', '', 'POST');
//$gender   = Request::getInt('roft', 0, 'POST');
$gender   = Request::getString('roft', '', 'POST'); //Richard
$id_owner = Request::getInt('id_owner', 0, 'POST');

//$id       = (!isset($_POST['dogid']) ? $id = '' : $id = $_POST['dogid']);
$animal = new Pedigree\Animal($dogid);
$fields = $animal->getNumOfFields();

foreach ($fields as $i => $iValue) {
    if ('user' . $iValue === $_POST['dbfield']) {
        $userField = new Pedigree\Field($fields[$i], $animal->getConfig());
        if ($userField->isActive()) {
            $currentfield = 'user' . $iValue;
            $pictureField = $_FILES[$currentfield]['name'];
            if (empty($pictureField)) {
                $newvalue = $_POST['user' . $iValue];
            } else {
                $newvalue = Pedigree\Utility::uploadPicture(0);
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
if (\Xmf\Request::hasVar('id_owner', 'POST')) {
    $curval = Request::getInt('curvaleig', 0, 'POST');
    //    $sql    = 'UPDATE ' . $GLOBALS['xoopsDB']->prefix($table) . " SET {$field}='" . $_POST['id_owner'] . "' WHERE id='{$dogid}'";
    $sql = 'UPDATE ' . $GLOBALS['xoopsDB']->prefix($table) . ' SET ' . $field . "='" . $_POST['id_owner'] . "' WHERE id='" . $dogid . "'";
    $GLOBALS['xoopsDB']->queryF($sql);

    $ch = 1;
}
//breeder
if (\Xmf\Request::hasVar('id_breeder', 'POST')) {
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
if (\Xmf\Request::hasVar('gend', 'GET')) {
    $curval = Request::getInt('curval', 0, 'GET');
    $thisid = Request::getInt('thisid', 0, 'GET');
    //$curname = Pedigree\Utility::getName($curval);
    $table = 'pedigree_registry';
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
    $foto   = Pedigree\Utility::uploadPicture(0);
    $sql    = 'UPDATE ' . $GLOBALS['xoopsDB']->prefix($table) . " SET foto='" . $GLOBALS['xoopsDB']->escape($foto) . "' WHERE id='{$dogid}'";
    $GLOBALS['xoopsDB']->queryF($sql);

    $ch = 1;
}

//owner
//lastname
if (\Xmf\Request::hasVar('pnamel', 'POST')) {
    //    $curval = $_POST['curvalnamel'];
    //    $sql    = 'UPDATE ' . $GLOBALS['xoopsDB']->prefix($table) . ' SET ' . $field . "='" . $_POST['pnamel'] . "' WHERE id='" . $dogid . "'";
    $curval = Request::getString('curvalnamel', '', 'POST');
    $pnamel = Request::getString('pnamel', '', 'POST');
    $sql    = 'UPDATE ' . $GLOBALS['xoopsDB']->prefix($table) . ' SET ' . $field . "='" . $GLOBALS['xoopsDB']->escape($pnamel) . "' WHERE id='" . $dogid . "'";
    $GLOBALS['xoopsDB']->queryF($sql);
    $chow = 1;
}
//firstname
if (\Xmf\Request::hasVar('pnamef', 'POST')) {
    //    $curval = $_POST['curvalnamef'];
    //    $sql    = 'UPDATE ' . $GLOBALS['xoopsDB']->prefix($table) . ' SET ' . $field . "='" . $_POST['pnamef'] . "' WHERE id='" . $dogid . "'";
    $curval = Request::getString('curvalnamef', '', 'POST');
    $pnamel = Request::getString('pnamef', '', 'POST');
    $sql    = 'UPDATE ' . $GLOBALS['xoopsDB']->prefix($table) . ' SET ' . $field . "='" . $GLOBALS['xoopsDB']->escape($pnamef) . "' WHERE id='" . $dogid . "'";
    $GLOBALS['xoopsDB']->query($sql);
    $chow = 1;
}
//streetname
if (\Xmf\Request::hasVar('street', 'POST')) {
    //    $curval = $_POST['curvalstreet'];
    //    $sql    = 'UPDATE ' . $GLOBALS['xoopsDB']->prefix($table) . ' SET ' . $field . "='" . $_POST['street'] . "' WHERE id='" . $dogid . "'";
    $curval = Request::getString('curvalstreet', '', 'POST');
    $street = $GLOBALS['xoopsDB']->escape(Request::getString('street', '', 'POST'));
    $sql    = 'UPDATE ' . $GLOBALS['xoopsDB']->prefix($table) . ' SET ' . $field . "='" . $street . "' WHERE id='" . $dogid . "'";
    $GLOBALS['xoopsDB']->queryF($sql);
    $chow = 1;
}
//housenumber
if (\Xmf\Request::hasVar('housenumber', 'POST')) {
    //    $curval = $_POST['curvalhousenumber'];
    //    $sql    = 'UPDATE ' . $GLOBALS['xoopsDB']->prefix($table) . ' SET ' . $field . "='" . $_POST['housenumber'] . "' WHERE id='" . $dogid . "'";
    $curval      = Request::getString('curvalhousenumber', '', 'POST');
    $housenumber = $GLOBALS['xoopsDB']->escape(Request::getString('housenumber', '', 'POST'));
    $sql         = 'UPDATE ' . $GLOBALS['xoopsDB']->prefix($table) . ' SET ' . $field . "='" . $housenumber . "' WHERE id='" . $dogid . "'";
    $GLOBALS['xoopsDB']->queryF($sql);
    $chow = 1;
}
//postcode
if (\Xmf\Request::hasVar('postcode', 'POST')) {
    //    $curval = $_POST['curvalpostcode'];
    //    $sql    = 'UPDATE ' . $GLOBALS['xoopsDB']->prefix($table) . ' SET ' . $field . "='" . $_POST['postcode'] . "' WHERE id='" . $dogid . "'";
    $curval   = Request::getString('curvalpostcode', '', 'POST');
    $postcode = $GLOBALS['xoopsDB']->escape(Request::getString('postcode', '', 'POST'));
    $sql      = 'UPDATE ' . $GLOBALS['xoopsDB']->prefix($table) . ' SET ' . $field . "='" . $postcode . "' WHERE id='" . $dogid . "'";
    $GLOBALS['xoopsDB']->query($sql);
    $chow = 1;
}
//city
if (\Xmf\Request::hasVar('city', 'POST')) {
    //    $curval = $_POST['curvalcity'];
    //    $sql    = 'UPDATE ' . $GLOBALS['xoopsDB']->prefix($table) . ' SET ' . $field . "='" . $_POST['city'] . "' WHERE id='" . $dogid . "'";
    $curval = Request::getString('curvalcity', '', 'POST');
    $city   = $GLOBALS['xoopsDB']->escape(Request::getString('city', '', 'POST'));
    $sql    = 'UPDATE ' . $GLOBALS['xoopsDB']->prefix($table) . ' SET ' . $field . "='" . $city . "' WHERE id='" . $dogid . "'";
    $GLOBALS['xoopsDB']->query($sql);
    $chow = 1;
}
//phonenumber
if (\Xmf\Request::hasVar('phonenumber', 'POST')) {
    //    $curval = $_POST['curvalphonenumber'];
    //    $sql    = 'UPDATE ' . $GLOBALS['xoopsDB']->prefix($table) . ' SET ' . $field . "='" . $_POST['phonenumber'] . "' WHERE id='" . $dogid . "'";
    $curval      = Request::getString('curvalphonenumber', '', 'POST');
    $phonenumber = $GLOBALS['xoopsDB']->escape(Request::getString('phonenumber', '', 'POST'));
    $sql         = 'UPDATE ' . $GLOBALS['xoopsDB']->prefix($table) . ' SET ' . $field . "='" . $phonenumber . "' WHERE id='" . $dogid . "'";
    $GLOBALS['xoopsDB']->query($sql);
    $chow = 1;
}
//email
if (\Xmf\Request::hasVar('email', 'POST')) {
    //    $curval = $_POST['curvalemail'];
    //    $sql    = 'UPDATE ' . $GLOBALS['xoopsDB']->prefix($table) . ' SET ' . $field . "='" . $_POST['email'] . "' WHERE id='" . $dogid . "'";
    $curval = Request::getString('curvalemail', '', 'POST');
    $email  = $GLOBALS['xoopsDB']->escape(Request::getEmail('email', '', 'POST'));
    $sql    = 'UPDATE ' . $GLOBALS['xoopsDB']->prefix($table) . ' SET ' . $field . "='" . $email . "' WHERE id='" . $dogid . "'";
    $GLOBALS['xoopsDB']->query($sql);
    $chow = 1;
}
//website
if (\Xmf\Request::hasVar('web', 'POST')) {
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
require_once XOOPS_ROOT_PATH . '/footer.php';
