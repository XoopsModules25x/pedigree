<?php
/*
 You may not change or alter any portion of this comment or credits of
 supporting developers from this source code or any supporting source code
 which is considered copyrighted (c) material of the original comment or credit
 authors.

 This program is distributed in the hope that it will be useful, but
 WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 */

/**
 * Module: Pedigree
 *
 * @package   XoopsModules\Pedigree
 * @author    XOOPS Module Development Team
 * @copyright Copyright (c) 2001-2019 {@link https://xoops.org XOOPS Project}
 * @license   https://www.gnu.org/licenses/gpl-2.0.html GNU Public License
 */

use Xmf\Request;
use XoopsModules\Pedigree\{
    Constants,
    Helper,
    Utility
};

/** @var Helper $helper */

require_once __DIR__ . '/header.php';


$helper->loadLanguage('main');

// Include any common code for this module.
require_once $helper->path('include/common.php');

/*
// Get all HTTP post or get parameters into global variables that are prefixed with "param_"
//import_request_variables("gp", "param_");
extract($_GET, EXTR_PREFIX_ALL, "param");
extract($_POST, EXTR_PREFIX_ALL, "param");
*/
$GLOBALS['xoopsOption']['template_main'] = 'pedigree_result.tpl';
require $GLOBALS['xoops']->path('/header.php');

//get module configuration
/** @var XoopsModuleHandler $moduleHandler */
$moduleHandler = xoops_getHandler('module');
$module        = $moduleHandler->getByDirname($moduleDirName);
$configHandler = xoops_getHandler('config');
$moduleConfig  = $configHandler->getConfigsByCat(0, $module->getVar('mid'));

$perPage = $helper->getConfig('perpage', Constants::DEFAULT_PER_PAGE);
$perPage = (int)$perPage > 0 ? (int)$perPage : Constants::DEFAULT_PER_PAGE; // default if invalid number in module param

$st  = Request::getInt('st', 0, 'GET');
$com = Request::getString('com', 'father', 'GET');
/*
$st = isset($_GET['st']) ? $_GET['st'] : null;
if (!$st) {
    $st = 0;
}
$com = $_GET['com'];
if (!$com) {
    $com = "father";
}
*/

$dogs         = []; // an empty array
$numofcolumns = 0;
$pages        = '';

//count total number of dogs
$numDog = "SELECT COUNT( {$com} ) AS X, {$com} FROM " . $GLOBALS['xoopsDB']->prefix('pedigree_registry') . " WHERE {$com} !=0 GROUP BY {$com}";
$numRes = $GLOBALS['xoopsDB']->query($numDog);
//total number of dogs the query will find
$numResults = $GLOBALS['xoopsDB']->getRowsNum($numRes);
//total number of pages
$numPages = floor($numResults / $perPage) + 1;
if (($numPages * $perPage) == ($numResults + $perPage)) {
    --$numpage;
}
//find current page
$currentPage = floor($st / $perPage) + 1;
//create previous button
if ($numPages > 1) {
    if ($currentPage > 1) {
        $pages .= '<a href="topstud.php?com=' . $com . '&st=' . ($st - $perPage) . '">' . _MA_PEDIGREE_PREVIOUS . '</a>&nbsp;&nbsp;';
    }
}
//create numbers
for ($x = 1; $x < ($numPages + 1); ++$x) {
    //create line break after 20 number
    if (0 == ($x % 20)) {
        $pages .= '<br>';
    }
    if ($x != $currentPage) {
        $pages .= '<a href="topstud.php?com=' . $com . '&st=' . ($perPage * ($x - 1)) . '">' . $x . '</a>&nbsp;&nbsp;';
    } else {
        $pages .= $x . '&nbsp;&nbsp';
    }
}
//create next button
if ($numPages > 1) {
    if ($currentPage < $numPages) {
        $pages .= '<a href="topstud.php?com=' . $com . '&st=' . ($st + $perPage) . '">' . _MA_PEDIGREE_NEXT . '</a>&nbsp;&nbsp;';
    }
}
//query
$sql    = 'SELECT count( d.'
               . $com
               . ' ) AS X, d.'
               . $com
          . ', p.pname as p_pname, p.father as p_father, p.mother as p_mother, p.coi as p_coi, p.foto as p_foto FROM '
               . $GLOBALS['xoopsDB']->prefix('pedigree_registry')
               . ' d LEFT JOIN '
               . $GLOBALS['xoopsDB']->prefix('pedigree_registry')
               . ' p ON d.'
               . $com
               . ' = p.id WHERE d.'
               . $com
               . ' !=0 GROUP BY d.'
               . $com
               . ' ORDER BY X DESC LIMIT '
               . $st
               . ', '
               . $perPage;
$result = $GLOBALS['xoopsDB']->query($sql);

while (false !== ($row = $GLOBALS['xoopsDB']->fetchArray($result))) {
    $numofcolumns = 2;
    $gender       = ('father' === $com) ? "<img src=\"" . PEDIGREE_IMAGE_URL . "/male.gif\">" : "<img src=\"" . PEDIGREE_IMAGE_URL . "/female.gif\">";

    //read coi% information if exists or create link if not
    if ('' == $row['p_coi'] || '0' == $row['p_coi']) {
        $coi = '<a href="coi.php?s=' . $row['p_father'] . '&d=' . $row['p_mother'] . '&dogid=' . $row[$com] . '&detail=1">' . _MA_PEDIGREE_UNKNOWN . '</a>';
    } else {
        $coi = $row['p_coi'] . ' %';
    }
    //number of pups
    $dob = $row['X'];
    //create array for dogs
    if ('' != $row['p_foto']) {
        //@todo figure out what this file is and where it should be placed
        $camera = ' <img src="' . PEDIGREE_IMAGE_URL . '/images/camera.png">';
    } else {
        $camera = '';
    }
    $name = stripslashes($row['p_pname']) . $camera;
    for ($i = 1; $i < $numofcolumns; ++$i) {
        $columnvalue[] = ['value' => $coi];
        $columnvalue[] = ['value' => $row['X']];
    }
    $dogs[] = [
        'id'          => $row[$com],
        'name'        => $name,
        'gender'      => $gender,
        'link'        => '<a href="pedigree.php?pedid=' . $row[$com] . '">' . $name . '</a>',
        'colour'      => '',
        'number'      => '',
        'usercolumns' => $columnvalue,
    ];
    unset($columnvalue);
}
$columns[] = ['columnname' => 'Name', 'columnnumber' => 1];
$columns[] = ['columnname' => 'COI%', 'columnnumber' => 2];
$columns[] = ['columnname' => 'Offspring', 'columnnumber' => 3];

//add data to smarty template
//assign dog
$GLOBALS['xoopsTpl']->assign('dogs', $dogs);
$GLOBALS['xoopsTpl']->assign('columns', $columns);
$GLOBALS['xoopsTpl']->assign('numofcolumns', $numofcolumns);
$GLOBALS['xoopsTpl']->assign('tsarray', Utility::sortTable($numofcolumns));
//find last shown number
if (($st + $perPage) > $numResults) {
    $lastshown = $numResults;
} else {
    $lastshown = $st + $perPage;
}
//create string
$matches     = _MA_PEDIGREE_MATCHES;
$nummatchstr = $numResults . $matches . ($st + 1) . '-' . $lastshown . ' (' . $numPages . ' pages)';
$GLOBALS['xoopsTpl']->assign('nummatch', strtr($nummatchstr, ['[animalTypes]' => $helper->getConfig('animalTypes')]));
$GLOBALS['xoopsTpl']->assign('pages', $pages);

//comments and footer
require $GLOBALS['xoops']->path('/footer.php');
