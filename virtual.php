<?php
// -------------------------------------------------------------------------

require_once dirname(dirname(__DIR__)) . '/mainfile.php';

$moduleDirName = basename(__DIR__);
xoops_loadLanguage('main', $moduleDirName);
xoops_load('PedigreeAnimal', $moduleDirName);
xoops_load('XoopsRequest');

// Include any common code for this module.
require_once XOOPS_ROOT_PATH . "/modules/{$moduleDirName}/include/common.php";

$pedigree = PedigreePedigree::getInstance(false);

$xoopsOption['template_main'] = 'pedigree_virtual.tpl';

include $GLOBALS['xoops']->path('/header.php');
$xoopsTpl->assign('page_title', 'Pedigree database - Virtual Mating');

//create function variable from url
//if (isset($_GET['f'])) {
//    $f = $_GET['f'];
//}
//if (!isset($f)) {
$f = XoopsRequest::getString('f', '', 'get');
switch ($f) {
    default: // virt
/*
        //get module configuration
        $moduleHandler = xoops_getHandler('module');
        $module        = $moduleHandler->getByDirname('pedigree');
        $configHandler = xoops_getHandler('config');
        $moduleConfig  = $configHandler->getConfigsByCat(0, $module->getVar('mid'));
*/
        //    if (isset($_GET['st'])) {
        //        $st = $_GET['st'];
        //    } else {
        //        $st = 0;
        //    }
        //    if (isset($_GET['l'])) {
        //        $l = $_GET['l'];
        //    } else {
        //        $l = 'A';
        //    }
        $st = XoopsRequest::getInt('st', 0, 'get');
        $l  = XoopsRequest::getString('l', 'a', 'get');

        $xoopsTpl->assign('sire', '1');
        //create list of males dog to select from
        $perp = $pedigree->getConfig('perpage');
        //count total number of dogs
        $numdog = 'SELECT COUNT(d.Id) FROM ' . $GLOBALS['xoopsDB']->prefix('pedigree_tree') . ' d LEFT JOIN ' . $GLOBALS['xoopsDB']->prefix('pedigree_tree') . ' m ON m.Id = d.mother LEFT JOIN ' . $GLOBALS['xoopsDB']->prefix('pedigree_tree') //            . " f ON f.id = d.father WHERE d.roft = '0' and d.mother != '0' and d.father != '0' and m.mother != '0' and m.father != '0' and f.mother != '0' and f.father != '0' and d.NAAM LIKE '" . $l . "%'";
                  . " f ON f.Id = d.father WHERE d.roft = '0' AND d.mother != '0' AND d.father != '0' AND m.mother != '0' AND m.father != '0' AND f.mother != '0' AND f.father != '0' AND d.NAAM LIKE '" . $GLOBALS['xoopsDB']->escape($l) . "%'";
        $numres = $GLOBALS['xoopsDB']->query($numdog);
        //total number of dogs the query will find
        list($numresults) = $GLOBALS['xoopsDB']->fetchRow($numres);
        //total number of pages
        $numpages = floor($numresults / $perp) + 1;
        if (($numpages * $perp) == ($numresults + $perp)) {
            --$numpages;
        }
        //find current page
        $cpage = floor($st / $perp) + 1;
        //create alphabet
        $pages = '';
        for ($i = 65; $i <= 90; ++$i) {
            if ($l == chr($i)) {
                $pages .= "<b><a href=\"virtual.php?r=1&st=0&l=" . chr($i) . "\">" . chr($i) . '</a></b>&nbsp;';
            } else {
                $pages .= "<a href=\"virtual.php?r=1&st=0&l=" . chr($i) . "\">" . chr($i) . '</a>&nbsp;';
            }
        }
        $pages .= '-&nbsp;';
        $pages .= "<a href=\"virtual.php?r=1&st=0&l=Ã…\">Ã…</a>&nbsp;";
        $pages .= "<a href=\"virtual.php?r=1&st=0&l=Ã–\">Ã–</a>&nbsp;";
        $pages .= '<br />';
        //create previous button
        if (($numpages > 1) && ($cpage > 1)) {
            $pages .= "<a href=\"virtual.php?r=1&&l=" . $l . 'st=' . ($st - $perp) . "\">" . _MA_PEDIGREE_PREVIOUS . '</a>&nbsp;&nbsp';
        }
        //create numbers
        $xLimit = $numpages + 1;
        for ($x = 1; $x < $xLimit; ++$x) {
            //create line break after 20 number
            if (($x % 20) == 0) {
                $pages .= '<br />';
            }
            if ($x != $cpage) {
                $pages .= "<a href=\"virtual.php?r=1&l=" . $l . '&st=' . ($perp * ($x - 1)) . "\">" . $x . '</a>&nbsp;&nbsp;';
            } else {
                $pages .= $x . '&nbsp;&nbsp';
            }
        }
        //create next button
        if ($numpages > 1) {
            if ($cpage < $numpages) {
                $pages .= "<a href=\"virtual.php?r=1&l=" . $l . '&st=' . ($st + $perp) . "\">" . _MA_PEDIGREE_NEXT . '</a>&nbsp;&nbsp';
            }
        }

        //query
        $queryString = 'SELECT d.*, d.Id AS d_id, d.NAAM AS d_naam FROM ' . $GLOBALS['xoopsDB']->prefix('pedigree_tree') . ' d LEFT JOIN ' . $GLOBALS['xoopsDB']->prefix('pedigree_tree') . ' m ON m.Id = d.mother LEFT JOIN ' . $GLOBALS['xoopsDB']->prefix('pedigree_tree') . " f ON f.Id = d.father WHERE d.roft = '0' AND d.mother != '0' AND d.father != '0' AND m.mother != '0' AND m.father != '0' AND f.mother != '0' AND f.father != '0' AND d.NAAM LIKE '" . $l . "%' ORDER BY d.NAAM LIMIT " . $st . ', ' . $perp;
        $result      = $GLOBALS['xoopsDB']->query($queryString);

        $animal = new PedigreeAnimal();
        //test to find out how many user fields there are...
        $fields       = $animal->getNumOfFields();
        $numofcolumns = 1;
        $columns[]    = array('columnname' => 'Name');
        for ($i = 0, $iMax = count($fields); $i < $iMax; ++$i) {
            $userField   = new Field($fields[$i], $animal->getConfig());
            $fieldType   = $userField->getSetting('fieldtype');
            $fieldObject = new $fieldType($userField, $animal);
            //create empty string
            $lookupvalues = '';
            if ($userField->isActive() && $userField->inList()) {
                if ($userField->hasLookup()) {
                    $lookupvalues = $userField->lookupField($fields[$i]);
                    //debug information
                    //print_r($lookupvalues);
                }
                $columns[] = array('columnname' => $fieldObject->fieldname, 'columnnumber' => $userField->getId(), 'lookupval' => $lookupvalues);
                ++$numofcolumns;
                unset($lookupvalues);
            }
        }

        while (false !== ($row = $GLOBALS['xoopsDB']->fetchArray($result))) {
            //create picture information
            if ($row['foto'] != '') {
                $camera = " <img src=\"assets/images/dog-icon25.png\">";
            } else {
                $camera = '';
            }
            $name = stripslashes($row['d_naam']) . $camera;
            //empty array
            unset($columnvalue);
            //fill array
            for ($i = 1; $i < $numofcolumns; ++$i) {
                $x = $columns[$i]['columnnumber'];
                //echo $x."columnnumber";
                if (is_array($columns[$i]['lookupval'])) {
                    foreach ($columns[$i]['lookupval'] as $key => $keyvalue) {
                        if ($keyvalue['id'] == $row['user' . $x]) {
                            //echo "key:".$row['user5']."<br />";
                            $value = $keyvalue['value'];
                        }
                    }
                    //debug information
                    ///echo $columns[$i]['columnname']."is an array !";
                } //format value - cant use object because of query count
                elseif (0 === strpos($row['user' . $x], 'http://')) {
                    $value = "<a href=\"" . $row['user' . $x] . "\">" . $row['user' . $x] . '</a>';
                } else {
                    $value = $row['user' . $x];
                }
                $columnvalue[] = array('value' => $value);
                unset($value);
            }
            $dogs[] = array(
                'id'          => $row['d_id'],
                'name'        => $name,
                'gender'      => "<img src=\"assets/images/male.gif\" alt=\"" . _MA_PEDIGREE_FEMALE . "\">",
                'link'        => "<a href=\"virtual.php?f=dam&selsire=" . $row['d_id'] . "\">" . $name . '</a>',
                'colour'      => '',
                'number'      => '',
                'usercolumns' => isset($columnvalue) ? $columnvalue : 0
            );
        }

        //add data to smarty template
        //assign dog
        if (isset($dogs)) {
            $xoopsTpl->assign('dogs', $dogs);
        }
        $xoopsTpl->assign('columns', $columns);
        $xoopsTpl->assign('numofcolumns', $numofcolumns);
        $xoopsTpl->assign('tsarray', PedigreeUtilities::sortTable($numofcolumns));
        $xoopsTpl->assign('nummatch', strtr(_MA_PEDIGREE_ADD_SELSIRE, array('[father]' => $pedigree->getConfig('father'))));
        $xoopsTpl->assign('pages', $pages);

        $xoopsTpl->assign('virtualtitle', strtr(_MA_PEDIGREE_VIRUTALTIT, array('[mother]' => $pedigree->getConfig('mother'))));
        $xoopsTpl->assign('virtualstory', strtr(_MA_PEDIGREE_VIRUTALSTO, array('[mother]' => $pedigree->getConfig('mother'), '[father]' => $pedigree->getConfig('father'), '[children]' => $pedigree->getConfig('children'))));
        $xoopsTpl->assign('nextaction', '<b>' . strtr(_MA_PEDIGREE_VIRT_SIRE, array('[father]' => $pedigree->getConfig('father'))) . '</b>');
        break;

    case 'dam':
        global $xoopsTpl;
        $pedigree = PedigreePedigree::getInstance(false);
        $pages = '';
/*
        //get module configuration
        $moduleHandler = xoops_getHandler('module');
        $module        = $moduleHandler->getByDirname('pedigree');
        $configHandler = xoops_getHandler('config');
        $moduleConfig  = $configHandler->getConfigsByCat(0, $module->getVar('mid'));
*/
        $st = XoopsRequest::getInt('st', 0, 'GET');
        $l  = XoopsRequest::getWord('l', 'A', 'GET');
        $selsire = XoopsRequest::getInt('selsire', 0, 'GET');
/*
        if (isset($_GET['st'])) {
            $st = $_GET['st'];
        } else {
            $st = 0;
        }
        if (isset($_GET['l'])) {
            $l = $_GET['l'];
        } else {
            $l = 'A';
        }
        $selsire = $_GET['selsire'];
*/

        $xoopsTpl->assign('sire', '1');
        //create list of males dog to select from
        $perp = $pedigree->getConfig('perpage');
        //count total number of dogs
        $numdog = 'SELECT COUNT(d.Id) FROM ' . $GLOBALS['xoopsDB']->prefix('pedigree_tree') . ' d LEFT JOIN ' . $GLOBALS['xoopsDB']->prefix('pedigree_tree') . ' m ON m.Id = d.mother LEFT JOIN ' . $GLOBALS['xoopsDB']->prefix('pedigree_tree') //  . " f ON f.id = d.father WHERE d.roft = '1' and d.mother != '0' and d.father != '0' and m.mother != '0' and m.father != '0' and f.mother != '0' and f.father != '0' and d.NAAM LIKE '" . $l . "%'";
                  . " f ON f.Id = d.father WHERE d.roft = '1' AND d.mother != '0' AND d.father != '0' AND m.mother != '0' AND m.father != '0' AND f.mother != '0' AND f.father != '0' AND d.NAAM LIKE '" . $GLOBALS['xoopsDB']->escape($l) . "%'";

        $numres = $GLOBALS['xoopsDB']->query($numdog);
        //total number of dogs the query will find
        list($numresults) = $GLOBALS['xoopsDB']->fetchRow($numres);
        //total number of pages
        $numpages = floor($numresults / $perp) + 1;
        if (($numpages * $perp) == ($numresults + $perp)) {
            --$numpages;
        }
        //find current page
        $cpage = floor($st / $perp) + 1;
        //create the alphabet
        $pages .= "<a href=\"virtual.php?f=dam&selsire=" . $selsire . "&st=0&l=a\">A</a>&nbsp;";
        $pages .= "<a href=\"virtual.php?f=dam&selsire=" . $selsire . "&st=0&l=b\">B</a>&nbsp;";
        $pages .= "<a href=\"virtual.php?f=dam&selsire=" . $selsire . "&st=0&l=c\">C</a>&nbsp;";
        $pages .= "<a href=\"virtual.php?f=dam&selsire=" . $selsire . "&st=0&l=d\">D</a>&nbsp;";
        $pages .= "<a href=\"virtual.php?f=dam&selsire=" . $selsire . "&st=0&l=e\">E</a>&nbsp;";
        $pages .= "<a href=\"virtual.php?f=dam&selsire=" . $selsire . "&st=0&l=f\">F</a>&nbsp;";
        $pages .= "<a href=\"virtual.php?f=dam&selsire=" . $selsire . "&st=0&l=g\">G</a>&nbsp;";
        $pages .= "<a href=\"virtual.php?f=dam&selsire=" . $selsire . "&st=0&l=h\">H</a>&nbsp;";
        $pages .= "<a href=\"virtual.php?f=dam&selsire=" . $selsire . "&st=0&l=i\">I</a>&nbsp;";
        $pages .= "<a href=\"virtual.php?f=dam&selsire=" . $selsire . "&st=0&l=j\">J</a>&nbsp;";
        $pages .= "<a href=\"virtual.php?f=dam&selsire=" . $selsire . "&st=0&l=k\">K</a>&nbsp;";
        $pages .= "<a href=\"virtual.php?f=dam&selsire=" . $selsire . "&st=0&l=l\">L</a>&nbsp;";
        $pages .= "<a href=\"virtual.php?f=dam&selsire=" . $selsire . "&st=0&l=m\">M</a>&nbsp;";
        $pages .= "<a href=\"virtual.php?f=dam&selsire=" . $selsire . "&st=0&l=n\">N</a>&nbsp;";
        $pages .= "<a href=\"virtual.php?f=dam&selsire=" . $selsire . "&st=0&l=o\">O</a>&nbsp;";
        $pages .= "<a href=\"virtual.php?f=dam&selsire=" . $selsire . "&st=0&l=p\">P</a>&nbsp;";
        $pages .= "<a href=\"virtual.php?f=dam&selsire=" . $selsire . "&st=0&l=q\">Q</a>&nbsp;";
        $pages .= "<a href=\"virtual.php?f=dam&selsire=" . $selsire . "&st=0&l=r\">R</a>&nbsp;";
        $pages .= "<a href=\"virtual.php?f=dam&selsire=" . $selsire . "&st=0&l=s\">S</a>&nbsp;";
        $pages .= "<a href=\"virtual.php?f=dam&selsire=" . $selsire . "&st=0&l=t\">T</a>&nbsp;";
        $pages .= "<a href=\"virtual.php?f=dam&selsire=" . $selsire . "&st=0&l=u\">U</a>&nbsp;";
        $pages .= "<a href=\"virtual.php?f=dam&selsire=" . $selsire . "&st=0&l=v\">V</a>&nbsp;";
        $pages .= "<a href=\"virtual.php?f=dam&selsire=" . $selsire . "&st=0&l=w\">W</a>&nbsp;";
        $pages .= "<a href=\"virtual.php?f=dam&selsire=" . $selsire . "&st=0&l=x\">X</a>&nbsp;";
        $pages .= "<a href=\"virtual.php?f=dam&selsire=" . $selsire . "&st=0&l=y\">Y</a>&nbsp;";
        $pages .= "<a href=\"virtual.php?f=dam&selsire=" . $selsire . "&st=0&l=z\">Z</a>&nbsp;";
        $pages .= '-&nbsp;';
        $pages .= "<a href=\"virtual.php?f=dam&selsire=" . $selsire . "&st=0&l=Ã…\">Ã…</a>&nbsp;";
        $pages .= "<a href=\"virtual.php?f=dam&selsire=" . $selsire . "&st=0&l=Ã–\">Ã–</a>&nbsp;";
        //create linebreak
        $pages .= '<br />';
        //create previous button
        if ($numpages > 1) {
            if ($cpage > 1) {
                $pages .= "<a href=\"virtual.php?f=dam&selsire=" . $selsire . '&l=' . $l . '&st=' . ($st - $perp) . "\">" . _MA_PEDIGREE_PREVIOUS . '</a>&nbsp;&nbsp';
            }
        }
        //create numbers
        for ($x = 1; $x < ($numpages + 1); ++$x) {
            //create line break after 20 number
            if (($x % 20) == 0) {
                $pages .= '<br />';
            }
            if ($x != $cpage) {
                $pages .= "<a href=\"virtual.php?f=dam&selsire=" . $selsire . '&l=' . $l . '&st=' . ($perp * ($x - 1)) . "\">" . $x . '</a>&nbsp;&nbsp;';
            } else {
                $pages .= $x . '&nbsp;&nbsp';
            }
        }
        //create next button
        if ($numpages > 1) {
            if ($cpage < $numpages) {
                $pages .= "<a href=\"virtual.php?f=dam&selsire=" . $selsire . '&l=' . $l . '&st=' . ($st + $perp) . "\">" . _MA_PEDIGREE_NEXT . '</a>&nbsp;&nbsp';
            }
        }

        //query
        $queryString = 'SELECT d.*, d.Id AS d_id, d.NAAM AS d_naam FROM ' . $GLOBALS['xoopsDB']->prefix('pedigree_tree') . ' d LEFT JOIN ' . $GLOBALS['xoopsDB']->prefix('pedigree_tree') . ' m ON m.Id = d.mother LEFT JOIN ' . $GLOBALS['xoopsDB']->prefix('pedigree_tree') . " f ON f.Id = d.father WHERE d.roft = '1' AND d.mother != '0' AND d.father != '0' AND m.mother != '0' AND m.father != '0' AND f.mother != '0' AND f.father != '0' AND d.NAAM LIKE '" . $l . "%' ORDER BY d.NAAM LIMIT " . $st . ', ' . $perp;
        $result      = $GLOBALS['xoopsDB']->query($queryString);

        $animal = new PedigreeAnimal();
        //test to find out how many user fields there are...
        $fields       = $animal->getNumOfFields();
        $numofcolumns = 1;
        $columns[]    = array('columnname' => 'Name');
        for ($i = 0, $iMax = count($fields); $i < $iMax; ++$i) {
            $userField   = new Field($fields[$i], $animal->getConfig());
            $fieldType   = $userField->getSetting('fieldtype');
            $fieldObject = new $fieldType($userField, $animal);
            //create empty string
            $lookupvalues = '';
            if ($userField->isActive() && $userField->inList()) {
                if ($userField->hasLookup()) {
                    $lookupvalues = $userField->lookupField($fields[$i]);
                    //debug information
                    //print_r($lookupvalues);
                }
                $columns[] = array('columnname' => $fieldObject->fieldname, 'columnnumber' => $userField->getId(), 'lookupval' => $lookupvalues);
                ++$numofcolumns;
                unset($lookupvalues);
            }
        }

        while (false !== ($row = $GLOBALS['xoopsDB']->fetchArray($result))) {
            //create picture information
            if ($row['foto'] != '') {
                $camera = " <img src=\"assets/images/dog-icon25.png\">";
            } else {
                $camera = '';
            }
            $name = stripslashes($row['d_naam']) . $camera;
            //empty array
            unset($columnvalue);
            //fill array
            for ($i = 1; $i < $numofcolumns; ++$i) {
                $x = $columns[$i]['columnnumber'];
                //echo $x."columnnumber";
                if (is_array($columns[$i]['lookupval'])) {
                    foreach ($columns[$i]['lookupval'] as $key => $keyvalue) {
                        if ($keyvalue['id'] == $row['user' . $x]) {
                            //echo "key:".$row['user5']."<br />";
                            $value = $keyvalue['value'];
                        }
                    }
                    //debug information
                    ///echo $columns[$i]['columnname']."is an array !";
                } //format value - cant use object because of query count
                elseif (0 === strpos($row['user' . $x], 'http://')) {
                    $value = "<a href=\"" . $row['user' . $x] . "\">" . $row['user' . $x] . '</a>';
                } else {
                    $value = $row['user' . $x];
                }
                $columnvalue[] = array('value' => $value);
                unset($value);
            }
            $dogs[] = array(
                'id'          => $row['d_id'],
                'name'        => $name,
                'gender'      => "<img src=\"assets/images/female.gif\" alt=\"" . _MA_PEDIGREE_FEMALE . "\">",
                'link'        => "<a href=\"virtual.php?f=check&selsire=" . $selsire . '&seldam=' . $row['d_id'] . "\">" . $name . '</a>',
                'colour'      => '',
                'number'      => '',
                'usercolumns' => isset($columnvalue) ? $columnvalue : 0
            );
        }

        //add data to smarty template
        //assign dog
        $xoopsTpl->assign('dogs', $dogs);
        $xoopsTpl->assign('columns', $columns);
        $xoopsTpl->assign('numofcolumns', $numofcolumns);
        $xoopsTpl->assign('tsarray', PedigreeUtilities::sortTable($numofcolumns));
        $xoopsTpl->assign('nummatch', strtr(_MA_PEDIGREE_ADD_SELDAM, array('[mother]' => $pedigree->getConfig('mother'))));
        $xoopsTpl->assign('pages', $pages);

        $xoopsTpl->assign('virtualtitle', _MA_PEDIGREE_VIRUTALTIT);
        $xoopsTpl->assign('virtualstory', strtr(_MA_PEDIGREE_VIRUTALSTO, array('[mother]' => $pedigree->getConfig('mother'), '[father]' => $pedigree->getConfig('father'), '[children]' => $pedigree->getConfig('children'))));
        $xoopsTpl->assign('nextaction', '<b>' . strtr(_MA_PEDIGREE_VIRT_DAM, array('[mother]' => $pedigree->getConfig('mother'))) . '</b>');

        //find father
        $query  = 'SELECT Id, NAAM FROM ' . $GLOBALS['xoopsDB']->prefix('pedigree_tree') . ' WHERE Id=' . $selsire;
        $result = $GLOBALS['xoopsDB']->query($query);
        while (false !== ($row = $GLOBALS['xoopsDB']->fetchArray($result))) {
            $vsire = stripslashes($row['NAAM']);
        }
        $xoopsTpl->assign('virtualsiretitle', strtr(_MA_PEDIGREE_VIRTUALSTIT, array('[father]' => $pedigree->getConfig('father'))));
        $xoopsTpl->assign('virtualsire', $vsire);
        break;

    case 'check':
        $pedigree = PedigreePedigree::getInstance(false);
/*
        //get module configuration
        $moduleHandler = xoops_getHandler('module');
        $module        = $moduleHandler->getByDirname('pedigree');
        $configHandler = xoops_getHandler('config');
        $moduleConfig  = $configHandler->getConfigsByCat(0, $module->getVar('mid'));
*/
        $selsire = XoopsRequest::getInt('selsire', 0, 'GET');
        $seldam  = XoopsRequest::getInt('seldam', 0, 'GET');
/*
        if (isset($_GET['selsire'])) {
            $selsire = $_GET['selsire'];
        }
        if (isset($_GET['seldam'])) {
            $seldam = $_GET['seldam'];
        }
*/
        $xoopsTpl->assign('virtualtitle', _MA_PEDIGREE_VIRUTALTIT);
        $xoopsTpl->assign('virtualstory', strtr(_MA_PEDIGREE_VIRUTALSTO, array('[mother]' => $pedigree->getConfig('mother'), '[father]' => $pedigree->getConfig('father'), '[children]' => $pedigree->getConfig('children'))));
        //find father
        $query  = 'SELECT Id, NAAM FROM ' . $GLOBALS['xoopsDB']->prefix('pedigree_tree') . ' WHERE Id=' . $selsire;
        $result = $GLOBALS['xoopsDB']->query($query);
        while (false !== ($row = $GLOBALS['xoopsDB']->fetchArray($result))) {
            $vsire = stripslashes($row['NAAM']);
        }
        $xoopsTpl->assign('virtualsiretitle', strtr(_MA_PEDIGREE_VIRTUALSTIT, array('[father]' => $pedigree->getConfig('father'))));
        $xoopsTpl->assign('virtualsire', $vsire);
        //find mother
        $query  = 'SELECT Id, NAAM FROM ' . $GLOBALS['xoopsDB']->prefix('pedigree_tree') . ' WHERE Id=' . $seldam;
        $result = $GLOBALS['xoopsDB']->query($query);
        while (false !== ($row = $GLOBALS['xoopsDB']->fetchArray($result))) {
            $vdam = stripslashes($row['NAAM']);
        }
        $xoopsTpl->assign('virtualdamtitle', strtr(_MA_PEDIGREE_VIRTUALDTIT, array('[mother]' => $pedigree->getConfig('mother'))));
        $xoopsTpl->assign('virtualdam', $vdam);

        $xoopsTpl->assign('form', "<a href=\"coi.php?s=" . $selsire . '&d=' . $seldam . "&dogid=&detail=1\">" . _MA_PEDIGREE_VIRTUALBUT . '</a>');
        break;
}
//footer
include $GLOBALS['xoops']->path('footer.php');
