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
 * Pedigree module for XOOPS
 *
 * @copyright       {@link http://sourceforge.net/projects/xoops/ The XOOPS Project}
 * @license         GPL 2.0 or later
 * @package         pedigree
 * @since
 * @author          XOOPS Module Dev Team (http://xoops.org)
 */

include_once __DIR__ . '/admin_header.php';

xoops_cp_header();
$adminMenu = new ModuleAdmin();

//It recovered the value of argument op in URL$
$op = XoopsRequest::getCmd('op', 'list');
switch ($op) {
    case 'list':
    default:
        echo $adminMenu->addNavigation(basename(__FILE__));
        $adminMenu->addItemButton(_AM_PEDIGREE_NEWPEDIGREE_TRASH, 'pedigree_trash.php?op=new_pedigree_trash', 'add');
        //        echo $adminMenu->renderButton();
        $criteria = new CriteriaCompo();
        $criteria->setSort('Id');
        $criteria->setOrder('ASC');
        $numrows            = $pedigreeTrashHandler->getCount();
        $pedigree_trash_arr = $pedigreeTrashHandler->getall($criteria);

        //Table view
        if ($numrows > 0) {
            echo "<table cellspacing='1' class='outer width100'>
                <thead>
                <tr>
                    <th class='txtcenter'>" . _AM_PEDIGREE_PEDIGREE_TRASH_NAAM . "</th>
                        <th class='txtcenter'>" . _AM_PEDIGREE_PEDIGREE_TRASH_ID_OWNER . "</th>
                        <th class='txtcenter'>" . _AM_PEDIGREE_PEDIGREE_TRASH_ID_BREEDER . "</th>
                        <th class='txtcenter'>" . _AM_PEDIGREE_PEDIGREE_TRASH_USER . "</th>
                        <th class='txtcenter'>" . _AM_PEDIGREE_PEDIGREE_TRASH_ROFT . "</th>
                        <th class='txtcenter'>" . _AM_PEDIGREE_PEDIGREE_TRASH_MOTHER . "</th>
                        <th class='txtcenter'>" . _AM_PEDIGREE_PEDIGREE_TRASH_FATHER . "</th>
                        <th class='txtcenter'>" . _AM_PEDIGREE_PEDIGREE_TRASH_FOTO . "</th>
                        <th class='txtcenter'>" . _AM_PEDIGREE_PEDIGREE_TRASH_COI . "</th>

                    <th align='center' width='10%'>" . _AM_PEDIGREE_FORMACTION . '</th>
                </tr>
                </thead>
                <tbody>';

            $class = 'odd';

            foreach (array_keys($pedigree_trash_arr) as $i) {
                if (0 == $pedigree_trash_arr[$i]->getVar('pedigree_trash_pid')) {
                    echo "<tr class='{$class}'>";
                    $class = ($class === 'even') ? 'odd' : 'even';
                    echo "<td class='txtcenter'>" . $pedigree_trash_arr[$i]->getVar('NAAM') . '</td>';
                    echo "<td class='txtcenter'>" . $pedigree_trash_arr[$i]->getVar('id_owner') . '</td>';
                    echo "<td class='txtcenter'>" . $pedigree_trash_arr[$i]->getVar('id_breeder') . '</td>';
                    echo "<td class='txtcenter'>" . $pedigree_trash_arr[$i]->getVar('user') . '</td>';
                    echo "<td class='txtcenter'>" . $pedigree_trash_arr[$i]->getVar('roft') . '</td>';
                    echo "<td class='txtcenter'>" . $pedigree_trash_arr[$i]->getVar('mother') . '</td>';
                    echo "<td class='txtcenter'>" . $pedigree_trash_arr[$i]->getVar('father') . '</td>';
                    echo "<td class='txtcenter'>" . $pedigree_trash_arr[$i]->getVar('foto') . '</td>';
                    echo "<td class='txtcenter'>" . $pedigree_trash_arr[$i]->getVar('coi') . '</td>';
                    echo "<td class'txtcenter width10'>
                        <a href='pedigree_trash.php?op=edit_pedigree_trash&Id=" . $pedigree_trash_arr[$i]->getVar('Id') . "'><img src='{$pathIcon16}/edit.png' alt='" . _EDIT . "' title='" . _EDIT . "'></a>
                        <a href='pedigree_trash.php?op=delete_pedigree_trash&Id=" . $pedigree_trash_arr[$i]->getVar('Id') . "'><img src='{$pathIcon16}/delete.png' alt='" . _DELETE . "' title='" . _DELETE . "'></a>
                        </td>";
                    echo '</tr>';
                }
            }
            echo '</tbody>
                  </table>
                  <br /><br />';
        }

        break;

    case 'new_pedigree_trash':
        echo $adminMenu->addNavigation(basename(__FILE__));
        $adminMenu->addItemButton(_AM_PEDIGREE_PEDIGREE_TRASHLIST, 'pedigree_trash.php?op=list', 'list');
        echo $adminMenu->renderButton();

        $obj  = $pedigreeTrashHandler->create();
        $form = $obj->getForm();
        $form->display();
        break;

    case 'save_pedigree_trash':
        if (!$GLOBALS['xoopsSecurity']->check()) {
            redirect_header('pedigree_trash.php', 3, implode(',', $GLOBALS['xoopsSecurity']->getErrors()));
        }
        if (isset($_REQUEST['Id'])) {
            $obj = $pedigreeTrashHandler->get($_REQUEST['Id']);
        } else {
            $obj = $pedigreeTrashHandler->create();
        }

        //Form NAAM
        $obj->setVar('NAAM', $_REQUEST['NAAM']);
        //Form id_owner
        $obj->setVar('id_owner', $_REQUEST['id_owner']);
        //Form id_breeder
        $obj->setVar('id_breeder', $_REQUEST['id_breeder']);
        //Form user
        $obj->setVar('user', $_REQUEST['user']);
        //Form roft
        $obj->setVar('roft', $_REQUEST['roft']);
        //Form mother
        $obj->setVar('mother', $_REQUEST['mother']);
        //Form father
        $obj->setVar('father', $_REQUEST['father']);
        //Form foto
        $obj->setVar('foto', $_REQUEST['foto']);
        //Form coi
        $obj->setVar('coi', $_REQUEST['coi']);

        if ($pedigreeTrashHandler->insert($obj)) {
            redirect_header('pedigree_trash.php?op=list', 2, _AM_PEDIGREE_FORMOK);
        }

        echo $obj->getHtmlErrors();
        $form = $obj->getForm();
        $form->display();
        break;

    case 'edit_pedigree_trash':
        echo $adminMenu->addNavigation(basename(__FILE__));
        $adminMenu->addItemButton(_AM_PEDIGREE_NEWPEDIGREE_TRASH, 'pedigree_trash.php?op=new_pedigree_trash', 'add');
        $adminMenu->addItemButton(_AM_PEDIGREE_PEDIGREE_TRASHLIST, 'pedigree_trash.php?op=list', 'list');
        echo $adminMenu->renderButton();
        $obj  = $pedigreeTrashHandler->get($_REQUEST['Id']);
        $form = $obj->getForm();
        $form->display();
        break;

    case 'delete_pedigree_trash':
        $obj = $pedigreeTrashHandler->get($_REQUEST['Id']);
        if (isset($_REQUEST['ok']) && $_REQUEST['ok'] == 1) {
            if (!$GLOBALS['xoopsSecurity']->check()) {
                redirect_header('pedigree_trash.php', 3, implode(',', $GLOBALS['xoopsSecurity']->getErrors()));
            }
            if ($pedigreeTrashHandler->delete($obj)) {
                redirect_header('pedigree_trash.php', 3, _AM_PEDIGREE_FORMDELOK);
            } else {
                echo $obj->getHtmlErrors();
            }
        } else {
            xoops_confirm(array('ok' => 1, 'Id' => $_REQUEST['Id'], 'op' => 'delete_pedigree_trash'), $_SERVER['REQUEST_URI'], sprintf(_AM_PEDIGREE_FORMSUREDEL, $obj->getVar('pedigree_trash')));
        }
        break;
}
include_once __DIR__ . '/admin_footer.php';
