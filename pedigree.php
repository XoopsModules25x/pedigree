<?php
// -------------------------------------------------------------------------


$rootPath  = dirname(dirname(__DIR__));
$mydirname = basename(__DIR__);
$mydirpath = dirname(__DIR__);

include_once $rootPath . '/mainfile.php';
//include_once $rootPath . '/include/cp_functions.php';
//require_once $rootPath . '/include/cp_header.php';
//include_once $rootPath . '/class/xoopsformloader.php';


//require_once dirname(dirname(__DIR__)) . '/mainfile.php';


/*
if (file_exists(XOOPS_ROOT_PATH . "/modules/" . $xoopsModule->dirname() . "/language/" . $xoopsConfig['language'] . "/main.php")) {
    require_once XOOPS_ROOT_PATH . "/modules/" . $xoopsModule->dirname() . "/language/" . $xoopsConfig['language'] . "/main.php";
} else {
    include_once XOOPS_ROOT_PATH . "/modules/" . $xoopsModule->dirname() . "/language/english/main.php";
}
*/

//xoops_cp_header();

xoops_loadLanguage('main', basename(__DIR__));

include_once __DIR__ . '/header.php';

// Include any common code for this module.
require_once(XOOPS_ROOT_PATH . '/modules/' . $xoopsModule->dirname() . '/include/functions.php');
//require_once(XOOPS_ROOT_PATH ."/modules/" . $xoopsModule->dirname() . "/include/css.php");
// Get all HTTP post or get parameters into global variables that are prefixed with "param_"
//import_request_variables("gp", "param_");
extract($_GET, EXTR_PREFIX_ALL, 'param');
extract($_POST, EXTR_PREFIX_ALL, 'param');

// This page uses smarty templates. Set "$xoopsOption['template_main']" before including header
$xoopsOption['template_main'] = 'pedigree_pedigree.tpl';

include XOOPS_ROOT_PATH . '/header.php';

$GLOBALS['xoTheme']->addScript('browse.php?Frameworks/jquery/jquery.js');
$GLOBALS['xoTheme']->addScript('browse.php?modules/' . $mydirname . '/assets/js/jquery.magnific-popup.min.js');
$GLOBALS['xoTheme']->addStylesheet('browse.php?modules/' . $mydirname . '/assets/css/style.css');
$GLOBALS['xoTheme']->addStylesheet('browse.php?modules/' . $mydirname . '/assets/css/magnific-popup.css');

require_once(XOOPS_ROOT_PATH . '/class/xoopsformloader.php');
require_once(XOOPS_ROOT_PATH . '/modules/' . $xoopsModule->dirname() . '/include/class_field.php');

//get module configuration
$module_handler =& xoops_gethandler('module');
$module         =& $module_handler->getByDirname('pedigree');
$config_handler =& xoops_gethandler('config');
$moduleConfig   =& $config_handler->getConfigsByCat(0, $module->getVar('mid'));

//draw pedigree
pedigree_main($_GET['pedid']);

//comments and footer
//include XOOPS_ROOT_PATH . "/footer.php";
include __DIR__ . '/footer.php';

//
// Displays the "Main" tab of the module
//
/**
 * @param $ID
 */
function pedigree_main($ID)
{
    global $xoopsTpl;
    global $xoopsDB;
    global $moduleConfig;

    $a      = (!isset($_GET['pedid']) ? $a = 1 : $a = $_GET['pedid']);
    $animal = new Animal($a);
    //test to find out how many user fields there are..
    $fields = $animal->numoffields();

    $qarray = array('d', 'f', 'm', 'ff', 'mf', 'fm', 'mm', 'fff', 'ffm', 'fmf', 'fmm', 'mmf', 'mff', 'mfm', 'mmm');

    $querystring = 'SELECT ';

    foreach ($qarray as $key) {
        $querystring .= $key . '.id as ' . $key . '_id, ';
        $querystring .= $key . '.naam as ' . $key . '_naam, ';
        $querystring .= $key . '.mother as ' . $key . '_mother, ';
        $querystring .= $key . '.father as ' . $key . '_father, ';
        $querystring .= $key . '.roft as ' . $key . '_roft, ';
        $querystring .= $key . '.foto as ' . $key . '_foto, ';
    }

    $querystring .= 'mmm.coi as mmm_coi FROM ' . $xoopsDB->prefix('pedigree_tree') . ' d
    LEFT JOIN ' . $xoopsDB->prefix('pedigree_tree') . ' f ON d.father = f.id
    LEFT JOIN ' . $xoopsDB->prefix('pedigree_tree') . ' m ON d.mother = m.id
    LEFT JOIN ' . $xoopsDB->prefix('pedigree_tree') . ' ff ON f.father = ff.id
    LEFT JOIN ' . $xoopsDB->prefix('pedigree_tree') . ' fff ON ff.father = fff.id
    LEFT JOIN ' . $xoopsDB->prefix('pedigree_tree') . ' ffm ON ff.mother = ffm.id
    LEFT JOIN ' . $xoopsDB->prefix('pedigree_tree') . ' mf ON m.father = mf.id
    LEFT JOIN ' . $xoopsDB->prefix('pedigree_tree') . ' mff ON mf.father = mff.id
    LEFT JOIN ' . $xoopsDB->prefix('pedigree_tree') . ' mfm ON mf.mother = mfm.id
    LEFT JOIN ' . $xoopsDB->prefix('pedigree_tree') . ' fm ON f.mother = fm.id
    LEFT JOIN ' . $xoopsDB->prefix('pedigree_tree') . ' fmf ON fm.father = fmf.id
    LEFT JOIN ' . $xoopsDB->prefix('pedigree_tree') . ' fmm ON fm.mother = fmm.id
    LEFT JOIN ' . $xoopsDB->prefix('pedigree_tree') . ' mm ON m.mother = mm.id
    LEFT JOIN ' . $xoopsDB->prefix('pedigree_tree') . ' mmf ON mm.father = mmf.id
    LEFT JOIN ' . $xoopsDB->prefix('pedigree_tree') . " mmm ON mm.mother = mmm.id
    where d.id=$ID";

    $result = $xoopsDB->query($querystring);

    while ($row = $xoopsDB->fetchArray($result)) {
        //create array for animal (and all parents)
        foreach ($qarray as $key) {
            $d[$key]['id']     = $row[$key . '_id'];
            $d[$key]['name']   = stripslashes($row[$key . '_naam']);
            $d[$key]['mother'] = $row[$key . '_mother'];
            $d[$key]['father'] = $row[$key . '_father'];
            $d[$key]['roft']   = $row[$key . '_roft'];
            $d[$key]['nhsb']   = '';
            if (strlen($key) == 3 && $moduleConfig['lastimage'] == 0) {
                //do not show image in last row of pedigree
            } else {
                //check if image exists
                if ($row[$key . '_foto'] != '') {
                    $d[$key]['photo']    = PEDIGREE_UPLOAD_URL . '/images/thumbnails/' . $row[$key . '_foto'] . '_150.jpeg';
                    $d[$key]['photoBig'] = PEDIGREE_UPLOAD_URL . '/images/' . $row[$key . '_foto'];
                }
            }

            $d[$key]['overig'] = '';
            // $pedidata to hold viewable data to be shown in pedigree
            $pedidata = '';

            if (!$d[$key]['id'] == '') {
                //if exists create animal object
                $animal = new Animal($d[$key]['id']);
                $fields = $animal->numoffields();
            }
            for ($i = 0; $i < count($fields); ++$i) {
                $userfield = new Field($fields[$i], $animal->getconfig());
                if ($userfield->active() && $userfield->inpedigree()) {
                    $fieldType   = $userfield->getSetting('FieldType');
                    $fieldobject = new $fieldType($userfield, $animal);
                    $pedidata .= $fieldobject->showField() . '<br />';
                }
                $d[$key]['hd'] = $pedidata;
            }

        }

    }

    //add data to smarty template
    $xoopsTpl->assign('page_title', stripslashes($row['d_naam']));
    //assign dog
    $xoopsTpl->assign('d', $d);
    //assign config options

    $xoopsTpl->assign('male', "<img src=\"assets/images/male.gif\">");
    $xoopsTpl->assign('female', "<img src=\"assets/images/female.gif\">");

    //assign extra display options
    $xoopsTpl->assign('unknown', 'Unknown');
    $xoopsTpl->assign('SD', _MA_PEDIGREE_SD);
    $xoopsTpl->assign('PA', _MA_PEDIGREE_PA);
    $xoopsTpl->assign('GP', _MA_PEDIGREE_GP);
    $xoopsTpl->assign('GGP', _MA_PEDIGREE_GGP);
}
