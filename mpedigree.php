<?php
// -------------------------------------------------------------------------

require_once dirname(dirname(__DIR__)) . '/mainfile.php';

$moduleDirName = basename(__DIR__);
xoops_loadLanguage('main', $moduleDirName);

// Include any common code for this module.
require_once(XOOPS_ROOT_PATH . '/modules/' . $moduleDirName . '/include/common.php');
xoops_load('XoopsRequest');

$xoopsOption['template_main'] = 'pedigree_mpedigree.tpl';

include $GLOBALS['xoops']->path('/header.php');

//get module configuration
$moduleHandler = xoops_getHandler('module');
$module        = $moduleHandler->getByDirname('pedigree');
$configHandler = xoops_getHandler('config');
$moduleConfig  = $configHandler->getConfigsByCat(0, $module->getVar('mid'));

//always start with Anika
$pedId = XoopsRequest::getInt('pedid', 0, 'GET');
//$pedId = $_GET['pedid'];
//draw pedigree

$queryString = '
SELECT d.Id as d_id,
d.NAAM as d_naam,
d.roft as d_roft,
f.Id as f_id,
f.NAAM as f_naam,
m.Id as m_id,
m.NAAM as m_naam,
ff.Id as ff_id,
ff.NAAM as ff_naam,
mf.Id as mf_id,
mf.NAAM as mf_naam,
fm.Id as fm_id,
fm.NAAM as fm_naam,
mm.Id as mm_id,
mm.NAAM as mm_naam,
fff.Id as fff_id,
fff.NAAM as fff_naam,
ffm.Id as ffm_id,
ffm.NAAM as ffm_naam,
fmf.Id as fmf_id,
fmf.NAAM as fmf_naam,
fmm.Id as fmm_id,
fmm.NAAM as fmm_naam,
mmf.Id as mmf_id,
mmf.NAAM as mmf_naam,
mff.Id as mff_id,
mff.NAAM as mff_naam,
mfm.Id as mfm_id,
mfm.NAAM as mfm_naam,
mmm.Id as mmm_id,
mmm.NAAM as mmm_naam,
ffff.Id as ffff_id,
ffff.NAAM as ffff_naam,
ffmf.Id as ffmf_id,
ffmf.NAAM as ffmf_naam,
fmff.Id as fmff_id,
fmff.NAAM as fmff_naam,
fmmf.Id as fmmf_id,
fmmf.NAAM as fmmf_naam,
mmff.Id as mmff_id,
mmff.NAAM as mmff_naam,
mfff.Id as mfff_id,
mfff.NAAM as mfff_naam,
mfmf.Id as mfmf_id,
mfmf.NAAM as mfmf_naam,
mmmf.Id as mmmf_id,
mmmf.NAAM as mmmf_naam,
fffm.Id as fffm_id,
fffm.NAAM as fffm_naam,
ffmm.Id as ffmm_id,
ffmm.NAAM as ffmm_naam,
fmfm.Id as fmfm_id,
fmfm.NAAM as fmfm_naam,
fmmm.Id as fmmm_id,
fmmm.NAAM as fmmm_naam,
mmfm.Id as mmfm_id,
mmfm.NAAM as mmfm_naam,
mffm.Id as mffm_id,
mffm.NAAM as mffm_naam,
mfmm.Id as mfmm_id,
mfmm.NAAM as mfmm_naam,
mmmm.Id as mmmm_id,
mmmm.NAAM as mmmm_naam
FROM ' . $GLOBALS['xoopsDB']->prefix('pedigree_tree') . ' d
LEFT JOIN ' . $GLOBALS['xoopsDB']->prefix('pedigree_tree') . ' f ON d.father = f.Id
LEFT JOIN ' . $GLOBALS['xoopsDB']->prefix('pedigree_tree') . ' m ON d.mother = m.Id
LEFT JOIN ' . $GLOBALS['xoopsDB']->prefix('pedigree_tree') . ' ff ON f.father = ff.Id
LEFT JOIN ' . $GLOBALS['xoopsDB']->prefix('pedigree_tree') . ' fff ON ff.father = fff.Id
LEFT JOIN ' . $GLOBALS['xoopsDB']->prefix('pedigree_tree') . ' ffm ON ff.mother = ffm.Id
LEFT JOIN ' . $GLOBALS['xoopsDB']->prefix('pedigree_tree') . ' mf ON m.father = mf.Id
LEFT JOIN ' . $GLOBALS['xoopsDB']->prefix('pedigree_tree') . ' mff ON mf.father = mff.Id
LEFT JOIN ' . $GLOBALS['xoopsDB']->prefix('pedigree_tree') . ' mfm ON mf.mother = mfm.Id
LEFT JOIN ' . $GLOBALS['xoopsDB']->prefix('pedigree_tree') . ' fm ON f.mother = fm.Id
LEFT JOIN ' . $GLOBALS['xoopsDB']->prefix('pedigree_tree') . ' fmf ON fm.father = fmf.Id
LEFT JOIN ' . $GLOBALS['xoopsDB']->prefix('pedigree_tree') . ' fmm ON fm.mother = fmm.Id
LEFT JOIN ' . $GLOBALS['xoopsDB']->prefix('pedigree_tree') . ' mm ON m.mother = mm.Id
LEFT JOIN ' . $GLOBALS['xoopsDB']->prefix('pedigree_tree') . ' mmf ON mm.father = mmf.Id
LEFT JOIN ' . $GLOBALS['xoopsDB']->prefix('pedigree_tree') . ' mmm ON mm.mother = mmm.Id
LEFT JOIN ' . $GLOBALS['xoopsDB']->prefix('pedigree_tree') . ' ffff ON fff.father = ffff.Id
LEFT JOIN ' . $GLOBALS['xoopsDB']->prefix('pedigree_tree') . ' ffmf ON ffm.father = ffmf.Id
LEFT JOIN ' . $GLOBALS['xoopsDB']->prefix('pedigree_tree') . ' fmff ON fmf.father = fmff.Id
LEFT JOIN ' . $GLOBALS['xoopsDB']->prefix('pedigree_tree') . ' fmmf ON fmm.father = fmmf.Id
LEFT JOIN ' . $GLOBALS['xoopsDB']->prefix('pedigree_tree') . ' mmff ON mmf.father = mmff.Id
LEFT JOIN ' . $GLOBALS['xoopsDB']->prefix('pedigree_tree') . ' mfff ON mff.father = mfff.Id
LEFT JOIN ' . $GLOBALS['xoopsDB']->prefix('pedigree_tree') . ' mfmf ON mfm.father = mfmf.Id
LEFT JOIN ' . $GLOBALS['xoopsDB']->prefix('pedigree_tree') . ' mmmf ON mmm.father = mmmf.Id
LEFT JOIN ' . $GLOBALS['xoopsDB']->prefix('pedigree_tree') . ' fffm ON fff.mother = fffm.Id
LEFT JOIN ' . $GLOBALS['xoopsDB']->prefix('pedigree_tree') . ' ffmm ON ffm.mother = ffmm.Id
LEFT JOIN ' . $GLOBALS['xoopsDB']->prefix('pedigree_tree') . ' fmfm ON fmf.mother = fmfm.Id
LEFT JOIN ' . $GLOBALS['xoopsDB']->prefix('pedigree_tree') . ' fmmm ON fmm.mother = fmmm.Id
LEFT JOIN ' . $GLOBALS['xoopsDB']->prefix('pedigree_tree') . ' mmfm ON mmf.mother = mmfm.Id
LEFT JOIN ' . $GLOBALS['xoopsDB']->prefix('pedigree_tree') . ' mffm ON mff.mother = mffm.Id
LEFT JOIN ' . $GLOBALS['xoopsDB']->prefix('pedigree_tree') . ' mfmm ON mfm.mother = mfmm.Id
LEFT JOIN ' . $GLOBALS['xoopsDB']->prefix('pedigree_tree') . ' mmmm ON mmm.mother = mmmm.Id
WHERE d.Id=' . $pedId;

$result = $GLOBALS['xoopsDB']->query($queryString);

while (false !== ($row = $GLOBALS['xoopsDB']->fetchArray($result))) {
    //crete array to count frequency (to select colour)
    count_item($freq, $row['d_id']);
    count_item($freq, $row['f_id']);
    count_item($freq, $row['m_id']);
    count_item($freq, $row['ff_id']);
    count_item($freq, $row['fm_id']);
    count_item($freq, $row['mf_id']);
    count_item($freq, $row['mm_id']);
    count_item($freq, $row['fff_id']);
    count_item($freq, $row['ffm_id']);
    count_item($freq, $row['fmf_id']);
    count_item($freq, $row['fmm_id']);
    count_item($freq, $row['mff_id']);
    count_item($freq, $row['mfm_id']);
    count_item($freq, $row['mmf_id']);
    count_item($freq, $row['mmm_id']);
    count_item($freq, $row['ffff_id']);
    count_item($freq, $row['ffmf_id']);
    count_item($freq, $row['fmff_id']);
    count_item($freq, $row['fmmf_id']);
    count_item($freq, $row['mfff_id']);
    count_item($freq, $row['mfmf_id']);
    count_item($freq, $row['mmff_id']);
    count_item($freq, $row['mmmf_id']);
    count_item($freq, $row['fffm_id']);
    count_item($freq, $row['ffmm_id']);
    count_item($freq, $row['fmfm_id']);
    count_item($freq, $row['fmmm_id']);
    count_item($freq, $row['mffm_id']);
    count_item($freq, $row['mfmm_id']);
    count_item($freq, $row['mmfm_id']);
    count_item($freq, $row['mmmm_id']);

    //create array for dog (and all parents)
    //selected dog
    $d['d']['name'] = stripslashes($row['d_naam']);
    $d['d']['id']   = $row['d_id'];
    $d['d']['roft'] = $row['d_roft'];
    $d['d']['col']  = 'transparant';
    //father
    $d['f']['name'] = stripslashes($row['f_naam']);
    $d['f']['id']   = $row['f_id'];
    $d['f']['col']  = crcolour('f', $freq[$row['f_id']]);
    //mother
    $d['m']['name'] = stripslashes($row['m_naam']);
    $d['m']['id']   = $row['m_id'];
    $d['m']['col']  = crcolour('m', $freq[$row['m_id']]);
    //grandparents
    //father father
    $d['ff']['name'] = stripslashes($row['ff_naam']);
    $d['ff']['id']   = $row['ff_id'];
    $d['ff']['col']  = crcolour('f', $freq[$row['ff_id']]);
    //father mother
    $d['fm']['name'] = stripslashes($row['fm_naam']);
    $d['fm']['id']   = $row['fm_id'];
    $d['fm']['col']  = crcolour('m', $freq[$row['fm_id']]);
    //mother father
    $d['mf']['name'] = stripslashes($row['mf_naam']);
    $d['mf']['id']   = $row['mf_id'];
    $d['mf']['col']  = crcolour('f', $freq[$row['mf_id']]);
    //mother mother
    $d['mm']['name'] = stripslashes($row['mm_naam']);
    $d['mm']['id']   = $row['mm_id'];
    $d['mm']['col']  = crcolour('m', $freq[$row['mm_id']]);
    //great-grandparents
    //father father father
    $d['fff']['name'] = stripslashes($row['fff_naam']);
    $d['fff']['id']   = $row['fff_id'];
    $d['fff']['col']  = crcolour('f', $freq[$row['fff_id']]);
    //father father mother
    $d['ffm']['name'] = stripslashes($row['ffm_naam']);
    $d['ffm']['id']   = $row['ffm_id'];
    $d['ffm']['col']  = crcolour('m', $freq[$row['ffm_id']]);
    //father mother father
    $d['fmf']['name'] = stripslashes($row['fmf_naam']);
    $d['fmf']['id']   = $row['fmf_id'];
    $d['fmf']['col']  = crcolour('f', $freq[$row['fmf_id']]);
    //father mother mother
    $d['fmm']['name'] = stripslashes($row['fmm_naam']);
    $d['fmm']['id']   = $row['fmm_id'];
    $d['fmm']['col']  = crcolour('m', $freq[$row['fmm_id']]);
    //mother father father
    $d['mff']['name'] = stripslashes($row['mff_naam']);
    $d['mff']['id']   = $row['mff_id'];
    $d['mff']['col']  = crcolour('f', $freq[$row['mff_id']]);
    //mother father mother
    $d['mfm']['name'] = stripslashes($row['mfm_naam']);
    $d['mfm']['id']   = $row['mfm_id'];
    $d['mfm']['col']  = crcolour('m', $freq[$row['mfm_id']]);
    //mother mother father
    $d['mmf']['name'] = stripslashes($row['mmf_naam']);
    $d['mmf']['id']   = $row['mmf_id'];
    $d['mmf']['col']  = crcolour('f', $freq[$row['mmf_id']]);
    //mother mother mother
    $d['mmm']['name'] = stripslashes($row['mmm_naam']);
    $d['mmm']['id']   = $row['mmm_id'];
    $d['mmm']['col']  = crcolour('m', $freq[$row['mmm_id']]);
    //great-great-grandparents (fathers)
    //father father father
    $d['ffff']['name'] = stripslashes($row['ffff_naam']);
    $d['ffff']['id']   = $row['ffff_id'];
    $d['ffff']['col']  = crcolour('f', $freq[$row['ffff_id']]);
    //father father mother
    $d['ffmf']['name'] = stripslashes($row['ffmf_naam']);
    $d['ffmf']['id']   = $row['ffmf_id'];
    $d['ffmf']['col']  = crcolour('f', $freq[$row['ffmf_id']]);
    //father mother father
    $d['fmff']['name'] = stripslashes($row['fmff_naam']);
    $d['fmff']['id']   = $row['fmff_id'];
    $d['fmff']['col']  = crcolour('f', $freq[$row['fmff_id']]);
    //father mother mother
    $d['fmmf']['name'] = stripslashes($row['fmmf_naam']);
    $d['fmmf']['id']   = $row['fmmf_id'];
    $d['fmmf']['col']  = crcolour('f', $freq[$row['fmmf_id']]);
    //mother father father
    $d['mfff']['name'] = stripslashes($row['mfff_naam']);
    $d['mfff']['id']   = $row['mfff_id'];
    $d['mfff']['col']  = crcolour('f', $freq[$row['mfff_id']]);
    //mother father mother
    $d['mfmf']['name'] = stripslashes($row['mfmf_naam']);
    $d['mfmf']['id']   = $row['mfmf_id'];
    $d['mfmf']['col']  = crcolour('f', $freq[$row['mfmf_id']]);
    //mother mother father
    $d['mmff']['name'] = stripslashes($row['mmff_naam']);
    $d['mmff']['id']   = $row['mmff_id'];
    $d['mmff']['col']  = crcolour('f', $freq[$row['mmff_id']]);
    //mother mother mother
    $d['mmmf']['name'] = stripslashes($row['mmmf_naam']);
    $d['mmmf']['id']   = $row['mmmf_id'];
    $d['mmmf']['col']  = crcolour('f', $freq[$row['mmmf_id']]);
    //great-great-grandparents (mothers)
    //father father father
    $d['fffm']['name'] = stripslashes($row['fffm_naam']);
    $d['fffm']['id']   = $row['fffm_id'];
    $d['fffm']['col']  = crcolour('m', $freq[$row['fffm_id']]);
    //father father mother
    $d['ffmm']['name'] = stripslashes($row['ffmm_naam']);
    $d['ffmm']['id']   = $row['ffmm_id'];
    $d['ffmm']['col']  = crcolour('m', $freq[$row['ffmm_id']]);
    //father mother father
    $d['fmfm']['name'] = stripslashes($row['fmfm_naam']);
    $d['fmfm']['id']   = $row['fmfm_id'];
    $d['fmfm']['col']  = crcolour('m', $freq[$row['fmfm_id']]);
    //father mother mother
    $d['fmmm']['name'] = stripslashes($row['fmmm_naam']);
    $d['fmmm']['id']   = $row['fmmm_id'];
    $d['fmmm']['col']  = crcolour('m', $freq[$row['fmmm_id']]);
    //mother father father
    $d['mffm']['name'] = stripslashes($row['mffm_naam']);
    $d['mffm']['id']   = $row['mffm_id'];
    $d['mffm']['col']  = crcolour('m', $freq[$row['mffm_id']]);
    //mother father mother
    $d['mfmm']['name'] = stripslashes($row['mfmm_naam']);
    $d['mfmm']['id']   = $row['mfmm_id'];
    $d['mfmm']['col']  = crcolour('m', $freq[$row['mfmm_id']]);
    //mother mother father
    $d['mmfm']['name'] = stripslashes($row['mmfm_naam']);
    $d['mmfm']['id']   = $row['mmfm_id'];
    $d['mmfm']['col']  = crcolour('m', $freq[$row['mmfm_id']]);
    //mother mother mother
    $d['mmmm']['name'] = stripslashes($row['mmmm_naam']);
    $d['mmmm']['id']   = $row['mmmm_id'];
    $d['mmmm']['col']  = crcolour('m', $freq[$row['mmmm_id']]);
}

//add data to smarty template
$GLOBALS['xoopsTpl']->assign('xoops_pagetitle', $d['d']['name'] . ' -- mega pedigree');
//assign dog(s)
$GLOBALS['xoopsTpl']->assign('d', $d);
$GLOBALS['xoopsTpl']->assign('male', "<img src=\"assets/images/male.gif\" alt=\"" . ucfirst(_MA_PEDIGREE_MALE) . "\">");
$GLOBALS['xoopsTpl']->assign('female', "<img src=\"assets/images/female.gif\"> alt=\"" . ucfirst(_MA_PEDIGREE_FEMALE) . "\"");
//assign extra display options
$GLOBALS['xoopsTpl']->assign('unknown', _MA_PEDIGREE_UNKNOWN);
$GLOBALS['xoopsTpl']->assign('f2', strtr(_MA_PEDIGREE_MPED_F2, array('[animalType]' => $pedigree->getConfig('animalType'))));
$GLOBALS['xoopsTpl']->assign('f3', strtr(_MA_PEDIGREE_MPED_F3, array('[animalType]' => $pedigree->getConfig('animalType'))));
$GLOBALS['xoopsTpl']->assign('f4', strtr(_MA_PEDIGREE_MPED_F4, array('[animalType]' => $pedigree->getConfig('animalType'))));
$GLOBALS['xoopsTpl']->assign('m2', strtr(_MA_PEDIGREE_MPED_M2, array('[animalType]' => $pedigree->getConfig('animalType'))));
$GLOBALS['xoopsTpl']->assign('m3', strtr(_MA_PEDIGREE_MPED_M3, array('[animalType]' => $pedigree->getConfig('animalType'))));
$GLOBALS['xoopsTpl']->assign('m4', strtr(_MA_PEDIGREE_MPED_M4, array('[animalType]' => $pedigree->getConfig('animalType'))));

//comments and footer
include $GLOBALS['xoops']->path('/footer.php');

/**
 * @param $sex
 * @param $item
 *
 * @return string
 * @todo move this to ./include directory
 */
function crcolour($sex, $item)
{
    if ($item == '1') {
        $col = 'transparant';
    } elseif ($item == '2' && $sex === 'f') {
        $col = '#C8C8FF';
    } elseif ($item == 3 && $sex === 'f') {
        $col = '#6464FF';
    } elseif ($item == '4' && $sex === 'f') {
        $col = '#0000FF';
    } elseif ($item == '2' && $sex === 'm') {
        $col = '#FFC8C8';
    } elseif ($item == '3' && $sex === 'm') {
        $col = '#FF6464';
    } elseif ($item == '4' && $sex === 'm') {
        $col = '#FF0000';
    } else {
        $col = 'transparant';
    }

    return $col;
}

/**
 * @param     $freq
 * @param     $item
 * @param int $inc
 *
 * @return bool
 */
function count_item(&$freq, $item, $inc = 1)
{
    if (!is_array($freq)) {
        $freq = array();
    }
    $freq[$item] = (isset($freq[$item]) ? ($freq[$item] += $inc) : $inc);

    return true;
}
