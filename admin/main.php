<?php
// -------------------------------------------------------------------------
//    pedigree
//        Copyright 2004, James Cotton
//         http://www.dobermannvereniging.nl
//    Template
//        Copyright 2004 Thomas Hill
//        <a href="http://www.worldware.com">worldware.com</a>
// -------------------------------------------------------------------------
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
//  ------------------------------------------------------------------------ //

use XoopsModules\Pedigree;

require_once  dirname(dirname(dirname(__DIR__))) . '/include/cp_header.php';
$helper = XoopsModules\Pedigree\Helper::getInstance();
$helper->loadLanguage('modinfo');
require_once $helper->path('admin/menu.php');
