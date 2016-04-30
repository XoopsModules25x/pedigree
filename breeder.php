<?php
// -------------------------------------------------------------------------

require_once dirname(dirname(__DIR__)) . '/mainfile.php';
$moduleDirName = basename(__DIR__);
xoops_loadLanguage('main', $moduleDirName);
// Include any common code for this module.

// Get all HTTP post or get parameters into global variables that are prefixed with "param_"
//import_request_variables("gp", "param_");
extract($_GET, EXTR_PREFIX_ALL, 'param');
extract($_POST, EXTR_PREFIX_ALL, 'param');

$xoopsOption['template_main'] = 'pedigree_breeder.tpl';

include XOOPS_ROOT_PATH . '/header.php';
// Include any common code for this module.
require_once(XOOPS_ROOT_PATH . '/modules/' . $moduleDirName . '/include/common.php');
$xoopsTpl->assign('page_title', 'Pedigree database - View owner/breeder');

//get module configuration
$moduleHandler = xoops_getHandler('module');
$module        = $moduleHandler->getByDirname('pedigree');
$configHandler = xoops_getHandler('config');
$moduleConfig  = $configHandler->getConfigsByCat(0, $module->getVar('mid'));

if (!isset($f)) {
    $f = 'lastname';
}
//find letter on which to start else set to 'a'
if (isset($_GET['l'])) {
    $l = $_GET['l'];
} else {
    $l = 'a';
}
$w = $l . '%';
if ($l == 1) {
    $l = 'LIKE';
}
if (!isset($o)) {
    $o = 'lastname';
}
if (!isset($d)) {
    $d = 'ASC';
}
if (!isset($st)) {
    $st = 0;
}

$perp = $moduleConfig['perpage'];

global $xoopsTpl;
global $xoopsDB;
global $xoopsModuleConfig;

//iscurrent user a module admin ?
$modadmin    = false;
$xoopsModule = XoopsModule::getByDirname('pedigree');
if (!empty($xoopsUser)) {
    if ($xoopsUser->isAdmin($xoopsModule->mid())) {
        $modadmin = true;
    }
}

//count total number of owners
$numowner = 'SELECT COUNT(Id) FROM ' . $GLOBALS['xoopsDB']->prefix('pedigree_owner') . ' WHERE ' . $f . " LIKE '" . $w . "'";
$numres   = $GLOBALS['xoopsDB']->query($numowner);
//total number of owners the query will find
list($numresults) = $GLOBALS['xoopsDB']->fetchRow($numres);
//total number of pages
$numpages = floor($numresults / $perp) + 1;
if (($numpages * $perp) == ($numresults + $perp)) {
    ++$numpages;
}
//find current page
$cpage = floor($st / $perp) + 1;
//create alphabet
$pages = '';
$pages .= "<a href=\"breeder.php?f=" . $f . '&o=' . $o . '&d=' . $d . "&st=0&l=a\">A</a>&nbsp;";
$pages .= "<a href=\"breeder.php?f=" . $f . '&o=' . $o . '&d=' . $d . "&st=0&l=b\">B</a>&nbsp;";
$pages .= "<a href=\"breeder.php?f=" . $f . '&o=' . $o . '&d=' . $d . "&st=0&l=c\">C</a>&nbsp;";
$pages .= "<a href=\"breeder.php?f=" . $f . '&o=' . $o . '&d=' . $d . "&st=0&l=d\">D</a>&nbsp;";
$pages .= "<a href=\"breeder.php?f=" . $f . '&o=' . $o . '&d=' . $d . "&st=0&l=e\">E</a>&nbsp;";
$pages .= "<a href=\"breeder.php?f=" . $f . '&o=' . $o . '&d=' . $d . "&st=0&l=f\">F</a>&nbsp;";
$pages .= "<a href=\"breeder.php?f=" . $f . '&o=' . $o . '&d=' . $d . "&st=0&l=g\">G</a>&nbsp;";
$pages .= "<a href=\"breeder.php?f=" . $f . '&o=' . $o . '&d=' . $d . "&st=0&l=h\">H</a>&nbsp;";
$pages .= "<a href=\"breeder.php?f=" . $f . '&o=' . $o . '&d=' . $d . "&st=0&l=i\">I</a>&nbsp;";
$pages .= "<a href=\"breeder.php?f=" . $f . '&o=' . $o . '&d=' . $d . "&st=0&l=j\">J</a>&nbsp;";
$pages .= "<a href=\"breeder.php?f=" . $f . '&o=' . $o . '&d=' . $d . "&st=0&l=k\">K</a>&nbsp;";
$pages .= "<a href=\"breeder.php?f=" . $f . '&o=' . $o . '&d=' . $d . "&st=0&l=l\">L</a>&nbsp;";
$pages .= "<a href=\"breeder.php?f=" . $f . '&o=' . $o . '&d=' . $d . "&st=0&l=m\">M</a>&nbsp;";
$pages .= "<a href=\"breeder.php?f=" . $f . '&o=' . $o . '&d=' . $d . "&st=0&l=n\">N</a>&nbsp;";
$pages .= "<a href=\"breeder.php?f=" . $f . '&o=' . $o . '&d=' . $d . "&st=0&l=o\">O</a>&nbsp;";
$pages .= "<a href=\"breeder.php?f=" . $f . '&o=' . $o . '&d=' . $d . "&st=0&l=p\">P</a>&nbsp;";
$pages .= "<a href=\"breeder.php?f=" . $f . '&o=' . $o . '&d=' . $d . "&st=0&l=q\">Q</a>&nbsp;";
$pages .= "<a href=\"breeder.php?f=" . $f . '&o=' . $o . '&d=' . $d . "&st=0&l=r\">R</a>&nbsp;";
$pages .= "<a href=\"breeder.php?f=" . $f . '&o=' . $o . '&d=' . $d . "&st=0&l=s\">S</a>&nbsp;";
$pages .= "<a href=\"breeder.php?f=" . $f . '&o=' . $o . '&d=' . $d . "&st=0&l=t\">T</a>&nbsp;";
$pages .= "<a href=\"breeder.php?f=" . $f . '&o=' . $o . '&d=' . $d . "&st=0&l=u\">U</a>&nbsp;";
$pages .= "<a href=\"breeder.php?f=" . $f . '&o=' . $o . '&d=' . $d . "&st=0&l=v\">V</a>&nbsp;";
$pages .= "<a href=\"breeder.php?f=" . $f . '&o=' . $o . '&d=' . $d . "&st=0&l=w\">W</a>&nbsp;";
$pages .= "<a href=\"breeder.php?f=" . $f . '&o=' . $o . '&d=' . $d . "&st=0&l=x\">X</a>&nbsp;";
$pages .= "<a href=\"breeder.php?f=" . $f . '&o=' . $o . '&d=' . $d . "&st=0&l=y\">Y</a>&nbsp;";
$pages .= "<a href=\"breeder.php?f=" . $f . '&o=' . $o . '&d=' . $d . "&st=0&l=z\">Z</a>&nbsp;";
//create linebreak
$pages .= '<br />';
//create previous button
if ($numpages > 1) {
    if ($cpage > 1) {
        $pages .= "<a href=\"breeder.php?f=" . $f . '&o=' . $o . '&d=' . $d . '&l=' . $l . '&st=' . ($st - $perp) . "\">" . _MA_PEDIGREE_PREVIOUS . '</a>&nbsp;&nbsp;';
    }
}
//create numbers
for ($x = 1; $x < ($numpages + 1); ++$x) {
    //create line break after 20 number
    if (($x % 20) == 0) {
        $pages .= '<br />';
    }
    if ($x != $cpage) {
        $pages .= "<a href=\"breeder.php?f=" . $f . '&o=' . $o . '&d=' . $d . '&l=' . $l . '&st=' . ($perp * ($x - 1)) . "\">" . $x . '</a>&nbsp;&nbsp;';
    } else {
        $pages .= $x . '&nbsp;&nbsp';
    }
}
//create next button
if ($numpages > 1) {
    if ($cpage < $numpages) {
        $pages .= "<a href=\"breeder.php?f=" . $f . '&o=' . $o . '&d=' . $d . '&l=' . $l . '&st=' . ($st + $perp) . "\">" . _MA_PEDIGREE_NEXT . '</a>&nbsp;&nbsp;';
    }
}

//query
$queryString = 'SELECT * from ' . $GLOBALS['xoopsDB']->prefix('pedigree_owner') . ' WHERE ' . $f . " LIKE '" . $w . "' ORDER BY " . $o . ' ' . $d . ' LIMIT ' . $st . ', ' . $perp;
$result      = $GLOBALS['xoopsDB']->query($queryString);

while (false !== ($row = $GLOBALS['xoopsDB']->fetchArray($result))) {
    print_r();
    //check for access
    $access = '';
    if (!empty($xoopsUser)) {
        if ($row['user'] == $xoopsUser->getVar('uid') || $modadmin === true) {
            //$access = "<a href=\"dog.php?Id=".$row['Id']."\"><img src=\"assets/images/edit.gif\" alt="._MA_PEDIGREE_BTN_EDIT."></a>";
            $access .= "<a href=\"deletebreeder.php?Id=" . $row['Id'] . "\"><img src=\"assets/images/delete.gif\" alt=" . _MA_PEDIGREE_BTN_DELE . '></a>';
        } else {
            $access = '';
        }
    }
    //make names
    $name = $access . "<a href=\"owner.php?ownid=" . $row['Id'] . "\">" . stripslashes($row['lastname']) . ', ' . stripslashes($row['firstname']) . '</a>';
    //create array for owners
    $dogs[] = array(
        'id'   => $row['Id'],
        'name' => $name,
        'city' => $row['city']
    );
}

//add data to smarty template
//assign dog
if (isset($dogs)) {
    $xoopsTpl->assign('dogs', $dogs);
}
//assign links
if ($d === 'ASC') {
    $nl = "<a href=\"breeder.php?f=" . $f . "&o=lastname&d=DESC\">" . _MA_PEDIGREE_OWN_NAME . '</a>';
    $cl = "<a href=\"breeder.php?f=" . $f . "&o=city&d=DESC\">" . _MA_PEDIGREE_OWN_CITY . '</a>';
} else {
    $nl = "<a href=\"breeder.php?f=" . $f . "&o=lastname&d=ASC\">" . _MA_PEDIGREE_OWN_NAME . '</a>';
    $cl = "<a href=\"breeder.php?f=" . $f . "&o=city&d=ASC\">" . _MA_PEDIGREE_OWN_CITY . '</a>';
}
$xoopsTpl->assign('namelink', $nl);
$xoopsTpl->assign('colourlink', $cl);

//find last shown number
if (($st + $perp) > $numresults) {
    $lastshown = $numresults;
} else {
    $lastshown = $st + $perp;
}
//create string
$matches     = _MA_PEDIGREE_MATCHESB;
$nummatchstr = $numresults . $matches . ($st + 1) . '-' . $lastshown . ' (' . $numpages . ' pages)';
$xoopsTpl->assign('nummatch', $nummatchstr);
$xoopsTpl->assign('pages', $pages);

//$breederArray['letters']          = PedigreeUtilities::lettersChoice();

$myObject     = PedigreePedigree::getInstance();
$criteria     = $myObject->getHandler('tree')->getActiveCriteria();
$activeObject = 'owner';
$name         = 'lastname';
$file         = 'breeder.php';
$file2        = "breeder.php?f={$name}&amp;o={$name}&amp;d=ASC&amp;st=0&amp;l={$letter}";

$breederArray['letters'] = PedigreeUtilities::lettersChoice($myObject, $activeObject, $criteria, $name, $file, $file2);
//$catarray['toolbar']          = pedigree_toolbar();
$xoopsTpl->assign('breederArray', $breederArray);

//comments and footer
include XOOPS_ROOT_PATH . '/footer.php';
