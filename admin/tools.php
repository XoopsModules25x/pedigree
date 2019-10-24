<?php
// -------------------------------------------------------------------------

use Xmf\Request;
use XoopsModules\Pedigree;

require_once dirname(dirname(dirname(__DIR__))) . '/mainfile.php';
xoops_loadLanguage('main', basename(dirname(dirname(__DIR__))));

// Include any common code for this module.
require_once dirname(__DIR__) . '/include/common.php';

$GLOBALS['xoopsOption']['template_main'] = 'pedigree_tools.tpl';

include XOOPS_ROOT_PATH . '/header.php';
//@todo move language string to language file
$GLOBALS['xoopsTpl']->assign('page_title', 'Pedigree database - Add owner/breeder');

//check for access
$xoopsModule = XoopsModule::getByDirname($moduleDirName);
if (empty($GLOBALS['xoopsUser'])) {
    $helper->redirect('admin/index.php', 3, _NOPERM . '<br>' . _MA_PEDIGREE_REGIST);
}

//add JS routines
//@todo change this to send to Tpl addScript
echo "<script type=\"text/javascript\" src=\"assets/js/picker.js\"></script>\n";

//set form to be empty
$form = '';

//get module configuration
/** @var XoopsModuleHandler $moduleHandler */
$moduleHandler = xoops_getHandler('module');
$module = $moduleHandler->getByDirname($moduleDirName);
$configHandler = xoops_getHandler('config');
$moduleConfig = $configHandler->getConfigsByCat(0, $module->getVar('mid'));

$op = Request::getString('op', '', 'GET');
switch ($op) {
    case 'lang':
        lang();
        break;
    case 'langsave':
        langsave();
        break;
    case 'colours':
        colours();
        break;
    case 'settings':
        settings();
        break;
    case 'settingssave':
        settingssave();
        break;
    case 'pro':
        pro();
        break;
    case 'userfields':
        userfields();
        break;
    case 'deleted':
        deleted();
        break;
    case 'delperm':
        delperm($_GET['id']);
        break;
    case 'delall':
        delall();
        break;
    case 'restore':
        restore($_GET['id']);
        break;
    case 'database':
        database();
        $db = true;
        break;
    case 'dbanc':
        database_oa();
        $db = true;
        break;
    case 'fltypar':
        database_fp();
        $db = true;
        break;
    case 'credits':
        credits();
        break;
    default:
        index();
        break;
}

//create tools array
//@todo move language strings to language files
$tools[] = ['title' => 'General settings', 'link' => 'tools.php?op=settings', 'main' => '1'];
if ('1' == $helper->getConfig('proversion')) {
    $tools[] = ['title' => 'Pro-version settings', 'link' => 'tools.php?op=pro', 'main' => '1'];
}
$tools[] = ['title' => 'Language options', 'link' => 'tools.php?op=lang', 'main' => '1'];
$tools[] = ['title' => 'Create user fields', 'link' => 'tools.php?op=userfields', 'main' => '1'];
$tools[] = ['title' => 'Create colours', 'link' => 'tools.php?op=colours', 'main' => '1'];
$tools[] = ['title' => "Deleted pedigree's", 'link' => 'tools.php?op=deleted', 'main' => '1'];
$tools[] = ['title' => 'Database tools', 'link' => 'tools.php?op=database', 'main' => '1'];
if (isset($db)) {
    //create database submenu
    $tools[] = ['title' => 'Own ancestors', 'link' => 'tools.php?op=dbanc', 'main' => '0'];
    $tools[] = ['title' => 'Incorrect gender', 'link' => 'tools.php?op=fltypar', 'main' => '0'];
    $tools[] = ['title' => 'User Queries', 'link' => 'tools.php?op=userq', 'main' => '0'];
}
$tools[] = ['title' => 'Credits', 'link' => 'tools.php?op=credits', 'main' => '1'];
$tools[] = ['title' => 'Logout', 'link' => '../../user.php?op=logout', 'main' => '1'];
//add data (form) to smarty template

$xoopsTpl->assign('tools', $tools);

//footer
include XOOPS_ROOT_PATH . '/footer.php';

function index()
{
    $form = '';
}

/**
 * @todo move language string to language file
 */
function colours()
{
    global $xoopsTpl, $femaleTextColour;
    $form = 'This will be the wizard to create and modify the website colourscheme.<hr>';
    $form .= '<form name="myForm" action=\'savecolors.php\' method=\'POST\'>';
    $form .= $GLOBALS['xoopsSecurity']->getTokenHTML();
    $form .= '<input type="text" id="ftxtcolor" name="ftxtcolor" value="#' . $femaleTextColour . '" size="11" maxlength="7">';
    $form .= '<a href="javascript:TCP.popup(document.forms[\'myForm\'].elements[\'ftxtcolor\'])">';
    $form .= '<img width="15" height="13" border="0" alt="Click here to pick the color" src="img/sel.gif"></a>';
    $form .= '</form>';
    $xoopsTpl->assign('form', $form);
}

/**
 * @todo move language string to language file
 */
function userfields()
{
    global $xoopsTpl;
    $form = 'This will be the wizard to create and modify the custom userfields.<hr>';
    $xoopsTpl->assign('form', $form);
}

/**
 * @todo move language string to language file
 */
function credits()
{
    global $xoopsTpl;
    $form = 'Pedigree database module<br><br><li>Programming : James Cotton<br><li>Design & Layout : Ton van der Hagen<br><br>Technical support :<br><li><a href="mailto:support@animalpedigree.com">support@animalpedigree.com<br><li><a href="http://www.animalpedigree.com">www.animalpedigree.com</a><hr>';
    $xoopsTpl->assign('form', $form);
}

function database()
{
    global $xoopsTpl;
    $form = _MA_PEDIGREE_QUERY_EXPLAN;
    $xoopsTpl->assign('form', $form);
}

/**
 * @todo move language string to language file
 */
function database_oa()
{
    global $xoopsTpl;
    $form = _AM_PEDIGREE_DATABASE_CHECK_ANCESTORS;
    $sql = 'SELECT d.id AS d_id, d.naam AS d_naam
            FROM ' . $GLOBALS['xoopsDB']->prefix('pedigree_tree') . ' d
            LEFT JOIN ' . $GLOBALS['xoopsDB']->prefix('pedigree_tree') . ' m ON m.id = d.mother
            LEFT JOIN ' . $GLOBALS['xoopsDB']->prefix('pedigree_tree') . ' f ON f.id = d.father
            LEFT JOIN ' . $GLOBALS['xoopsDB']->prefix('pedigree_tree') . ' mm ON mm.id = m.mother
            LEFT JOIN ' . $GLOBALS['xoopsDB']->prefix('pedigree_tree') . ' mf ON mf.id = m.father
            LEFT JOIN ' . $GLOBALS['xoopsDB']->prefix('pedigree_tree') . ' fm ON fm.id = f.mother
            LEFT JOIN ' . $GLOBALS['xoopsDB']->prefix('pedigree_tree') . ' ff ON ff.id = f.father
            WHERE
            d.mother = d.id
            OR d.father = d.id
            OR m.mother = d.id
            OR m.father = d.id
            OR f.mother = d.id
            OR f.father = d.id
            OR mm.mother = d.id
            OR mm.father = d.id
            OR mf.mother = d.id
            OR mf.father = d.id
            OR fm.mother = d.id
            OR fm.father = d.id
            OR ff.mother = d.id
            OR ff.father = d.id
            ';
    $result = $GLOBALS['xoopsDB']->query($sql);
    while (false !== ($row = $GLOBALS['xoopsDB']->fetchArray($result))) {
        $form .= '<li><a href="pedigree.php?pedid=' . $row['d_id'] . '">' . $row['d_naam'] . '</a> [own parent or grandparent]<br>';
    }
    $xoopsTpl->assign('form', $form);
}

/**
 * @todo move language string to language file
 */
function database_fp()
{
    global $xoopsTpl;
    $form = _AM_PEDIGREE_DATABASE_CHECK_GENDER;
    $sql = 'SELECT d.id AS d_id, d.naam AS d_naam, m.roft AS m_roft
            FROM ' . $GLOBALS['xoopsDB']->prefix('pedigree_tree') . ' d
            LEFT JOIN ' . $GLOBALS['xoopsDB']->prefix('pedigree_tree') . " m ON m.id = d.mother
            WHERE
            d.mother = m.id
            AND m.roft = '0' ";
    $result = $GLOBALS['xoopsDB']->query($sql);
    while (false !== ($row = $GLOBALS['xoopsDB']->fetchArray($result))) {
        $form .= '<li><a href="dog.php?id=' . $row['d_id'] . '">' . $row['d_naam'] . '</a> [mother seems to be male]<br>';
    }
    $sql = 'SELECT d.id AS d_id, d.naam AS d_naam, f.roft AS f_roft
            FROM ' . $GLOBALS['xoopsDB']->prefix('pedigree_tree') . ' d
            LEFT JOIN ' . $GLOBALS['xoopsDB']->prefix('pedigree_tree') . " f ON f.id = d.father
            WHERE
            d.father = f.id
            AND f.roft = '1' ";
    $result = $GLOBALS['xoopsDB']->query($sql);
    while (false !== ($row = $GLOBALS['xoopsDB']->fetchArray($result))) {
        $form .= '<li><a href="dog.php?id=' . $row['d_id'] . '">' . $row['d_naam'] . '</a> [father seems to be female]<br>';
    }
    $xoopsTpl->assign('form', $form);
}

/**
 * @todo move language string to language file
 */
function pro()
{
    global $xoopsTpl;
    $form = 'Pro version settings go here.<hr>';
    $xoopsTpl->assign('form', $form);
}

/**
 * @todo refactor using {@see Pedigree\Trash} class
 */
function deleted()
{
    global $xoopsTpl, $moduleConfig;
    $form = "Below the line are the animals which have been deleted from your database.<br><br>By clicking on the name you can reinsert them into the database.<br>By clicking on the 'X' in front of the name you can permanently delete the animal.<hr>";
    $sql = 'SELECT id, naam  FROM ' . $GLOBALS['xoopsDB']->prefix('pedigree_trash');
    $result = $GLOBALS['xoopsDB']->query($sql);
    while (false !== ($row = $GLOBALS['xoopsDB']->fetchArray($result))) {
        $form .= '<a href="tools.php?op=delperm&id=' . $row['id'] . '"><img src="assets/images/delete.png"></a>&nbsp;<a href="tools.php?op=restore&id=' . $row['id'] . '">' . $row['naam'] . '</a><br>';
    }
    if ($GLOBALS['xoopsDB']->getRowsNum($result) > 0) {
        $form .= '<hr><a href="tools.php?op=delall">Click here</a> to remove all these ' . $moduleConfig['animalTypes'] . ' permenantly ';
    }
    $xoopsTpl->assign('form', $form);
}

/**
 * @param $id
 * @todo refactor using {@see Pedigree\Trash} class
 */
function delperm($id)
{
    global $xoopsTpl;
    $sql = 'DELETE FROM ' . $GLOBALS['xoopsDB']->prefix('pedigree_trash') . ' WHERE id = ' . $id;
    $GLOBALS['xoopsDB']->queryF($sql);
    deleted();
}

/**
 * @todo refactor using {@see Pedigree\Trash} class
 */
function delall()
{
    global $xoopsTpl;
    $sql = 'DELETE FROM ' . $GLOBALS['xoopsDB']->prefix('pedigree_trash');
    $GLOBALS['xoopsDB']->queryF($sql);
    deleted();
}

/**
 * @param $id
 */
function restore($id)
{
    global $xoopsTpl;
    $sql = 'SELECT * FROM ' . $GLOBALS['xoopsDB']->prefix('pedigree_trash') . ' WHERE id = ' . $id;
    $result = $GLOBALS['xoopsDB']->query($sql);
    while (false !== ($row = $GLOBALS['xoopsDB']->fetchArray($result))) {
        foreach ($row as $key => $values) {
            //          $queryvalues .= "'" . $values . "',";
            $queryvalues .= "'" . $GLOBALS['xoopsDB']->escape($values) . "',";
        }
        $outgoing = substr_replace($queryvalues, '', -1);
        $query = 'INSERT INTO ' . $GLOBALS['xoopsDB']->prefix('pedigree_tree') . ' VALUES (' . $outgoing . ')';
        $GLOBALS['xoopsDB']->queryF($query);
        $delquery = 'DELETE FROM ' . $GLOBALS['xoopsDB']->prefix('pedigree_trash') . ' WHERE id = ' . $id;
        $GLOBALS['xoopsDB']->queryF($delquery);
        $form .= '<li><a href="pedigree.php?pedid=' . $row['id'] . '">' . $row['naam'] . '</a> has been restored into the database.<hr>';
    }
    $xoopsTpl->assign('form', $form);
}

/**
 * @todo move language strings to language file
 */
function settings()
{
    global $xoopsTpl, $moduleConfig;
    include XOOPS_ROOT_PATH . '/class/xoopsformloader.php';
    $form = new \XoopsThemeForm('General settings', 'settings', 'tools.php?op=settingssave', 'POST', 1);
    $form->addElement(new \XoopsFormHiddenToken($name = 'XOOPS_TOKEN_REQUEST', $timeout = 360));
    $select = new \XoopsFormSelect('<b>Number of results per page</b>', 'perpage', $value = $moduleConfig['perpage'], $size = 1, $multiple = false);
    $options = [
        '50' => 50,
        '100' => 100,
        '250' => 250,
        '500' => 500,
        '1000' => 1000,
        '2000' => 2000,
        '5000' => 5000,
        '10000' => 10000,
    ];
    foreach ($options as $key => $values) {
        $select->addOption($key, $name = $values);
    }
    unset($options);
    $form->addElement($select);
    $form->addElement(new \XoopsFormLabel(
        _MA_PEDIGREE_EXPLAIN,
        'This field is used to set the number of results a page will return from a search. If more results are returned extra pages will be created for easy browsing.<br>Set this number higher as your database grows and the number of pages increase.'
    ));
    $radio = new \XoopsFormRadio('<b>Use owner/breeder fields</b>', 'ownerbreeder', $value = $moduleConfig['ownerbreeder']);
    $radio->addOption(1, $name = 'yes');
    $radio->addOption(0, $name = 'no');
    $form->addElement($radio);
    $form->addElement(new \XoopsFormLabel(
        _MA_PEDIGREE_EXPLAIN,
        'Use this field to set if you would like to use the owner/breeder fields of the database.<br>As the name suggests the owner/breeder fields let you record and display information about the owner and or breeder.<br>The owner/breeder menu items will also be affected by this setting.'
    ));
    $radiobr = new \XoopsFormRadio('<b>Show brother & sister field</b>', 'brothers', $value = $moduleConfig['brothers']);
    $radiobr->addOption(1, $name = 'yes');
    $radiobr->addOption(0, $name = 'no');
    $form->addElement($radiobr);
    $form->addElement(new \XoopsFormLabel(_MA_PEDIGREE_EXPLAIN, 'Use this field to set if you would like to use the add a ' . $moduleConfig['litter'] . 'feature.<br>If your chosen animal only has one offspring at a time this feature will not be useful to you.'));
    $radiolit = new \XoopsFormRadio('<b>Use add a ' . $moduleConfig['litter'] . ' feature</b>', 'uselitter', $value = $moduleConfig['uselitter']);
    $radiolit->addOption(1, $name = 'yes');
    $radiolit->addOption(0, $name = 'no');
    $form->addElement($radiolit);
    $form->addElement(new \XoopsFormLabel(_MA_PEDIGREE_EXPLAIN, 'Use this field to set if you would like to display the brothers & sisters field on the detailed ' . $moduleConfig['animalType'] . ' information page.'));
    $radioch = new \XoopsFormRadio('<b>Show ' . $moduleConfig['children'] . ' field</b>', 'pups', $value = $moduleConfig['pups']);
    $radioch->addOption(1, $name = 'yes');
    $radioch->addOption(0, $name = 'no');
    $form->addElement($radioch);
    $form->addElement(new \XoopsFormLabel(_MA_PEDIGREE_EXPLAIN, 'Use this field to set if you would like to display the ' . $moduleConfig['children'] . ' field on the detailed ' . $moduleConfig['animalType'] . ' information page.'));
    $form->addElement(new \XoopsFormButton('', 'button_id', 'Submit', 'submit'));
    $xoopsTpl->assign('form', $form->render());
}

/**
 * @todo move language string to language file
 */
function settingssave()
{
    global $xoopsTpl;
    $form = '';
    $settings = ['perpage', 'ownerbreeder', 'brothers', 'uselitter', 'pups'];
    foreach ($_POST as $key => $values) {
        if (in_array($key, $settings)) {
            //          $query = 'UPDATE ' . $GLOBALS['xoopsDB']->prefix('config') . " SET conf_value = '" . $values . "' WHERE conf_name = '" . $key . "'";
            $query = 'UPDATE ' . $GLOBALS['xoopsDB']->prefix('config') . " SET conf_value = '" . $GLOBALS['xoopsDB']->escape($values) . "' WHERE conf_name = '" . $GLOBALS['xoopsDB']->escape($key) . "'";
            $GLOBALS['xoopsDB']->query($query);
        }
    }
    $form .= 'Your settings have been saved.<hr>';
    $xoopsTpl->assign('form', $form);
}

/**
 * @todo move language string to language file
 */
function lang()
{
    global $xoopsTpl, $moduleConfig;
    include XOOPS_ROOT_PATH . '/class/xoopsformloader.php';
    $form = new \XoopsThemeForm('Language options', 'language', 'tools.php?op=langsave', 'post', true);
    $form->addElement(new \XoopsFormHiddenToken($name = 'XOOPS_TOKEN_REQUEST', $timeout = 360));
    $form->addElement(new \XoopsFormText('<b>type of animal</b>', 'animalType', $size = 50, $maxsize = 255, $value = $moduleConfig['animalType']));
    $form->addElement(new \XoopsFormLabel(_MA_PEDIGREE_EXPLAIN, 'Use this field to set the animal type which will be used in the application.<br><i>example : </i>snake, pigeon, dog, owl<br><br>The value should fit in the sentences below.<br>Please add optional information for this <b>'
                                                               . $moduleConfig['animalType']
                                                               . '</b>.<br>Select the first letter of the <b>'
                                                               . $moduleConfig['animalType']
                                                               . '</b>.'));
    $form->addElement(new \XoopsFormText('<b>type of animal</b>', 'animalTypes', $size = 50, $maxsize = 255, $value = $value = $moduleConfig['animalTypes']));
    $form->addElement(new \XoopsFormLabel(_MA_PEDIGREE_EXPLAIN, 'Use this field to set the animal type which will be used in the application.<br>This field is the plural of the previous field<br><i>example : </i>snakes, pigeons, dogs, owls<br><br>The value should fit in the sentence below.<br>No <b>'
                                                               . $moduleConfig['animalTypes']
                                                               . '</b> meeting your query have been found.'));
    $form->addElement(new \XoopsFormText('<b>male</b>', 'male', $size = 50, $maxsize = 255, $value = $moduleConfig['male']));
    $form->addElement(new \XoopsFormLabel(_MA_PEDIGREE_EXPLAIN, 'Use this field to set the name used for the male animal.<br><i>example : </i>male, buck, sire etc.'));
    $form->addElement(new \XoopsFormText('<b>female</b>', 'female', $size = 50, $maxsize = 255, $value = $moduleConfig['female']));
    $form->addElement(new \XoopsFormLabel(_MA_PEDIGREE_EXPLAIN, 'Use this field to set the name used for the female animal.<br><i>example : </i>female, vixen, dam etc.'));
    $form->addElement(new \XoopsFormText('<b>children</b>', 'children', $size = 50, $maxsize = 255, $value = $moduleConfig['children']));
    $form->addElement(new \XoopsFormLabel(_MA_PEDIGREE_EXPLAIN, 'Use this field to set the name used for children of this type of animal (' . $moduleConfig['animalTypes'] . ').<br><i>example : </i>pups, cubs, kittens etc.'));
    $form->addElement(new \XoopsFormText('<b>mother</b>', 'mother', $size = 50, $maxsize = 255, $value = $moduleConfig['mother']));
    $form->addElement(new \XoopsFormLabel(_MA_PEDIGREE_EXPLAIN, 'Use this field to set the name used for mother of this type of animal (' . $moduleConfig['animalTypes'] . ').<br><i>example : </i>dam, mare etc.'));
    $form->addElement(new \XoopsFormText('<b>father</b>', 'father', $size = 50, $maxsize = 255, $value = $moduleConfig['father']));
    $form->addElement(new \XoopsFormLabel(_MA_PEDIGREE_EXPLAIN, 'Use this field to set the name used for father of this type of animal (' . $moduleConfig['animalTypes'] . ').<br><i>example : </i>sire, stallion etc.'));
    $form->addElement(new \XoopsFormText('<b>litter</b>', 'litter', $size = 50, $maxsize = 255, $value = $moduleConfig['litter']));
    $form->addElement(new \XoopsFormLabel(_MA_PEDIGREE_EXPLAIN, 'Use this field to set the name used for a collection of newborn animals.<br><i>example : </i>litter, nest etc.'));
    $form->addElement(new \XoopsFormTextArea('<b>Welcome text</b>', 'welcome', $value = $moduleConfig['welcome'], $rows = 15, $cols = 50));

    $form->addElement(new \XoopsFormLabel(_MA_PEDIGREE_EXPLAIN, 'Use this field to set the text you would like to display for the welcome page.<br><br>You may use the follwing variables :<br>[animalType] = '
                                                               . $moduleConfig['animalType']
                                                               . '<br>[animalTypes] ='
                                                               . $moduleConfig['animalTypes']
                                                               . '<br>[numanimals] = number of animals in the database.'));
    $form->addElement(new \XoopsFormButton('', 'button_id', 'Submit', 'submit'));
    $xoopsTpl->assign('form', $form->render());
}

/**
 * @todo move language string to language file
 */
function langsave()
{
    global $xoopsTpl;
    $form = '';
    $settings = [
        'animalType',
        'animalTypes',
        'male',
        'female',
        'children',
        'mother',
        'father',
        'litter',
        'welcome',
    ];
    foreach ($_POST as $key => $values) {
        if (in_array($key, $settings)) {
            //          $query = 'UPDATE ' . $GLOBALS['xoopsDB']->prefix('config') . " SET conf_value = '" . $values . "' WHERE conf_name = '" . $key . "'";
            $query = 'UPDATE ' . $GLOBALS['xoopsDB']->prefix('config') . " SET conf_value = '" . $GLOBALS['xoopsDB']->escape($values) . "' WHERE conf_name = '" . $GLOBALS['xoopsDB']->escape($key) . "'";
            $GLOBALS['xoopsDB']->query($query);
        }
    }
    $form .= 'Your settings have been saved.<hr>';
    $xoopsTpl->assign('form', $form);
}
