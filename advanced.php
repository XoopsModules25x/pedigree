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

//needed for generation of pie charts
ob_start();
include(XOOPS_ROOT_PATH . '/modules/' . $xoopsModule->dirname() . '/include/class_eq_pie.php');
require_once(XOOPS_ROOT_PATH . '/modules/' . $xoopsModule->dirname() . '/include/class_field.php');

$xoopsOption['template_main'] = 'pedigree_advanced.tpl';

include XOOPS_ROOT_PATH . '/header.php';
// Include any common code for this module.
require_once(XOOPS_ROOT_PATH . '/modules/' . $xoopsModule->dirname() . '/include/functions.php');

global $xoopsTpl, $xoopsDB;
$totpl = array();
$books = array();
//get module configuration
$module_handler =& xoops_gethandler('module');
$module         =& $module_handler->getByDirname('pedigree');
$config_handler =& xoops_gethandler('config');
$moduleConfig   =& $config_handler->getConfigsByCat(0, $module->getVar('mid'));

//colour variables
$colors  = explode(';', $moduleConfig['colourscheme']);
$actlink = $colors[0];
$even    = $colors[1];
$odd     = $colors[2];
$text    = $colors[3];
$hovlink = $colors[4];
$head    = $colors[5];
$body    = $colors[6];
$title   = $colors[7];

//query to count male dogs
$result = $xoopsDB->query('select count(id) from ' . $xoopsDB->prefix('pedigree_tree') . " where roft='0'");
list($countmales) = $xoopsDB->fetchRow($result);

//query to count female dogs
$result = $xoopsDB->query('select count(id) from ' . $xoopsDB->prefix('pedigree_tree') . " where roft='1'");
list($countfemales) = $xoopsDB->fetchRow($result);

//create pie for number of males/females
//construct new pie
$numbers_pie = new eq_pie;
$data[0][0]  = strtr(_MA_PEDIGREE_FLD_MALE, array('[male]' => $moduleConfig['male']));
$data[0][1]  = $countmales;
$data[0][2]  = '#C8C8FF';

$data[1][0] = strtr(_MA_PEDIGREE_FLD_FEMA, array('[female]' => $moduleConfig['female']));
$data[1][1] = $countfemales;
$data[1][2] = '#FFC8C8';

$numbers_pie->MakePie('assets/images/numbers.png', '200', '200', '10', $odd, $data, '1');

//create animal object

$animal = new Animal();
//test to find out how many user fields there are...
$fields = $animal->numoffields();

for ($i = 0; $i < count($fields); ++$i) {
    $userfield   = new Field($fields[$i], $animal->getconfig());
    $fieldType   = $userfield->getSetting('FieldType');
    $fieldobject = new $fieldType($userfield, $animal);
    if ($userfield->active() && $userfield->inadvanced()) {
        $queryString
                  = 'select count(p.user' . $fields[$i] . ') as X, p.user' . $fields[$i] . ' as p_user' . $fields[$i] . ', b.ID as b_id, b.value as b_value from ' . $xoopsDB->prefix('pedigree_tree')
                    . ' p LEFT JOIN ' . $xoopsDB->prefix('pedigree_lookup' . $fields[$i]) . ' b ON p.user' . $fields[$i] . ' = b.ID GROUP BY p.user' . $fields[$i] . ' ORDER BY X DESC';
        $result   = $xoopsDB->query($queryString);
        $piecount = 0;
        unset($data);
        unset($books);

        while ($row = $xoopsDB->fetchArray($result)) {
            $data[$piecount][0] = $row['b_value'];
            $data[$piecount][1] = $row['X'];
            $data[$piecount][2] = '#' . hexdec(rand(255, 1)) . hexdec(rand(255, 1)) . hexdec(rand(255, 1));
            if ($row['p_user' . $fields[$i]] == '0') {
                $whe = 'zero';
            } else {
                $whe = $row['p_user' . $fields[$i]];
            }
            $books[] = array(
                'book'    => "<a href=\"result.php?f=user" . $fields[$i] . '&w=' . $whe . "&o=NAAM\">" . $row['X'] . '</a>',
                'country' => $row['b_value']
            );
            ++$piecount;
        }
        if ($userfield->inpie()) {
            $pie = new eq_pie;
            if ($piecount % 2 == 0) {
                $back = $even;
            } else {
                $back = $odd;
            }
            if (isset($data)) {
                $pie->MakePie('images/user' . $fields[$i] . '.png', '200', '200', '10', $back, $data, '1');
                unset($pie);
            }
            $books[] = array('book' => 'Chart', 'country' => '<img src="images/user' . $fields[$i] . '.png">');
        }
        $totpl[] = array('title' => $userfield->getSetting('FieldName'), 'content' => $books);
    }
}
//strtr(_MA_PEDIGREE_FLD_MALE, array( '[male]' => $moduleConfig['male'] ))
//strtr(_MA_PEDIGREE_ADV_ORPMUM, array( '[mother]' => $moduleConfig['mother'], '[animalTypes]' => $moduleConfig['animalTypes'] ))
if ($moduleConfig['proversion'] == '1') {
    $xoopsTpl->assign('pro', true);
}
$xoopsTpl->assign('title', strtr(_MA_PEDIGREE_ADV_VTMF, array('[male]' => $moduleConfig['male'], '[female]' => $moduleConfig['female'])));
$xoopsTpl->assign('topmales', "<a href=\"topstud.php?com=father\">" . strtr(_MA_PEDIGREE_ADV_STUD, array('[male]' => $moduleConfig['male'], '[children]' => $moduleConfig['children'])) . '</a>');
$xoopsTpl->assign('topfemales', "<a href=\"topstud.php?com=mother\">" . strtr(_MA_PEDIGREE_ADV_BITC, array('[female]' => $moduleConfig['female'], '[children]' => $moduleConfig['children'])) . '</a>');
$xoopsTpl->assign('tnmftitle', strtr(_MA_PEDIGREE_ADV_TNMFTIT, array('[male]' => $moduleConfig['male'], '[female]' => $moduleConfig['female'])));
$xoopsTpl->assign(
    'countmales',
    "<img src=\"assets/images/male.gif\"> " . strtr(_MA_PEDIGREE_ADV_TCMA, array('[male]' => $moduleConfig['male'], '[female]' => $moduleConfig['female']))
    . " : <a href=\"result.php?f=roft&w=zero&o=NAAM\">" . $countmales . '</a>'
);
$xoopsTpl->assign(
    'countfemales',
    "<img src=\"assets/images/female.gif\"> " . strtr(_MA_PEDIGREE_ADV_TCFE, array('[male]' => $moduleConfig['male'], '[female]' => $moduleConfig['female']))
    . " : <a href=\"result.php?f=roft&w=1&o=NAAM\">" . $countfemales
) . '</a>';
$xoopsTpl->assign('pienumber', "<img src=\"assets/images/numbers.png\">");
$xoopsTpl->assign('totpl', $totpl);
$xoopsTpl->assign('books', $books);

$xoopsTpl->assign('orptitle', _MA_PEDIGREE_ADV_ORPTIT);
$xoopsTpl->assign('orpall', "<a href=\"result.php?f=father=0 and mother&w=zero&o=NAAM\">" . strtr(_MA_PEDIGREE_ADV_ORPALL, array('[animalTypes]' => $moduleConfig['animalTypes'])) . '</a>');
$xoopsTpl->assign(
    'orpdad',
    "<a href=\"result.php?f=mother!=0 and father&w=zero&o=NAAM\">" . strtr(_MA_PEDIGREE_ADV_ORPDAD, array('[father]' => $moduleConfig['father'], '[animalTypes]' => $moduleConfig['animalTypes']))
    . '</a>'
);
$xoopsTpl->assign(
    'orpmum',
    "<a href=\"result.php?f=father!=0 and mother&w=zero&o=NAAM\">" . strtr(_MA_PEDIGREE_ADV_ORPMUM, array('[mother]' => $moduleConfig['mother'], '[animalTypes]' => $moduleConfig['animalTypes']))
    . '</a>'
);
$xoopsTpl->assign('position', _MA_PEDIGREE_M50_POS);
$xoopsTpl->assign('numdogs', _MA_PEDIGREE_M50_NUMD);
//comments and footer
include XOOPS_ROOT_PATH . '/footer.php';
