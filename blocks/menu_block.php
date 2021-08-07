<?php
/* ------------------------------------------------------------------------
      pedigree
          Copyright 2004, James Cotton
           http://www.dobermannvereniging.nl
   ------------------------------------------------------------------------
   ------------------------------------------------------------------------
   This program is free software; you can redistribute it and/or modify
   it under the terms of the GNU General Public License as published by
   the Free Software Foundation; either version 2 of the License, or
   (at your option) any later version.

   You may not change or alter any portion of this comment or credits
   of supporting developers from this source code or any supporting
   source code which is considered copyrighted (c) material of the
   original comment or credit authors.

   This program is distributed in the hope that it will be useful,
   but WITHOUT ANY WARRANTY; without even the implied warranty of
   MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
   GNU General Public License for more details.

   You should have received a copy of the GNU General Public License
   along with this program; if not, write to the Free Software
   Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307 USA
   ------------------------------------------------------------------------
*/

/**
 * @package         XoopsModules\Pedigree
 * @copyright       Copyright 2004, James Cotton <https://www.dobermannvereniging.nl>
 * @copyright       {@link https://xoops.org/ XOOPS Project}
 * @license         {@link https://www.gnu.org/licenses/gpl-2.0.html GNU GPL 2 or later}
 * @author          XOOPS Module Dev Team
 */

use XoopsModules\Pedigree\{
    Helper,
    Utility
};

// Include any constants used for internationalizing templates.
$moduleDirName = \basename(\dirname(__DIR__));
$helper        = Helper::getInstance();
$helper->loadLanguage('main');

// Include any common code for this module.
require_once $helper->path('include/common.php');

/**
 * @return XoopsTpl
 * @todo: move hard coded language strings to language file
 *
 */
function menu_block()
{
    $moduleDirName = basename(\dirname(__DIR__));
    $helper        = Helper::getInstance();

    //colour variables
    [$actlink, $even, $odd, $text, $hovlink, $head, $body, $title] = Utility::getColourScheme();
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
    /*
        //inline-css
        echo '<style>';
        //text-colour
        echo 'body {margin: 0;padding: 0;background: ' . $body . ';color: ' . $text . ";font-size: 62.5%; font-family: 'Lucida Grande', Verdana, Arial, Sans-Serif; text-align: left;}";
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

    $counter   = 1;
    $menuwidth = 4;
    $x         = $_SERVER['SCRIPT_NAME'];
    $lastpos   = Utility::myStrRpos($x, '/');
    $len       = mb_strlen($x);
    $curpage   = mb_substr($x, $lastpos, $len);

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
    if (true === $helper->isUserAdmin()) {
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
    $GLOBALS['xoopsTpl']->assign('menuarray', $menuarray);

    //return the template contents
    return $GLOBALS['xoopsTpl'];
}
