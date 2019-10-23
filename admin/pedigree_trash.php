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
 * Module: Pedigree
 *
 * @package         Xoopsmodules\Pedigree
 * @copyright       2011-2018 XOOPS Project (https://xoops.org)
 * @license         GNU GPL 2 or later (http://www.gnu.org/licenses/gpl-2.0.html)
 * @author          XOOPS Module Dev Team (https://xoops.org)
 */

use Xmf\Request;
use XoopsModules\Pedigree;

require_once __DIR__ . '/admin_header.php';

xoops_cp_header();
/** @var \Xmf\Module\Admin $adminObject */

$trashHandler = Pedigree\Helper::getInstance()->getHandler('Trash');

$op = Request::getCmd('op', 'list');
switch ($op) {
    case 'list':
    default:
        $adminObject->displayNavigation(basename(__FILE__));
        $adminObject->addItemButton(_AM_PEDIGREE_NEWPEDIGREE_TRASH, 'pedigree_trash.php?op=new_pedigree_trash', 'add');
        //$adminObject->displayButton('left');
        $criteria = new \CriteriaCompo();
        $criteria->setSort('id');
        $criteria->setOrder('ASC');
        $numRows = $trashHandler->getCount();
        $pedigreeTrashArr = $trashHandler->getAll($criteria);

        //Table view
        if ($numRows > 0) {
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
                    <th class='txtcenter' width='10%'>" . _AM_PEDIGREE_FORMACTION . '</th>
                </tr>
                </thead>
                <tbody>';

            $class = 'odd';

            foreach ($pedigreeTrashArr) as $tId => $trashObj) {
                if (0 == $trashObj->getVar('pedigree_trash_pid')) {
                    echo "<tr class='{$class}'>";
                    $class = ('even' === $class) ? 'odd' : 'even';
                    echo "<td class='txtcenter'>" . $trashObj->getVar('naam') . '</td>';
                    echo "<td class='txtcenter'>" . $trashObj->getVar('id_owner') . '</td>';
                    echo "<td class='txtcenter'>" . $trashObj->getVar('id_breeder') . '</td>';
                    echo "<td class='txtcenter'>" . $trashObj->getVar('user') . '</td>';
                    echo "<td class='txtcenter'>" . $trashObj->getVar('roft') . '</td>';
                    echo "<td class='txtcenter'>" . $trashObj->getVar('mother') . '</td>';
                    echo "<td class='txtcenter'>" . $trashObj->getVar('father') . '</td>';
                    echo "<td class='txtcenter'>" . $trashObj->getVar('foto') . '</td>';
                    echo "<td class='txtcenter'>" . $trashObj->getVar('coi') . '</td>';
                    echo "<td class'txtcenter width10'>
                        <a href='pedigree_trash.php?op=edit_pedigree_trash&id=" . $tId . "'><img src='{$pathIcon16}/edit.png' alt='" . _EDIT . "' title='" . _EDIT . "'></a>
                        <a href='pedigree_trash.php?op=delete_pedigree_trash&id=" . $tId . "'><img src='{$pathIcon16}/delete.png' alt='" . _DELETE . "' title='" . _DELETE . "'></a>
                        </td>";
                    echo '</tr>';
                }
            }
            echo '</tbody>
                  </table>
                  <br><br>';
        }

        break;

    case 'new_pedigree_trash':
        $adminObject->displayNavigation(basename(__FILE__));
        $adminObject->addItemButton(_AM_PEDIGREE_PEDIGREE_TRASHLIST, 'pedigree_trash.php?op=list', 'list');
        $adminObject->displayButton('left');

        $tObj  = $trashHandler->create();
        $form = $tObj->getForm();
        $form->display();
        break;

    case 'save_pedigree_trash':
        if (!$GLOBALS['xoopsSecurity']->check()) {
            $helper->redirect('admin/pedigree_trash.php', 3, implode(',', $GLOBALS['xoopsSecurity']->getErrors()));
        }
        $tId = Request::getInt('id', null, 'POST');
        $tObj = $trashHandler->get($tId); // gets object or creates one if tId is empty
        /*
        if (isset($_REQUEST['id'])) {
            $obj = $trashHandler->get($_REQUEST['id']);
        } else {
            $obj = $trashHandler->create();
        }
        */

        $tObj->setVar('naam', Request::getString('naam', '', 'POST')); //Form naam
        $tObj->setVar('id_owner', Request::getInt('id_owner', 0, 'POST')); //Form id_owner
        $tObj->setVar('id_breeder', Request::getInt('id_breeder', 0, 'POST')); //Form id_breeder
        $tObj->setVar('user', Request::getString('user', '', 'POST')); //Form user
        $tObj->setVar('roft', Request::getString('roft', '', 'POST')); //Form roft
        $tObj->setVar('mother', Request::getInt('mother', 0, 'POST')); //Form mother
        $tObj->setVar('father', Request::getInt('father', 0, 'POST')); //Form father
        $tObj->setVar('foto', Request::getString('foto', '', 'POST')); //Form foto
        $tObj->setVar('coi', Request::getString('coi', '', 'POST')); //Form coi

        if ($trashHandler->insert($tObj)) {
            $helper->redirect('admin/pedigree_trash.php?op=list', 2, _AM_PEDIGREE_FORMOK);
        }

        echo $tObj->getHtmlErrors();
        $form = $tObj->getForm();
        $form->display();
        break;

    case 'edit_pedigree_trash':
        $adminObject->displayNavigation(basename(__FILE__));
        $adminObject->addItemButton(_AM_PEDIGREE_NEWPEDIGREE_TRASH, 'pedigree_trash.php?op=new_pedigree_trash', 'add');
        $adminObject->addItemButton(_AM_PEDIGREE_PEDIGREE_TRASHLIST, 'pedigree_trash.php?op=list', 'list');
        $adminObject->displayButton('left');
        $obj  = $trashHandler->get($_REQUEST['id']);
        $form = $obj->getForm();
        $form->display();
        break;

    case 'delete_pedigree_trash':
        $tId = Request::getInt('id', 0);
        if (!$tId)) {
            $helper->redirect('admin/pedigree_trash.php', 3, _AM_PEDIGREE_ERR_INVALID);
        }
        $tObj = $trashHandler->get($tId);
        if (isset($_REQUEST['ok']) && 1 == $_REQUEST['ok']) {
            if (!$GLOBALS['xoopsSecurity']->check()) {
                $helper->redirect('admin/pedigree_trash.php', 3, implode(',', $GLOBALS['xoopsSecurity']->getErrors()));
            }
            if ($trashHandler->delete($obj)) {
                $helper->redirect('admin/pedigree_trash.php', 3, _AM_PEDIGREE_FORMDELOK);
            } else {
                echo $obj->getHtmlErrors();
            }
        } else {
            xoops_confirm(['ok' => 1, 'id' => $tId, 'op' => 'delete_pedigree_trash'], $_SERVER['REQUEST_URI'], sprintf(_AM_PEDIGREE_FORMSUREDEL, $obj->getVar('pedigree_trash')));
        }
        break;
}
require_once __DIR__ . '/admin_footer.php';
