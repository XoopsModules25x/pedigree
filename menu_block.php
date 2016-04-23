<?php
// -------------------------------------------------------------------------
//	pedigree
//		Copyright 2004, James Cotton
// 		http://www.dobermannvereniging.nl

// Include any constants used for internationalizing templates.

/*
if (file_exists(XOOPS_ROOT_PATH . "/modules/" . $xoopsModule->dirname() . "/language/" . $xoopsConfig['language'] . "/main.php")) {
    require_once XOOPS_ROOT_PATH . "/modules/" . $xoopsModule->dirname() . "/language/" . $xoopsConfig['language'] . "/main.php";
} else {
    include_once XOOPS_ROOT_PATH . "/modules/" . $xoopsModule->dirname() . "/language/english/main.php";
}
*/

xoops_loadLanguage('main', basename(dirname(__DIR__)));

// Include any common code for this module.
require_once(XOOPS_ROOT_PATH . "/modules/" . $xoopsModule->dirname() . "/include/class_field.php");
require_once(XOOPS_ROOT_PATH . "/modules/" . $xoopsModule->dirname() . "/include/functions.php");

/**
 * @return XoopsTpl
 */
function menu_block()
{
    global $xoopsTpl, $xoopsUser, $apppath;

    //get module configuration
    $module_handler = xoops_getHandler('module');
    $module         = $module_handler->getByDirname("pedigree");
    $config_handler = xoops_getHandler('config');
    $moduleConfig   = $config_handler->getConfigsByCat(0, $module->getVar('mid'));

    //colour variables
    $colors  = explode(";", $moduleConfig['colourscheme']);
    $actlink = $colors[0];
    $even    = $colors[1];
    $odd     = $colors[2];
    $text    = $colors[3];
    $hovlink = $colors[4];
    $head    = $colors[5];
    $body    = $colors[6];
    $title   = $colors[7];
//inline-css
    echo "<style>";
//text-colour
    echo "body {margin: 0;padding: 0;background: " . $body . ";color: " . $text
        . ";font-size: 100%; /* <-- Resets 1em to 10px */font-family: 'Lucida Grande', Verdana, Arial, Sans-Serif; text-align: left;}";
//link-colour
    echo "a, h2 a:hover, h3 a:hover { color: " . $actlink . "; text-decoration: none; }";
//link hover colour
    echo "a:hover { color: " . $hovlink . "; text-decoration: underline; }";
//th
    echo "th {padding: 2px;color: #ffffff;background: " . $title . ";font-family: Verdana, Arial, Helvetica, sans-serif;vertical-align: middle;}";
    echo "td#centercolumn th { color: #fff; background: " . $title . "; vertical-align: middle; }";
//head
    echo ".head {background-color: " . $head . "; padding: 3px; font-weight: normal;}";
//even
    echo ".even {background-color: " . $even . "; padding: 3px;}";
    echo "tr.even td {background-color: " . $even . "; padding: 3px;}";
//odd
    echo ".odd {background-color: " . $odd . "; padding: 3px;}";
    echo "tr.odd td {background-color: " . $odd . "; padding: 3px;}";
    echo "</style>";

    //iscurrent user a module admin ?
    $modadmin    = false;
    $xoopsModule = XoopsModule::getByDirname("pedigree");
    if (!empty($xoopsUser)) {
        if ($xoopsUser->isAdmin($xoopsModule->mid())) {
            $modadmin = true;
        }
    }
    $counter   = 1;
    $menuwidth = 4;

    $x       = $_SERVER['PHP_SELF'];
    $lastpos = my_strrpos($x, "/");
    $len     = strlen($x);
    $curpage = substr($x, $lastpos, $len);
    if ($moduleConfig['showwelcome'] == '1') {
        if ($curpage == "/welcome.php") {
            $title = '<b>' . _MA_PEDIGREE_WELCOME . '</b>';
        } else {
            $title = _MA_PEDIGREE_WELCOME;
        }
        $menuarray[] = array('title' => $title, 'link' => "welcome.php", 'counter' => $counter);
        ++$counter;
        if ($counter == $menuwidth) {
            $counter = 1;
        }
    }
    if ($curpage == "/index.php" || $curpage == "/result.php") {
        $title = "<b>" . _MA_PEDIGREE_VIEWSEARCH . $moduleConfig['animalTypes'] . "</b>";
    } else {
        $title = "_MA_PEDIGREE_VIEWSEARCH " . $moduleConfig['animalTypes'];
    }
    $menuarray[] = array('title' => $title, 'link' => "result.php", 'counter' => $counter);
    ++$counter;
    if ($counter == $menuwidth) {
        $counter = 1;
    }
    if ($curpage == "/index.php") {
        $title = "<b>" . _MA_PEDIGREE_ADD_A . $moduleConfig['animalType'] . "</b>";
    } else {
        $title = "PED_ADD_A " . $moduleConfig['animalType'];
    }
    $menuarray[] = array('title' => $title, 'link' => "add_dog.php", 'counter' => $counter);
    ++$counter;
    if ($counter == $menuwidth) {
        $counter = 1;
    }
    if ($moduleConfig['uselitter'] == '1') {
        if ($curpage == "/index.php") {
            $title = "<b>" . _MA_PEDIGREE_ADD_LITTER . $moduleConfig['litter'] . "</b>";
        } else {
            $title = "_MA_PEDIGREE_ADD_LITTER " . $moduleConfig['litter'];
        }
        $menuarray[] = array('title' => $title, 'link' => "add_litter.php", 'counter' => $counter);
        ++$counter;
        if ($counter == $menuwidth) {
            $counter = 1;
        }
    }
    if ($moduleConfig['ownerbreeder'] == '1') {
        if ($curpage == "/index.php" || $curpage == "/owner.php") {
            $title = "<b>" . _MA_PEDIGREE_VIEW_OWNBREED . "</b>";
        } else {
            $title = "_MA_PEDIGREE_VIEW_OWNBREED";
        }
        $menuarray[] = array('title' => $title, 'link' => "breeder.php", 'counter' => $counter);
        ++$counter;
        if ($counter == $menuwidth) {
            $counter = 1;
        }
        if ($curpage == "/index.php" || $curpage == "/add_breeder.php") {
            $title = "<b>" . _MA_PEDIGREE_ADD_OWNBREED . "</b>";
        } else {
            $title = "_MA_PEDIGREE_ADD_OWNBREED";
        }
        $menuarray[] = array('title' => $title, 'link' => "add_breeder.php", 'counter' => $counter);
        ++$counter;
        if ($counter == $menuwidth) {
            $counter = 1;
        }
    }
    if ($curpage == "/index.php" || $curpage == "/advanced.php") {
        $title = "<b>" . _MA_PEDIGREE_ADVANCE_INFO . "</b>";
    } else {
        $title = "_MA_PEDIGREE_ADVANCE_INFO";
    }
    $menuarray[] = array('title' => $title, 'link' => "advanced.php", 'counter' => $counter);
    ++$counter;
    if ($counter == $menuwidth) {
        $counter = 1;
    }
    if ($moduleConfig['proversion'] == '1') {
        if ($curpage == "/index.php" || $curpage == "/virtual.php") {
            $title = "<b>" . _MA_PEDIGREE_VIRUTALTIT . "</b>";
        } else {
            $title = "_MA_PEDIGREE_VIRUTALTIT";
        }
        $menuarray[] = array('title' => $title, 'link' => "virtual.php", 'counter' => $counter);
        ++$counter;
        if ($counter == $menuwidth) {
            $counter = 1;
        }
    }
    if ($curpage == "/index.php" || $curpage == "/latest.php") {
        $title = "<b>" . _MA_PEDIGREE_LATEST_ADD . "</b>";
    } else {
        $title = "_MA_PEDIGREE_LATEST_ADD";
    }
    $menuarray[] = array('title' => $title, 'link' => "latest.php", 'counter' => $counter);
    ++$counter;
    if ($counter == $menuwidth) {
        $counter = 1;
    }
    if ($modadmin == true) {
        if ($curpage == "/index.php" || $curpage == "/tools.php") {
            $title = "<b>" . _MA_PEDIGREE_WEB_TOOLS . "</b>";
        } else {
            $title = "_MA_PEDIGREE_WEB_TOOLS";
        }
        $menuarray[] = array('title' => $title, 'link' => "tools.php?op=index", 'counter' => $counter);
        ++$counter;
        if ($counter == $menuwidth) {
            $counter = 1;
        }

        $title       = _MA_PEDIGREE_USER_LOGOUT;
        $menuarray[] = array('title' => $title, 'link' => "../../user.php?op=logout", 'counter' => $counter);
        ++$counter;
        if ($counter == $menuwidth) {
            $counter = 1;
        }
    } else {
        if ($curpage == "/user.php") {
            $title = '._MA_PEDIGREE_USER_LOGIN.';
        } else {
            $title = _MA_PEDIGREE_USER_LOGIN;
        }
        $menuarray[] = array('title' => $title, 'link' => "../../user.php", 'counter' => $counter);
        ++$counter;
        if ($counter == $menuwidth) {
            $counter = 1;
        }
    }

    //create path taken
    //showpath();
    $xoopsTpl->assign("menuarray", $menuarray);
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
