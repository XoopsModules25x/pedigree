<?php
//  ------------------------------------------------------------------------ //
//                XOOPS - PHP Content Management System                      //
//                    Copyright (c) 2000 XOOPS.org                           //
//                       <http://www.xoops.org/>                             //
//  ------------------------------------------------------------------------ //
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
// Author: Tobias Liegl (AKA CHAPI)                                          //
// Site: http://www.chapi.de                                                 //
// Project: The XOOPS Project                                                //
// ------------------------------------------------------------------------- //

global $xoopsDB;

$SQL = "SELECT conf_value from ".$xoopsDB->prefix("config")." WHERE conf_name = 'pedigreeColours'";
$result = $xoopsDB->query($SQL);
while ($row = $xoopsDB->fetchArray($result)) {
    $c = $row['conf_value'];
}
$colors = explode(";", $c);

$mainBackColour 				= 	$colors[0];
$selectedBackColour 			= 	$colors[1];
$selectedTextColour				=	$colors[2];
$selectedTextFont				=	$colors[3];
$selectedTextSize				=	$colors[4];
$selectedTextStyle				=	$colors[5];
$maleBackColour					=	$colors[6];
$maleTextColour					=	$colors[7];
$maleTextFont					=	$colors[8];
$maleTextSize					=	$colors[9];
$maleTextStyle					=	$colors[10];
$femaleBackColour				=	$colors[11];
$femaleTextColour				=	$colors[12];
$femaleTextFont					=	$colors[13];
$femaleTextSize					=	$colors[14];
$femaleTextStyle				=	$colors[15];
$borderStyle					=	$colors[16];
$borderWidth					=	$colors[17];
$borderColour					=	$colors[18];

echo"<style type=\"text/css\">
<!--
td.even {
    background-color : #".$maleBackColour.";
    color : #".$maleTextColour.";
    font-family : ".$maleTextFont.";
    font-size : ".$maleTextSize.";
    font-style : ".$maleTextStyle.";
    border-style : ".$borderStyle.";
    border-width : ".$borderWidth."px;
    border-color : #".$borderColour.";
    }
tr.even {
    background-color : #".$maleBackColour.";
    color : #".$maleTextColour.";
    font-family : ".$maleTextFont.";
    font-size : ".$maleTextSize.";
    font-style : ".$maleTextStyle.";
    border-style : ".$borderStyle.";
    border-width : ".$borderWidth."px;
    border-color : #".$borderColour.";
    }
td.odd {
    background-color : #".$femaleBackColour.";
    color : #".$femaleTextColour.";
    font-family : ".$femaleTextFont.";
    font-size : ".$femaleTextSize.";
    font-style : ".$femaleTextStyle.";
    border-style : ".$borderStyle.";
    border-width : ".$borderWidth."px;
    border-color : #".$borderColour.";
    }
tr.odd {
    background-color : #".$femaleBackColour.";
    color : #".$femaleTextColour.";
    font-family : ".$femaleTextFont.";
    font-size : ".$femaleTextSize.";
    font-style : ".$femaleTextStyle.";
    border-style : ".$borderStyle.";
    border-width : ".$borderWidth."px;
    border-color : #".$borderColour.";
    }
td.head {
    background-color : #".$selectedBackColour.";
    color : #".$selectedTextColour.";
    font-family : ".$selectedTextFont.";
    font-size : ".$selectedTextSize.";
    font-style : ".$selectedTextStyle.";
    border-style : ".$borderStyle.";
    border-width : ".$borderWidth."px;
    border-color : #".$borderColour.";
    }
th {
    background-color : #".$selectedBackColour.";
    color : #".$selectedTextColour.";
    font-family : ".$selectedTextFont.";
    font-size : ".$selectedTextSize.";
    font-style : ".$selectedTextStyle.";
    border-style : ".$borderStyle.";
    border-width : ".$borderWidth."px;
    border-color : #".$borderColour.";
    }
-->
</style>";
