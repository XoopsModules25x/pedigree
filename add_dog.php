<?php
// -------------------------------------------------------------------------

use Xmf\Request;

//require_once  dirname(dirname(__DIR__)) . '/mainfile.php';
require_once __DIR__ . '/header.php';
//$moduleDirName = basename(__DIR__);
xoops_loadLanguage('main', $moduleDirName);
// Include any common code for this module.
require_once XOOPS_ROOT_PATH . '/modules/' . $xoopsModule->dirname() . '/include/common.php';
require_once XOOPS_ROOT_PATH . '/modules/' . $xoopsModule->dirname() . '/include/class_field.php';

$GLOBALS['xoopsOption']['template_main'] = 'pedigree_adddog.tpl';

include XOOPS_ROOT_PATH . '/header.php';
$xoopsTpl->assign('page_title', 'Pedigree database - Update details');

//check for access
$xoopsModule = XoopsModule::getByDirname($moduleDirName);
if (empty($xoopsUser)) {
    redirect_header('index.php', 3, _NOPERM . '<br>' . _MA_PEDIGREE_REGIST);
}

//create function variable from url
//if (isset($_GET['f'])) {
//    $f = $_GET['f'];
//} else {
//    $f = '';
//    addDog();
//}

$f = Request::getString('f', '', 'GET');

//if ($f === 'checkName') {
//    checkName();
//}
//if ($f === 'sire') {
//    sire();
//}
//if ($f === 'dam') {
//    dam();
//}
//if ($f === 'check') {
//    check();
//}

if (empty($f)) {
    addDog();
} elseif ('checkName' === $f) {
    checkName();
} elseif ('sire' === $f) {
    sire();
} elseif ('dam' === $f) {
    dam();
} elseif ('check' === $f) {
    check();
}

function addDog()
{
    global $xoopsTpl, $xoopsUser, $xoopsDB;
    $moduleDirName = basename(__DIR__);
    //get module configuration
    /** @var XoopsModuleHandler $moduleHandler */
    $moduleHandler = xoops_getHandler('module');
    $module        = $moduleHandler->getByDirname($moduleDirName);
    $configHandler = xoops_getHandler('config');
    $moduleConfig  = $configHandler->getConfigsByCat(0, $module->getVar('mid'));

    //check for access
    if (empty($GLOBALS['xoopsUser']) || !($GLOBALS['xoopsUser'] instanceof \XoopsUser)) {
        redirect_header('javascript:history.go(-1)', 3, _NOPERM . '<br>' . _MA_PEDIGREE_REGIST);
    }
    if (0 == $xoopsUser->getVar('uid')) {
        redirect_header('javascript:history.go(-1)', 3, _NOPERM . '<br>' . _MA_PEDIGREE_REGIST);
    }
    //create form
    include XOOPS_ROOT_PATH . '/class/xoopsformloader.php';
    $form = new \XoopsThemeForm(strtr(_MA_PEDIGREE_ADD_DOG, ['[animalType]' => $moduleConfig['animalType']]), 'dogname', 'add_dog.php?f=checkName', 'post', true);
    $form->addElement(new \XoopsFormHiddenToken($name = 'XOOPS_TOKEN_REQUEST', $timeout = 360));
    //create random value
    $random = (mt_rand() % 10000);
    $form->addElement(new \XoopsFormHidden('random', $random));
    //find userid
    $form->addElement(new \XoopsFormHidden('user', $xoopsUser->getVar('uid')));

    //name
    $form->addElement(new \XoopsFormText('<b>' . _MA_PEDIGREE_FLD_NAME . '</b>', 'naam', $size = 50, $maxsize = 255, $value = ''));
    $string = strtr(_MA_PEDIGREE_FLD_NAME_EX, ['[animalType]' => $moduleConfig['animalType']]);
    $form->addElement(new \XoopsFormLabel(_MA_PEDIGREE_EXPLAIN, $string));

    //submit button
    $form->addElement(new \XoopsFormButton('', 'button_id', strtr(_MA_PEDIGREE_ADD_DATA, ['[animalType]' => $moduleConfig['animalType']]), 'submit'));

    //add data (form) to smarty template
    $xoopsTpl->assign('form', $form->render());
}

function checkName()
{
    //configure global variables
    global $xoopsTpl, $xoopsDB, $xoopsUser;
    $moduleDirName = basename(__DIR__);
    //get module configuration
    /** @var XoopsModuleHandler $moduleHandler */
    $moduleHandler = xoops_getHandler('module');
    $module        = $moduleHandler->getByDirname($moduleDirName);
    $configHandler = xoops_getHandler('config');
    $moduleConfig  = $configHandler->getConfigsByCat(0, $module->getVar('mid'));

    //$name = $_POST['naam'];
    $name = Request::getString('naam', '', 'POST');
    //query
    //$queryString = 'SELECT * FROM ' . $GLOBALS['xoopsDB']->prefix('pedigree_tree') . " WHERE naam LIKE'%" . $name . "%' ORDER BY naam";
    $queryString = 'SELECT * FROM ' . $GLOBALS['xoopsDB']->prefix('pedigree_tree') . " WHERE naam LIKE'%" . $GLOBALS['xoopsDB']->escape($name) . "%' ORDER BY naam";
    $result      = $GLOBALS['xoopsDB']->query($queryString);
    $numResults  = $GLOBALS['xoopsDB']->getRowsNum($result);
    if ($numResults >= 1 && !isset($_GET['r'])) {
        //create form
        include XOOPS_ROOT_PATH . '/class/xoopsformloader.php';
        $form = new \XoopsThemeForm(strtr(_MA_PEDIGREE_ADD_DOG, ['[animalType]' => $moduleConfig['animalType']]), 'dogname', 'add_dog.php?f=checkName&r=1', 'post', true);
        //other elements
        $form->addElement(new \XoopsFormHiddenToken($name = 'XOOPS_TOKEN_REQUEST', $timeout = 360));
        $form->addElement(new \XoopsFormHidden('naam', $_POST['naam']));
        $form->addElement(new \XoopsFormHidden('user', $xoopsUser->getVar('uid')));
        while (false !== ($row = $GLOBALS['xoopsDB']->fetchBoth($result))) {
            //name
            $form->addElement(new \XoopsFormLabel('<b>' . _MA_PEDIGREE_FLD_NAME . '</b>', '<a href="dog.php?id=' . $row['id'] . '">' . stripslashes($row['naam']) . '</a>'));
        }
        $form->addElement(new \XoopsFormLabel(_MA_PEDIGREE_EXPLAIN, strtr(_MA_PEDIGREE_ADD_KNOWN, ['[animalTypes]' => $moduleConfig['animalTypes']])));
        //submit button
        $form->addElement(new \XoopsFormButton('', 'button_id', strtr(_MA_PEDIGREE_ADD_KNOWNOK, ['[animalType]' => $moduleConfig['animalType']]), 'submit'));
        //add data (form) to smarty template
        $xoopsTpl->assign('form', $form->render());
    } else {
        //create form
        include XOOPS_ROOT_PATH . '/class/xoopsformloader.php';
        $form = new \XoopsThemeForm(strtr(_MA_PEDIGREE_ADD_DOG, ['[animalType]' => $moduleConfig['animalType']]), 'dogname', 'add_dog.php?f=sire', 'post', true);
        //added to handle upload
        $form->setExtra("enctype='multipart/form-data'");
        $form->addElement(new \XoopsFormHiddenToken($name = 'XOOPS_TOKEN_REQUEST', $timeout = 360));
        //create random value
        $random = (mt_rand() % 10000);
        $form->addElement(new \XoopsFormHidden('random', $random));
        $form->addElement(new \XoopsFormHidden('naam', htmlspecialchars($_POST['naam'], ENT_QUOTES)));
        //find userid from previous form
        $form->addElement(new \XoopsFormHidden('user', $_POST['user']));

        //name
        $form->addElement(new \XoopsFormLabel('<b>' . _MA_PEDIGREE_FLD_NAME . '</b>', stripslashes($_POST['naam'])));
        //gender
        $gender_radio = new \XoopsFormRadio('<b>' . _MA_PEDIGREE_FLD_GEND . '</b>', 'roft', $value = '0');
        $gender_radio->addOptionArray([
                                          '0' => strtr(_MA_PEDIGREE_FLD_MALE, ['[male]' => $moduleConfig['male']]),
                                          '1' => strtr(_MA_PEDIGREE_FLD_FEMA, ['[female]' => $moduleConfig['female']])
                                      ]);
        $form->addElement($gender_radio);
        if ('1' == $moduleConfig['ownerbreeder']) {
            //breeder
            $breeder_select = new \XoopsFormSelect('<b>' . _MA_PEDIGREE_FLD_BREE . '</b>', $name = 'id_breeder', $value = '0', $size = 1, $multiple = false);
            $queryfok       = 'SELECT id, lastname, firstname FROM ' . $GLOBALS['xoopsDB']->prefix('pedigree_owner') . ' ORDER BY lastname';
            $resfok         = $GLOBALS['xoopsDB']->query($queryfok);
            $breeder_select->addOption('0', $name = _MA_PEDIGREE_UNKNOWN);
            while (false !== ($rowfok = $GLOBALS['xoopsDB']->fetchArray($resfok))) {
                $breeder_select->addOption($rowfok['id'], $name = $rowfok['lastname'] . ', ' . $rowfok['firstname']);
            }
            $form->addElement($breeder_select);
            $form->addElement(new \XoopsFormLabel(_MA_PEDIGREE_EXPLAIN, strtr(_MA_PEDIGREE_FLD_BREE_EX, ['[animalType]' => $moduleConfig['animalType']])));

            //owner
            $owner_select = new \XoopsFormSelect('<b>' . _MA_PEDIGREE_FLD_OWNE . '</b>', $name = 'id_owner', $value = '0', $size = 1, $multiple = false);
            $queryfok     = 'SELECT id, lastname, firstname FROM ' . $GLOBALS['xoopsDB']->prefix('pedigree_owner') . ' ORDER BY lastname';
            $resfok       = $GLOBALS['xoopsDB']->query($queryfok);
            $owner_select->addOption('0', $name = _MA_PEDIGREE_UNKNOWN);
            while (false !== ($rowfok = $GLOBALS['xoopsDB']->fetchArray($resfok))) {
                $owner_select->addOption($rowfok['id'], $name = $rowfok['lastname'] . ', ' . $rowfok['firstname']);
            }
            $form->addElement($owner_select);
            $form->addElement(new \XoopsFormLabel(_MA_PEDIGREE_EXPLAIN, strtr(_MA_PEDIGREE_FLD_OWNE_EX, ['[animalType]' => $moduleConfig['animalType']])));
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

        for ($i = 0, $iMax = count($fields); $i < $iMax; ++$i) {
            $userField   = new Field($fields[$i], $animal->getConfig());
            $fieldType   = $userField->getSetting('FieldType');
            $fieldObject = new $fieldType($userField, $animal);
            if ($userField->isActive() && !$userField->isLocked()) {
                $newEntry = $fieldObject->newField();
                $form->addElement($newEntry);
            }
            unset($newEntry);
        }

        //submit button
        $form->addElement(new \XoopsFormButton('', 'button_id', strtr(_MA_PEDIGREE_ADD_SIRE, ['[father]' => $moduleConfig['father']]), 'submit'));

        //add data (form) to smarty template
        $xoopsTpl->assign('form', $form->render());
    }
}

function sire()
{
    global $xoopsTpl, $xoopsUser, $xoopsDB;
    $moduleDirName = basename(__DIR__);
    //get module configuration
    /** @var XoopsModuleHandler $moduleHandler */
    $moduleHandler = xoops_getHandler('module');
    $module        = $moduleHandler->getByDirname($moduleDirName);
    $configHandler = xoops_getHandler('config');
    $moduleConfig  = $configHandler->getConfigsByCat(0, $module->getVar('mid'));
    $empty         = []; // an empty array

    //check for access
    if (empty($GLOBALS['xoopsUser']) || !($GLOBALS['xoopsUser'] instanceof \XoopsUser)) {
        redirect_header('javascript:history.go(-1)', 3, _NOPERM . '<br>' . _MA_PEDIGREE_REGIST);
    }
    $user = isset($_POST['user']) ? $_POST['user'] : null;
    if (empty($random)) {
        //        $random = isset($_POST['random']) ? $_POST['random'] : null;
        $random = Request::getString('random', null, 'POST');
    }
    //    if (isset($_GET['random'])) {
    //        $random = $_GET['random'];
    //    }

    $random = Request::getString('random', $random, 'GET');

    if (empty($st)) {
        $st = 0;
    }
    if (isset($_GET['st'])) {
        $st = $_GET['st'];
    }
    $name = isset($_POST['naam']) ? $_POST['naam'] : null;
    $roft = isset($_POST['roft']) ? $_POST['roft'] : null;

    $id_owner   = isset($_POST['id_owner']) ? $_POST['id_owner'] : null;
    $id_breeder = isset($_POST['id_breeder']) ? $_POST['id_breeder'] : null;

    $pictureField = isset($_FILES['photo']) ? $_FILES['photo']['name'] : null; // $_FILES['photo']['name'];
    if (empty($pictureField) || '' == $pictureField) {
        $foto = '';
    } else {
        $foto = PedigreeUtility::uploadPicture(0);
    }
    $numPictureField = 1;

    //make the redirect
    if (!isset($_GET['r'])) {
        if ('' == $_POST['naam']) {
            redirect_header('add_dog.php', 1, _MA_PEDIGREE_ADD_NAMEPLZ);
        }
        //create animal object
        $animal = new Pedigree\Animal();
        //test to find out how many user fields there are..
        $fields = $animal->getNumOfFields();
        sort($fields); //sort by ID not by order
        $usersql = '';
        for ($i = 0, $iMax = count($fields); $i < $iMax; ++$i) {
            $userField   = new Field($fields[$i], $animal->getConfig());
            $fieldType   = $userField->getSetting('FieldType');
            $fieldObject = new $fieldType($userField, $animal);
            if ($userField->isActive()) {
                //check if _FILES variable exists for user picturefield
                $currentfield = 'user' . $fields[$i];
                $pictureField = $_FILES[$currentfield]['name'];
                if ('Picture' === $fieldType && (!empty($pictureField) || '' != $pictureField)) {
                    $userpicture = PedigreeUtility::uploadPicture($numPictureField);
                    $usersql     .= ",'" . $userpicture . "'";
                    ++$numPictureField;
                } elseif ($userField->isLocked()) {
                    //userfield is locked, substitute default value
                    $usersql .= ",'" . $userField->defaultvalue . "'";
                } else {
                    //echo $fieldType.":".$i.":".$fields[$i]."<br>";
                    $usersql .= ",'" . PedigreeUtility::unHtmlEntities($_POST['user' . $fields[$i]]) . "'";
                }
            } else {
                $usersql .= ",''";
            }
            //echo $fields[$i]."<br>";
        }

        //insert into pedigree_temp
        //        $query = 'INSERT INTO ' . $GLOBALS['xoopsDB']->prefix('pedigree_temp') . " VALUES ('" . $random . "','" . PedigreeUtility::unHtmlEntities($name) . "','" . $id_owner . "','" . $id_breeder . "','" . $user . "','" . $roft . "','','','" . $foto . "', ''" . $usersql . ')';
        $query = 'INSERT INTO '
                 . $GLOBALS['xoopsDB']->prefix('pedigree_temp')
                 . " VALUES ('"
                 . $GLOBALS['xoopsDB']->escape($random)
                 . "','"
                 . $GLOBALS['xoopsDB']->escape(PedigreeUtility::unHtmlEntities($name))
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
        redirect_header('add_dog.php?f=sire&random=' . $random . '&st=' . $st . '&r=1&l=a', 1, strtr(_MA_PEDIGREE_ADD_SIREPLZ, ['[father]' => $moduleConfig['father']]));
    }
    //find letter on which to start else set to 'a'
    $l = Request::getString('l', 'A', 'GET');
    //    if (isset($_GET['l'])) {
    //        $l = $_GET['l'];
    //    } else {
    //        $l = 'a';
    //    }
    //assign sire to template
    $xoopsTpl->assign('sire', '1');
    //create list of males dog to select from
    $perPage = $moduleConfig['perpage'];
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
            $pages .= '<b><a href="add_dog.php?f=sire&r=1&random=' . $random . '&l=' . chr($i) . '">' . chr($i) . '</a></b>&nbsp;';
        } else {
            $pages .= '<a href="add_dog.php?f=sire&r=1&random=' . $random . '&l=' . chr($i) . '">' . chr($i) . '</a>&nbsp;';
        }
    }
    $pages .= '-&nbsp;';
    $pages .= '<a href="add_dog.php?f=sire&r=1&random=' . $random . '&l=Ã…">Ã…</a>&nbsp;';
    $pages .= '<a href="add_dog.php?f=sire&r=1&random=' . $random . '&l=Ã–">Ã–</a>&nbsp;';
    //create linebreak
    $pages .= '<br>';
    //create previous button
    if ($numPages > 1) {
        if ($currentPage > 1) {
            $pages .= '<a href="add_dog.php?f=sire&r=1&l=' . $l . '&random=' . $random . '&st=' . ($st - $perPage) . '">' . _MA_PEDIGREE_PREVIOUS . '</a>&nbsp;&nbsp';
        }
    }
    //create numbers
    for ($x = 1; $x < ($numPages + 1); ++$x) {
        //create line break after 20 numbers
        if (0 == ($x % 20)) {
            $pages .= '<br>';
        }
        if ($x != $currentPage) {
            $pages .= '<a href="add_dog.php?f=sire&r=1&l=' . $l . '&random=' . $random . '&st=' . ($perPage * ($x - 1)) . '">' . $x . '</a>&nbsp;&nbsp;';
        } else {
            $pages .= $x . '&nbsp;&nbsp';
        }
    }
    //create next button
    if ($numPages > 1) {
        if ($currentPage < $numPages) {
            $pages .= '<a href="add_dog.php?f=sire&r=1&l=' . $l . '&random=' . $random . '&st=' . ($st + $perPage) . '">' . _MA_PEDIGREE_NEXT . '</a>&nbsp;&nbsp';
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
        'link'        => '<a href="add_dog.php?f=dam&random=' . $random . '&selsire=0">' . strtr(_MA_PEDIGREE_ADD_SIREUNKNOWN, ['[father]' => $moduleConfig['father']]) . '</a>',
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
            'link'        => '<a href="add_dog.php?f=dam&random=' . $random . '&selsire=' . $row['id'] . '">' . $name . '</a>',
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
    //assign links
    $xoopsTpl->assign('nummatch', strtr(_MA_PEDIGREE_ADD_SELSIRE, ['[father]' => $moduleConfig['father']]));
    $xoopsTpl->assign('pages', $pages);

    //mb =========== FATHER LETTERS =============================
    $myObject = Pedigree\Helper::getInstance();
    $roft     = 0;
    //    $criteria     = $myObject->getHandler('tree')->getActiveCriteria($roft);
    $activeObject = 'tree';
    $name         = 'naam';
    $number1      = '1';
    $number2      = '0';
    //    $link         = "virtual.php?r={$number1}&st={$number2}&l=";
    $link = "add_dog.php?f=sire&r=1&random={$random}&l=";

    //    http://localhost/257belgi/modules/pedigree/virtual.php?f=dam&selsire=35277

    $link2 = '';

    $criteria = $myObject->getHandler('tree')->getActiveCriteria($roft);
    //    $criteria->setGroupby('UPPER(LEFT(' . $name . ',1))');

    $fatherArray['letters'] = PedigreeUtility::lettersChoice($myObject, $activeObject, $criteria, $name, $link, $link2);
    //$catarray['toolbar']          = pedigree_toolbar();
    $xoopsTpl->assign('fatherArray', $fatherArray);

    //mb ========================================
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
    $empty         = []; // an empty array

    //check for access
    $xoopsModule = XoopsModule::getByDirname($moduleDirName);
    if (empty($GLOBALS['xoopsUser']) || !($GLOBALS['xoopsUser'] instanceof \XoopsUser)) {
        redirect_header('javascript:history.go(-1)', 3, _NOPERM . '<br>' . _MA_PEDIGREE_REGIST);
    }
    //    if (empty($random)) {
    //$random = isset($_POST['random']) ? $_POST['random'] : null;
    //}
    //if (isset($_GET['random'])) {
    //$random = $_GET['random'];
    //}
    //if (empty($st)) {
    //$st = 0;
    //}
    //if (isset($_GET['st'])) {
    //$st = $_GET['st'];
    //}
    $random = Request::getInt('random', 0);
    $st     = Request::getInt('st', 0, 'GET');
    //find letter on which to start else set to 'a'
    //    if (isset($_GET['l'])) {
    //        $l = $_GET['l'];
    //    } else {
    //        $l = 'a';
    //    }
    $l = Request::getString('l', 'a', 'GET');
    //make the redirect
    if (!isset($_GET['r'])) {
        //insert into pedigree_temp
        //        $query = 'UPDATE ' . $GLOBALS['xoopsDB']->prefix('pedigree_temp') . ' SET father =' . $_GET['selsire'] . ' WHERE id=' . $random;
        //        $GLOBALS['xoopsDB']->queryF($query);
        $query = 'UPDATE ' . $GLOBALS['xoopsDB']->prefix('pedigree_temp') . ' SET father =' . Request::getInt('selsire', 0, 'GET') . ' WHERE id=' . $random;
        $GLOBALS['xoopsDB']->queryF($query);
        redirect_header('add_dog.php?f=dam&random=' . $random . '&st=' . $st . '&r=1&l=a', 1, strtr(_MA_PEDIGREE_ADD_SIREOK, ['[mother]' => $moduleConfig['mother']]));
    }

    $xoopsTpl->assign('sire', '1');
    //create list of males dog to select from
    $perPage = $moduleConfig['perpage'];
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
            $pages .= '<b><a href="add_dog.php?f=dam&r=1&random=' . $random . '&l=' . chr($i) . '">' . chr($i) . '</a></b>&nbsp;';
        } else {
            $pages .= '<a href="add_dog.php?f=dam&r=1&random=' . $random . '&l=' . chr($i) . '">' . chr($i) . '</a>&nbsp;';
        }
    }
    $pages .= '-&nbsp;';
    $pages .= '<a href="add_dog.php?f=dam&r=1&random=' . $random . '&l=Ã…">Ã…</a>&nbsp;';
    $pages .= '<a href="add_dog.php?f=dam&r=1&random=' . $random . '&l=Ã–">Ã–</a>&nbsp;';
    $pages .= '<br>';
    //create previous button
    if ($numPages > 1) {
        if ($currentPage > 1) {
            $pages .= '<a href="add_dog.php?f=dam&r=1&l=' . $l . '&random=' . $random . '&st=' . ($st - $perPage) . '">' . _MA_PEDIGREE_PREVIOUS . '</a>&nbsp;&nbsp';
        }
    }
    //create numbers
    for ($x = 1; $x < ($numPages + 1); ++$x) {
        //create line break after 20 number
        if (0 == ($x % 20)) {
            $pages .= '<br>';
        }
        if ($x != $currentPage) {
            $pages .= '<a href="add_dog.php?f=dam&r=1&l=' . $l . '&random=' . $random . '&st=' . ($perPage * ($x - 1)) . '">' . $x . '</a>&nbsp;&nbsp;';
        } else {
            $pages .= $x . '&nbsp;&nbsp';
        }
    }
    //create next button
    if ($numPages > 1) {
        if ($currentPage < $numPages) {
            $pages .= '<a href="add_dog.php?f=dam&l=' . $l . '&r=1&random=' . $random . '&st=' . ($st + $perPage) . '">' . _MA_PEDIGREE_NEXT . '</a>&nbsp;&nbsp;';
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
        'link'        => '<a href="add_dog.php?f=check&random=' . $random . '&seldam=0">' . strtr(_MA_PEDIGREE_ADD_DAMUNKNOWN, ['[mother]' => $moduleConfig['mother']]) . '</a>',
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
            'link'        => '<a href="add_dog.php?f=check&random=' . $random . '&seldam=' . $row['id'] . '">' . $name . '</a>',
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

    //mb ========= MOTHER LETTERS===============================
    $myObject = Pedigree\Helper::getInstance();
    $roft     = 1;
    //    $criteria     = $myObject->getHandler('tree')->getActiveCriteria($roft);
    $activeObject = 'tree';
    $name         = 'naam';
    $number1      = '1';
    $number2      = '0';
    $link         = "add_dog.php?f=dam&r=1&random={$random}&l=";
    $link2        = '';

    $criteria = $myObject->getHandler('tree')->getActiveCriteria($roft);
    //    $criteria->setGroupby('UPPER(LEFT(' . $name . ',1))');

    $motherArray['letters'] = PedigreeUtility::lettersChoice($myObject, $activeObject, $criteria, $name, $link, $link2);
    //$catarray['toolbar']          = pedigree_toolbar();
    $xoopsTpl->assign('motherArray', $motherArray);

    //mb ========================================
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

    //check for access
    $xoopsModule = XoopsModule::getByDirname($moduleDirName);
    if (empty($xoopsUser)) {
        redirect_header('index.php', 3, _NOPERM . '<br>' . _MA_PEDIGREE_REGIST);
    }
    if (empty($random)) {
        $random = $_POST['random'];
    }
    if (isset($_GET['random'])) {
        $random = $_GET['random'];
    }

    //query
    $queryString = 'SELECT * FROM ' . $GLOBALS['xoopsDB']->prefix('pedigree_temp') . ' WHERE id = ' . $random;
    $result      = $GLOBALS['xoopsDB']->query($queryString);
    while (false !== ($row = $GLOBALS['xoopsDB']->fetchArray($result))) {
        //create animal object
        $animal = new Pedigree\Animal();
        //test to find out how many user fields there are..
        $fields = $animal->getNumOfFields();
        sort($fields);
        $usersql = '';
        for ($i = 0, $iMax = count($fields); $i < $iMax; ++$i) {
            $userField   = new Field($fields[$i], $animal->getConfig());
            $fieldType   = $userField->getSetting('FieldType');
            $fieldObject = new $fieldType($userField, $animal);
            if ($userField->isActive()) {
                $usersql .= ",'" . addslashes($row['user' . $fields[$i]]) . "'";
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
    }
    $sqlQuery = 'DELETE FROM ' . $GLOBALS['xoopsDB']->prefix('pedigree_temp') . " WHERE id='" . $random . "'";
    $GLOBALS['xoopsDB']->queryF($sqlQuery);
    redirect_header('latest.php', 1, strtr(_MA_PEDIGREE_ADD_OK, ['[animalType]' => $moduleConfig['animalType']]));
}

//footer
include XOOPS_ROOT_PATH . '/footer.php';
