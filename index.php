<?php
// -------------------------------------------------------------------------

require __DIR__ . '/header.php';

// Include any common code for this module.
//require_once XOOPS_ROOT_PATH . "/modules/{$moduleDirName}/include/common.php";
//require_once $GLOBALS['xoops']->path('modules/' . $xoopsModule->dirname() . '/include/class_field.php');
require_once $GLOBALS['xoops']->path("modules/{$moduleDirName}/class/field.php");

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

// Breadcrumb
$breadcrumb = new PedigreeBreadcrumb();
$breadcrumb->addLink($pedigree->getModule()->getVar('name'), PEDIGREE_URL);

$GLOBALS['xoopsTpl']->assign('module_home', $pedigree->getModule()->getVar('name')); // this definition is not removed for backward compatibility issues
$GLOBALS['xoopsTpl']->assign('pedigree_breadcrumb', $breadcrumb->render());

//get module configuration
/*
$moduleHandler = xoops_getHandler('module');
$module        = $moduleHandler->getByDirname($xoopsModule->dirname());
$configHandler = xoops_getHandler('config');
$moduleConfig  = $configHandler->getConfigsByCat(0, $module->getVar('mid'));
*/


//create animal object
$animal = new PedigreeAnimal();

if (!class_exists('PedigreeField')) {
    $GLOBALS['xoops']->path("modules/{$moduleDirName}/class/field.php");
}

//test to find out how many user fields there are
$fields = $animal->getNumOfFields();
$fieldCount = (is_array($fields) && (!empty($fields))) ? count($fields) : 0;

for ($i = 0; $i < $fieldCount; ++$i) {
    $userField = new PedigreeField($fields[$i], $animal->getConfig());
    if ($userField->isActive() && $userField->hasSearch()) {
        $fieldType   = $userField->getSetting('fieldtype');
        $fieldObject = new $fieldType($userField, $animal);
        $function    = 'user' . $fields[$i] . $fieldObject->getSearchString();
        //echo $function."<br />";
        $usersearch[] = array('title' => $userField->getSetting('searchname'),
                           'searchid' => 'user' . $fields[$i],
                           'function' => $function,
                        'explanation' => $userField->getSetting('searchexplanation'),
                        'searchfield' => $fieldObject->searchfield()
        );
    }
}

//$catarray['letters'] = PedigreeUtilities::lettersChoice();
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
$GLOBALS['xoopsTpl']->assign(array('sselect' => strtr(_MA_PEDIGREE_SELECT, array('[animalType]' => $pedigree->getConfig('animalType'))),
                                   'explain' => _MA_PEDIGREE_EXPLAIN,
                                     'sname' => _MA_PEDIGREE_SEARCHNAME,
                                   'snameex' => strtr(_MA_PEDIGREE_SEARCHNAME_EX, array('[animalTypes]' => $pedigree->getConfig('animalTypes'))),
                                'usersearch' => isset($usersearch) ? $usersearch : '',
                               'showwelcome' => $pedigree->getConfig('showwelcome'),
                                   'welcome' => $GLOBALS['myts']->displayTarea($pedigree->getConfig('welcome'))
                             ));

include $GLOBALS['xoops']->path('footer.php');
