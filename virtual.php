<?php
// -------------------------------------------------------------------------

use Xmf\Request;
use XoopsModules\Pedigree;
use XoopsModules\Pedigree\Helper;

//require_once \dirname(__DIR__, 2) . '/mainfile.php';
require_once __DIR__ . '/header.php';

//$moduleDirName = basename(__DIR__);
xoops_loadLanguage('main', $moduleDirName);
xoops_load('Pedigree\Animal', $moduleDirName);

// Include any common code for this module.
require_once XOOPS_ROOT_PATH . '/modules/' . $moduleDirName . '/include/common.php';

$GLOBALS['xoopsOption']['template_main'] = 'pedigree_virtual.tpl';
require_once $GLOBALS['xoops']->path('/header.php');

$xoopsTpl->assign('page_title', 'Pedigree database - Virtual Mating');

//create function variable from url
//if (\Xmf\Request::hasVar('f', 'GET')) {
//    $f = $_GET['f'];
//}
//if (!isset($f)) {
$f = Request::getString('f', '', 'GET');

if (empty($f)) {
    virt();
} elseif ('dam' === $f) {
    dam();
} elseif ('check' === $f) {
    check();
}

function virt()
{
    require_once __DIR__ . '/include/common.php';
    global $xoopsTpl;
    $moduleDirName = basename(__DIR__);
    //get module configuration
    /** @var \XoopsModuleHandler $moduleHandler */
    $moduleHandler = xoops_getHandler('module');
    $module        = $moduleHandler->getByDirname($moduleDirName);
    /** @var \XoopsConfigHandler $configHandler */
    $configHandler = xoops_getHandler('config');
    $moduleConfig  = $configHandler->getConfigsByCat(0, $module->getVar('mid'));
    /** @var Pedigree\Helper $helper */
    $helper = Helper::getInstance();

    //    if (\Xmf\Request::hasVar('st', 'GET')) {
    //        $st = $_GET['st'];
    //    } else {
    //        $st = 0;
    //    }
    //    if (\Xmf\Request::hasVar('l', 'GET')) {
    //        $l = $_GET['l'];
    //    } else {
    //        $l = 'A';
    //    }
    $st = Request::getInt('st', 0, 'GET');
    $l  = Request::getString('l', 'A', 'GET');

    $xoopsTpl->assign('sire', '1');
    //create list of males dog to select from
    $perPage = $helper->getConfig('perpage');
    //count total number of dogs
    $numDog = 'SELECT COUNT(d.id) FROM '
              . $GLOBALS['xoopsDB']->prefix('pedigree_registry')
              . ' d LEFT JOIN '
              . $GLOBALS['xoopsDB']->prefix('pedigree_registry')
              . ' m ON m.id = d.mother LEFT JOIN '
              . $GLOBALS['xoopsDB']->prefix('pedigree_registry')
              //            . " f ON f.id = d.father WHERE d.roft = '0' and d.mother != '0' and d.father != '0' and m.mother != '0' and m.father != '0' and f.mother != '0' and f.father != '0' and d.pname LIKE '" . $l . "%'";
              . " f ON f.id = d.father WHERE d.roft = '0' AND d.mother != '0' AND d.father != '0' AND m.mother != '0' AND m.father != '0' AND f.mother != '0' AND f.father != '0' AND d.pname LIKE '"
              . $GLOBALS['xoopsDB']->escape($l)
              . "%'";
    $numRes = $GLOBALS['xoopsDB']->query($numDog);
    //total number of dogs the query will find
    [$numResults] = $GLOBALS['xoopsDB']->fetchRow($numRes);
    //total number of pages
    $numPages = floor($numResults / $perPage) + 1;
    if (($numPages * $perPage) == ($numResults + $perPage)) {
        --$numPages;
    }
    //find current page
    $currentPage = floor($st / $perPage) + 1;
    //create alphabet
    $pages = '';
    for ($i = 65; $i <= 90; ++$i) {
        if ($l == chr($i)) {
            $pages .= '<b><a href="virtual.php?r=1&st=0&l=' . chr($i) . '">' . chr($i) . '</a></b>&nbsp;';
        } else {
            $pages .= '<a href="virtual.php?r=1&st=0&l=' . chr($i) . '">' . chr($i) . '</a>&nbsp;';
        }
    }
    $pages .= '-&nbsp;';
    $pages .= '<a href="virtual.php?r=1&st=0&l=Ã…">Ã…</a>&nbsp;';
    $pages .= '<a href="virtual.php?r=1&st=0&l=Ã–">Ã–</a>&nbsp;';
    $pages .= '<br>';
    //create previous button
    if (($numPages > 1) && ($currentPage > 1)) {
        $pages .= '<a href="virtual.php?r=1&&l=' . $l . 'st=' . ($st - $perPage) . '">' . _MA_PEDIGREE_PREVIOUS . '</a>&nbsp;&nbsp';
    }
    //create numbers
    $xLimit = $numPages + 1;
    for ($x = 1; $x < $xLimit; ++$x) {
        //create line break after 20 number
        if (0 == ($x % 20)) {
            $pages .= '<br>';
        }
        if ($x != $currentPage) {
            $pages .= '<a href="virtual.php?r=1&l=' . $l . '&st=' . ($perPage * ($x - 1)) . '">' . $x . '</a>&nbsp;&nbsp;';
        } else {
            $pages .= $x . '&nbsp;&nbsp';
        }
    }
    //create next button
    if ($numPages > 1) {
        if ($currentPage < $numPages) {
            $pages .= '<a href="virtual.php?r=1&l=' . $l . '&st=' . ($st + $perPage) . '">' . _MA_PEDIGREE_NEXT . '</a>&nbsp;&nbsp';
        }
    }

    //query
    $sql    = 'SELECT d.*, d.id AS d_id, d.pname AS d_pname FROM '
              . $GLOBALS['xoopsDB']->prefix('pedigree_registry')
              . ' d LEFT JOIN '
              . $GLOBALS['xoopsDB']->prefix('pedigree_registry')
              . ' m ON m.id = d.mother LEFT JOIN '
              . $GLOBALS['xoopsDB']->prefix('pedigree_registry')
              . " f ON f.id = d.father WHERE d.roft = '0' AND d.mother != '0' AND d.father != '0' AND m.mother != '0' AND m.father != '0' AND f.mother != '0' AND f.father != '0' AND d.pname LIKE '"
              . $l
              . "%' ORDER BY d.pname LIMIT "
              . $st
              . ', '
              . $perPage;
    $result = $GLOBALS['xoopsDB']->query($sql);

    $animal = new Pedigree\Animal();
    //test to find out how many user fields there are...
    $fields       = $animal->getNumOfFields();
    $numofcolumns = 1;
    $columns[]    = ['columnname' => 'Name'];
    foreach ($fields as $i => $iValue) {
        $userField   = new Pedigree\Field($fields[$i], $animal->getConfig());
        $fieldType   = '\XoopsModules\Pedigree\\' . $userField->getSetting('fieldtype');
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
                'columnname'   => $fieldObject->fieldname,
                'columnnumber' => $userField->getId(),
                'lookupval'    => $lookupValues,
            ];
            ++$numofcolumns;
            unset($lookupValues);
        }
    }

    while (false !== ($row = $GLOBALS['xoopsDB']->fetchArray($result))) {
        //create picture information
        if ('' != $row['foto']) {
            $camera = ' <img src="' . PEDIGREE_UPLOAD_URL . '/images/camera.png">';
        } else {
            $camera = '';
        }
        $name = stripslashes($row['d_pname']) . $camera;
        //empty array
        unset($columnvalue);
        //fill array
        for ($i = 1; $i < $numofcolumns; ++$i) {
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
            'id'          => $row['d_id'],
            'name'        => $name,
            'gender'      => '<img src="assets/images/male.gif">',
            'link'        => '<a href="virtual.php?f=dam&selsire=' . $row['d_id'] . '">' . $name . '</a>',
            'colour'      => '',
            'number'      => '',
            'usercolumns' => isset($columnvalue) ? $columnvalue : 0,
        ];
    }

    //add data to smarty template
    //assign dog
    if (isset($dogs)) {
        $xoopsTpl->assign('dogs', $dogs);
    }
    $xoopsTpl->assign('columns', $columns);
    $xoopsTpl->assign('numofcolumns', $numofcolumns);
    $xoopsTpl->assign('tsarray', Pedigree\Utility::sortTable($numofcolumns));
    $xoopsTpl->assign('nummatch', strtr(_MA_PEDIGREE_ADD_SELSIRE, ['[father]' => $helper->getConfig('father')]));
    $xoopsTpl->assign('pages', $pages);

    $xoopsTpl->assign('virtualtitle', strtr(_MA_PEDIGREE_VIRUTALTIT, ['[mother]' => $helper->getConfig('mother')]));
    $xoopsTpl->assign(
        'virtualstory',
        strtr(
            _MA_PEDIGREE_VIRUTALSTO,
            [
                '[mother]'   => $helper->getConfig('mother'),
                '[father]'   => $helper->getConfig('father'),
                '[children]' => $helper->getConfig('children'),
            ]
        )
    );
    $xoopsTpl->assign('nextaction', '<b>' . strtr(_MA_PEDIGREE_VIRT_SIRE, ['[father]' => $helper->getConfig('father')]) . '</b>');
    //    break;

    //mb =========== FATHER LETTERS =============================
    /** @var Pedigree\Helper $helper */
    $helper = Helper::getInstance();
    $roft   = 0;
    //    $criteria     = $helper->getHandler('Tree')->getActiveCriteria($roft);
    $activeObject = 'Tree';
    $name         = 'pname';
    $number1      = '1';
    $number2      = '0';
    //    $link         = "virtual.php?r={$number1}&st={$number2}&l=";
    $link = "virtual.php?r={$number1}&st={$number2}&l=";

    //    http://localhost/257belgi/modules/pedigree/virtual.php?f=dam&selsire=35277

    $link2 = '';

    $criteria = $helper->getHandler('Tree')->getActiveCriteria($roft);
    //    $criteria->setGroupby('UPPER(LEFT(' . $name . ',1))');

    $fatherArray['letters'] = Pedigree\Utility::lettersChoice($helper, $activeObject, $criteria, $name, $link, $link2);
    //$catarray['toolbar']          = pedigree_toolbar();
    $xoopsTpl->assign('fatherArray', $fatherArray);
    //mb ========================================
}

function dam()
{
    global $xoopsTpl;
    $pages         = '';
    $moduleDirName = basename(__DIR__);
    /** @var \XoopsModules\Pedigree\Helper $helper */
    $helper = Helper::getInstance();

    //get module configuration
    /** @var \XoopsModuleHandler $moduleHandler */
    $moduleHandler = xoops_getHandler('module');
    $module        = $moduleHandler->getByDirname($moduleDirName);
    /** @var \XoopsConfigHandler $configHandler */
    $configHandler = xoops_getHandler('config');
    $moduleConfig  = $configHandler->getConfigsByCat(0, $module->getVar('mid'));

    $st = Request::getString('st', 0, 'GET');
    $l  = Request::getString('l', 'A', 'GET');

    //    if (\Xmf\Request::hasVar('st', 'GET')) {
    //        $st = $_GET['st'];
    //    } else {
    //        $st = 0;
    //    }
    //    if (\Xmf\Request::hasVar('l', 'GET')) {
    //        $l = $_GET['l'];
    //    } else {
    //        $l = 'A';
    //    }

    $selsire = $_GET['selsire'];

    $xoopsTpl->assign('sire', '1');
    //create list of males dog to select from
    $perPage = $helper->getConfig('perpage');
    //count total number of dogs
    $numDog = 'SELECT COUNT(d.id) FROM '
              . $GLOBALS['xoopsDB']->prefix('pedigree_registry')
              . ' d LEFT JOIN '
              . $GLOBALS['xoopsDB']->prefix('pedigree_registry')
              . ' m ON m.id = d.mother LEFT JOIN '
              . $GLOBALS['xoopsDB']->prefix('pedigree_registry')
              //  . " f ON f.id = d.father WHERE d.roft = '1' and d.mother != '0' and d.father != '0' and m.mother != '0' and m.father != '0' and f.mother != '0' and f.father != '0' and d.pname LIKE '" . $l . "%'";
              . " f ON f.id = d.father WHERE d.roft = '1' AND d.mother != '0' AND d.father != '0' AND m.mother != '0' AND m.father != '0' AND f.mother != '0' AND f.father != '0' AND d.pname LIKE '"
              . $GLOBALS['xoopsDB']->escape($l)
              . "%'";

    $numRes = $GLOBALS['xoopsDB']->query($numDog);
    //total number of dogs the query will find
    [$numResults] = $GLOBALS['xoopsDB']->fetchRow($numRes);
    //total number of pages
    $numPages = floor($numResults / $perPage) + 1;
    if (($numPages * $perPage) == ($numResults + $perPage)) {
        --$numPages;
    }
    //find current page
    $currentPage = floor($st / $perPage) + 1;
    //create the alphabet
    $pages .= '<a href="virtual.php?f=dam&selsire=' . $selsire . '&st=0&l=a">A</a>&nbsp;';
    $pages .= '<a href="virtual.php?f=dam&selsire=' . $selsire . '&st=0&l=b">B</a>&nbsp;';
    $pages .= '<a href="virtual.php?f=dam&selsire=' . $selsire . '&st=0&l=c">C</a>&nbsp;';
    $pages .= '<a href="virtual.php?f=dam&selsire=' . $selsire . '&st=0&l=d">D</a>&nbsp;';
    $pages .= '<a href="virtual.php?f=dam&selsire=' . $selsire . '&st=0&l=e">E</a>&nbsp;';
    $pages .= '<a href="virtual.php?f=dam&selsire=' . $selsire . '&st=0&l=f">F</a>&nbsp;';
    $pages .= '<a href="virtual.php?f=dam&selsire=' . $selsire . '&st=0&l=g">G</a>&nbsp;';
    $pages .= '<a href="virtual.php?f=dam&selsire=' . $selsire . '&st=0&l=h">H</a>&nbsp;';
    $pages .= '<a href="virtual.php?f=dam&selsire=' . $selsire . '&st=0&l=i">I</a>&nbsp;';
    $pages .= '<a href="virtual.php?f=dam&selsire=' . $selsire . '&st=0&l=j">J</a>&nbsp;';
    $pages .= '<a href="virtual.php?f=dam&selsire=' . $selsire . '&st=0&l=k">K</a>&nbsp;';
    $pages .= '<a href="virtual.php?f=dam&selsire=' . $selsire . '&st=0&l=l">L</a>&nbsp;';
    $pages .= '<a href="virtual.php?f=dam&selsire=' . $selsire . '&st=0&l=m">M</a>&nbsp;';
    $pages .= '<a href="virtual.php?f=dam&selsire=' . $selsire . '&st=0&l=n">N</a>&nbsp;';
    $pages .= '<a href="virtual.php?f=dam&selsire=' . $selsire . '&st=0&l=o">O</a>&nbsp;';
    $pages .= '<a href="virtual.php?f=dam&selsire=' . $selsire . '&st=0&l=p">P</a>&nbsp;';
    $pages .= '<a href="virtual.php?f=dam&selsire=' . $selsire . '&st=0&l=q">Q</a>&nbsp;';
    $pages .= '<a href="virtual.php?f=dam&selsire=' . $selsire . '&st=0&l=r">R</a>&nbsp;';
    $pages .= '<a href="virtual.php?f=dam&selsire=' . $selsire . '&st=0&l=s">S</a>&nbsp;';
    $pages .= '<a href="virtual.php?f=dam&selsire=' . $selsire . '&st=0&l=t">T</a>&nbsp;';
    $pages .= '<a href="virtual.php?f=dam&selsire=' . $selsire . '&st=0&l=u">U</a>&nbsp;';
    $pages .= '<a href="virtual.php?f=dam&selsire=' . $selsire . '&st=0&l=v">V</a>&nbsp;';
    $pages .= '<a href="virtual.php?f=dam&selsire=' . $selsire . '&st=0&l=w">W</a>&nbsp;';
    $pages .= '<a href="virtual.php?f=dam&selsire=' . $selsire . '&st=0&l=x">X</a>&nbsp;';
    $pages .= '<a href="virtual.php?f=dam&selsire=' . $selsire . '&st=0&l=y">Y</a>&nbsp;';
    $pages .= '<a href="virtual.php?f=dam&selsire=' . $selsire . '&st=0&l=z">Z</a>&nbsp;';
    $pages .= '-&nbsp;';
    $pages .= '<a href="virtual.php?f=dam&selsire=' . $selsire . '&st=0&l=Ã…">Ã…</a>&nbsp;';
    $pages .= '<a href="virtual.php?f=dam&selsire=' . $selsire . '&st=0&l=Ã–">Ã–</a>&nbsp;';
    //create linebreak
    $pages .= '<br>';
    //create previous button
    if ($numPages > 1) {
        if ($currentPage > 1) {
            $pages .= '<a href="virtual.php?f=dam&selsire=' . $selsire . '&l=' . $l . '&st=' . ($st - $perPage) . '">' . _MA_PEDIGREE_PREVIOUS . '</a>&nbsp;&nbsp';
        }
    }
    //create numbers
    for ($x = 1; $x < ($numPages + 1); ++$x) {
        //create line break after 20 number
        if (0 == ($x % 20)) {
            $pages .= '<br>';
        }
        if ($x != $currentPage) {
            $pages .= '<a href="virtual.php?f=dam&selsire=' . $selsire . '&l=' . $l . '&st=' . ($perPage * ($x - 1)) . '">' . $x . '</a>&nbsp;&nbsp;';
        } else {
            $pages .= $x . '&nbsp;&nbsp';
        }
    }
    //create next button
    if ($numPages > 1) {
        if ($currentPage < $numPages) {
            $pages .= '<a href="virtual.php?f=dam&selsire=' . $selsire . '&l=' . $l . '&st=' . ($st + $perPage) . '">' . _MA_PEDIGREE_NEXT . '</a>&nbsp;&nbsp';
        }
    }

    //query
    $sql    = 'SELECT d.*, d.id AS d_id, d.pname AS d_pname FROM '
              . $GLOBALS['xoopsDB']->prefix('pedigree_registry')
              . ' d LEFT JOIN '
              . $GLOBALS['xoopsDB']->prefix('pedigree_registry')
              . ' m ON m.id = d.mother LEFT JOIN '
              . $GLOBALS['xoopsDB']->prefix('pedigree_registry')
              . " f ON f.id = d.father WHERE d.roft = '1' AND d.mother != '0' AND d.father != '0' AND m.mother != '0' AND m.father != '0' AND f.mother != '0' AND f.father != '0' AND d.pname LIKE '"
              . $l
              . "%' ORDER BY d.pname LIMIT "
              . $st
              . ', '
              . $perPage;
    $result = $GLOBALS['xoopsDB']->query($sql);

    $animal = new Pedigree\Animal();
    //test to find out how many user fields there are...
    $fields       = $animal->getNumOfFields();
    $numofcolumns = 1;
    $columns[]    = ['columnname' => 'Name'];
    foreach ($fields as $i => $iValue) {
        $userField   = new Pedigree\Field($fields[$i], $animal->getConfig());
        $fieldType   = '\XoopsModules\Pedigree\\' . $userField->getSetting('fieldtype');
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
                'columnname'   => $fieldObject->fieldname,
                'columnnumber' => $userField->getId(),
                'lookupval'    => $lookupValues,
            ];
            ++$numofcolumns;
            unset($lookupValues);
        }
    }

    while (false !== ($row = $GLOBALS['xoopsDB']->fetchArray($result))) {
        //create picture information
        if ('' != $row['foto']) {
            $camera = ' <img src="' . PEDIGREE_UPLOAD_URL . '/images/camera.png">';
        } else {
            $camera = '';
        }
        $name = stripslashes($row['d_pname']) . $camera;
        //empty array
        unset($columnvalue);
        //fill array
        for ($i = 1; $i < $numofcolumns; ++$i) {
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
            'id'          => $row['d_id'],
            'name'        => $name,
            'gender'      => '<img src="assets/images/female.gif">',
            'link'        => '<a href="virtual.php?f=check&selsire=' . $selsire . '&seldam=' . $row['d_id'] . '">' . $name . '</a>',
            'colour'      => '',
            'number'      => '',
            'usercolumns' => isset($columnvalue) ? $columnvalue : 0,
        ];
    }

    //add data to smarty template
    //assign dog
    $xoopsTpl->assign('dogs', $dogs);
    $xoopsTpl->assign('columns', $columns);
    $xoopsTpl->assign('numofcolumns', $numofcolumns);
    $xoopsTpl->assign('tsarray', Pedigree\Utility::sortTable($numofcolumns));
    $xoopsTpl->assign('nummatch', strtr(_MA_PEDIGREE_ADD_SELDAM, ['[mother]' => $helper->getConfig('mother')]));
    $xoopsTpl->assign('pages', $pages);

    $xoopsTpl->assign('virtualtitle', _MA_PEDIGREE_VIRUTALTIT);
    $xoopsTpl->assign(
        'virtualstory',
        strtr(
            _MA_PEDIGREE_VIRUTALSTO,
            [
                '[mother]'   => $helper->getConfig('mother'),
                '[father]'   => $helper->getConfig('father'),
                '[children]' => $helper->getConfig('children'),
            ]
        )
    );
    $xoopsTpl->assign('nextaction', '<b>' . strtr(_MA_PEDIGREE_VIRT_DAM, ['[mother]' => $helper->getConfig('mother')]) . '</b>');

    //find father
    $query  = 'SELECT id, pname FROM ' . $GLOBALS['xoopsDB']->prefix('pedigree_registry') . ' WHERE id=' . $selsire;
    $result = $GLOBALS['xoopsDB']->query($query);
    while (false !== ($row = $GLOBALS['xoopsDB']->fetchArray($result))) {
        $vsire = stripslashes($row['pname']);
    }
    $xoopsTpl->assign('virtualsiretitle', strtr(_MA_PEDIGREE_VIRTUALSTIT, ['[father]' => $moduleConfig['father']]));
    $xoopsTpl->assign('virtualsire', $vsire);

    //mb ========= MOTHER LETTERS===============================
    /** @var Pedigree\Helper $helper */
    $helper = Helper::getInstance();
    $roft   = 1;
    //    $criteria     = $helper->getHandler('Tree')->getActiveCriteria($roft);
    $activeObject = 'Tree';
    $name         = 'pname';
    $number1      = '1';
    $number2      = '0';
    $link         = "virtual.php?r={$number1}&st={$number2}&l=";

    $criteria = $helper->getHandler('Tree')->getActiveCriteria($roft);
    //    $criteria->setGroupby('UPPER(LEFT(' . $name . ',1))');

    $motherArray['letters'] = Pedigree\Utility::lettersChoice($helper, $activeObject, $criteria, $name, $link);
    //$catarray['toolbar']          = pedigree_toolbar();
    $xoopsTpl->assign('motherArray', $motherArray);
    //mb ========================================
}

function check()
{
    global $xoopsTpl;
    $moduleDirName = basename(__DIR__);
    //get module configuration
    /** @var \XoopsModuleHandler $moduleHandler */
    $moduleHandler = xoops_getHandler('module');
    /** @var \XoopsModules\Pedigree\Helper $helper */
    $helper = Helper::getInstance();

    $module = $moduleHandler->getByDirname($moduleDirName);
    /** @var \XoopsConfigHandler $configHandler */
    $configHandler = xoops_getHandler('config');
    $moduleConfig  = $configHandler->getConfigsByCat(0, $module->getVar('mid'));

    $selsire = Request::getInt('selsire', 0, 'GET');
    $seldam  = Request::getInt('seldam', 0, 'GET');
    /*
    if (\Xmf\Request::hasVar('selsire', 'GET')) {
        $selsire = $_GET['selsire'];
    }
    if (\Xmf\Request::hasVar('seldam', 'GET')) {
        $seldam = $_GET['seldam'];
    }
    */

    $xoopsTpl->assign('virtualtitle', _MA_PEDIGREE_VIRUTALTIT);
    $xoopsTpl->assign(
        'virtualstory',
        strtr(
            _MA_PEDIGREE_VIRUTALSTO,
            [
                '[mother]'   => $helper->getConfig('mother'),
                '[father]'   => $helper->getConfig('father'),
                '[children]' => $helper->getConfig('children'),
            ]
        )
    );
    //find father
    $query  = 'SELECT id, pname FROM ' . $GLOBALS['xoopsDB']->prefix('pedigree_registry') . ' WHERE id=' . $selsire;
    $result = $GLOBALS['xoopsDB']->query($query);
    while (false !== ($row = $GLOBALS['xoopsDB']->fetchArray($result))) {
        $vsire = stripslashes($row['pname']);
    }
    $xoopsTpl->assign('virtualsiretitle', strtr(_MA_PEDIGREE_VIRTUALSTIT, ['[father]' => $helper->getConfig('father')]));
    $xoopsTpl->assign('virtualsire', $vsire);
    //find mother
    $query  = 'SELECT id, pname FROM ' . $GLOBALS['xoopsDB']->prefix('pedigree_registry') . ' WHERE id=' . $seldam;
    $result = $GLOBALS['xoopsDB']->query($query);
    while (false !== ($row = $GLOBALS['xoopsDB']->fetchArray($result))) {
        $vdam = stripslashes($row['pname']);
    }
    $xoopsTpl->assign('virtualdamtitle', strtr(_MA_PEDIGREE_VIRTUALDTIT, ['[mother]' => $helper->getConfig('mother')]));
    $xoopsTpl->assign('virtualdam', $vdam);

    $xoopsTpl->assign('form', '<a href="coi.php?s=' . $selsire . '&d=' . $seldam . '&dogid=&detail=1">' . _MA_PEDIGREE_VIRTUALBUT . '</a>');
}

//footer
require_once $GLOBALS['xoops']->path('footer.php');
