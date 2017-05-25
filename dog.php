<?php
// -------------------------------------------------------------------------

require_once dirname(dirname(__DIR__)) . '/mainfile.php';
$moduleDirName = basename(__DIR__);
xoops_loadLanguage('main', $moduleDirName);
// Include any common code for this module.
require_once XOOPS_ROOT_PATH . "/modules/{$moduleDirName}/include/common.php";
//require_once(XOOPS_ROOT_PATH . "/modules/{$moduleDirName}/class/field.php";
if (!class_exists('PedigreeField')) {
    require_once XOOPS_ROOT_PATH . "/modules/{$moduleDirName}/class/field.php";
}

// Get all HTTP post or get parameters into global variables that are prefixed with "param_"
//import_request_variables("gp", "param_");
extract($_GET, EXTR_PREFIX_ALL, 'param');
extract($_POST, EXTR_PREFIX_ALL, 'param');

$xoopsOption['template_main'] = 'pedigree_dog.tpl';

include XOOPS_ROOT_PATH . '/header.php';

global $xoopsUser, $xoopsTpl, $xoopsDB;

$pathIcon16 = $pedigree->getModule()->getInfo('icons16');

xoops_load('XoopsUserUtility');

//get module configuration
$moduleConfig = $pedigree->getConfig();
/*
$moduleHandler = xoops_getHandler('module');
$module        = $moduleHandler->getByDirname('pedigree');
$configHandler = xoops_getHandler('config');
$moduleConfig  = $configHandler->getConfigsByCat(0, $module->getVar('mid'));
*/
$myts = MyTextSanitizer::getInstance();

if (isset($_GET['Id'])) {
    $id = XoopsRequest::getInt('Id', 0, 'get');
} else {
    echo 'No dog has been selected';
    die();
}

if (isset($_GET['delpicture']) && $_GET['delpicture'] === 'true') {
    $delpicsql = 'UPDATE ' . $GLOBALS['xoopsDB']->prefix('pedigree_tree') . " SET foto = '' WHERE Id = '" . $id . "'";
    $GLOBALS['xoopsDB']->query($delpicsql);
}
//query
$queryString = 'SELECT * FROM ' . $GLOBALS['xoopsDB']->prefix('pedigree_tree') . ' WHERE Id=' . $id;
$result      = $GLOBALS['xoopsDB']->query($queryString);

while (false !== ($row = $GLOBALS['xoopsDB']->fetchArray($result))) {
    //name
    $naam = stripslashes($row['NAAM']);
    $xoopsTpl->assign('xoops_pagetitle', $naam . ' -- detailed information');
    //owner
    if ($row['id_owner'] != '0') {
        $queryeig = 'SELECT Id, lastname, firstname FROM ' . $GLOBALS['xoopsDB']->prefix('pedigree_owner') . ' WHERE Id=' . $row['id_owner'];
        $reseig   = $GLOBALS['xoopsDB']->query($queryeig);
        while (false !== ($roweig = $GLOBALS['xoopsDB']->fetchArray($reseig))) {
            $eig = "<a href=\"owner.php?ownid=" . $roweig['Id'] . "\">" . $roweig['firstname'] . ' ' . $roweig['lastname'] . '</a>';
        }
    } else {
        $eig = "<a href=\"update.php?Id=" . $row['Id'] . "&fld=ow\">" . _MA_PEDIGREE_UNKNOWN . '</a>';
    }
    //breeder
    if ($row['id_breeder'] != '0') {
        $queryfok = 'SELECT Id, lastname, firstname FROM ' . $GLOBALS['xoopsDB']->prefix('pedigree_owner') . ' WHERE Id=' . $row['id_breeder'];
        $resfok   = $GLOBALS['xoopsDB']->query($queryfok);
        while (false !== ($rowfok = $GLOBALS['xoopsDB']->fetchArray($resfok))) {
            $fok = "<a href=\"owner.php?ownid=" . $rowfok['Id'] . "\">" . $rowfok['firstname'] . ' ' . $rowfok['lastname'] . '</a>';
        }
    } else {
        $fok = "<a href=\"update.php?Id=" . $row['Id'] . "&fld=br\">" . _MA_PEDIGREE_UNKNOWN . '</a>';
    }
    //gender
    if ($row['roft'] == 0) {
        $gender = "<img src=\"assets/images/male.gif\" alt=\"" . _MA_PEDIGREE_MALE . "\"> " . strtr(_MA_PEDIGREE_FLD_MALE, array('[male]' => $moduleConfig['male']));
    } else {
        $gender = "<img src=\"assets/images/female.gif\" alt=\"" . _MA_PEDIGREE_FEMALE . "\"> " . strtr(_MA_PEDIGREE_FLD_FEMA, array('[female]' => $moduleConfig['female']));
    }
    //Sire
    if ($row['father'] != 0) {
        $querysire = 'SELECT NAAM FROM ' . $GLOBALS['xoopsDB']->prefix('pedigree_tree') . ' WHERE Id=' . $row['father'];
        $ressire   = $GLOBALS['xoopsDB']->query($querysire);
        while (false !== ($rowsire = $GLOBALS['xoopsDB']->fetchArray($ressire))) {
            $sire = "<img src=\"assets/images/male.gif\"><a href=\"dog.php?Id=" . $row['father'] . "\">" . stripslashes($rowsire['NAAM']) . '</a>';
        }
    } else {
        $sire = "<img src=\"assets/images/male.gif\" alt=\"" . _MA_PEDIGREE_MALE . "\"><a href=\"seldog.php?curval=" . $row['Id'] . "&gend=0&letter=a\">" . _MA_PEDIGREE_UNKNOWN . '</a>';
    }
    //Dam
    if ($row['mother'] != 0) {
        $querydam = 'SELECT NAAM FROM ' . $GLOBALS['xoopsDB']->prefix('pedigree_tree') . ' WHERE Id=' . $row['mother'];
        $resdam   = $GLOBALS['xoopsDB']->query($querydam);
        while (false !== ($rowdam = $GLOBALS['xoopsDB']->fetchArray($resdam))) {
            $dam = "<img src=\"assets/images/female.gif\"><a href=\"dog.php?Id=" . $row['mother'] . "\">" . stripslashes($rowdam['NAAM']) . '</a>';
        }
    } else {
        $dam = "<img src=\"assets/images/female.gif\" alt=\"" . _MA_PEDIGREE_FEMALE . "\"><a href=\"seldog.php?curval=" . $row['Id'] . "&gend=1&letter=a\">" . _MA_PEDIGREE_UNKNOWN . '</a>';
    }
    //picture
    if ($row['foto'] != '') {
        $picture = '<img src=assets/images/thumbnails/' . $row['foto'] . '_400.jpeg>';
    } else {
        $picture = "<a href=\"update.php?Id=" . $row['Id'] . "&fld=pc\">" . _MA_PEDIGREE_UNKNOWN . '</a>';
    }
    //inbred precentage
    if ($row['coi'] == '') {
        if ($row['father'] != 0 && $row['mother'] != 0) {
            $inbred = "<a href=\"coi.php?s=" . $row['father'] . '&d=' . $row['mother'] . '&dogid=' . $row['Id'] . "&detail=1\">" . strtr(_MA_PEDIGREE_COI_WAIT, array('[animalType]' => $moduleConfig['animalType'])) . '</a>';
        } else {
            $inbred = _MA_PEDIGREE_COI_MORE;
        }
    } else {
        $inbred = "<a href=\"coi.php?s=" . $row['father'] . '&d=' . $row['mother'] . '&dogid=' . $row['Id'] . "&detail=1\" title=\"" . strtr(_MA_PEDIGREE_COI_WAIT, array('[animalType]' => $moduleConfig['animalType'])) . "\">" . $row['coi'] . ' %</a>';
    }
    //brothers and sisters
    $bas = bas($id, $row['father'], $row['mother']);
    //pups
    if ('1' == $moduleConfig['pups']) {
        $pups = pups($id, $row['roft']);
    }
    //check for edit rights
    $access      = 0;
    if (($xoopsUser instanceof XoopsUser) && (($xoopsUser->isAdmin($pedigree->getModule()->mid())) || ($row['user'] == $xoopsUser->getVar('uid')))) {
        $access = 1;
    }

    //name
    $items[] = array(
        'header' => _MA_PEDIGREE_FLD_NAME,
        'data'   => "<a href=\"pedigree.php?pedid=" . $row['Id'] . "\">" . $naam . '</a> (click to view pedigree)',
        'edit'   => "<a href=\"update.php?Id=" . $row['Id'] . "&fld=nm\"><img src=' " . $pathIcon16 . "/edit.png' border='0' alt=_EDIT title=_EDIT /></a>"
    );
    if ($moduleConfig['ownerbreeder'] == '1') {
        //owner
        $items[] = array(
            'header' => _MA_PEDIGREE_FLD_OWNE,
            'data'   => $eig,
            'edit'   => "<a href=\"update.php?Id=" . $row['Id'] . "&fld=ow\"><img src=' " . $pathIcon16 . "/edit.png' border='0' alt=_EDIT title=_EDIT /></a>"
        );
        //breeder
        $items[] = array(
            'header' => _MA_PEDIGREE_FLD_BREE,
            'data'   => $fok,
            'edit'   => "<a href=\"update.php?Id=" . $row['Id'] . "&fld=br\"><img src=' " . $pathIcon16 . "/edit.png' border='0' alt=_EDIT title=_EDIT /></a>"
        );
    }
    //gender
    $items[] = array(
        'header' => _MA_PEDIGREE_FLD_GEND,
        'data'   => $gender,
        'edit'   => "<a href=\"update.php?Id=" . $row['Id'] . "&fld=sx\"><img src=' " . $pathIcon16 . "/edit.png' border='0' alt=_EDIT title=_EDIT /></a>"
    );
    //sire
    $items[] = array(
        'header' => strtr(_MA_PEDIGREE_FLD_FATH, array('[father]' => $moduleConfig['father'])),
        'data'   => $sire,
        'edit'   => "<a href=\"seldog.php?curval=" . $row['Id'] . "&gend=0&letter=a\"><img src=' " . $pathIcon16 . "/edit.png' border='0' alt=_EDIT title=_EDIT /></a>"
    );
    //dam
    $items[] = array(
        'header' => strtr(_MA_PEDIGREE_FLD_MOTH, array('[mother]' => $moduleConfig['mother'])),
        'data'   => $dam,
        'edit'   => "<a href=\"seldog.php?curval=" . $row['Id'] . "&gend=1&letter=a\"><img src=' " . $pathIcon16 . "/edit.png' border='0' alt=_EDIT title=_EDIT /></a>"
    );
    //picture
    $items[] = array(
        'header' => _MA_PEDIGREE_FLD_PICT,
        'data'   => $picture,
        'edit'   => "<a href=\"update.php?Id=" . $row['Id'] . "&fld=pc\"><img src=' " . $pathIcon16 . "/edit.png' border='0' alt=_EDIT title=_EDIT /></a><a href=\"dog.php?Id=" . $row['Id'] . "&delpicture=true\"><img src=' " . $pathIcon16 . "/delete.png' border='0' alt=_DELETE title=_DELETE /></a>"
    );

    //userdefined fields
    $a = XoopsRequest::getInt('Id', 1, 'GET');
    $animal = new PedigreeAnimal($a);

    //test to find out how many user fields there are..
    $fields = $animal->getNumOfFields();
    //create userfields and populate them
    for ($i = 0, $iMax = count($fields); $i < $iMax; ++$i) {
        $userField = new PedigreeField($fields[$i], $animal->getConfig());
        if ($userField->isActive()) {
            $fieldType   = $userField->getSetting('fieldyype');
            $fieldObject = new $fieldType($userField, $animal);
            if ($userField->isLocked()) {
                $items[] = array(
                    'header' => $userField->getSetting('fieldname'),
                    'data'   => $fieldObject->showValue(),
                    'edit'   => ''
                );
            } else {
                $items[] = array(
                    'header' => $userField->getSetting('fieldname'),
                    'data'   => $fieldObject->showValue(),
                    'edit'   => "<a href=\"update.php?Id=" . $row['Id'] . '&fld=' . $fields[$i] . "\"><img src=' " . $pathIcon16 . "/edit.png' border='0' alt=_EDIT title=_EDIT /></a>"
                );
            }
        }
        unset($fieldObject, $userField);
    }

    if ($moduleConfig['proversion'] == '1') {
        //inbred percentage
        $items[] = array(
            'header' => _MA_PEDIGREE_FLD_INBR,
            'data'   => $inbred,
            'edit'   => ''
        );
    }
    if ($moduleConfig['pups'] == '1') {
        //pups
        if ($nummatch == '0') {
            $pups = '';
        } else {
            $pups = 'pups';
        }
        $items[] = array(
            'header' => $moduleConfig['children'],
            'data'   => $pups,
            'edit'   => ''
        );
    }
    if ($moduleConfig['brothers'] == '1') {
        //bas (brothers and sisters)
        if ($nummatch1 == '0') {
            $bas = '';
        } else {
            $bas = 'bas';
        }
        $items[] = array(
            'header' => _MA_PEDIGREE_FLD_BAS,
            'data'   => $bas,
            'edit'   => ''
        );
    }
    //database user
    if ($moduleConfig['proversion'] == '1') {
        $items[] = array(
            'header' => _MA_PEDIGREE_FLD_DBUS,
            'data'   => XoopsUserUtility::getUnameFromId($row['user']),
            'edit'   => ''
        );
    }
    //inbred pedigree
    if ($moduleConfig['proversion'] == '1') {
        $items[] = array(
            'header' => 'Inbred Pedigree',
            'data'   => "<a href=\"mpedigree.php?pedid=" . $row['Id'] . "\">Inbreeding pedigree</a>",
            'edit'   => ''
        );
    }
    $id = $row['Id'];
}

//add data to smarty template
//assign dog
//pups
$xoopsTpl->assign('dogs', $dogs);
$xoopsTpl->assign('columns', $columns);
$xoopsTpl->assign('numofcolumns', $numofcolumns);
$xoopsTpl->assign('nummatch', $nummatch . ' Animals found.');

//bas
$xoopsTpl->assign('dogs1', $dogs1);
$xoopsTpl->assign('columns1', $columns1);
$xoopsTpl->assign('numofcolumns1', $numofcolumns1);
$xoopsTpl->assign('nummatch1', $nummatch1 . ' Animals found.');

//both pups and bas
$xoopsTpl->assign('width', 100 / $numofcolumns);
$xoopsTpl->assign('tsarray', PedigreeUtilities::sortTable($numofcolumns));

$xoopsTpl->assign('access', $access);
$xoopsTpl->assign('items', $items);
$xoopsTpl->assign('name', $naam);
$xoopsTpl->assign('id', $id);
$xoopsTpl->assign('sdvins', _MA_PEDIGREE_SDVINS);
$xoopsTpl->assign('vpo', _MA_PEDIGREE_VPO);
$xoopsTpl->assign('vpo2', _MA_PEDIGREE_VPO2);
$xoopsTpl->assign('sii', _MA_PEDIGREE_SII);
$xoopsTpl->assign('sip', _MA_PEDIGREE_SIP);
$xoopsTpl->assign('id', $id);
$xoopsTpl->assign('delete', _MA_PEDIGREE_BTN_DELE);

//comments and footer
include XOOPS_ROOT_PATH . '/include/comment_view.php';
include XOOPS_ROOT_PATH . '/footer.php';
