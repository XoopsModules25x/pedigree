<?php
// -------------------------------------------------------------------------

use Xmf\Request;
use XoopsModules\Pedigree;


//require_once  dirname(dirname(__DIR__)) . '/mainfile.php';
require_once __DIR__ . '/header.php';
$moduleDirName = basename(__DIR__);
xoops_loadLanguage('main', $moduleDirName);
// Include any common code for this module.
require_once XOOPS_ROOT_PATH . '/modules/' . $moduleDirName . '/include/common.php';

$GLOBALS['xoopsOption']['template_main'] = 'pedigree_update.tpl';
require_once XOOPS_ROOT_PATH . '/header.php';

$xoopsTpl->assign('page_title', 'Pedigree database - Update details');

//check for access
$xoopsModule = XoopsModule::getByDirname($moduleDirName);
if (empty($GLOBALS['xoopsUser']) || !($GLOBALS['xoopsUser'] instanceof \XoopsUser)) {
    redirect_header('javascript:history.go(-1)', 3, _NOPERM . '<br>' . _MA_PEDIGREE_REGIST);
}
// ( $xoopsUser->isAdmin($xoopsModule->mid() ) )

global $xoopsTpl;
global $xoopsDB;
global $xoopsModuleConfig;

//get module configuration
/*
$moduleHandler = xoops_getHandler('module');
$module        = $moduleHandler->getByDirname($moduleDirName);
$configHandler = xoops_getHandler('config');
$moduleConfig  = $configHandler->getConfigsByCat(0, $module->getVar('mid'));
*/
$helper     = Pedigree\Helper::getInstance(false);
$moduleConfig = $helper->getConfig();

$myts = \MyTextSanitizer::getInstance();

$fld = Request::getString('fld', '', 'GET');
$id  = Request::getInt('id', 0, 'GET');
/*
$fld = $_GET['fld'];
$id  = $_GET['id'];
*/

//query (find values for this dog (and format them))
$sql = 'SELECT * FROM ' . $GLOBALS['xoopsDB']->prefix('pedigree_registry') . ' WHERE id=' . $id;
$result      = $GLOBALS['xoopsDB']->query($sql);

while (false !== ($row = $GLOBALS['xoopsDB']->fetchArray($result))) {
    //ID
    $id = $row['id'];
    //name
    $pname     = htmlentities(stripslashes($row['pname']), ENT_QUOTES);
    $namelink = '<a href="dog.php?id=' . $row['id'] . '">' . stripslashes($row['pname']) . '</a>';
    //owner
    $queryeig = 'SELECT id, lastname, firstname FROM ' . $GLOBALS['xoopsDB']->prefix('pedigree_owner') . ' WHERE id=' . $row['id_owner'];
    $reseig   = $GLOBALS['xoopsDB']->query($queryeig);
    while (false !== ($roweig = $GLOBALS['xoopsDB']->fetchArray($reseig))) {
        $eig = '<a href="owner.php?ownid=' . $roweig['id'] . '">' . $roweig['firstname'] . ' ' . $roweig['lastname'] . '</a>';
    }
    $curvaleig = $row['id_owner'];
    //breeder
    $queryfok = 'SELECT id, lastname, firstname FROM ' . $GLOBALS['xoopsDB']->prefix('pedigree_owner') . ' WHERE id=' . $row['id_breeder'];
    $resfok   = $GLOBALS['xoopsDB']->query($queryfok);
    while (false !== ($rowfok = $GLOBALS['xoopsDB']->fetchArray($resfok))) {
        $fok = '<a href="owner.php?ownid=' . $rowfok['id'] . '">' . $rowfok['firstname'] . ' ' . $rowfok['lastname'] . '</a>';
    }
    $curvalfok = $row['id_breeder'];
    //gender
    if ('0' == $row['roft']) {
        $gender = '<img src="assets/images/male.gif"> ' . _MA_PEDIGREE_FLD_MALE;
    } else {
        $gender = '<img src="assets/images/female.gif"> ' . _MA_PEDIGREE_FLD_FEMA;
    }
    $curvalroft = $row['roft'];
    //Sire
    if (0 != $row['father']) {
        $querysire = 'SELECT pname FROM ' . $GLOBALS['xoopsDB']->prefix('pedigree_registry') . ' WHERE id=' . $row['father'];
        $ressire   = $GLOBALS['xoopsDB']->query($querysire);
        while (false !== ($rowsire = $GLOBALS['xoopsDB']->fetchArray($ressire))) {
            $sire = '<img src="assets/images/male.gif"><a href="dog.php?id=' . $row['father'] . '">' . stripslashes($rowsire['pname']) . '</a>';
        }
    }
    //Dam
    if (0 != $row['mother']) {
        $querydam = 'SELECT pname FROM ' . $GLOBALS['xoopsDB']->prefix('pedigree_registry') . ' WHERE id=' . $row['mother'];
        $resdam   = $GLOBALS['xoopsDB']->query($querydam);
        while (false !== ($rowdam = $GLOBALS['xoopsDB']->fetchArray($resdam))) {
            $dam = '<img src="assets/images/female.gif"><a href="dog.php?id=' . $row['mother'] . '">' . stripslashes($rowdam['pname']) . '</a>';
        }
    }
    //picture
    $picture = '';
    if ('' != $row['foto']) {
        $picture = '<img src=' . PEDIGREE_UPLOAD_URL . '/images/thumbnails/' . $row['foto'] . '_400.jpeg>';
        $foto    = $row['foto'];
    } else {
        $foto = '';
    }
    //user who entered the info
    $dbuser = $row['user'];
}

//create form
require_once XOOPS_ROOT_PATH . '/class/xoopsformloader.php';
$form = new \XoopsThemeForm($pname, 'updatedata', 'updatepage.php', 'post', true);
$form->setExtra("enctype='multipart/form-data'");
//hidden value current record owner
$form->addElement(new \XoopsFormHidden('dbuser', $dbuser));
//hidden value dog ID
$form->addElement(new \XoopsFormHidden('dogid', $id));
$form->addElement(new \XoopsFormHidden('curname', $pname));
$form->addElement(new \XoopsFormHiddenToken($name = 'XOOPS_TOKEN_REQUEST', $timeout = 360));
//name
if ('nm' === $fld || 'all' === $fld) {
    $form->addElement(new \XoopsFormText('<b>' . _MA_PEDIGREE_FLD_NAME . '</b>', 'pname', $size = 50, $maxsize = 255, $value = $pname));
    $form->addElement(new \XoopsFormLabel(_MA_PEDIGREE_EXPLAIN, _MA_PEDIGREE_FLD_NAME_EX));
    $form->addElement(new \XoopsFormHidden('dbtable', 'pedigree_registry'));
    $form->addElement(new \XoopsFormHidden('dbfield', 'pname'));
    $form->addElement(new \XoopsFormHidden('curvalname', $pname));
} else {
    //owner
    if ('ow' === $fld || 'all' === $fld) {
        $owner_select = new \XoopsFormSelect('<b>' . _MA_PEDIGREE_FLD_OWNE . '</b>', $name = 'id_owner', $value = null, $size = 1, $multiple = false);
        $queryeig     = 'SELECT id, lastname, firstname FROM ' . $GLOBALS['xoopsDB']->prefix('pedigree_owner') . ' ORDER BY `lastname`';
        $reseig       = $GLOBALS['xoopsDB']->query($queryeig);
        $owner_select->addOption(0, $name = _MA_PEDIGREE_UNKNOWN);
        while (false !== ($roweig = $GLOBALS['xoopsDB']->fetchArray($reseig))) {
            $owner_select->addOption($roweig['id'], $name = $roweig['lastname'] . ', ' . $roweig['firstname']);
        }
        $form->addElement($owner_select);
        $form->addElement(new \XoopsFormLabel(_MA_PEDIGREE_EXPLAIN, _MA_PEDIGREE_FLD_OWNE_EX));
        $form->addElement(new \XoopsFormHidden('dbtable', 'pedigree_registry'));
        $form->addElement(new \XoopsFormHidden('dbfield', 'id_owner'));
        $form->addElement(new \XoopsFormHidden('curvaleig', $curvaleig));
    }
}

//breeder
if ('br' === $fld || 'all' === $fld) {
    $breeder_select = new \XoopsFormSelect('<b>' . _MA_PEDIGREE_FLD_BREE . '</b>', $name = 'id_breeder', $value = null, $size = 1, $multiple = false);
    $queryfok       = 'SELECT id, lastname, firstname FROM ' . $GLOBALS['xoopsDB']->prefix('pedigree_owner') . ' ORDER BY `lastname`';
    $resfok         = $GLOBALS['xoopsDB']->query($queryfok);
    $breeder_select->addOption(0, $name = _MA_PEDIGREE_UNKNOWN);
    while (false !== ($rowfok = $GLOBALS['xoopsDB']->fetchArray($resfok))) {
        $breeder_select->addOption($rowfok['id'], $name = $rowfok['lastname'] . ', ' . $rowfok['firstname']);
    }
    $form->addElement($breeder_select);
    $form->addElement(new \XoopsFormLabel(_MA_PEDIGREE_EXPLAIN, _MA_PEDIGREE_FLD_BREE_EX));
    $form->addElement(new \XoopsFormHidden('dbtable', 'pedigree_registry'));
    $form->addElement(new \XoopsFormHidden('dbfield', 'id_breeder'));
    $form->addElement(new \XoopsFormHidden('curvalfok', $curvalfok));
}

//gender
if ('sx' === $fld || 'all' === $fld) {
    $gender_radio = new \XoopsFormRadio('<b>' . _MA_PEDIGREE_FLD_GEND . '</b>', 'roft', $value = null);
    $gender_radio->addOptionArray(['0' => _MA_PEDIGREE_FLD_MALE, '1' => _MA_PEDIGREE_FLD_FEMA]);
    $form->addElement($gender_radio);
    $form->addElement(new \XoopsFormLabel(_MA_PEDIGREE_EXPLAIN, _MA_PEDIGREE_FLD_GEND_EX));
    $form->addElement(new \XoopsFormHidden('dbtable', 'pedigree_registry'));
    $form->addElement(new \XoopsFormHidden('dbfield', 'roft'));
    $form->addElement(new \XoopsFormHidden('curvalroft', $curvalroft));
}

//picture
if ('pc' === $fld || 'all' === $fld) {
    $form->addElement(new \XoopsFormLabel('Picture', $picture));
    $form->setExtra("enctype='multipart/form-data'");
    $img_box = new \XoopsFormFile('Image', 'photo', 1024000);
    $img_box->setExtra("size ='50'");
    $form->addElement($img_box);
    $form->addElement(new \XoopsFormLabel(_MA_PEDIGREE_EXPLAIN, strtr(_MA_PEDIGREE_FLD_PICT_EX, ['[animalType]' => $moduleConfig['animalType']])));
    $form->addElement(new \XoopsFormHidden('dbtable', 'pedigree_registry'));
    $form->addElement(new \XoopsFormHidden('dbfield', 'foto'));
    $form->addElement(new \XoopsFormHidden('curvalpic', $foto));
}

//create animal object

$a      = (!isset($_GET['id']) ? $a = 1 : $a = $_GET['id']);
$animal = new Pedigree\Animal($a);

//test to find out how many user fields there are..
$fields = $animal->getNumOfFields();

foreach ($fields as $i => $iValue) {
    if ($_GET['fld'] == $iValue) {
        $userField = new Pedigree\Field($fields[$i], $animal->getConfig());
        if ($userField->isActive()) {
            $fieldType   = $userField->getSetting('FieldType');
            $fieldObject = new $fieldType($userField, $animal);
            $edditable   = $fieldObject->editField();
            $form->addElement($edditable);
            $explain = $userField->getSetting('FieldExplanation');
            $form->addElement(new \XoopsFormLabel(_MA_PEDIGREE_EXPLAIN, $explain));
            $form->addElement(new \XoopsFormHidden('dbtable', 'pedigree_registry'));
            $form->addElement(new \XoopsFormHidden('dbfield', 'user' . $iValue));
        }
    }
}

//submit button
if ($fld) {
    $form->addElement(new \XoopsFormButton('', 'button_id', _MA_PEDIGREE_BUT_SUB, 'submit'));
}
//add data (form) to smarty template
$xoopsTpl->assign('form', $form->render());

//footer
require_once XOOPS_ROOT_PATH . '/footer.php';
