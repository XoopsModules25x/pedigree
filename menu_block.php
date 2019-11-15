<?php

/**
 * Include any constants used for internationalizing templates.
 *
 * @package      XoopsModules\Pedigree
 * @copyright    2004 James Cotton
 * @copyright    {@link https://xoops.org/ XOOPS Project}
 * @license      {@link http://www.gnu.org/licenses/gpl-2.0.html GNU GPL 2 or later}
 * @author       James Cotton {@link http://www.dobermannvereniging.nl James Cotton}
 * @author       XOOPS Module Dev Team
 */
use XoopsModules\Pedigree;

$moduleDirName = basename(__DIR__);
$helper = Pedigree\Helper::getInstance();
$helper->loadLanguage('main');

// Include any common code for this module.
require_once $helper->path('include/common.php');

/**
 * @return XoopsTpl
 */
function menu_block()
{
    /** @var \XoopsModules\Pedigree\Helper $helper */
    $helper = Pedigree\Helper::getInstance();

    list($actlink, $even, $odd, $text, $hovlink, $head, $body, $title) = Pedigree\Utility::getColourScheme();
    /*
    $actlink = $colors[0];
    $even    = $colors[1];
    $odd     = $colors[2];
    $text    = $colors[3];
    $hovlink = $colors[4];
    $head    = $colors[5];
    $body    = $colors[6];
    $title   = $colors[7];
    */
    //inline-css
    echo '<style>';
    //text-colour
    echo 'body {margin: 0;padding: 0;background: ' . $body . ';color: ' . $text . ";font-size: 100%; /* <-- Resets 1em to 10px */font-family: 'Lucida Grande', Verdana, Arial, Sans-Serif; text-align: left;}";
    //link-colour
    echo 'a, h2 a:hover, h3 a:hover { color: ' . $actlink . '; text-decoration: none; }';
    //link hover colour
    echo 'a:hover { color: ' . $hovlink . '; text-decoration: underline; }';
    //th
    echo 'th {padding: 2px;color: #ffffff;background: ' . $title . ';font-family: Verdana, Arial, Helvetica, sans-serif;vertical-align: middle;}';
    echo 'td#centercolumn th { color: #fff; background: ' . $title . '; vertical-align: middle; }';
    //head
    echo '.head {background-color: ' . $head . '; padding: 3px; font-weight: normal;}';
    //even
    echo '.even {background-color: ' . $even . '; padding: 3px;}';
    echo 'tr.even td {background-color: ' . $even . '; padding: 3px;}';
    //odd
    echo '.odd {background-color: ' . $odd . '; padding: 3px;}';
    echo 'tr.odd td {background-color: ' . $odd . '; padding: 3px;}';
    echo '</style>';

    //is current user a module admin ?
    if (!empty($GLOBALS['xoopsUser']) && ($GLOBALS['xoopsUser'] instanceof \XoopsUser)
        && $GLOBALS['xoopsUser']->isAdmin($GLOBALS['xoopsModule']->mid())) {
        $isAdmin = true;
    } else {
        $isAdmin = false;
    }

    $counter = 1;
    $menuwidth = 4;

    $x = $_SERVER['SCRIPT_NAME'];
    $lastpos = Pedigree\Utility::myStrRpos($x, '/');
    $len = mb_strlen($x);
    $curpage = mb_substr($x, $lastpos, $len);
    if ('1' == $helper->getConfig('showwelcome')) {
        if ('/welcome.php' === $curpage) {
            $title = '<b>' . _MA_PEDIGREE_WELCOME . '</b>';
        } else {
            $title = _MA_PEDIGREE_WELCOME;
        }
        $menuarray[] = ['title' => $title, 'link' => 'welcome.php', 'counter' => $counter];
        ++$counter;
        if ($counter == $menuwidth) {
            $counter = 1;
        }
    }
    if ('/index.php' === $curpage || '/result.php' == $curpage) {
        $title = '<b>' . _MA_PEDIGREE_VIEWSEARCH . $helper->getConfig('animalTypes') . '</b>';
    } else {
        $title = '_MA_PEDIGREE_VIEWSEARCH ' . $helper->getConfig('animalTypes');
    }
    $menuarray[] = ['title' => $title, 'link' => 'result.php', 'counter' => $counter];
    ++$counter;
    if ($counter == $menuwidth) {
        $counter = 1;
    }
    if ('/index.php' === $curpage) {
        $title = '<b>' . _MA_PEDIGREE_ADD_A . $helper->getConfig('animalType') . '</b>';
    } else {
        $title = 'PED_ADD_A ' . $helper->getConfig('animalType');
    }
    $menuarray[] = ['title' => $title, 'link' => 'add_dog.php', 'counter' => $counter];
    ++$counter;
    if ($counter == $menuwidth) {
        $counter = 1;
    }
    if ('1' == $helper->getConfig('uselitter') {
        if ('/index.php' === $curpage) {
            $title = '<b>' . _MA_PEDIGREE_ADD_LITTER . $helper->getConfig('litter') . '</b>';
        } else {
            $title = '_MA_PEDIGREE_ADD_LITTER ' . $helper->getConfig('litter');
        }
        $menuarray[] = ['title' => $title, 'link' => 'add_litter.php', 'counter' => $counter];
        ++$counter;
        if ($counter == $menuwidth) {
            $counter = 1;
        }
    }
    if ('1' == $helper->getConfig('ownerbreeder')) {
        if ('/index.php' === $curpage || '/owner.php' === $curpage) {
            $title = '<b>' . _MA_PEDIGREE_VIEW_OWNBREED . '</b>';
        } else {
            $title = '_MA_PEDIGREE_VIEW_OWNBREED';
        }
        $menuarray[] = ['title' => $title, 'link' => 'breeder.php', 'counter' => $counter];
        ++$counter;
        if ($counter == $menuwidth) {
            $counter = 1;
        }
        if ('/index.php' === $curpage || '/add_breeder.php' === $curpage) {
            $title = '<b>' . _MA_PEDIGREE_ADD_OWNBREED . '</b>';
        } else {
            $title = '_MA_PEDIGREE_ADD_OWNBREED';
        }
        $menuarray[] = ['title' => $title, 'link' => 'add_breeder.php', 'counter' => $counter];
        ++$counter;
        if ($counter == $menuwidth) {
            $counter = 1;
        }
    }
    if ('/index.php' === $curpage || '/advanced.php' === $curpage) {
        $title = '<b>' . _MA_PEDIGREE_ADVANCE_INFO . '</b>';
    } else {
        $title = '_MA_PEDIGREE_ADVANCE_INFO';
    }
    $menuarray[] = ['title' => $title, 'link' => 'advanced.php', 'counter' => $counter];
    ++$counter;
    if ($counter == $menuwidth) {
        $counter = 1;
    }
    if ('1' == $helper->getConfig('proversion')) {
        if ('/index.php' === $curpage || '/virtual.php' === $curpage) {
            $title = '<b>' . _MA_PEDIGREE_VIRUTALTIT . '</b>';
        } else {
            $title = '_MA_PEDIGREE_VIRUTALTIT';
        }
        $menuarray[] = ['title' => $title, 'link' => 'virtual.php', 'counter' => $counter];
        ++$counter;
        if ($counter == $menuwidth) {
            $counter = 1;
        }
    }
    if ('/index.php' === $curpage || '/latest.php' === $curpage) {
        $title = '<b>' . _MA_PEDIGREE_LATEST_ADD . '</b>';
    } else {
        $title = '_MA_PEDIGREE_LATEST_ADD';
    }
    $menuarray[] = ['title' => $title, 'link' => 'latest.php', 'counter' => $counter];
    ++$counter;
    if ($counter == $menuwidth) {
        $counter = 1;
    }
    if ($isAdmin) {
        if ('/index.php' === $curpage || '/tools.php' === $curpage) {
            $title = '<b>' . _MA_PEDIGREE_WEB_TOOLS . '</b>';
        } else {
            $title = '_MA_PEDIGREE_WEB_TOOLS';
        }
        $menuarray[] = ['title' => $title, 'link' => 'tools.php?op=index', 'counter' => $counter];
        ++$counter;
        if ($counter == $menuwidth) {
            $counter = 1;
        }

        $title = _MA_PEDIGREE_USER_LOGOUT;
        $menuarray[] = ['title' => $title, 'link' => '../../user.php?op=logout', 'counter' => $counter];
        ++$counter;
        if ($counter == $menuwidth) {
            $counter = 1;
        }
    } else {
        if ('/user.php' === $curpage) {
            $title = '._MA_PEDIGREE_USER_LOGIN.';
        } else {
            $title = _MA_PEDIGREE_USER_LOGIN;
        }
        $menuarray[] = ['title' => $title, 'link' => '../../user.php', 'counter' => $counter];
        ++$counter;
        if ($counter == $menuwidth) {
            $counter = 1;
        }
    }

    //create path taken
    //showpath();
    $GLOBALS['xoopsTpl']->assign('menuarray', $menuarray);

    //return the template contents
    return $GLOBALS['xoopsTpl'];
}
