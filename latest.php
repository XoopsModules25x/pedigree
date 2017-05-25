<?php
// -------------------------------------------------------------------------

require_once dirname(dirname(__DIR__)) . '/mainfile.php';

$moduleDirName = basename(__DIR__);
xoops_loadLanguage('main', $moduleDirName);
xoops_load('XoopsRequest');

// Include any common code for this module.
require_once XOOPS_ROOT_PATH . '/modules/' . $moduleDirName . '/include/common.php';
require_once $GLOBALS['xoops']->path("modules/{$moduleDirName}/class/field.php");
//path taken

$xoopsOption['template_main'] = 'pedigree_latest.tpl';

include $GLOBALS['xoops']->path('/header.php');
/*
//get module configuration
$moduleHandler = xoops_getHandler('module');
$module        = $moduleHandler->getByDirname('pedigree');
$configHandler = xoops_getHandler('config');
$moduleConfig  = $configHandler->getConfigsByCat(0, $module->getVar('mid'));
*/
$st = XoopsRequest::getInt('st', 0, 'GET');

$perp = $pedigree->getConfig('perpage');

//iscurrent user a module admin ?
if (($xoopsUser instanceof XoopsUser) && ($xoopsUser->isAdmin($pedigree->getModule()->mid()))) {
    $modadmin = true;
} else {
    $modadmin= false;
}

//count total number of animals
$numanimal = 'SELECT Id FROM ' . $GLOBALS['xoopsDB']->prefix('pedigree_tree') . ' NOLIMIT';
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
$queryString = 'SELECT d.Id as d_id,
                d.NAAM as d_naam,
                d.roft as d_roft,
                d.mother as d_mother,
                d.father as d_father,
                d.foto as d_foto,
                d.user as d_user,
                f.Id as f_id,
                f.NAAM as f_naam,
                m.Id as m_id,
                m.NAAM as m_naam,
                u.uname as u_uname FROM '
              . $GLOBALS['xoopsDB']->prefix('pedigree_tree')
              . ' d LEFT JOIN '
              . $GLOBALS['xoopsDB']->prefix('pedigree_tree')
              . ' f ON d.father = f.Id LEFT JOIN '
               . $GLOBALS['xoopsDB']->prefix('pedigree_tree')
               . ' m ON d.mother = m.Id LEFT JOIN '
               . $GLOBALS['xoopsDB']->prefix('users')
               . ' u ON d.user = u.uid ORDER BY '
               . 'd.Id DESC LIMIT ' . $st . ', ' . $perp;
$result      = $GLOBALS['xoopsDB']->query($queryString);
$pathIcon16  = $pedigree->getModule()->getInfo('icons16');

while (false !== ($row = $GLOBALS['xoopsDB']->fetchArray($result))) {
    //reset $gender
    $gender = '';
    if (!empty($GLOBALS['xoopsUser'])) {
        if ($row['d_user'] == $GLOBALS['xoopsUser']->getVar('uid') || $modadmin == true) {
            $gender = "<a href=\"dog.php?Id=" . $row['d_id'] . "\"><img src=\"{$pathIcon16}/edit.png\" alt=\"" . _EDIT . "\"></a><a href=\"delete.php?Id=" . $row['d_id'] . "\"><img src=\"{$pathIcon16}/delete.png\" alt=\"" . _DELETE . "\"></a>";
        } else {
            $gender = '';
        }
    }

    if ('' != $row['d_foto']) {
        $camera = " <img src=\"assets/images/dog-icon25.png\" alt=\"\">";
    } else {
        $camera = '';
    }

    if (0 == $row['d_roft']) {
        $gender .= "<img src=\"assets/images/male.gif\" alt=\"" . ucfirst(_MA_PEDIGREE_FEMALE) . "\">";
    } else {
        $gender .= "<img src=\"assets/images/female.gif\" alt=\"" . ucfirst(_MA_PEDIGREE_MALE) . "\">";
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
$matches     = strtr(_MA_PEDIGREE_MATCHES, array('[animalTypes]' => $pedigree->getConfig('animalTypes')));
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
