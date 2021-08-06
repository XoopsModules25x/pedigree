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

//require_once  \dirname(__DIR__, 2) . '/mainfile.php';
require_once __DIR__ . '/header.php';
$helper->loadLanguage('main');

// Include any common code for this module.
require_once $helper->path('include/common.php');

$GLOBALS['xoopsOption']['template_main'] = 'pedigree_addlitter.tpl';
require XOOPS_ROOT_PATH . '/header.php';
$GLOBALS['xoopsTpl']->assign('page_title', _MA_PEDIGREE_ADD_LITTER_PAGETITLE);

//check for access
if (empty($GLOBALS['xoopsUser']) || !($GLOBALS['xoopsUser'] instanceof \XoopsUser) || $GLOBALS['xoopsUser']->isGuest()) {
    redirect_header('javascript:history.go(-1)', 3, _NOPERM . '<br>' . _MA_PEDIGREE_REGIST);
}

$f = Request::getCmd('f', 'addlitter', 'GET');
switch ($f) {
    case 'addlitter':
    default:
        //create xoopsform
        require XOOPS_ROOT_PATH . '/class/xoopsformloader.php';
        $searchform = new \XoopsThemeForm(strtr(_MA_PEDIGREE_ADD_LITTER, ['[litter]' => $helper->getConfig('litter')]), 'searchform', $helper->url('add_litter.php?f=sire'), 'post');
        $searchform->addElement(new \XoopsFormHiddenToken($name = 'XOOPS_TOKEN_REQUEST', $timeout = Constants::TOKEN_TIMEOUT));
        //create random value
        $random = (mt_rand() % 10000);
        $searchform->addElement(new \XoopsFormHidden('random', $random));
        $searchform->addElement(new \XoopsFormHidden('userid', $GLOBALS['xoopsUser']->getVar('uid'))); //get user's ID
        $animal = new Pedigree\Animal(); //create animal object
        $fields = $animal->getNumOfFields();//test to find out how many user fields there are...

        //create form contents
        for ($count = 1; $count < 11; ++$count) {
            //name
            $searchform->addElement(new \XoopsFormLabel($count . '.', strtr(_MA_PEDIGREE_KITT_NAME . $count . '.', ['[animalType]' => $helper->getConfig('animalType')])));
            $textbox[$count] = new \XoopsFormText('<b>' . _MA_PEDIGREE_FLD_NAME . '</b>', 'name' . $count, $size = 50, $maxsize = 50, '');
            $searchform->addElement($textbox[$count]);
            //gender
            $gender_radio[$count] = new \XoopsFormRadio('<b>' . _MA_PEDIGREE_FLD_GEND . '</b>', 'roft' . $count, $value = '0');
            $gender_radio[$count]->addOptionArray([
                                                      Constants::MALE   => strtr(_MA_PEDIGREE_FLD_MALE, ['[male]' => $helper->getConfig('male')]),
                                                      Constants::FEMALE => strtr(_MA_PEDIGREE_FLD_FEMA, ['[female]' => $helper->getConfig('female')]),
                                                  ]);
            $searchform->addElement($gender_radio[$count]);
            //add userfields
            $fieldCount = count($fields);
            for ($i = 0; $i < $fieldCount; ++$i) {
                $userField   = new Pedigree\Field($fields[$i], $animal->getConfig());
                $fieldType   = $userField->getSetting('fieldtype');
                $fieldObject = new $fieldType($userField, $animal);
                if ($userField->isActive() && '1' == $userField->getSetting('Litter') && !$userField->isLocked()) {
                    $newEntry[$count][$i] = $fieldObject->newField($count);
                    $searchform->addElement($newEntry[$count][$i]);
                }
            }
            //add empty place holder as divider
            $searchform->addElement(new \XoopsFormLabel('&nbsp;', ''));
        }

        $searchform->addElement(new \XoopsFormLabel(_MA_PEDIGREE_ADD_DATA, _MA_PEDIGREE_DATA_INFO . $helper->getConfig('litter') . '.</h2>'));
        //add userfields that are not shown in the litter
        $fieldCount = count($fields);
        for ($i = 0, $fieldCount; $i < $fieldCount; ++$i) {
            $userField   = new Pedigree\Field($fields[$i], $animal->getConfig());
            $fieldType   = $userField->getSetting('fieldtype');
            $fieldObject = new $fieldType($userField, $animal);
            if ($userField->isActive() && $userField->generalLitter() && !$userField->isLocked()) {
                //add the "-" character to the beginning of the fieldname !!!
                $newEntry[$i] = $fieldObject->newField('-');
                $searchform->addElement($newEntry[$i]);
            }
        }
        //add the breeder to the list for the entire litter
        //no need to add the owner here because they will be different for each animal in the litter.
        if ('1' == $helper->getConfig('ownerbreeder')) {
            //breeder
            $ownerHandler = $helper->getHandler('Owner');
            $criteria     = new \Criteria();
            $criteria->setSort('lastname, firstname');
            $ownerObjArray = $ownerHandler->getAll($criteria);
            $breeder       = new \XoopsFormSelect(_MA_PEDIGREE_FLD_BREE, 'id_breeder', $value = '', $size = 1, $multiple = false);
            $breeder->addOption(0, $name = _MA_PEDIGREE_UNKNOWN);
            foreach ($ownerObjArray as $oObj) {
                $breeder->addOption($oObj->getVar('id'), $name = $oObj->getVar('lastname') . ', ' . $oObj->getVar('firstname'));
            }
            /*
            $queryfok = 'SELECT id, firstname, lastname FROM ' . $GLOBALS['xoopsDB']->prefix('pedigree_owner') . ' ORDER BY `lastname`;
            $resfok = $GLOBALS['xoopsDB']->query($queryfok);
            $breeder->addOption(0, $name = _MA_PEDIGREE_UNKNOWN);
            while (false !== ($rowfok = $GLOBALS['xoopsDB']->fetchArray($resfok))) {
                $breeder->addOption($rowfok['id'], $name = $rowfok['lastname'] . ', ' . $rowfok['firstname']);
            }
            */
            $searchform->addElement($breeder);
        }

        //submit button
        $searchform->addElement(new \XoopsFormButton('', 'submit', strtr(_MA_PEDIGREE_ADD_SIRE, ['[father]' => $helper->getConfig('father', '')]), 'submit'));
        //send to template
        $searchform->assign($GLOBALS['xoopsTpl']);
        break;
    case 'sire':
        //debug option !
        //print_r($_POST); die();
        //    $userid = $_POST['userid'];
        //    if (empty($random)) {
        //        $random = $_POST['random'];
        //    }
        //    if (isset($_GET['random'])) {
        //        $random = $_GET['random'];
        //    }
        //    if (empty($st)) {
        //        $st = 0;
        //    }
        //    if (isset($_GET['st'])) {
        //        $st = $_GET['st'];
        //    }
        $userid     = Request::getInt('userid', 0, 'POST');
        $random     = Request::getInt('random', 0);
        $st         = Request::getInt('st', 0);
        $userfields = '';
        $name       = '';
        $roft       = '';
        for ($count = 1; $count < 11; ++$count) {
            $namelitter = 'name' . $count;
            $roftlitter = 'roft' . $count;
            //check for an empty name
            if ('' !== $_POST[$namelitter]) {
                $name .= ':' . Request::getString('namelitter', '', 'POST');
                $roft .= ':' . Request::getString('roftlitter', '', 'POST');
            } else {
                if (1 == $count) {
                    $helper->redirect('add_litter.php', 3, _MA_PEDIGREE_ADD_NAMEPLZ);
                }
            }
        }

        $id_breeder = Request::getInt('id_breeder', 0, 'POST');

        //make the redirect
        if (!isset($_GET['r'])) {
            $animal = new Pedigree\Animal();
            $fields = $animal->getNumOfFields();
            sort($fields);
            foreach ($fields as $i => $iValue) {
                $userField   = new Pedigree\Field($fields[$i], $animal->getConfig());
                $fieldType   = $userField->getSetting('fieldtype');
                $fieldObject = new $fieldType($userField, $animal);
                $defvalue    = $fieldObject->defaultvalue;
                //empty string to house the different values for this userfield
                $withinfield = '';
                for ($count = 1; $count < 11; ++$count) {
                    if ('' !== $_POST['name' . $count]) {
                        //@todo need to sanitize these $_POST values
                        if (isset($_POST[$count . 'user' . $iValue])) {
                            //debug option
                            //echo $count.'user'.$fields[$i]."=".$_POST[$count.'user'.$fields[$i]]."<br>";
                            $withinfield .= ':' . $_POST[$count . 'user' . $iValue];
                        } else {
                            if ($userField->isActive() && $userField->generalLitter() && !$userField->isLocked()) {
                                //use $_POST value if this is a general litter field
                                $withinfield .= ':' . $_POST['-user' . $iValue];
                            } else {
                                //create $withinfield for fields not added to the litter
                                $withinfield .= ':' . $defvalue;
                            }
                        }
                    }
                }
                //debug option
                //echo "user".$fields[$i]." - ".$withinfield."<br>";
                $user{$fields[$i]} = $withinfield;
            }
            //insert into pedigree_temp
            //      $query = 'INSERT INTO ' . $GLOBALS['xoopsDB']->prefix('pedigree_temp') . " VALUES ('" . $random . "','" . Pedigree\Utility::unHtmlEntities($name) . "','0','" . $id_breeder . "','" . $userid . "','" . $roft . "','','','', ''";
            $query = 'INSERT INTO ' . $GLOBALS['xoopsDB']->prefix('pedigree_temp') . " VALUES ('" . $random . "','" . Pedigree\Utility::unHtmlEntities($name) . "','0','" . Request::getInt('id_breeder', 0, 'POST') . "','" . $userid . "','" . $roft . "','','','', ''";
            foreach ($fields as $i => $iValue) {
                $userField   = new Pedigree\Field($fields[$i], $animal->getConfig());
                $fieldType   = $userField->getSetting('fieldtype');
                $fieldObject = new $fieldType($userField, $animal);
                //do we only need to create a query for active fields ?
                $query .= ",'" . $user{$fields[$i]} . "'";
            }
            $query .= ')';
            //debug options
            //echo $query."<br>"; die();
            $GLOBALS['xoopsDB']->query($query);
            $helper->redirect('add_litter.php?f=sire&random=' . $random . '&st=' . $st . '&r=1&l=a', 1, strtr(_MA_PEDIGREE_ADD_SIREPLZ, ['[father]' => $helper->getConfig('father', '')]));
        }
        //@todo refactor to allow for language other than english
        //find letter on which to start else set to 'a'
        $l = Request::getWord('l', 'A', 'GET');

        //assign 'sire' to the template
        $GLOBALS['xoopsTpl']->assign('sire', '1');

        //create list of males dog to select from
        $perPage = $helper->getConfig('perpage', Constants::DEFAULT_PER_PAGE);
        $perPage = (int)$perPage > 0 ? (int)$perPage : Constants::DEFAULT_PER_PAGE; // default if invalid number in module param
        //count total number of dogs
        $numDog = 'SELECT id FROM ' . $GLOBALS['xoopsDB']->prefix('pedigree_registry') . " WHERE roft='0' AND pname LIKE '" . $l . "%'";
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
        //create alphabet
        $pages = '';
        //@todo need to rework this as it's only valid for English
        for ($i = 65; $i <= 90; ++$i) {
            if ($l == chr($i)) {
                $pages .= '<b><a href="' . $helper->url('add_litter.php?f=sire&r=1&r=1&random=' . $random . '&l=' . chr($i)) . '">' . chr($i) . '</a></b>&nbsp;';
            } else {
                $pages .= '<a href="' . $helper->url('add_litter.php?f=sire&r=1&r=1&random=' . $random . '&l=' . chr($i)) . '">' . chr($i) . '</a>&nbsp;';
            }
        }
        $pages .= '-&nbsp;';
        $pages .= '<a href="' . $helper->url('add_litter.php?f=sire&r=1&random=' . $random . '&l=Ã…') . '">Ã…</a>&nbsp;';
        $pages .= '<a href="' . $helper->url('add_litter.php?f=sire&r=1&random=' . $random . '&l=Ã–') . '">Ã–</a>&nbsp;';
        //create linebreak
        $pages .= '<br>';
        //create previous button
        if ($numPages > 1) {
            if ($currentPage > 1) {
                $pages .= '<a href="' . $helper->url('add_litter.php?f=sire&r=1&l=' . $l . '&random=' . $random . '&st=' . ($st - $perPage)) . '">' . _MA_PEDIGREE_PREVIOUS . '</a>&nbsp;&nbsp';
            }
        }
        //create numbers
        for ($x = 1; $x < ($numPages + 1); ++$x) {
            //create line break after 20 number
            if (0 == ($x % 20)) {
                $pages .= '<br>';
            }
            if ($x != $currentPage) {
                $pages .= '<a href="' . $helper->url('add_litter.php?f=sire&r=1&l=' . $l . '&random=' . $random . '&st=' . ($perPage * ($x - 1))) . '">' . $x . '</a>&nbsp;&nbsp;';
            } else {
                $pages .= $x . '&nbsp;&nbsp';
            }
        }
        //create next button
        if ($numPages > 1) {
            if ($currentPage < $numPages) {
                $pages .= '<a href="' . $helper->url('add_litter.php?f=sire&r=1&l=' . $l . '&random=' . $random . '&st=' . ($st + $perPage)) . '">' . _MA_PEDIGREE_NEXT . '</a>&nbsp;&nbsp';
            }
        }
        //query
        $sql = 'SELECT * FROM ' . $GLOBALS['xoopsDB']->prefix('pedigree_registry') . " WHERE roft = '0' AND pname LIKE '" . $l . "%' ORDER BY pname LIMIT " . $st . ', ' . $perPage;
        $result      = $GLOBALS['xoopsDB']->query($sql);

        $animal = new Pedigree\Animal();
        //test to find out how many user fields there are...
        $fields       = $animal->getNumOfFields();
        $numOfColumns = 1;
        $columns[]    = ['columnname' => 'Name'];
        foreach ($fields as $i => $iValue) {
            $userField   = new Pedigree\Field($fields[$i], $animal->getConfig());
            $fieldType   = $userField->getSetting('fieldtype');
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
                ++$numOfColumns;
                unset($lookupValues);
            }
        }

        $empty = array_fill(0, $numOfColumns - 1, ['value' => '']);
        /*
        $empty = []; //initialize the array
        for ($i = 1; $i < $numOfColumns; ++$i) {
            $empty[] = ['value' => ''];
        }
        */
        $dogs [] = [
            'id'          => '0',
            'name'        => '',
            'gender'      => '',
            'link'        => '<a href="add_litter.php?f=dam&random=' . $random . '&selsire=0">' . strtr(_MA_PEDIGREE_ADD_SIREUNKNOWN, ['[father]' => $helper->getConfig('father', '')]) . '</a>',
            'colour'      => '',
            'number'      => '',
            'usercolumns' => $empty,
        ];

        while (false !== ($row = $GLOBALS['xoopsDB']->fetchArray($result))) {
            //create picture information
            $camera = ('' != $row['foto']) ? " <img src=\"" . PEDIGREE_IMAGE_URL . "/camera.png\">" : '';
            $name   = stripslashes($row['pname']) . $camera;
            //empty array
            $columnvalue = []; // initialize columnvalue to empty array
            //fill array
            for ($i = 1; $i < $numOfColumns; ++$i) {
                $x = $columns[$i]['columnnumber'];
                if (is_array($columns[$i]['lookupval'])) {
                    foreach ($columns[$i]['lookupval'] as $key => $keyValue) {
                        if ($key == $row['user' . $x]) {
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
            }
            $dogs[] = [
                'id'          => $row['id'],
                'name'        => $name,
                'gender'      => "<img src=\"" . PEDIGREE_IMAGE_URL . "/male.gif\">",
                'link'        => '<a href="add_litter.php?f=dam&random=' . $random . '&selsire=' . $row['id'] . '">' . $name . '</a>',
                'colour'      => '',
                'number'      => '',
                'usercolumns' => $columnvalue,
            ];
        }

        //add data to smarty template
        //assign dog
        $GLOBALS['xoopsTpl']->assign([
                                         'dogs'         => $dogs,
                                         'columns'      => $columns,
                                         'numofcolumns' => $numOfColumns,
                                         'tsarray'      => Pedigree\Utility::sortTable($numOfColumns),
                                         'nummatch'     => strtr(_MA_PEDIGREE_ADD_SELSIRE, ['[father]' => $helper->getConfig('father', '')]),
                                         'pages'        => $pages,
                                     ]);
        break;

    case 'dam':
        if (empty($random)) {
            $random = Request::getInt('random', 0);
        }
        $st = Request::getInt('st', 0, 'GET');
        //make the redirect
        if (!isset($_GET['r'])) {
            //insert into pedigree_temp
            //      $query = 'UPDATE ' . $GLOBALS['xoopsDB']->prefix('pedigree_temp') . ' SET father =' . $_GET['selsire'] . ' WHERE id=' . $random;
            $query = 'UPDATE ' . $GLOBALS['xoopsDB']->prefix('pedigree_temp') . ' SET father =' . Request::getInt('selsire', 0, 'GET') . ' WHERE id=' . $random;
            //@todo figure out what's suppose to happen here. Query results don't go anywhere...
            $GLOBALS['xoopsDB']->queryF($query);
            $helper->redirect('add_litter.php?f=dam&random=' . $random . '&st=' . $st . '&r=1', Constants::REDIRECT_DELAY_SHORT, strtr(_MA_PEDIGREE_ADD_SIREOK, ['[mother]' => $helper->getConfig('mother', '')]));
        }
        //find letter on which to start else set to 'a'
        $l = Request::getString('l', 'a', 'GET');
        //assign sire to the template

        $GLOBALS['xoopsTpl']->assign('sire', '1');
        //create list of males dog to select from
        $perPage = (int)$helper->getConfig('perpage', Constants::DEFAULT_PER_PAGE);
        $perPage = (int)$perPage > 0 ? (int)$perPage : Constants::DEFAULT_PER_PAGE; //set default number of pages if invalid value in module preferences
        //count total number of dogs
        $numDog = 'SELECT id FROM ' . $GLOBALS['xoopsDB']->prefix('pedigree_registry') . " WHERE roft='1' AND pname LIKE '" . $GLOBALS['xoopsDB']->escape($l) . "%'";
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
        //create alphabet
        $pages = '';
        //@todo need to rework this as it's only valid for English
        for ($i = 65; $i <= 90; ++$i) {
            if ($l == chr($i)) {
                $pages .= '<b><a href="' . $helper->url('add_litter.php?f=dam&r=1&random=' . $random . '&l=' . chr($i)) . '">' . chr($i) . '</a></b>&nbsp;';
            } else {
                $pages .= '<a href="' . $helper->url('add_litter.php?f=dam&r=1&random=' . $random . '&l=' . chr($i)) . '">' . chr($i) . '</a>&nbsp;';
            }
        }
        $pages .= '-&nbsp;';
        $pages .= '<a href="' . $helper->url('add_litter.php?f=dam&r=1&random=' . $random . '&l=Ã…') . '">Ã…</a>&nbsp;';
        $pages .= '<a href="' . $helper->url('add_litter.php?f=dam&r=1&random=' . $random . '&l=Ã–') . '">Ã–</a>&nbsp;';
        //create linebreak
        $pages .= '<br>';
        //create previous button
        if ($numPages > 1) {
            if ($currentPage > 1) {
                $pages .= '<a href="' . $helper->url('add_litter.php?f=dam&r=1&l=' . $l . '&random=' . $random . '&st=' . ($st - $perPage)) . '">' . _MA_PEDIGREE_PREVIOUS . '</a>&nbsp;&nbsp';
            }
        }
        //create numbers
        for ($x = 1; $x < ($numPages + 1); ++$x) {
            //create line break after 20 number
            if (0 == ($x % 20)) {
                $pages .= '<br>';
            }
            if ($x != $currentPage) {
                $pages .= '<a href="' . $helper->url('add_litter.php?f=dam&r=1&l=' . $l . '&random=' . $random . '&st=' . ($perPage * ($x - 1))) . '">' . $x . '</a>&nbsp;&nbsp;';
            } else {
                $pages .= $x . '&nbsp;&nbsp';
            }
        }
        //create next button
        if ($numPages > 1) {
            if ($currentPage < $numPages) {
                $pages .= '<a href="' . $helper->url('add_litter.php?f=dam&r=1&l=' . $l . '&random=' . $random . '&st=' . ($st + $perPage)) . '">' . _MA_PEDIGREE_NEXT . '</a>&nbsp;&nbsp';
            }
        }
        //query
        $sql = 'SELECT * FROM ' . $GLOBALS['xoopsDB']->prefix('pedigree_registry') . " WHERE roft = '1' AND pname LIKE '" . $l . "%' ORDER BY pname LIMIT " . $st . ', ' . $perPage;
        $result      = $GLOBALS['xoopsDB']->query($sql);

        $animal = new Pedigree\Animal();
        //test to find out how many user fields there are...
        $fields       = $animal->getNumOfFields();
        $numOfColumns = 1;
        $columns[]    = ['columnname' => 'Name'];
        foreach ($fields as $i => $iValue) {
            $userField   = new Pedigree\Field($fields[$i], $animal->getConfig());
            $fieldType   = $userField->getSetting('fieldtype');
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
                ++$numOfColumns;
                unset($lookupValues);
            }
        }

        $empty = array_fill(0, $numOfColumns - 1, ['value' => '']);
        /*
        $empty = []; //initialize the array
        for ($i = 1; $i < $numOfColumns; ++$i) {
            $empty[] = ['value' => ''];
        }
        */
        $dogs [] = [
            'id'          => '0',
            'name'        => '',
            'gender'      => '',
            'link'        => '<a href="add_litter.php?f=check&random=' . $random . '&seldam=0">' . strtr(_MA_PEDIGREE_ADD_DAMUNKNOWN, ['[mother]' => $helper->getConfig('mother', '')]) . '</a>',
            'colour'      => '',
            'number'      => '',
            'usercolumns' => $empty,
        ];

        while (false !== ($row = $GLOBALS['xoopsDB']->fetchArray($result))) {
            //create picture information
            $camera = ('' != $row['foto']) ? " <img src=\"" . PEDIGREE_IMAGE_URL . "/camera.png\">" : '';
            $name   = stripslashes($row['pname']) . $camera;
            //empty array
            unset($columnvalue);
            //fill array
            for ($i = 1; $i < $numOfColumns; ++$i) {
                $x = $columns[$i]['columnnumber'];
                if (is_array($columns[$i]['lookupval'])) {
                    foreach ($columns[$i]['lookupval'] as $key => $keyValue) {
                        if ($key == $row['user' . $x]) {
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
            }
            $dogs[] = [
                'id'          => $row['id'],
                'name'        => $name,
                'gender'      => "<img src=\"" . PEDIGREE_IMAGE_URL . "/female.gif\">",
                'link'        => '<a href="add_litter.php?f=check&random=' . $random . '&seldam=' . $row['id'] . '">' . $name . '</a>',
                'colour'      => '',
                'number'      => '',
                'usercolumns' => $columnvalue,
            ];
        }

        //add data to smarty template
        //assign dog
        $GLOBALS['xoopsTpl']->assign([
                                         'dogs'         => $dogs,
                                         'columns'      => $columns,
                                         'numofcolumns' => $numOfColumns,
                                         'tsarray'      => Pedigree\Utility::sortTable($numOfColumns),
                                         'nummatch'     => strtr(_MA_PEDIGREE_ADD_SELDAM, ['[mother]' => $helper->getConfig('mother', '')]),
                                         'pages'        => $pages,
                                     ]);
        break;
    case 'check':
        if (empty($random)) {
            $random = Request::getInt('random', 0);
        }
        //query
        $sql = 'SELECT * FROM ' . $GLOBALS['xoopsDB']->prefix('pedigree_temp') . ' WHERE id = ' . $random;
        $result      = $GLOBALS['xoopsDB']->query($sql);
        $seldam      = Request::getInt('seldam', 0, 'GET');
        while (false !== ($row = $GLOBALS['xoopsDB']->fetchArray($result))) {
            //pull data apart.
            if ('' !== $row['pname']) {
                $genders    = explode(':', $row['roft']);
                $names      = explode(':', $row['pname']);
                $namesCount = count($names);
                for ($c = 1; $c < $namesCount; ++$c) {
                    //$query = 'INSERT INTO ' . $GLOBALS['xoopsDB']->prefix('pedigree_registry') . " VALUES ('','" . addslashes($names[$c]) . "','0','" . $row['id_breeder'] . "','" . $row['user'] . "','" . $genders[$c] . "','" . $_GET['seldam'] . "','" . $row['father'] . "','',''";
                    $query = 'INSERT INTO '
                             . $GLOBALS['xoopsDB']->prefix('pedigree_registry')
                             . " VALUES ('','"
                             . $GLOBALS['xoopsDB']->escape($names[$c])
                             . "','0','"
                             . $GLOBALS['xoopsDB']->escape($row['id_breeder'])
                             . "','"
                             . $GLOBALS['xoopsDB']->escape($row['user'])
                             . "','"
                             . $GLOBALS['xoopsDB']->escape($genders[$c])
                             . "','"
                             . $GLOBALS['xoopsDB']->escape($seldam)
                             . "','"
                             . $GLOBALS['xoopsDB']->escape($row['father'])
                             . "','',''";
                    //create animal object
                    $animal = new Pedigree\Animal();
                    //test to find out how many user fields there are..
                    $fields = $animal->getNumOfFields();
                    sort($fields);
                    foreach ($fields as $i => $iValue) {
                        $userfields{$fields[$i]} = explode(':', $row['user' . $iValue]);
                        $query                   .= ",'" . $userfields{$fields[$i]}
                            [$c] . "'";
                    }
                    //insert into pedigree
                    $query .= ');';
                    $GLOBALS['xoopsDB']->queryF($query);
                }
            }
            $sqlQuery = 'DELETE FROM ' . $GLOBALS['xoopsDB']->prefix('pedigree_temp') . " WHERE id='" . $random . "'";
        }
        $helper->redirect('latest.php', 1, strtr(_MA_PEDIGREE_ADD_LIT_OK, ['[animalTypes]' => $helper->getConfig('animalTypes')]));
        break;
}

//footer
require XOOPS_ROOT_PATH . '/footer.php';
