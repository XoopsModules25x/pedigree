<?php
// -------------------------------------------------------------------------

require_once dirname(dirname(__DIR__)) . '/mainfile.php';

/*
if (file_exists(XOOPS_ROOT_PATH . "/modules/" . $xoopsModule->dirname() . "/language/" . $xoopsConfig['language'] . "/main.php")) {
    require_once XOOPS_ROOT_PATH . "/modules/" . $xoopsModule->dirname() . "/language/" . $xoopsConfig['language'] . "/main.php";
} else {
    include_once XOOPS_ROOT_PATH . "/modules/" . $xoopsModule->dirname() . "/language/english/main.php";
}
*/

xoops_loadLanguage('main', basename(dirname(__DIR__)));

// Include any common code for this module.
require_once(XOOPS_ROOT_PATH . "/modules/" . $xoopsModule->dirname() . "/include/functions.php");
require_once(XOOPS_ROOT_PATH . "/modules/" . $xoopsModule->dirname() . "/include/class_field.php");

$xoopsOption['template_main'] = "pedigree_adddog.html";

include XOOPS_ROOT_PATH . '/header.php';
$xoopsTpl->assign('page_title', "Pedigree database - Update details");

//check for access
$xoopsModule =& XoopsModule::getByDirname("pedigree");
if (empty($xoopsUser)) {
    redirect_header("index.php", 3, _NOPERM . "<br />" . _MA_PEDIGREE_REGIST);
    exit();
}

//create function variable from url
if (isset($_GET['f'])) {
    $f = $_GET['f'];
} else {
    $f = "";
    adddog();
}
if ($f == "checkname") {
    checkname();
}
if ($f == "sire") {
    sire();
}
if ($f == "dam") {
    dam();
}
if ($f == "check") {
    check();
}

function adddog()
{
    global $xoopsTpl, $xoopsUser, $xoopsDB;

    //get module configuration
    $module_handler =& xoops_gethandler('module');
    $module         =& $module_handler->getByDirname("pedigree");
    $config_handler =& xoops_gethandler('config');
    $moduleConfig   =& $config_handler->getConfigsByCat(0, $module->getVar('mid'));

    //check for access
    if (empty($xoopsUser)) {
        redirect_header("javascript:history.go(-1)", 3, _NOPERM . "<br />" . _MA_PEDIGREE_REGIST);
        exit();
    }
    if ($xoopsUser->getVar("uid") == 0) {
        redirect_header("javascript:history.go(-1)", 3, _NOPERM . "<br />" . _MA_PEDIGREE_REGIST);
        exit();
    }
    //create form
    include XOOPS_ROOT_PATH . "/class/xoopsformloader.php";
    $form = new XoopsThemeForm(strtr(_MA_PEDIGREE_ADD_DOG, array('[animalType]' => $moduleConfig['animalType'])), 'dogname', 'add_dog.php?f=checkname', 'POST');
    $form->addElement(new XoopsFormHiddenToken($name = 'XOOPS_TOKEN_REQUEST', $timeout = 360));
    //create random value
    $random = (rand() % 10000);
    $form->addElement(new XoopsFormHidden('random', $random));
    //find userid
    $form->addElement(new XoopsFormHidden('user', $xoopsUser->getVar("uid")));

    //name
    $form->addElement(new XoopsFormText("<b>" . _MA_PEDIGREE_FLD_NAME . "</b>", 'NAAM', $size = 50, $maxsize = 255, $value = ''));
    $string = strtr(_MA_PEDIGREE_FLD_NAME_EX, array('[animalType]' => $moduleConfig['animalType']));
    $form->addElement(new XoopsFormLabel(_MA_PEDIGREE_EXPLAIN, $string));

    //submit button
    $form->addElement(new XoopsFormButton('', 'button_id', strtr(_MA_PEDIGREE_ADD_DATA, array('[animalType]' => $moduleConfig['animalType'])), 'submit'));

    //add data (form) to smarty template
    $xoopsTpl->assign("form", $form->render());
}

function checkname()

{
    //configure global variables
    global $xoopsTpl, $xoopsDB, $xoopsUser;

    //get module configuration
    $module_handler =& xoops_gethandler('module');
    $module         =& $module_handler->getByDirname("pedigree");
    $config_handler =& xoops_gethandler('config');
    $moduleConfig   =& $config_handler->getConfigsByCat(0, $module->getVar('mid'));

    $name = $_POST['NAAM'];
    //query
    $queryString = "SELECT * from " . $xoopsDB->prefix("pedigree_tree") . " WHERE NAAM LIKE'%" . $name . "%' ORDER BY NAAM";
    $result      = $xoopsDB->query($queryString);
    $numresults  = $xoopsDB->getRowsNum($result);
    if ($numresults >= 1 && !(isset($_GET['r']))) {
        //create form
        include XOOPS_ROOT_PATH . "/class/xoopsformloader.php";
        $form = new XoopsThemeForm(strtr(_MA_PEDIGREE_ADD_DOG, array('[animalType]' => $moduleConfig['animalType'])), 'dogname', 'add_dog.php?f=checkname&r=1', 'POST');
        //other elements
        $form->addElement(new XoopsFormHiddenToken($name = 'XOOPS_TOKEN_REQUEST', $timeout = 360));
        $form->addElement(new XoopsFormHidden('NAAM', $_POST['NAAM']));
        $form->addElement(new XoopsFormHidden('user', $xoopsUser->getVar("uid")));
        while ($row = $xoopsDB->fetchArray($result)) {
            //name
            $form->addElement(new XoopsFormLabel("<b>" . _MA_PEDIGREE_FLD_NAME . "</b>", "<a href=\"dog.php?id=" . $row['ID'] . "\">" . stripslashes($row['NAAM']) . "</a>"));
        }
        $form->addElement(new XoopsFormLabel(_MA_PEDIGREE_EXPLAIN, strtr(_MA_PEDIGREE_ADD_KNOWN, array('[animalTypes]' => $moduleConfig['animalTypes']))));
        //submit button
        $form->addElement(new XoopsFormButton('', 'button_id', strtr(_MA_PEDIGREE_ADD_KNOWNOK, array('[animalType]' => $moduleConfig['animalType'])), 'submit'));
        //add data (form) to smarty template
        $xoopsTpl->assign("form", $form->render());
    } else {
        //create form
        include XOOPS_ROOT_PATH . "/class/xoopsformloader.php";
        $form = new XoopsThemeForm(strtr(_MA_PEDIGREE_ADD_DOG, array('[animalType]' => $moduleConfig['animalType'])), 'dogname', 'add_dog.php?f=sire', 'POST');
        //added to handle upload
        $form->setExtra("enctype='multipart/form-data'");
        $form->addElement(new XoopsFormHiddenToken($name = 'XOOPS_TOKEN_REQUEST', $timeout = 360));
        //create random value
        $random = (rand() % 10000);
        $form->addElement(new XoopsFormHidden('random', $random));
        $form->addElement(new XoopsFormHidden('NAAM', htmlspecialchars($_POST['NAAM'], ENT_QUOTES)));
        //find userid from previous form
        $form->addElement(new XoopsFormHidden('user', $_POST['user']));

        //name
        $form->addElement(new XoopsFormLabel("<b>" . _MA_PEDIGREE_FLD_NAME . "</b>", stripslashes($_POST['NAAM'])));
        //gender
        $gender_radio = new XoopsFormRadio("<b>" . _MA_PEDIGREE_FLD_GEND . "</b>", 'roft', $value = '0');
        $gender_radio->addOptionArray(
            array('0' => strtr(_MA_PEDIGREE_FLD_MALE, array('[male]' => $moduleConfig['male'])), '1' => strtr(_MA_PEDIGREE_FLD_FEMA, array('[female]' => $moduleConfig['female'])))
        );
        $form->addElement($gender_radio);
        if ($moduleConfig['ownerbreeder'] == '1') {
            //breeder
            $breeder_select = new XoopsFormSelect("<b>" . _MA_PEDIGREE_FLD_BREE . "</b>", $name = "id_breeder", $value = '0', $size = 1, $multiple = false);
            $queryfok       = "SELECT ID, lastname, firstname from " . $xoopsDB->prefix("pedigree_owner") . " ORDER BY lastname";
            $resfok         = $xoopsDB->query($queryfok);
            $breeder_select->addOption('0', $name = _MA_PEDIGREE_UNKNOWN, $disabled = false);
            while ($rowfok = $xoopsDB->fetchArray($resfok)) {
                $breeder_select->addOption($rowfok['ID'], $name = $rowfok['lastname'] . ", " . $rowfok['firstname'], $disabled = false);
            }
            $form->addElement($breeder_select);
            $form->addElement(new XoopsFormLabel(_MA_PEDIGREE_EXPLAIN, strtr(_MA_PEDIGREE_FLD_BREE_EX, array('[animalType]' => $moduleConfig['animalType']))));

            //owner
            $owner_select = new XoopsFormSelect("<b>" . _MA_PEDIGREE_FLD_OWNE . "</b>", $name = "id_owner", $value = '0', $size = 1, $multiple = false);
            $queryfok     = "SELECT ID, lastname, firstname from " . $xoopsDB->prefix("pedigree_owner") . " ORDER BY lastname";
            $resfok       = $xoopsDB->query($queryfok);
            $owner_select->addOption('0', $name = _MA_PEDIGREE_UNKNOWN, $disabled = false);
            while ($rowfok = $xoopsDB->fetchArray($resfok)) {
                $owner_select->addOption($rowfok['ID'], $name = $rowfok['lastname'] . ", " . $rowfok['firstname'], $disabled = false);
            }
            $form->addElement($owner_select);
            $form->addElement(new XoopsFormLabel(_MA_PEDIGREE_EXPLAIN, strtr(_MA_PEDIGREE_FLD_OWNE_EX, array('[animalType]' => $moduleConfig['animalType']))));
        }
        //picture
        $max_imgsize = 1024000;
        $img_box     = new XoopsFormFile("Image", "photo", $max_imgsize);
        $img_box->setExtra("size ='50'");
        $form->addElement($img_box);

        //create animal object
        $animal = new Animal();
        //test to find out how many user fields there are..
        $fields = $animal->numoffields();

        for ($i = 0; $i < count($fields); ++$i) {
            $userfield   = new Field($fields[$i], $animal->getconfig());
            $fieldType   = $userfield->getSetting("FieldType");
            $fieldobject = new $fieldType($userfield, $animal);
            if ($userfield->active() && !$userfield->isLocked()) {
                $newentry = $fieldobject->newField();
                $form->addElement($newentry);
            }
            unset($newentry);
        }

        //submit button
        $form->addElement(new XoopsFormButton('', 'button_id', strtr(_MA_PEDIGREE_ADD_SIRE, array('[father]' => $moduleConfig['father'])), 'submit'));

        //add data (form) to smarty template
        $xoopsTpl->assign("form", $form->render());
    }
}

function sire()
{
    global $xoopsTpl, $xoopsUser, $xoopsDB;

    //get module configuration
    $module_handler =& xoops_gethandler('module');
    $module         =& $module_handler->getByDirname("pedigree");
    $config_handler =& xoops_gethandler('config');
    $moduleConfig   =& $config_handler->getConfigsByCat(0, $module->getVar('mid'));
    $empty          = array(); // an empty array

    //check for access
    if (empty($xoopsUser)) {
        redirect_header("javascript:history.go(-1)", 3, _NOPERM . "<br />" . _MA_PEDIGREE_REGIST);
        exit();
    }
    $user = isset($_POST['user']) ? $_POST['user'] : null;
    if (empty($random)) {
        $random = isset($_POST['random']) ? $_POST['random'] : null;
    }
    if (isset($_GET['random'])) {
        $random = $_GET['random'];
    }
    if (empty($st)) {
        $st = 0;
    }
    if (isset($_GET['st'])) {
        $st = $_GET['st'];
    }
    $name = isset($_POST['NAAM']) ? $_POST['NAAM'] : null;
    $roft = isset($_POST['roft']) ? $_POST['roft'] : null;

    $id_owner   = isset($_POST['id_owner']) ? $_POST['id_owner'] : null;
    $id_breeder = isset($_POST['id_breeder']) ? $_POST['id_breeder'] : null;

    $picturefield = isset($_FILES['photo']) ? $_FILES['photo']['name'] : null;  // $_FILES['photo']['name'];
    if (empty($picturefield) || $picturefield == "") {
        $foto = "";
    } else {
        $foto = uploadedpict(0);
    }
    $numpicturefield = 1;

    //make the redirect
    if (!isset($_GET['r'])) {
        if ($_POST['NAAM'] == "") {
            redirect_header("add_dog.php", 1, _MA_PEDIGREE_ADD_NAMEPLZ);
        }
        //create animal object
        $animal = new Animal();
        //test to find out how many user fields there are..
        $fields = $animal->numoffields();
        sort($fields); //sort by ID not by order
        $usersql = "";
        for ($i = 0; $i < count($fields); ++$i) {
            $userfield   = new Field($fields[$i], $animal->getconfig());
            $fieldType   = $userfield->getSetting("FieldType");
            $fieldobject = new $fieldType($userfield, $animal);
            if ($userfield->active()) {
                //check if _FILES variable exists for user picturefield
                $currentfield = 'user' . $fields[$i];
                $picturefield = $_FILES[$currentfield]['name'];
                if ($fieldType == "Picture" && (!empty($picturefield) || $picturefield != "")) {
                    $userpicture = uploadedpict($numpicturefield);
                    $usersql .= ",'" . $userpicture . "'";
                    ++$numpicturefield;
                } elseif ($userfield->isLocked()) {
                    //userfield is locked, substitute default value
                    $usersql .= ",'" . $userfield->DefaultValue . "'";
                } else {
                    //echo $fieldType.":".$i.":".$fields[$i]."<br />";
                    $usersql .= ",'" . unhtmlentities($_POST['user' . $fields[$i]]) . "'";
                }
            } else {
                $usersql .= ",''";
            }
            //echo $fields[$i]."<br/>";

        }

        //insert into pedigree_temp
        $query
            = "INSERT INTO " . $xoopsDB->prefix("pedigree_temp") . " VALUES ('" . $random . "','" . unhtmlentities($name) . "','" . $id_owner . "','" . $id_breeder . "','" . $user . "','" . $roft
            . "','','','" . $foto . "', ''" . $usersql . ")";
        //echo $query; die();
        $xoopsDB->query($query);
        redirect_header("add_dog.php?f=sire&random=" . $random . "&st=" . $st . "&r=1&l=a", 1, strtr(_MA_PEDIGREE_ADD_SIREPLZ, array('[father]' => $moduleConfig['father'])));
    }
    //find letter on which to start else set to 'a'
    if (isset($_GET['l'])) {
        $l = $_GET['l'];
    } else {
        $l = "a";
    }
    //assign sire to template
    $xoopsTpl->assign("sire", "1");
    //create list of males dog to select from
    $perp = $moduleConfig['perpage'];
    //count total number of dogs
    $numdog = "SELECT count(ID) from " . $xoopsDB->prefix("pedigree_tree") . " WHERE roft='0' and NAAM LIKE '" . $l . "%'";
    $numres = $xoopsDB->query($numdog);
    //total number of dogs the query will find
    list($numresults) = $xoopsDB->fetchRow($numres);
    //total number of pages
    $numpages = (floor($numresults / $perp)) + 1;
    if (($numpages * $perp) == ($numresults + $perp)) {
        $numpages = $numpages - 1;
    }
    //find current page
    $cpage = (floor($st / $perp)) + 1;
    //create alphabet
    $pages = "";
    for ($i = 65; $i <= 90; ++$i) {
        if ($l == chr($i)) {
            $pages .= "<b><a href=\"add_dog.php?f=sire&r=1&random=" . $random . "&l=" . chr($i) . "\">" . chr($i) . "</a></b>&nbsp;";
        } else {
            $pages .= "<a href=\"add_dog.php?f=sire&r=1&random=" . $random . "&l=" . chr($i) . "\">" . chr($i) . "</a>&nbsp;";
        }
    }
    $pages .= "-&nbsp;";
    $pages .= "<a href=\"add_dog.php?f=sire&r=1&random=" . $random . "&l=Ã…\">Ã…</a>&nbsp;";
    $pages .= "<a href=\"add_dog.php?f=sire&r=1&random=" . $random . "&l=Ã–\">Ã–</a>&nbsp;";
    //create linebreak
    $pages .= "<br />";
    //create previous button
    if ($numpages > 1) {
        if ($cpage > 1) {
            $pages .= "<a href=\"add_dog.php?f=sire&r=1&l=" . $l . "&random=" . $random . "&st=" . ($st - $perp) . "\">" . _MA_PEDIGREE_PREVIOUS . "</a>&nbsp;&nbsp";
        }
    }
    //create numbers
    for ($x = 1; $x < ($numpages + 1); ++$x) {
        //create line break after 20 number
        if (($x % 20) == 0) {
            $pages .= "<br />";
        }
        if ($x != $cpage) {
            $pages .= "<a href=\"add_dog.php?f=sire&r=1&l=" . $l . "&random=" . $random . "&st=" . ($perp * ($x - 1)) . "\">" . $x . "</a>&nbsp;&nbsp;";
        } else {
            $pages .= $x . "&nbsp;&nbsp";
        }
    }
    //create next button
    if ($numpages > 1) {
        if ($cpage < ($numpages)) {
            $pages .= "<a href=\"add_dog.php?f=sire&r=1&l=" . $l . "&random=" . $random . "&st=" . ($st + $perp) . "\">" . _MA_PEDIGREE_NEXT . "</a>&nbsp;&nbsp";
        }
    }

    //query
    $queryString = "SELECT * from " . $xoopsDB->prefix("pedigree_tree") . " WHERE roft = '0' and NAAM like '" . $l . "%'ORDER BY NAAM LIMIT " . $st . ", " . $perp;
    $result      = $xoopsDB->query($queryString);

    $animal = new Animal();
//test to find out how many user fields there are...
    $fields       = $animal->numoffields();
    $numofcolumns = 1;
    $columns[]    = array('columnname' => "Name");
    for ($i = 0; $i < count($fields); ++$i) {
        $userfield   = new Field($fields[$i], $animal->getconfig());
        $fieldType   = $userfield->getSetting("FieldType");
        $fieldobject = new $fieldType($userfield, $animal);
        //create empty string
        $lookupvalues = "";
        if ($userfield->active() && $userfield->inlist()) {
            if ($userfield->haslookup()) {
                $lookupvalues = $userfield->lookup($fields[$i]);
                //debug information
                //print_r($lookupvalues);
            }
            $columns[] = array('columnname' => $fieldobject->fieldname, 'columnnumber' => $userfield->getID(), 'lookupval' => $lookupvalues);
            ++$numofcolumns;
            unset($lookupvalues);
        }
    }

    for ($i = 1; $i < ($numofcolumns); ++$i) {
        $empty[] = array('value' => "");
    }
    $dogs [] = array(
        'id'          => "0",
        'name'        => "",
        'gender'      => "",
        'link'        => "<a href=\"add_dog.php?f=dam&random=" . $random . "&selsire=0\">" . strtr(_MA_PEDIGREE_ADD_SIREUNKNOWN, array('[father]' => $moduleConfig['father'])) . "</a>",
        'colour'      => "",
        'number'      => "",
        'usercolumns' => $empty
    );

    while ($row = $xoopsDB->fetchArray($result)) {
        //create picture information
        if ($row['foto'] != '') {
            $camera = " <img src=\"images/camera.png\">";
        } else {
            $camera = "";
        }
        $name = stripslashes($row['NAAM']) . $camera;
        //empty array
        unset($columnvalue);
        //fill array
        for ($i = 1; $i < ($numofcolumns); ++$i) {
            $x = $columns[$i]['columnnumber'];
            if (is_array($columns[$i]['lookupval'])) {
                foreach ($columns[$i]['lookupval'] as $key => $keyvalue) {
                    if ($key == $row['user' . $x]) {
                        $value = $keyvalue['value'];
                    }
                }
                //debug information
                ///echo $columns[$i]['columnname']."is an array !";
            } //format value - cant use object because of query count
            elseif (substr($row['user' . $x], 0, 7) == 'http://') {
                $value = "<a href=\"" . $row['user' . $x] . "\">" . $row['user' . $x] . "</a>";
            } else {
                $value = $row['user' . $x];
            }
            $columnvalue[] = array('value' => $value);
        }
        $dogs[] = array(
            'id'          => $row['ID'],
            'name'        => $name,
            'gender'      => '<img src="images/male.gif">',
            'link'        => "<a href=\"add_dog.php?f=dam&random=" . $random . "&selsire=" . $row['ID'] . "\">" . $name . "</a>",
            'colour'      => "",
            'number'      => "",
            'usercolumns' => $columnvalue
        );
    }

    //add data to smarty template
    //assign dog
    $xoopsTpl->assign("dogs", $dogs);
    $xoopsTpl->assign("columns", $columns);
    $xoopsTpl->assign("numofcolumns", $numofcolumns);
    $xoopsTpl->assign("tsarray", sorttable($numofcolumns));
    //assign links
    $xoopsTpl->assign("nummatch", strtr(_MA_PEDIGREE_ADD_SELSIRE, array('[father]' => $moduleConfig['father'])));
    $xoopsTpl->assign("pages", $pages);

}

function dam()
{
    global $xoopsTpl, $xoopsUser, $xoopsDB;

    //get module configuration
    $module_handler =& xoops_gethandler('module');
    $module         =& $module_handler->getByDirname("pedigree");
    $config_handler =& xoops_gethandler('config');
    $moduleConfig   =& $config_handler->getConfigsByCat(0, $module->getVar('mid'));
    $empty          = array(); // an empty array

    //check for access
    $xoopsModule =& XoopsModule::getByDirname("pedigree");
    if (empty($xoopsUser)) {
        redirect_header("javascript:history.go(-1)", 3, _NOPERM . "<br />" . _MA_PEDIGREE_REGIST);
        exit();
    }
    if (empty($random)) {
        $random = isset($_POST['random']) ? $_POST['random'] : null;
    }
    if (isset($_GET['random'])) {
        $random = $_GET['random'];
    }
    if (empty($st)) {
        $st = 0;
    }
    if (isset($_GET['st'])) {
        $st = $_GET['st'];
    }
    //find letter on which to start else set to 'a'
    if (isset($_GET['l'])) {
        $l = $_GET['l'];
    } else {
        $l = "a";
    }
    //make the redirect
    if (!isset($_GET['r'])) {
        //insert into pedigree_temp
        $query = "UPDATE " . $xoopsDB->prefix("pedigree_temp") . " SET father =" . $_GET['selsire'] . " WHERE ID=" . $random;
        $xoopsDB->queryf($query);
        redirect_header("add_dog.php?f=dam&random=" . $random . "&st=" . $st . "&r=1&l=a", 1, strtr(_MA_PEDIGREE_ADD_SIREOK, array('[mother]' => $moduleConfig['mother'])));
    }

    $xoopsTpl->assign("sire", "1");
    //create list of males dog to select from
    $perp = $moduleConfig['perpage'];
    //count total number of dogs
    $numdog = "SELECT count(ID) from " . $xoopsDB->prefix("pedigree_tree") . " WHERE roft='1' and NAAM LIKE '" . $l . "%'";
    $numres = $xoopsDB->query($numdog);
    list($numresults) = $xoopsDB->fetchRow($numres);
    $numpages = (floor($numresults / $perp)) + 1;
    if (($numpages * $perp) == ($numresults + $perp)) {
        $numpages = $numpages - 1;
    }
    $cpage = (floor($st / $perp)) + 1;
    //create alphabet
    $pages = "";
    for ($i = 65; $i <= 90; ++$i) {
        if ($l == chr($i)) {
            $pages .= "<b><a href=\"add_dog.php?f=dam&r=1&random=" . $random . "&l=" . chr($i) . "\">" . chr($i) . "</a></b>&nbsp;";
        } else {
            $pages .= "<a href=\"add_dog.php?f=dam&r=1&random=" . $random . "&l=" . chr($i) . "\">" . chr($i) . "</a>&nbsp;";
        }
    }
    $pages .= "-&nbsp;";
    $pages .= "<a href=\"add_dog.php?f=dam&r=1&random=" . $random . "&l=Ã…\">Ã…</a>&nbsp;";
    $pages .= "<a href=\"add_dog.php?f=dam&r=1&random=" . $random . "&l=Ã–\">Ã–</a>&nbsp;";
    $pages .= "<br />";
    //create previous button
    if ($numpages > 1) {
        if ($cpage > 1) {
            $pages .= "<a href=\"add_dog.php?f=dam&r=1&l=" . $l . "&random=" . $random . "&st=" . ($st - $perp) . "\">" . _MA_PEDIGREE_PREVIOUS . "</a>&nbsp;&nbsp";
        }
    }
    //create numbers
    for ($x = 1; $x < ($numpages + 1); ++$x) {
        //create line break after 20 number
        if (($x % 20) == 0) {
            $pages .= "<br />";
        }
        if ($x != $cpage) {
            $pages .= "<a href=\"add_dog.php?f=dam&r=1&l=" . $l . "&random=" . $random . "&st=" . ($perp * ($x - 1)) . "\">" . $x . "</a>&nbsp;&nbsp;";
        } else {
            $pages .= $x . "&nbsp;&nbsp";
        }
    }
    //create next button
    if ($numpages > 1) {
        if ($cpage < ($numpages)) {
            $pages .= "<a href=\"add_dog.php?f=dam&l=" . $l . "&r=1&random=" . $random . "&st=" . ($st + $perp) . "\">" . _MA_PEDIGREE_NEXT . "</a>&nbsp;&nbsp;";
        }
    }

    //query
    $queryString = "SELECT * from " . $xoopsDB->prefix("pedigree_tree") . " WHERE roft = '1' and NAAM LIKE '" . $l . "%' ORDER BY NAAM LIMIT " . $st . ", " . $perp;
    $result      = $xoopsDB->query($queryString);

    $animal = new Animal();
    //test to find out how many user fields there are...
    $fields       = $animal->numoffields();
    $numofcolumns = 1;
    $columns[]    = array('columnname' => "Name");
    for ($i = 0; $i < count($fields); ++$i) {
        $userfield   = new Field($fields[$i], $animal->getconfig());
        $fieldType   = $userfield->getSetting("FieldType");
        $fieldobject = new $fieldType($userfield, $animal);
        //create empty string
        $lookupvalues = "";
        if ($userfield->active() && $userfield->inlist()) {
            if ($userfield->haslookup()) {
                $lookupvalues = $userfield->lookup($fields[$i]);
                //debug information
                //print_r($lookupvalues);
            }
            $columns[] = array('columnname' => $fieldobject->fieldname, 'columnnumber' => $userfield->getID(), 'lookupval' => $lookupvalues);
            ++$numofcolumns;
            unset($lookupvalues);
        }
    }

    for ($i = 1; $i < ($numofcolumns); ++$i) {
        $empty[] = array('value' => "");
    }
    $dogs [] = array(
        'id'          => "0",
        'name'        => "",
        'gender'      => "",
        'link'        => "<a href=\"add_dog.php?f=check&random=" . $random . "&seldam=0\">" . strtr(_MA_PEDIGREE_ADD_DAMUNKNOWN, array('[mother]' => $moduleConfig['mother'])) . "</a>",
        'colour'      => "",
        'number'      => "",
        'usercolumns' => $empty
    );

    while ($row = $xoopsDB->fetchArray($result)) {
        //create picture information
        if ($row['foto'] != '') {
            $camera = " <img src=\"images/camera.png\">";
        } else {
            $camera = "";
        }
        $name = stripslashes($row['NAAM']) . $camera;
        //empty array
        unset($columnvalue);
        //fill array
        for ($i = 1; $i < ($numofcolumns); ++$i) {
            $x = $columns[$i]['columnnumber'];
            if (is_array($columns[$i]['lookupval'])) {
                foreach ($columns[$i]['lookupval'] as $key => $keyvalue) {
                    if ($key == $row['user' . $x]) {
                        $value = $keyvalue['value'];
                    }
                }
                //debug information
                ///echo $columns[$i]['columnname']."is an array !";
            } //format value - cant use object because of query count
            elseif (substr($row['user' . $x], 0, 7) == 'http://') {
                $value = "<a href=\"" . $row['user' . $x] . "\">" . $row['user' . $x] . "</a>";
            } else {
                $value = $row['user' . $x];
            }
            $columnvalue[] = array('value' => $value);
        }
        $dogs[] = array(
            'id'          => $row['ID'],
            'name'        => $name,
            'gender'      => '<img src="images/female.gif">',
            'link'        => "<a href=\"add_dog.php?f=check&random=" . $random . "&seldam=" . $row['ID'] . "\">" . $name . "</a>",
            'colour'      => "",
            'number'      => "",
            'usercolumns' => $columnvalue
        );
    }

    //add data to smarty template
    //assign dog
    $xoopsTpl->assign("dogs", $dogs);
    $xoopsTpl->assign("columns", $columns);
    $xoopsTpl->assign("numofcolumns", $numofcolumns);
    $xoopsTpl->assign("tsarray", sorttable($numofcolumns));
    $xoopsTpl->assign("nummatch", strtr(_MA_PEDIGREE_ADD_SELDAM, array('[mother]' => $moduleConfig['mother'])));
    $xoopsTpl->assign("pages", $pages);
}

function check()
{
    global $xoopsTpl, $xoopsUser, $xoopsDB;

    //get module configuration
    $module_handler =& xoops_gethandler('module');
    $module         =& $module_handler->getByDirname("pedigree");
    $config_handler =& xoops_gethandler('config');
    $moduleConfig   =& $config_handler->getConfigsByCat(0, $module->getVar('mid'));

    //check for access
    $xoopsModule =& XoopsModule::getByDirname("pedigree");
    if (empty($xoopsUser)) {
        redirect_header("index.php", 3, _NOPERM . "<br />" . _MA_PEDIGREE_REGIST);
        exit();
    }
    if (empty($random)) {
        $random = $_POST['random'];
    }
    if (isset($_GET['random'])) {
        $random = $_GET['random'];
    }

    //query
    $queryString = "SELECT * from " . $xoopsDB->prefix("pedigree_temp") . " WHERE ID = " . $random;
    $result      = $xoopsDB->query($queryString);
    while ($row = $xoopsDB->fetchArray($result)) {
        //create animal object
        $animal = new Animal();
        //test to find out how many user fields there are..
        $fields = $animal->numoffields();
        sort($fields);
        $usersql = "";
        for ($i = 0; $i < count($fields); ++$i) {
            $userfield   = new Field($fields[$i], $animal->getconfig());
            $fieldType   = $userfield->getSetting("FieldType");
            $fieldobject = new $fieldType($userfield, $animal);
            if ($userfield->active()) {
                $usersql .= ",'" . addslashes($row['user' . $fields[$i]]) . "'";
            } else {
                $usersql .= ",'" . $fieldobject->defaultvalue . "'";
            }
            //echo $fields[$i]."<br/>";
        }
        //insert into pedigree
        $query
            = "INSERT INTO " . $xoopsDB->prefix("pedigree_tree") . " VALUES ('','" . addslashes($row['NAAM']) . "','" . $row['id_owner'] . "','" . $row['id_breeder'] . "','" . $row['user'] . "','"
            . $row['roft'] . "','" . $_GET['seldam'] . "','" . $row['father'] . "','" . addslashes($row['foto']) . "',''" . $usersql . ")";
        mysql_query($query);
        //echo $query; die();
    }
    $sqlquery = "DELETE from " . $xoopsDB->prefix("pedigree_temp") . " where ID='" . $random . "'";
    $xoopsDB->queryf($sqlquery);
    redirect_header("latest.php", 1, strtr(_MA_PEDIGREE_ADD_OK, array('[animalType]' => $moduleConfig['animalType'])));
}

//footer
include XOOPS_ROOT_PATH . "/footer.php";
