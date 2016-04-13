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

require_once dirname(dirname(dirname(__DIR__))) . '/include/cp_header.php';
//require_once(XOOPS_ROOT_PATH . "/modules/" . $xoopsModule->dirname() . "/include/pedigree_includes.php");
//require_once dirname(__DIR__) . "/include/pedigree_includes.php";
/*
if (file_exists("../language/" . $xoopsConfig['language'] . "/modinfo.php")) {
    include_once '../language/' . $xoopsConfig['language'] . "/modinfo.php";
} else {
    include_once '../language/english/modinfo.php';
}
*/
xoops_loadLanguage('main', basename(dirname(dirname(__DIR__))));
require_once(XOOPS_ROOT_PATH . "/modules/" . $xoopsModule->dirname() . "/admin/menu.php");

// Get HTTP post/get parameters.
//import_request_variables("gp", "param_");
extract($_GET, EXTR_PREFIX_ALL, "param");
extract($_POST, EXTR_PREFIX_ALL, "param");

//
// Writes out the form to get all config parameters.
//
function pedigree_config_form()
{
    $config_fields = pedigree_get_config_fields();
    $values        = pedigree_get_config();
    print "
    <form action='config.php' method='POST' enctype='application/x-www-form-urlencoded'>\n
    <table border='1' cellpadding='0' cellspacing='0' width='100%'>\n
        <tr><th>" . _AM_PEDIGREE_CTITLE . "</th></tr>\n
        <tr>\n
        <td class='bg2'>\n
            <table width='100%' border='0' cellpadding='4' cellspacing='1'>\n";

    foreach ($config_fields as $field => $prompt) {
        if ($field == 'config_id') {
            continue;
        }
        $pname = "param_" . $field;
        print "
                <tr nowrap='nowrap'>\n
                <td class ='head'>" . $prompt . "</td>\n
                <td class='even' valign = 'top'>\n
                    <input type='text' name='$field' size='32' maxlength='32' value ='" . $values[$field] . "'>\n
                </td></tr>\n
                </tr>\n";
    }
    print "
                <td class='head'>&nbsp;</td>\n
                <td class='even'>\n
                    <input type='hidden' name='op' value='config' />\n
                    <input type='hidden' name='window' value='config' />\n
                    <input type='submit' value='" . _AM_PEDIGREE_BUT_GO . "' />\n
                </td></tr>\n
            </table>\n
        </td></tr>\n
    </table>\n
    </form>\n";
}

//
// Displays the main admin interface
//
function pedigree_config_main()
{
    //xoops_cp_header();
    $p_title = _AM_PEDIGREE_CONFIGURE;
    print "<h4 style='text-align:left;'>$p_title</h4>";
    pedigree_admin_hmenu();
    pedigree_config_form();
    xoops_cp_footer();
    exit();
}

// Processes the configuration update request, by
// getting the HTTP parameters, and putting them into the database.
function pedigree_config_post()
{
    global $xoopsDB;
    $config_fields = pedigree_get_config_fields();
    foreach ($config_fields as $field => $prompt) {
        $param = "param_" . $field;
        global $$param;
    }
    $param_config_id = 1;
    $sql             = "REPLACE INTO " . $xoopsDB->prefix("pedigree_config") . " (" . pedigree_to_string($config_fields) . ") VALUES (";

    $first = true;
    foreach ($config_fields as $field => $prompt) {
        $param = "param_" . $field;
        if (!$first) {
            $sql .= ", ";
        }
        // Handle a 'feature' of PHP that adds backslashes to HTTP parameters.
        $param_value = get_magic_quotes_gpc() ? stripslashes($$param) : $$param;
        $sql .= "'" . $xoopsDB->escape($param_value) . "'";
        $first = false;
    }
    $sql .= " )";
    if (!$xoopsDB->query($sql)) {
        $error = $xoopsDB->error();
        xoops_cp_header();
        pedigree_show_sql_error(_AM_PEDIGREE_ERR_ADD_FAILED, $error, $sql);
        xoops_cp_footer();
    } else {
        redirect_header("config.php", 1, _AM_PEDIGREE_OK_DB);
    }
    exit();
}

if (!isset($param_op)) {
    $param_op = 'main';
}

switch ($param_op) {
    case "main":
        pedigree_config_main();
        break;
    case "config":
        pedigree_config_post();
        break;
    default:
        //xoops_cp_header();
        print "<h1>Unknown method requested '$param_op'</h1>";
        xoops_cp_footer();
}
