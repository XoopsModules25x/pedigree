<?php
// -------------------------------------------------------------------------
//  pedigree
//      Copyright 2004, James Cotton
//      http://www.dobermannvereniging.nl
//  Template
//      Copyright 2004 Thomas Hill
//      <a href="http://www.worldware.com">worldware.com</a>
// -------------------------------------------------------------------------
// ------------------------------------------------------------------------- //
//  This program is free software; you can redistribute it and/or modify     //
//  it under the terms of the GNU General Public License as published by     //
//  the Free Software Foundation; either version 2 of the License, or        //
//  (at your option) any later version.                                      //
//                                                                           //
//  You may not change or alter any portion of this comment or credits       //
//  of supporting developers from this source code or any supporting         //
//  source code which is considered copyrighted (c) material of the          //
//  original comment or credit authors.                                      //
//                                                                           //
//  This program is distributed in the hope that it will be useful,          //
//  but WITHOUT ANY WARRANTY; without even the implied warranty of           //
//  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the            //
//  GNU General Public License for more details.                             //
//                                                                           //
//  You should have received a copy of the GNU General Public License        //
//  along with this program; if not, write to the Free Software              //
//  Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307 USA //
// ------------------------------------------------------------------------- //

use XoopsModules\Pedigree;

//require_once  dirname(__DIR__) . '/include/common.php';
$helper = Pedigree\Helper::getInstance();

$pathIcon32 = \Xmf\Module\Admin::menuIconPath('');
$pathModIcon32 = $helper->getModule()->getInfo('modicons32');

//xoops_cp_header();
//echo "<h4>Pedigree Administration</h4><table width='100%' border='0' cellspacing='1' class='outer'>";
//echo "<tr><td class='odd'> - <b><a href='database_table.php?op=sql'>SQL actions</a></b>";
//echo "<br><br>";
//echo " - <b><a href='database_table.php?op=main'>Edit entry</a></b>";
//echo "<br><br>";
//echo " - <b><a href='database_table.php?op=add'>Add entry</a></b>";
//echo "<br><br>";
//echo "- <b><a href='".XOOPS_URL."/modules/system/admin.php?fct=preferences&amp;op=showmod&amp;mod=".$xoopsModule->getVar('mid') ."'>Preferences</a></b></td></tr></table>";

// $adminmenu[0]['link'] = "admin/database_table.php?op=sql";
// $adminmenu[0]['title'] = "SQL Actions";
// $adminmenu[1]['link'] = "admin/colors.php";
// $adminmenu[1]['title'] = "Create colours";

$adminmenu = [];

$adminmenu[] = [
    'title' => _MI_PEDIGREE_ADMENU1,
    'link'  => 'admin/index.php',
    'icon'  => $pathIcon32 . '/home.png'
];

$adminmenu[] = [
    'title' => _MI_PEDIGREE_ADMENU2,
    'link'  => 'admin/pedigree_trash.php',
    'icon'  => $pathIcon32 . '/alert.png'
];

$adminmenu[] = [
    'title' => _MI_PEDIGREE_ADMENU3,
    'link'  => 'admin/owner.php',
    'icon'  => $pathIcon32 . '/user-icon.png'
];

$adminmenu[] = [
    'title' => _MI_PEDIGREE_ADMENU4,
    'link'  => 'admin/pedigree_temp.php',
    'icon'  => $pathIcon32 . '/wizard.png'
];

$adminmenu[] = [
    'title' => _MI_PEDIGREE_ADMENU5,
    'link'  => 'admin/pedigree.php',
    'icon'  => $pathIcon32 . '/groupmod.png'
];

$adminmenu[] = [
    'title' => _MI_PEDIGREE_ADMENU6,
    'link'  => 'admin/pedigree_config.php',
    'icon'  => $pathIcon32 . '/administration.png'
];

/*
$adminmenu[] = array(
    'title' => _MI_PEDIGREE_ADMENU7,
    'link'  => 'admin/permissions.php',
    'icon'  => $pathIcon32.'/permissions.png'
);
*/

$adminmenu[] = [
    'title' => _MI_PEDIGREE_ADMENU8,
    'link'  => 'admin/about.php',
    'icon'  => $pathIcon32 . '/about.png'
];
