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
pedigree_main($pedId);

//comments and footer
include $GLOBALS['xoops']->path('/footer.php');

//
// Displays the "Main" tab of the module
//
/**
 * @param $ID
 */
function pedigree_main($ID)
{
    global $moduleConfig;

    $queryString = '
    SELECT d.id as d_id,
    d.naam as d_naam,
    d.roft as d_roft,
    f.id as f_id,
    f.naam as f_naam,
    m.id as m_id,
    m.naam as m_naam,
    ff.id as ff_id,
    ff.naam as ff_naam,
    mf.id as mf_id,
    mf.naam as mf_naam,
    fm.id as fm_id,
    fm.naam as fm_naam,
    mm.id as mm_id,
    mm.naam as mm_naam,
    fff.id as fff_id,
    fff.naam as fff_naam,
    ffm.id as ffm_id,
    ffm.naam as ffm_naam,
    fmf.id as fmf_id,
    fmf.naam as fmf_naam,
    fmm.id as fmm_id,
    fmm.naam as fmm_naam,
    mmf.id as mmf_id,
    mmf.naam as mmf_naam,
    mff.id as mff_id,
    mff.naam as mff_naam,
    mfm.id as mfm_id,
    mfm.naam as mfm_naam,
    mmm.id as mmm_id,
    mmm.naam as mmm_naam,
    ffff.id as ffff_id,
    ffff.naam as ffff_naam,
    ffmf.id as ffmf_id,
    ffmf.naam as ffmf_naam,
    fmff.id as fmff_id,
    fmff.naam as fmff_naam,
    fmmf.id as fmmf_id,
    fmmf.naam as fmmf_naam,
    mmff.id as mmff_id,
    mmff.naam as mmff_naam,
    mfff.id as mfff_id,
    mfff.naam as mfff_naam,
    mfmf.id as mfmf_id,
    mfmf.naam as mfmf_naam,
    mmmf.id as mmmf_id,
    mmmf.naam as mmmf_naam,
    fffm.id as fffm_id,
    fffm.naam as fffm_naam,
    ffmm.id as ffmm_id,
    ffmm.naam as ffmm_naam,
    fmfm.id as fmfm_id,
    fmfm.naam as fmfm_naam,
    fmmm.id as fmmm_id,
    fmmm.naam as fmmm_naam,
    mmfm.id as mmfm_id,
    mmfm.naam as mmfm_naam,
    mffm.id as mffm_id,
    mffm.naam as mffm_naam,
    mfmm.id as mfmm_id,
    mfmm.naam as mfmm_naam,
    mmmm.id as mmmm_id,
    mmmm.naam as mmmm_naam
    FROM ' . $GLOBALS['xoopsDB']->prefix('pedigree_tree') . ' d
    LEFT JOIN ' . $GLOBALS['xoopsDB']->prefix('pedigree_tree') . ' f ON d.father = f.id
    LEFT JOIN ' . $GLOBALS['xoopsDB']->prefix('pedigree_tree') . ' m ON d.mother = m.id
    LEFT JOIN ' . $GLOBALS['xoopsDB']->prefix('pedigree_tree') . ' ff ON f.father = ff.id
    LEFT JOIN ' . $GLOBALS['xoopsDB']->prefix('pedigree_tree') . ' fff ON ff.father = fff.id
    LEFT JOIN ' . $GLOBALS['xoopsDB']->prefix('pedigree_tree') . ' ffm ON ff.mother = ffm.id
    LEFT JOIN ' . $GLOBALS['xoopsDB']->prefix('pedigree_tree') . ' mf ON m.father = mf.id
    LEFT JOIN ' . $GLOBALS['xoopsDB']->prefix('pedigree_tree') . ' mff ON mf.father = mff.id
    LEFT JOIN ' . $GLOBALS['xoopsDB']->prefix('pedigree_tree') . ' mfm ON mf.mother = mfm.id
    LEFT JOIN ' . $GLOBALS['xoopsDB']->prefix('pedigree_tree') . ' fm ON f.mother = fm.id
    LEFT JOIN ' . $GLOBALS['xoopsDB']->prefix('pedigree_tree') . ' fmf ON fm.father = fmf.id
    LEFT JOIN ' . $GLOBALS['xoopsDB']->prefix('pedigree_tree') . ' fmm ON fm.mother = fmm.id
    LEFT JOIN ' . $GLOBALS['xoopsDB']->prefix('pedigree_tree') . ' mm ON m.mother = mm.id
    LEFT JOIN ' . $GLOBALS['xoopsDB']->prefix('pedigree_tree') . ' mmf ON mm.father = mmf.id
    LEFT JOIN ' . $GLOBALS['xoopsDB']->prefix('pedigree_tree') . ' mmm ON mm.mother = mmm.id
    LEFT JOIN ' . $GLOBALS['xoopsDB']->prefix('pedigree_tree') . ' ffff ON fff.father = ffff.id
    LEFT JOIN ' . $GLOBALS['xoopsDB']->prefix('pedigree_tree') . ' ffmf ON ffm.father = ffmf.id
    LEFT JOIN ' . $GLOBALS['xoopsDB']->prefix('pedigree_tree') . ' fmff ON fmf.father = fmff.id
    LEFT JOIN ' . $GLOBALS['xoopsDB']->prefix('pedigree_tree') . ' fmmf ON fmm.father = fmmf.id
    LEFT JOIN ' . $GLOBALS['xoopsDB']->prefix('pedigree_tree') . ' mmff ON mmf.father = mmff.id
    LEFT JOIN ' . $GLOBALS['xoopsDB']->prefix('pedigree_tree') . ' mfff ON mff.father = mfff.id
    LEFT JOIN ' . $GLOBALS['xoopsDB']->prefix('pedigree_tree') . ' mfmf ON mfm.father = mfmf.id
    LEFT JOIN ' . $GLOBALS['xoopsDB']->prefix('pedigree_tree') . ' mmmf ON mmm.father = mmmf.id
    LEFT JOIN ' . $GLOBALS['xoopsDB']->prefix('pedigree_tree') . ' fffm ON fff.mother = fffm.id
    LEFT JOIN ' . $GLOBALS['xoopsDB']->prefix('pedigree_tree') . ' ffmm ON ffm.mother = ffmm.id
    LEFT JOIN ' . $GLOBALS['xoopsDB']->prefix('pedigree_tree') . ' fmfm ON fmf.mother = fmfm.id
    LEFT JOIN ' . $GLOBALS['xoopsDB']->prefix('pedigree_tree') . ' fmmm ON fmm.mother = fmmm.id
    LEFT JOIN ' . $GLOBALS['xoopsDB']->prefix('pedigree_tree') . ' mmfm ON mmf.mother = mmfm.id
    LEFT JOIN ' . $GLOBALS['xoopsDB']->prefix('pedigree_tree') . ' mffm ON mff.mother = mffm.id
    LEFT JOIN ' . $GLOBALS['xoopsDB']->prefix('pedigree_tree') . ' mfmm ON mfm.mother = mfmm.id
    LEFT JOIN ' . $GLOBALS['xoopsDB']->prefix('pedigree_tree') . " mmmm ON mmm.mother = mmmm.id
    where d.id=$ID";

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
    $GLOBALS['xoopsTpl']->assign('male', "<img src=\"assets/images/male.gif\">");
    $GLOBALS['xoopsTpl']->assign('female', "<img src=\"assets/images/female.gif\">");
    //assign extra display options
    $GLOBALS['xoopsTpl']->assign('unknown', 'Unknown');
    $GLOBALS['xoopsTpl']->assign('f2', strtr(_MA_PEDIGREE_MPED_F2, array('[animalType]' => $moduleConfig['animalType'])));
    $GLOBALS['xoopsTpl']->assign('f3', strtr(_MA_PEDIGREE_MPED_F3, array('[animalType]' => $moduleConfig['animalType'])));
    $GLOBALS['xoopsTpl']->assign('f4', strtr(_MA_PEDIGREE_MPED_F4, array('[animalType]' => $moduleConfig['animalType'])));
    $GLOBALS['xoopsTpl']->assign('m2', strtr(_MA_PEDIGREE_MPED_M2, array('[animalType]' => $moduleConfig['animalType'])));
    $GLOBALS['xoopsTpl']->assign('m3', strtr(_MA_PEDIGREE_MPED_M3, array('[animalType]' => $moduleConfig['animalType'])));
    $GLOBALS['xoopsTpl']->assign('m4', strtr(_MA_PEDIGREE_MPED_M4, array('[animalType]' => $moduleConfig['animalType'])));
}

/**
 * @param $sex
 * @param $item
 *
 * @return string
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
