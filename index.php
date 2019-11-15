<?php
/*
 * You may not change or alter any portion of this comment or credits
 * of supporting developers from this source code or any supporting source code
 * which is considered copyrighted (c) material of the original comment or credit authors.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 */

/**
 * @package      XoopsModules\Pedigree
 * @copyright    {@link https://xoops.org/ XOOPS Project}
 * @license      {@link http://www.gnu.org/licenses/gpl-2.0.html GNU GPL 2 or later}
 * @author       XOOPS Module Dev Team
 */
use XoopsModules\Pedigree;

require_once __DIR__ . '/header.php';
/** @var \XoopsModules\Pedigree\Helper $helper */
$helper->loadLanguage('main');

// Include any common code for this module.
require_once $helper->path('include/common.php');
//require_once __DIR__ . '/welcome.php';

$GLOBALS['xoopsOption']['template_main'] = 'pedigree_index.tpl';

include $GLOBALS['xoops']->path('/header.php');

//load javascript
$xoTheme->addScript(XOOPS_URL . '/browse.php?Frameworks/jquery/jquery.js');
//$xoTheme->addScript($helper->url('assets/js/jquery.ThickBox/thickbox-compressed.js'));

$xoTheme->addScript($helper->url('assets/js/jquery.magnific-popup.min.js'));
$xoTheme->addScript($helper->url('assets/js/colpick.js'));

//load CSS style sheets
$xoTheme->addStylesheet($helper->url('assets/css/colpick.css'));
$xoTheme->addStylesheet($helper->url('assets/css/magnific-popup.css'));
$xoTheme->addStylesheet($helper->url('assets/css/style.css'));

//$xoTheme->addStylesheet($helper->url('assets/css/jquery.ThickBox/thickbox.css'));
//$xoTheme->addStylesheet($helper->url('assets/css/module.css'));

$GLOBALS['xoopsTpl']->assign('pedigree_url', $helper->url());

// Breadcrumb
$breadcrumb = new Pedigree\Breadcrumb();
$breadcrumb->addLink($helper->getModule()->getVar('name'), $helper->url());

$GLOBALS['xoopsTpl']->assign('module_home', $helper->getDirname()); // this definition is not removed for backward compatibility issues
$GLOBALS['xoopsTpl']->assign('pedigree_breadcrumb', $breadcrumb->render());

/**
 * Create an animal object
 * @var \XoopsModules\Pedigree\Animal $animal
 */
$animal = new Pedigree\Animal();
$fields = $animal->getFieldsIds(); //find out how many user fields there are

foreach ($fields as $i => $iValue) {
    /** @var \XoopsModules\Pedigree\Field $userField */
    $userField = new Pedigree\Field($fields[$i], $animal->getConfig());
    if ($userField->isActive() && $userField->hasSearch()) {
        $fieldType = $userField->getSetting('fieldtype');
        $fieldObject = new $fieldType($userField, $animal);
        $function = 'user' . $iValue . $fieldObject->getSearchString();
        //echo $function."<br>";
        $usersearch[] = [
            'title' => $userField->getSetting('SearchName'),
            'searchid' => 'user' . $iValue,
            'function' => $function,
            'explanation' => $userField->getSetting('SearchExplanation'),
            'searchfield' => $fieldObject->searchfield(),
        ];
    }
}

//$catarray['letters']          = Pedigree\Utility::lettersChoice();
//$letter       = '';
//$myObject     = Pedigree\Helper::getInstance();
//$activeObject = 'Tree';
$name = 'naam';
$link = "result.php?f={$name}&amp;l=1&amp;o={$name}&amp;w=";
$link2 = '%25';

$criteria = $helper->getHandler('Tree')->getActiveCriteria();
$criteria->setGroupby('UPPER(LEFT(' . $name . ', 1))');
$catarray['letters'] = Pedigree\Utility::lettersChoice($helper, 'Tree', $criteria, $name, $link, $link2);
//$catarray['toolbar'] = pedigree_toolbar();

$word = $myts->displayTarea(strtr($helper->getConfig('welcome'), ['[numanimals]' => $numdogs, '[animalType]' => $helper->getConfig('animalType'), '[animalTypes]' => $helper->getConfig('animalTypes')]));

//add data to smarty template
$GLOBALS['xoopsTpl']->assign([
    'catarray' => $catarray,
    'pageTitle' => _MA_PEDIGREE_BROWSETOTOPIC,
    'sselect' => strtr(_MA_PEDIGREE_SELECT, ['[animalType]' => $helper->getConfig('animalType')]),
    'explain' => _MA_PEDIGREE_EXPLAIN,
    'sname' => _MA_PEDIGREE_SEARCHNAME,
    'snameex' => strtr(_MA_PEDIGREE_SEARCHNAME_EX, ['[animalTypes]' => $helper->getConfig('animalTypes')]),
    'usersearch' => isset($usersearch) ? $usersearch : '',
    'showwelcome' => $helper->getConfig('showwelcome'),
    'word' => $word,
    //'welcome'     => $GLOBALS['myts']->displayTarea($helper->getConfig('welcome'))
]);

include $GLOBALS['xoops']->path('footer.php');
