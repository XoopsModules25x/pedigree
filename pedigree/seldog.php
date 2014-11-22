<?php
// -------------------------------------------------------------------------

require_once dirname(dirname(__DIR__)) . '/mainfile.php';

/*
if (file_exists(XOOPS_ROOT_PATH . "/modules/" . $xoopsModule->dirname() . "/language/" . $xoopsConfig['language'] . "/main.php")) {
    require_once XOOPS_ROOT_PATH . "/modules/" . $xoopsModule->dirname() . "/language/" . $xoopsConfig['language'] . "/main.php";
} else {
    include_once XOOPS_ROOT_PATH . "/modules/" . $xoopsModule->dirname() . "/language/english/main.php";
}
*/

xoops_loadLanguage('main', basename(dirname(__DIR__)));

// Include any common code for this module.
require_once(XOOPS_ROOT_PATH . "/modules/" . $xoopsModule->dirname() . "/include/functions.php");

// Get all HTTP post or get parameters into global variables that are prefixed with "param_"
//import_request_variables("gp", "param_");
extract($_GET, EXTR_PREFIX_ALL, "param");
extract($_POST, EXTR_PREFIX_ALL, "param");

$xoopsOption['template_main'] = "pedigree_sel.html";

include XOOPS_ROOT_PATH . '/header.php';

//get module configuration
$module_handler =& xoops_gethandler('module');
$module         =& $module_handler->getByDirname("pedigree");
$config_handler =& xoops_gethandler('config');
$moduleConfig   =& $config_handler->getConfigsByCat(0, $module->getVar('mid'));

$st = isset($_GET['st']) ? $_GET['st'] : '';
if (!$st) {
    $st = 0;
}
$curval = $_GET['curval'];
$letter = $_GET['letter'];
$gend   = $_GET['gend'];

if (!$letter) {
    $letter = "a";
}
if (!$gend) {
    $gend = 0;
}

$perp = $moduleConfig['perpage'];

global $xoopsTpl;
global $xoopsDB;
global $xoopsModuleConfig;

$xoopsTpl->assign("page_title", _MI_PEDIGREE_TITLE);

//count total number of dogs
$numdog = "SELECT ID from " . $xoopsDB->prefix("pedigree_tree") . " WHERE NAAM LIKE '" . $letter . "%' and roft = '" . $gend . "'";
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
//create alphabet
$pages = "";
for ($i = 65; $i <= 90; ++$i) {
    if ($letter == chr($i)) {
        $pages .= "<b><a href=\"seldog.php?gend=" . $gend . "&curval=" . $curval . "&letter=" . chr($i) . "\">" . chr($i) . "</a></b>&nbsp;";
    } else {
        $pages .= "<a href=\"seldog.php?gend=" . $gend . "&curval=" . $curval . "&letter=" . chr($i) . "\">" . chr($i) . "</a>&nbsp;";
    }
}
$pages .= "-&nbsp;";
$pages .= "<a href=\"seldog.php?gend=" . $gend . "&curval=" . $curval . "&letter=Ã…\">Ã…</a>&nbsp;";
$pages .= "<a href=\"seldog.php?gend=" . $gend . "&curval=" . $curval . "&letter=Ã–\">Ã–</a>&nbsp;";
//create linebreak
$pages .= "<br />";
//create previous button
if ($numpages > 1) {
    if ($cpage > 1) {
        $pages .= "<a href=\"seldog.php?gend=" . $gend . "&curval=" . $curval . "&letter=" . $letter . "&st=" . ($st - $perp) . "\">" . _MA_PEDIGREE_PREVIOUS . "</a>&nbsp;&nbsp";
    }
}
//create numbers
for ($x = 1; $x < ($numpages + 1); ++$x) {
    //create line break after 20 number
    if (($x % 20) == 0) {
        $pages .= "<br />";
    }
    if ($x != $cpage) {
        $pages .= "<a href=\"seldog.php?gend=" . $gend . "&curval=" . $curval . "&letter=" . $letter . "&st=" . ($perp * ($x - 1)) . "\">" . $x . "</a>&nbsp;&nbsp";
    } else {
        $pages .= $x . "&nbsp;&nbsp";
    }
}
//create next button
if ($numpages > 1) {
    if ($cpage < ($numpages)) {
        $pages .= "<a href=\"seldog.php?gend=" . $gend . "&curval=" . $curval . "&letter=" . $letter . "&st=" . ($st + $perp) . "\">" . _MA_PEDIGREE_NEXT . "</a>&nbsp;&nbsp";
    }
}

//query
$queryString = "SELECT * FROM " . $xoopsDB->prefix("pedigree_tree") . " where NAAM like '" . $letter . "%' and roft = '" . $gend . "' order by NAAM LIMIT " . $st . ", " . $perp;
$result      = $xoopsDB->query($queryString);

$animal = new Animal();
//test to find out how many user fields there are...
$fields       = $animal->numoffields();
$numofcolumns = 1;
$columns[]    = array('columnname' => "Name");
for ($i = 0; $i < count($fields); ++$i) {
    $userfield   = new Field($fields[$i], $animal->getconfig());
    $fieldType   = $userfield->getSetting("FieldType");
    $fieldobject = new $fieldType($userfield, $animal);
    //create empty string
    $lookupvalues = "";
    if ($userfield->active() && $userfield->inlist()) {
        if ($userfield->haslookup()) {
            $lookupvalues = $userfield->lookup($fields[$i]);
            //debug information
            //print_r($lookupvalues);
        }
        $columns[] = array('columnname' => $fieldobject->fieldname, 'columnnumber' => $userfield->getID(), 'lookupval' => $lookupvalues);
        ++$numofcolumns;
        unset($lookupvalues);
    }
}

for ($i = 1; $i < ($numofcolumns); ++$i) {
    $empty[] = array('value' => "");
}
if ($gend == '0') {
    $dogs [] = array(
        'id'          => "0",
        'name'        => "",
        'gender'      => "",
        'link'        => "<a href=\"updatepage.php?gend=" . $gend . "&curval=" . $curval . "&thisid=0\">" . strtr(_MA_PEDIGREE_ADD_SIREUNKNOWN, array('[father]' => $moduleConfig['father'])) . "</a>",
        'colour'      => "",
        'number'      => "",
        'usercolumns' => $empty
    );
} else {
    $dogs [] = array(
        'id'          => "0",
        'name'        => "",
        'gender'      => "",
        'link'        => "<a href=\"updatepage.php?gend=" . $gend . "&curval=" . $curval . "&thisid=0\">" . strtr(_MA_PEDIGREE_ADD_DAMUNKNOWN, array('[mother]' => $moduleConfig['mother'])) . "</a>",
        'colour'      => "",
        'number'      => "",
        'usercolumns' => $empty
    );
}

while ($row = $xoopsDB->fetchArray($result)) {
    //create picture information
    if ($row['foto'] != '') {
        $camera = " <img src=\"images/camera.png\">";
    } else {
        $camera = "";
    }
    $name = stripslashes($row['NAAM']) . $camera;
    //empty array
    unset($columnvalue);
    //fill array
    for ($i = 1; $i < ($numofcolumns); ++$i) {
        $x = $columns[$i]['columnnumber'];
        if (is_array($columns[$i]['lookupval'])) {
            foreach ($columns[$i]['lookupval'] as $key => $keyvalue) {
                if ($key == $row['user' . $x]) {
                    $value = $keyvalue['value'];
                }
            }
            //debug information
            ///echo $columns[$i]['columnname']."is an array !";
        } //format value - cant use object because of query count
        elseif (substr($row['user' . $x], 0, 7) == 'http://') {
            $value = "<a href=\"" . $row['user' . $x] . "\">" . $row['user' . $x] . "</a>";
        } else {
            $value = $row['user' . $x];
        }
        $columnvalue[] = array('value' => $value);
    }
    if ($gend == '0') {
        $dogs[] = array(
            'id'          => $row['ID'],
            'name'        => $name,
            'gender'      => '<img src="images/male.gif">',
            'link'        => "<a href=\"updatepage.php?gend=" . $gend . "&curval=" . $curval . "&thisid=" . $row['ID'] . "\">" . $name . "</a>",
            'colour'      => "",
            'number'      => "",
            'usercolumns' => $columnvalue
        );
    } else {
        $dogs[] = array(
            'id'          => $row['ID'],
            'name'        => $name,
            'gender'      => '<img src="images/female.gif">',
            'link'        => "<a href=\"updatepage.php?gend=" . $gend . "&curval=" . $curval . "&thisid=" . $row['ID'] . "\">" . $name . "</a>",
            'colour'      => "",
            'number'      => "",
            'usercolumns' => $columnvalue
        );
    }
}

//add data to smarty template
//assign dog
$xoopsTpl->assign("dogs", $dogs);
$xoopsTpl->assign("columns", $columns);
$xoopsTpl->assign("numofcolumns", $numofcolumns);
$xoopsTpl->assign("tsarray", sorttable($numofcolumns));
//add data to smarty template
if ($gend == '0') {
    $seltitparent = strtr(_MA_PEDIGREE_FLD_FATH, array('[father]' => $moduleConfig['father']));
} else {
    $seltitparent = strtr(_MA_PEDIGREE_FLD_MOTH, array('[mother]' => $moduleConfig['mother']));
}
$seltitle = _MA_PEDIGREE_SEL . $seltitparent . _MA_PEDIGREE_FROM . getname($curval);

$xoopsTpl->assign("seltitle", $seltitle);

//find last shown number
if (($st + $perp) > $numresults) {
    $lastshown = $numresults;
} else {
    $lastshown = $st + $perp;
}
//create string
$matches     = strtr(_MA_PEDIGREE_MATCHES, array('[animalTypes]' => $moduleConfig['animalTypes']));
$nummatchstr = $numresults . $matches . ($st + 1) . "-" . $lastshown . " (" . $numpages . " pages)";
$xoopsTpl->assign("nummatch", $nummatchstr);
$xoopsTpl->assign("pages", $pages);
$xoopsTpl->assign("curval", $curval);
//comments and footer
include XOOPS_ROOT_PATH . "/footer.php";
