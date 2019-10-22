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

/*
// Get all HTTP post or get parameters into global variables that are prefixed with "param_"
//import_request_variables("gp", "param_");
extract($_GET, EXTR_PREFIX_ALL, "param");
extract($_POST, EXTR_PREFIX_ALL, "param");
*/
$GLOBALS['xoopsOption']['template_main'] = 'pedigree_result.tpl';

include $GLOBALS['xoops']->path('/header.php');

//get module configuration
/** @var XoopsModuleHandler $moduleHandler */
$moduleHandler = xoops_getHandler('module');
$module = $moduleHandler->getByDirname($moduleDirName);
$configHandler = xoops_getHandler('config');
$moduleConfig = $configHandler->getConfigsByCat(0, $module->getVar('mid'));

$perPage = $moduleConfig['perpage'];

$st = Request::getInt('st', 0, 'GET');
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

$dogs = []; // an empty array
$numofcolumns = 0;
$pages = '';

//count total number of dogs
$numDog = "SELECT COUNT( {$com} ) AS X, {$com} FROM " . $GLOBALS['xoopsDB']->prefix('pedigree_tree') . " WHERE {$com} !=0 GROUP BY {$com}";
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
$queryString = 'SELECT count( d.'
               . $com
               . ' ) AS X, d.'
               . $com
               . ', p.naam as p_NAAM, p.father as p_father, p.mother as p_mother, p.coi as p_coi, p.foto as p_foto FROM '
               . $GLOBALS['xoopsDB']->prefix('pedigree_tree')
               . ' d LEFT JOIN '
               . $GLOBALS['xoopsDB']->prefix('pedigree_tree')
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
$result = $GLOBALS['xoopsDB']->query($queryString);

while (false !== ($row = $GLOBALS['xoopsDB']->fetchArray($result))) {
    $numofcolumns = 2;
    if ('father' === $com) {
        $gender = '<img src="assets/images/male.gif">';
    } else {
        $gender = '<img src="assets/images/female.gif">';
    }
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
        $camera = ' <img src="' . PEDIGREE_UPLOAD_URL . '/images/dog-icon25.png">';
    } else {
        $camera = '';
    }
    $name = stripslashes($row['p_NAAM']) . $camera;
    for ($i = 1; $i < $numofcolumns; ++$i) {
        $columnvalue[] = ['value' => $coi];
        $columnvalue[] = ['value' => $row['X']];
    }
    $dogs[] = [
        'id' => $row[$com],
        'name' => $name,
        'gender' => $gender,
        'link' => '<a href="pedigree.php?pedid=' . $row[$com] . '">' . $name . '</a>',
        'colour' => '',
        'number' => '',
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
$GLOBALS['xoopsTpl']->assign('tsarray', Pedigree\Utility::sortTable($numofcolumns));
//find last shown number
if (($st + $perPage) > $numResults) {
    $lastshown = $numResults;
} else {
    $lastshown = $st + $perPage;
}
//create string
$matches = _MA_PEDIGREE_MATCHES;
$nummatchstr = $numResults . $matches . ($st + 1) . '-' . $lastshown . ' (' . $numPages . ' pages)';
$GLOBALS['xoopsTpl']->assign('nummatch', strtr($nummatchstr, ['[animalTypes]' => $moduleConfig['animalTypes']]));
$GLOBALS['xoopsTpl']->assign('pages', $pages);

//comments and footer
include $GLOBALS['xoops']->path('/footer.php');
