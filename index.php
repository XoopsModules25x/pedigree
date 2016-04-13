<?php
// -------------------------------------------------------------------------

$currentFile = pathinfo(__FILE__, PATHINFO_BASENAME);
include __DIR__ . '/header.php';

require_once dirname(dirname(__DIR__)) . '/mainfile.php';

/*
if (file_exists(XOOPS_ROOT_PATH . "/modules/" . $xoopsModule->dirname() . "/language/" . $xoopsConfig['language'] . "/main.php")) {
    require_once XOOPS_ROOT_PATH . "/modules/" . $xoopsModule->dirname() . "/language/" . $xoopsConfig['language'] . "/main.php";
} else {
    include_once XOOPS_ROOT_PATH . "/modules/" . $xoopsModule->dirname() . "/language/english/main.php";
}
*/

xoops_loadLanguage('main', basename(dirname(__DIR__)));

// Include any common code for this module.
require_once(XOOPS_ROOT_PATH . "/modules/" . $xoopsModule->dirname() . "/include/functions.php");
require_once(XOOPS_ROOT_PATH . "/modules/" . $xoopsModule->dirname() . "/include/class_field.php");

$xoopsOption['template_main'] = "pedigree_index.tpl";

include XOOPS_ROOT_PATH . '/header.php';

$xoTheme->addScript(XOOPS_URL . '/browse.php?Frameworks/jquery/jquery.js');
$xoTheme->addScript(PEDIGREE_URL . '/assets/js/jquery.ThickBox/thickbox-compressed.js');

$xoTheme->addScript(PEDIGREE_URL . '/assets/js/jquery.magnific-popup.min.js');
$xoTheme->addScript(PEDIGREE_URL . '/assets/js/colpick.js');

$xoTheme->addStylesheet(PEDIGREE_URL . '/assets/js/colpick.css');
$xoTheme->addStylesheet(PEDIGREE_URL . '/assets/js/magnific-popup.css');

$xoTheme->addStylesheet(PEDIGREE_URL . '/assets/js/jquery.ThickBox/thickbox.css');
$xoTheme->addStylesheet(PEDIGREE_URL . '/module.css');

$xoopsTpl->assign('pedigree_url', PEDIGREE_URL . '/');

// Breadcrumb
$breadcrumb = new PedigreeBreadcrumb();
$breadcrumb->addLink($pedigree->getModule()->getVar('name'), PEDIGREE_URL);

$xoopsTpl->assign('module_home', pedigree_module_home(false)); // this definition is not removed for backward compatibility issues
$xoopsTpl->assign('pedigree_breadcrumb', $breadcrumb->render());

//get module configuration
$module_handler =& xoops_gethandler('module');
$module         =& $module_handler->getByDirname("pedigree");
$config_handler =& xoops_gethandler('config');
$moduleConfig   =& $config_handler->getConfigsByCat(0, $module->getVar('mid'));

index_main();

//footer
include XOOPS_ROOT_PATH . "/footer.php";

// Displays the "Main" tab of the module
function index_main()
{
    global $xoopsTpl, $moduleConfig;

    //create animal object
    $animal = new Animal();

    //test to find out how many user fields there are..
    $fields = $animal->numoffields();

    for ($i = 0; $i < count($fields); ++$i) {
        $userfield = new Field($fields[$i], $animal->getconfig());
        if ($userfield->active() && $userfield->hassearch()) {
            $fieldType   = $userfield->getSetting("FieldType");
            $fieldobject = new $fieldType($userfield, $animal);
            $function    = "user" . $fields[$i] . $fieldobject->getsearchstring();
            //echo $function."<br />";
            $usersearch[] = array(
                'title'       => $userfield->getSetting("SearchName"),
                'searchid'    => "user" . $fields[$i],
                'function'    => $function,
                'explenation' => $userfield->getSetting("SearchExplenation"),
                'searchfield' => $fieldobject->searchfield()
            );
        }
    }

    //add data to smarty template
    $xoopsTpl->assign("sselect", strtr(_MA_PEDIGREE_SELECT, array('[animalType]' => $moduleConfig['animalType'])));
    $xoopsTpl->assign("explain", _MA_PEDIGREE_EXPLAIN);
    $xoopsTpl->assign("sname", _MA_PEDIGREE_SEARCHNAME);
    $xoopsTpl->assign("snameex", strtr(_MA_PEDIGREE_SEARCHNAME_EX, array('[animalTypes]' => $moduleConfig['animalTypes'])));
    $xoopsTpl->assign("usersearch", (isset($usersearch) ? $usersearch : ''));
}
