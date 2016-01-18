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
 * @version         $Id: owner.php 12277 2014-01-26 01:21:57Z beckmi $
 */

include_once 'admin_header.php';
//It recovered the value of argument op in URL$
$op = animal_CleanVars($_REQUEST, 'op', 'list', 'string');
switch ($op) {
    case "list":
    default:
        echo $adminMenu->addNavigation('owner.php');
        $adminMenu->addItemButton(_AM_PEDIGREE_NEWOWNER, 'owner.php?op=new_owner', 'add');
        echo $adminMenu->renderButton('left');
        $criteria = new CriteriaCompo();
        $criteria->setSort("ID");
        $criteria->setOrder("ASC");
        $numrows   = $pedigreeOwnerHandler->getCount();
        $owner_arr = $pedigreeOwnerHandler->getall($criteria);

        //Table view
        if ($numrows > 0) {
            echo "<table width='100%' cellspacing='1' class='outer'>
                <tr>
                    <th align=\"center\">" . _AM_PEDIGREE_OWNER_FIRSTNAME . "</th>
                        <th align=\"center\">" . _AM_PEDIGREE_OWNER_LASTNAME . "</th>
                        <th align=\"center\">" . _AM_PEDIGREE_OWNER_POSTCODE . "</th>
                        <th align=\"center\">" . _AM_PEDIGREE_OWNER_CITY . "</th>
                        <th align=\"center\">" . _AM_PEDIGREE_OWNER_STREETNAME . "</th>
                        <th align=\"center\">" . _AM_PEDIGREE_OWNER_HOUSENUMBER . "</th>
                        <th align=\"center\">" . _AM_PEDIGREE_OWNER_PHONENUMBER . "</th>
                        <th align=\"center\">" . _AM_PEDIGREE_OWNER_EMAILADRES . "</th>
                        <th align=\"center\">" . _AM_PEDIGREE_OWNER_WEBSITE . "</th>
                        <th align=\"center\">" . _AM_PEDIGREE_OWNER_USER . "</th>

                    <th align='center' width='10%'>" . _AM_PEDIGREE_FORMACTION . "</th>
                </tr>";

            $class = "odd";

            foreach (array_keys($owner_arr) as $i) {
                if ($owner_arr[$i]->getVar("owner_pid") == 0) {
                    echo "<tr class='" . $class . "'>";
                    $class = ($class == "even") ? "odd" : "even";
                    echo "<td align=\"center\">" . $owner_arr[$i]->getVar("firstname") . "</td>";
                    echo "<td align=\"center\">" . $owner_arr[$i]->getVar("lastname") . "</td>";
                    echo "<td align=\"center\">" . $owner_arr[$i]->getVar("postcode") . "</td>";
                    echo "<td align=\"center\">" . $owner_arr[$i]->getVar("city") . "</td>";
                    echo "<td align=\"center\">" . $owner_arr[$i]->getVar("streetname") . "</td>";
                    echo "<td align=\"center\">" . $owner_arr[$i]->getVar("housenumber") . "</td>";
                    echo "<td align=\"center\">" . $owner_arr[$i]->getVar("phonenumber") . "</td>";
                    echo "<td align=\"center\">" . $owner_arr[$i]->getVar("emailadres") . "</td>";
                    echo "<td align=\"center\">" . $owner_arr[$i]->getVar("website") . "</td>";
                    echo "<td align=\"center\">" . $owner_arr[$i]->getVar("user") . "</td>";

                    echo "<td align='center' width='10%'>
                        <a href='owner.php?op=edit_owner&ID=" . $owner_arr[$i]->getVar("ID") . "'><img src=" . $pathIcon16 . "/edit.png alt='" . _EDIT . "' title='" . _EDIT . "'></a>
                        <a href='owner.php?op=delete_owner&ID=" . $owner_arr[$i]->getVar("ID") . "'><img src=" . $pathIcon16 . "/delete.png alt='" . _DELETE . "' title='" . _DELETE . "'></a>
                        </td>";
                    echo "</tr>";
                }
            }
            echo "</table><br /><br />";
        }

        break;

    case "new_owner":
        echo $adminMenu->addNavigation("owner.php");
        $adminMenu->addItemButton(_AM_PEDIGREE_OWNERLIST, 'owner.php?op=list', 'list');
        echo $adminMenu->renderButton();

        $obj  =& $pedigreeOwnerHandler->create();
        $form = $obj->getForm();
        $form->display();
        break;

    case "save_owner":
        if (!$GLOBALS["xoopsSecurity"]->check()) {
            redirect_header("owner.php", 3, implode(",", $GLOBALS["xoopsSecurity"]->getErrors()));
        }
        if (isset($_REQUEST["ID"])) {
            $obj =& $pedigreeOwnerHandler->get($_REQUEST["ID"]);
        } else {
            $obj =& $pedigreeOwnerHandler->create();
        }

        //Form firstname
        $obj->setVar("firstname", $_REQUEST["firstname"]);
        //Form lastname
        $obj->setVar("lastname", $_REQUEST["lastname"]);
        //Form postcode
        $obj->setVar("postcode", $_REQUEST["postcode"]);
        //Form city
        $obj->setVar("city", $_REQUEST["city"]);
        //Form streetname
        $obj->setVar("streetname", $_REQUEST["streetname"]);
        //Form housenumber
        $obj->setVar("housenumber", $_REQUEST["housenumber"]);
        //Form phonenumber
        $obj->setVar("phonenumber", $_REQUEST["phonenumber"]);
        //Form emailadres
        $obj->setVar("emailadres", $_REQUEST["emailadres"]);
        //Form website
        $obj->setVar("website", $_REQUEST["website"]);
        //Form user
        $obj->setVar("user", $_REQUEST["user"]);

        if ($pedigreeOwnerHandler->insert($obj)) {
            redirect_header("owner.php?op=list", 2, _AM_PEDIGREE_FORMOK);
        }

        echo $obj->getHtmlErrors();
        $form =& $obj->getForm();
        $form->display();
        break;

    case "edit_owner":
        echo $adminMenu->addNavigation("owner.php");
        $adminMenu->addItemButton(_AM_PEDIGREE_NEWOWNER, 'owner.php?op=new_owner', 'add');
        $adminMenu->addItemButton(_AM_PEDIGREE_OWNERLIST, 'owner.php?op=list', 'list');
        echo $adminMenu->renderButton();
        $obj  = $pedigreeOwnerHandler->get($_REQUEST["ID"]);
        $form = $obj->getForm();
        $form->display();
        break;

    case "delete_owner":
        $obj =& $pedigreeOwnerHandler->get($_REQUEST["ID"]);
        if (isset($_REQUEST["ok"]) && $_REQUEST["ok"] == 1) {
            if (!$GLOBALS["xoopsSecurity"]->check()) {
                redirect_header("owner.php", 3, implode(",", $GLOBALS["xoopsSecurity"]->getErrors()));
            }
            if ($pedigreeOwnerHandler->delete($obj)) {
                redirect_header("owner.php", 3, _AM_PEDIGREE_FORMDELOK);
            } else {
                echo $obj->getHtmlErrors();
            }
        } else {
            xoops_confirm(array("ok" => 1, "ID" => $_REQUEST["ID"], "op" => "delete_owner"), $_SERVER["REQUEST_URI"], sprintf(_AM_PEDIGREE_FORMSUREDEL, $obj->getVar("owner")));
        }
        break;
}
include_once 'admin_footer.php';
