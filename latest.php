<?php
// -------------------------------------------------------------------------

require_once dirname(dirname(__DIR__)) . '/mainfile.php';

$moduleDirName = basename(__DIR__);
xoops_loadLanguage('main', $moduleDirName);

// Include any common code for this module.
require_once(XOOPS_ROOT_PATH . '/modules/' . $moduleDirName . '/include/common.php');
require_once $GLOBALS['xoops']->path("modules/{$moduleDirName}/include/class_field.php");
//path taken

$xoopsOption['template_main'] = 'pedigree_latest.tpl';

include $GLOBALS['xoops']->path('/header.php');

//get module configuration
$moduleHandler = xoops_getHandler('module');
$module        = $moduleHandler->getByDirname('pedigree');
$configHandler = xoops_getHandler('config');
$moduleConfig  = $configHandler->getConfigsByCat(0, $module->getVar('mid'));

if (isset($st)) {
    $st = $_GET['st'];
} else {
    $st = 0;
}

$perp = $moduleConfig['perpage'];
global $xoopsTpl, $xoopsModuleConfig;

//iscurrent user a module admin ?
$modadmin    = false;
$xoopsModule = XoopsModule::getByDirname('pedigree');
if (!empty($GLOBALS['xoopsUser'])) {
    if ($GLOBALS['xoopsUser']->isAdmin($xoopsModule->mid())) {
        $modadmin = true;
    }
}

//count total number of animals
$numanimal = 'SELECT ID from ' . $GLOBALS['xoopsDB']->prefix('pedigree_tree') . ' NOLIMIT';
$numres    = $GLOBALS['xoopsDB']->query($numanimal);
//total number of animals the query will find
$numresults = $GLOBALS['xoopsDB']->getRowsNum($numres);
//total number of pages
$numpages = floor($numresults / $perp) + 1;
if (($numpages * $perp) == ($numresults + $perp)) {
    --$numpages;
}
//find current page
$cpage = floor($st / $perp) + 1;

//create numbers
for ($x = 1; $x < ($numpages + 1); ++$x) {
    $pages = $x . '&nbsp;&nbsp';
}

//query
$queryString = 'SELECT d.id as d_id, d.naam as d_naam, d.roft as d_roft, d.mother as d_mother, d.father as d_father, d.foto as d_foto, d.user as d_user, f.id as f_id, f.naam as f_naam, m.id as m_id, m.naam as m_naam, u.uname as u_uname FROM ' . $GLOBALS['xoopsDB']->prefix('pedigree_tree') . ' d LEFT JOIN ' . $GLOBALS['xoopsDB']->prefix('pedigree_tree') . ' f ON d.father = f.id LEFT JOIN ' . $GLOBALS['xoopsDB']->prefix('pedigree_tree') . ' m ON d.mother = m.id LEFT JOIN ' . $GLOBALS['xoopsDB']->prefix('users') . ' u ON d.user = u.uid order by d.id desc LIMIT ' . $st . ', ' . $perp;
$result      = $GLOBALS['xoopsDB']->query($queryString);
$pathIcon16  = $GLOBALS['xoopsModule']->getInfo('icons16');

while (false !== ($row = $GLOBALS['xoopsDB']->fetchArray($result))) {
    //reset $gender
    $gender = '';
    if (!empty($GLOBALS['xoopsUser'])) {
        if ($row['d_user'] == $GLOBALS['xoopsUser']->getVar('uid') || $modadmin == true) {
            $gender = "<a href=\"dog.php?id=" . $row['d_id'] . "\"><img src=" . $pathIcon16 . '/edit.png alt=' . _MA_PEDIGREE_BTN_EDIT . "></a><a href=\"delete.php?id=" . $row['d_id'] . "\"><img src=" . $pathIcon16 . '/delete.png alt=' . _MA_PEDIGREE_BTN_DELE . '></a>';
        } else {
            $gender = '';
        }
    }

    if ($row['d_foto'] != '') {
        $camera = " <img src=\"assets/images/dog-icon25.png\">";
    } else {
        $camera = '';
    }

    if ($row['d_roft'] == 0) {
        $gender .= "<img src=\"assets/images/male.gif\">";
    } else {
        $gender .= "<img src=\"assets/images/female.gif\">";
    }
    //create string for parents
    if ($row['f_naam'] == '') {
        $dad = _MA_PEDIGREE_UNKNOWN;
    } else {
        $dad = "<a href=\"pedigree.php?pedid=" . $row['f_id'] . "\">" . stripslashes($row['f_naam']) . '</a>';
    }
    if ($row['m_naam'] == '') {
        $mom = _MA_PEDIGREE_UNKNOWN;
    } else {
        $mom = "<a href=\"pedigree.php?pedid=" . $row['m_id'] . "\">" . stripslashes($row['m_naam']) . '</a>';
    }
    $parents = $dad . ' x ' . $mom;
    //create array for animals
    $animals[] = array(
        'id'      => $row['d_id'],
        'name'    => stripslashes($row['d_naam']) . $camera,
        'gender'  => $gender,
        'parents' => $parents,
        'addedby' => "<a href=\"../../userinfo.php?uid=" . $row['d_user'] . "\">" . $row['u_uname'] . '</a>'
    );
    //reset rights ready for the next dog
    $editdel = '0';
}

//add data to smarty template
//assign dog
if (isset($animals)) {
    $xoopsTpl->assign('dogs', $animals);
}

//find last shown number
if (($st + $perp) > $numresults) {
    $lastshown = $numresults;
} else {
    $lastshown = $st + $perp;
}
//create string
$matches     = strtr(_MA_PEDIGREE_MATCHES, array('[animalTypes]' => $moduleConfig['animalTypes']));
$nummatchstr = $numresults . $matches . ($st + 1) . '-' . $lastshown . ' (' . $numpages . ' pages)';
$xoopsTpl->assign('nummatch', $nummatchstr);
if (isset($pages)) {
    $xoopsTpl->assign('pages', $pages);
}
$xoopsTpl->assign('name', _MA_PEDIGREE_FLD_NAME);
$xoopsTpl->assign('parents', _MA_PEDIGREE_PA);
$xoopsTpl->assign('addedby', _MA_PEDIGREE_FLD_DBUS);
//comments and footer
include XOOPS_ROOT_PATH . '/footer.php';
