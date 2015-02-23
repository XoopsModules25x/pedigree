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
 * @version         $Id: pedigree.php 12841 2014-11-12 13:14:13Z beckmi $
 */

include_once __DIR__ . '/admin_header.php';
//It recovered the value of argument op in URL$
$op = animal_CleanVars($_REQUEST, 'op', 'list', 'string');
switch ($op) {
    case "list":
    default:
        echo $adminMenu->addNavigation('pedigree.php');
        $adminMenu->addItemButton(_AM_PEDIGREE_NEWPEDIGREE, 'pedigree.php?op=new_pedigree', 'add');
        echo $adminMenu->renderButton('left');
        $criteria = new CriteriaCompo();
        $criteria->setSort("ID");
        $criteria->setOrder("ASC");
        $numrows      = $pedigreeTreeHandler->getCount();
        $pedigree_arr = $pedigreeTreeHandler->getall($criteria);

        //Table view
        if ($numrows > 0) {
            echo "<table width='100%' cellspacing='1' class='outer'>
                <tr>
                    <th align=\"center\">" . _AM_PEDIGREE_PEDIGREE_NAAM . "</th>
                        <th align=\"center\">" . _AM_PEDIGREE_PEDIGREE_ID_OWNER . "</th>
                        <th align=\"center\">" . _AM_PEDIGREE_PEDIGREE_ID_BREEDER . "</th>
                        <th align=\"center\">" . _AM_PEDIGREE_PEDIGREE_USER . "</th>
                        <th align=\"center\">" . _AM_PEDIGREE_PEDIGREE_ROFT . "</th>
                        <th align=\"center\">" . _AM_PEDIGREE_PEDIGREE_MOTHER . "</th>
                        <th align=\"center\">" . _AM_PEDIGREE_PEDIGREE_FATHER . "</th>
                        <th align=\"center\">" . _AM_PEDIGREE_PEDIGREE_FOTO . "</th>
                        <th align=\"center\">" . _AM_PEDIGREE_PEDIGREE_COI . "</th>

                    <th align='center' width='10%'>" . _AM_PEDIGREE_FORMACTION . "</th>
                </tr>";

            $class = "odd";

            foreach (array_keys($pedigree_arr) as $i) {
                if ($pedigree_arr[$i]->getVar("pedigree_pid") == 0) {
                    echo "<tr class='" . $class . "'>";
                    $class = ($class == "even") ? "odd" : "even";
                    echo "<td align=\"center\">" . $pedigree_arr[$i]->getVar("NAAM") . "</td>";
                    echo "<td align=\"center\">" . $pedigree_arr[$i]->getVar("id_owner") . "</td>";
                    echo "<td align=\"center\">" . $pedigree_arr[$i]->getVar("id_breeder") . "</td>";
                    echo "<td align=\"center\">" . $pedigree_arr[$i]->getVar("user") . "</td>";
                    echo "<td align=\"center\">" . $pedigree_arr[$i]->getVar("roft") . "</td>";
                    echo "<td align=\"center\">" . $pedigree_arr[$i]->getVar("mother") . "</td>";
                    echo "<td align=\"center\">" . $pedigree_arr[$i]->getVar("father") . "</td>";
                    echo "<td align=\"center\">" . $pedigree_arr[$i]->getVar("foto") . "</td>";
                    echo "<td align=\"center\">" . $pedigree_arr[$i]->getVar("coi") . "</td>";

                    echo "<td align='center' width='10%'>
                        <a href='pedigree.php?op=edit_pedigree&ID=" . $pedigree_arr[$i]->getVar("ID") . "'><img src=" . $pathIcon16 . "/edit.png alt='" . _EDIT . "' title='" . _EDIT . "'></a>
                        <a href=" . PEDIGREE_URL ."/delete.php?id=" . $pedigree_arr[$i]->getVar("ID") . "><img src=" . $pathIcon16 . "/delete.png alt='" . _DELETE . "' title='" . _DELETE . "'></a>
                        </td>";
                    echo "</tr>";
                }
            }
            echo "</table><br /><br />";
        }

        break;

    case "new_pedigree":
        echo $adminMenu->addNavigation("pedigree.php");
        $adminMenu->addItemButton(_AM_PEDIGREE_PEDIGREELIST, 'pedigree.php?op=list', 'list');
        echo $adminMenu->renderButton();

        $obj  =& $pedigreeTreeHandler->create();
        $form = $obj->getForm();
        $form->display();
        break;

    case "save_pedigree":
        if (!$GLOBALS["xoopsSecurity"]->check()) {
            redirect_header("pedigree.php", 3, implode(",", $GLOBALS["xoopsSecurity"]->getErrors()));
        }
        if (isset($_REQUEST["ID"])) {
            $obj =& $pedigreeTreeHandler->get($_REQUEST["ID"]);
        } else {
            $obj =& $pedigreeTreeHandler->create();
        }

        //Form NAAM
        $obj->setVar("NAAM", $_REQUEST["NAAM"]);
        //Form id_owner
        $obj->setVar("id_owner", $_REQUEST["id_owner"]);
        //Form id_breeder
        $obj->setVar("id_breeder", $_REQUEST["id_breeder"]);
        //Form user
        $obj->setVar("user", $_REQUEST["user"]);
        //Form roft
        $obj->setVar("roft", $_REQUEST["roft"]);
        //Form mother
        $obj->setVar("mother", $_REQUEST["mother"]);
        //Form father
        $obj->setVar("father", $_REQUEST["father"]);
        //Form foto
        $obj->setVar("foto", $_REQUEST["foto"]);
        //Form coi
        $obj->setVar("coi", $_REQUEST["coi"]);

        if ($pedigreeTreeHandler->insert($obj)) {
            redirect_header("pedigree.php?op=list", 2, _AM_PEDIGREE_FORMOK);
        }

        echo $obj->getHtmlErrors();
        $form =& $obj->getForm();
        $form->display();
        break;

    case "edit_pedigree":
        echo $adminMenu->addNavigation("pedigree.php");
        $adminMenu->addItemButton(_AM_PEDIGREE_NEWPEDIGREE, 'pedigree.php?op=new_pedigree', 'add');
        $adminMenu->addItemButton(_AM_PEDIGREE_PEDIGREELIST, 'pedigree.php?op=list', 'list');
        echo $adminMenu->renderButton();
        $obj  = $pedigreeTreeHandler->get($_REQUEST["ID"]);
        $form = $obj->getForm();
        $form->display();
        break;

    case "delete_pedigree":
        $obj =& $pedigreeTreeHandler->get($_REQUEST["ID"]);
        if (isset($_REQUEST["ok"]) && $_REQUEST["ok"] == 1) {
            if (!$GLOBALS["xoopsSecurity"]->check()) {
                redirect_header("pedigree.php", 3, implode(",", $GLOBALS["xoopsSecurity"]->getErrors()));
            }
            if ($pedigreeTreeHandler->delete($obj)) {
                redirect_header("pedigree.php", 3, _AM_PEDIGREE_FORMDELOK);
            } else {
                echo $obj->getHtmlErrors();
            }
        } else {
            xoops_confirm(array("ok" => 1, "ID" => $_REQUEST["ID"], "op" => "delete_pedigree"), $_SERVER["REQUEST_URI"], sprintf(_AM_PEDIGREE_FORMSUREDEL, $obj->getVar("pedigree")));
        }
        break;
}
include_once __DIR__ . '/admin_footer.php';
