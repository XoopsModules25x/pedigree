<?php
/*
 You may not change or alter any portion of this comment or credits
 of supporting developers from this source code or any supporting source code
 which is considered copyrighted (c) material of the original comment or credit authors.

 This program is distributed in the hope that it will be useful,
 but WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
*/

/**
 * animal module for xoops
 *
 * @copyright       {@link http://sourceforge.net/projects/thmod/ The TXMod XOOPS Project}
 * @copyright       {@link http://sourceforge.net/projects/xoops/ The XOOPS Project}
 * @license         GPL 2.0 or later
 * @package         pedigree
 * @author          XOOPS Mod Development Team
 */

use Xmf\Request;
use XoopsModules\Pedigree;

$rootPath      = dirname(dirname(__DIR__));
$moduleDirName = basename(__DIR__);
$mydirpath     = dirname(__DIR__);

//require_once  dirname(dirname(__DIR__)) . '/mainfile.php';
require_once __DIR__ . '/header.php';
//require_once $rootPath . '/include/cp_functions.php';
//require_once $rootPath . '/include/cp_header.php';
//require_once $rootPath . '/class/xoopsformloader.php';

//require_once dirname(dirname(__DIR__)) . '/mainfile.php';
//xoops_cp_header();

xoops_loadLanguage('main', $moduleDirName);

//require_once __DIR__ . '/header.php';

// Include any common code for this module.
require_once __DIR__ . '/include/common.php';
//require(XOOPS_ROOT_PATH ."/modules/" . $xoopsModule->dirname() . "/include/css.php");

// Get all HTTP post or get parameters into global variables that are prefixed with "param_"
//import_request_variables("gp", "param_");
//extract($_GET, EXTR_PREFIX_ALL, "param");
//extract($_POST, EXTR_PREFIX_ALL, "param");

// This page uses smarty templates. Set "$xoopsOption['template_main']" before including header
$GLOBALS['xoopsOption']['template_main'] = 'pedigree_pedigree.tpl';

require_once $GLOBALS['xoops']->path('/header.php');

$GLOBALS['xoTheme']->addScript('browse.php?Frameworks/jquery/jquery.js');
$GLOBALS['xoTheme']->addScript("browse.php?modules/{$moduleDirName}/assets/js/jquery.magnific-popup.min.js");
$GLOBALS['xoTheme']->addStylesheet("browse.php?modules/{$moduleDirName}/assets/css/style.css");
$GLOBALS['xoTheme']->addStylesheet("browse.php?modules/{$moduleDirName}/assets/css/magnific-popup.css");

require_once $GLOBALS['xoops']->path('class/xoopsformloader.php');

//get module configuration
/** @var XoopsModuleHandler $moduleHandler */
$moduleHandler = xoops_getHandler('module');
$module        = $moduleHandler->getByDirname($moduleDirName);
$configHandler = xoops_getHandler('config');
$moduleConfig  = $configHandler->getConfigsByCat(0, $module->getVar('mid'));

//draw pedigree
//$id = Request::getInt('pedid',0,'GET');
pedigree_main();

//require_once XOOPS_ROOT_PATH . "/footer.php";
require_once __DIR__   . '/footer.php';

//
// Displays the "Main" tab of the module
//
/**
 */
function pedigree_main()
{
    require_once __DIR__ . '/include/common.php';
    $moduleDirName = basename(__DIR__);
    $id     = Request::getInt('pedid', 1, 'GET');
    $animal = new Pedigree\Animal($id);
    //test to find out how many user fields there are.
    $fields      = $animal->getNumOfFields();
    $fieldsCount = count($fields);

    $qarray = ['d', 'f', 'm', 'ff', 'mf', 'fm', 'mm', 'fff', 'ffm', 'fmf', 'fmm', 'mmf', 'mff', 'mfm', 'mmm'];

    $sql = 'SELECT ';

    foreach ($qarray as $key) {
        $sql .= $key . '.id as ' . $key . '_id, ';
        $sql .= $key . '.pname as ' . $key . '_pname, ';
        $sql .= $key . '.mother as ' . $key . '_mother, ';
        $sql .= $key . '.father as ' . $key . '_father, ';
        $sql .= $key . '.roft as ' . $key . '_roft, ';
        $sql .= $key . '.foto as ' . $key . '_foto, ';
    }

    $sql .= 'mmm.coi as mmm_coi FROM ' . $GLOBALS['xoopsDB']->prefix('pedigree_registry') . ' d
                 LEFT JOIN ' . $GLOBALS['xoopsDB']->prefix('pedigree_registry') . ' f ON d.father = f.id
                 LEFT JOIN ' . $GLOBALS['xoopsDB']->prefix('pedigree_registry') . ' m ON d.mother = m.id
                 LEFT JOIN ' . $GLOBALS['xoopsDB']->prefix('pedigree_registry') . ' ff ON f.father = ff.id
                 LEFT JOIN ' . $GLOBALS['xoopsDB']->prefix('pedigree_registry') . ' fff ON ff.father = fff.id
                 LEFT JOIN ' . $GLOBALS['xoopsDB']->prefix('pedigree_registry') . ' ffm ON ff.mother = ffm.id
                 LEFT JOIN ' . $GLOBALS['xoopsDB']->prefix('pedigree_registry') . ' mf ON m.father = mf.id
                 LEFT JOIN ' . $GLOBALS['xoopsDB']->prefix('pedigree_registry') . ' mff ON mf.father = mff.id
                 LEFT JOIN ' . $GLOBALS['xoopsDB']->prefix('pedigree_registry') . ' mfm ON mf.mother = mfm.id
                 LEFT JOIN ' . $GLOBALS['xoopsDB']->prefix('pedigree_registry') . ' fm ON f.mother = fm.id
                 LEFT JOIN ' . $GLOBALS['xoopsDB']->prefix('pedigree_registry') . ' fmf ON fm.father = fmf.id
                 LEFT JOIN ' . $GLOBALS['xoopsDB']->prefix('pedigree_registry') . ' fmm ON fm.mother = fmm.id
                 LEFT JOIN ' . $GLOBALS['xoopsDB']->prefix('pedigree_registry') . ' mm ON m.mother = mm.id
                 LEFT JOIN ' . $GLOBALS['xoopsDB']->prefix('pedigree_registry') . ' mmf ON mm.father = mmf.id
                 LEFT JOIN ' . $GLOBALS['xoopsDB']->prefix('pedigree_registry') . " mmm ON mm.mother = mmm.id
                 WHERE d.id={$id}";

    $result = $GLOBALS['xoopsDB']->query($sql);
    global $moduleConfig;

    while (false !== ($row = $GLOBALS['xoopsDB']->fetchArray($result))) {
        //create array for animal (and all parents)
        foreach ($qarray as $key) {
            $d[$key] = [
                'id'     => $row[$key . '_id'],
                'name'   => stripslashes($row[$key . '_pname']),
                'mother' => $row[$key . '_mother'],
                'father' => $row[$key . '_father'],
                'roft'   => $row[$key . '_roft'],
                'nhsb'   => ''
            ];
            if ((3 != strlen($key) || (0 != $moduleConfig['lastimage'])) && ('' !== $row[$key . '_foto'])) {
                //show image in last row of pedigree if image exists
                $d[$key]['photo']    = PEDIGREE_UPLOAD_URL . '/images/thumbnails/' . $row[$key . '_foto'] . '_150.jpeg';
                $d[$key]['photoBig'] = PEDIGREE_UPLOAD_URL . '/images/' . $row[$key . '_foto'] . '.jpeg';
            }

            $d[$key]['overig'] = '';
            // $pedidata to hold viewable data to be shown in pedigree
            $pedidata = '';

            if ('' == !$d[$key]['id']) {
                //if exists create animal object
                $animal = new Pedigree\Animal($d[$key]['id']);
                $fields = $animal->getNumOfFields();
            }
            foreach ($fields as $i => $iValue) {
                $userField = new Pedigree\Field($fields[$i], $animal->getConfig());
                if ($userField->isActive() && $userField->inPedigree()) {
                    $fieldType = $userField->getSetting('FieldType');
                    $fieldObj  = new $fieldType($userField, $animal);
                    $pedidata  .= $fieldObj->showField() . '<br>';
                }
                $d[$key]['hd'] = $pedidata;
            }
        }
    }

    //add data to smarty template
    $GLOBALS['xoopsTpl']->assign([
                                     'page_title' => stripslashes($row['d_pname']),
                                     'd'          => $d,  //assign dog
                                     //assign config options
                                     'male'       => "<img src='assets/images/male.gif'>",
                                     'female'     => "<img src='assets/images/female.gif'>",
                                     //assign extra display options
                                     'unknown'    => 'Unknown',
                                     'SD'         => _MA_PEDIGREE_SD,
                                     'PA'         => _MA_PEDIGREE_PA,
                                     'GP'         => _MA_PEDIGREE_GP,
                                     'GGP'        => _MA_PEDIGREE_GGP
                                 ]);

    //    require_once __DIR__   . '/footer.php';
}
