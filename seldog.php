<?php
// -------------------------------------------------------------------------

require_once dirname(dirname(__DIR__)) . '/mainfile.php';

$moduleDirName = basename(__DIR__);
xoops_loadLanguage('main', $moduleDirName);
xoops_load('PedigreeAnimal', $moduleDirName);
xoops_load('XoopsRequest');

// Include any common code for this module.
require_once(XOOPS_ROOT_PATH . '/modules/' . $moduleDirName . '/include/common.php');

/*
// Get all HTTP post or get parameters into global variables that are prefixed with "param_"
//import_request_variables("gp", "param_");
extract($_GET, EXTR_PREFIX_ALL, "param");
extract($_POST, EXTR_PREFIX_ALL, "param");
*/
$xoopsOption['template_main'] = 'pedigree_sel.tpl';

include $GLOBALS['xoops']->path('/header.php');

//get module configuration
$moduleHandler = xoops_getHandler('module');
$module        = $moduleHandler->getByDirname('pedigree');
$configHandler = xoops_getHandler('config');
$moduleConfig  = $configHandler->getConfigsByCat(0, $module->getVar('mid'));

$st     = XoopsRequest::getInt('st', 0, 'GET');
$gend   = XoopsRequest::getInt('gend', 0, 'GET');
$curval = XoopsRequest::getInt('curval', 0, 'GET');

/* @todo: default value of 'a' assumes english, this should be defined in language file */
$letter = XoopsRequest::getString('letter', 'a', 'GET');

$perp = $moduleConfig['perpage'];

$GLOBALS['xoopsTpl']->assign('page_title', _MI_PEDIGREE_TITLE);

//count total number of dogs
$numdog = 'SELECT Id FROM ' . $GLOBALS['xoopsDB']->prefix('pedigree_tree') . " WHERE NAAM LIKE '{$letter}%' AND roft = '{$gend}'";
$numres = $GLOBALS['xoopsDB']->query($numdog);
//total number of dogs the query will find
$numresults = $GLOBALS['xoopsDB']->getRowsNum($numres);
//total number of pages
$numpages = floor($numresults / $perp) + 1;
if (($numpages * $perp) == ($numresults + $perp)) {
    --$numpages;
}
//find current page
$cpage = floor($st / $perp) + 1;
/* @todo: change this to use letters() from mylinks or similar module - this routine assumes english */
//create alphabet
$pages = '';
for ($i = 65; $i <= 90; ++$i) {
    if ($letter == chr($i)) {
        $pages .= "<b><a href='seldog.php?gend={$gend}&curval={$curval}&letter=" . chr($i) . "'>" . chr($i) . '</a></b>&nbsp;';
    } else {
        $pages .= "<a href='seldog.php?gend={$gend}&curval={$curval}&letter=" . chr($i) . "'>" . chr($i) . '</a>&nbsp;';
    }
}
$pages .= '-&nbsp;';
$pages .= "<a href='seldog.php?gend={$gend}&curval={$curval}&letter=Ã…\">Ã…</a>&nbsp;";
$pages .= "<a href='seldog.php?gend={$gend}&curval={$curval}&letter=Ã–\">Ã–</a>&nbsp;";
//create linebreak
$pages .= '<br />';
//create previous button
if (($numpages > 1) && ($cpage > 1)) {
    $pages .= "<a href='seldog.php?gend={$gend}&curval={$curval}&letter={$letter}&st=" . ($st - $perp) . "'>" . _MA_PEDIGREE_PREVIOUS . '</a>&nbsp;&nbsp';
}
//create numbers
for ($x = 1; $x < ($numpages + 1); ++$x) {
    //create line break after 20 number
    if (0 == ($x % 20)) {
        $pages .= '<br />';
    }
    if ($x != $cpage) {
        $pages .= "<a href='seldog.php?gend={$gend}&curval={$curval}&letter={$letter}&st=" . ($perp * ($x - 1)) . "'>{$x}</a>&nbsp;&nbsp";
    } else {
        $pages .= "{$x}&nbsp;&nbsp";
    }
}
//create next button
if (($numpages > 1) && ($cpage < $numpages)) {
    $pages .= "<a href='seldog.php?gend={$gend}&curval={$curval}&letter={$letter}&st=" . ($st + $perp) . "'>" . _MA_PEDIGREE_NEXT . '</a>&nbsp;&nbsp';
}

//query
$queryString = 'SELECT * FROM ' . $GLOBALS['xoopsDB']->prefix('pedigree_tree') . " WHERE NAAM LIKE '{$letter}%' AND roft = '{$gend}' ORDER BY NAAM LIMIT {$st}, {$perp}";
$result      = $GLOBALS['xoopsDB']->query($queryString);

$animal = new PedigreeAnimal();
//test to find out how many user fields there are...
$fields       = $animal->getNumOfFields();
$fieldsCount  = count($fields);
$numofcolumns = 1;
$columns[]    = array('columnname' => 'Name');
for ($i = 0; $i < $fieldsCount; ++$i) {
    $userField = new Field($fields[$i], $animal->getConfig());
    $fieldType = $userField->getSetting('fieldtype');
    $fieldObj  = new $fieldType($userField, $animal);
    //create empty string
    if ($userField->isActive() && $userField->inList()) {
        if ($userField->hasLookup()) {
            $lookupvalues = $userField->lookupField($fields[$i]);
            //debug information
            //print_r($lookupvalues);
        } else {
            $lookupvalues = '';
        }
        $columns[] = array(
            'columnname'   => $fieldObj->fieldname,
            'columnnumber' => $userField->getId(),
            'lookupval'    => $lookupvalues
        );
        ++$numofcolumns;
        unset($lookupvalues);
    }
}

$empty = array_fill(0, $numofcolumns, array('value' => ''));
/*
for ($i = 1; $i < $numofcolumns; ++$i) {
    $empty[] = array('value' => "");
}
*/
if (0 == $gend) {
    $dogs [] = array(
        'id'          => '0',
        'name'        => '',
        'gender'      => '',
        'link'        => "<a href='update.php?gend={$gend}&curval={$curval}&thisid=0'>" . strtr(_MA_PEDIGREE_ADD_SIREUNKNOWN, array('[father]' => $moduleConfig['father'])) . '</a>',
        'colour'      => '',
        'number'      => '',
        'usercolumns' => $empty
    );
} else {
    $dogs [] = array(
        'id'          => '0',
        'name'        => '',
        'gender'      => '',
        'link'        => "<a href='update.php?gend={$gend}&curval={$curval}&thisid=0'>" . strtr(_MA_PEDIGREE_ADD_DAMUNKNOWN, array('[mother]' => $moduleConfig['mother'])) . '</a>',
        'colour'      => '',
        'number'      => '',
        'usercolumns' => $empty
    );
}

while (false !== ($row = $GLOBALS['xoopsDB']->fetchArray($result))) {
    //create picture information
    $camera = ('' != $row['foto']) ? " <img src='assets/images/dog-icon25.png'>" : '';
    $name   = stripslashes($row['NAAM']) . $camera;
    //empty array
    unset($columnvalue);
    //fill array
    for ($i = 1; $i < $numofcolumns; ++$i) {
        $x = 'user' . $columns[$i]['columnnumber'];
        if (is_array($columns[$i]['lookupval'])) {
            foreach ($columns[$i]['lookupval'] as $key => $keyvalue) {
                if ($key == $row[$x]) {
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
        $columnvalue[] = array('value' => $value);
    }
    if (0 == $gend) {
        $dogs[] = array(
            'id'          => $row['Id'],
            'name'        => $name,
            'gender'      => '<img src="assets/images/male.gif">',
            'link'        => "<a href='update.php?gend={$gend}&curval={$curval}&thisid={$row['Id']}'>{$name}</a>",
            'colour'      => '',
            'number'      => '',
            'usercolumns' => $columnvalue
        );
    } else {
        $dogs[] = array(
            'id'          => $row['Id'],
            'name'        => $name,
            'gender'      => '<img src="assets/images/female.gif">',
            'link'        => "<a href='update.php?gend={$gend}&curval={$curval}&thisid={$row['Id']}'>{$name}</a>",
            'colour'      => '',
            'number'      => '',
            'usercolumns' => $columnvalue
        );
    }
}

//add data to smarty template
//assign dog
$GLOBALS['xoopsTpl']->assign(array(
                                 'dogs'         => $dogs,
                                 'columns'      => $columns,
                                 'numofcolumns' => $numofcolumns,
                                 'tsarray'      => PedigreeUtilities::sortTable($numofcolumns)
                             ));
//add data to smarty template
if (0 == $gend) {
    $selTtlParent = strtr(_MA_PEDIGREE_FLD_FATH, array('[father]' => $moduleConfig['father']));
} else {
    $selTtlParent = strtr(_MA_PEDIGREE_FLD_MOTH, array('[mother]' => $moduleConfig['mother']));
}
$seltitle = _MA_PEDIGREE_SEL . $selTtlParent . _MA_PEDIGREE_FROM . PedigreeUtilities::getName($curval);

$GLOBALS['xoopsTpl']->assign('seltitle', $seltitle);

//find last shown number
$lastshown = (($st + $perp) > $numresults) ? $numresults : $st + $perp;

//create string
/* @todo: move hard coded language string to language files */
$matches     = strtr(_MA_PEDIGREE_MATCHES, array('[animalTypes]' => $moduleConfig['animalTypes']));
$nummatchstr = "{$numresults}{$matches}" . ($st + 1) . " - {$lastshown} ({$numpages} pages)";
$GLOBALS['xoopsTpl']->assign(array(
                                 'nummatch' => $nummatchstr,
                                 'pages'    => $pages,
                                 'curval'   => $curval
                             ));

include $GLOBALS['xoops']->path('footer.php');
