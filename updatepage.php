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
 * Module: Pedigree animal module for XOOPS
 *
 * @package         XoopsModules\Pedigree
 * @copyright       {@link http://sourceforge.net/projects/thmod/ The TXMod XOOPS Project}
 * @copyright       {@link http://sourceforge.net/projects/xoops/ The XOOPS Project}
 * @license         GPL 2.0 or later
 * @author          XOOPS Mod Development Team
 *
 * @todo            move hard coded language strings to language file
 */

use Xmf\Request;
use XoopsModules\Pedigree;
use XoopsModules\Pedigree\Constants;

require_once __DIR__ . '/header.php';
/** @var XoopsModules\Pedigree\Helper $helper */
$helper->loadLanguage('main');

//check for access - only allow registered users
//$xoopsModule = XoopsModule::getByDirname($moduleDirName);
if (empty($GLOBALS['xoopsUser']) || !($GLOBALS['xoopsUser'] instanceof \XoopsUser)) {
    redirect_header('javascript:history.go(-1)', 3, _NOPERM . '<br>' . _MA_PEDIGREE_REGIST);
}

// Include any common code for this module.
require_once $helper->path('include/common.php');

/*
$GLOBALS['xoopsOption']['template_main'] = "pedigree_update.tpl";

include $GLOBALS['xoops']->path('/header.php');
$GLOBALS['xoopsTpl']->assign('page_title', "Pedigree database - Update details");
*/

//check XOOPS security token
if (!$GLOBALS['xoopsSecurity']->check()) {
    $helper->redirect('', Constants::REDIRECT_DELAY_MEDIUM, implode('<br>', $GLOBALS['xoopsSecurity']->getErrors()));
}

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

$ch   = false;
$chow = false;

//name
if (!empty($name)) {
    $curval = Request::getString('curvalname', '', 'POST');
    //    $sql    = 'UPDATE ' . $GLOBALS['xoopsDB']->prefix($table) . " SET {$field}='{$name}' WHERE id='{$dogid}'";
    $sql = 'UPDATE ' . $GLOBALS['xoopsDB']->prefix($table) . ' SET ' . $field . "='" . $GLOBALS['xoopsDB']->escape($name) . "' WHERE id='" . $dogid . "'";
    $GLOBALS['xoopsDB']->queryF($sql);

    $ch = true;
}
//owner
if (isset($_POST['id_owner'])) {
    $curval = Request::getInt('curvaleig', 0, 'POST');
    //    $sql    = 'UPDATE ' . $GLOBALS['xoopsDB']->prefix($table) . " SET {$field}='" . $_POST['id_owner'] . "' WHERE id='{$dogid}'";
    $sql = 'UPDATE ' . $GLOBALS['xoopsDB']->prefix($table) . ' SET ' . $field . "='" . $_POST['id_owner'] . "' WHERE id='" . $dogid . "'";
    $GLOBALS['xoopsDB']->queryF($sql);

    $ch = true;
}
//breeder
if (isset($_POST['id_breeder'])) {
    $curval = Request::getInt('curvalfok', 0, 'POST');
    //    $sql    = 'UPDATE ' . $GLOBALS['xoopsDB']->prefix($table) . " SET {$field}='" . $_POST['id_breeder'] . "' WHERE id='{$dogid}'";
    $id_breeder = Request::getInt('id_breeder', 0, 'post');
    $sql        = 'UPDATE ' . $GLOBALS['xoopsDB']->prefix($table) . ' SET ' . $field . "='" . $id_breeder . "' WHERE id='" . $dogid . "'";
    $GLOBALS['xoopsDB']->queryF($sql);

    $ch = true;
}
//gender
if (!empty($_POST['roft']) || '0' == $_POST['roft']) {
    $curval = $_POST['curvalroft'];
    $curval = Request::getInt('curvalroft', 0, 'POST');
    //    $sql    = 'UPDATE ' . $GLOBALS['xoopsDB']->prefix($table) . " SET {$field}='" . $_POST['roft'] . "' WHERE id='{$dogid}'";
    $roft = Request::getInt('roft', 0, 'post');
    $sql  = 'UPDATE ' . $GLOBALS['xoopsDB']->prefix($table) . ' SET ' . $field . "='" . $roft . "' WHERE id='" . $dogid . "'";
    $GLOBALS['xoopsDB']->queryF($sql);

    $ch = true;
}
//sire - dam
if (isset($_GET['gend'])) {
    $curval = Request::getInt('curval', 0, 'GET');
    $thisid = Request::getInt('thisid', 0, 'GET');
    //$curname = Pedigree\Utility::getName($curval);
    $table = 'pedigree_tree';
    if (0 == Request::getInt('gend', '', 'GET')) {
        $sql = 'UPDATE ' . $GLOBALS['xoopsDB']->prefix($table) . " SET father='" . $thisid . "' WHERE id='{$curval}'";
        $GLOBALS['xoopsDB']->queryF($sql);
    } else {
        $sql = 'UPDATE ' . $GLOBALS['xoopsDB']->prefix($table) . " SET mother='" . $thisid . "' WHERE id='{$curval}'";
        $GLOBALS['xoopsDB']->queryF($sql);
    }

    $ch    = true;
    $dogid = $curval;
}
//picture
if ('foto' === $_POST['dbfield']) {
    $curval = Request::getString('curvalpic', '', 'POST');
    $foto   = Pedigree\Utility::uploadPicture(0);
    $sql    = 'UPDATE ' . $GLOBALS['xoopsDB']->prefix($table) . " SET foto='" . $GLOBALS['xoopsDB']->escape($foto) . "' WHERE id='{$dogid}'";
    $GLOBALS['xoopsDB']->queryF($sql);

    $ch = true;
}

//owner
//lastname
if (isset($_POST['pnamel'])) {
    //    $curval = $_POST['curvalnamel'];
    //    $sql    = 'UPDATE ' . $GLOBALS['xoopsDB']->prefix($table) . ' SET ' . $field . "='" . $_POST['pnamel'] . "' WHERE id='" . $dogid . "'";
    $curval = Request::getString('curvalnamel', '', 'POST');
    $pnamel  = Request::getString('pnamel', '', 'POST');
    $sql    = 'UPDATE ' . $GLOBALS['xoopsDB']->prefix($table) . ' SET ' . $field . "='" . $GLOBALS['xoopsDB']->escape($pnamel) . "' WHERE id='" . $dogid . "'";
    $GLOBALS['xoopsDB']->queryF($sql);
    $chow = true;
}
//firstname
if (isset($_POST['pnamef'])) {
    //    $curval = $_POST['curvalnamef'];
    //    $sql    = 'UPDATE ' . $GLOBALS['xoopsDB']->prefix($table) . ' SET ' . $field . "='" . $_POST['pnamef'] . "' WHERE id='" . $dogid . "'";
    $curval = Request::getString('curvalnamef', '', 'POST');
    $pnamel  = Request::getString('pnamef', '', 'POST');
    $sql    = 'UPDATE ' . $GLOBALS['xoopsDB']->prefix($table) . ' SET ' . $field . "='" . $GLOBALS['xoopsDB']->escape($pnamef) . "' WHERE id='" . $dogid . "'";
    $GLOBALS['xoopsDB']->query($sql);
    $chow = true;
}
//streetname
if (isset($_POST['street'])) {
    //    $curval = $_POST['curvalstreet'];
    //    $sql    = 'UPDATE ' . $GLOBALS['xoopsDB']->prefix($table) . ' SET ' . $field . "='" . $_POST['street'] . "' WHERE id='" . $dogid . "'";
    $curval = Request::getString('curvalstreet', '', 'POST');
    $street = $GLOBALS['xoopsDB']->escape(Request::getString('street', '', 'POST'));
    $sql    = 'UPDATE ' . $GLOBALS['xoopsDB']->prefix($table) . ' SET ' . $field . "='" . $street . "' WHERE id='" . $dogid . "'";
    $GLOBALS['xoopsDB']->queryF($sql);
    $chow = true;
}
//housenumber
if (isset($_POST['housenumber'])) {
    //    $curval = $_POST['curvalhousenumber'];
    //    $sql    = 'UPDATE ' . $GLOBALS['xoopsDB']->prefix($table) . ' SET ' . $field . "='" . $_POST['housenumber'] . "' WHERE id='" . $dogid . "'";
    $curval      = Request::getString('curvalhousenumber', '', 'POST');
    $housenumber = $GLOBALS['xoopsDB']->escape(Request::getString('housenumber', '', 'POST'));
    $sql         = 'UPDATE ' . $GLOBALS['xoopsDB']->prefix($table) . ' SET ' . $field . "='" . $housenumber . "' WHERE id='" . $dogid . "'";
    $GLOBALS['xoopsDB']->queryF($sql);
    $chow = true;
}
//postcode
if (isset($_POST['postcode'])) {
    //    $curval = $_POST['curvalpostcode'];
    //    $sql    = 'UPDATE ' . $GLOBALS['xoopsDB']->prefix($table) . ' SET ' . $field . "='" . $_POST['postcode'] . "' WHERE id='" . $dogid . "'";
    $curval   = Request::getString('curvalpostcode', '', 'POST');
    $postcode = $GLOBALS['xoopsDB']->escape(Request::getString('postcode', '', 'POST'));
    $sql      = 'UPDATE ' . $GLOBALS['xoopsDB']->prefix($table) . ' SET ' . $field . "='" . $postcode . "' WHERE id='" . $dogid . "'";
    $GLOBALS['xoopsDB']->query($sql);
    $chow = true;
}
//city
if (isset($_POST['city'])) {
    //    $curval = $_POST['curvalcity'];
    //    $sql    = 'UPDATE ' . $GLOBALS['xoopsDB']->prefix($table) . ' SET ' . $field . "='" . $_POST['city'] . "' WHERE id='" . $dogid . "'";
    $curval = Request::getString('curvalcity', '', 'POST');
    $city   = $GLOBALS['xoopsDB']->escape(Request::getString('city', '', 'POST'));
    $sql    = 'UPDATE ' . $GLOBALS['xoopsDB']->prefix($table) . ' SET ' . $field . "='" . $city . "' WHERE id='" . $dogid . "'";
    $GLOBALS['xoopsDB']->query($sql);
    $chow = true;
}
//phonenumber
if (isset($_POST['phonenumber'])) {
    //    $curval = $_POST['curvalphonenumber'];
    //    $sql    = 'UPDATE ' . $GLOBALS['xoopsDB']->prefix($table) . ' SET ' . $field . "='" . $_POST['phonenumber'] . "' WHERE id='" . $dogid . "'";
    $curval      = Request::getString('curvalphonenumber', '', 'POST');
    $phonenumber = $GLOBALS['xoopsDB']->escape(Request::getString('phonenumber', '', 'POST'));
    $sql         = 'UPDATE ' . $GLOBALS['xoopsDB']->prefix($table) . ' SET ' . $field . "='" . $phonenumber . "' WHERE id='" . $dogid . "'";
    $GLOBALS['xoopsDB']->query($sql);
    $chow = true;
}
//email
if (isset($_POST['email'])) {
    //    $curval = $_POST['curvalemail'];
    //    $sql    = 'UPDATE ' . $GLOBALS['xoopsDB']->prefix($table) . ' SET ' . $field . "='" . $_POST['email'] . "' WHERE id='" . $dogid . "'";
    $curval = Request::getString('curvalemail', '', 'POST');
    $email  = $GLOBALS['xoopsDB']->escape(Request::getEmail('email', '', 'POST'));
    $sql    = 'UPDATE ' . $GLOBALS['xoopsDB']->prefix($table) . ' SET ' . $field . "='" . $email . "' WHERE id='" . $dogid . "'";
    $GLOBALS['xoopsDB']->query($sql);
    $chow = true;
}
//website
if (isset($_POST['web'])) {
    //    $curval = $_POST['curvalweb'];
    //    $sql    = 'UPDATE ' . $GLOBALS['xoopsDB']->prefix($table) . ' SET ' . $field . "='" . $_POST['web'] . "' WHERE id='" . $dogid . "'";
    $curval = Request::getString('curvalweb', '', 'POST');
    $web    = $GLOBALS['xoopsDB']->escape(Request::getUrl('web', '', 'POST'));
    $sql    = 'UPDATE ' . $GLOBALS['xoopsDB']->prefix($table) . ' SET ' . $field . "='" . $web . "' WHERE id='" . $dogid . "'";
    $GLOBALS['xoopsDB']->query($sql);
    $chow = true;
}

//check for access and completion
if ($ch) {
    $helper->redirect('dog.php?id=' . $dogid, Constants::REDIRECT_DELAY_SHORT, _MD_DATACHANGED);
} elseif ($chow) {
    $helper->redirect('owner.php?ownid=' . $dogid, Constants::REDIRECT_DELAY_SHORT, _MD_DATACHANGED);
} else {
    //@todo REFACTOR THIS CODE - IT IS A POTENTIAL SECURITY RISK
    foreach ($_POST as $key => $values) {
        $filesval .= $key . ' : ' . Request::getString($values) . '<br>';
    }

    $helper->redirect('dog.php?id=' . $dogid, Constants::REDIRECT_DELAY_LONG, 'ERROR!!<br>' . $filesval);
}
//footer
require XOOPS_ROOT_PATH . '/footer.php';
