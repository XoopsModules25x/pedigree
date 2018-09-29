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
 * @author          XOOPS Module Dev Team (https://xoops.org)
 */

use Xmf\Request;
use XoopsModules\Pedigree;

require_once __DIR__ . '/admin_header.php';

xoops_cp_header();
//$adminObject = \Xmf\Module\Admin::getInstance();

$treeHandler = Pedigree\Helper::getInstance()->getHandler('Tree');

//It recovered the value of argument op in URL$
$op = Request::getString('op', 'list');
switch ($op) {
    case 'list':
    default:
        $adminObject->displayNavigation(basename(__FILE__));
        $adminObject->addItemButton(_AM_PEDIGREE_NEWPEDIGREE, 'pedigree.php?op=new_pedigree', 'add');
        $adminObject->displayButton('left');
        $criteria = new \CriteriaCompo();
        $criteria->setSort('id');
        $criteria->setOrder('ASC');
        $numrows      = $treeHandler->getCount();
        $pedigree_arr = $treeHandler->getAll($criteria);

        //Table view
        if ($numrows > 0) {
            echo "<table width='100%' cellspacing='1' class='outer'>
                <tr>
                    <th align=\"center\">" . _AM_PEDIGREE_PEDIGREE_PNAME . '</th>
                        <th align="center">' . _AM_PEDIGREE_PEDIGREE_ID_OWNER . '</th>
                        <th align="center">' . _AM_PEDIGREE_PEDIGREE_ID_BREEDER . '</th>
                        <th align="center">' . _AM_PEDIGREE_PEDIGREE_USER . '</th>
                        <th align="center">' . _AM_PEDIGREE_PEDIGREE_ROFT . '</th>
                        <th align="center">' . _AM_PEDIGREE_PEDIGREE_MOTHER . '</th>
                        <th align="center">' . _AM_PEDIGREE_PEDIGREE_FATHER . '</th>
                        <th align="center">' . _AM_PEDIGREE_PEDIGREE_FOTO . '</th>
                        <th align="center">' . _AM_PEDIGREE_PEDIGREE_COI . "</th>

                    <th align='center' width='10%'>" . _AM_PEDIGREE_FORMACTION . '</th>
                </tr>';

            $class = 'odd';

            foreach (array_keys($pedigree_arr) as $i) {
                if (0 == $pedigree_arr[$i]->getVar('pedigree_pid')) {
                    echo "<tr class='" . $class . "'>";
                    $class = ('even' === $class) ? 'odd' : 'even';
                    echo '<td align="center">' . $pedigree_arr[$i]->getVar('pname') . '</td>';
                    echo '<td align="center">' . $pedigree_arr[$i]->getVar('id_owner') . '</td>';
                    echo '<td align="center">' . $pedigree_arr[$i]->getVar('id_breeder') . '</td>';
                    echo '<td align="center">' . $pedigree_arr[$i]->getVar('user') . '</td>';
                    echo '<td align="center">' . $pedigree_arr[$i]->getVar('roft') . '</td>';
                    echo '<td align="center">' . $pedigree_arr[$i]->getVar('mother') . '</td>';
                    echo '<td align="center">' . $pedigree_arr[$i]->getVar('father') . '</td>';
                    echo '<td align="center">' . $pedigree_arr[$i]->getVar('foto') . '</td>';
                    echo '<td align="center">' . $pedigree_arr[$i]->getVar('coi') . '</td>';

                    echo "<td align='center' width='10%'>
                        <a href='pedigree.php?op=edit_pedigree&id=" . $pedigree_arr[$i]->getVar('id') . "'><img src=" . $pathIcon16 . "/edit.png alt='" . _EDIT . "' title='" . _EDIT . "'></a>
                        <a href=" . PEDIGREE_URL . '/delete.php?id=' . $pedigree_arr[$i]->getVar('id') . '><img src=' . $pathIcon16 . "/delete.png alt='" . _DELETE . "' title='" . _DELETE . "'></a>
                        </td>";
                    echo '</tr>';
                }
            }
            echo '</table><br><br>';
        }

        break;

    case 'new_pedigree':
        $adminObject->displayNavigation(basename(__FILE__));
        $adminObject->addItemButton(_AM_PEDIGREE_PEDIGREELIST, 'pedigree.php?op=list', 'list');
        $adminObject->displayButton('left');

        $obj  = $treeHandler->create();
        $form = $obj->getForm();
        $form->display();
        break;

    case 'save_pedigree':
        if (!$GLOBALS['xoopsSecurity']->check()) {
            redirect_header('pedigree.php', 3, implode(',', $GLOBALS['xoopsSecurity']->getErrors()));
        }
        if (\Xmf\Request::hasVar('id', 'REQUEST')) {
            $obj = $treeHandler->get($_REQUEST['id']);
        } else {
            $obj = $treeHandler->create();
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

        if ($treeHandler->insert($obj)) {
            redirect_header('pedigree.php?op=list', 2, _AM_PEDIGREE_FORMOK);
        }

        echo $obj->getHtmlErrors();
        $form = $obj->getForm();
        $form->display();
        break;

    case 'edit_pedigree':
        $adminObject->displayNavigation(basename(__FILE__));
        $adminObject->addItemButton(_AM_PEDIGREE_NEWPEDIGREE, 'pedigree.php?op=new_pedigree', 'add');
        $adminObject->addItemButton(_AM_PEDIGREE_PEDIGREELIST, 'pedigree.php?op=list', 'list');
        $adminObject->displayButton('left');
        $obj  = $treeHandler->get($_REQUEST['id']);
        $form = $obj->getForm();
        $form->display();
        break;

    case 'delete_pedigree':
        $obj = $treeHandler->get($_REQUEST['id']);
        if (\Xmf\Request::hasVar('ok', 'REQUEST') && 1 == $_REQUEST['ok']) {
            if (!$GLOBALS['xoopsSecurity']->check()) {
                redirect_header('pedigree.php', 3, implode(',', $GLOBALS['xoopsSecurity']->getErrors()));
            }
            if ($treeHandler->delete($obj)) {
                redirect_header('pedigree.php', 3, _AM_PEDIGREE_FORMDELOK);
            } else {
                echo $obj->getHtmlErrors();
            }
        } else {
            xoops_confirm(['ok' => 1, 'id' => $_REQUEST['id'], 'op' => 'delete_pedigree'], $_SERVER['REQUEST_URI'], sprintf(_AM_PEDIGREE_FORMSUREDEL, $obj->getVar('pedigree')));
        }
        break;
}
require_once __DIR__ . '/admin_footer.php';
