<?php
// -------------------------------------------------------------------------

use XoopsModules\Pedigree;

/** @var \XoopsModules\Pedigree\Helper $helper */
require_once __DIR__ . '/header.php';
$helper->loadLanguage('main');

// Get all HTTP post or get parameters into global variables that are prefixed with "param_"
//import_request_variables("gp", "param_");
extract($_GET, EXTR_PREFIX_ALL, 'param');
extract($_POST, EXTR_PREFIX_ALL, 'param');

$GLOBALS['xoopsOption']['template_main'] = 'pedigree_breeder.tpl';
include XOOPS_ROOT_PATH . '/header.php';

// Include common modlue code
require_once $helper->path('include/common.php');
//require_once XOOPS_ROOT_PATH . '/modules/' . $moduleDirName . '/include/common.php';

//@todo move hard coded language string to language file(s)
$GLOBALS['xoopsTpl']->assign('page_title', 'Pedigree database - View owner/breeder');

// Breadcrumb
$breadcrumb = new Pedigree\Breadcrumb();
$breadcrumb->addLink($helper->getModule()->getVar('name'), $helper->url());
$GLOBALS['xoopsTpl']->assign('module_home', $helper->getDirname()); // this definition is not removed for backward compatibility issues
$GLOBALS['xoopsTpl']->assign('pedigree_breadcrumb', $breadcrumb->render());

//get module configuration
/** @var \XoopsModuleHandler $moduleHandler */
$moduleHandler = xoops_getHandler('module');
$module        = $moduleHandler->getByDirname($moduleDirName);
$configHandler = xoops_getHandler('config');
$moduleConfig  = $configHandler->getConfigsByCat(0, $module->getVar('mid'));

/** @internal Code below replaced in v1.32 Alpha 1 (Oct 20, 2019)- code didn't take into account
 * that 'extract' command above adds 'param' prefix to 'incoming' variables.
 */
/*
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
if (1 == $l) {
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
*/
$f = Request::getString('f', 'lastname');
//find letter on which to start else set to 'a'
$l = Request::getString('l', 'a', 'get');
$w = $l . '%';
if ('1' === $l) {
    $l = 'LIKE';
}
$o  = Request::getString('o', 'lastname');
$d  = Request::getString('d', 'ASC');
$st = Request::getInt('st', 0);
/** @internal end of code replacement from v1.32 Alpha 1 */

$perPage = $helper->getConfig('perpage');
//$perPage = $moduleConfig['perpage'];

//iscurrent user a module admin ?
$modAdmin = $helper->isUserAdmin();
/*
$modAdmin    = false;
$xoopsModule = \XoopsModule::getByDirname($moduleDirName);
if (!empty($GLOBALS['xoopsUser'])) {
    if ($GLOBALS['xoopsUser']->isAdmin($xoopsModule->mid())) {
        $modAdmin = true;
    }
}
*/
//count total number of owners
$numowner = 'SELECT count(id) FROM ' . $GLOBALS['xoopsDB']->prefix('pedigree_owner') . ' WHERE ' . $f . " LIKE '" . $w . "'";
$numRes   = $GLOBALS['xoopsDB']->query($numowner);
//total number of owners the query will find
list($numResults) = $GLOBALS['xoopsDB']->fetchRow($numRes);
//total number of pages
$numPages = floor($numResults / $perPage) + 1;
if (($numPages * $perPage) == ($numResults + $perPage)) {
    ++$numPages;
}
//find current page
$currentPage = floor($st / $perPage) + 1;
//create alphabet
$pages = '';
/*
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
$pages .= '<br>';
*/
//create previous button
if ($numPages > 1) {
    if ($currentPage > 1) {
        $pages .= "<a href=\"" . $helper->url("breeder.php?f={$f}&o={$o}&d={$d}&l={$l}&st=" . ($st - $perPage)) . "\">" . _MA_PEDIGREE_PREVIOUS . "</a>&nbsp;&nbsp;";
    }

    // create numbers
    for ($x = 1; $x < ($numPages + 1); ++$x) {
        //create line break after 20 number
        if (0 == ($x % 20)) {
            $pages .= '<br>';
        }
        if ($x != $currentPage) {
                $pages .= "<a href=\"" . $helper->url("breeder.php?f={$f}&o={$o}&d={$d}&l={$l}&st=" . ($perPage * ($x - 1))) . "\">{$x}</a>&nbsp;&nbsp;";
        } else {
            $pages .= $x . '&nbsp;&nbsp';
        }
    }
}

//create next button
if ($numPages > 1) {
    if ($currentPage < $numPages) {
        $pages .= "<a href=\"" . $helper->url("breeder.php?f={$f}&o={$o}&d={$d}&l={$l}&st=" . ($st + $perPage)) . "\">" . _MA_PEDIGREE_NEXT . "</a>&nbsp;&nbsp;";
    }
}

//query
$queryString = 'SELECT * FROM ' . $GLOBALS['xoopsDB']->prefix('pedigree_owner') . ' WHERE ' . $f . " LIKE '" . $w . "' ORDER BY " . $o . ' ' . $d . ' LIMIT ' . $st . ', ' . $perPage;
$result      = $GLOBALS['xoopsDB']->query($queryString);

while (false !== ($row = $GLOBALS['xoopsDB']->fetchArray($result))) {
    //check for access
    $access = '';
    if (!empty($xoopsUser)) {
        if (true === $modAdmin || $row['user'] == $xoopsUser->getVar('uid')) {
            //$access = "<a href=\"dog.php?id=".$row['id']."\"><img src=\"assets/images/edit.png\" alt="._EDIT."></a>";
            $access .= "<a href=\"" . $helper->url("deletebreeder.php?id={$row['id']}") . "\"><img src=\"" . $pathIcon16 . "/delete.png\" title=\"" . _DELETE . "\" alt=\"" . _DELETE . "\"</a>";
        } else {
            $access = '';
        }
    }
    //make names
    $name = $access . "<a href=\"" . $helper->url("owner.php?ownid={$row['id']}") . "\">" . stripslashes($row['lastname']) . ', ' . stripslashes($row['firstname']) . "</a>";
    //create array for owners
    $dogs[] = [
        'id'   => $row['id'],
        'name' => $name,
        'city' => $row['city']
    ];
}

//add data to smarty template
//assign dog
if (isset($dogs)) {
    $GLOBALS['xoopsTpl']->assign('dogs', $dogs);
}
//assign links
if ('ASC' === $d) {
    $nl = "<a href=\"" . $helper->url("breeder.php?f={$f}&o=lastname&d=DESC") . "\">" . _MA_PEDIGREE_OWN_NAME . "</a>";
    $cl = "<a href=\"" . $helper->url("breeder.php?f={$f}&o=city&d=DESC") . "\">" . _MA_PEDIGREE_OWN_CITY . "</a>";
} else {
    $nl = "<a href=\"" . $helper->url("breeder.php?f={$f}&o=lastname&d=ASC") . "\">" . _MA_PEDIGREE_OWN_NAME . "</a>";
    $cl = "<a href=\"" . $helper->url("breeder.php?f={$f}&o=city&d=ASC") . "\">" . _MA_PEDIGREE_OWN_CITY . "</a>";
}
$GLOBALS['xoopsTpl']->assign('namelink', $nl);
$GLOBALS['xoopsTpl']->assign('colourlink', $cl);

//find last shown number
$lastshown = ($st + $perPage) > $numResults ? $numResults : $st + $perPage;

//create string
$matches     = _MA_PEDIGREE_MATCHESB;
$nummatchstr = $numResults . $matches . ($st + 1) . '-' . $lastshown . ' (' . $numPages . ' pages)';
$GLOBALS['xoopsTpl']->assign('nummatch', $nummatchstr);
$GLOBALS['xoopsTpl']->assign('pages', $pages);

$criteria     = $helper->getHandler('Tree')->getActiveCriteria();
$activeObject = 'owner';
$name         = 'lastname';
$link         = $helper->url("breeder.php?f={$name}&amp;o={$name}&amp;d=ASC&amp;st=0&amp;l=");
$link2        = '';

$breederArray['letters'] = Pedigree\Utility::lettersChoice($helper, $activeObject, $criteria, $name, $link, $link2);
//$catarray['toolbar']     = pedigree_toolbar();

$GLOBALS['xoopsTpl']->assign('breederArray', $breederArray);
$GLOBALS['xoopsTpl']->assign('pageTitle', _MA_PEDIGREE_BREEDER_PAGETITLE);

//comments and footer
include XOOPS_ROOT_PATH . '/footer.php';
