<?php
// -------------------------------------------------------------------------

require_once dirname(dirname(__DIR__)) . '/mainfile.php';

$moduleDirName = basename(__DIR__);
xoops_loadLanguage('main', $moduleDirName);

// Include any common code for this module.
require_once(XOOPS_ROOT_PATH . '/modules/' . $moduleDirName . '/include/common.php');
require_once $GLOBALS['xoops']->path("modules/{$moduleDirName}/include/class_field.php");
require_once $GLOBALS['xoops']->path("modules/{$moduleDirName}/class/animal.php");
require_once __DIR__ . '/header.php';

$xoopsOption['template_main'] = 'pedigree_result.tpl';

include $GLOBALS['xoops']->path('/header.php');

//get module configuration
$moduleHandler = xoops_getHandler('module');
$module        = $moduleHandler->getByDirname('pedigree');
$configHandler = xoops_getHandler('config');
$moduleConfig  = $configHandler->getConfigsByCat(0, $module->getVar('mid'));

$f = XoopsRequest::getCmd('f', 'NAAM', 'GET');
$q = XoopsRequest::getString('query', '', 'POST');
/*
if (!isset($_GET['f'])) {
    $f = "NAAM";
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
    if ($_GET['w'] === 'zero' || $_GET['w'] === '' || $_GET['w'] === '0') {
        $w = '0';
    } else {
        $w = $_GET['w'];
    }
}
if (isset($_GET['l'])) {
    if ($_GET['l'] == '1' || $_GET['l'] === 'LIKE') {
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

//is current user a module admin?
$modadmin    = false;
$xoopsModule = XoopsModule::getByDirname('pedigree');
if (!empty($GLOBALS['xoopsUser']) && ($GLOBALS['xoopsUser'] instanceof XoopsUser) && $GLOBALS['xoopsUser']->isAdmin($xoopsModule->mid())) {
    $modadmin = true;
}

//count total number of dogs
$numdog = 'SELECT COUNT(ID) FROM ' . $GLOBALS['xoopsDB']->prefix('pedigree_tree') . ' WHERE ' . $f . ' ' . $l . " '" . $w . "'";
$numres = $GLOBALS['xoopsDB']->query($numdog);
//total number of dogs the query will find
list($numresults) = $GLOBALS['xoopsDB']->fetchRow($numres);
//if nothing is found
if (0 == $numresults) {
    //just for debug information
    //echo $numdog;
    redirect_header('index.php', 15, strtr(_MA_PEDIGREE_SEARCH_NO, array('[animalTypes]' => $moduleConfig['animalTypes'])));
}
//total number of pages
$numpages = floor($numresults / $perp) + 1;
if (($numpages * $perp) == ($numresults + $perp)) {
    --$numpages;
}
//find current page
$cpage = floor($st / $perp) + 1;
//create empty pages variable
$pages = '';

$length = strlen($w);
if (substr($w, $length - 1, $length) === '%') {
    $whe = substr($w, 0, $length - 1) . '%25';
    if (0 === strpos($whe, '%')) {
        $length = strlen($whe);
        $whe    = '%25' . substr($whe, 1, $length);
    }
} else {
    $whe = $w;
}
/* @todo: replace this with standard XOOPS Page Navigation */
//create previous button
if ($numpages > 1) {
    if ($cpage > 1) {
        $pages .= "<a href=\"result.php?f=" . $f . '&amp;l=' . $l . '&amp;w=' . $whe . '&amp;o=' . $o . '&amp;d=' . $d . '&amp;st=' . ($st - $perp) . "\">" . _MA_PEDIGREE_PREVIOUS . '</a>&nbsp;&nbsp;';
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
$queryString = 'SELECT * FROM ' . $GLOBALS['xoopsDB']->prefix('pedigree_tree') . ' WHERE ' . $f . ' ' . $l . " '" . $w . "' ORDER BY " . $o . ' ' . $d . ' LIMIT ' . $st . ', ' . $perp;
$result      = $GLOBALS['xoopsDB']->query($queryString);

$animal = new PedigreeAnimal();
//test to find out how many user fields there are...
$fields       = $animal->getNumOfFields();
$fieldsCount  = count($fields);
$numofcolumns = 1;
$columns      = array(array('columnname' => 'Name'));
for ($i = 0; $i < $fieldsCount; ++$i) {
    $userField   = new Field($fields[$i], $animal->getConfig());
    $fieldType   = $userField->getSetting('FieldType');
    $fieldObject = new $fieldType($userField, $animal);
    //create empty string
    if ($userField->isActive() && $userField->inList()) {
        if ($userField->hasLookup()) {
            $lookupvalues = $userField->lookupField($fields[$i]);
        } else {
            $lookupvalues = '';
        }
        /* print_r($lookupvalues);            //debug information */
        $columns[] = array(
            'columnname'   => $fieldObject->fieldname,
            'columnnumber' => $userField->getId(),
            'lookupval'    => $lookupvalues
        );
        ++$numofcolumns;
        unset($lookupvalues);
    }
}

$pathIcon16 = $GLOBALS['xoopsModule']->getInfo('icons16');

while (false !== ($row = $GLOBALS['xoopsDB']->fetchArray($result))) {
    //reset $gender
    $gender = '';
    if ((!empty($GLOBALS['xoopsUser']) && ($GLOBALS['xoopsUser'] instanceof XoopsUser)) && (($row['user'] == $xoopsUser->getVar('uid')) || (true == $modadmin))) {
        $gender = "<a href='dog.php?id={$row['Id']}'><img src=" . $pathIcon16 . '/edit.png alt=' . _EDIT . "'></a>
            .  <a href='delete.php?id={$row['Id']}'><img src=" . $pathIcon16 . '/delete.png alt=' . _DELETE . "'></a>";
    }
    if ($row['roft'] == 0) {
        $gender .= "<img src='assets/images/male.gif'>";
    } else {
        $gender .= "<img src='assets/images/female.gif'>";
    }
    $camera = ('' != $row['foto']) ? " <img src='assets/images/dog-icon25.png'>" : '';
    $name   = stripslashes($row['NAAM']) . $camera;
    //empty array
    unset($columnvalue);
    //fill array
    for ($i = 1; $i < $numofcolumns; ++$i) {
        $x = 'user' . $columns[$i]['columnnumber'];
        //echo $x."columnnumber";
        if (is_array($columns[$i]['lookupval'])) {
            foreach ($columns[$i]['lookupval'] as $key => $keyvalue) {
                if ($keyvalue['id'] == $row[$x]) {
                    //echo "key:".$row['user5']."<br />";
                    $value = $keyvalue['value'];
                }
            }
            //debug information
            ///echo $columns[$i]['columnname']."is an array !";
        } //format value - cant use object because of query count
        elseif (0 === strpos($row[$x], 'http://')) {
            $value = "<a href='{$row[$x]}'>{$row[$x]}</a>";
        } else {
            $value = $row[$x];
        }
        if (isset($value)) {
            $columnvalue[] = array('value' => $value);
            unset($value);
        }
    }
    $animals[] = array(
        'id'          => $row['Id'],
        'name'        => $name,
        'gender'      => $gender,
        'link'        => "<a href='pedigree.php?pedid={$row['Id']}'>{$name}</a>",
        'colour'      => '',
        'number'      => '',
        'usercolumns' => isset($columnvalue) ? $columnvalue : 0
    );
}

//add data to smarty template
//assign dog
$GLOBALS['xoopsTpl']->assign(array(
                                 'dogs'         => $animals,
                                 'columns'      => $columns,
                                 'numofcolumns' => $numofcolumns,
                                 'tsarray'      => PedigreeUtilities::sortTable($numofcolumns)
                             ));
//assign links

//find last shown number
if (($st + $perp) > $numresults) {
    $lastshown = $numresults;
} else {
    $lastshown = $st + $perp;
}
//create string
$matches     = strtr(_MA_PEDIGREE_MATCHES, array('[animalTypes]' => $moduleConfig['animalTypes']));
$nummatchstr = "{$numresults}{$matches}" . ($st + 1) . " - {$lastshown} ({$numpages} pages)";
$GLOBALS['xoopsTpl']->assign('nummatch', $nummatchstr);
$GLOBALS['xoopsTpl']->assign('pages', $pages);

//comments and footer
include $GLOBALS['xoops']->path('footer.php');
