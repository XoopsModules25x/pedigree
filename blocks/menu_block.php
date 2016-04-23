<?php
// -------------------------------------------------------------------------
//	pedigree
//		Copyright 2004, James Cotton
// 		http://www.dobermannvereniging.nl

$dirname = basename(dirname(__DIR__));
// Include any constants used for internationalizing templates.
if (file_exists(XOOPS_ROOT_PATH . "/modules/{$dirname}/language/{$xoopsConfig['language']}/main.php")) {
    require_once XOOPS_ROOT_PATH . "/modules/{$dirname}/language/{$xoopsConfig['language']}/main.php";
} else {
    include_once XOOPS_ROOT_PATH . "/modules/{$dirname}/language/english/main.php";
}
// Include any common code for this module.
require_once(XOOPS_ROOT_PATH . "/modules/{$dirname}/include/class_field.php");
require_once(XOOPS_ROOT_PATH . "/modules/{$dirname}/include/functions.php");

/**
 * @return XoopsTpl
 */
function menu_block()
{
    global $xoopsTpl, $xoopsUser, $apppath;

    $dirname = basename(dirname(__DIR__));

    //get module configuration
    $module_handler = xoops_getHandler('module');
    $module         = $module_handler->getByDirname($dirname);
    $config_handler = xoops_getHandler('config');
    $moduleConfig   = $config_handler->getConfigsByCat(0, $module->getVar('mid'));

    //colour variables
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
    $colors  = explode(";", $moduleConfig['colourscheme']);
/* WTF - WHY is this in a block???????
//inline-css
    echo "<style>";
//text-colour
    echo "body {margin: 0;padding: 0;background: " . $body . ";color: " . $text
        . ";font-size: 62.5%; font-family: 'Lucida Grande', Verdana, Arial, Sans-Serif; text-align: left;}";
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
*/
    //iscurrent user a module admin ?
    $modadmin    = false;
    if (!empty($xoopsUser)) {
        if ($xoopsUser->isAdmin($module->mid())) {
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
            $title = "<b>Welcome</b>";
        } else {
            $title = "Welcome";
        }
        $menuarray[] = array('title' => $title, 'link' => "welcome.php", 'counter' => $counter);
        ++$counter;
        if ($counter == $menuwidth) {
            $counter = 1;
        }
    }
    if ($curpage == "/index.php" || $curpage == "/result.php") {
        $title = "<b>View/Search " . $moduleConfig['animalTypes'] . "</b>";
    } else {
        $title = "View/Search " . $moduleConfig['animalTypes'];
    }
    $menuarray[] = array('title' => $title, 'link' => "index.php", 'counter' => $counter);
    ++$counter;
    if ($counter == $menuwidth) {
        $counter = 1;
    }
    if ($curpage == "/add_dog.php") {
        $title = "<b>Add a " . $moduleConfig['animalType'] . "</b>";
    } else {
        $title = "Add a " . $moduleConfig['animalType'];
    }
    $menuarray[] = array('title' => $title, 'link' => "add_dog.php", 'counter' => $counter);
    ++$counter;
    if ($counter == $menuwidth) {
        $counter = 1;
    }
    if ($moduleConfig['uselitter'] == '1') {
        if ($curpage == "/add_litter.php") {
            $title = "<b>Add a " . $moduleConfig['litter'] . "</b>";
        } else {
            $title = "Add a " . $moduleConfig['litter'];
        }
        $menuarray[] = array('title' => $title, 'link' => "add_litter.php", 'counter' => $counter);
        ++$counter;
        if ($counter == $menuwidth) {
            $counter = 1;
        }
    }
    if ($moduleConfig['ownerbreeder'] == '1') {
        if ($curpage == "/breeder.php" || $curpage == "/owner.php") {
            $title = "<b>View owners/breeders</b>";
        } else {
            $title = "View owners/breeders";
        }
        $menuarray[] = array('title' => $title, 'link' => "breeder.php", 'counter' => $counter);
        ++$counter;
        if ($counter == $menuwidth) {
            $counter = 1;
        }
        if ($curpage == "/add_breeder.php") {
            $title = "<b>Add an owner/breeder</b>";
        } else {
            $title = "Add an owner/breeder";
        }
        $menuarray[] = array('title' => $title, 'link' => "add_breeder.php", 'counter' => $counter);
        ++$counter;
        if ($counter == $menuwidth) {
            $counter = 1;
        }
    }
    if ($curpage == "/advanced.php") {
        $title = "<b>Advanced info</b>";
    } else {
        $title = "Advanced info";
    }
    $menuarray[] = array('title' => $title, 'link' => "advanced.php", 'counter' => $counter);
    ++$counter;
    if ($counter == $menuwidth) {
        $counter = 1;
    }
    if ($moduleConfig['proversion'] == '1') {
        if ($curpage == "/virtual.php") {
            $title = "<b>Virtual mating</b>";
        } else {
            $title = "Virtual Mating";
        }
        $menuarray[] = array('title' => $title, 'link' => "virtual.php", 'counter' => $counter);
        ++$counter;
        if ($counter == $menuwidth) {
            $counter = 1;
        }
    }
    if ($curpage == "/latest.php") {
        $title = "<b>latest additions</b>";
    } else {
        $title = "latest additions";
    }
    $menuarray[] = array('title' => $title, 'link' => "latest.php", 'counter' => $counter);
    ++$counter;
    if ($counter == $menuwidth) {
        $counter = 1;
    }
    if ($modadmin == true) {
        if ($curpage == "/tools.php") {
            $title = "<b>Webmaster tools</b>";
        } else {
            $title = "Webmaster tools";
        }
        $menuarray[] = array('title' => $title, 'link' => "tools.php?op=index", 'counter' => $counter);
        ++$counter;
        if ($counter == $menuwidth) {
            $counter = 1;
        }
        $title       = "Logout";
        $menuarray[] = array('title' => $title, 'link' => "../../user.php?op=logout", 'counter' => $counter);
        ++$counter;
        if ($counter == $menuwidth) {
            $counter = 1;
        }
    } else {
        if ($curpage == "/user.php") {
            $title = "<b>User login</b>";
        } else {
            $title = "User login";
        }
        $menuarray[] = array('title' => $title, 'link' => "../../user.php", 'counter' => $counter);
        ++$counter;
        if ($counter == $menuwidth) {
            $counter = 1;
        }
    }

    //create path taken
    //showpath();
    $xoopsTpl->assign("modulename", $dirname);
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
