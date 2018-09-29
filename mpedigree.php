<?php
// -------------------------------------------------------------------------

use Xmf\Request;
use XoopsModules\Pedigree;

//require_once  dirname(dirname(__DIR__)) . '/mainfile.php';
require_once __DIR__ . '/header.php';

$moduleDirName = basename(__DIR__);
xoops_loadLanguage('main', $moduleDirName);

// Include any common code for this module.
require_once XOOPS_ROOT_PATH . '/modules/' . $moduleDirName . '/include/common.php';

$GLOBALS['xoopsOption']['template_main'] = 'pedigree_mpedigree.tpl';

require_once $GLOBALS['xoops']->path('/header.php');

//get module configuration
/** @var \XoopsModuleHandler $moduleHandler */
$moduleHandler = xoops_getHandler('module');
$module        = $moduleHandler->getByDirname($moduleDirName);
$configHandler = xoops_getHandler('config');
$moduleConfig  = $configHandler->getConfigsByCat(0, $module->getVar('mid'));

//always start with Anika
$pedId = Request::getInt('pedid', 0, 'GET');
//$pedId = $_GET['pedid'];
//draw pedigree

$sql = '
SELECT d.id as d_id,
d.pname as d_pname,
d.roft as d_roft,
f.id as f_id,
f.pname as f_pname,
m.id as m_id,
m.pname as m_pname,
ff.id as ff_id,
ff.pname as ff_pname,
mf.id as mf_id,
mf.pname as mf_pname,
fm.id as fm_id,
fm.pname as fm_pname,
mm.id as mm_id,
mm.pname as mm_pname,
fff.id as fff_id,
fff.pname as fff_pname,
ffm.id as ffm_id,
ffm.pname as ffm_pname,
fmf.id as fmf_id,
fmf.pname as fmf_pname,
fmm.id as fmm_id,
fmm.pname as fmm_pname,
mmf.id as mmf_id,
mmf.pname as mmf_pname,
mff.id as mff_id,
mff.pname as mff_pname,
mfm.id as mfm_id,
mfm.pname as mfm_pname,
mmm.id as mmm_id,
mmm.pname as mmm_pname,
ffff.id as ffff_id,
ffff.pname as ffff_pname,
ffmf.id as ffmf_id,
ffmf.pname as ffmf_pname,
fmff.id as fmff_id,
fmff.pname as fmff_pname,
fmmf.id as fmmf_id,
fmmf.pname as fmmf_pname,
mmff.id as mmff_id,
mmff.pname as mmff_pname,
mfff.id as mfff_id,
mfff.pname as mfff_pname,
mfmf.id as mfmf_id,
mfmf.pname as mfmf_pname,
mmmf.id as mmmf_id,
mmmf.pname as mmmf_pname,
fffm.id as fffm_id,
fffm.pname as fffm_pname,
ffmm.id as ffmm_id,
ffmm.pname as ffmm_pname,
fmfm.id as fmfm_id,
fmfm.pname as fmfm_pname,
fmmm.id as fmmm_id,
fmmm.pname as fmmm_pname,
mmfm.id as mmfm_id,
mmfm.pname as mmfm_pname,
mffm.id as mffm_id,
mffm.pname as mffm_pname,
mfmm.id as mfmm_id,
mfmm.pname as mfmm_pname,
mmmm.id as mmmm_id,
mmmm.pname as mmmm_pname
    FROM ' . $GLOBALS['xoopsDB']->prefix('pedigree_registry') . ' d
LEFT JOIN ' . $GLOBALS['xoopsDB']->prefix('pedigree_registry') . ' f ON d.father = f.id
LEFT JOIN ' . $GLOBALS['xoopsDB']->prefix('pedigree_registry') . ' m ON d.mother = m.id
LEFT JOIN ' . $GLOBALS['xoopsDB']->prefix('pedigree_registry') . ' ff ON f.father = ff.id
LEFT JOIN ' . $GLOBALS['xoopsDB']->prefix('pedigree_registry') . ' fff ON ff.father = fff.id
LEFT JOIN ' . $GLOBALS['xoopsDB']->prefix('pedigree_registry') . ' ffm ON ff.mother = ffm.id
LEFT JOIN ' . $GLOBALS['xoopsDB']->prefix('pedigree_registry') . ' mf ON m.father = mf.id
LEFT JOIN ' . $GLOBALS['xoopsDB']->prefix('pedigree_registry') . ' mff ON mf.father = mff.id
LEFT JOIN ' . $GLOBALS['xoopsDB']->prefix('pedigree_registry') . ' mfm ON mf.mother = mfm.id
LEFT JOIN ' . $GLOBALS['xoopsDB']->prefix('pedigree_registry') . ' fm ON f.mother = fm.id
LEFT JOIN ' . $GLOBALS['xoopsDB']->prefix('pedigree_registry') . ' fmf ON fm.father = fmf.id
LEFT JOIN ' . $GLOBALS['xoopsDB']->prefix('pedigree_registry') . ' fmm ON fm.mother = fmm.id
LEFT JOIN ' . $GLOBALS['xoopsDB']->prefix('pedigree_registry') . ' mm ON m.mother = mm.id
LEFT JOIN ' . $GLOBALS['xoopsDB']->prefix('pedigree_registry') . ' mmf ON mm.father = mmf.id
LEFT JOIN ' . $GLOBALS['xoopsDB']->prefix('pedigree_registry') . ' mmm ON mm.mother = mmm.id
LEFT JOIN ' . $GLOBALS['xoopsDB']->prefix('pedigree_registry') . ' ffff ON fff.father = ffff.id
LEFT JOIN ' . $GLOBALS['xoopsDB']->prefix('pedigree_registry') . ' ffmf ON ffm.father = ffmf.id
LEFT JOIN ' . $GLOBALS['xoopsDB']->prefix('pedigree_registry') . ' fmff ON fmf.father = fmff.id
LEFT JOIN ' . $GLOBALS['xoopsDB']->prefix('pedigree_registry') . ' fmmf ON fmm.father = fmmf.id
LEFT JOIN ' . $GLOBALS['xoopsDB']->prefix('pedigree_registry') . ' mmff ON mmf.father = mmff.id
LEFT JOIN ' . $GLOBALS['xoopsDB']->prefix('pedigree_registry') . ' mfff ON mff.father = mfff.id
LEFT JOIN ' . $GLOBALS['xoopsDB']->prefix('pedigree_registry') . ' mfmf ON mfm.father = mfmf.id
LEFT JOIN ' . $GLOBALS['xoopsDB']->prefix('pedigree_registry') . ' mmmf ON mmm.father = mmmf.id
LEFT JOIN ' . $GLOBALS['xoopsDB']->prefix('pedigree_registry') . ' fffm ON fff.mother = fffm.id
LEFT JOIN ' . $GLOBALS['xoopsDB']->prefix('pedigree_registry') . ' ffmm ON ffm.mother = ffmm.id
LEFT JOIN ' . $GLOBALS['xoopsDB']->prefix('pedigree_registry') . ' fmfm ON fmf.mother = fmfm.id
LEFT JOIN ' . $GLOBALS['xoopsDB']->prefix('pedigree_registry') . ' fmmm ON fmm.mother = fmmm.id
LEFT JOIN ' . $GLOBALS['xoopsDB']->prefix('pedigree_registry') . ' mmfm ON mmf.mother = mmfm.id
LEFT JOIN ' . $GLOBALS['xoopsDB']->prefix('pedigree_registry') . ' mffm ON mff.mother = mffm.id
LEFT JOIN ' . $GLOBALS['xoopsDB']->prefix('pedigree_registry') . ' mfmm ON mfm.mother = mfmm.id
LEFT JOIN ' . $GLOBALS['xoopsDB']->prefix('pedigree_registry') . ' mmmm ON mmm.mother = mmmm.id
WHERE d.id=' . $pedId;

$result = $GLOBALS['xoopsDB']->query($sql);

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
    $d['d']['name'] = stripslashes($row['d_pname']);
    $d['d']['id']   = $row['d_id'];
    $d['d']['roft'] = $row['d_roft'];
    $d['d']['col']  = 'transparant';
    //father
    $d['f']['name'] = stripslashes($row['f_pname']);
    $d['f']['id']   = $row['f_id'];
    $d['f']['col']  = crcolour('f', $freq[$row['f_id']]);
    //mother
    $d['m']['name'] = stripslashes($row['m_pname']);
    $d['m']['id']   = $row['m_id'];
    $d['m']['col']  = crcolour('m', $freq[$row['m_id']]);
    //grandparents
    //father father
    $d['ff']['name'] = stripslashes($row['ff_pname']);
    $d['ff']['id']   = $row['ff_id'];
    $d['ff']['col']  = crcolour('f', $freq[$row['ff_id']]);
    //father mother
    $d['fm']['name'] = stripslashes($row['fm_pname']);
    $d['fm']['id']   = $row['fm_id'];
    $d['fm']['col']  = crcolour('m', $freq[$row['fm_id']]);
    //mother father
    $d['mf']['name'] = stripslashes($row['mf_pname']);
    $d['mf']['id']   = $row['mf_id'];
    $d['mf']['col']  = crcolour('f', $freq[$row['mf_id']]);
    //mother mother
    $d['mm']['name'] = stripslashes($row['mm_pname']);
    $d['mm']['id']   = $row['mm_id'];
    $d['mm']['col']  = crcolour('m', $freq[$row['mm_id']]);
    //great-grandparents
    //father father father
    $d['fff']['name'] = stripslashes($row['fff_pname']);
    $d['fff']['id']   = $row['fff_id'];
    $d['fff']['col']  = crcolour('f', $freq[$row['fff_id']]);
    //father father mother
    $d['ffm']['name'] = stripslashes($row['ffm_pname']);
    $d['ffm']['id']   = $row['ffm_id'];
    $d['ffm']['col']  = crcolour('m', $freq[$row['ffm_id']]);
    //father mother father
    $d['fmf']['name'] = stripslashes($row['fmf_pname']);
    $d['fmf']['id']   = $row['fmf_id'];
    $d['fmf']['col']  = crcolour('f', $freq[$row['fmf_id']]);
    //father mother mother
    $d['fmm']['name'] = stripslashes($row['fmm_pname']);
    $d['fmm']['id']   = $row['fmm_id'];
    $d['fmm']['col']  = crcolour('m', $freq[$row['fmm_id']]);
    //mother father father
    $d['mff']['name'] = stripslashes($row['mff_pname']);
    $d['mff']['id']   = $row['mff_id'];
    $d['mff']['col']  = crcolour('f', $freq[$row['mff_id']]);
    //mother father mother
    $d['mfm']['name'] = stripslashes($row['mfm_pname']);
    $d['mfm']['id']   = $row['mfm_id'];
    $d['mfm']['col']  = crcolour('m', $freq[$row['mfm_id']]);
    //mother mother father
    $d['mmf']['name'] = stripslashes($row['mmf_pname']);
    $d['mmf']['id']   = $row['mmf_id'];
    $d['mmf']['col']  = crcolour('f', $freq[$row['mmf_id']]);
    //mother mother mother
    $d['mmm']['name'] = stripslashes($row['mmm_pname']);
    $d['mmm']['id']   = $row['mmm_id'];
    $d['mmm']['col']  = crcolour('m', $freq[$row['mmm_id']]);
    //great-great-grandparents (fathers)
    //father father father
    $d['ffff']['name'] = stripslashes($row['ffff_pname']);
    $d['ffff']['id']   = $row['ffff_id'];
    $d['ffff']['col']  = crcolour('f', $freq[$row['ffff_id']]);
    //father father mother
    $d['ffmf']['name'] = stripslashes($row['ffmf_pname']);
    $d['ffmf']['id']   = $row['ffmf_id'];
    $d['ffmf']['col']  = crcolour('f', $freq[$row['ffmf_id']]);
    //father mother father
    $d['fmff']['name'] = stripslashes($row['fmff_pname']);
    $d['fmff']['id']   = $row['fmff_id'];
    $d['fmff']['col']  = crcolour('f', $freq[$row['fmff_id']]);
    //father mother mother
    $d['fmmf']['name'] = stripslashes($row['fmmf_pname']);
    $d['fmmf']['id']   = $row['fmmf_id'];
    $d['fmmf']['col']  = crcolour('f', $freq[$row['fmmf_id']]);
    //mother father father
    $d['mfff']['name'] = stripslashes($row['mfff_pname']);
    $d['mfff']['id']   = $row['mfff_id'];
    $d['mfff']['col']  = crcolour('f', $freq[$row['mfff_id']]);
    //mother father mother
    $d['mfmf']['name'] = stripslashes($row['mfmf_pname']);
    $d['mfmf']['id']   = $row['mfmf_id'];
    $d['mfmf']['col']  = crcolour('f', $freq[$row['mfmf_id']]);
    //mother mother father
    $d['mmff']['name'] = stripslashes($row['mmff_pname']);
    $d['mmff']['id']   = $row['mmff_id'];
    $d['mmff']['col']  = crcolour('f', $freq[$row['mmff_id']]);
    //mother mother mother
    $d['mmmf']['name'] = stripslashes($row['mmmf_pname']);
    $d['mmmf']['id']   = $row['mmmf_id'];
    $d['mmmf']['col']  = crcolour('f', $freq[$row['mmmf_id']]);
    //great-great-grandparents (mothers)
    //father father father
    $d['fffm']['name'] = stripslashes($row['fffm_pname']);
    $d['fffm']['id']   = $row['fffm_id'];
    $d['fffm']['col']  = crcolour('m', $freq[$row['fffm_id']]);
    //father father mother
    $d['ffmm']['name'] = stripslashes($row['ffmm_pname']);
    $d['ffmm']['id']   = $row['ffmm_id'];
    $d['ffmm']['col']  = crcolour('m', $freq[$row['ffmm_id']]);
    //father mother father
    $d['fmfm']['name'] = stripslashes($row['fmfm_pname']);
    $d['fmfm']['id']   = $row['fmfm_id'];
    $d['fmfm']['col']  = crcolour('m', $freq[$row['fmfm_id']]);
    //father mother mother
    $d['fmmm']['name'] = stripslashes($row['fmmm_pname']);
    $d['fmmm']['id']   = $row['fmmm_id'];
    $d['fmmm']['col']  = crcolour('m', $freq[$row['fmmm_id']]);
    //mother father father
    $d['mffm']['name'] = stripslashes($row['mffm_pname']);
    $d['mffm']['id']   = $row['mffm_id'];
    $d['mffm']['col']  = crcolour('m', $freq[$row['mffm_id']]);
    //mother father mother
    $d['mfmm']['name'] = stripslashes($row['mfmm_pname']);
    $d['mfmm']['id']   = $row['mfmm_id'];
    $d['mfmm']['col']  = crcolour('m', $freq[$row['mfmm_id']]);
    //mother mother father
    $d['mmfm']['name'] = stripslashes($row['mmfm_pname']);
    $d['mmfm']['id']   = $row['mmfm_id'];
    $d['mmfm']['col']  = crcolour('m', $freq[$row['mmfm_id']]);
    //mother mother mother
    $d['mmmm']['name'] = stripslashes($row['mmmm_pname']);
    $d['mmmm']['id']   = $row['mmmm_id'];
    $d['mmmm']['col']  = crcolour('m', $freq[$row['mmmm_id']]);
}

//add data to smarty template
$GLOBALS['xoopsTpl']->assign('xoops_pagetitle', $d['d']['name'] . ' -- mega pedigree');
//assign dog(s)
$GLOBALS['xoopsTpl']->assign('d', $d);
$GLOBALS['xoopsTpl']->assign('male', '<img src="assets/images/male.gif">');
$GLOBALS['xoopsTpl']->assign('female', '<img src="assets/images/female.gif">');
//assign extra display options
$GLOBALS['xoopsTpl']->assign('unknown', 'Unknown');
$GLOBALS['xoopsTpl']->assign('f2', strtr(_MA_PEDIGREE_MPED_F2, ['[animalType]' => $helper->$getConfig('animalType')]));
$GLOBALS['xoopsTpl']->assign('f3', strtr(_MA_PEDIGREE_MPED_F3, ['[animalType]' => $helper->$getConfig('animalType')]));
$GLOBALS['xoopsTpl']->assign('f4', strtr(_MA_PEDIGREE_MPED_F4, ['[animalType]' => $helper->$getConfig('animalType')]));
$GLOBALS['xoopsTpl']->assign('m2', strtr(_MA_PEDIGREE_MPED_M2, ['[animalType]' => $helper->$getConfig('animalType')]));
$GLOBALS['xoopsTpl']->assign('m3', strtr(_MA_PEDIGREE_MPED_M3, ['[animalType]' => $helper->$getConfig('animalType')]));
$GLOBALS['xoopsTpl']->assign('m4', strtr(_MA_PEDIGREE_MPED_M4, ['[animalType]' => $helper->$getConfig('animalType')]));

/**
 * @param $sex
 * @param $item
 *
 * @return string
 * @todo move this to ./include directory
 */
function crcolour($sex, $item)
{
    if ('1' == $item) {
        $col = 'transparant';
    } elseif ('2' == $item && 'f' === $sex) {
        $col = '#C8C8FF';
    } elseif (3 == $item && 'f' === $sex) {
        $col = '#6464FF';
    } elseif ('4' == $item && 'f' === $sex) {
        $col = '#0000FF';
    } elseif ('2' == $item && 'm' === $sex) {
        $col = '#FFC8C8';
    } elseif ('3' == $item && 'm' === $sex) {
        $col = '#FF6464';
    } elseif ('4' == $item && 'm' === $sex) {
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
        $freq = [];
    }
    $freq[$item] = (isset($freq[$item]) ? ($freq[$item] += $inc) : $inc);

    return true;
}
