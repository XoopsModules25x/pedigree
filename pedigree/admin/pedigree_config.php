<?php
/*
 You may not change or alter any portion of this comment or credits
 of supporting developers from this source code or any supporting source code
 which is considered copyrighted (c) material of the original comment or credit authors.

 This program is distributed in the hope that it will be useful,
 but WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
*/
/**
 * animal module for xoops
 *
 * @copyright       The TXMod XOOPS Project http://sourceforge.net/projects/thmod/
 * @copyright       The XOOPS Project http://sourceforge.net/projects/xoops/
 * @license         GPL 2.0 or later
 * @package         animal
 * @since           2.5.x
 * @author          XOOPS Development Team ( name@site.com ) - ( http://xoops.org )
 * @version         $Id: pedigree_config.php 12865 2014-11-22 07:03:35Z beckmi $
 */

include_once 'admin_header.php';
//It recovered the value of argument op in URL$
$op = animal_CleanVars($_REQUEST, 'op', 'list', 'string');
switch ($op) {
    case "list":
    default:
        echo $adminMenu->addNavigation('pedigree_config.php');
        $adminMenu->addItemButton(_AM_PEDIGREE_NEWPEDIGREE_CONFIG, 'pedigree_config.php?op=new_pedigree_config', 'add');
        echo $adminMenu->renderButton('left');
        $criteria = new CriteriaCompo();
        $criteria->setSort("ID");
        $criteria->setOrder("ASC");
        $numrows             = $pedigreeFieldsHandler->getCount();
        $pedigree_config_arr = $pedigreeFieldsHandler->getall($criteria);

        //Table view
        if ($numrows > 0) {
            echo "<table width='100%' cellspacing='1' class='outer'>
                <tr>
                    <th align=\"center\">" . _AM_PEDIGREE_PEDIGREE_CONFIG_ISACTIVE . "</th>
                        <th align=\"center\">" . _AM_PEDIGREE_PEDIGREE_CONFIG_FIELDNAME . "</th>
                        <th align=\"center\">" . _AM_PEDIGREE_PEDIGREE_CONFIG_FIELDTYPE . "</th>
                        <th align=\"center\">" . _AM_PEDIGREE_PEDIGREE_CONFIG_LOOKUPTABLE . "</th>
                        <th align=\"center\">" . _AM_PEDIGREE_PEDIGREE_CONFIG_DEFAULTVALUE . "</th>
                        <th align=\"center\">" . _AM_PEDIGREE_PEDIGREE_CONFIG_FIELDEXPLENATION . "</th>
                        <th align=\"center\">" . _AM_PEDIGREE_PEDIGREE_CONFIG_HASSEARCH . "</th>
                        <th align=\"center\">" . _AM_PEDIGREE_PEDIGREE_CONFIG_LITTER . "</th>
                        <th align=\"center\">" . _AM_PEDIGREE_PEDIGREE_CONFIG_GENERALLITTER . "</th>
                        <th align=\"center\">" . _AM_PEDIGREE_PEDIGREE_CONFIG_SEARCHNAME . "</th>
                        <th align=\"center\">" . _AM_PEDIGREE_PEDIGREE_CONFIG_SEARCHEXPLENATION . "</th>
                        <th align=\"center\">" . _AM_PEDIGREE_PEDIGREE_CONFIG_VIEWINPEDIGREE . "</th>
                        <th align=\"center\">" . _AM_PEDIGREE_PEDIGREE_CONFIG_VIEWINADVANCED . "</th>
                        <th align=\"center\">" . _AM_PEDIGREE_PEDIGREE_CONFIG_VIEWINPIE . "</th>
                        <th align=\"center\">" . _AM_PEDIGREE_PEDIGREE_CONFIG_VIEWINLIST . "</th>

                    <th align='center' width='10%'>" . _AM_PEDIGREE_FORMACTION . "</th>
                </tr>";

            $class = "odd";

            foreach (array_keys($pedigree_config_arr) as $i) {
                if ($pedigree_config_arr[$i]->getVar("pedigree_config_pid") == 0) {
                    echo "<tr class='" . $class . "'>";
                    $class = ($class == "even") ? "odd" : "even";
                    echo "<td align=\"center\">" . $pedigree_config_arr[$i]->getVar("isActive") . "</td>";
                    echo "<td align=\"center\">" . $pedigree_config_arr[$i]->getVar("FieldName") . "</td>";
                    echo "<td align=\"center\">" . $pedigree_config_arr[$i]->getVar("FieldType") . "</td>";
                    echo "<td align=\"center\">" . $pedigree_config_arr[$i]->getVar("LookupTable") . "</td>";
                    echo "<td align=\"center\">" . $pedigree_config_arr[$i]->getVar("DefaultValue") . "</td>";
                    echo "<td align=\"center\">" . $pedigree_config_arr[$i]->getVar("FieldExplenation") . "</td>";
                    echo "<td align=\"center\">" . $pedigree_config_arr[$i]->getVar("HasSearch") . "</td>";
                    echo "<td align=\"center\">" . $pedigree_config_arr[$i]->getVar("Litter") . "</td>";
                    echo "<td align=\"center\">" . $pedigree_config_arr[$i]->getVar("Generallitter") . "</td>";
                    echo "<td align=\"center\">" . $pedigree_config_arr[$i]->getVar("SearchName") . "</td>";
                    echo "<td align=\"center\">" . $pedigree_config_arr[$i]->getVar("SearchExplenation") . "</td>";
                    echo "<td align=\"center\">" . $pedigree_config_arr[$i]->getVar("ViewInPedigree") . "</td>";
                    echo "<td align=\"center\">" . $pedigree_config_arr[$i]->getVar("ViewInAdvanced") . "</td>";
                    echo "<td align=\"center\">" . $pedigree_config_arr[$i]->getVar("ViewInPie") . "</td>";
                    echo "<td align=\"center\">" . $pedigree_config_arr[$i]->getVar("ViewInList") . "</td>";

                    echo "<td align='center' width='10%'>
                        <a href='pedigree_config.php?op=edit_pedigree_config&ID=" . $pedigree_config_arr[$i]->getVar("ID") . "'><img src=" . $pathIcon16 . "/edit.png alt='" . _EDIT . "' title='"
                        . _EDIT . "'></a>
                        <a href='pedigree_config.php?op=delete_pedigree_config&ID=" . $pedigree_config_arr[$i]->getVar("ID") . "'><img src=" . $pathIcon16 . "/delete.png alt='" . _DELETE . "' title='"
                        . _DELETE . "'></a>
                        </td>";
                    echo "</tr>";
                }
            }
            echo "</table><br /><br />";
        }

        break;

    case "new_pedigree_config":
        echo $adminMenu->addNavigation("pedigree_config.php");
        $adminMenu->addItemButton(_AM_PEDIGREE_PEDIGREE_CONFIGLIST, 'pedigree_config.php?op=list', 'list');
        echo $adminMenu->renderButton();

        $obj  =& $pedigreeFieldsHandler->create();
        $form = $obj->getForm();
        $form->display();
        break;

    case "save_pedigree_config":
        if (!$GLOBALS["xoopsSecurity"]->check()) {
            redirect_header("pedigree_config.php", 3, implode(",", $GLOBALS["xoopsSecurity"]->getErrors()));
        }
        if (isset($_REQUEST["ID"])) {
            $obj =& $pedigreeFieldsHandler->get($_REQUEST["ID"]);
        } else {
            $obj =& $pedigreeFieldsHandler->create();
        }

        //Form isActive
        $obj->setVar("isActive", $_REQUEST["isActive"]);
        //Form FieldName
        $obj->setVar("FieldName", $_REQUEST["FieldName"]);
        //Form FieldType
        $obj->setVar("FieldType", $_REQUEST["FieldType"]);
        //Form LookupTable
        $obj->setVar("LookupTable", $_REQUEST["LookupTable"]);
        //Form DefaultValue
        $obj->setVar("DefaultValue", $_REQUEST["DefaultValue"]);
        //Form FieldExplenation
        $obj->setVar("FieldExplenation", $_REQUEST["FieldExplenation"]);
        //Form HasSearch
        $obj->setVar("HasSearch", $_REQUEST["HasSearch"]);
        //Form Litter
        $obj->setVar("Litter", $_REQUEST["Litter"]);
        //Form Generallitter
        $obj->setVar("Generallitter", $_REQUEST["Generallitter"]);
        //Form SearchName
        $obj->setVar("SearchName", $_REQUEST["SearchName"]);
        //Form SearchExplenation
        $obj->setVar("SearchExplenation", $_REQUEST["SearchExplenation"]);
        //Form ViewInPedigree
        $obj->setVar("ViewInPedigree", $_REQUEST["ViewInPedigree"]);
        //Form ViewInAdvanced
        $obj->setVar("ViewInAdvanced", $_REQUEST["ViewInAdvanced"]);
        //Form ViewInPie
        $obj->setVar("ViewInPie", $_REQUEST["ViewInPie"]);
        //Form ViewInList
        $obj->setVar("ViewInList", $_REQUEST["ViewInList"]);
        //Form locked
        $obj->setVar("locked", $_REQUEST["locked"]);
        //Form order
        $obj->setVar("order", $_REQUEST["order"]);

        if ($pedigreeFieldsHandler->insert($obj)) {
            redirect_header("pedigree_config.php?op=list", 2, _AM_PEDIGREE_FORMOK);
        }

        echo $obj->getHtmlErrors();
        $form =& $obj->getForm();
        $form->display();
        break;

    case "edit_pedigree_config":
        echo $adminMenu->addNavigation("pedigree_config.php");
        $adminMenu->addItemButton(_AM_PEDIGREE_NEWPEDIGREE_CONFIG, 'pedigree_config.php?op=new_pedigree_config', 'add');
        $adminMenu->addItemButton(_AM_PEDIGREE_PEDIGREE_CONFIGLIST, 'pedigree_config.php?op=list', 'list');
        echo $adminMenu->renderButton();
        $obj  = $pedigreeFieldsHandler->get($_REQUEST["ID"]);
        $form = $obj->getForm();
        $form->display();
        break;

    case "delete_pedigree_config":
        $obj =& $pedigreeFieldsHandler->get($_REQUEST["ID"]);
        if (isset($_REQUEST["ok"]) && $_REQUEST["ok"] == 1) {
            if (!$GLOBALS["xoopsSecurity"]->check()) {
                redirect_header("pedigree_config.php", 3, implode(",", $GLOBALS["xoopsSecurity"]->getErrors()));
            }
            if ($pedigreeFieldsHandler->delete($obj)) {
                redirect_header("pedigree_config.php", 3, _AM_PEDIGREE_FORMDELOK);
            } else {
                echo $obj->getHtmlErrors();
            }
        } else {
            xoops_confirm(array("ok" => 1, "ID" => $_REQUEST["ID"], "op" => "delete_pedigree_config"), $_SERVER["REQUEST_URI"], sprintf(_AM_PEDIGREE_FORMSUREDEL, $obj->getVar("pedigree_config")));
        }
        break;
}
include_once 'admin_footer.php';
