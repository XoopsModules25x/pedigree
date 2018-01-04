<?php
// -------------------------------------------------------------------------

use Xmf\Request;

//require_once __DIR__ . '/../../mainfile.php';
require_once __DIR__ . '/header.php';
$moduleDirName = basename(__DIR__);
xoops_loadLanguage('main', $moduleDirName);
// Include any common code for this module.
require_once XOOPS_ROOT_PATH . '/modules/' . $xoopsModule->dirname() . '/include/common.php';
require_once XOOPS_ROOT_PATH . '/modules/' . $xoopsModule->dirname() . '/include/class_field.php';

$GLOBALS['xoopsOption']['template_main'] = 'pedigree_addlitter.tpl';
include XOOPS_ROOT_PATH . '/header.php';
$xoopsTpl->assign('page_title', 'Pedigree database - add a litter');

//check for access
$xoopsModule = XoopsModule::getByDirname($moduleDirName);
if (empty($xoopsUser)) {
    redirect_header('index.php', 3, _NOPERM . '<br>' . _MA_PEDIGREE_REGIST);
}

//get module configuration
/** @var XoopsModuleHandler $moduleHandler */
$moduleHandler = xoops_getHandler('module');
$module        = $moduleHandler->getByDirname($moduleDirName);
$configHandler = xoops_getHandler('config');
$moduleConfig  = $configHandler->getConfigsByCat(0, $module->getVar('mid'));

if (!isset($_GET['f'])) {
    addlitter();
} else {
    $f = $_GET['f'];
    if ('sire' === $f) {
        sire();
    }
    if ('dam' === $f) {
        dam();
    }
    if ('check' === $f) {
        check();
    }
}

function addlitter()
{
    global $xoopsTpl, $xoopsUser, $xoopsDB, $xoopsOption;
    $moduleDirName = basename(__DIR__);
    //get module configuration
    /** @var XoopsModuleHandler $moduleHandler */
    $moduleHandler = xoops_getHandler('module');
    $module        = $moduleHandler->getByDirname($moduleDirName);
    $configHandler = xoops_getHandler('config');
    $moduleConfig  = $configHandler->getConfigsByCat(0, $module->getVar('mid'));

    //create xoopsform
    include XOOPS_ROOT_PATH . '/class/xoopsformloader.php';
    $searchform = new XoopsThemeForm(strtr(_MA_PEDIGREE_ADD_LITTER, ['[litter]' => $moduleConfig['litter']]), 'searchform', 'add_litter.php?f=sire', 'post', true);
    $searchform->addElement(new XoopsFormHiddenToken($name = 'XOOPS_TOKEN_REQUEST', $timeout = 360));
    //create random value
    $random = (mt_rand() % 10000);
    $searchform->addElement(new XoopsFormHidden('random', $random));
    //find userid
    $searchform->addElement(new XoopsFormHidden('userid', $xoopsUser->getVar('uid')));
    //create animal object
    $animal = new PedigreeAnimal();
    //test to find out how many user fields there are...
    $fields = $animal->getNumOfFields();

    //create form contents
    for ($count = 1; $count < 11; ++$count) {
        //name
        $searchform->addElement(new XoopsFormLabel($count . '.', strtr(_MA_PEDIGREE_KITT_NAME . $count . '.', ['[animalType]' => $moduleConfig['animalType']])));
        $textbox[$count] = new XoopsFormText('<b>' . _MA_PEDIGREE_FLD_NAME . '</b>', 'name' . $count, $size = 50, $maxsize = 50, '');
        $searchform->addElement($textbox[$count]);
        //gender
        $gender_radio[$count] = new XoopsFormRadio('<b>' . _MA_PEDIGREE_FLD_GEND . '</b>', 'roft' . $count, $value = '0');
        $gender_radio[$count]->addOptionArray([
                                                  '0' => strtr(_MA_PEDIGREE_FLD_MALE, ['[male]' => $moduleConfig['male']]),
                                                  '1' => strtr(_MA_PEDIGREE_FLD_FEMA, ['[female]' => $moduleConfig['female']])
                                              ]);
        $searchform->addElement($gender_radio[$count]);
        //add userfields
        for ($i = 0, $iMax = count($fields); $i < $iMax; ++$i) {
            $userField   = new Field($fields[$i], $animal->getConfig());
            $fieldType   = $userField->getSetting('FieldType');
            $fieldObject = new $fieldType($userField, $animal);
            if ($userField->isActive() && '1' == $userField->getSetting('Litter') && !$userField->isLocked()) {
                $newEntry[$count][$i] = $fieldObject->newField($count);
                $searchform->addElement($newEntry[$count][$i]);
            }
        }
        //add empty place holder as divider
        $searchform->addElement(new XoopsFormLabel('&nbsp;', ''));
    }

    $searchform->addElement(new XoopsFormLabel(_MA_PEDIGREE_ADD_DATA, _MA_PEDIGREE_DATA_INFO . $moduleConfig['litter'] . '.</h2>'));
    //add userfields that are not shown in the litter
    for ($i = 0, $iMax = count($fields); $i < $iMax; ++$i) {
        $userField   = new Field($fields[$i], $animal->getConfig());
        $fieldType   = $userField->getSetting('FieldType');
        $fieldObject = new $fieldType($userField, $animal);
        if ($userField->isActive() && $userField->generalLitter() && !$userField->isLocked()) {
            //add the "-" character to the beginning of the fieldname !!!
            $newEntry[$i] = $fieldObject->newField('-');
            $searchform->addElement($newEntry[$i]);
        }
    }
    //add the breeder to the list for the entire litter
    //no need to add the owner here because they will be different for each animal in the litter.
    if ('1' == $moduleConfig['ownerbreeder']) {
        //breeder
        $breeder  = new XoopsFormSelect(_MA_PEDIGREE_FLD_BREE, 'id_breeder', $value = '', $size = 1, $multiple = false);
        $queryfok = 'SELECT id, firstname, lastname FROM ' . $GLOBALS['xoopsDB']->prefix('pedigree_owner') . ' ORDER BY `lastname`';
        $resfok   = $GLOBALS['xoopsDB']->query($queryfok);
        $breeder->addOption(0, $name = 'Unknown');
        while (false !== ($rowfok = $GLOBALS['xoopsDB']->fetchArray($resfok))) {
            $breeder->addOption($rowfok['id'], $name = $rowfok['lastname'] . ', ' . $rowfok['firstname']);
        }
        $searchform->addElement($breeder);
    }

    //submit button
    $searchform->addElement(new XoopsFormButton('', 'submit', strtr(_MA_PEDIGREE_ADD_SIRE, ['[father]' => $moduleConfig['father']]), 'submit'));
    //send to template
    $searchform->assign($xoopsTpl);
}

function sire()
{
    global $xoopsTpl, $xoopsUser, $xoopsDB;
    $moduleDirName = basename(__DIR__);
    //debug option !
    //print_r($_POST); die();
    //get module configuration
    /** @var XoopsModuleHandler $moduleHandler */
    $moduleHandler = xoops_getHandler('module');
    $module        = $moduleHandler->getByDirname($moduleDirName);
    $configHandler = xoops_getHandler('config');
    $moduleConfig  = $configHandler->getConfigsByCat(0, $module->getVar('mid'));

    //check for access
    $xoopsModule = XoopsModule::getByDirname($moduleDirName);
    if (empty($GLOBALS['xoopsUser']) || !($GLOBALS['xoopsUser'] instanceof XoopsUser)) {
        redirect_header('javascript:history.go(-1)', 3, _NOPERM . '<br>' . _MA_PEDIGREE_REGIST);
    }
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
    $userid     = Request::getInt('userid', 0, 'post');
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
            $name .= ':' . $_POST[$namelitter];
            $roft .= ':' . $_POST[$roftlitter];
        } else {
            if ('1' == $count) {
                redirect_header('add_litter.php', 3, _MA_PEDIGREE_ADD_NAMEPLZ);
            }
        }
    }
    if (isset($_POST['id_breeder'])) {
        $id_breeder = $_POST['id_breeder'];
    } else {
        $id_breeder = '0';
    }

    //make the redirect
    if (!isset($_GET['r'])) {
        //create animal object
        $animal = new PedigreeAnimal();
        //test to find out how many user fields there are..
        $fields = $animal->getNumOfFields();
        sort($fields);
        $usersql = '';
        for ($i = 0, $iMax = count($fields); $i < $iMax; ++$i) {
            $userField   = new Field($fields[$i], $animal->getConfig());
            $fieldType   = $userField->getSetting('FieldType');
            $fieldObject = new $fieldType($userField, $animal);
            $defvalue    = $fieldObject->defaultvalue;
            //emtpy string to house the different values for this userfield
            $withinfield = '';
            for ($count = 1; $count < 11; ++$count) {
                if ('' !== $_POST['name' . $count]) {
                    if (isset($_POST[$count . 'user' . $fields[$i]])) {
                        //debug option
                        //echo $count.'user'.$fields[$i]."=".$_POST[$count.'user'.$fields[$i]]."<br>";
                        $withinfield .= ':' . $_POST[$count . 'user' . $fields[$i]];
                    } else {
                        if ($userField->isActive() && $userField->generalLitter() && !$userField->isLocked()) {
                            //use $_POST value if this is a general litter field
                            $withinfield .= ':' . $_POST['-user' . $fields[$i]];
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
        //      $query = 'INSERT INTO ' . $GLOBALS['xoopsDB']->prefix('pedigree_temp') . " VALUES ('" . $random . "','" . PedigreeUtility::unHtmlEntities($name) . "','0','" . $id_breeder . "','" . $userid . "','" . $roft . "','','','', ''";
        $query = 'INSERT INTO '
                 . $GLOBALS['xoopsDB']->prefix('pedigree_temp')
                 . " VALUES ('"
                 . Request::getInt($random)
                 . "','"
                 . Request::getInt(PedigreeUtility::unHtmlEntities($name))
                 . "','0','"
                 . Request::getInt($id_breeder)
                 . "','"
                 . Request::getInt($userid)
                 . "','"
                 . Request::getInt($roft)
                 . "','','','', ''";
        for ($i = 0, $iMax = count($fields); $i < $iMax; ++$i) {
            $userField   = new Field($fields[$i], $animal->getConfig());
            $fieldType   = $userField->getSetting('FieldType');
            $fieldObject = new $fieldType($userField, $animal);
            //do we only need to create a query for active fields ?
            $query .= ",'" . $user{$fields[$i]} . "'";
        }
        $query .= ')';
        //debug options
        //echo $query."<br>"; die();
        $GLOBALS['xoopsDB']->query($query);
        redirect_header('add_litter.php?f=sire&random=' . $random . '&st=' . $st . '&r=1&l=a', 1, strtr(_MA_PEDIGREE_ADD_SIREPLZ, ['[father]' => $moduleConfig['father']]));
    }
    //find letter on which to start else set to 'a'
    if (isset($_GET['l'])) {
        $l = $_GET['l'];
    } else {
        $l = 'a';
    }
    //assign 'sire' to the template
    $xoopsTpl->assign('sire', '1');
    //create list of males dog to select from
    $perPage = $moduleConfig['perpage'];
    //count total number of dogs
    $numDog = 'SELECT id FROM ' . $GLOBALS['xoopsDB']->prefix('pedigree_tree') . " WHERE roft='0' AND naam LIKE '" . $l . "%'";
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
    for ($i = 65; $i <= 90; ++$i) {
        if ($l == chr($i)) {
            $pages .= '<b><a href="add_litter.php?f=sire&r=1&r=1&random=' . $random . '&l=' . chr($i) . '">' . chr($i) . '</a></b>&nbsp;';
        } else {
            $pages .= '<a href="add_litter.php?f=sire&r=1&r=1&random=' . $random . '&l=' . chr($i) . '">' . chr($i) . '</a>&nbsp;';
        }
    }
    $pages .= '-&nbsp;';
    $pages .= '<a href="add_litter.php?f=sire&r=1&random=' . $random . '&l=Ã…">Ã…</a>&nbsp;';
    $pages .= '<a href="add_litter.php?f=sire&r=1&random=' . $random . '&l=Ã–">Ã–</a>&nbsp;';
    //create linebreak
    $pages .= '<br>';
    //create previous button
    if ($numPages > 1) {
        if ($currentPage > 1) {
            $pages .= '<a href="add_litter.php?f=sire&r=1&l=' . $l . '&random=' . $random . '&st=' . ($st - $perPage) . '">' . _MA_PEDIGREE_PREVIOUS . '</a>&nbsp;&nbsp';
        }
    }
    //create numbers
    for ($x = 1; $x < ($numPages + 1); ++$x) {
        //create line break after 20 number
        if (0 == ($x % 20)) {
            $pages .= '<br>';
        }
        if ($x != $currentPage) {
            $pages .= '<a href="add_litter.php?f=sire&r=1&l=' . $l . '&random=' . $random . '&st=' . ($perPage * ($x - 1)) . '">' . $x . '</a>&nbsp;&nbsp;';
        } else {
            $pages .= $x . '&nbsp;&nbsp';
        }
    }
    //create next button
    if ($numPages > 1) {
        if ($currentPage < $numPages) {
            $pages .= '<a href="add_litter.php?f=sire&r=1&l=' . $l . '&random=' . $random . '&st=' . ($st + $perPage) . '">' . _MA_PEDIGREE_NEXT . '</a>&nbsp;&nbsp';
        }
    }
    //query
    $queryString = 'SELECT * FROM ' . $GLOBALS['xoopsDB']->prefix('pedigree_tree') . " WHERE roft = '0' AND naam LIKE '" . $l . "%' ORDER BY naam LIMIT " . $st . ', ' . $perPage;
    $result      = $GLOBALS['xoopsDB']->query($queryString);

    $animal = new PedigreeAnimal();
    //test to find out how many user fields there are...
    $fields       = $animal->getNumOfFields();
    $numofcolumns = 1;
    $columns[]    = ['columnname' => 'Name'];
    for ($i = 0, $iMax = count($fields); $i < $iMax; ++$i) {
        $userField   = new Field($fields[$i], $animal->getConfig());
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
        'link'        => '<a href="add_litter.php?f=dam&random=' . $random . '&selsire=0">' . strtr(_MA_PEDIGREE_ADD_SIREUNKNOWN, ['[father]' => $moduleConfig['father']]) . '</a>',
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
            elseif (0 === strpos($row['user' . $x], 'http://')) {
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
            'link'        => '<a href="add_litter.php?f=dam&random=' . $random . '&selsire=' . $row['id'] . '">' . $name . '</a>',
            'colour'      => '',
            'number'      => '',
            'usercolumns' => $columnvalue
        ];
    }

    //add data to smarty template
    //assign dog
    $xoopsTpl->assign('dogs', $dogs);
    $xoopsTpl->assign('columns', $columns);
    $xoopsTpl->assign('numofcolumns', $numofcolumns);
    $xoopsTpl->assign('tsarray', PedigreeUtility::sortTable($numofcolumns));
    $xoopsTpl->assign('nummatch', strtr(_MA_PEDIGREE_ADD_SELSIRE, ['[father]' => $moduleConfig['father']]));
    $xoopsTpl->assign('pages', $pages);
}

function dam()
{
    global $xoopsTpl, $xoopsUser, $xoopsDB;
    $moduleDirName = basename(__DIR__);
    //get module configuration
    /** @var XoopsModuleHandler $moduleHandler */
    $moduleHandler = xoops_getHandler('module');
    $module        = $moduleHandler->getByDirname($moduleDirName);
    $configHandler = xoops_getHandler('config');
    $moduleConfig  = $configHandler->getConfigsByCat(0, $module->getVar('mid'));

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
    $random = Request::getInt('random', 0);
    $st     = Request::getInt('st', 0, 'GET');
    //make the redirect
    if (!isset($_GET['r'])) {
        //insert into pedigree_temp
        //      $query = 'UPDATE ' . $GLOBALS['xoopsDB']->prefix('pedigree_temp') . ' SET father =' . $_GET['selsire'] . ' WHERE id=' . $random;
        $query = 'UPDATE ' . $GLOBALS['xoopsDB']->prefix('pedigree_temp') . ' SET father =' . Request::getInt('selsire', 0, 'GET') . ' WHERE id=' . $random;
        $GLOBALS['xoopsDB']->queryF($query);
        redirect_header('add_litter.php?f=dam&random=' . $random . '&st=' . $st . '&r=1', 1, strtr(_MA_PEDIGREE_ADD_SIREOK, ['[mother]' => $moduleConfig['mother']]));
    }
    //find letter on which to start else set to 'a'
    //    if (isset($_GET['l'])) {
    //        $l = $_GET['l'];
    //    } else {
    //        $l = 'a';
    //    }
    $l = Request::getString('l', 'a', 'GET');
    //assign sire to the template
    $xoopsTpl->assign('sire', '1');
    //create list of males dog to select from
    //    $perPage = $moduleConfig['perpage'];
    $perPage = (int)$moduleConfig['perpage'];
    //count total number of dogs
    //  $numDog = 'SELECT id FROM ' . $GLOBALS['xoopsDB']->prefix('pedigree_tree') . " WHERE roft='1' and naam LIKE '" . $l . "%'";
    $numDog = 'SELECT id FROM ' . $GLOBALS['xoopsDB']->prefix('pedigree_tree') . " WHERE roft='1' AND naam LIKE '" . $GLOBALS['xoopsDB']->escape($l) . "%'";
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
    for ($i = 65; $i <= 90; ++$i) {
        if ($l == chr($i)) {
            $pages .= '<b><a href="add_litter.php?f=dam&r=1&random=' . $random . '&l=' . chr($i) . '">' . chr($i) . '</a></b>&nbsp;';
        } else {
            $pages .= '<a href="add_litter.php?f=dam&r=1&random=' . $random . '&l=' . chr($i) . '">' . chr($i) . '</a>&nbsp;';
        }
    }
    $pages .= '-&nbsp;';
    $pages .= '<a href="add_litter.php?f=dam&r=1&random=' . $random . '&l=Ã…">Ã…</a>&nbsp;';
    $pages .= '<a href="add_litter.php?f=dam&r=1&random=' . $random . '&l=Ã–">Ã–</a>&nbsp;';
    //create linebreak
    $pages .= '<br>';
    //create previous button
    if ($numPages > 1) {
        if ($currentPage > 1) {
            $pages .= '<a href="add_litter.php?f=dam&r=1&l=' . $l . '&random=' . $random . '&st=' . ($st - $perPage) . '">' . _MA_PEDIGREE_PREVIOUS . '</a>&nbsp;&nbsp';
        }
    }
    //create numbers
    for ($x = 1; $x < ($numPages + 1); ++$x) {
        //create line break after 20 number
        if (0 == ($x % 20)) {
            $pages .= '<br>';
        }
        if ($x != $currentPage) {
            $pages .= '<a href="add_litter.php?f=dam&r=1&l=' . $l . '&random=' . $random . '&st=' . ($perPage * ($x - 1)) . '">' . $x . '</a>&nbsp;&nbsp;';
        } else {
            $pages .= $x . '&nbsp;&nbsp';
        }
    }
    //create next button
    if ($numPages > 1) {
        if ($currentPage < $numPages) {
            $pages .= '<a href="add_litter.php?f=dam&r=1&l=' . $l . '&random=' . $random . '&st=' . ($st + $perPage) . '">' . _MA_PEDIGREE_NEXT . '</a>&nbsp;&nbsp';
        }
    }
    //query
    $queryString = 'SELECT * FROM ' . $GLOBALS['xoopsDB']->prefix('pedigree_tree') . " WHERE roft = '1' AND naam LIKE '" . $l . "%' ORDER BY naam LIMIT " . $st . ', ' . $perPage;
    $result      = $GLOBALS['xoopsDB']->query($queryString);

    $animal = new PedigreeAnimal();
    //test to find out how many user fields there are...
    $fields       = $animal->getNumOfFields();
    $numofcolumns = 1;
    $columns[]    = ['columnname' => 'Name'];
    for ($i = 0, $iMax = count($fields); $i < $iMax; ++$i) {
        $userField   = new Field($fields[$i], $animal->getConfig());
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
        'link'        => '<a href="add_litter.php?f=check&random=' . $random . '&seldam=0">' . strtr(_MA_PEDIGREE_ADD_DAMUNKNOWN, ['[mother]' => $moduleConfig['mother']]) . '</a>',
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
            elseif (0 === strpos($row['user' . $x], 'http://')) {
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
            'link'        => '<a href="add_litter.php?f=check&random=' . $random . '&seldam=' . $row['id'] . '">' . $name . '</a>',
            'colour'      => '',
            'number'      => '',
            'usercolumns' => $columnvalue
        ];
    }

    //add data to smarty template
    //assign dog
    $xoopsTpl->assign('dogs', $dogs);
    $xoopsTpl->assign('columns', $columns);
    $xoopsTpl->assign('numofcolumns', $numofcolumns);
    $xoopsTpl->assign('tsarray', PedigreeUtility::sortTable($numofcolumns));
    $xoopsTpl->assign('nummatch', strtr(_MA_PEDIGREE_ADD_SELDAM, ['[mother]' => $moduleConfig['mother']]));
    $xoopsTpl->assign('pages', $pages);
}

function check()
{
    global $xoopsTpl, $xoopsUser, $xoopsDB;
    $moduleDirName = basename(__DIR__);
    //get module configuration
    /** @var XoopsModuleHandler $moduleHandler */
    $moduleHandler = xoops_getHandler('module');
    $module        = $moduleHandler->getByDirname($moduleDirName);
    $configHandler = xoops_getHandler('config');
    $moduleConfig  = $configHandler->getConfigsByCat(0, $module->getVar('mid'));

    if (empty($random)) {
        $random = $_POST['random'];
    }
    if (isset($_GET['random'])) {
        $random = $_GET['random'];
    }
    //query
    $queryString = 'SELECT * FROM ' . $GLOBALS['xoopsDB']->prefix('pedigree_temp') . ' WHERE id = ' . $random;
    $result      = $GLOBALS['xoopsDB']->query($queryString);
    $seldam      = Request::getInt('seldam', 0, 'GET');
    while (false !== ($row = $GLOBALS['xoopsDB']->fetchArray($result))) {
        //pull data apart.
        if ('' !== $row['naam']) {
            $genders = explode(':', $row['roft']);
            $names   = explode(':', $row['naam']);
            for ($c = 1, $cMax = count($names); $c < $cMax; ++$c) {
                //              $query = 'INSERT INTO ' . $GLOBALS['xoopsDB']->prefix('pedigree_tree') . " VALUES ('','" . addslashes($names[$c]) . "','0','" . $row['id_breeder'] . "','" . $row['user'] . "','" . $genders[$c] . "','" . $_GET['seldam'] . "','" . $row['father'] . "','',''";
                $query = 'INSERT INTO '
                         . $GLOBALS['xoopsDB']->prefix('pedigree_tree')
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
                $animal = new PedigreeAnimal();
                //test to find out how many user fields there are..
                $fields = $animal->getNumOfFields();
                sort($fields);
                $usersql = '';
                for ($i = 0, $iMax = count($fields); $i < $iMax; ++$i) {
                    $userfields{$fields[$i]} = explode(':', $row['user' . $fields[$i]]);
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
    redirect_header('latest.php', 1, strtr(_MA_PEDIGREE_ADD_LIT_OK, ['[animalTypes]' => $moduleConfig['animalTypes']]));
}

//footer
include XOOPS_ROOT_PATH . '/footer.php';
