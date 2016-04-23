<?php
// -------------------------------------------------------------------------

require_once dirname(dirname(__DIR__)) . '/mainfile.php';
//if (file_exists(XOOPS_ROOT_PATH . "/modules/" . $xoopsModule->dirname() . "/language/" . $xoopsConfig['language'] . "/main.php")) {
//    require_once XOOPS_ROOT_PATH . "/modules/" . $xoopsModule->dirname() . "/language/" . $xoopsConfig['language'] . "/main.php";
//} else {
//    include_once XOOPS_ROOT_PATH . "/modules/" . $xoopsModule->dirname() . "/language/english/main.php";
//}

xoops_loadLanguage('main', basename(dirname(__DIR__)));

// Include any common code for this module.
require_once(XOOPS_ROOT_PATH . "/modules/" . $xoopsModule->dirname() . "/include/functions.php");

$xoopsOption['template_main'] = "pedigree_welcome.tpl";
include XOOPS_ROOT_PATH . '/header.php';

global $xoopsTpl, $xoopsDB, $myts;

$myts = MyTextSanitizer::getInstance(); // MyTextSanitizer object

//query to count dogs
$result = $xoopsDB->query("select count(*) from " . $xoopsDB->prefix("pedigree_tree"));
list($numdogs) = $xoopsDB->fetchRow($result);

//get module configuration
$module_handler = xoops_getHandler('module');
$module         = $module_handler->getByDirname("pedigree");
$config_handler = xoops_getHandler('config');
$moduleConfig   = $config_handler->getConfigsByCat(0, $module->getVar('mid'));

$word = $myts->displayTarea(strtr($moduleConfig['welcome'], array('[numanimals]' => $numdogs, '[animalType]' => $moduleConfig['animalType'], '[animalTypes]' => $moduleConfig['animalTypes'])));

$xoopsTpl->assign("welcome", _MA_PEDIGREE_WELCOME);
$xoopsTpl->assign("word", $word);
//comments and footer
include XOOPS_ROOT_PATH . "/footer.php";
