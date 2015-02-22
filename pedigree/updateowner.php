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

$myts = &MyTextSanitizer::getInstance();

$fld = $_GET['fld'];
$id  = $_GET['id'];
//query (find values for this owner/breeder (and format them))
$queryString = "SELECT * from " . $xoopsDB->prefix("pedigree_owner") . " WHERE ID=" . $id;
$result      = $xoopsDB->query($queryString);

while ($row = $xoopsDB->fetchArray($result)) {
    //ID
    $id = $row['ID'];
    //name
    $naaml    = htmlentities(stripslashes($row['lastname']), ENT_QUOTES);
    $naamf    = htmlentities(stripslashes($row['firstname']), ENT_QUOTES);
    $naam     = $naaml . ", " . $naamf;
    $namelink = "<a href=\"dog.php?id=" . $row['ID'] . "\">" . stripslashes($row['NAAM']) . "</a>";
    //street
    $street = stripslashes($row['streetname']);
    //housenumber
    $housenumber = $row['housenumber'];
    //postcode
    $postcode = $row['postcode'];
    //city
    $city = stripslashes($row['city']);
    //phonenumber
    $phonenumber = stripslashes($row['phonenumber']);
    //email
    $email = stripslashes($row['emailadres']);
    //website
    $web = stripslashes($row['website']);

    //user who entered the info
    $dbuser = $row['user'];
}

//create form
include XOOPS_ROOT_PATH . "/class/xoopsformloader.php";
$form = new XoopsThemeForm($naam, 'updatedata', 'updatepage.php', 'POST');
//hidden value current record owner
$form->addElement(new XoopsFormHidden('dbuser', $dbuser));
//hidden value dog ID
$form->addElement(new XoopsFormHidden('ownerid', $id));
$form->addElement(new XoopsFormHidden('curname', $naam));
$form->addElement(new XoopsFormHiddenToken($name = 'XOOPS_TOKEN_REQUEST', $timeout = 360));
//name last
if ($fld == "nl" || $fld == "all") {
    $form->addElement(new XoopsFormText("<b>" . _MA_PEDIGREE_OWN_LNAME . "</b>", 'naaml', $size = 50, $maxsize = 255, $value = $naaml));
    $form->addElement(new XoopsFormHidden('dbtable', 'owner'));
    $form->addElement(new XoopsFormHidden('dbfield', 'lastname'));
    $form->addElement(new XoopsFormHidden('curvalnamel', $naaml));
} else {
    $form->addElement(new XoopsFormLabel(_MA_PEDIGREE_OWN_LNAME, $naaml));
}
//name first
if ($fld == "nf" || $fld == "all") {
    $form->addElement(new XoopsFormText("<b>" . _MA_PEDIGREE_OWN_FNAME . "</b>", 'naamf', $size = 50, $maxsize = 255, $value = $naamf));
    $form->addElement(new XoopsFormHidden('dbtable', 'owner'));
    $form->addElement(new XoopsFormHidden('dbfield', 'firstname'));
    $form->addElement(new XoopsFormHidden('curvalnamef', $naamf));
} else {
    $form->addElement(new XoopsFormLabel(_MA_PEDIGREE_OWN_FNAME, $naamf));
}
//street
if ($fld == "st" || $fld == "all") {
    $form->addElement(new XoopsFormText("<b>" . _MA_PEDIGREE_FLD_OWN_STR . "</b>", 'street', $size = 50, $maxsize = 255, $value = $street));
    $form->addElement(new XoopsFormHidden('dbtable', 'owner'));
    $form->addElement(new XoopsFormHidden('dbfield', 'streetname'));
    $form->addElement(new XoopsFormHidden('curvalstreet', $street));
} else {
    $form->addElement(new XoopsFormLabel(_MA_PEDIGREE_FLD_OWN_STR, $street));
}
//housenumber
if ($fld == "hn" || $fld == "all") {
    $form->addElement(new XoopsFormText("<b>" . _MA_PEDIGREE_FLD_OWN_HN . "</b>", 'housenumber', $size = 50, $maxsize = 255, $value = $housenumber));
    $form->addElement(new XoopsFormHidden('dbtable', 'owner'));
    $form->addElement(new XoopsFormHidden('dbfield', 'housenumber'));
    $form->addElement(new XoopsFormHidden('curvalhousenumber', $housenumber));
} else {
    $form->addElement(new XoopsFormLabel(_MA_PEDIGREE_FLD_OWN_HN, $housenumber));
}
//postcode
if ($fld == "pc" || $fld == "all") {
    $form->addElement(new XoopsFormText("<b>" . _MA_PEDIGREE_FLD_OWN_PC . "</b>", 'postcode', $size = 50, $maxsize = 255, $value = $postcode));
    $form->addElement(new XoopsFormHidden('dbtable', 'owner'));
    $form->addElement(new XoopsFormHidden('dbfield', 'postcode'));
    $form->addElement(new XoopsFormHidden('curvalpostcode', $postcode));
} else {
    $form->addElement(new XoopsFormLabel(_MA_PEDIGREE_FLD_OWN_PC, $postcode));
}
//city
if ($fld == "ct" || $fld == "all") {
    $form->addElement(new XoopsFormText("<b>" . _MA_PEDIGREE_FLD_OWN_CITY . "</b>", 'city', $size = 50, $maxsize = 255, $value = $city));
    $form->addElement(new XoopsFormHidden('dbtable', 'owner'));
    $form->addElement(new XoopsFormHidden('dbfield', 'city'));
    $form->addElement(new XoopsFormHidden('curvalcity', $city));
} else {
    $form->addElement(new XoopsFormLabel(_MA_PEDIGREE_FLD_OWN_CITY, $city));
}
//phonenumber
if ($fld == "pn" || $fld == "all") {
    $form->addElement(new XoopsFormText("<b>" . _MA_PEDIGREE_FLD_OWN_PN . "</b>", 'phonenumber', $size = 50, $maxsize = 255, $value = $phonenumber));
    $form->addElement(new XoopsFormHidden('dbtable', 'owner'));
    $form->addElement(new XoopsFormHidden('dbfield', 'phonenumber'));
    $form->addElement(new XoopsFormHidden('curvalphonenumber', $phonenumber));
} else {
    $form->addElement(new XoopsFormLabel(_MA_PEDIGREE_FLD_OWN_PN, $phonenumber));
}
//email
if ($fld == "em" || $fld == "all") {
    $form->addElement(new XoopsFormText("<b>" . _MA_PEDIGREE_FLD_OWN_EMAIL . "</b>", 'email', $size = 50, $maxsize = 255, $value = $email));
    $form->addElement(new XoopsFormHidden('dbtable', 'owner'));
    $form->addElement(new XoopsFormHidden('dbfield', 'emailadres'));
    $form->addElement(new XoopsFormHidden('curvalemail', $email));
} else {
    $form->addElement(new XoopsFormLabel(_MA_PEDIGREE_FLD_OWN_EMAIL, $email));
}
//website
if ($fld == "we" || $fld == "all") {
    $form->addElement(new XoopsFormText("<b>" . _MA_PEDIGREE_FLD_OWN_WEB . "</b>", 'web', $size = 50, $maxsize = 255, $value = $web));
    $form->addElement(new XoopsFormHidden('dbtable', 'owner'));
    $form->addElement(new XoopsFormHidden('dbfield', 'website'));
    $form->addElement(new XoopsFormHidden('curvalweb', $web));
} else {
    $form->addElement(new XoopsFormLabel(_MA_PEDIGREE_FLD_OWN_WEB, $web));
}

//submit button
if ($fld) {
    $form->addElement(new XoopsFormButton('', 'button_id', _MA_PEDIGREE_BUT_SUB, 'submit'));
}
//add data (form) to smarty template
$xoopsTpl->assign("form", $form->render());

//footer
include XOOPS_ROOT_PATH . "/footer.php";
