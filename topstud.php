<?php
// -------------------------------------------------------------------------

require_once dirname(dirname(__DIR__)) . '/mainfile.php';

$moduleDirName = basename(__DIR__);
xoops_loadLanguage('main', $moduleDirName);

// Include any common code for this module.
require_once(XOOPS_ROOT_PATH . '/modules/' . $moduleDirName . '/include/common.php');
xoops_load('XoopsRequest');

/*
// Get all HTTP post or get parameters into global variables that are prefixed with "param_"
//import_request_variables("gp", "param_");
extract($_GET, EXTR_PREFIX_ALL, "param");
extract($_POST, EXTR_PREFIX_ALL, "param");
*/
$xoopsOption['template_main'] = 'pedigree_result.tpl';

include $GLOBALS['xoops']->path('/header.php');

//get module configuration
$moduleHandler = xoops_getHandler('module');
$module        = $moduleHandler->getByDirname('pedigree');
$configHandler = xoops_getHandler('config');
$moduleConfig  = $configHandler->getConfigsByCat(0, $module->getVar('mid'));

$perp = $moduleConfig['perpage'];

$st  = XoopsRequest::getInt('st', 0, 'GET');
$com = XoopsRequest::getStrint('com', 'father', 'GET');
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

$dogs         = array(); // an empty array
$numofcolumns = 0;
$pages        = '';

//count total number of dogs
$numdog = "SELECT COUNT( {$com} ) AS X, {$com} FROM " . $GLOBALS['xoopsDB']->prefix('pedigree_tree') . " WHERE {$com} !=0 GROUP BY {$com}";
$numres = $GLOBALS['xoopsDB']->query($numdog);
//total number of dogs the query will find
$numresults = $GLOBALS['xoopsDB']->getRowsNum($numres);
//total number of pages
$numpages = floor($numresults / $perp) + 1;
if (($numpages * $perp) == ($numresults + $perp)) {
    --$numpage;
}
//find current page
$cpage = floor($st / $perp) + 1;
//create previous button
if ($numpages > 1) {
    if ($cpage > 1) {
        $pages .= "<a href=\"topstud.php?com=" . $com . '&st=' . ($st - $perp) . "\">" . _MA_PEDIGREE_PREVIOUS . '</a>&nbsp;&nbsp;';
    }
}
//create numbers
for ($x = 1; $x < ($numpages + 1); ++$x) {
    //create line break after 20 number
    if (($x % 20) == 0) {
        $pages .= '<br />';
    }
    if ($x != $cpage) {
        $pages .= "<a href=\"topstud.php?com=" . $com . '&st=' . ($perp * ($x - 1)) . "\">" . $x . '</a>&nbsp;&nbsp;';
    } else {
        $pages .= $x . '&nbsp;&nbsp';
    }
}
//create next button
if ($numpages > 1) {
    if ($cpage < $numpages) {
        $pages .= "<a href=\"topstud.php?com=" . $com . '&st=' . ($st + $perp) . "\">" . _MA_PEDIGREE_NEXT . '</a>&nbsp;&nbsp;';
    }
}
//query
$queryString = 'SELECT count( d.' . $com . ' ) AS X, d.' . $com . ', p.NAAM as p_NAAM, p.father as p_father, p.mother as p_mother, p.coi as p_coi, p.foto as p_foto FROM ' . $GLOBALS['xoopsDB']->prefix('pedigree_tree') . ' d LEFT JOIN ' . $GLOBALS['xoopsDB']->prefix('pedigree_tree') . ' p ON d.' . $com . ' = p.Id WHERE d.' . $com . ' !=0 GROUP BY d.' . $com . ' ORDER BY X DESC LIMIT ' . $st . ', ' . $perp;
$result      = $GLOBALS['xoopsDB']->query($queryString);

while (false !== ($row = $GLOBALS['xoopsDB']->fetchArray($result))) {
    $numofcolumns = 2;
    if ($com === 'father') {
        $gender = "<img src=\"assets/images/male.gif\">";
    } else {
        $gender = "<img src=\"assets/images/female.gif\">";
    }
    //read coi% information if exists or create link if not
    if ($row['p_coi'] == '' || $row['p_coi'] == '0') {
        $coi = "<a href=\"coi.php?s=" . $row['p_father'] . '&d=' . $row['p_mother'] . '&dogid=' . $row[$com] . "&detail=1\">" . _MA_PEDIGREE_UNKNOWN . '</a>';
    } else {
        $coi = $row['p_coi'] . ' %';
    }
    //number of pups
    $dob = $row['X'];
    //create array for dogs
    if ($row['p_foto'] != '') {
        $camera = " <img src=\"assets/images/dog-icon25.png\">";
    } else {
        $camera = '';
    }
    $name = stripslashes($row['p_NAAM']) . $camera;
    for ($i = 1; $i < $numofcolumns; ++$i) {
        $columnvalue[] = array('value' => $coi);
        $columnvalue[] = array('value' => $row['X']);
    }
    $dogs[] = array(
        'id'          => $row[$com],
        'name'        => $name,
        'gender'      => $gender,
        'link'        => "<a href=\"pedigree.php?pedid=" . $row[$com] . "\">" . $name . '</a>',
        'colour'      => '',
        'number'      => '',
        'usercolumns' => $columnvalue
    );
    unset($columnvalue);

}
$columns[] = array('columnname' => 'Name', 'columnnumber' => 1);
$columns[] = array('columnname' => 'COI%', 'columnnumber' => 2);
$columns[] = array('columnname' => 'Offspring', 'columnnumber' => 3);

//add data to smarty template
//assign dog
$GLOBALS['xoopsTpl']->assign('dogs', $dogs);
$GLOBALS['xoopsTpl']->assign('columns', $columns);
$GLOBALS['xoopsTpl']->assign('numofcolumns', $numofcolumns);
$GLOBALS['xoopsTpl']->assign('tsarray', PedigreeUtilities::sortTable($numofcolumns));
//find last shown number
if (($st + $perp) > $numresults) {
    $lastshown = $numresults;
} else {
    $lastshown = $st + $perp;
}
//create string
$matches     = _MA_PEDIGREE_MATCHES;
$nummatchstr = $numresults . $matches . ($st + 1) . '-' . $lastshown . ' (' . $numpages . ' pages)';
$GLOBALS['xoopsTpl']->assign('nummatch', strtr($nummatchstr, array('[animalTypes]' => $moduleConfig['animalTypes'])));
$GLOBALS['xoopsTpl']->assign('pages', $pages);

//comments and footer
include $GLOBALS['xoops']->path('/footer.php');
