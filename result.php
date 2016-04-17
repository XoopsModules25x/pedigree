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
require_once(XOOPS_ROOT_PATH . '/modules/' . $xoopsModule->dirname() . '/include/functions.php');
require_once(XOOPS_ROOT_PATH . '/modules/' . $xoopsModule->dirname() . '/include/class_field.php');

$xoopsOption['template_main'] = 'pedigree_result.tpl';

include XOOPS_ROOT_PATH . '/header.php';

//get module configuration
$module_handler = xoops_getHandler('module');
$module         = $module_handler->getByDirname('pedigree');
$config_handler = xoops_getHandler('config');
$moduleConfig   = $config_handler->getConfigsByCat(0, $module->getVar('mid'));

if (!isset($_GET['f'])) {
    $f = 'NAAM';
} else {
    $f = $_GET['f'];
}
if (isset($_POST['query'])) {
    $q = $_POST['query'];
} else {
    $q = '';
}
if (strlen($q) < 1 && isset($_POST['query'])) {
    redirect_header('index.php', 30, _MA_PEDIGREE_SEARCH_SHORT);
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
    if ($_GET['w'] == 'zero' || $_GET['w'] == '' || $_GET['w'] == '0') {
        $w = '0';
    } else {
        $w = $_GET['w'];
    }
}
if (isset($_GET['l'])) {
    if ($_GET['l'] == '1' || $_GET['l'] == 'LIKE') {
        $l = 'LIKE';
    }
} else {
    $l = '=';
}

if (!$_GET['o']) {
    $o = 'NAAM';
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

$perp = $moduleConfig['perpage'];

global $xoopsTpl;
global $xoopsDB;
global $xoopsModuleConfig;

//iscurrent user a module admin ?
$modadmin    = false;
$xoopsModule = XoopsModule::getByDirname('pedigree');
if (!empty($xoopsUser)) {
    if ($xoopsUser->isAdmin($xoopsModule->mid())) {
        $modadmin = true;
    }
}

//count total number of dogs
$numdog = 'SELECT count(ID) from ' . $xoopsDB->prefix('pedigree_tree') . ' WHERE ' . $f . ' ' . $l . " '" . $w . "'";
$numres = $xoopsDB->query($numdog);
//total number of dogs the query will find
list($numresults) = $xoopsDB->fetchRow($numres);
//if nothing is found
if ($numresults == 0) {
    //just for debug information
    //echo $numdog;
    redirect_header('index.php', 300, strtr(_MA_PEDIGREE_SEARCH_NO, array('[animalTypes]' => $moduleConfig['animalTypes'])));
}
//total number of pages
$numpages = floor($numresults / $perp) + 1;
if (($numpages * $perp) == ($numresults + $perp)) {
    $numpages = $numpages - 1;
}
//find current page
$cpage = floor($st / $perp) + 1;
//create empty pages variable
$pages = '';

$length = strlen($w);
if (substr($w, $length - 1, $length) == '%') {
    $whe = substr($w, 0, $length - 1) . '%25';
    if (substr($whe, 0, 1) == '%') {
        $length = strlen($whe);
        $whe    = '%25' . substr($whe, 1, $length);
    }
} else {
    $whe = $w;
}

//create previous button
if ($numpages > 1) {
    if ($cpage > 1) {
        $pages .= "<a href=\"result.php?f=" . $f . '&amp;l=' . $l . '&amp;w=' . $whe . '&amp;o=' . $o . '&amp;d=' . $d . '&amp;st=' . ($st - $perp) . "\">" . _MA_PEDIGREE_PREVIOUS
                  . '</a>&nbsp;&nbsp;';
    }
}
//create numbers
for ($x = 1; $x < ($numpages + 1); ++$x) {
    //create line break after 20 number
    if (($x % 20) == 0) {
        $pages .= '<br />';
    }
    if ($x != $cpage) {
        $pages .= "<a href=\"result.php?f=" . $f . '&l=' . $l . '&w=' . $whe . '&o=' . $o . '&d=' . $d . '&st=' . ($perp * ($x - 1)) . "\">" . $x . '</a>&nbsp;&nbsp;';
    } else {
        $pages .= '<b>' . $x . '</b>&nbsp;&nbsp';
    }
}
//create next button
if ($numpages > 1) {
    if ($cpage < $numpages) {
        $pages .= "<a href=\"result.php?f=" . $f . '&amp;l=' . $l . '&amp;w=' . $whe . '&amp;o=' . $o . '&amp;d=' . $d . '&amp;st=' . ($st + $perp) . "\">" . _MA_PEDIGREE_NEXT . '</a>&nbsp;&nbsp';
    }
}

//query
$queryString
        = 'SELECT * from ' . $xoopsDB->prefix('pedigree_tree') . ' WHERE ' . $f . ' ' . $l . " '" . $w . "' ORDER BY " . $o . ' ' . $d . ' LIMIT ' . $st . ', ' . $perp;
$result = $xoopsDB->query($queryString);

$animal = new Animal();
//test to find out how many user fields there are...
$fields       = $animal->numoffields();
$numofcolumns = 1;
$columns[]    = array('columnname' => 'Name');
for ($i = 0; $i < count($fields); ++$i) {
    $userfield   = new Field($fields[$i], $animal->getconfig());
    $fieldType   = $userfield->getSetting('FieldType');
    $fieldobject = new $fieldType($userfield, $animal);
    //create empty string
    $lookupvalues = '';
    if ($userfield->active() && $userfield->inlist()) {
        if ($userfield->haslookup()) {
            $lookupvalues = $userfield->lookup($fields[$i]);
            //debug information
            //print_r($lookupvalues);
        }
        $columns[] = array(
            'columnname'   => $fieldobject->fieldname,
            'columnnumber' => $userfield->getID(),
            'lookupval'    => $lookupvalues
        );
        ++$numofcolumns;
        unset($lookupvalues);
    }
}

while ($row = $xoopsDB->fetchArray($result)) {
    //reset $gender
    $gender = '';
    if (!empty($xoopsUser)) {
        if ($row['user'] == $xoopsUser->getVar('uid') || $modadmin == true) {
            $gender
                = "<a href=\"dog.php?id=" . $row['ID'] . "\"><img src=\"assets/images/edit.gif\" alt=" . _MA_PEDIGREE_BTN_EDIT . "></a><a href=\"delete.php?id=" . $row['ID']
                . "\"><img src=\"assets/images/delete.gif\" alt=" . _MA_PEDIGREE_BTN_DELE . '></a>';
        } else {
            $gender = '';
        }
    }
    if ($row['roft'] == 0) {
        $gender .= "<img src=\"assets/images/male.gif\">";
    } else {
        $gender .= "<img src=\"assets/images/female.gif\">";
    }
    if ($row['foto'] != '') {
        $camera = " <img src=\"assets/images/camera.png\">";
    } else {
        $camera = '';
    }
    $name = stripslashes($row['NAAM']) . $camera;
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
        elseif (substr($row['user' . $x], 0, 7) == 'http://') {
            $value = "<a href=\"" . $row['user' . $x] . "\">" . $row['user' . $x] . '</a>';
        } else {
            $value = $row['user' . $x];
        }
        if (isset($value)) {
            $columnvalue[] = array('value' => $value);
            unset($value);
        }
    }
    $animals[] = array(
        'id'          => $row['ID'],
        'name'        => $name,
        'gender'      => $gender,
        'link'        => "<a href=\"pedigree.php?pedid=" . $row['ID'] . "\">" . $name . '</a>',
        'colour'      => '',
        'number'      => '',
        'usercolumns' => isset($columnvalue) ? $columnvalue : 0
    );
}

//add data to smarty template
//assign dog
$xoopsTpl->assign('dogs', $animals);
$xoopsTpl->assign('columns', $columns);
$xoopsTpl->assign('numofcolumns', $numofcolumns);
$xoopsTpl->assign('tsarray', sorttable($numofcolumns));
//assign links

//find last shown number
if (($st + $perp) > $numresults) {
    $lastshown = $numresults;
} else {
    $lastshown = $st + $perp;
}
//create string
$matches     = strtr(_MA_PEDIGREE_MATCHES, array('[animalTypes]' => $moduleConfig['animalTypes']));
$nummatchstr = $numresults . $matches . ($st + 1) . '-' . $lastshown . ' (' . $numpages . ' pages)';
$xoopsTpl->assign('nummatch', $nummatchstr);
$xoopsTpl->assign('pages', $pages);

//comments and footer
include XOOPS_ROOT_PATH . '/footer.php';
