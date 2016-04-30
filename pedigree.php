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

$rootPath      = dirname(dirname(__DIR__));
$moduleDirName = basename(__DIR__);
$mydirpath     = dirname(__DIR__);

include_once $rootPath . '/mainfile.php';
//include_once $rootPath . '/include/cp_functions.php';
//require_once $rootPath . '/include/cp_header.php';
//include_once $rootPath . '/class/xoopsformloader.php';

//require_once dirname(dirname(__DIR__)) . '/mainfile.php';
//xoops_cp_header();

xoops_loadLanguage('main', $moduleDirName);

include_once __DIR__ . '/header.php';

// Include any common code for this module.
require_once XOOPS_ROOT_PATH . "/modules/{$moduleDirName}/include/common.php";
//require_once(XOOPS_ROOT_PATH ."/modules/" . $xoopsModule->dirname() . "/include/css.php");

// Get all HTTP post or get parameters into global variables that are prefixed with "param_"
//import_request_variables("gp", "param_");
//extract($_GET, EXTR_PREFIX_ALL, "param");
//extract($_POST, EXTR_PREFIX_ALL, "param");

// This page uses smarty templates. Set "$xoopsOption['template_main']" before including header
$xoopsOption['template_main'] = 'pedigree_pedigree.tpl';

include $GLOBALS['xoops']->path('/header.php');

$GLOBALS['xoTheme']->addScript('browse.php?Frameworks/jquery/jquery.js');
$GLOBALS['xoTheme']->addScript("browse.php?modules/{$moduleDirName}/assets/js/jquery.magnific-popup.min.js");
$GLOBALS['xoTheme']->addStylesheet("browse.php?modules/{$moduleDirName}/assets/css/style.css");
$GLOBALS['xoTheme']->addStylesheet("browse.php?modules/{$moduleDirName}/assets/css/magnific-popup.css");

require_once $GLOBALS['xoops']->path('class/xoopsformloader.php');
require_once $GLOBALS['xoops']->path("modules/{$moduleDirName}/class/field.php");
/*
//get module configuration
$moduleHandler = xoops_getHandler('module');
$module        = $moduleHandler->getByDirname('pedigree');
$configHandler = xoops_getHandler('config');
$moduleConfig  = $configHandler->getConfigsByCat(0, $module->getVar('mid'));
*/
require_once $GLOBALS['xoops']->path("modules/{$moduleDirName}/class/animal.php");
$id     = XoopsRequest::getInt('pedid', 1, 'GET');
$animal = new PedigreeAnimal($id);
//test to find out how many user fields there are.
$fields      = $animal->getNumOfFields();
$fieldsCount = count($fields);

$qarray = array('d', 'f', 'm', 'ff', 'mf', 'fm', 'mm', 'fff', 'ffm', 'fmf', 'fmm', 'mmf', 'mff', 'mfm', 'mmm');

$querystring = 'SELECT ';

foreach ($qarray as $key) {
    $querystring .= $key . '.Id as ' . $key . '_id, ';
    $querystring .= $key . '.NAAM as ' . $key . '_naam, ';
    $querystring .= $key . '.mother as ' . $key . '_mother, ';
    $querystring .= $key . '.father as ' . $key . '_father, ';
    $querystring .= $key . '.roft as ' . $key . '_roft, ';
    $querystring .= $key . '.foto as ' . $key . '_foto, ';
}

$querystring .= 'mmm.coi as mmm_coi FROM ' . $GLOBALS['xoopsDB']->prefix('pedigree_tree') . ' d
             LEFT JOIN ' . $GLOBALS['xoopsDB']->prefix('pedigree_tree') . ' f ON d.father = f.Id
             LEFT JOIN ' . $GLOBALS['xoopsDB']->prefix('pedigree_tree') . ' m ON d.mother = m.Id
             LEFT JOIN ' . $GLOBALS['xoopsDB']->prefix('pedigree_tree') . ' ff ON f.father = ff.Id
             LEFT JOIN ' . $GLOBALS['xoopsDB']->prefix('pedigree_tree') . ' fff ON ff.father = fff.Id
             LEFT JOIN ' . $GLOBALS['xoopsDB']->prefix('pedigree_tree') . ' ffm ON ff.mother = ffm.Id
             LEFT JOIN ' . $GLOBALS['xoopsDB']->prefix('pedigree_tree') . ' mf ON m.father = mf.Id
             LEFT JOIN ' . $GLOBALS['xoopsDB']->prefix('pedigree_tree') . ' mff ON mf.father = mff.Id
             LEFT JOIN ' . $GLOBALS['xoopsDB']->prefix('pedigree_tree') . ' mfm ON mf.mother = mfm.Id
             LEFT JOIN ' . $GLOBALS['xoopsDB']->prefix('pedigree_tree') . ' fm ON f.mother = fm.Id
             LEFT JOIN ' . $GLOBALS['xoopsDB']->prefix('pedigree_tree') . ' fmf ON fm.father = fmf.Id
             LEFT JOIN ' . $GLOBALS['xoopsDB']->prefix('pedigree_tree') . ' fmm ON fm.mother = fmm.Id
             LEFT JOIN ' . $GLOBALS['xoopsDB']->prefix('pedigree_tree') . ' mm ON m.mother = mm.Id
             LEFT JOIN ' . $GLOBALS['xoopsDB']->prefix('pedigree_tree') . ' mmf ON mm.father = mmf.Id
             LEFT JOIN ' . $GLOBALS['xoopsDB']->prefix('pedigree_tree') . " mmm ON mm.mother = mmm.Id
             WHERE d.Id={$id}";

$result = $GLOBALS['xoopsDB']->query($querystring);

while (false !== ($row = $GLOBALS['xoopsDB']->fetchArray($result))) {
    //create array for animal (and all parents)
    foreach ($qarray as $key) {
        $d[$key] = array(
            'id'     => $row[$key . '_id'],
            'name'   => stripslashes($row[$key . '_naam']),
            'mother' => $row[$key . '_mother'],
            'father' => $row[$key . '_father'],
            'roft'   => $row[$key . '_roft'],
            'nhsb'   => ''
        );
        if ((3 != strlen($key) || (0 != $pedigree->getConfig('lastimage'))) && ('' !== $row[$key . '_foto'])) {
            //show image in last row of pedigree if image exists
            $d[$key]['photo']    = PEDIGREE_UPLOAD_URL . '/images/thumbnails/' . $row[$key . '_foto'] . '_150.jpeg';
            $d[$key]['photoBig'] = PEDIGREE_UPLOAD_URL . '/images/' . $row[$key . '_foto'] . '.jpeg';
        }

        $d[$key]['overig'] = '';
        // $pedidata to hold viewable data to be shown in pedigree
        $pedidata = '';

        if ('' == !$d[$key]['id']) {
            //if exists create animal object
            $animal = new PedigreeAnimal($d[$key]['id']);
            $fields = $animal->getNumOfFields();
        }
        for ($i = 0, $iMax = count($fields); $i < $iMax; ++$i) {
            $userField = new Field($fields[$i], $animal->getConfig());
            if ($userField->isActive() && $userField->inPedigree()) {
                $fieldType = $userField->getSetting('fieldtype');
                $fieldObj  = new $fieldType($userField, $animal);
                $pedidata .= $fieldObj->showField() . '<br />';
            }
            $d[$key]['hd'] = $pedidata;
        }
    }
}

//add data to smarty template
$GLOBALS['xoopsTpl']->assign(array(
                                 'page_title' => stripslashes($row['d_naam']),
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
                             ));

include __DIR__ . '/footer.php';
