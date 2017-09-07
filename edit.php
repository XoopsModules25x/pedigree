<?php
// -------------------------------------------------------------------------

use Xmf\Request;

//require_once __DIR__ . '/../../mainfile.php';
require_once __DIR__ . '/header.php';
$moduleDirName = basename(__DIR__);
xoops_loadLanguage('main', $moduleDirName);

//needed for generation of pie charts
ob_start();
include XOOPS_ROOT_PATH . '/modules/' . $xoopsModule->dirname() . '/include/class_eq_pie.php';
require_once XOOPS_ROOT_PATH . '/modules/' . $xoopsModule->dirname() . '/include/class_field.php';

$GLOBALS['xoopsOption']['template_main'] = 'pedigree_edit.tpl';

include XOOPS_ROOT_PATH . '/header.php';
// Include any common code for this module.
require_once XOOPS_ROOT_PATH . '/modules/' . $moduleDirName . '/include/common.php';

global $xoopsTpl, $xoopsDB;

//get module configuration
/** @var XoopsModuleHandler $moduleHandler */
$moduleHandler = xoops_getHandler('module');
$module        = $moduleHandler->getByDirname($moduleDirName);
$configHandler = xoops_getHandler('config');
$moduleConfig  = $configHandler->getConfigsByCat(0, $module->getVar('mid'));

if (isset($_GET['f'])) {
    if ($_GET['f'] === 'save') {
        save();
    }
} else {
    edit();
}

function save()
{
    global $xoopsDB, $moduleConfig;
    $a      = (!isset($_POST['id']) ? $a = '' : $a = $_POST['id']);
    $animal = new PedigreeAnimal($a);
    $fields = $animal->getNumOfFields();
    for ($i = 0, $iMax = count($fields); $i < $iMax; ++$i) {
        $userField = new Field($fields[$i], $animal->getConfig());
        if ($userField->isActive()) {
            $currentfield = 'user' . $fields[$i];
            $pictureField = $_FILES[$currentfield]['name'];
            if (empty($pictureField) || $pictureField == '') {
                $newvalue = $_POST['user' . $fields[$i]];
            } else {
                $newvalue = PedigreeUtility::uploadPicture(0);
            }
            $sql = 'UPDATE ' . $GLOBALS['xoopsDB']->prefix('pedigree_tree') . ' SET user' . $fields[$i] . "='" . $GLOBALS['xoopsDB']->escape($newvalue) . "' WHERE id='" . $a . "'";
            $GLOBALS['xoopsDB']->query($sql);
        }
    }
    //    $sql = 'UPDATE ' . $GLOBALS['xoopsDB']->prefix('pedigree_tree') . " SET naam = '" . $_POST['naam'] . "', roft = '" . $_POST['roft'] . "' WHERE id='" . $a . "'";
    $NAAM = Request::getString('naam', '', 'post');
    $roft = Request::getString('roft', '', 'post');
    $sql  = 'UPDATE ' . $GLOBALS['xoopsDB']->prefix('pedigree_tree') . " SET naam = '" . $GLOBALS['xoopsDB']->escape($NAAM) . "', roft = '" . $GLOBALS['xoopsDB']->escape($roft) . "' WHERE id='" . $a . "'";
    $GLOBALS['xoopsDB']->query($sql);
    $pictureField = $_FILES['photo']['name'];
    if (empty($pictureField) || $pictureField == '') {
        //llalalala
    } else {
        $foto = PedigreeUtility::uploadPicture(0);
        //      $sql  = 'UPDATE ' . $GLOBALS['xoopsDB']->prefix('pedigree_tree') . " SET foto='" . $foto . "' WHERE id='" . $a . "'";
        $sql = 'UPDATE ' . $GLOBALS['xoopsDB']->prefix('pedigree_tree') . " SET foto='" . $GLOBALS['xoopsDB']->escape($foto) . "' WHERE id='" . $a . "'";
    }
    $GLOBALS['xoopsDB']->query($sql);
    if ($moduleConfig['ownerbreeder'] == '1') {
        //      $sql = 'UPDATE ' . $GLOBALS['xoopsDB']->prefix('pedigree_tree') . " SET id_owner = '" . $_POST['id_owner'] . "', id_breeder = '" . $_POST['id_breeder'] . "' WHERE id='" . $a . "'";
        $sql = 'UPDATE ' . $GLOBALS['xoopsDB']->prefix('pedigree_tree') . " SET id_owner = '" . Request::getInt('id_owner', 0, 'post') . "', id_breeder = '" . Request::getInt('id_breeder', 0, 'post') . "' WHERE id='" . $a . "'";
        $GLOBALS['xoopsDB']->query($sql);
    }
    redirect_header('dog.php?id=' . $a, 2, 'Your changes have been saved');
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
    include XOOPS_ROOT_PATH . '/class/xoopsformloader.php';

    $sql    = 'SELECT * FROM ' . $GLOBALS['xoopsDB']->prefix('pedigree_tree') . ' WHERE id=' . $id;
    $result = $GLOBALS['xoopsDB']->query($sql);
    while (false !== ($row = $GLOBALS['xoopsDB']->fetchArray($result))) {
        $form = new XoopsThemeForm('Edit ' . $row['naam'], 'dogname', 'edit.php?f=save', 'post', true);
        $form->addElement(new XoopsFormHiddenToken($name = 'XOOPS_TOKEN_REQUEST', $timeout = 360));
        $form->addElement(new XoopsFormHidden('id', $id));
        //name
        $naam = htmlentities(stripslashes($row['naam']), ENT_QUOTES);
        $form->addElement(new XoopsFormText('<b>' . _MA_PEDIGREE_FLD_NAME . '</b>', 'naam', $size = 50, $maxsize = 255, $value = $naam));
        //gender
        $roft         = $row['roft'];
        $gender_radio = new XoopsFormRadio('<b>' . _MA_PEDIGREE_FLD_GEND . '</b>', 'roft', $value = $roft);
        $gender_radio->addOptionArray([
                                          '0' => strtr(_MA_PEDIGREE_FLD_MALE, ['[male]' => $moduleConfig['male']]),
                                          '1' => strtr(_MA_PEDIGREE_FLD_FEMA, ['[female]' => $moduleConfig['female']])
                                      ]);
        $form->addElement($gender_radio);
        //father
        $sql       = 'SELECT * FROM ' . $GLOBALS['xoopsDB']->prefix('pedigree_tree') . " WHERE id='" . $row['father'] . "'";
        $resfather = $GLOBALS['xoopsDB']->query($sql);
        $numfields = mysqli_num_rows($resfather);
        if (!$numfields == '0') {
            while (false !== ($rowfetch = $GLOBALS['xoopsDB']->fetchArray($resfather))) {
                $form->addElement(new XoopsFormLabel('<b>' . strtr(_MA_PEDIGREE_FLD_FATH, ['[father]' => $moduleConfig['father']]) . '</b>', '<img src="assets/images/male.gif"><a href="seldog.php?curval=' . $row['id'] . '&gend=0&letter=a">' . $rowfetch['naam'] . '</a>'));
            }
        } else {
            $form->addElement(new XoopsFormLabel('<b>' . strtr(_MA_PEDIGREE_FLD_FATH, ['[father]' => $moduleConfig['father']]) . '</b>', '<img src="assets/images/male.gif"><a href="seldog.php?curval=' . $row['id'] . '&gend=0&letter=a">Unknown</a>'));
        }
        //mother
        $sql       = 'SELECT * FROM ' . $GLOBALS['xoopsDB']->prefix('pedigree_tree') . " WHERE id='" . $row['mother'] . "'";
        $resmother = $GLOBALS['xoopsDB']->query($sql);
        $numfields = mysqli_num_rows($resmother);
        if (!$numfields == '0') {
            while (false !== ($rowfetch = $GLOBALS['xoopsDB']->fetchArray($resmother))) {
                $form->addElement(new XoopsFormLabel('<b>' . strtr(_MA_PEDIGREE_FLD_MOTH, ['[mother]' => $moduleConfig['mother']]) . '</b>', '<img src="assets/images/female.gif"><a href="seldog.php?curval=' . $row['id'] . '&gend=1&letter=a">' . $rowfetch['naam'] . '</a>'));
            }
        } else {
            $form->addElement(new XoopsFormLabel('<b>' . strtr(_MA_PEDIGREE_FLD_MOTH, ['[mother]' => $moduleConfig['mother']]) . '</b>', '<img src="assets/images/female.gif"><a href="seldog.php?curval=' . $row['id'] . '&gend=1&letter=a">Unknown</a>'));
        }
        //owner/breeder
        if ($moduleConfig['ownerbreeder'] == '1') {
            $owner_select = new XoopsFormSelect('<b>' . _MA_PEDIGREE_FLD_OWNE . '</b>', $name = 'id_owner', $value = $row['id_owner'], $size = 1, $multiple = false);
            $queryeig     = 'SELECT id, lastname, firstname FROM ' . $GLOBALS['xoopsDB']->prefix('pedigree_owner') . ' ORDER BY "lastname"';
            $reseig       = $GLOBALS['xoopsDB']->query($queryeig);
            $owner_select->addOption(0, $name = _MA_PEDIGREE_UNKNOWN);
            while (false !== ($roweig = $GLOBALS['xoopsDB']->fetchArray($reseig))) {
                $owner_select->addOption($roweig['id'], $name = $roweig['lastname'] . ', ' . $roweig['firstname']);
            }
            $form->addElement($owner_select);
            //breeder
            $breeder_select = new XoopsFormSelect('<b>' . _MA_PEDIGREE_FLD_BREE . '</b>', $name = 'id_breeder', $value = $row['id_breeder'], $size = 1, $multiple = false);
            $queryfok       = 'SELECT id, lastname, firstname FROM ' . $GLOBALS['xoopsDB']->prefix('pedigree_owner') . ' ORDER BY "lastname"';
            $resfok         = $GLOBALS['xoopsDB']->query($queryfok);
            $breeder_select->addOption(0, $name = _MA_PEDIGREE_UNKNOWN);
            while (false !== ($rowfok = $GLOBALS['xoopsDB']->fetchArray($resfok))) {
                $breeder_select->addOption($rowfok['id'], $name = $rowfok['lastname'] . ', ' . $rowfok['firstname']);
            }
            $form->addElement($breeder_select);
        }
        //picture
        if ($row['foto'] != '') {
            $picture = '<img src=' . PEDIGREE_UPLOAD_URL . '/images/thumbnails/' . $row['foto'] . '_400.jpeg>';
            $form->addElement(new XoopsFormLabel('<b>Picture</b>', $picture));
        } else {
            $picture = '';
        }
        $form->setExtra("enctype='multipart/form-data'");
        $img_box = new XoopsFormFile('<b>Image</b>', 'photo', 1024000);
        $img_box->setExtra("size ='50'");
        $form->addElement($img_box);
        //userfields
        //create animal object
        $animal = new PedigreeAnimal($id);
        //test to find out how many user fields there are..
        $fields = $animal->getNumOfFields();
        for ($i = 0, $iMax = count($fields); $i < $iMax; ++$i) {
            $userField = new Field($fields[$i], $animal->getConfig());
            if ($userField->isActive()) {
                $fieldType     = $userField->getSetting('FieldType');
                $fieldObject   = new $fieldType($userField, $animal);
                $edditable[$i] = $fieldObject->editField();
                $form->addElement($edditable[$i]);
            }
        }
    }
    $form->addElement(new XoopsFormButton('', 'button_id', _MA_PEDIGREE_BUT_SUB, 'submit'));
    $xoopsTpl->assign('form', $form->render());
}

//comments and footer
include XOOPS_ROOT_PATH . '/footer.php';
