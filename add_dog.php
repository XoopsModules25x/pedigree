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

//require_once  dirname(dirname(__DIR__)) . '/mainfile.php';
require_once __DIR__ . '/header.php';
/** @var \XoopsModules\Pedigree\Helper $helper */
$helper->loadLanguage('main');

// Include any common code for this module.
require_once $helper->path('include/common.php');

$GLOBALS['xoopsOption']['template_main'] = 'pedigree_adddog.tpl';
include XOOPS_ROOT_PATH . '/header.php';
$GLOBALS['xoopsTpl']->assign('page_title', _MA_PEDIGREE_UPDATE);

//check for access
$xoopsModule = XoopsModule::getByDirname($moduleDirName);
if (empty($GLOBALS['xoopsUser']) || !$GLOBALS['xoopsUser'] instanceof \XoopsUser || $GLOBALS['xoopsUser']->isGuest()) {
    $helper->redirect('', 3, _NOPERM . '<br>' . _MA_PEDIGREE_REGIST);
}

//create function variable from url
//if (isset($_GET['f'])) {
//    $f = $_GET['f'];
//} else {
//    $f = '';
//    addDog();
//}

$f      = Request::getString('f', '', 'GET');
$random = '';

switch ($f) {
    case 'checkName':
        $name        = Request::getString('naam', '', 'POST');
        $queryString = 'SELECT * FROM ' . $GLOBALS['xoopsDB']->prefix('pedigree_tree') . " WHERE naam LIKE'%" . $GLOBALS['xoopsDB']->escape($name) . "%' ORDER BY naam";
        $result      = $GLOBALS['xoopsDB']->query($queryString);
        $numResults  = $GLOBALS['xoopsDB']->getRowsNum($result);
        if ($numResults >= 1 && !isset($_GET['r'])) {
            //create form
            include XOOPS_ROOT_PATH . '/class/xoopsformloader.php';
            $form = new \XoopsThemeForm(strtr(_MA_PEDIGREE_ADD_DOG, ['[animalType]' => $helper->getConfig['animalType']]), 'dogname', 'add_dog.php?f=checkName&r=1', 'post');
            $form->addElement(new \XoopsFormHiddenToken($name = 'XOOPS_TOKEN_REQUEST', $timeout = 360));
            $form->addElement(new \XoopsFormHidden('naam', $name));
            $form->addElement(new \XoopsFormHidden('user', $GLOBALS['xoopsUser']->getVar('uid')));
            while (false !== ($row = $GLOBALS['xoopsDB']->fetchBoth($result))) {
                $form->addElement(new \XoopsFormLabel('<b>' . _MA_PEDIGREE_FLD_NAME . '</b>', '<a href="' . $helper->url('dog.php?id=' . $row['id']) . '">' . stripslashes($row['naam']) . '</a>'));
            }
            $form->addElement(new \XoopsFormLabel(_MA_PEDIGREE_EXPLAIN, strtr(_MA_PEDIGREE_ADD_KNOWN, ['[animalTypes]' => $helper->getConfig['animalTypes']])));
            //submit button
            $form->addElement(new \XoopsFormButton('', 'button_id', strtr(_MA_PEDIGREE_ADD_KNOWNOK, ['[animalType]' => $helper->getConfig['animalType']]), 'submit'));
            //add data (form) to smarty template
            $GLOBALS['xoopsTpl']->assign('form', $form->render());
        } else {
            //create form
            include XOOPS_ROOT_PATH . '/class/xoopsformloader.php';
            $form = new \XoopsThemeForm(strtr(_MA_PEDIGREE_ADD_DOG, ['[animalType]' => $helper->getConfig['animalType']]), 'dogname', 'add_dog.php?f=sire', 'post');
            //added to handle upload
            $form->setExtra("enctype='multipart/form-data'");
            $form->addElement(new \XoopsFormHiddenToken($name = 'XOOPS_TOKEN_REQUEST', $timeout = 360));
            //create random value
            $random = (mt_rand() % 10000);
            $form->addElement(new \XoopsFormHidden('random', $random));
            $form->addElement(new \XoopsFormHidden('naam', htmlspecialchars($name, ENT_QUOTES)));
            //find userid from previous form
            $form->addElement(new \XoopsFormHidden('user', $GLOBALS['xoopsUser']->getVar('uid')));

            //name
            $form->addElement(new \XoopsFormLabel('<b>' . _MA_PEDIGREE_FLD_NAME . '</b>', stripslashes($name)));
            //gender
            $gender_radio = new \XoopsFormRadio('<b>' . _MA_PEDIGREE_FLD_GEND . '</b>', 'roft', $value = '0');
            $gender_radio->addOptionArray([
                '0' => strtr(_MA_PEDIGREE_FLD_MALE, ['[male]' => $helper->getConfig['male']]),
                '1' => strtr(_MA_PEDIGREE_FLD_FEMA, ['[female]' => $helper->getConfig['female']])
            ]);
            $form->addElement($gender_radio);
            if ('1' == $helper->getConfig['ownerbreeder']) {
                //breeder
                $breeder_select = new \XoopsFormSelect('<b>' . _MA_PEDIGREE_FLD_BREE . '</b>', $name = 'id_breeder', $value = '0', $size = 1, $multiple = false);
                $queryfok       = 'SELECT id, lastname, firstname FROM ' . $GLOBALS['xoopsDB']->prefix('pedigree_owner') . ' ORDER BY lastname';
                $resfok         = $GLOBALS['xoopsDB']->query($queryfok);
                $breeder_select->addOption('0', $name = _MA_PEDIGREE_UNKNOWN);
                while (false !== ($rowfok = $GLOBALS['xoopsDB']->fetchArray($resfok))) {
                    $breeder_select->addOption($rowfok['id'], $name = $rowfok['lastname'] . ', ' . $rowfok['firstname']);
                }
                $form->addElement($breeder_select);
                $form->addElement(new \XoopsFormLabel(_MA_PEDIGREE_EXPLAIN, strtr(_MA_PEDIGREE_FLD_BREE_EX, ['[animalType]' => $helper->getConfig['animalType']])));

                //owner
                $owner_select = new \XoopsFormSelect('<b>' . _MA_PEDIGREE_FLD_OWNE . '</b>', $name = 'id_owner', $value = '0', $size = 1, $multiple = false);
                $queryfok     = 'SELECT id, lastname, firstname FROM ' . $GLOBALS['xoopsDB']->prefix('pedigree_owner') . ' ORDER BY lastname';
                $resfok       = $GLOBALS['xoopsDB']->query($queryfok);
                $owner_select->addOption('0', $name = _MA_PEDIGREE_UNKNOWN);
                while (false !== ($rowfok = $GLOBALS['xoopsDB']->fetchArray($resfok))) {
                    $owner_select->addOption($rowfok['id'], $name = $rowfok['lastname'] . ', ' . $rowfok['firstname']);
                }
                $form->addElement($owner_select);
                $form->addElement(new \XoopsFormLabel(_MA_PEDIGREE_EXPLAIN, strtr(_MA_PEDIGREE_FLD_OWNE_EX, ['[animalType]' => $helper->getConfig['animalType']])));
            }
            //picture
            $max_imgsize = 1024000;
            $img_box     = new \XoopsFormFile('Image', 'photo', $max_imgsize);
            $img_box->setExtra("size ='50'");
            $form->addElement($img_box);

            //create animal object
            $animal = new Pedigree\Animal();
            //test to find out how many user fields there are..
            $fields = $animal->getNumOfFields();

            foreach ($fields as $i => $iValue) {
                $userField   = new Pedigree\Field($fields[$i], $animal->getConfig());
                $fieldType   = $userField->getSetting('FieldType');
                $fieldObject = new $fieldType($userField, $animal);
                if ($userField->isActive() && !$userField->isLocked()) {
                    $newEntry = $fieldObject->newField();
                    $form->addElement($newEntry);
                }
                unset($newEntry);
            }

            //submit button
            $form->addElement(new \XoopsFormButton('', 'button_id', strtr(_MA_PEDIGREE_ADD_SIRE, ['[father]' => $helper->getConfig['father']]), 'submit'));

            //add data (form) to smarty template
            $GLOBALS['xoopsTpl']->assign('form', $form->render());
        }
        break;
    case 'sire':
        $empty  = []; // an empty array
        $user            = Request::getInt('user', null, 'POST');
        $random          = Request::getString('random', $random, 'GET');
        //@todo is $st suppose to be POST?
        $st              = Request::getInt('st', 0, 'GET');
        $name            = Request::getString('naam', null, 'POST');
        $roft            = Request::getString('roft', null, 'POST');
        $id_owner        = Request::getInt('id_owner', null, 'POST');
        $id_breeder      = Request::getInt('id_breeder', null, 'POST');
        $pictureField    = isset($_FILES['photo']) ? $_FILES['photo']['name'] : null; // $_FILES['photo']['name'];
        $foto            = (empty($pictureField)) ? '' : Pedigree\Utility::uploadPicture(0);
        $numPictureField = 1;

        //make the redirect
        if (!isset($_GET['r'])) {
            if (empty($name)) {
                $helper->redirect('add_dog.php', 1, _MA_PEDIGREE_ADD_NAMEPLZ);
            }
            //create animal object
            $animal = new Pedigree\Animal();
            $fields = $animal->getNumOfFields();//test to find out how many user fields there are..
            sort($fields); //sort by ID not by order
            $usersql = '';
            foreach ($fields as $i => $iValue) {
                $userField   = new Pedigree\Field($fields[$i], $animal->getConfig());
                $fieldType   = $userField->getSetting('FieldType');
                $fieldObject = new $fieldType($userField, $animal);
                if ($userField->isActive()) {
                    //check if _FILES variable exists for user picturefield
                    $currentfield = 'user' . $iValue;
                    $pictureField = $_FILES[$currentfield]['name'];
                    if ('Picture' === $fieldType && (!empty($pictureField) || '' != $pictureField)) {
                        $userpicture = Pedigree\Utility::uploadPicture($numPictureField);
                        $usersql .= ",'" . $userpicture . "'";
                        ++$numPictureField;
                    } elseif ($userField->isLocked()) {
                        //userfield is locked, substitute default value
                        $usersql .= ",'" . $userField->defaultvalue . "'";
                    } else {
                        //echo $fieldType.":".$i.":".$fields[$i]."<br>";
                        $usersql .= ",'" . Pedigree\Utility::unHtmlEntities($_POST['user' . $iValue]) . "'";
                    }
                } else {
                    $usersql .= ",''";
                }
                //echo $fields[$i]."<br>";
            }

            //insert into pedigree_temp
            //        $query = 'INSERT INTO ' . $GLOBALS['xoopsDB']->prefix('pedigree_temp') . " VALUES ('" . $random . "','" . Pedigree\Utility::unHtmlEntities($name) . "','" . $id_owner . "','" . $id_breeder . "','" . $user . "','" . $roft . "','','','" . $foto . "', ''" . $usersql . ')';
            $query = 'INSERT INTO '
                . $GLOBALS['xoopsDB']->prefix('pedigree_temp')
                . " VALUES ('"
                . $GLOBALS['xoopsDB']->escape($random)
                . "','"
                . $GLOBALS['xoopsDB']->escape(Pedigree\Utility::unHtmlEntities($name))
                . "','"
                . $GLOBALS['xoopsDB']->escape($id_owner)
                . "','"
                . $GLOBALS['xoopsDB']->escape($id_breeder)
                . "','"
                . $GLOBALS['xoopsDB']->escape($user)
                . "','"
                . $GLOBALS['xoopsDB']->escape($roft)
                . "','0','0','"
                . $GLOBALS['xoopsDB']->escape($foto)
                . "', ''"
                . $usersql
                . ')';
            //echo $query; die();
            $GLOBALS['xoopsDB']->queryF($query);
            $helper->redirect('add_dog.php?f=sire&random=' . $random . '&st=' . $st . '&r=1&l=a', 1, strtr(_MA_PEDIGREE_ADD_SIREPLZ, ['[father]' => $helper->getConfig['father']]));
        }
        //find letter on which to start else set to 'a'
        $l = Request::getString('l', 'a', 'GET');
        $GLOBALS['xoopsTpl']->assign('sire', '1');

        //create list of males dog to select from
        $perPage = $helper->getConfig['perpage'];
        //count total number of dogs
        $numDog = 'SELECT count(id) FROM ' . $GLOBALS['xoopsDB']->prefix('pedigree_tree') . " WHERE roft='0' AND naam LIKE '" . $l . "%'";
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
        $pages = '';
        for ($i = 65; $i <= 90; ++$i) {
            if ($l == chr($i)) {
                $pages .= '<b><a href="' . $helper->url('add_dog.php?f=sire&r=1&random=' . $random . '&l=' . chr($i)) . '">' . chr($i) . '</a></b>&nbsp;';
            } else {
                $pages .= '<a href="' . $helper->url('add_dog.php?f=sire&r=1&random=' . $random . '&l=' . chr($i)) . '">' . chr($i) . '</a>&nbsp;';
            }
        }
        $pages .= '-&nbsp;';
        $pages .= '<a href="' . $helper->url('add_dog.php?f=sire&r=1&random=' . $random . '&l=Ã…') . '">Ã…</a>&nbsp;';
        $pages .= '<a href="' . $helper->url('add_dog.php?f=sire&r=1&random=' . $random . '&l=Ã–') . '">Ã–</a>&nbsp;';
        //create linebreak
        $pages .= '<br>';
        //create previous button
        if ($numPages > 1) {
            if ($currentPage > 1) {
                $pages .= '<a href="' . $helper->url('add_dog.php?f=sire&r=1&l=' . $l . '&random=' . $random . '&st=' . ($st - $perPage)) . '">' . _MA_PEDIGREE_PREVIOUS . '</a>&nbsp;&nbsp';
            }
        }
        //create numbers
        for ($x = 1; $x < ($numPages + 1); ++$x) {
            //create line break after 20 numbers
            if (0 == ($x % 20)) {
                $pages .= '<br>';
            }
            if ($x != $currentPage) {
                $pages .= '<a href="' . $helper->url('add_dog.php?f=sire&r=1&l=' . $l . '&random=' . $random . '&st=' . ($perPage * ($x - 1))) . '">' . $x . '</a>&nbsp;&nbsp;';
            } else {
                $pages .= $x . '&nbsp;&nbsp';
            }
        }
        //create next button
        if ($numPages > 1) {
            if ($currentPage < $numPages) {
                $pages .= '<a href="' . $helper->url('add_dog.php?f=sire&r=1&l=' . $l . '&random=' . $random . '&st=' . ($st + $perPage)) . '">' . _MA_PEDIGREE_NEXT . '</a>&nbsp;&nbsp';
            }
        }

        //query
        $queryString = 'SELECT * FROM ' . $GLOBALS['xoopsDB']->prefix('pedigree_tree') . " WHERE roft = '0' AND naam LIKE '" . $l . "%'ORDER BY naam LIMIT " . $st . ', ' . $perPage;
        $result      = $GLOBALS['xoopsDB']->query($queryString);

        $animal = new Pedigree\Animal();
        //test to find out how many user fields there are...
        $fields       = $animal->getNumOfFields();
        $numofcolumns = 1;
        $columns[]    = ['columnname' => 'Name'];
        foreach ($fields as $i => $iValue) {
            $userField   = new Pedigree\Field($fields[$i], $animal->getConfig());
            $fieldType   = $userField->getSetting('FieldType');
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
                    'lookupval'    => $lookupValues
                ];
                ++$numofcolumns;
                unset($lookupValues);
            }
        }

        for ($i = 1; $i < $numofcolumns; ++$i) {
            $empty[] = ['value' => ''];
        }
        $dogs [] = [
            'id'          => '0',
            'name'        => '',
            'gender'      => '',
            'link'        => '<a href="' . $helper->url('add_dog.php?f=dam&random=' . $random . '&selsire=0') . '">' . strtr(_MA_PEDIGREE_ADD_SIREUNKNOWN, ['[father]' => $helper->getConfig['father']]) . '</a>',
            'colour'      => '',
            'number'      => '',
            'usercolumns' => $empty
        ];

        while (false !== ($row = $GLOBALS['xoopsDB']->fetchArray($result))) {
            //create picture information
            if ('' != $row['foto']) {
                $camera = ' <img src="assets/images/camera.png">';
            } else {
                $camera = '';
            }
            $name = stripslashes($row['naam']) . $camera;
            //empty array
            unset($columnvalue);
            //fill array
            for ($i = 1; $i < $numofcolumns; ++$i) {
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
                elseif (0 === strncmp($row['user' . $x], 'http://', 7)) { //@todo need to update for https
                    $value = '<a href="' . $row['user' . $x] . '">' . $row['user' . $x] . '</a>';
                } else {
                    $value = $row['user' . $x];
                }
                $columnvalue[] = ['value' => $value];
            }
            $dogs[] = [
                'id'          => $row['id'],
                'name'        => $name,
                'gender'      => '<img src="assets/images/male.gif">',
                'link'        => '<a href="' . $helper->url('add_dog.php?f=dam&random=' . $random . '&selsire=' . $row['id']) . '">' . $name . '</a>',
                'colour'      => '',
                'number'      => '',
                'usercolumns' => $columnvalue
            ];
        }

        //add data to smarty template
        //assign dog
        $GLOBALS['xoopsTpl']->assign('dogs', $dogs);
        $GLOBALS['xoopsTpl']->assign('columns', $columns);
        $GLOBALS['xoopsTpl']->assign('numofcolumns', $numofcolumns);
        $GLOBALS['xoopsTpl']->assign('tsarray', Pedigree\Utility::sortTable($numofcolumns));
        //assign links
        $GLOBALS['xoopsTpl']->assign('nummatch', strtr(_MA_PEDIGREE_ADD_SELSIRE, ['[father]' => $helper->getConfig['father']]));
        $GLOBALS['xoopsTpl']->assign('pages', $pages);

        //mb =========== FATHER LETTERS =============================
        $roft     = 0;
        //    $criteria     = $helper->getHandler('Tree')->getActiveCriteria($roft);
        $activeObject = 'Tree';
        $name         = 'naam';
        $number1      = '1';
        $number2      = '0';
        //    $link         = "virtual.php?r={$number1}&st={$number2}&l=";
        $link = "add_dog.php?f=sire&r=1&random={$random}&l=";

        //    http://localhost/257belgi/modules/pedigree/virtual.php?f=dam&selsire=35277

        $link2 = '';

        $criteria = $helper->getHandler('Tree')->getActiveCriteria($roft);
        //    $criteria->setGroupby('UPPER(LEFT(' . $name . ',1))');

        $fatherArray['letters'] = Pedigree\Utility::lettersChoice($helper, $activeObject, $criteria, $name, $link, $link2);
        //$catarray['toolbar']    = pedigree_toolbar();
        $GLOBALS['xoopsTpl']->assign('fatherArray', $fatherArray);
        break;

    case 'dam':
        $empty = []; // an empty array
        if (empty($random)) {
            $random = Request::getInt('random', 0);
        }
        $st = Request::getInt('st', 0, 'GET');
        $l  = Request::getString('l', 'a', 'GET');
        //make the redirect
        if (!isset($_GET['r'])) {
            //insert into pedigree_temp
            //        $query = 'UPDATE ' . $GLOBALS['xoopsDB']->prefix('pedigree_temp') . ' SET father =' . $_GET['selsire'] . ' WHERE id=' . $random;
            //        $GLOBALS['xoopsDB']->queryF($query);
            $query = 'UPDATE ' . $GLOBALS['xoopsDB']->prefix('pedigree_temp') . ' SET father =' . Request::getInt('selsire', 0, 'GET') . ' WHERE id=' . $random;
            $GLOBALS['xoopsDB']->queryF($query);
            $helper->redirect('add_dog.php?f=dam&random=' . $random . '&st=' . $st . '&r=1&l=a', 1, strtr(_MA_PEDIGREE_ADD_SIREOK, ['[mother]' => $helper->getConfig['mother']]));
        }

        $GLOBALS['xoopsTpl']->assign('sire', '1');
        //create list of males dog to select from
        $perPage = $helper->getConfig['perpage'];
        //count total number of dogs
        $numDog = 'SELECT count(id) FROM ' . $GLOBALS['xoopsDB']->prefix('pedigree_tree') . " WHERE roft='1' AND naam LIKE '" . $l . "%'";
        $numRes = $GLOBALS['xoopsDB']->query($numDog);
        list($numResults) = $GLOBALS['xoopsDB']->fetchRow($numRes);
        $numPages = floor($numResults / $perPage) + 1;
        if (($numPages * $perPage) == ($numResults + $perPage)) {
            --$numPages;
        }
        $currentPage = floor($st / $perPage) + 1;
        //create alphabet
        $pages = '';
        for ($i = 65; $i <= 90; ++$i) {
            if ($l == chr($i)) {
                $pages .= '<b><a href="' . $helper->url('add_dog.php?f=dam&r=1&random=' . $random . '&l=' . chr($i)) . '">' . chr($i) . '</a></b>&nbsp;';
            } else {
                $pages .= '<a href="' . $helper->url('add_dog.php?f=dam&r=1&random=' . $random . '&l=' . chr($i)) . '">' . chr($i) . '</a>&nbsp;';
            }
        }
        $pages .= '-&nbsp;';
        $pages .= '<a href="' . $helper->url('add_dog.php?f=dam&r=1&random=' . $random . '&l=Ã…') . '">Ã…</a>&nbsp;';
        $pages .= '<a href="' . $helper->url('add_dog.php?f=dam&r=1&random=' . $random . '&l=Ã–') . '">Ã–</a>&nbsp;';
        $pages .= '<br>';
        //create previous button
        if ($numPages > 1) {
            if ($currentPage > 1) {
                $pages .= '<a href="' . $helper->url('add_dog.php?f=dam&r=1&l=' . $l . '&random=' . $random . '&st=' . ($st - $perPage)) . '">' . _MA_PEDIGREE_PREVIOUS . '</a>&nbsp;&nbsp';
            }
        }
        //create numbers
        for ($x = 1; $x < ($numPages + 1); ++$x) {
            //create line break after 20 number
            if (0 == ($x % 20)) {
                $pages .= '<br>';
            }
            if ($x != $currentPage) {
                $pages .= '<a href="' . $helper->url('add_dog.php?f=dam&r=1&l=' . $l . '&random=' . $random . '&st=' . ($perPage * ($x - 1))) . '">' . $x . '</a>&nbsp;&nbsp;';
            } else {
                $pages .= $x . '&nbsp;&nbsp';
            }
        }
        //create next button
        if ($numPages > 1) {
            if ($currentPage < $numPages) {
                $pages .= '<a href="' . $helper->url('add_dog.php?f=dam&l=' . $l . '&r=1&random=' . $random . '&st=' . ($st + $perPage)) . '">' . _MA_PEDIGREE_NEXT . '</a>&nbsp;&nbsp;';
            }
        }

        //query
        $queryString = 'SELECT * FROM ' . $GLOBALS['xoopsDB']->prefix('pedigree_tree') . " WHERE roft = '1' AND naam LIKE '" . $l . "%' ORDER BY naam LIMIT " . $st . ', ' . $perPage;
        $result      = $GLOBALS['xoopsDB']->query($queryString);

        $animal = new Pedigree\Animal();
        //test to find out how many user fields there are...
        $fields       = $animal->getNumOfFields();
        $numofcolumns = 1;
        $columns[]    = ['columnname' => 'Name'];
        foreach ($fields as $i => $iValue) {
            $userField   = new Pedigree\Field($fields[$i], $animal->getConfig());
            $fieldType   = $userField->getSetting('FieldType');
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
                    'lookupval'    => $lookupValues
                ];
                ++$numofcolumns;
                unset($lookupValues);
            }
        }

        for ($i = 1; $i < $numofcolumns; ++$i) {
            $empty[] = ['value' => ''];
        }
        $dogs [] = [
            'id'          => '0',
            'name'        => '',
            'gender'      => '',
            'link'        => '<a href="' . $helper->url('add_dog.php?f=check&random=' . $random . '&seldam=0') . '">' . strtr(_MA_PEDIGREE_ADD_DAMUNKNOWN, ['[mother]' => $helper->getConfig['mother']]) . '</a>',
            'colour'      => '',
            'number'      => '',
            'usercolumns' => $empty
        ];

        while (false !== ($row = $GLOBALS['xoopsDB']->fetchArray($result))) {
            //create picture information
            if ('' != $row['foto']) {
                $camera = ' <img src="assets/images/camera.png">';
            } else {
                $camera = '';
            }
            $name = stripslashes($row['naam']) . $camera;
            //empty array
            unset($columnvalue);
            //fill array
            for ($i = 1; $i < $numofcolumns; ++$i) {
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
                'gender'      => '<img src="assets/images/female.gif">',
                'link'        => '<a href="' . $helper->url('add_dog.php?f=check&random=' . $random . '&seldam=' . $row['id']) . '">' . $name . '</a>',
                'colour'      => '',
                'number'      => '',
                'usercolumns' => $columnvalue
            ];
        }

        //add data to smarty template
        //assign dog
        $GLOBALS['xoopsTpl']->assign('dogs', $dogs);
        $GLOBALS['xoopsTpl']->assign('columns', $columns);
        $GLOBALS['xoopsTpl']->assign('numofcolumns', $numofcolumns);
        $GLOBALS['xoopsTpl']->assign('tsarray', Pedigree\Utility::sortTable($numofcolumns));
        $GLOBALS['xoopsTpl']->assign('nummatch', strtr(_MA_PEDIGREE_ADD_SELDAM, ['[mother]' => $helper->getConfig['mother']]));
        $GLOBALS['xoopsTpl']->assign('pages', $pages);

        //mb ========= MOTHER LETTERS===============================
        $roft     = 1;
        //    $criteria     = $helper->getHandler('Tree')->getActiveCriteria($roft);
        $activeObject = 'Tree';
        $name         = 'naam';
        $number1      = '1';
        $number2      = '0';
        $link         = "add_dog.php?f=dam&r=1&random={$random}&l=";
        $link2        = '';

        $criteria = $helper->getHandler('Tree')->getActiveCriteria($roft);
        //    $criteria->setGroupby('UPPER(LEFT(' . $name . ',1))');

        $motherArray['letters'] = Pedigree\Utility::lettersChoice($helper, $activeObject, $criteria, $name, $link, $link2);
        //$catarray['toolbar']    = pedigree_toolbar();
        $GLOBALS['xoopsTpl']->assign('motherArray', $motherArray);
        break;

    case 'check':
        if (empty($random)) {
            $random = Request::getInt('random', null);
        }

        //query
        $queryString = 'SELECT * FROM ' . $GLOBALS['xoopsDB']->prefix('pedigree_temp') . ' WHERE id = ' . $random;
        $result      = $GLOBALS['xoopsDB']->query($queryString);
        while (false !== ($row = $GLOBALS['xoopsDB']->fetchArray($result))) {
            //create animal object
            $animal = new Pedigree\Animal();
            $fields = $animal->getNumOfFields(); //test to find out how many user fields there are..
            sort($fields);
            $usersql = '';
            foreach ($fields as $i => $iValue) {
                $userField   = new Pedigree\Field($fields[$i], $animal->getConfig());
                $fieldType   = $userField->getSetting('FieldType');
                $fieldObject = new $fieldType($userField, $animal);
                if ($userField->isActive()) {
                    $usersql .= ",'" . addslashes($row['user' . $iValue]) . "'";
                } else {
                    $usersql .= ",'" . $fieldObject->defaultvalue . "'";
                }
                //echo $fields[$i]."<br>";
            }
            //insert into pedigree
            //$query = 'INSERT INTO ' . $GLOBALS['xoopsDB']->prefix('pedigree_tree') . " VALUES ('','" . addslashes($row['naam']) . "','" . $row['id_owner'] . "','" . $row['id_breeder'] . "','" . $row['user'] . "','" . $row['roft'] . "','" . $_GET['seldam'] . "','" . $row['father'] . "','" . addslashes($row['foto']) . "',''" . $usersql . ')';
            $sql = 'INSERT INTO '
                . $GLOBALS['xoopsDB']->prefix('pedigree_tree')
                . " VALUES (0,'"
                . $GLOBALS['xoopsDB']->escape($row['naam'])
                . "','"
                . $GLOBALS['xoopsDB']->escape($row['id_owner'])
                . "','"
                . $GLOBALS['xoopsDB']->escape($row['id_breeder'])
                . "','"
                . $GLOBALS['xoopsDB']->escape($row['user'])
                . "','"
                . $GLOBALS['xoopsDB']->escape($row['roft'])
                . "','"
                . $GLOBALS['xoopsDB']->escape($_GET['seldam'])
                . "','"
                . $GLOBALS['xoopsDB']->escape($row['father'])
                . "','"
                . $GLOBALS['xoopsDB']->escape($row['foto'])
                . "',''"
                . $usersql
                . ')';
            $GLOBALS['xoopsDB']->queryF($sql);
            //echo $query; die();
            break;
        }
        $sqlQuery = 'DELETE FROM ' . $GLOBALS['xoopsDB']->prefix('pedigree_temp') . " WHERE id='" . $random . "'";
        $GLOBALS['xoopsDB']->queryF($sqlQuery);
        $helper->redirect('latest.php', 1, strtr(_MA_PEDIGREE_ADD_OK, ['[animalType]' => $helper->getConfig['animalType']]));
        break;

    default: //add a dog
        include XOOPS_ROOT_PATH . '/class/xoopsformloader.php';
        //create form
        $form = new \XoopsThemeForm(strtr(_MA_PEDIGREE_ADD_DOG, ['[animalType]' => $helper->getConfig['animalType']]), 'dogname', 'add_dog.php?f=checkName', 'post');
        $form->addElement(new \XoopsFormHiddenToken($name = 'XOOPS_TOKEN_REQUEST', $timeout = 360));
        //create random value
        $random = (mt_rand() % 10000);
        $form->addElement(new \XoopsFormHidden('random', $random));
        //find userid
        $form->addElement(new \XoopsFormHidden('user', $GLOBALS['xoopsUser']->getVar('uid')));

        //name
        $form->addElement(new \XoopsFormText('<b>' . _MA_PEDIGREE_FLD_NAME . '</b>', 'naam', $size = 50, $maxsize = 255, $value = ''), true); // name required
        $string = strtr(_MA_PEDIGREE_FLD_NAME_EX, ['[animalType]' => $helper->getConfig['animalType']]);
        $form->addElement(new \XoopsFormLabel(_MA_PEDIGREE_EXPLAIN, $string));

        //submit button
        $form->addElement(new \XoopsFormButton('', 'button_id', strtr(_MA_PEDIGREE_ADD_DATA, ['[animalType]' => $helper->getConfig['animalType']]), 'submit'));

        //add data (form) to smarty template
        $GLOBALS['xoopsTpl']->assign('form', $form->render());
        break;
}

//footer
include XOOPS_ROOT_PATH . '/footer.php';
