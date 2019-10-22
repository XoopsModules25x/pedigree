<?php

require_once dirname(dirname(dirname(__DIR__))) . '/include/cp_header.php';
xoops_loadLanguage('modinfo', basename(dirname(dirname(__DIR__))));

require_once XOOPS_ROOT_PATH . '/modules/' . $xoopsModule->dirname() . '/admin/menu.php';

xoops_cp_header();

$colourString = mb_substr($_POST['mainbgcolor'], 1)
                . ';'
                . mb_substr($_POST['sbgcolor'], 1)
                . ';'
                . mb_substr($_POST['stxtcolor'], 1)
                . ';'
                . $_POST['sfont']
                . ';'
                . $_POST['sfontsize']
                . ';'
                . $_POST['sfontstyle']
                . ';'
                . mb_substr($_POST['mbgcolor'], 1)
                . ';'
                . mb_substr($_POST['mtxtcolor'], 1)
                . ';'
                . $_POST['mfont']
                . ';'
                . $_POST['mfontsize']
                . ';'
                . $_POST['mfontstyle']
                . ';'
                . mb_substr($_POST['fbgcolor'], 1)
                . ';'
                . mb_substr($_POST['ftxtcolor'], 1)
                . ';'
                . $_POST['ffont']
                . ';'
                . $_POST['ffontsize']
                . ';'
                . $_POST['ffontstyle']
                . ';'
                . $_POST['bstyle']
                . ';'
                . mb_substr($_POST['bwidth'], 0, 1)
                . ';'
                . mb_substr($_POST['bcolor'], 1);

//$sql = 'UPDATE ' . $GLOBALS['xoopsDB']->prefix('config') . " SET conf_value='" . $colourString . "' WHERE conf_name = 'pedigreeColours'";
//$GLOBALS['xoopsDB']->queryf($sql);
$sql = 'UPDATE ' . $GLOBALS['xoopsDB']->prefix('config') . " SET conf_value='" . $GLOBALS['xoopsDB']->escape($colourString) . "' WHERE conf_name = 'pedigreeColours'";
$GLOBALS['xoopsDB']->queryF($sql);
//@todo move hard coded language string to language file(s)
$helper->redirect('admin/colors.php', 3, 'Your settings have been saved...');

xoops_cp_footer();
