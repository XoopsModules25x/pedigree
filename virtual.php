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
use XoopsModules\Pedigree\Constants;

require_once __DIR__ . '/header.php';

/** @var XoopsModules\Pedigree\Helper $helper */
$helper->loadLanguage('main');

$GLOBALS['xoopsOption']['template_main'] = 'pedigree_virtual.tpl';
include $GLOBALS['xoops']->path('/header.php');

$GLOBALS['xoopsTpl']->assign('page_title', _MA_PEDIGREE_VIRTUAL_PAGETITLE);

$f = Request::getCmd('f', '', 'GET');

switch($f) {
    case 'dam':
        $pages = '';
        $st = Request::getString('st', 0, 'GET');
        $l = Request::getString('l', 'A', 'GET');
        $selsire = Request::getInt('selsire', 0, 'GET');

        $GLOBALS['xoopsTpl']->assign('sire', '1');
        //create list of males dog to select from
        $perPage = $helper->getConfig('perpage', Constants::DEFAULT_PER_PAGE);
        $perPage = (int)$perPage > 0 ? (int)$perPage : Constants::DEFAULT_PER_PAGE; // make sure $perPage is 'valid'

        //Count total number of dogs
        $numDog = 'SELECT COUNT(d.id) FROM '
                  . $GLOBALS['xoopsDB']->prefix('pedigree_tree')
                  . ' d LEFT JOIN '
                  . $GLOBALS['xoopsDB']->prefix('pedigree_tree')
                  . ' m ON m.id = d.mother LEFT JOIN '
                  . $GLOBALS['xoopsDB']->prefix('pedigree_tree')
                  //  . " f ON f.id = d.father WHERE d.roft = '1' and d.mother != '0' and d.father != '0' and m.mother != '0' and m.father != '0' and f.mother != '0' and f.father != '0' and d.naam LIKE '" . $l . "%'";
                  . " f ON f.id = d.father WHERE d.roft = '1' AND d.mother != '0' AND d.father != '0' AND m.mother != '0' AND m.father != '0' AND f.mother != '0' AND f.father != '0' AND d.naam LIKE '"
                  . $GLOBALS['xoopsDB']->escape($l)
                  . "%'";

        $numRes = $GLOBALS['xoopsDB']->query($numDog);

        //total number of dogs the query will find
        list($numResults) = $GLOBALS['xoopsDB']->fetchRow($numRes);
        //total number of pages
        $numPages = floor($numResults / $perPage) + 1;
        if (($numPages * $perPage) == ($numResults + $perPage)) {
            --$numPages;
        }
        //find current page
        $currentPage = floor($st / $perPage) + 1;

        //create the alphabet
        $pages .= "<a href=\"" . $helper->url("virtual.php?f=dam&selsire=" . $selsire . "&st=0&l=a") . "\">A</a>&nbsp;";
        $pages .= "<a href=\"" . $helper->url("virtual.php?f=dam&selsire=" . $selsire . "&st=0&l=b") . "\">B</a>&nbsp;";
        $pages .= "<a href=\"" . $helper->url("virtual.php?f=dam&selsire=" . $selsire . "&st=0&l=c") . "\">C</a>&nbsp;";
        $pages .= "<a href=\"" . $helper->url("virtual.php?f=dam&selsire=" . $selsire . "&st=0&l=d") . "\">D</a>&nbsp;";
        $pages .= "<a href=\"" . $helper->url("virtual.php?f=dam&selsire=" . $selsire . "&st=0&l=e") . "\">E</a>&nbsp;";
        $pages .= "<a href=\"" . $helper->url("virtual.php?f=dam&selsire=" . $selsire . "&st=0&l=f") . "\">F</a>&nbsp;";
        $pages .= "<a href=\"" . $helper->url("virtual.php?f=dam&selsire=" . $selsire . "&st=0&l=g") . "\">G</a>&nbsp;";
        $pages .= "<a href=\"" . $helper->url("virtual.php?f=dam&selsire=" . $selsire . "&st=0&l=h") . "\">H</a>&nbsp;";
        $pages .= "<a href=\"" . $helper->url("virtual.php?f=dam&selsire=" . $selsire . "&st=0&l=i") . "\">I</a>&nbsp;";
        $pages .= "<a href=\"" . $helper->url("virtual.php?f=dam&selsire=" . $selsire . "&st=0&l=j") . "\">J</a>&nbsp;";
        $pages .= "<a href=\"" . $helper->url("virtual.php?f=dam&selsire=" . $selsire . "&st=0&l=k") . "\">K</a>&nbsp;";
        $pages .= "<a href=\"" . $helper->url("virtual.php?f=dam&selsire=" . $selsire . "&st=0&l=l") . "\">L</a>&nbsp;";
        $pages .= "<a href=\"" . $helper->url("virtual.php?f=dam&selsire=" . $selsire . "&st=0&l=m") . "\">M</a>&nbsp;";
        $pages .= "<a href=\"" . $helper->url("virtual.php?f=dam&selsire=" . $selsire . "&st=0&l=n") . "\">N</a>&nbsp;";
        $pages .= "<a href=\"" . $helper->url("virtual.php?f=dam&selsire=" . $selsire . "&st=0&l=o") . "\">O</a>&nbsp;";
        $pages .= "<a href=\"" . $helper->url("virtual.php?f=dam&selsire=" . $selsire . "&st=0&l=p") . "\">P</a>&nbsp;";
        $pages .= "<a href=\"" . $helper->url("virtual.php?f=dam&selsire=" . $selsire . "&st=0&l=q") . "\">Q</a>&nbsp;";
        $pages .= "<a href=\"" . $helper->url("virtual.php?f=dam&selsire=" . $selsire . "&st=0&l=r") . "\">R</a>&nbsp;";
        $pages .= "<a href=\"" . $helper->url("virtual.php?f=dam&selsire=" . $selsire . "&st=0&l=s") . "\">S</a>&nbsp;";
        $pages .= "<a href=\"" . $helper->url("virtual.php?f=dam&selsire=" . $selsire . "&st=0&l=t") . "\">T</a>&nbsp;";
        $pages .= "<a href=\"" . $helper->url("virtual.php?f=dam&selsire=" . $selsire . "&st=0&l=u") . "\">U</a>&nbsp;";
        $pages .= "<a href=\"" . $helper->url("virtual.php?f=dam&selsire=" . $selsire . "&st=0&l=v") . "\">V</a>&nbsp;";
        $pages .= "<a href=\"" . $helper->url("virtual.php?f=dam&selsire=" . $selsire . "&st=0&l=w") . "\">W</a>&nbsp;";
        $pages .= "<a href=\"" . $helper->url("virtual.php?f=dam&selsire=" . $selsire . "&st=0&l=x") . "\">X</a>&nbsp;";
        $pages .= "<a href=\"" . $helper->url("virtual.php?f=dam&selsire=" . $selsire . "&st=0&l=y") . "\">Y</a>&nbsp;";
        $pages .= "<a href=\"" . $helper->url("virtual.php?f=dam&selsire=" . $selsire . "&st=0&l=z") . "\">Z</a>&nbsp;";
        $pages .= "-&nbsp;";
        $pages .= "<a href=\"" . $helper->url("virtual.php?f=dam&selsire=" . $selsire . "&st=0&l=Ã…") . "\">Ã…</a>&nbsp;";
        $pages .= "<a href=\"" . $helper->url("virtual.php?f=dam&selsire=" . $selsire . "&st=0&l=Ã–") . "\">Ã–</a>&nbsp;";
        //create linebreak
        $pages .= '<br>';
        //create previous button
        if ($numPages > 1) {
            if ($currentPage > 1) {
                $pages .= "<a href=\"" . $helper->url("virtual.php?f=dam&selsire={$selsire}&l={$l}&st=" . ($st - $perPage)) . "\">" . _MA_PEDIGREE_PREVIOUS . "</a>&nbsp;&nbsp";
            }
        }
        //create numbers
        for ($x = 1; $x < ($numPages + 1); ++$x) {
            //create line break after 20 number
            if (0 == ($x % 20)) {
                $pages .= '<br>';
            }
            if ($x != $currentPage) {
                $pages .= "<a href=\"" . $helper->url("virtual.php?f=dam&selsire={$selsire}&l={$l}&st=" . ($perPage * ($x - 1))) . "\">{$x}</a>&nbsp;&nbsp;";
            } else {
                $pages .= $x . '&nbsp;&nbsp';
            }
        }
        //create next button
        if ($numPages > 1) {
            if ($currentPage < $numPages) {
                $pages .= "<a href=\"" . $helper->url("virtual.php?f=dam&selsire={$selsire}&l={$l}&st=" . ($st + $perPage)) . "\">" . _MA_PEDIGREE_NEXT . "</a>&nbsp;&nbsp";
            }
        }

        //query
        $queryString = 'SELECT d.*, d.id AS d_id, d.naam AS d_naam FROM '
                       . $GLOBALS['xoopsDB']->prefix('pedigree_tree')
                       . ' d LEFT JOIN '
                       . $GLOBALS['xoopsDB']->prefix('pedigree_tree')
                       . ' m ON m.id = d.mother LEFT JOIN '
                       . $GLOBALS['xoopsDB']->prefix('pedigree_tree')
                       . " f ON f.id = d.father WHERE d.roft = '1' AND d.mother != '0' AND d.father != '0' AND m.mother != '0' AND m.father != '0' AND f.mother != '0' AND f.father != '0' AND d.naam LIKE '"
                       . $l
                       . "%' ORDER BY d.naam LIMIT "
                       . $st
                       . ', '
                       . $perPage;
        $result = $GLOBALS['xoopsDB']->query($queryString);

        $animal = new Pedigree\Animal();
        //test to find out how many user fields there are...
        $fields = $animal->getFieldsIds();
        $numOfColumns = 1;
        $columns[] = ['columnname' => 'Name'];
        foreach ($fields as $i => $iValue) {
            $userField = new Pedigree\Field($fields[$i], $animal->getConfig());
            $fieldType = $userField->getSetting('fieldtype');
            $fieldObject = new $fieldType($userField, $animal);
            //create empty string
            $lookupValues = '';
            if ($userField->isActive() && $userField->inList()) {
                if ($userField->hasLookup()) {
                    $lookupValues = $userField->lookupField($fields[$i]);
                    //debug information
                    //print_r($lookupValues);
                }
                $columns[] = [
                    'columnname' => $fieldObject->fieldname,
                    'columnnumber' => $userField->getId(),
                    'lookupval' => $lookupValues,
                ];
                ++$numOfColumns;
                unset($lookupValues);
            }
        }

        while (false !== ($row = $GLOBALS['xoopsDB']->fetchArray($result))) {
            //create picture information
            if ('' != $row['foto']) {
                $camera = ' <img src="' . PEDIGREE_UPLOAD_URL . '/images/dog-icon25.png">';
            } else {
                $camera = '';
            }
            $name = stripslashes($row['d_naam']) . $camera;
            //empty array
            unset($columnvalue);
            //fill array
            for ($i = 1; $i < $numOfColumns; ++$i) {
                $x = $columns[$i]['columnnumber'];
                //echo $x."columnnumber";
                if (is_array($columns[$i]['lookupval'])) {
                    foreach ($columns[$i]['lookupval'] as $key => $keyValue) {
                        if ($keyValue['id'] == $row['user' . $x]) {
                            //echo "key:".$row['user5']."<br>";
                            $value = $keyValue['value'];
                        }
                    }
                    //debug information
                    ///echo $columns[$i]['columnname']."is an array !";
                } //format value - cant use object because of query count
                elseif (0 === strncmp($row['user' . $x], 'http://', 7)) {
                    $value = '<a href="' . $row['user' . $x] . '">' . $row['user' . $x] . '</a>';
                } else {
                    $value = $row['user' . $x];
                }
                $columnvalue[] = ['value' => $value];
                unset($value);
            }
            $dogs[] = [
                'id' => $row['d_id'],
                'name' => $name,
                //@todo add alt and title tags
                'gender' => "<img src=\"" . PEDIGREE_IMAGE_URL . "/female.gif\">",
                'link' => "<a href=\"" / $helper->url("virtual.php?f=check&selsire={$selsire}&seldam={$row['d_id']}") . "\">{$name}</a>",
                'colour' => '',
                'number' => '',
                'usercolumns' => isset($columnvalue) ? $columnvalue : 0,
            ];
        }

        //add data to smarty template
        $GLOBALS['xoopsTpl']->assign([
            'dogs' => $dogs,
            'columns' => $columns,
            'numofcolumns' => $numOfColumns,
            'tsarray' => Pedigree\Utility::sortTable($numOfColumns),
            'nummatch' => strtr(_MA_PEDIGREE_ADD_SELDAM, ['[mother]' => $helper->getConfig('mother')]),
            'pages' => $pages,
            'virtualtitle' => _MA_PEDIGREE_VIRUTALTIT,
            'virtualstory' => strtr(_MA_PEDIGREE_VIRUTALSTO, [
                '[mother]' => $helper->getConfig('mother'),
                '[father]' => $helper->getConfig('father'),
                '[children]' => $helper->getConfig('children')]),
            'nextaction' => '<span style="font-weight: bold;">' . strtr(_MA_PEDIGREE_VIRT_DAM, ['[mother]' => $helper->getConfig('mother')]) . '</span>',
            'virtualsiretitle' => strtr(_MA_PEDIGREE_VIRTUALSTIT, ['[father]' => $helper->getConfig('father')])
        ]);

        //Find Father
        //@todo - this looks wrong, shouldn't this be looking at 'father' field, not 'id'
        $sireArray = $treeHandler->get($selsire);
        $vsire = $sireArray instanceof Pedigree\Tree ? $sireArray->getVar('naam') : '';
        /*
        $query = 'SELECT id, naam FROM ' . $GLOBALS['xoopsDB']->prefix('pedigree_tree') . ' WHERE id=' . $selsire;
        $result = $GLOBALS['xoopsDB']->query($query);
        while (false !== ($row = $GLOBALS['xoopsDB']->fetchArray($result))) {
            $vsire = stripslashes($row['naam']);
        }
        */
        $GLOBALS['xoopsTpl']->assign('virtualsire', $vsire);

        //mb ========= MOTHER LETTERS===============================
        $roft = 1;
        $name = 'naam';
        $link = "virtual.php?r=1&st=0&l=";

        $criteria = $helper->getHandler('Tree')->getActiveCriteria($roft);
        //$criteria->setGroupby('UPPER(LEFT(' . $name . ',1))');

        $motherArray['letters'] = Pedigree\Utility::lettersChoice($helper, 'Tree', $criteria, $name, $link);
        //$catarray['toolbar'] = pedigree_toolbar();
        $GLOBALS['xoopsTpl']->assign('motherArray', $motherArray);
        break;

    case 'check':

        $selsire = Request::getInt('selsire', 0, 'GET');
        $seldam = Request::getInt('seldam', 0, 'GET');

        $treeHandler = $helper->getHandler('Tree');

        $GLOBALS['xoopsTpl']->assign([
            'virtualtitle' => _MA_PEDIGREE_VIRUTALTIT,
            'virtualstory' => strtr(_MA_PEDIGREE_VIRUTALSTO, [
                                '[mother]' => $helper->getConfig('mother'),
                                '[father]' => $helper->getConfig('father'),
                                '[children]' => $helper->getConfig('children')
            ]),
            'virtualsiretitle' => strtr(_MA_PEDIGREE_VIRTUALSTIT, ['[father]' => $helper->getConfig('father')]),
            'virtualdamtitle' => strtr(_MA_PEDIGREE_VIRTUALDTIT, ['[mother]' => $helper->getConfig('mother')]),
            'form' => "<a href=\"" . $helper->url("coi.php?s={$selsire}&d={$seldam}&dogid=&detail=1") . "\">" . _MA_PEDIGREE_VIRTUALBUT . "</a>"
        ]);
        //Find Father
        //@todo - this looks wrong, shouldn't this be looking at 'father' field, not 'id'
        $sireArray = $treeHandler->get($selsire);
        $vsire = $sireArray instanceof Pedigree\Tree ? $sireArray->getVar('naam') : '';
        /*
        $query = 'SELECT id, naam FROM ' . $GLOBALS['xoopsDB']->prefix('pedigree_tree') . ' WHERE id=' . $selsire;
        $result = $GLOBALS['xoopsDB']->query($query);
        while (false !== ($row = $GLOBALS['xoopsDB']->fetchArray($result))) {
            $vsire = stripslashes($row['naam']);
        }
        */
        $GLOBALS['xoopsTpl']->assign('virtualsire', $vsire);

        //Find Mother
        //@todo - this looks wrong, shouldn't this be looking at 'mother' field, not 'id'
        $damArray = $treeHandler->get($seldam);
        $vdam = $damArray instanceof Pedigree\Tree ? $damArray->getVar('naam') : '';
        /*
        $query = 'SELECT id, naam FROM ' . $GLOBALS['xoopsDB']->prefix('pedigree_tree') . ' WHERE id=' . $seldam;
        $result = $GLOBALS['xoopsDB']->query($query);
        while (false !== ($row = $GLOBALS['xoopsDB']->fetchArray($result))) {
            $vdam = stripslashes($row['naam']);
        }
        */
        $GLOBALS['xoopsTpl']->assign('virtualdam', $vdam);
        break;

    default:
        $st = Request::getInt('st', 0, 'GET');
        $l = Request::getString('l', 'a', 'GET');

        $GLOBALS['xoopsTpl']->assign('sire', '1');
        //create list of males dog to select from
        $perPage = $helper->getConfig('perpage', Constants::DEFAULT_PER_PAGE);
        $perPage = (int)$perPage > 0 ? (int)$perPage : Constants::DEFAULT_PER_PAGE; // make sure $perPage is 'valid'
        //count total number of dogs
        $numDog = 'SELECT COUNT(d.id) FROM '
                . $GLOBALS['xoopsDB']->prefix('pedigree_tree')
                . ' d LEFT JOIN '
                . $GLOBALS['xoopsDB']->prefix('pedigree_tree')
                . ' m ON m.id = d.mother LEFT JOIN '
                . $GLOBALS['xoopsDB']->prefix('pedigree_tree')
                //. " f ON f.id = d.father WHERE d.roft = '0' and d.mother != '0' and d.father != '0' and m.mother != '0' and m.father != '0' and f.mother != '0' and f.father != '0' and d.naam LIKE '" . $l . "%'";
                . " f ON f.id = d.father WHERE d.roft = '0' AND d.mother != '0' AND d.father != '0' AND m.mother != '0' AND m.father != '0' AND f.mother != '0' AND f.father != '0' AND d.naam LIKE '"
                . $GLOBALS['xoopsDB']->escape($l)
                . "%'";
        $numRes = $GLOBALS['xoopsDB']->query($numDog);
        //total number of dogs the query will find
        list($numResults) = $GLOBALS['xoopsDB']->fetchRow($numRes);
        //total number of pages
        $numPages = floor($numResults / $perPage) + 1;
        if (($numPages * $perPage) == ($numResults + $perPage)) {
            --$numPages;
        }
        //find current page
        $currentPage = floor($st / $perPage) + 1;
        //create alphabet
        //@todo this needs to be refactored for non-English languages
        $pages = '';
        for ($i = 65; $i <= 90; ++$i) {
            if ($l == chr($i)) {
                $pages .= "<span style=\"font-weight: bold;\"><a href=\"" . $helper->url("virtual.php?r=1&st=0&l=" . chr($i)) . "\">" . chr($i) . "</a></span>&nbsp;";
            } else {
                $pages .= "<a href=\"" . $helper->url("virtual.php?r=1&st=0&l=" . chr($i)) . "\">" . chr($i) . "</a>&nbsp;";
            }
        }
        $pages .= '-&nbsp;';
        $pages .= "<a href=\"" . $helper->url("virtual.php?r=1&st=0&l=Ã…") . "\">Ã…</a>&nbsp;";
        $pages .= "<a href=\"" . $helper->url("virtual.php?r=1&st=0&l=Ã–") . "\">Ã–</a>&nbsp;";
        $pages .= "<br>\n";
        //create previous button
        if (($numPages > 1) && ($currentPage > 1)) {
            $pages .= "<a href=\"" . $helper->url("virtual.php?r=1&&l={$l}&st=" . ($st - $perPage)) . "\">" . _MA_PEDIGREE_PREVIOUS . "</a>&nbsp;&nbsp";
        }
        //create numbers
        $xLimit = $numPages + 1;
        for ($x = 1; $x < $xLimit; ++$x) {
            //create line break after 20 number
            if (0 == ($x % 20)) {
                $pages .= '<br>';
            }
            if ($x != $currentPage) {
                $pages .= "<a href=\"" . $helper->url("virtual.php?r=1&l={$l}&st=" . ($perPage * ($x - 1))) . "\">{$x}</a>&nbsp;&nbsp;";
            } else {
                $pages .= $x . '&nbsp;&nbsp';
            }
        }
        //create next button
        if ($numPages > 1) {
            if ($currentPage < $numPages) {
                $pages .= "<a href=\"" . $helper->url("virtual.php?r=1&l={$l}&st=" . ($st + $perPage)) . "\">" . _MA_PEDIGREE_NEXT . "</a>&nbsp;&nbsp";
            }
        }

        //query
        $queryString = 'SELECT d.*, d.id AS d_id, d.naam AS d_naam FROM '
                     . $GLOBALS['xoopsDB']->prefix('pedigree_tree')
                     . ' d LEFT JOIN '
                     . $GLOBALS['xoopsDB']->prefix('pedigree_tree')
                     . ' m ON m.id = d.mother LEFT JOIN '
                     . $GLOBALS['xoopsDB']->prefix('pedigree_tree')
                     . " f ON f.id = d.father WHERE d.roft = '0' AND d.mother != '0' AND d.father != '0' AND m.mother != '0' AND m.father != '0' AND f.mother != '0' AND f.father != '0' AND d.naam LIKE '"
                     . $l
                     . "%' ORDER BY d.naam LIMIT "
                     . $st
                     . ', '
                     . $perPage;
        $result = $GLOBALS['xoopsDB']->query($queryString);
        $animal = new Pedigree\Animal();
        //test to find out how many user fields there are...
        $fields = $animal->getFieldsIds();
        $animalConfig = $animal->getConfig();
        $numOfColumns = 1;
        $columns[] = ['columnname' => 'Name'];
        foreach ($fields as $i => $iValue) {
            $userField = new Pedigree\Field($fields[$i], $animalConfig);
            $fieldType = $userField->getSetting('fieldtype');
            $fieldObject = new $fieldType($userField, $animal);
            //create empty string
            $lookupValues = '';
            if ($userField->isActive() && $userField->inList()) {
                if ($userField->hasLookup()) {
                    $lookupValues = $userField->lookupField($fields[$i]);
                    //debug information
                    //print_r($lookupValues);
                }
                $columns[] = [
                    'columnname' => $fieldObject->fieldname,
                    'columnnumber' => $userField->getId(),
                    'lookupval' => $lookupValues,
                ];
                ++$numOfColumns;
                unset($lookupValues);
            }
        }

        while (false !== ($row = $GLOBALS['xoopsDB']->fetchArray($result))) {
            //create picture information
            if ('' != $row['foto']) {
                $camera = ' <img src="' . PEDIGREE_UPLOAD_URL . '/images/dog-icon25.png">';
            } else {
                $camera = '';
            }
            $name = stripslashes($row['d_naam']) . $camera;
            //empty array
            unset($columnvalue);
            //fill array
            for ($i = 1; $i < $numOfColumns; ++$i) {
                $x = $columns[$i]['columnnumber'];
                //echo $x."columnnumber";
                if (is_array($columns[$i]['lookupval'])) {
                    foreach ($columns[$i]['lookupval'] as $key => $keyValue) {
                        if ($keyValue['id'] == $row['user' . $x]) {
                            //echo "key:".$row['user5']."<br>";
                            $value = $keyValue['value'];
                        }
                    }
                    //debug information
                    ///echo $columns[$i]['columnname']."is an array !";
                } //format value - cant use object because of query count
                elseif (0 === strncmp($row['user' . $x], 'http://', 7)) { //@todo need to make this so allows for https:// too
                    $value = '<a href="' . $row['user' . $x] . '">' . $row['user' . $x] . '</a>';
                } else {
                    $value = $row['user' . $x];
                }
                $columnvalue[] = ['value' => $value];
                unset($value);
            }
            $dogs[] = [
                'id' => $row['d_id'],
                'name' => $name,
                //@todo add alt and title tags
                'gender' => "<img src=\"" . PEDIGREE_IMAGE_URL . "/male.gif\">",
                'link' => "<a href=\"" . $helper->url("virtual.php?f=dam&selsire=" . $row['d_id']) . "\">{$name}</a>",
                'colour' => '',
                'number' => '',
                'usercolumns' => isset($columnvalue) ? $columnvalue : 0,
            ];
        }

        //add data to smarty template
        //assign dog
        if (isset($dogs)) {
            $GLOBALS['xoopsTpl']->assign('dogs', $dogs);
        }
        $GLOBALS['xoopsTpl']->assign([
            'columns' => $columns,
            'numofcolumns' => $numOfColumns,
            'tsarray' => Pedigree\Utility::sortTable($numOfColumns),
            'nummatch' => strtr(_MA_PEDIGREE_ADD_SELSIRE, ['[father]' => $helper->getConfig('father')]),
            'pages' => $pages,
            'virtualtitle' => strtr(_MA_PEDIGREE_VIRUTALTIT, ['[mother]' => $helper->getConfig('mother')]),
            'virtualstory' => strtr(_MA_PEDIGREE_VIRUTALSTO, [
                '[mother]' => $helper->getConfig('mother'),
                '[father]' => $helper->getConfig('father'),
                '[children]' => $helper->getConfig('children')]),
            'nextaction' => '<span style="font-weight: bold;">' . strtr(_MA_PEDIGREE_VIRT_SIRE, ['[father]' => $helper->getConfig('father')]) . '</span>'
        ]);

        //mb =========== FATHER LETTERS =============================
        $roft = 0;
        $name = 'naam';
        $link = "virtual.php?r=1&st=0&l=";
        $link2 = '';

        $criteria = $helper->getHandler('Tree')->getActiveCriteria($roft);
        //$criteria = $helper->getHandler('Tree')->getActiveCriteria($roft);
        //$criteria->setGroupby('UPPER(LEFT(' . $name . ',1))');

        $fatherArray['letters'] = Pedigree\Utility::lettersChoice($helper, 'Tree', $criteria, $name, $link, $link2);
        //$catarray['toolbar'] = pedigree_toolbar();
        $GLOBALS['xoopsTpl']->assign('fatherArray', $fatherArray);
        break;
}

//footer
include $GLOBALS['xoops']->path('footer.php');
