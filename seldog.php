<?php
/*
 You may not change or alter any portion of this comment or credits of
 supporting developers from this source code or any supporting source code
 which is considered copyrighted (c) material of the original comment or credit
 authors.

 This program is distributed in the hope that it will be useful, but
 WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 */
/**
 * Module: Pedigree
 *
 * @package   XoopsModules\Pedigree
 * @copyright Copyright (c) 2001-2019 {@link https://xoops.org XOOPS Project}
 * @license   https://www.gnu.org/licenses/gpl-2.0.html GNU Public License
 * @author    XOOPS Module Development Team
 */

use Xmf\Request;
use XoopsModules\Pedigree;
use XoopsModules\Pedigree\Constants;

require_once __DIR__ . '/header.php';
/** @var XoopsModules\Pedigree\Helper $helper */
$helper->loadLanguage('main');
//xoops_load('Pedigree\Animal', $moduleDirName);

// Include any common code for this module.
require_once $helper->path('include/common.php');

/*
// Get all HTTP post or get parameters into global variables that are prefixed with "param_"
//import_request_variables("gp", "param_");
extract($_GET, EXTR_PREFIX_ALL, "param");
extract($_POST, EXTR_PREFIX_ALL, "param");
*/

$GLOBALS['xoopsOption']['template_main'] = 'pedigree_sel.tpl';
include $GLOBALS['xoops']->path('/header.php');

$st = Request::getInt('st', 0, 'GET');
$gend = Request::getInt('gend', Constants::MALE, 'GET');
$curval = Request::getInt('curval', 0, 'GET');

/* @todo: default value of 'a' assumes english, this should be defined in language file */
$letter = Request::getString('letter', 'A', 'GET');

$confPerPage = (int)$helper->getConfig('perpage', Constants::DEFAULT_PER_PAGE);
$perPage = (int)$confPerPage > 0 ? (int)$confPerPage : Constants::DEFAULT_PER_PAGE;

$GLOBALS['xoopsTpl']->assign('page_title', _MI_PEDIGREE_TITLE);

//count total number of dogs
$treeHandler = $helper->getHandler('Tree');
$criteria = new \CriteriaCompo('roft', $gend);
$criteria->add(new \Criteria('naam', "{$letter}%"), 'LIKE');
$treeCount = $treeHandler->getCount($criteria);

//total number of pages
$numPages = floor($treeCount / $perPage) + 1;
if (($numPages * $perPage) == ($treeCount + $perPage)) {
    --$numPages;
}
//find current page
$currentPage = floor($st / $perPage) + 1;
/*
$numDog = 'SELECT id FROM ' . $GLOBALS['xoopsDB']->prefix('pedigree_tree') . " WHERE naam LIKE '{$letter}%' AND roft = '{$gend}'";
$numRes = $GLOBALS['xoopsDB']->query($numDog);
//total number of dogs the query will find
$numResults = $GLOBALS['xoopsDB']->getRowsNum($numRes);
//total number of pages
$numPages = floor($numResults / $perPage) + 1;
if (($numPages * $perPage) == ($numResults + $perPage)) {
    --$numPages;
}
//find current page
$currentPage = floor($st / $perPage) + 1;
*/
/* @todo: change this to use letters() from mylinks or similar module - this routine assumes english */
//create alphabet
$pages = '';
for ($i = 65; $i <= 90; ++$i) {
    if ($letter == chr($i)) {
        $pages .= "<b><a href='seldog.php?gend={$gend}&curval={$curval}&letter=" . chr($i) . "'>" . chr($i) . '</a></b>&nbsp;';
    } else {
        $pages .= "<a href='seldog.php?gend={$gend}&curval={$curval}&letter=" . chr($i) . "'>" . chr($i) . '</a>&nbsp;';
    }
}
$pages .= '-&nbsp;';
$pages .= "<a href='seldog.php?gend={$gend}&curval={$curval}&letter=Ã…\">Ã…</a>&nbsp;";
$pages .= "<a href='seldog.php?gend={$gend}&curval={$curval}&letter=Ã–\">Ã–</a>&nbsp;";
//create linebreak
$pages .= '<br>';
//create previous button
if (($numPages > 1) && ($currentPage > 1)) {
    $pages .= "<a href='seldog.php?gend={$gend}&curval={$curval}&letter={$letter}&st=" . ($st - $perPage) . "'>" . _MA_PEDIGREE_PREVIOUS . '</a>&nbsp;&nbsp';
}
//create numbers
for ($x = 1; $x < ($numPages + 1); ++$x) {
    //create line break after 20 number
    if (0 == ($x % 20)) {
        $pages .= '<br>';
    }
    if ($x != $currentPage) {
        $pages .= "<a href='seldog.php?gend={$gend}&curval={$curval}&letter={$letter}&st=" . ($perPage * ($x - 1)) . "'>{$x}</a>&nbsp;&nbsp";
    } else {
        $pages .= "{$x}&nbsp;&nbsp";
    }
}
//create next button
if (($numPages > 1) && ($currentPage < $numPages)) {
    $pages .= "<a href='seldog.php?gend={$gend}&curval={$curval}&letter={$letter}&st=" . ($st + $perPage) . "'>" . _MA_PEDIGREE_NEXT . '</a>&nbsp;&nbsp';
}

//query
$queryString = 'SELECT * FROM ' . $GLOBALS['xoopsDB']->prefix('pedigree_tree') . " WHERE naam LIKE '{$letter}%' AND roft = '{$gend}' ORDER BY naam LIMIT {$st}, {$perPage}";
$result = $GLOBALS['xoopsDB']->query($queryString);

$animal = new Pedigree\Animal();
//test to find out how many user fields there are...
$fields = $animal->getFieldsIds();
$fieldsCount = count($fields);
$numofcolumns = 1;
//@todo move hard coded language string to language file
$columns[] = ['columnname' => 'Name'];
foreach ($fields as $i => $iValue) {
    $userField = new Pedigree\Field($fields[$i], $animal->getConfig());
    $fieldType = $userField->getSetting('fieldtype');
    $fieldObj = new $fieldType($userField, $animal);
    //create empty string
    if ($userField->isActive() && $userField->inList()) {
        if ($userField->hasLookup()) {
            $lookupValues = $userField->lookupField($fields[$i]);
        //debug information
            //print_r($lookupValues);
        } else {
            $lookupValues = '';
        }
        $columns[] = [
            'columnname' => $fieldObj->fieldname,
            'columnnumber' => $userField->getId(),
            'lookupval' => $lookupValues,
        ];
        ++$numofcolumns;
        unset($lookupValues);
    }
}

$empty = array_fill(0, $numofcolumns, ['value' => '']);
/*
for ($i = 1; $i < $numofcolumns; ++$i) {
    $empty[] = array('value' => "");
}
*/
if (Constants::MALE === $gend) {
    $dogs[] = [
        'id' => '0',
        'name' => '',
        'gender' => '',
        'link' => '<a href="' . $helper->url("updatepage.php?gend=" . Constants::MALE . "&curval={$curval}&thisid=0") . '">' . strtr(_MA_PEDIGREE_ADD_SIREUNKNOWN, ['[father]' => $helper->getConfig('father')]) . '</a>',
        'colour' => '',
        'number' => '',
        'usercolumns' => $empty,
    ];
} else {
    $dogs[] = [
        'id' => '0',
        'name' => '',
        'gender' => '',
        'link' => '<a href="' . $helper->url("updatepage.php?gend=" . Constants::FEMALE . "&curval={$curval}&thisid=0") . '">' . strtr(_MA_PEDIGREE_ADD_DAMUNKNOWN, ['[mother]' => $helper->getConfig('mother')]) . '</a>',
        'colour' => '',
        'number' => '',
        'usercolumns' => $empty,
    ];
}

while (false !== ($row = $GLOBALS['xoopsDB']->fetchArray($result))) {
    //create picture information
    $camera = ('' != $row['foto']) ? " <img src=\"" . PEDIGREE_IMAGE_URL . "/camera.png\">" : '';
    $name = stripslashes($row['naam']) . $camera;
    //empty array
    unset($columnvalue);
    //fill array
    for ($i = 1; $i < $numofcolumns; ++$i) {
        $x = 'user' . $columns[$i]['columnnumber'];
        if (is_array($columns[$i]['lookupval'])) {
            foreach ($columns[$i]['lookupval'] as $key => $keyValue) {
                if ($key == $row[$x]) {
                    $value = $keyValue['value'];
                }
            }
            //debug information
            ///echo $columns[$i]['columnname']."is an array !";
        } //format value - cant use object because of query count
        elseif (0 === strncmp($row[$x], 'http://', 7)) {
            $value = "<a href='{$row[$x]}'>{$row[$x]}</a>";
        } else {
            $value = $row[$x];
        }
        $columnvalue[] = ['value' => $value];
    }
    if (Constants::MALE == $gend) {
        $dogs[] = [
            'id' => $row['id'],
            'name' => $name,
            'gender' => "<img src=\"" . PEDIGREE_IMAGE_URL . "/male.gif\" alt=\"" . $helper->getConfig('male') . "\" title=\"" . $helper->getConfig('male') . "\">",
            'link' => "<a href='updatepage.php?gend={$gend}&curval={$curval}&thisid={$row['id']}'>{$name}</a>",
            'colour' => '',
            'number' => '',
            'usercolumns' => isset($columnvalue) ?: [],
        ];
    } else {
        $dogs[] = [
            'id' => $row['id'],
            'name' => $name,
            'gender' => "<img src=\"" . PEDIGREE_IMAGE_URL . "/female.gif\" alt=\"" . $helper->getConfig('female') . "\" title=\"" . $helper->getConfig('female') . "\">",
            'link' => "<a href='updatepage.php?gend={$gend}&curval={$curval}&thisid={$row['id']}'>{$name}</a>",
            'colour' => '',
            'number' => '',
            'usercolumns' => isset($columnvalue) ?: [],
        ];
    }
}

//add data to smarty template
//assign dog
$GLOBALS['xoopsTpl']->assign([
                                 'dogs' => $dogs,
                                 'columns' => $columns,
                                 'numofcolumns' => $numofcolumns,
                                 'tsarray' => Pedigree\Utility::sortTable($numofcolumns),
                             ]);
//add data to smarty template
if (0 == $gend) {
    $selTtlParent = strtr(_MA_PEDIGREE_FLD_FATH, ['[father]' => $helper->getConfig('father')]);
} else {
    $selTtlParent = strtr(_MA_PEDIGREE_FLD_MOTH, ['[mother]' => $helper->getConfig('mother')]);
}
$seltitle = _MA_PEDIGREE_SEL . $selTtlParent . _MA_PEDIGREE_FROM . Pedigree\Utility::getName($curval);

$GLOBALS['xoopsTpl']->assign('seltitle', $seltitle);

//find last shown number
$lastshown = (($st + $perPage) > $numResults) ? $numResults : $st + $perPage;

//create string
/* @todo: move hard coded language string to language files */
$matches = strtr(_MA_PEDIGREE_MATCHES, ['[animalTypes]' => $helper->getConfig('animalTypes')]);
$nummatchstr = "{$numResults}{$matches}" . ($st + 1) . " - {$lastshown} ({$numPages} pages)";
$GLOBALS['xoopsTpl']->assign([
                                 'nummatch' => $nummatchstr,
                                 'pages' => $pages,
                                 'curval' => $curval,
                             ]);

//mb ========= MOTHER LETTERS===============================
$roft = $gend;
//$criteria = $helper->getHandler('Tree')->getActiveCriteria($roft);
$activeObject = 'Tree';
$name = 'naam';
$number1 = '1';
$number2 = '0';
$link = "seldog.php?gend={$gend}&curval={$curval}&letter=";

$criteria = $helper->getHandler('Tree')->getActiveCriteria($roft);
$criteria->setGroupby('UPPER(LEFT(' . $name . ',1))');

$motherArray['letters'] = Pedigree\Utility::lettersChoice($helper, $activeObject, $criteria, $name, $link);
//$catarray['toolbar']          = pedigree_toolbar();
$xoopsTpl->assign('motherArray', $motherArray);

//mb ========================================

include $GLOBALS['xoops']->path('footer.php');
