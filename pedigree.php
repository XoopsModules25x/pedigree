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
 * @package         XoopsModules\Pedigree
 * @copyright       {@link http://sourceforge.net/projects/thmod/ The TXMod XOOPS Project}
 * @copyright       {@link http://sourceforge.net/projects/xoops/ The XOOPS Project}
 * @license         https://www.gnu.org/licenses/gpl-2.0.html GNU Public License
 * @author          XOOPS Module Development Team
 */

use Xmf\Request;
use XoopsModules\Pedigree;

require_once \dirname(__DIR__, 2) . '/mainfile.php';
require_once __DIR__ . '/header.php';
//xoops_cp_header();
$helper->loadLanguage('main');

//require_once __DIR__ . '/header.php';

// Include any common code for this module.
require_once $helper->path('include/common.php');
//require_once XOOPS_ROOT_PATH ."/modules/" . $xoopsModule->dirname() . "/include/css.php";

// This page uses smarty templates. Set "$xoopsOption['template_main']" before including header
$GLOBALS['xoopsOption']['template_main'] = 'pedigree_pedigree.tpl';
include $GLOBALS['xoops']->path('/header.php');

$GLOBALS['xoTheme']->addScript('browse.php?Frameworks/jquery/jquery.js');
$GLOBALS['xoTheme']->addScript("browse.php?modules/{$moduleDirName}/assets/js/jquery.magnific-popup.min.js");
$GLOBALS['xoTheme']->addStylesheet("browse.php?modules/{$moduleDirName}/assets/css/style.css");
$GLOBALS['xoTheme']->addStylesheet("browse.php?modules/{$moduleDirName}/assets/css/magnific-popup.css");

require_once $GLOBALS['xoops']->path('class/xoopsformloader.php');

//
// Displays the "Main" tab of the module
//
$id = Request::getInt('pedid', 1, 'GET');
//$animal = new Pedigree\Animal($id);
//test to find out how many user fields there are.
//$fields      = $animal->getNumOfFields();
//$fieldsCount = count($fields);

$qarray = ['d', 'f', 'm', 'ff', 'mf', 'fm', 'mm', 'fff', 'ffm', 'fmf', 'fmm', 'mmf', 'mff', 'mfm', 'mmm'];

$querystring = 'SELECT ';

foreach ($qarray as $key) {
    $querystring .= $key . '.id as ' . $key . '_id, ';
    $querystring .= $key . '.pname as ' . $key . '_pname, ';
    $querystring .= $key . '.mother as ' . $key . '_mother, ';
    $querystring .= $key . '.father as ' . $key . '_father, ';
    $querystring .= $key . '.roft as ' . $key . '_roft, ';
    $querystring .= $key . '.foto as ' . $key . '_foto, ';
}

$querystring .= 'mmm.coi as mmm_coi FROM ' . $GLOBALS['xoopsDB']->prefix('pedigree_registry') . ' d
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

$result = $GLOBALS['xoopsDB']->query($querystring);

$dogs = []; // initialize dogs array
while (false !== ($row = $GLOBALS['xoopsDB']->fetchArray($result))) {
    //create array for animal (and all parents)
    foreach ($qarray as $key) {
        $dogs[$key] = [
            'id'     => $row[$key . '_id'],
            'name'   => stripslashes($row[$key . '_pname']),
            'mother' => $row[$key . '_mother'],
            'father' => $row[$key . '_father'],
            'roft'   => $row[$key . '_roft'],
            'nhsb'   => '',
        ];
        if ((3 != mb_strlen($key) || (0 != $helper->getConfig('lastimage'))) && ('' !== $row[$key . '_foto'])) {
            //show image in last row of pedigree if image exists
            $dogs[$key]['photo']    = PEDIGREE_UPLOAD_URL . '/images/thumbnails/' . $row[$key . '_foto'] . '_150.jpeg';
            $dogs[$key]['photoBig'] = PEDIGREE_UPLOAD_URL . '/images/' . $row[$key . '_foto'] . '.jpeg';
        }

        $dogs[$key]['overig'] = '';
        // $pedidata to hold viewable data to be shown in pedigree
        $pedidata = '';

        if (0 !== $dogs[$key]['id']) {
            //if exists create animal object
            $animal = new Pedigree\Animal($dogs[$key]['id']);
            $fields = $animal->getNumOfFields();
        }
        foreach ($fields as $i => $iValue) {
            $userField = new Pedigree\Field($fields[$i], $animal->getConfig());
            if ($userField->isActive() && $userField->inPedigree()) {
                $fieldType = $userField->getSetting('fieldtype');
                $fieldObj  = new $fieldType($userField, $animal);
                $pedidata  .= $fieldObj->showField() . '<br>';
            }
            $dogs[$key]['hd'] = $pedidata;
        }
    }
}

//add data to smarty template
$GLOBALS['xoopsTpl']->assign([
                                 'page_title' => stripslashes($row['d_pname']),
                                 'd'          => $dogs,  //assign dogs array
                                 //assign config options
                                 'male'       => "<img src=\"" . PEDIGREE_IMAGE_URL . "/male.gif\" alt=\"" . _MA_PEDIGREE_FLD_MALE . "\" title=\"" . _MA_PEDIGREE_FLD_MALE . "\">",
                                 'female'     => "<img src=\"" . PEDIGREE_IMAGE_URL . "/female.gif\" alt=\"" . _MA_PEDIGREE_FLD_FEMA . "\" title=\"" . _MA_PEDIGREE_FLD_FEMA . "\">",
                                 //assign extra display options
                                 'unknown'    => _MA_PEDIGREE_UNKNOWN,
                                 'SD'         => _MA_PEDIGREE_SD,
                                 'PA'         => _MA_PEDIGREE_PA,
                                 'GP'         => _MA_PEDIGREE_GP,
                                 'GGP'        => _MA_PEDIGREE_GGP,
                             ]);

require __DIR__ . '/footer.php';
