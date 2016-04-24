<?php
// -------------------------------------------------------------------------

include __DIR__ . '/header.php';
require_once dirname(dirname(__DIR__)) . '/mainfile.php';

$moduleDirName = basename(__DIR__);
xoops_loadLanguage('main', $moduleDirName);

// Include any common code for this module.
require_once(XOOPS_ROOT_PATH . '/modules/' . $moduleDirName . '/include/common.php');
require_once $GLOBALS['xoops']->path('modules/' . $xoopsModule->dirname() . '/include/class_field.php');

$xoopsOption['template_main'] = 'pedigree_index.tpl';

include $GLOBALS['xoops']->path('/header.php');

//load javascript
$xoTheme->addScript(XOOPS_URL . '/browse.php?Frameworks/jquery/jquery.js');
//$xoTheme->addScript(PEDIGREE_URL . '/assets/js/jquery.ThickBox/thickbox-compressed.js');

$xoTheme->addScript(PEDIGREE_URL . '/assets/js/jquery.magnific-popup.min.js');
$xoTheme->addScript(PEDIGREE_URL . '/assets/js/colpick.js');

//load CSS style sheets
$xoTheme->addStylesheet(PEDIGREE_URL . '/assets/css/colpick.css');
$xoTheme->addStylesheet(PEDIGREE_URL . '/assets/css/magnific-popup.css');
$xoTheme->addStylesheet(PEDIGREE_URL . '/assets/css/style.css');

//$xoTheme->addStylesheet(PEDIGREE_URL . '/assets/css/jquery.ThickBox/thickbox.css');
//$xoTheme->addStylesheet(PEDIGREE_URL . '/module.css');

$GLOBALS['xoopsTpl']->assign('pedigree_url', PEDIGREE_URL . '/');

// Breadcrumb
$breadcrumb = new PedigreeBreadcrumb();
$breadcrumb->addLink($pedigree->getModule()->getVar('name'), PEDIGREE_URL);

$GLOBALS['xoopsTpl']->assign('module_home', PedigreeUtilities::getModuleName(false)); // this definition is not removed for backward compatibility issues
$GLOBALS['xoopsTpl']->assign('pedigree_breadcrumb', $breadcrumb->render());

//get module configuration
$moduleHandler = xoops_getHandler('module');
$module        = $moduleHandler->getByDirname($xoopsModule->dirname());
$configHandler = xoops_getHandler('config');
$moduleConfig  = $configHandler->getConfigsByCat(0, $module->getVar('mid'));

//create animal object
require_once $GLOBALS['xoops']->path('modules/' . $xoopsModule->dirname() . '/class/animal.php');
$animal = new PedigreeAnimal();

//test to find out how many user fields there are..
$fields = $animal->getNumOfFields();

for ($i = 0, $iMax = count($fields); $i < $iMax; ++$i) {
    $userField = new Field($fields[$i], $animal->getConfig());
    if ($userField->isActive() && $userField->hasSearch()) {
        $fieldType   = $userField->getSetting('FieldType');
        $fieldObject = new $fieldType($userField, $animal);
        $function    = 'user' . $fields[$i] . $fieldObject->getSearchString();
        //echo $function."<br />";
        $usersearch[] = array(
            'title'       => $userField->getSetting('SearchName'),
            'searchid'    => 'user' . $fields[$i],
            'function'    => $function,
            'explanation' => $userField->getSetting('SearchExplanation'),
            'searchfield' => $fieldObject->searchfield()
        );
    }
}

//$catarray['letters']          = PedigreeUtilities::lettersChoice();
$letter              = '';
$myObject            = PedigreePedigree::getInstance();
$criteria            = $myObject->getHandler('tree')->getActiveCriteria();
$activeObject        = 'tree';
$name                = 'NAAM';
$file                = 'result.php';
$file2               = "result.php?f={$name}&amp;l=1&amp;w={$letter}%25&amp;o={$name}";
$catarray['letters'] = PedigreeUtilities::lettersChoice($myObject, $activeObject, $criteria, $name, $file, $file2);
//$catarray['toolbar']          = pedigree_toolbar();
$xoopsTpl->assign('catarray', $catarray);

//add data to smarty template
$GLOBALS['xoopsTpl']->assign(array(
                                 'sselect'     => strtr(_MA_PEDIGREE_SELECT, array('[animalType]' => $moduleConfig['animalType'])),
                                 'explain'     => _MA_PEDIGREE_EXPLAIN,
                                 'sname'       => _MA_PEDIGREE_SEARCHNAME,
                                 'snameex'     => strtr(_MA_PEDIGREE_SEARCHNAME_EX, array('[animalTypes]' => $moduleConfig['animalTypes'])),
                                 'usersearch'  => isset($usersearch) ? $usersearch : '',
                                 'showwelcome' => $moduleConfig['showwelcome'],
                                 'welcome'     => $GLOBALS['myts']->displayTarea($moduleConfig['welcome'])
                             ));

include $GLOBALS['xoops']->path('footer.php');
