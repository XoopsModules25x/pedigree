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
 * @copyright       @link http://sourceforge.net/projects/xoops/ The XOOPS Project}
 * @license         GPL 2.0 or later
 * @package         pedigree
 * @since
 * @author          XOOPS Module Dev Team (https://xoops.org)
 */

use Xmf\Request;
use XoopsModules\Pedigree\{
    Helper
};

/** @var \XoopsThemeForm $form */

require_once __DIR__ . '/admin_header.php';

xoops_cp_header();
//$adminObject = \Xmf\Module\Admin::getInstance();

$tempHandler = Helper::getInstance()->getHandler('Temp');

//It recovered the value of argument op in URL$
$op = Request::getCmd('op', 'list');
switch ($op) {
    case 'list':
    default:
        $adminObject->displayNavigation(basename(__FILE__));
        $adminObject->addItemButton(_AM_PEDIGREE_NEWPEDIGREE_TEMP, 'pedigree_temp.php?op=new_pedigree_temp', 'add');
        $adminObject->displayButton('left');
        $criteria = new \CriteriaCompo();
        $criteria->setSort('id');
        $criteria->setOrder('ASC');
        $numrows           = $tempHandler->getCount();
        $pedigree_temp_arr = $tempHandler->getAll($criteria);

        //Table view
        if ($numrows > 0) {
            echo "<table cellspacing='1' class='outer width100'>
                <thead>
                <tr>
                    <th class='txtcenter'>" . _AM_PEDIGREE_PEDIGREE_TEMP_PNAME . "</th>
                        <th class='txtcenter'>" . _AM_PEDIGREE_PEDIGREE_TEMP_ID_OWNER . "</th>
                        <th class='txtcenter'>" . _AM_PEDIGREE_PEDIGREE_TEMP_ID_BREEDER . "</th>
                        <th class='txtcenter'>" . _AM_PEDIGREE_PEDIGREE_TEMP_USER . "</th>
                        <th class='txtcenter'>" . _AM_PEDIGREE_PEDIGREE_TEMP_ROFT . "</th>
                        <th class='txtcenter'>" . _AM_PEDIGREE_PEDIGREE_TEMP_MOTHER . "</th>
                        <th class='txtcenter'>" . _AM_PEDIGREE_PEDIGREE_TEMP_FATHER . "</th>
                        <th class='txtcenter'>" . _AM_PEDIGREE_PEDIGREE_TEMP_FOTO . "</th>
                        <th class='txtcenter width10'>" . _AM_PEDIGREE_FORMACTION . '</th>
                </tr>
                </thead>
                <tbody>';

            $class = 'odd';

            foreach (array_keys($pedigree_temp_arr) as $i) {
                if (0 == $pedigree_temp_arr[$i]->getVar('pedigree_temp_pid')) {
                    echo "<tr class='{$class}'>";
                    $class = ('even' === $class) ? 'odd' : 'even';
                    echo "<td class='txtcenter'>" . $pedigree_temp_arr[$i]->getVar('pname') . '</td>';
                    echo "<td class='txtcenter'>" . $pedigree_temp_arr[$i]->getVar('id_owner') . '</td>';
                    echo "<td class='txtcenter'>" . $pedigree_temp_arr[$i]->getVar('id_breeder') . '</td>';
                    echo "<td class='txtcenter'>" . $pedigree_temp_arr[$i]->getVar('user') . '</td>';
                    echo "<td class='txtcenter'>" . $pedigree_temp_arr[$i]->getVar('roft') . '</td>';
                    echo "<td class='txtcenter'>" . $pedigree_temp_arr[$i]->getVar('mother') . '</td>';
                    echo "<td class='txtcenter'>" . $pedigree_temp_arr[$i]->getVar('father') . '</td>';
                    echo "<td class='txtcenter'>" . $pedigree_temp_arr[$i]->getVar('foto') . '</td>';
                    echo "<td class-'txtcenter width10'>
                        <a href='pedigree_temp.php?op=edit_pedigree_temp&id=" . $pedigree_temp_arr[$i]->getVar('id') . "'><img src=" . $pathIcon16 . "/edit.png alt='" . _EDIT . "' title='" . _EDIT . "'></a>
                        <a href='pedigree_temp.php?op=delete_pedigree_temp&id=" . $pedigree_temp_arr[$i]->getVar('id') . "'><img src=" . $pathIcon16 . "/delete.png alt='" . _DELETE . "' title='" . _DELETE . "'></a>
                        </td>";
                    echo '</tr>';
                }
            }
            echo '</tbody>
                  </table>
                  <br><br>';
        }

        break;
    case 'new_pedigree_temp':
        $adminObject->displayNavigation(basename(__FILE__));
        $adminObject->addItemButton(_AM_PEDIGREE_PEDIGREE_TEMPLIST, 'pedigree_temp.php?op=list', 'list');
        $adminObject->displayButton('left');

        $tempHandler = Helper::getInstance()->getHandler('Temp');
        $obj         = $tempHandler->create();
        $form        = $obj->getForm();
        $form->display();
        break;
    case 'save_pedigree_temp':
        if (!$GLOBALS['xoopsSecurity']->check()) {
            $helper->redirect('admin/pedigree_temp.php', 3, implode(',', $GLOBALS['xoopsSecurity']->getErrors()));
        }
        if (isset($_REQUEST['id']) && (($id = Request::getInt('id', 1)) > 0)) {
            $obj = $tempHandler->get($id);
        } else {
            $obj = $tempHandler->create();
        }

        //Form pname
        $obj->setVar('pname', $_REQUEST['pname']);
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

        if ($tempHandler->insert($obj)) {
            $helper->redirect('admin/pedigree_temp.php?op=list', 2, _AM_PEDIGREE_FORMOK);
        }

        echo $obj->getHtmlErrors();
        $form = $obj->getForm();
        $form->display();
        break;
    case 'edit_pedigree_temp':
        $adminObject->displayNavigation(basename(__FILE__));
        $adminObject->addItemButton(_AM_PEDIGREE_NEWPEDIGREE_TEMP, 'pedigree_temp.php?op=new_pedigree_temp', 'add');
        $adminObject->addItemButton(_AM_PEDIGREE_PEDIGREE_TEMPLIST, 'pedigree_temp.php?op=list', 'list');
        $adminObject->displayButton('left');
        $obj  = $tempHandler->get($_REQUEST['id']);
        $form = $obj->getForm();
        $form->display();
        break;
    case 'delete_pedigree_temp':
        $obj = $tempHandler->get($_REQUEST['id']);
        if (isset($_REQUEST['ok']) && 1 == $_REQUEST['ok']) {
            if (!$GLOBALS['xoopsSecurity']->check()) {
                $helper->redirect('admin/pedigree_temp.php', 3, implode(',', $GLOBALS['xoopsSecurity']->getErrors()));
            }
            if ($tempHandler->delete($obj)) {
                $helper->redirect('admin/pedigree_temp.php', 3, _AM_PEDIGREE_FORMDELOK);
            } else {
                echo $obj->getHtmlErrors();
            }
        } else {
            xoops_confirm(['ok' => 1, 'id' => $_REQUEST['id'], 'op' => 'delete_pedigree_temp'], $_SERVER['REQUEST_URI'], sprintf(_AM_PEDIGREE_FORMSUREDEL, $obj->getVar('pedigree_temp')));
        }
        break;
}
require_once __DIR__ . '/admin_footer.php';
