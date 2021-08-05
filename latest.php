<?php
// -------------------------------------------------------------------------

use Xmf\Module\Admin;
use Xmf\Request;

//require_once \dirname(__DIR__, 2) . '/mainfile.php';
require_once __DIR__ . '/header.php';

$moduleDirName = basename(__DIR__);
xoops_loadLanguage('main', $moduleDirName);

// Include any common code for this module.
require_once XOOPS_ROOT_PATH . '/modules/' . $moduleDirName . '/include/common.php';
//path taken

$GLOBALS['xoopsOption']['template_main'] = 'pedigree_latest.tpl';

require_once $GLOBALS['xoops']->path('/header.php');

//get module configuration
/** @var \XoopsModuleHandler $moduleHandler */
$moduleHandler = xoops_getHandler('module');
$module        = $moduleHandler->getByDirname($moduleDirName);
/** @var \XoopsConfigHandler $configHandler */
$configHandler = xoops_getHandler('config');
$moduleConfig  = $configHandler->getConfigsByCat(0, $module->getVar('mid'));

$st = Request::getInt('st', 0, 'GET');

$perPage = $moduleConfig['perpage'];
global $xoopsTpl, $xoopsModuleConfig;

//iscurrent user a module admin ?
$modadmin    = false;
$xoopsModule = XoopsModule::getByDirname($moduleDirName);
if (!empty($GLOBALS['xoopsUser'])) {
    if ($GLOBALS['xoopsUser']->isAdmin($xoopsModule->mid())) {
        $modadmin = true;
    }
}

//count total number of animals
$numanimal = 'SELECT id FROM ' . $GLOBALS['xoopsDB']->prefix('pedigree_registry') . ' NOLIMIT';
$numRes    = $GLOBALS['xoopsDB']->query($numanimal);
//total number of animals the query will find
$numResults = $GLOBALS['xoopsDB']->getRowsNum($numRes);
//total number of pages
$numPages = floor($numResults / $perPage) + 1;
if (($numPages * $perPage) == ($numResults + $perPage)) {
    --$numPages;
}
//find current page
$currentPage = floor($st / $perPage) + 1;

//create numbers
for ($x = 1; $x < ($numPages + 1); ++$x) {
    $pages = $x . '&nbsp;&nbsp';
}

//query
$sql        = 'SELECT d.id AS d_id, d.pname AS d_pname, d.roft AS d_roft, d.mother AS d_mother, d.father AS d_father, d.foto AS d_foto, d.user AS d_user, f.id AS f_id, f.pname AS f_pname, m.id AS m_id, m.pname AS m_pname, u.uname AS u_uname FROM '
              . $GLOBALS['xoopsDB']->prefix('pedigree_registry')
              . ' d LEFT JOIN '
              . $GLOBALS['xoopsDB']->prefix('pedigree_registry')
              . ' f ON d.father = f.id LEFT JOIN '
              . $GLOBALS['xoopsDB']->prefix('pedigree_registry')
              . ' m ON d.mother = m.id LEFT JOIN '
              . $GLOBALS['xoopsDB']->prefix('users')
              . ' u ON d.user = u.uid ORDER BY d.id DESC LIMIT '
              . $st
              . ', '
              . $perPage;
$result     = $GLOBALS['xoopsDB']->query($sql);
$pathIcon16 = Admin::iconUrl('', 16);

while (false !== ($row = $GLOBALS['xoopsDB']->fetchArray($result))) {
    //reset $gender
    $gender = '';
    if (!empty($GLOBALS['xoopsUser'])) {
        if ($row['d_user'] == $GLOBALS['xoopsUser']->getVar('uid') || true === $modadmin) {
            $gender = '<a href="dog.php?id=' . $row['d_id'] . '"><img src=' . $pathIcon16 . '/edit.png alt=' . _EDIT . '></a><a href="delete.php?id=' . $row['d_id'] . '"><img src=' . $pathIcon16 . '/delete.png alt=' . _DELETE . '></a>';
        } else {
            $gender = '';
        }
    }

    if ('' != $row['d_foto']) {
        $camera = ' <img src="assets/images/camera.png">';
    } else {
        $camera = '';
    }

    if (0 == $row['d_roft']) {
        $gender .= '<img src="assets/images/male.gif">';
    } else {
        $gender .= '<img src="assets/images/female.gif">';
    }
    //create string for parents
    if ('' == $row['f_pname']) {
        $dad = _MA_PEDIGREE_UNKNOWN;
    } else {
        $dad = '<a href="pedigree.php?pedid=' . $row['f_id'] . '">' . stripslashes($row['f_pname']) . '</a>';
    }
    if ('' == $row['m_pname']) {
        $mom = _MA_PEDIGREE_UNKNOWN;
    } else {
        $mom = '<a href="pedigree.php?pedid=' . $row['m_id'] . '">' . stripslashes($row['m_pname']) . '</a>';
    }
    $parents = $dad . ' x ' . $mom;
    //create array for animals
    $animals[] = [
        'id'      => $row['d_id'],
        'name'    => stripslashes($row['d_pname']) . $camera,
        'gender'  => $gender,
        'parents' => $parents,
        'addedby' => '<a href="../../userinfo.php?uid=' . $row['d_user'] . '">' . $row['u_uname'] . '</a>',
    ];
    //reset rights ready for the next dog
    $editdel = '0';
}

//add data to smarty template
//assign dog
if (isset($animals)) {
    $xoopsTpl->assign('dogs', $animals);
}

//find last shown number
if (($st + $perPage) > $numResults) {
    $lastshown = $numResults;
} else {
    $lastshown = $st + $perPage;
}
//create string
$matches     = strtr(_MA_PEDIGREE_MATCHES, ['[animalTypes]' => $moduleConfig['animalTypes']]);
$nummatchstr = $numResults . $matches . ($st + 1) . '-' . $lastshown . ' (' . $numPages . ' pages)';
$xoopsTpl->assign('nummatch', $nummatchstr);
if (isset($pages)) {
    $xoopsTpl->assign('pages', $pages);
}
$xoopsTpl->assign('name', _MA_PEDIGREE_FLD_NAME);
$xoopsTpl->assign('parents', _MA_PEDIGREE_PA);
$xoopsTpl->assign('addedby', _MA_PEDIGREE_FLD_DBUS);
//comments and footer
require_once XOOPS_ROOT_PATH . '/footer.php';
