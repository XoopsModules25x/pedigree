<?php
// -------------------------------------------------------------------------

use XoopsModules\Pedigree;

require_once __DIR__ . '/header.php';

$moduleDirName = basename(__DIR__);
xoops_loadLanguage('main', $moduleDirName);

// Include any common code for this module.
require_once XOOPS_ROOT_PATH . '/modules/' . $moduleDirName . '/include/common.php';
require_once __DIR__ . '/welcome.php';

$GLOBALS['xoopsOption']['template_main'] = 'pedigree_index.tpl';

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
$breadcrumb = new Pedigree\Breadcrumb();
$breadcrumb->addLink($helper->getModule()->getVar('name'), PEDIGREE_URL);

$GLOBALS['xoopsTpl']->assign('module_home', Pedigree\Utility::getModuleName(false)); // this definition is not removed for backward compatibility issues
$GLOBALS['xoopsTpl']->assign('pedigree_breadcrumb', $breadcrumb->render());

//get module configuration
/** @var XoopsModuleHandler $moduleHandler */
$moduleHandler = xoops_getHandler('module');
$module        = $moduleHandler->getByDirname($xoopsModule->dirname());
$configHandler = xoops_getHandler('config');
$moduleConfig  = $configHandler->getConfigsByCat(0, $module->getVar('mid'));

//create animal object
$animal = new Pedigree\Animal();

//test to find out how many user fields there are..
$fields = $animal->getNumOfFields();

for ($i = 0, $iMax = count($fields); $i < $iMax; ++$i) {
    $userField = new Pedigree\Field($fields[$i], $animal->getConfig());
    if ($userField->isActive() && $userField->hasSearch()) {
        $fieldType   = $userField->getSetting('FieldType');
        $fieldObject = new $fieldType($userField, $animal);
        $function    = 'user' . $fields[$i] . $fieldObject->getSearchString();
        //echo $function."<br>";
        $usersearch[] = [
            'title'       => $userField->getSetting('SearchName'),
            'searchid'    => 'user' . $fields[$i],
            'function'    => $function,
            'explanation' => $userField->getSetting('SearchExplanation'),
            'searchfield' => $fieldObject->searchfield()
        ];
    }
}

//$catarray['letters']          = Pedigree\Utility::lettersChoice();
$letter       = '';
$myObject     = Pedigree\Helper::getInstance();
$activeObject = 'Tree';
$name         = 'naam';
$link         = "result.php?f={$name}&amp;l=1&amp;o={$name}&amp;w=";
$link2        = '%25';

$criteria = $myObject->getHandler('Tree')->getActiveCriteria();
$criteria->setGroupby('UPPER(LEFT(' . $name . ',1))');
$catarray['letters'] = Pedigree\Utility::lettersChoice($myObject, $activeObject, $criteria, $name, $link, $link2);
//$catarray['toolbar']          = pedigree_toolbar();
$xoopsTpl->assign('catarray', $catarray);
$xoopsTpl->assign('pageTitle', _MA_PEDIGREE_BROWSETOTOPIC);

//add data to smarty template
$GLOBALS['xoopsTpl']->assign([
                                 'sselect'    => strtr(_MA_PEDIGREE_SELECT, ['[animalType]' => $moduleConfig['animalType']]),
                                 'explain'    => _MA_PEDIGREE_EXPLAIN,
                                 'sname'      => _MA_PEDIGREE_SEARCHNAME,
                                 'snameex'    => strtr(_MA_PEDIGREE_SEARCHNAME_EX, ['[animalTypes]' => $moduleConfig['animalTypes']]),
                                 'usersearch' => isset($usersearch) ? $usersearch : ''
                             ]);
$GLOBALS['xoopsTpl']->assign('showwelcome', $moduleConfig['showwelcome']);
//$GLOBALS['xoopsTpl']->assign('welcome', $GLOBALS['myts']->displayTarea($moduleConfig['welcome']));
//$word = $myts->displayTarea(strtr($helper->getConfig('welcome'), array('[numanimals]' => $numdogs, '[animalType]' => $helper->getConfig('animalType'), '[animalTypes]' => $helper->getConfig('animalTypes'))));
$GLOBALS['xoopsTpl']->assign('word', $word);
include $GLOBALS['xoops']->path('footer.php');
