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
 * @package   \XoopsModules\Pedigree
 * @copyright Copyright (c) 2001-2019 {@link https://xoops.org XOOPS Project}
 * @license   https://www.gnu.org/licenses/gpl-2.0.html GNU Public License
 * @author    XOOPS Module Development Team
 */

use XoopsModules\Pedigree;
use XoopsModules\Pedigree\Constants;

require_once __DIR__ . '/header.php';

/** @var \XoopsModules\Pedigree\Helper $helper */
$helper->loadLanguage('main');

//needed for generation of pie charts
//ob_start();

$GLOBALS['xoopsOption']['template_main'] = 'pedigree_advanced.tpl';
include XOOPS_ROOT_PATH . '/header.php';

// Include any common code for this module.
require_once $helper->path('include/common.php');
$xoTheme->addScript(XOOPS_URL . '/browse.php?Frameworks/jquery/jquery.js');
$xoTheme->addScript($helper->url('assets/js/jquery.canvasjs.min.js'));

$totpl = [];
$books = [];

//get colour variables
list($actlink, $even, $odd, $text, $hovlink, $head, $body, $title) = Pedigree\Utility::getColourScheme();
/*
$actlink = $colors[0];
$even    = $colors[1];
$odd     = $colors[2];
$text    = $colors[3];
$hovlink = $colors[4];
$head    = $colors[5];
$body    = $colors[6];
$title   = $colors[7];
*/
//@todo TEST conversion to use Object CRUD using \Criteria instead of SQL call
//query to count male dogs
$treeHandler = $helper->getHandler('Tree');
$criteria = new \Criteria();
$criteria->setGroupBy('roft');
$criteria->order = 'ASC'; //hack to work around bug in XOOPS core
$roftCountArray = $treeHandler->getCounts($criteria);
$countMales = $roftCountArray[Constants::MALE];
$countFemales = $roftCountArray[Constants::FEMALE];
$totalAnimals = $countMales + $countFemales;
$pctMales = $totalAnimals > 0 ? round($countMales / $totalAnimals, Constants::PCT_PRECISION) : 0;
// to eliminate rounding errors
$pctFemales = 1 - $pctMales;

/*
$totalAnimals = $countMales + $countFemales;
$pctMales = (($totalAminals > 0) && ($countMales > 0)) ? round(100 / $totalAnimals * $countMales, 1) : 0;
$pctFemales = round(100 / $totalAnimals * $countFemales, 1);

//query to count male aminals
$result = $GLOBALS['xoopsDB']->query('SELECT count(id) FROM ' . $GLOBALS['xoopsDB']->prefix('pedigree_tree') . " WHERE roft='0'");
list($countmales) = $GLOBALS['xoopsDB']->fetchRow($result);

//query to count female animals
$result = $GLOBALS['xoopsDB']->query('SELECT count(id) FROM ' . $GLOBALS['xoopsDB']->prefix('pedigree_tree') . " WHERE roft='1'");
list($countfemales) = $GLOBALS['xoopsDB']->fetchRow($result);
*/
/*
//create pie for number of males/females
//construct new pie
$numbers_pie = new eq_pie;
$data[0][0]  = strtr(_MA_PEDIGREE_FLD_MALE, array('[male]' => $configs['male']));
$data[0][1]  = $countMales;
$data[0][2]  = '#C8C8FF';
$data[1][0] = strtr(_MA_PEDIGREE_FLD_FEMA, array('[female]' => $configs['female']));
$data[1][1] = $countFemales;
$data[1][2] = '#FFC8C8';

$numbers_pie->MakePie('assets/images/numbers.png', '200', '200', '10', $odd, $data, '1');

//create animal object

$animal = new Pedigree\Animal();
//test to find out how many user fields there are...
$fields = $animal->getFieldsIds();

for ($i = 0, $iMax = count($fields); $i < $iMax; ++$i) {
    $userField   = new Pedigree\Field($fields[$i], $animal->getConfig());
    $fieldType   = $userField->getSetting('fieldtype');
    $fieldObject = new $fieldType($userField, $animal);
    if ($userField->isActive() && $userField->inAdvanced()) {
        $queryString =
            'SELECT count(p.user' . $fields[$i] . ') as X, p.user' . $fields[$i] . ' as p_user' . $fields[$i] . ', b.ID as b_id, b.value as b_value FROM ' . $GLOBALS['xoopsDB']->prefix('pedigree_tree') . ' p LEFT JOIN ' . $GLOBALS['xoopsDB']->prefix('pedigree_lookup' . $fields[$i]) . ' b ON p.user'
            . $fields[$i] . ' = b.ID GROUP BY p.user' . $fields[$i] . ' ORDER BY X DESC';
        $result      = $GLOBALS['xoopsDB']->query($queryString);
        $piecount    = 0;
        unset($data, $books);

        while (false !== ($row = $GLOBALS['xoopsDB']->fetchArray($result))) {
            $data[$piecount][0] = $row['b_value'];
            $data[$piecount][1] = $row['X'];
            $data[$piecount][2] = '#' . hexdec(mt_rand(255, 1)) . hexdec(mt_rand(255, 1)) . hexdec(mt_rand(255, 1));
            if ($row['p_user' . $fields[$i]] == '0') {
                $whe = 'zero';
            } else {
                $whe = $row['p_user' . $fields[$i]];
            }
            $books[] = array(
                'book'    => "<a href=\"result.php?f=user" . $fields[$i] . '&w=' . $whe . "&o=naam\">" . $row['X'] . '</a>',
                'country' => $row['b_value']
            );
            ++$piecount;
        }
        if ($userField->inPie()) {
            $pie = new eq_pie;
            if ($piecount % 2 == 0) {
                $back = $even;
            } else {
                $back = $odd;
            }
            $pie->MakePie('assets/images/user' . $fields[$i] . '.png', '200', '200', '10', $back, $data, '1');
            unset($pie);
            $books[] = array('book' => 'Chart', 'country' => '<img src="assets/images/user' . $fields[$i] . '.png">');
        }
        $totpl[] = array('title' => $userField->getSetting('FieldName'), 'content' => $books);
    }
}
*/

$configs = $helper->getConfigs();
//strtr(_MA_PEDIGREE_FLD_MALE, array( '[male]' => $configs['male'] ))
//strtr(_MA_PEDIGREE_ADV_ORPMUM, array( '[mother]' => $configs['mother'], '[animalTypes]' => $configs['animalTypes'] ))
if ('1' == $configs['proversion']) {
    $GLOBALS['xoopsTpl']->assign('pro', true);
}
//get module preferences (configs)
$GLOBALS['xoopsTpl']->assign([
    'title' => strtr(_MA_PEDIGREE_ADV_VTMF, ['[male]' => $configs['male'], '[female]' => $configs['female']]),
    'topmales' => '<a href="topstud.php?com=father">' . strtr(_MA_PEDIGREE_ADV_STUD, [
                        '[male]' => $configs['male'],
                        '[children]' => $configs['children'],
                    ]) . '</a>',
    'topfemales' => '<a href="topstud.php?com=mother">' . strtr(_MA_PEDIGREE_ADV_BITC, [
                        '[female]' => $configs['female'],
                        '[children]' => $configs['children'],
                    ]) . '</a>',
    'tnmftitle' => strtr(_MA_PEDIGREE_ADV_TNMFTIT, ['[male]' => $configs['male'], '[female]' => $configs['female']]),
    'countmales' => "<img src=\"" . PEDIGREE_IMAGE_URL . "/male.gif\"> " . strtr(_MA_PEDIGREE_ADV_TCMA, [
                        '[male]' => $configs['male'],
                        '[female]' => $configs['female'],
                    ]) . ' : <a href="result.php?f=roft&w=zero&o=naam">' . $countMales . '</a>',
    'countfemales' => "<img src=\"" . PEDIGREE_IMAGE_URL . "/female.gif\"> " . strtr(_MA_PEDIGREE_ADV_TCFE, [
                        '[male]' => $configs['male'],
                        '[female]' => $configs['female'],
                    ]) . ' : <a href="result.php?f=roft&w=1&o=naam">' . $countFemales . '</a>',
    'pienumber' => "<img src=\"" . PEDIGREE_IMAGE_URL . "/numbers.png\">",
    'totpl' => $totpl,
    'books' => $books,
    'orptitle' => _MA_PEDIGREE_ADV_ORPTIT,
    'orpall' => '<a href="result.php?f=father=0 and mother&w=zero&o=naam">' . strtr(_MA_PEDIGREE_ADV_ORPALL, ['[animalTypes]' => $configs['animalTypes']]) . '</a>',
    'orpdad' => '<a href="result.php?f=mother!=0 and father&w=zero&o=naam">' . strtr(_MA_PEDIGREE_ADV_ORPDAD, [
                        '[father]' => $configs['father'],
                        '[animalTypes]' => $configs['animalTypes'],
                    ]) . '</a>',
    'orpmum' => '<a href="result.php?f=father!=0 and mother&w=zero&o=naam">' . strtr(_MA_PEDIGREE_ADV_ORPMUM, [
                              '[mother]' => $configs['mother'],
                              '[animalTypes]' => $configs['animalTypes'],
                          ]) . '</a>',
    'position' => _MA_PEDIGREE_M50_POS,
    'numdogs' => _MA_PEDIGREE_M50_NUMD,
    'maledogs' => $pctMales,
    'femaledogs' => $pctFemales
]);
//comments and footer
include XOOPS_ROOT_PATH . '/footer.php';
