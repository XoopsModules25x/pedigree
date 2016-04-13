<?php
// -------------------------------------------------------------------------

require_once dirname(dirname(__DIR__)) . '/mainfile.php';
include_once __DIR__ . '/include/config.php';

/*
if (file_exists(XOOPS_ROOT_PATH . "/modules/" . $xoopsModule->dirname() . "/language/" . $xoopsConfig['language'] . "/main.php")) {
    require_once XOOPS_ROOT_PATH . "/modules/" . $xoopsModule->dirname() . "/language/" . $xoopsConfig['language'] . "/main.php";
} else {
    include_once XOOPS_ROOT_PATH . "/modules/" . $xoopsModule->dirname() . "/language/english/main.php";
}
*/

xoops_loadLanguage('main', basename(__DIR__));

//needed for generation of pie charts
ob_start();
include(XOOPS_ROOT_PATH . "/modules/" . $xoopsModule->dirname() . "/include/class_eq_pie.php");
require_once(XOOPS_ROOT_PATH . "/modules/" . $xoopsModule->dirname() . "/include/class_field.php");

$xoopsOption['template_main'] = "pedigree_edit.tpl";

include XOOPS_ROOT_PATH . '/header.php';
// Include any common code for this module.
require_once(XOOPS_ROOT_PATH . "/modules/" . $xoopsModule->dirname() . "/include/functions.php");

global $xoopsTpl, $xoopsDB;

//get module configuration
$module_handler =& xoops_gethandler('module');
$module         =& $module_handler->getByDirname("pedigree");
$config_handler =& xoops_gethandler('config');
$moduleConfig   =& $config_handler->getConfigsByCat(0, $module->getVar('mid'));

if (isset($_GET['f'])) {
    if ($_GET['f'] == "save") {
        save();
    }
} else {
    edit();
}

function save()
{
    global $xoopsDB, $moduleConfig;
    $a      = (!isset($_POST['id']) ? $a = '' : $a = $_POST['id']);
    $animal = new Animal($a);
    $fields = $animal->numoffields();
    for ($i = 0; $i < count($fields); ++$i) {
        $userfield = new Field($fields[$i], $animal->getconfig());
        if ($userfield->active()) {
            $currentfield = 'user' . $fields[$i];
            $picturefield = $_FILES[$currentfield]['name'];
            if (empty($picturefield) || $picturefield == "") {
                $newvalue = $_POST['user' . $fields[$i]];
            } else {
                $newvalue = uploadedpict(0);
            }
            $sql = "UPDATE " . $xoopsDB->prefix("pedigree_tree") . " SET user" . $fields[$i] . "='" . $newvalue . "' WHERE ID='" . $a . "'";
            $xoopsDB->queryF($sql);
        }
    }
    $sql = "UPDATE " . $xoopsDB->prefix("pedigree_tree") . " SET NAAM = '" . $_POST['NAAM'] . "', roft = '" . $_POST['roft'] . "' WHERE ID='" . $a . "'";
    $xoopsDB->queryF($sql);
    $picturefield = $_FILES['photo']['name'];
    if (empty($picturefield) || $picturefield == "") {
        //llalalala
    } else {
        $foto = uploadedpict(0);
        $sql  = "UPDATE " . $xoopsDB->prefix("pedigree_tree") . " SET foto='" . $foto . "' WHERE ID='" . $a . "'";
    }
    $xoopsDB->queryF($sql);
    if ($moduleConfig['ownerbreeder'] == '1') {
        $sql = "UPDATE " . $xoopsDB->prefix("pedigree_tree") . " SET id_owner = '" . $_POST['id_owner'] . "', id_breeder = '" . $_POST['id_breeder'] . "' WHERE ID='" . $a . "'";
        $xoopsDB->queryF($sql);
    }
    redirect_header("dog.php?id=" . $a, 2, "Your changes have been saved");
}

/**
 * @param int $id
 */
function edit($id = 0)
{
    global $xoopsTpl, $xoopsDB, $moduleConfig;
    if (isset($_GET['id'])) {
        $id = $_GET['id'];
    }
    include XOOPS_ROOT_PATH . "/class/xoopsformloader.php";

    $sql    = "SELECT * FROM " . $xoopsDB->prefix("pedigree_tree") . " WHERE ID=" . $id;
    $result = $xoopsDB->query($sql);
    while ($row = $xoopsDB->fetchArray($result)) {
        $form = new XoopsThemeForm('Edit ' . $row['NAAM'], 'dogname', 'edit.php?f=save', 'POST');
        $form->addElement(new XoopsFormHiddenToken($name = 'XOOPS_TOKEN_REQUEST', $timeout = 360));
        $form->addElement(new XoopsFormHidden('id', $id));
        //name
        $naam = htmlentities(stripslashes($row['NAAM']), ENT_QUOTES);
        $form->addElement(new XoopsFormText("<b>" . _MA_PEDIGREE_FLD_NAME . "</b>", 'NAAM', $size = 50, $maxsize = 255, $value = $naam));
        //gender
        $roft         = $row['roft'];
        $gender_radio = new XoopsFormRadio("<b>" . _MA_PEDIGREE_FLD_GEND . "</b>", 'roft', $value = $roft);
        $gender_radio->addOptionArray(
            array('0' => strtr(_MA_PEDIGREE_FLD_MALE, array('[male]' => $moduleConfig['male'])), '1' => strtr(_MA_PEDIGREE_FLD_FEMA, array('[female]' => $moduleConfig['female'])))
        );
        $form->addElement($gender_radio);
        //father
        $sql       = "SELECT * from " . $xoopsDB->prefix("pedigree_tree") . " WHERE ID='" . $row['father'] . "'";
        $resfather = $xoopsDB->query($sql);
        $numfields = $xoopsDB->getRowsNum($resfather);
        if (!$numfields == "0") {
            while ($rowfetch = $xoopsDB->fetchArray($resfather)) {
                $form->addElement(
                    new XoopsFormLabel(
                        "<b>" . strtr(_MA_PEDIGREE_FLD_FATH, array('[father]' => $moduleConfig['father'])) . "</b>",
                        "<img src=\"assets/images/male.gif\"><a href=\"seldog.php?curval=" . $row['ID'] . "&gend=1&letter=a\">" . $rowfetch['NAAM'] . "</a>"
                    )
                );
            }
        } else {
            $form->addElement(
                new XoopsFormLabel(
                    "<b>" . strtr(_MA_PEDIGREE_FLD_FATH, array('[father]' => $moduleConfig['father'])) . "</b>",
                    "<img src=\"assets/images/male.gif\"><a href=\"seldog.php?curval=" . $row['ID'] . "&gend=1&letter=a\">Unknown</a>"
                )
            );
        }
        //mother
        $sql       = "SELECT * from " . $xoopsDB->prefix("pedigree_tree") . " WHERE ID='" . $row['mother'] . "'";
        $resmother = $xoopsDB->query($sql);
        $numfields = $xoopsDB->getRowsNum($resmother);
        if (!$numfields == "0") {
            while ($rowfetch = $xoopsDB->fetchArray($resmother)) {
                $form->addElement(
                    new XoopsFormLabel(
                        "<b>" . strtr(_MA_PEDIGREE_FLD_MOTH, array('[mother]' => $moduleConfig['mother'])) . "</b>",
                        "<img src=\"assets/images/female.gif\"><a href=\"seldog.php?curval=" . $row['ID'] . "&gend=0&letter=a\">" . $rowfetch['NAAM'] . "</a>"
                    )
                );
            }
        } else {
            $form->addElement(
                new XoopsFormLabel(
                    "<b>" . strtr(_MA_PEDIGREE_FLD_MOTH, array('[mother]' => $moduleConfig['mother'])) . "</b>",
                    "<img src=\"assets/images/female.gif\"><a href=\"seldog.php?curval=" . $row['ID'] . "&gend=0&letter=a\">Unknown</a>"
                )
            );
        }
        //owner/breeder
        if ($moduleConfig['ownerbreeder'] == '1') {
            $owner_select = new XoopsFormSelect("<b>" . _MA_PEDIGREE_FLD_OWNE . "</b>", $name = "id_owner", $value = $row['id_owner'], $size = 1, $multiple = false);
            $queryeig     = "SELECT ID, lastname, firstname from " . $xoopsDB->prefix("pedigree_owner") . " ORDER BY \"lastname\"";
            $reseig       = $xoopsDB->query($queryeig);
            $owner_select->addOption(0, $name = _MA_PEDIGREE_UNKNOWN, $disabled = false);
            while ($roweig = $xoopsDB->fetchArray($reseig)) {
                $owner_select->addOption($roweig['ID'], $name = $roweig['lastname'] . ", " . $roweig['firstname'], $disabled = false);
            }
            $form->addElement($owner_select);
            //breeder
            $breeder_select = new XoopsFormSelect("<b>" . _MA_PEDIGREE_FLD_BREE . "</b>", $name = "id_breeder", $value = $row['id_breeder'], $size = 1, $multiple = false);
            $queryfok       = "SELECT ID, lastname, firstname from " . $xoopsDB->prefix("pedigree_owner") . " ORDER BY \"lastname\"";
            $resfok         = $xoopsDB->query($queryfok);
            $breeder_select->addOption(0, $name = _MA_PEDIGREE_UNKNOWN, $disabled = false);
            while ($rowfok = $xoopsDB->fetchArray($resfok)) {
                $breeder_select->addOption($rowfok['ID'], $name = $rowfok['lastname'] . ", " . $rowfok['firstname'], $disabled = false);
            }
            $form->addElement($breeder_select);
        }
        //picture
        if ($row['foto'] != "") {
            $picture = "<img src=" . PEDIGREE_UPLOAD_URL . "/images/thumbnails" . $row['foto'] . "_400.jpeg>";
            $form->addElement(new XoopsFormLabel('<b>Picture</b>', $picture));
        } else {
            $picture = "";
        }
        $form->setExtra("enctype='multipart/form-data'");
        $img_box = new XoopsFormFile("<b>Image</b>", "photo", 1024000);
        $img_box->setExtra("size ='50'");
        $form->addElement($img_box);
        //userfields
        //create animal object
        $animal = new Animal($id);
        //test to find out how many user fields there are..
        $fields = $animal->numoffields();
        for ($i = 0; $i < count($fields); ++$i) {
            $userfield = new Field($fields[$i], $animal->getconfig());
            if ($userfield->active()) {
                $fieldType     = $userfield->getSetting("FieldType");
                $fieldobject   = new $fieldType($userfield, $animal);
                $edditable[$i] = $fieldobject->editField();
                $form->addElement($edditable[$i]);
            }
        }

    }
    $form->addElement(new XoopsFormButton('', 'button_id', _MA_PEDIGREE_BUT_SUB, 'submit'));
    $xoopsTpl->assign("form", $form->render());
}

//comments and footer
include XOOPS_ROOT_PATH . "/footer.php";
