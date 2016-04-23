<?php
// -------------------------------------------------------------------------

require_once dirname(dirname(__DIR__)) . '/mainfile.php';
include_once __DIR__ . '/include/config.php';

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

//get module configuration
$module_handler = xoops_getHandler('module');
$module         = $module_handler->getByDirname("pedigree");
$config_handler = xoops_getHandler('config');
$moduleConfig   = $config_handler->getConfigsByCat(0, $module->getVar('mid'));

$myts = &MyTextSanitizer::getInstance();

$fld = $_GET['fld'];
$id  = $_GET['id'];
//query (find values for this dog (and format them))
$queryString = "SELECT * from " . $xoopsDB->prefix("pedigree_tree") . " WHERE ID=" . $id;
$result      = $xoopsDB->query($queryString);

while ($row = $xoopsDB->fetchArray($result)) {
    //ID
    $id = $row['ID'];
    //name
    $naam     = htmlentities(stripslashes($row['NAAM']), ENT_QUOTES);
    $namelink = "<a href=\"dog.php?id=" . $row['ID'] . "\">" . stripslashes($row['NAAM']) . "</a>";
    //owner
    $queryeig = "SELECT ID, lastname, firstname from " . $xoopsDB->prefix("pedigree_owner") . " WHERE ID=" . $row['id_owner'];
    $reseig   = $xoopsDB->query($queryeig);
    while ($roweig = $xoopsDB->fetchArray($reseig)) {
        $eig = "<a href=\"owner.php?ownid=" . $roweig['ID'] . "\">" . $roweig['firstname'] . " " . $roweig['lastname'] . "</a>";
    }
    $curvaleig = $row['id_owner'];
    //breeder
    $queryfok = "SELECT ID, lastname, firstname from " . $xoopsDB->prefix("pedigree_owner") . " WHERE ID=" . $row['id_breeder'];
    $resfok   = $xoopsDB->query($queryfok);
    while ($rowfok = $xoopsDB->fetchArray($resfok)) {
        $fok = "<a href=\"owner.php?ownid=" . $rowfok['ID'] . "\">" . $rowfok['firstname'] . " " . $rowfok['lastname'] . "</a>";
    }
    $curvalfok = $row['id_breeder'];
    //gender
    if ($row['roft'] == '0') {
        $gender = "<img src=\"assets/images/male.gif\"> " . _MA_PEDIGREE_FLD_MALE;
    } else {
        $gender = "<img src=\"assets/images/female.gif\"> " . _MA_PEDIGREE_FLD_FEMA;
    }
    $curvalroft = $row['roft'];
    //Sire
    if ($row['father'] != 0) {
        $querysire = "SELECT NAAM from " . $xoopsDB->prefix("pedigree_tree") . " WHERE ID=" . $row['father'];
        $ressire   = $xoopsDB->query($querysire);
        while ($rowsire = $xoopsDB->fetchArray($ressire)) {
            $sire = "<img src=\"assets/images/male.gif\"><a href=\"dog.php?id=" . $row['father'] . "\">" . stripslashes($rowsire['NAAM']) . "</a>";
        }
    }
    //Dam
    if ($row['mother'] != 0) {
        $querydam = "SELECT NAAM from " . $xoopsDB->prefix("pedigree_tree") . " WHERE ID=" . $row['mother'];
        $resdam   = $xoopsDB->query($querydam);
        while ($rowdam = $xoopsDB->fetchArray($resdam)) {
            $dam = "<img src=\"assets/images/female.gif\"><a href=\"dog.php?id=" . $row['mother'] . "\">" . stripslashes($rowdam['NAAM']) . "</a>";
        }
    }
    //picture
    $picture = '';
    if ($row['foto'] != "") {
        $picture = "<img src=" . PEDIGREE_UPLOAD_URL . "/images/thumbnails/" . $row['foto'] . "_400.jpeg>";
        $foto    = $row['foto'];
    } else {
        $foto = "";
    }
    //user who entered the info
    $dbuser = $row['user'];
}

//create form
include XOOPS_ROOT_PATH . "/class/xoopsformloader.php";
$form = new XoopsThemeForm($naam, 'updatedata', 'updatepage.php', 'POST');
$form->setExtra("enctype='multipart/form-data'");
//hidden value current record owner
$form->addElement(new XoopsFormHidden('dbuser', $dbuser));
//hidden value dog ID
$form->addElement(new XoopsFormHidden('dogid', $id));
$form->addElement(new XoopsFormHidden('curname', $naam));
$form->addElement(new XoopsFormHiddenToken($name = 'XOOPS_TOKEN_REQUEST', $timeout = 360));
//name
if ($fld == "nm" || $fld == "all") {
    $form->addElement(new XoopsFormText("<b>" . _MA_PEDIGREE_FLD_NAME . "</b>", 'NAAM', $size = 50, $maxsize = 255, $value = $naam));
    $form->addElement(new XoopsFormLabel(_MA_PEDIGREE_EXPLAIN, _MA_PEDIGREE_FLD_NAME_EX));
    $form->addElement(new XoopsFormHidden('dbtable', 'pedigree_tree'));
    $form->addElement(new XoopsFormHidden('dbfield', 'NAAM'));
    $form->addElement(new XoopsFormHidden('curvalname', $naam));
} else //owner
{
    if ($fld == "ow" || $fld == "all") {
        $owner_select = new XoopsFormSelect("<b>" . _MA_PEDIGREE_FLD_OWNE . "</b>", $name = "id_owner", $value = null, $size = 1, $multiple = false);
        $queryeig     = "SELECT ID, lastname, firstname from " . $xoopsDB->prefix("pedigree_owner") . " ORDER BY `lastname`";
        $reseig       = $xoopsDB->query($queryeig);
        $owner_select->addOption(0, $name = _MA_PEDIGREE_UNKNOWN);
        while ($roweig = $xoopsDB->fetchArray($reseig)) {
            $owner_select->addOption($roweig['ID'], $name = $roweig['lastname'] . ", " . $roweig['firstname']);
        }
        $form->addElement($owner_select);
        $form->addElement(new XoopsFormLabel(_MA_PEDIGREE_EXPLAIN, _MA_PEDIGREE_FLD_OWNE_EX));
        $form->addElement(new XoopsFormHidden('dbtable', 'pedigree_tree'));
        $form->addElement(new XoopsFormHidden('dbfield', 'id_owner'));
        $form->addElement(new XoopsFormHidden('curvaleig', $curvaleig));
    }
}

//breeder
if ($fld == "br" || $fld == "all") {
    $breeder_select = new XoopsFormSelect("<b>" . _MA_PEDIGREE_FLD_BREE . "</b>", $name = "id_breeder", $value = null, $size = 1, $multiple = false);
    $queryfok       = "SELECT ID, lastname, firstname from " . $xoopsDB->prefix("pedigree_owner") . " ORDER BY `lastname`";
    $resfok         = $xoopsDB->query($queryfok);
    $breeder_select->addOption(0, $name = _MA_PEDIGREE_UNKNOWN);
    while ($rowfok = $xoopsDB->fetchArray($resfok)) {
        $breeder_select->addOption($rowfok['ID'], $name = $rowfok['lastname'] . ", " . $rowfok['firstname']);
    }
    $form->addElement($breeder_select);
    $form->addElement(new XoopsFormLabel(_MA_PEDIGREE_EXPLAIN, _MA_PEDIGREE_FLD_BREE_EX));
    $form->addElement(new XoopsFormHidden('dbtable', 'pedigree_tree'));
    $form->addElement(new XoopsFormHidden('dbfield', 'id_breeder'));
    $form->addElement(new XoopsFormHidden('curvalfok', $curvalfok));
}

//gender
if ($fld == "sx" || $fld == "all") {
    $gender_radio = new XoopsFormRadio("<b>" . _MA_PEDIGREE_FLD_GEND . "</b>", 'roft', $value = null);
    $gender_radio->addOptionArray(array('0' => _MA_PEDIGREE_FLD_MALE, '1' => _MA_PEDIGREE_FLD_FEMA));
    $form->addElement($gender_radio);
    $form->addElement(new XoopsFormLabel(_MA_PEDIGREE_EXPLAIN, _MA_PEDIGREE_FLD_GEND_EX));
    $form->addElement(new XoopsFormHidden('dbtable', 'pedigree_tree'));
    $form->addElement(new XoopsFormHidden('dbfield', 'roft'));
    $form->addElement(new XoopsFormHidden('curvalroft', $curvalroft));
}

//picture
if ($fld == "pc" || $fld == "all") {
    $form->addElement(new XoopsFormLabel('Picture', $picture));
    $form->setExtra("enctype='multipart/form-data'");
    $img_box = new XoopsFormFile("Image", "photo", 1024000);
    $img_box->setExtra("size ='50'");
    $form->addElement($img_box);
    $form->addElement(new XoopsFormLabel(_MA_PEDIGREE_EXPLAIN, strtr(_MA_PEDIGREE_FLD_PICT_EX, array('[animalType]' => $moduleConfig['animalType']))));
    $form->addElement(new XoopsFormHidden('dbtable', 'pedigree_tree'));
    $form->addElement(new XoopsFormHidden('dbfield', 'foto'));
    $form->addElement(new XoopsFormHidden('curvalpic', $foto));
}

//create animal object

$a      = (!isset($_GET['id']) ? $a = 1 : $a = $_GET['id']);
$animal = new Animal($a);

//test to find out how many user fields there are..
$fields = $animal->numoffields();

for ($i = 0; $i < count($fields); ++$i) {
    if ($_GET['fld'] == $fields[$i]) {
        $userfield = new Field($fields[$i], $animal->getconfig());
        if ($userfield->active()) {

            $fieldType   = $userfield->getSetting("FieldType");
            $fieldobject = new $fieldType($userfield, $animal);
            $edditable   = $fieldobject->editField();
            $form->addElement($edditable);
            $explain = $userfield->getSetting("FieldExplenation");
            $form->addElement(new XoopsFormLabel(_MA_PEDIGREE_EXPLAIN, $explain));
            $form->addElement(new XoopsFormHidden('dbtable', 'pedigree_tree'));
            $form->addElement(new XoopsFormHidden('dbfield', 'user' . $fields[$i]));
        }
    }
}

//submit button
if ($fld) {
    $form->addElement(new XoopsFormButton('', 'button_id', _MA_PEDIGREE_BUT_SUB, 'submit'));
}
//add data (form) to smarty template
$xoopsTpl->assign("form", $form->render());

//footer
include XOOPS_ROOT_PATH . "/footer.php";
