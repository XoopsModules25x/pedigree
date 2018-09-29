<?php
// -------------------------------------------------------------------------
//    pedigree
//        Copyright 2004, James Cotton
//         http://www.dobermannvereniging.nl

use XoopsModules\Pedigree;

// Include any constants used for internationalizing templates.
$moduleDirName = basename(dirname(__DIR__));
xoops_loadLanguage('main', $moduleDirName);
//xoops_loadLanguage('main', $moduleDirName);
// Include any common code for this module.

require_once XOOPS_ROOT_PATH . '/modules/' . $moduleDirName . '/include/common.php';

/**
 * @todo: move hard coded language strings to language file
 *
 * @return XoopsTpl
 */
function menu_block()
{
    global $xoopsTpl, $xoopsUser, $pedigree;
    $moduleDirName = basename(dirname(__DIR__));
    /*
    //get module configuration
    $moduleHandler = xoops_getHandler('module');
    $module        = $moduleHandler->getByDirname($moduleDirName);
    $configHandler = xoops_getHandler('config');
    $moduleConfig  = $configHandler->getConfigsByCat(0, $module->getVar('mid'));
*/
    //colour variables
    $colors  = explode(',', $helper->getConfig('colourscheme'));
    $actlink = $colors[0];
    $even    = $colors[1];
    $odd     = $colors[2];
    $text    = $colors[3];
    $hovlink = $colors[4];
    $head    = $colors[5];
    $body    = $colors[6];
    $title   = $colors[7];

    /*
        //inline-css
        echo '<style>';
        //text-colour
        echo 'body {margin: 0;padding: 0;background: ' . $body . ';color: ' . $text . ";font-size: 62.5%; font-family: 'Lucida Grande', Verdana, Arial, sans-serif; text-align: left;}";
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
    */

    //iscurrent user a module admin ?
    $xoopsModule = XoopsModule::getByDirname($moduleDirName);
    if (!empty($xoopsUser) && ($GLOBALS['xoopsUser'] instanceof \XoopsUser)
        && $xoopsUser->isAdmin($xoopsModule->mid())) {
        $modadmin = true;
    } else {
        $modadmin = false;
    }
    $counter   = 1;
    $menuwidth = 4;

    $x       = $_SERVER['PHP_SELF'];
    $lastpos = my_strrpos($x, '/');
    $len     = strlen($x);
    $curpage = substr($x, $lastpos, $len);
    if (1 == $helper->getConfig('showwelcome')) {
        if ('/welcome.php' === $curpage) {
            $title = '<b>Welcome</b>';
        } else {
            $title = 'Welcome';
        }
        $menuarray[] = ['title' => $title, 'link' => 'welcome.php', 'counter' => $counter];
        ++$counter;
        if ($counter == $menuwidth) {
            $counter = 1;
        }
    }
    if ('/index.php' === $curpage || '/result.php' === $curpage) {
        $title = '<b>View/Search ' . $helper->getConfig('animalTypes') . '</b>';
    } else {
        $title = 'View/Search ' . $helper->getConfig('animalTypes');
    }
    $menuarray[] = ['title' => $title, 'link' => 'index.php', 'counter' => $counter];
    ++$counter;
    if ($counter == $menuwidth) {
        $counter = 1;
    }
    if ('/add_dog.php' === $curpage) {
        $title = '<b>Add a ' . $helper->getConfig('animalType') . '</b>';
    } else {
        $title = 'Add a ' . $helper->getConfig('animalType');
    }
    $menuarray[] = ['title' => $title, 'link' => 'add_dog.php', 'counter' => $counter];
    ++$counter;
    if ($counter == $menuwidth) {
        $counter = 1;
    }
    if ('1' == $helper->getConfig('uselitter')) {
        if ('/add_litter.php' === $curpage) {
            $title = '<b>Add a ' . $helper->getConfig('litter') . '</b>';
        } else {
            $title = 'Add a ' . $helper->getConfig('litter');
        }
        $menuarray[] = ['title' => $title, 'link' => 'add_litter.php', 'counter' => $counter];
        ++$counter;
        if ($counter == $menuwidth) {
            $counter = 1;
        }
    }
    if ('1' == $helper->getConfig('ownerbreeder')) {
        if ('/breeder.php' === $curpage || '/owner.php' === $curpage) {
            $title = '<b>View owners/breeders</b>';
        } else {
            $title = 'View owners/breeders';
        }
        $menuarray[] = ['title' => $title, 'link' => 'breeder.php', 'counter' => $counter];
        ++$counter;
        if ($counter == $menuwidth) {
            $counter = 1;
        }
        if ('/add_breeder.php' === $curpage) {
            $title = '<b>Add an owner/breeder</b>';
        } else {
            $title = 'Add an owner/breeder';
        }
        $menuarray[] = ['title' => $title, 'link' => 'add_breeder.php', 'counter' => $counter];
        ++$counter;
        if ($counter == $menuwidth) {
            $counter = 1;
        }
    }
    if ('/advanced.php' === $curpage) {
        $title = '<b>Advanced info</b>';
    } else {
        $title = 'Advanced info';
    }
    $menuarray[] = ['title' => $title, 'link' => 'advanced.php', 'counter' => $counter];
    ++$counter;
    if ($counter == $menuwidth) {
        $counter = 1;
    }
    if ('1' == $helper->getConfig('proversion')) {
        if ('/virtual.php' === $curpage) {
            $title = '<b>Virtual mating</b>';
        } else {
            $title = 'Virtual Mating';
        }
        $menuarray[] = ['title' => $title, 'link' => 'virtual.php', 'counter' => $counter];
        ++$counter;
        if ($counter == $menuwidth) {
            $counter = 1;
        }
    }
    if ('/latest.php' === $curpage) {
        $title = '<b>latest additions</b>';
    } else {
        $title = 'latest additions';
    }
    $menuarray[] = ['title' => $title, 'link' => 'latest.php', 'counter' => $counter];
    ++$counter;
    if ($counter == $menuwidth) {
        $counter = 1;
    }
    if (true === $modadmin) {
        if ('/tools.php' === $curpage) {
            $title = '<b>Webmaster tools</b>';
        } else {
            $title = 'Webmaster tools';
        }
        $menuarray[] = ['title' => $title, 'link' => 'tools.php?op=index', 'counter' => $counter];
        ++$counter;
        if ($counter == $menuwidth) {
            $counter = 1;
        }
        $title       = 'Logout';
        $menuarray[] = ['title' => $title, 'link' => '../../user.php?op=logout', 'counter' => $counter];
        ++$counter;
        if ($counter == $menuwidth) {
            $counter = 1;
        }
    } else {
        if ('/user.php' === $curpage) {
            $title = '<b>User login</b>';
        } else {
            $title = 'User login';
        }
        $menuarray[] = ['title' => $title, 'link' => '../../user.php', 'counter' => $counter];
        ++$counter;
        if ($counter == $menuwidth) {
            $counter = 1;
        }
    }

    //create path taken
    //showpath();
    $xoopsTpl->assign('menuarray', $menuarray);

    //return the template contents
    return $xoopsTpl;
}

/**
 * @param     $haystack
 * @param     $needle
 * @param int $offset
 *
 * @return bool|int
 */
function my_strrpos($haystack, $needle, $offset = 0)
{
    // same as strrpos, except $needle can be a string
    $strrpos = false;
    if (is_string($haystack) && is_string($needle) && is_numeric($offset)) {
        $strlen = strlen($haystack);
        $strpos = strpos(strrev(substr($haystack, $offset)), strrev($needle));
        if (is_numeric($strpos)) {
            $strrpos = $strlen - $strpos - strlen($needle);
        }
    }

    return $strrpos;
}
