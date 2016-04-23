<?php
// -------------------------------------------------------------------------

require_once dirname(dirname(__DIR__)) . '/mainfile.php';

//if (file_exists(XOOPS_ROOT_PATH . "/modules/" . $xoopsModule->dirname() . "/language/" . $xoopsConfig['language'] . "/main.php")) {
//    require_once XOOPS_ROOT_PATH . "/modules/" . $xoopsModule->dirname() . "/language/" . $xoopsConfig['language'] . "/main.php";
//} else {
//    include_once XOOPS_ROOT_PATH . "/modules/" . $xoopsModule->dirname() . "/language/english/main.php";
//}

xoops_loadLanguage('main', basename(dirname(__DIR__)));

// Include any common code for this module.
require_once(XOOPS_ROOT_PATH . "/modules/" . $xoopsModule->dirname() . "/include/functions.php");

// Get all HTTP post or get parameters into global variables that are prefixed with "param_"
//import_request_variables("gp", "param_");
extract($_GET, EXTR_PREFIX_ALL, "param");
extract($_POST, EXTR_PREFIX_ALL, "param");

$xoopsOption['template_main'] = "pedigree_result.tpl";

include XOOPS_ROOT_PATH . '/header.php';

//get module configuration
$module_handler = xoops_getHandler('module');
$module         = $module_handler->getByDirname("pedigree");
$config_handler = xoops_getHandler('config');
$moduleConfig   = $config_handler->getConfigsByCat(0, $module->getVar('mid'));

$perp = $moduleConfig['perpage'];

global $xoopsTpl;
global $xoopsDB;
global $xoopsModuleConfig;

$st = isset($_GET['st']) ? $_GET['st'] : null;
if (!$st) {
    $st = 0;
}

$com = $_GET['com'];
if (!$com) {
    $com = "father";
}

$dogs         = array(); // an empty array
$numofcolumns = 0;
$pages        = '';

//count total number of dogs
$numdog = "SELECT count( " . $com . " ) AS X, " . $com . " FROM " . $xoopsDB->prefix("pedigree_tree") . " WHERE " . $com . " !=0 GROUP BY " . $com;
$numres = $xoopsDB->query($numdog);
//total number of dogs the query will find
$numresults = $xoopsDB->getRowsNum($numres);
//total number of pages
$numpages = (floor($numresults / $perp)) + 1;
if (($numpages * $perp) == ($numresults + $perp)) {
    $numpages = $numpages - 1;
}
//find current page
$cpage = (floor($st / $perp)) + 1;
//create previous button
if ($numpages > 1) {
    if ($cpage > 1) {
        $pages .= "<a href=\"topstud.php?com=" . $com . "&st=" . ($st - $perp) . "\">" . _MA_PEDIGREE_PREVIOUS . "</a>&nbsp;&nbsp;";
    }
}
//create numbers
for ($x = 1; $x < ($numpages + 1); ++$x) {
    //create line break after 20 number
    if (($x % 20) == 0) {
        $pages .= "<br />";
    }
    if ($x != $cpage) {
        $pages .= "<a href=\"topstud.php?com=" . $com . "&st=" . ($perp * ($x - 1)) . "\">" . $x . "</a>&nbsp;&nbsp;";
    } else {
        $pages .= $x . "&nbsp;&nbsp";
    }
}
//create next button
if ($numpages > 1) {
    if ($cpage < ($numpages)) {
        $pages .= "<a href=\"topstud.php?com=" . $com . "&st=" . ($st + $perp) . "\">" . _MA_PEDIGREE_NEXT . "</a>&nbsp;&nbsp;";
    }
}
//query
$queryString
        =
    "SELECT count( d." . $com . " ) AS X, d." . $com . ", p.NAAM as p_NAAM, p.father as p_father, p.mother as p_mother, p.coi as p_coi, p.foto as p_foto FROM " . $xoopsDB->prefix("pedigree_tree")
    . " d LEFT JOIN " . $xoopsDB->prefix("pedigree_tree") . " p ON d." . $com . " = p.id WHERE d." . $com . " !=0 GROUP BY d." . $com . " ORDER BY X DESC LIMIT " . $st . ", " . $perp;
$result = $xoopsDB->query($queryString);

while ($row = $xoopsDB->fetchArray($result)) {
    $numofcolumns = 2;
    if ($com == "father") {
        $gender = "<img src=\"assets/images/male.gif\">";
    } else {
        $gender = "<img src=\"assets/images/female.gif\">";
    }
    //read coi% information if exists or create link if not
    if ($row['p_coi'] == "" || $row['p_coi'] == "0") {
        $coi = "<a href=\"coi.php?s=" . $row['p_father'] . "&d=" . $row['p_mother'] . "&dogid=" . $row[$com] . "&detail=1\">" . _MA_PEDIGREE_UNKNOWN . "</a>";
    } else {
        $coi = $row['p_coi'] . " %";
    }
    //number of pups
    $dob = $row['X'];
    //create array for dogs
    if ($row['p_foto'] != '') {
        $camera = " <img src=\"assets/images/file-picture-icon.png\">";
    } else {
        $camera = "";
    }
    $name = stripslashes($row['p_NAAM']) . $camera;
    for ($i = 1; $i < ($numofcolumns); ++$i) {
        $columnvalue[] = array('value' => $coi);
        $columnvalue[] = array('value' => $row['X']);
    }
    $dogs[] = array(
        'id'          => $row[$com],
        'name'        => $name,
        'gender'      => $gender,
        'link'        => "<a href=\"pedigree.php?pedid=" . $row[$com] . "\">" . $name . "</a>",
        'colour'      => '',
        'number'      => '',
        'usercolumns' => $columnvalue
    );
    unset($columnvalue);

}
$columns[] = array('columnname' => "Name", 'columnnumber' => 1);
$columns[] = array('columnname' => "COI%", 'columnnumber' => 2);
$columns[] = array('columnname' => "Offspring", 'columnnumber' => 3);

//add data to smarty template
//assign dog
$xoopsTpl->assign("dogs", $dogs);
$xoopsTpl->assign("columns", $columns);
$xoopsTpl->assign("numofcolumns", $numofcolumns);
$xoopsTpl->assign("tsarray", sorttable($numofcolumns));
//find last shown number
if (($st + $perp) > $numresults) {
    $lastshown = $numresults;
} else {
    $lastshown = $st + $perp;
}
//create string
$matches     = _MA_PEDIGREE_MATCHES;
$nummatchstr = $numresults . $matches . ($st + 1) . "-" . $lastshown . " (" . $numpages . " pages)";
$xoopsTpl->assign("nummatch", strtr($nummatchstr, array('[animalTypes]' => $moduleConfig['animalTypes'])));
$xoopsTpl->assign("pages", $pages);

//comments and footer
include XOOPS_ROOT_PATH . "/footer.php";
