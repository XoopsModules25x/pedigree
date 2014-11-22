<?php
// -------------------------------------------------------------------------
//	pedigree
//		Copyright 2004, James Cotton
// 		http://www.dobermannvereniging.nl
//	Template
//		Copyright 2004 Thomas Hill
//		<a href="http://www.worldware.com">worldware.com</a>
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

//xoops_cp_header();
//echo "<h4>Pedigree Administration</h4><table width='100%' border='0' cellspacing='1' class='outer'>";
//echo "<tr><td class='odd'> - <b><a href='database_table.php?op=sql'>SQL actions</a></b>";
//echo "<br /><br />";
//echo " - <b><a href='database_table.php?op=main'>Edit entry</a></b>";
//echo "<br /><br />";
//echo " - <b><a href='database_table.php?op=add'>Add entry</a></b>";
//echo "<br /><br />";
//echo "- <b><a href='".XOOPS_URL."/modules/system/admin.php?fct=preferences&amp;op=showmod&amp;mod=".$xoopsModule->getVar('mid') ."'>Preferences</a></b></td></tr></table>";

// $adminmenu[0]['link'] = "admin/database_table.php?op=sql";
// $adminmenu[0]['title'] = "SQL Actions";
// $adminmenu[1]['link'] = "admin/colors.php";
// $adminmenu[1]['title'] = "Create colours";

$dirname                = basename(dirname(dirname(__FILE__)));
$module_handler         =& xoops_gethandler("module");
$xoopsModule            =& XoopsModule::getByDirname($dirname);
$moduleInfo             =& $module_handler->get($xoopsModule->getVar("mid"));
$pathIcon32             = $moduleInfo->getInfo("icons32");
$adminmenu              = array();
$i                      = 1;
$adminmenu[$i]["title"] = _MI_PEDIGREE_ADMENU1;
$adminmenu[$i]["link"]  = "admin/index.php";
$adminmenu[$i]["icon"]  = $pathIcon32 . "/home.png";
++$i;
$adminmenu[$i]["title"] = _MI_PEDIGREE_ADMENU2;
$adminmenu[$i]["link"]  = "admin/pedigree_trash.php";
$adminmenu[$i]["icon"]  = $pathIcon32 . "/alert.png";
++$i;
$adminmenu[$i]["title"] = _MI_PEDIGREE_ADMENU3;
$adminmenu[$i]["link"]  = "admin/owner.php";
$adminmenu[$i]["icon"]  = $pathIcon32 . "/user-icon.png";
++$i;
$adminmenu[$i]["title"] = _MI_PEDIGREE_ADMENU4;
$adminmenu[$i]["link"]  = "admin/pedigree_temp.php";
$adminmenu[$i]["icon"]  = $pathIcon32 . "/wizard.png";
++$i;
$adminmenu[$i]["title"] = _MI_PEDIGREE_ADMENU5;
$adminmenu[$i]["link"]  = "admin/pedigree.php";
$adminmenu[$i]["icon"]  = $pathIcon32 . "/groupmod.png";
++$i;
$adminmenu[$i]["title"] = _MI_PEDIGREE_ADMENU6;
$adminmenu[$i]["link"]  = "admin/pedigree_config.php";
$adminmenu[$i]["icon"]  = $pathIcon32 . "/administration.png";
//++$i;
//$adminmenu[$i]["title"] = _MI_PEDIGREE_ADMENU7;
//$adminmenu[$i]["link"] = "admin/permissions.php";
//$adminmenu[$i]["icon"] = $pathIcon32."/permissions.png";
++$i;
$adminmenu[$i]["title"] = _MI_PEDIGREE_ADMENU8;
$adminmenu[$i]["link"]  = "admin/about.php";
$adminmenu[$i]["icon"]  = $pathIcon32 . "/about.png";
unset($i);
