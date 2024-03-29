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
 * @author    XOOPS Module Development Team
 * @copyright Copyright (c) 2001-2019 {@link https://xoops.org XOOPS Project}
 * @license   https://www.gnu.org/licenses/gpl-2.0.html GNU Public License
 */

use Xmf\Request;
use XoopsModules\Pedigree;

//require_once  \dirname(__DIR__, 2) . '/mainfile.php';
require_once __DIR__ . '/header.php';

$moduleDirName = basename(__DIR__);
xoops_loadLanguage('main', $moduleDirName);

// Include any common code for this module.
require_once XOOPS_ROOT_PATH . '/modules/' . $moduleDirName . '/include/common.php';

$GLOBALS['xoopsOption']['template_main'] = 'pedigree_result.tpl';
include $GLOBALS['xoops']->path('/header.php');

//get module configuration
/** @var XoopsModuleHandler $moduleHandler */
$moduleHandler = xoops_getHandler('module');
$module        = $moduleHandler->getByDirname($moduleDirName);
$configHandler = xoops_getHandler('config');
$moduleConfig  = $configHandler->getConfigsByCat(0, $module->getVar('mid'));

$f = Request::getString('f', 'pname', 'GET');
$q = Request::getString('query', '', 'POST');
/*
if (!isset($_GET['f'])) {
    $f = "pname";
} else {
    $f = $_GET['f'];
}

if (isset($_POST['query'])) {
    $q = $_POST['query'];
} else {
    $q = '';
}
*/
if ('' === $q < 1 && isset($_POST['query'])) {
    redirect_header('index.php', 3, _MA_PEDIGREE_SEARCH_SHORT);
}

if (!isset($_GET['w'])) {
    $w = '%' . $q . '%';
}

if (isset($_GET['p'])) {
    $p = $_GET['p'];
}

if (isset($p)) {
    $w = $q;
}

if (isset($_GET['w'])) {
    if ('zero' === $_GET['w'] || '' === $_GET['w'] || '0' === $_GET['w']) {
        $w = '0';
    } else {
        $w = $_GET['w'];
    }
}
if (isset($_GET['l'])) {
    if ('1' == $_GET['l'] || 'LIKE' === $_GET['l']) {
        $l = 'LIKE';
    }
} else {
    $l = '=';
}

if (!$_GET['o']) {
    $o = 'pname';
} else {
    $o = $_GET['o'];
}

if (!isset($_GET['d'])) {
    $d = 'ASC';
} else {
    $d = $_GET['d'];
}

if (!isset($_GET['st'])) {
    $st = 0;
} else {
    $st = $_GET['st'];
}

$perPage = $moduleConfig['perpage'];

//is current user a module admin?
$modadmin    = false;
$xoopsModule = XoopsModule::getByDirname($moduleDirName);
if (!empty($GLOBALS['xoopsUser']) && ($GLOBALS['xoopsUser'] instanceof \XoopsUser)
    && $GLOBALS['xoopsUser']->isAdmin($xoopsModule->mid())) {
    $modadmin = true;
}

//count total number of dogs
$numDog = 'SELECT COUNT(id) FROM ' . $GLOBALS['xoopsDB']->prefix('pedigree_registry') . ' WHERE ' . $f . ' ' . $l . " '" . $w . "'";
$numRes = $GLOBALS['xoopsDB']->query($numDog);
//total number of dogs the query will find
[$numResults] = $GLOBALS['xoopsDB']->fetchRow($numRes);
//if nothing is found
if (0 == $numResults) {
    //just for debug information
    //echo $numDog;
    redirect_header('index.php', 15, strtr(_MA_PEDIGREE_SEARCH_NO, ['[animalTypes]' => $moduleConfig['animalTypes']]));
}
//total number of pages
$numPages = floor($numResults / $perPage) + 1;
if (($numPages * $perPage) == ($numResults + $perPage)) {
    --$numPages;
}
//find current page
$currentPage = floor($st / $perPage) + 1;
//create empty pages variable
$pages = '';

$length = mb_strlen($w);
if ('%' === mb_substr($w, $length - 1, $length)) {
    $whe = mb_substr($w, 0, $length - 1) . '%25';
    if (0 === strncmp($whe, '%', 1)) {
        $length = mb_strlen($whe);
        $whe    = '%25' . mb_substr($whe, 1, $length);
    }
} else {
    $whe = $w;
}
/* @todo: replace this with standard XOOPS Page Navigation */
//create previous button
if ($numPages > 1) {
    if ($currentPage > 1) {
        $pages .= '<a href="result.php?f=' . $f . '&amp;l=' . $l . '&amp;w=' . $whe . '&amp;o=' . $o . '&amp;d=' . $d . '&amp;st=' . ($st - $perPage) . '">' . _MA_PEDIGREE_PREVIOUS . '</a>&nbsp;&nbsp;';
    }

    //create numbers
    for ($x = 1; $x < ($numPages + 1); ++$x) {
        //create line break after 20 number
        if (0 == ($x % 20)) {
            $pages .= '<br>';
        }
        if ($x != $currentPage) {
            $pages .= '<a href="result.php?f=' . $f . '&l=' . $l . '&w=' . $whe . '&o=' . $o . '&d=' . $d . '&st=' . ($perPage * ($x - 1)) . '">' . $x . '</a>&nbsp;&nbsp;';
        } else {
            $pages .= '<b>' . $x . '</b>&nbsp;&nbsp';
        }
    }
}
//create next button
if ($numPages > 1) {
    if ($currentPage < $numPages) {
        $pages .= '<a href="result.php?f=' . $f . '&amp;l=' . $l . '&amp;w=' . $whe . '&amp;o=' . $o . '&amp;d=' . $d . '&amp;st=' . ($st + $perPage) . '">' . _MA_PEDIGREE_NEXT . '</a>&nbsp;&nbsp';
    }
}

//query
$sql = 'SELECT * FROM ' . $GLOBALS['xoopsDB']->prefix('pedigree_registry') . ' WHERE ' . $f . ' ' . $l . " '" . $w . "' ORDER BY " . $o . ' ' . $d . ' LIMIT ' . $st . ', ' . $perPage;
$result      = $GLOBALS['xoopsDB']->query($sql);

$animal = new Pedigree\Animal();
//test to find out how many user fields there are...
$fields       = $animal->getNumOfFields();
$fieldsCount  = count($fields);
$numofcolumns = 1;
$columns      = [['columnname' => 'Name']];
foreach ($fields as $i => $iValue) {
    $userField   = new Pedigree\Field($fields[$i], $animal->getConfig());
    $fieldType   = $userField->getSetting('fieldtype');
    $fieldObject = new $fieldType($userField, $animal);
    //create empty string
    if ($userField->isActive() && $userField->inList()) {
        if ($userField->hasLookup()) {
            $lookupValues = $userField->lookupField($fields[$i]);
        } else {
            $lookupValues = '';
        }
        /* print_r($lookupValues);            //debug information */
        $columns[] = [
            'columnname'   => $fieldObject->fieldname,
            'columnnumber' => $userField->getId(),
            'lookupval'    => $lookupValues,
        ];
        ++$numofcolumns;
        unset($lookupValues);
    }
}

$pathIcon16 = \Xmf\Module\Admin::iconUrl('', 16);

while (false !== ($row = $GLOBALS['xoopsDB']->fetchArray($result))) {
    //reset $gender
    $gender = '';
    if ($helper->isUserAdmin()) {
        $gender = "<a href=\"dog.php?id={$row['id']}\">{$icons['edit']}</a>&nbsp;<a href=\"delete.php?id={$row['id']}\">{$icons['delete']}</a>&nbsp;";
    }
    if (Constants::MALE == $row['roft']) {
        $gender .= "<img src=\"" . PEDIGREE_IMAGE_URL . "/male.gif\" alt=\"" . $helper->getConfig('male') . "\" title=\"" . $helper->getConfig('male') . "\">";
    } else {
        $gender .= "<img src=\"" . PEDIGREE_IMAGE_URL . "/female.gif\" alt=\"" . $helper->getConfig('female') . "\" title=\"" . $helper->getConfig('female') . "\">";
    }

    //$camera = ('' != $row['foto']) ? " <img src='" . PEDIGREE_UPLOAD_URL . "/images/dog-icon25.png'>" : '';
    $camera = ('' !== $row['foto']) ? "&nbsp;<img src=\"" . PEDIGREE_IMAGE_URL . "/camera.png\">" : '';
    $name   = stripslashes($row['pname']) . $camera;
    //empty array
    unset($columnvalue);
    //fill array
    for ($i = 1; $i < $numofcolumns; ++$i) {
        $x = 'user' . $columns[$i]['columnnumber'];
        //echo $x."columnnumber";
        if (is_array($columns[$i]['lookupval'])) {
            foreach ($columns[$i]['lookupval'] as $key => $keyValue) {
                if ($keyValue['id'] == $row[$x]) {
                    //echo "key:".$row['user5']."<br>";
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
        if (isset($value)) {
            $columnvalue[] = ['value' => $value];
            unset($value);
        }
    }
    $animals[] = [
        'id'          => $row['id'],
        'name'        => $name,
        'gender'      => $gender,
        'link'        => "<a href='pedigree.php?pedid={$row['id']}'>{$name}</a>",
        'colour'      => '',
        'number'      => '',
        'usercolumns' => isset($columnvalue) ? $columnvalue : 0,
    ];
}

//add data to smarty template
//assign dog
$GLOBALS['xoopsTpl']->assign([
                                 'dogs'         => $animals,
                                 'columns'      => $columns,
                                 'numofcolumns' => $numofcolumns,
                                 'tsarray'      => Pedigree\Utility::sortTable($numofcolumns),
                             ]);
//assign links

//find last shown number
if (($st + $perPage) > $numResults) {
    $lastshown = $numResults;
} else {
    $lastshown = $st + $perPage;
}
//create string
$matches     = strtr(_MA_PEDIGREE_MATCHES, ['[animalTypes]' => $moduleConfig['animalTypes']]);
$nummatchstr = "{$numResults}{$matches}" . ($st + 1) . " - {$lastshown} ({$numPages} pages)";
$GLOBALS['xoopsTpl']->assign('nummatch', $nummatchstr);
$GLOBALS['xoopsTpl']->assign('pages', $pages);

//comments and footer
include $GLOBALS['xoops']->path('footer.php');
