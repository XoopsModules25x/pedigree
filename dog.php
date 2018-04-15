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

// Get all HTTP post or get parameters into global variables that are prefixed with "param_"
//import_request_variables("gp", "param_");
extract($_GET, EXTR_PREFIX_ALL, 'param');
extract($_POST, EXTR_PREFIX_ALL, 'param');

$GLOBALS['xoopsOption']['template_main'] = 'pedigree_dog.tpl';
include XOOPS_ROOT_PATH . '/header.php';

global $xoopsUser, $xoopsTpl, $xoopsDB, $xoopsModuleConfig, $xoopsModule;
global $numofcolumns, $numMatch, $pages, $columns, $dogs;

$pathIcon16 = \Xmf\Module\Admin::iconUrl('', 16);

xoops_load('XoopsUserUtility');

//get module configuration
/** @var XoopsModuleHandler $moduleHandler */
$moduleHandler = xoops_getHandler('module');
$module        = $moduleHandler->getByDirname($moduleDirName);
$configHandler = xoops_getHandler('config');
$moduleConfig  = $configHandler->getConfigsByCat(0, $module->getVar('mid'));

$myts = \MyTextSanitizer::getInstance();

if (isset($_GET['id'])) {
    $id = Request::getInt('id', 0, 'GET');
} else {
    echo 'No dog has been selected';
    die();
}

if (isset($_GET['delpicture']) && 'true' === $_GET['delpicture']) {
    $delpicsql = 'UPDATE ' . $GLOBALS['xoopsDB']->prefix('pedigree_tree') . " SET foto = '' WHERE id = '" . $id . "'";
    $GLOBALS['xoopsDB']->query($delpicsql);
}
//query
$queryString = 'SELECT * FROM ' . $GLOBALS['xoopsDB']->prefix('pedigree_tree') . ' WHERE id=' . $id;
$result      = $GLOBALS['xoopsDB']->query($queryString);

while (false !== ($row = $GLOBALS['xoopsDB']->fetchArray($result))) {
    //name
    $naam = stripslashes($row['naam']);
    $xoopsTpl->assign('xoops_pagetitle', $naam . ' -- detailed information');
    //owner
    if ('0' != $row['id_owner']) {
        $queryeig = 'SELECT id, lastname, firstname FROM ' . $GLOBALS['xoopsDB']->prefix('pedigree_owner') . ' WHERE id=' . $row['id_owner'];
        $reseig   = $GLOBALS['xoopsDB']->query($queryeig);
        while (false !== ($roweig = $GLOBALS['xoopsDB']->fetchArray($reseig))) {
            $eig = '<a href="owner.php?ownid=' . $roweig['id'] . '">' . $roweig['firstname'] . ' ' . $roweig['lastname'] . '</a>';
        }
    } else {
        $eig = '<a href="update.php?id=' . $row['id'] . '&fld=ow">' . _MA_PEDIGREE_UNKNOWN . '</a>';
    }
    //breeder
    if ('0' != $row['id_breeder']) {
        $queryfok = 'SELECT id, lastname, firstname FROM ' . $GLOBALS['xoopsDB']->prefix('pedigree_owner') . ' WHERE id=' . $row['id_breeder'];
        $resfok   = $GLOBALS['xoopsDB']->query($queryfok);
        while (false !== ($rowfok = $GLOBALS['xoopsDB']->fetchArray($resfok))) {
            $fok = '<a href="owner.php?ownid=' . $rowfok['id'] . '">' . $rowfok['firstname'] . ' ' . $rowfok['lastname'] . '</a>';
        }
    } else {
        $fok = '<a href="update.php?id=' . $row['id'] . '&fld=br">' . _MA_PEDIGREE_UNKNOWN . '</a>';
    }
    //gender
    if (0 == $row['roft']) {
        $gender = '<img src="assets/images/male.gif"> ' . strtr(_MA_PEDIGREE_FLD_MALE, ['[male]' => $moduleConfig['male']]);
    } else {
        $gender = '<img src="assets/images/female.gif"> ' . strtr(_MA_PEDIGREE_FLD_FEMA, ['[female]' => $moduleConfig['female']]);
    }
    //Sire
    if (0 != $row['father']) {
        $querysire = 'SELECT naam FROM ' . $GLOBALS['xoopsDB']->prefix('pedigree_tree') . ' WHERE id=' . $row['father'];
        $ressire   = $GLOBALS['xoopsDB']->query($querysire);
        while (false !== ($rowsire = $GLOBALS['xoopsDB']->fetchArray($ressire))) {
            $sire = '<img src="assets/images/male.gif"><a href="dog.php?id=' . $row['father'] . '">' . stripslashes($rowsire['naam']) . '</a>';
        }
    } else {
        $sire = '<img src="assets/images/male.gif"><a href="seldog.php?curval=' . $row['id'] . '&gend=0&letter=A">' . _MA_PEDIGREE_UNKNOWN . '</a>';
    }
    //Dam
    if (0 != $row['mother']) {
        $querydam = 'SELECT naam FROM ' . $GLOBALS['xoopsDB']->prefix('pedigree_tree') . ' WHERE id=' . $row['mother'];
        $resdam   = $GLOBALS['xoopsDB']->query($querydam);
        while (false !== ($rowdam = $GLOBALS['xoopsDB']->fetchArray($resdam))) {
            $dam = '<img src="assets/images/female.gif"><a href="dog.php?id=' . $row['mother'] . '">' . stripslashes($rowdam['naam']) . '</a>';
        }
    } else {
        $dam = '<img src="assets/images/female.gif"><a href="seldog.php?curval=' . $row['id'] . '&gend=1&letter=A">' . _MA_PEDIGREE_UNKNOWN . '</a>';
    }
    //picture
    if ('' != $row['foto']) {
        $picture = '<img src=' . PEDIGREE_UPLOAD_URL . '/images/thumbnails/' . $row['foto'] . '_400.jpeg>';
    } else {
        $picture = '<a href="update.php?id=' . $row['id'] . '&fld=pc">' . _MA_PEDIGREE_UNKNOWN . '</a>';
    }
    //inbred precentage
    if ('' == $row['coi']) {
        if (0 != $row['father'] && 0 != $row['mother']) {
            $inbred = '<a href="coi.php?s=' . $row['father'] . '&d=' . $row['mother'] . '&dogid=' . $row['id'] . '&detail=1">' . strtr(_MA_PEDIGREE_COI_WAIT, ['[animalType]' => $moduleConfig['animalType']]) . '</a>';
        } else {
            $inbred = _MA_PEDIGREE_COI_MORE;
        }
    } else {
        $inbred = '<a href="coi.php?s=' . $row['father'] . '&d=' . $row['mother'] . '&dogid=' . $row['id'] . '&detail=1" title="' . strtr(_MA_PEDIGREE_COI_WAIT, ['[animalType]' => $moduleConfig['animalType']]) . '">' . $row['coi'] . ' %</a>';
    }
    //brothers and sisters
    $bas = Pedigree\Utility::bas($id, $row['father'], $row['mother']);
    //pups
    if ('1' == $moduleConfig['pups']) {
        $pups = Pedigree\Utility::pups($id, $row['roft']);
    }
    //check for edit rights
    $access      = 0;
    $xoopsModule = XoopsModule::getByDirname($moduleDirName);
    if (!empty($xoopsUser)) {
        if ($xoopsUser->isAdmin($xoopsModule->mid())) {
            $access = 1;
        }
        if ($row['user'] == $xoopsUser->getVar('uid')) {
            $access = 1;
        }
    }

    //name
    $items[] = [
        'header' => _MA_PEDIGREE_FLD_NAME,
        'data'   => '<a href="pedigree.php?pedid=' . $row['id'] . '">' . $naam . '</a> (click to view pedigree)',
        'edit'   => '<a href="update.php?id=' . $row['id'] . "&fld=nm\"><img src=' " . $pathIcon16 . "/edit.png' border='0' alt=_EDIT title=_EDIT></a>"
    ];
    if ('1' == $moduleConfig['ownerbreeder']) {
        //owner
        $items[] = [
            'header' => _MA_PEDIGREE_FLD_OWNE,
            'data'   => $eig,
            'edit'   => '<a href="update.php?id=' . $row['id'] . "&fld=ow\"><img src=' " . $pathIcon16 . "/edit.png' border='0' alt=_EDIT title=_EDIT></a>"
        ];
        //breeder
        $items[] = [
            'header' => _MA_PEDIGREE_FLD_BREE,
            'data'   => $fok,
            'edit'   => '<a href="update.php?id=' . $row['id'] . "&fld=br\"><img src=' " . $pathIcon16 . "/edit.png' border='0' alt=_EDIT title=_EDIT></a>"
        ];
    }
    //gender
    $items[] = [
        'header' => _MA_PEDIGREE_FLD_GEND,
        'data'   => $gender,
        'edit'   => '<a href="update.php?id=' . $row['id'] . "&fld=sx\"><img src=' " . $pathIcon16 . "/edit.png' border='0' alt=_EDIT title=_EDIT></a>"
    ];
    //sire
    $items[] = [
        'header' => strtr(_MA_PEDIGREE_FLD_FATH, ['[father]' => $moduleConfig['father']]),
        'data'   => $sire,
        'edit'   => '<a href="seldog.php?curval=' . $row['id'] . "&gend=0&letter=A\"><img src=' " . $pathIcon16 . "/edit.png' border='0' alt=_EDIT title=_EDIT></a>"
    ];
    //dam
    $items[] = [
        'header' => strtr(_MA_PEDIGREE_FLD_MOTH, ['[mother]' => $moduleConfig['mother']]),
        'data'   => $dam,
        'edit'   => '<a href="seldog.php?curval=' . $row['id'] . "&gend=1&letter=A\"><img src=' " . $pathIcon16 . "/edit.png' border='0' alt=_EDIT title=_EDIT></a>"
    ];
    //picture
    $items[] = [
        'header' => _MA_PEDIGREE_FLD_PICT,
        'data'   => $picture,
        'edit'   => '<a href="update.php?id=' . $row['id'] . "&fld=pc\"><img src=' " . $pathIcon16 . "/edit.png' border='0' alt=_EDIT title=_EDIT></a><a href=\"dog.php?id=" . $row['id'] . "&delpicture=true\"><img src=' " . $pathIcon16 . "/delete.png' border='0' alt=_DELETE title=_DELETE></a>"
    ];

    //userdefined fields

    $a      = Request::getInt('id', 1, 'GET');
    $animal = new Pedigree\Animal($a);

    //test to find out how many user fields there are..
    $fields = $animal->getNumOfFields();
    //create userfields and populate them
    for ($i = 0, $iMax = count($fields); $i < $iMax; ++$i) {
        $userField = new Pedigree\Field($fields[$i], $animal->getConfig());
        if ($userField->isActive()) {
            $fieldType   = $userField->getSetting('FieldType');
            $fieldObject = new $fieldType($userField, $animal);
            if ($userField->isLocked()) {
                $items[] = [
                    'header' => $userField->getSetting('FieldName'),
                    'data'   => $fieldObject->showValue(),
                    'edit'   => ''
                ];
            } else {
                $items[] = [
                    'header' => $userField->getSetting('FieldName'),
                    'data'   => $fieldObject->showValue(),
                    'edit'   => '<a href="update.php?id=' . $row['id'] . '&fld=' . $fields[$i] . "\"><img src=' " . $pathIcon16 . "/edit.png' border='0' alt=_EDIT title=_EDIT></a>"
                ];
            }
        }
        unset($fieldObject, $userField);
    }

    if ('1' == $moduleConfig['proversion']) {
        //inbred percentage
        $items[] = [
            'header' => _MA_PEDIGREE_FLD_INBR,
            'data'   => $inbred,
            'edit'   => ''
        ];
    }
    if ('1' == $moduleConfig['pups']) {
        //pups
        if ('0' == $numMatch) {
            $pups = '';
        } else {
            $pups = 'pups';
        }
        $items[] = [
            'header' => $moduleConfig['children'],
            'data'   => $pups,
            'edit'   => ''
        ];
    }
    if ('1' == $moduleConfig['brothers']) {
        //bas (brothers and sisters)
        if ('0' == $nummatch1) {
            $bas = '';
        } else {
            $bas = 'bas';
        }
        $items[] = [
            'header' => _MA_PEDIGREE_FLD_BAS,
            'data'   => $bas,
            'edit'   => ''
        ];
    }
    //database user
    if ('1' == $moduleConfig['proversion']) {
        $items[] = [
            'header' => _MA_PEDIGREE_FLD_DBUS,
            'data'   => \XoopsUserUtility::getUnameFromId($row['user']),
            'edit'   => ''
        ];
    }
    //inbred pedigree
    if ('1' == $moduleConfig['proversion']) {
        $items[] = [
            'header' => 'Inbred Pedigree',
            'data'   => '<a href="mpedigree.php?pedid=' . $row['id'] . '">Inbreeding pedigree</a>',
            'edit'   => ''
        ];
    }
    $id = $row['id'];
}

//add data to smarty template
//assign dog
//pups
$xoopsTpl->assign('dogs', $dogs);
$xoopsTpl->assign('columns', $columns);
$xoopsTpl->assign('numofcolumns', $numofcolumns);
$xoopsTpl->assign('nummatch', $numMatch . ' Animals found.');

//bas
$xoopsTpl->assign('dogs1', $dogs1);
$xoopsTpl->assign('columns1', $columns1);
$xoopsTpl->assign('numofcolumns1', $numofcolumns1);
$xoopsTpl->assign('nummatch1', $nummatch1 . ' Animals found.');

//both pups and bas
$xoopsTpl->assign('width', 100 / $numofcolumns);
$xoopsTpl->assign('tsarray', Pedigree\Utility::sortTable($numofcolumns));

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
$xoopsTpl->assign('delete', _DELETE);

//comments and footer
include XOOPS_ROOT_PATH . '/include/comment_view.php';
include XOOPS_ROOT_PATH . '/footer.php';
