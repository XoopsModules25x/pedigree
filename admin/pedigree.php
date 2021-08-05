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
 * @package         \XoopsModules\Pedigree
 * @copyright       {@link http://sourceforge.net/projects/xoops/ The XOOPS Project}
 * @license         https://www.gnu.org/licenses/gpl-2.0.html GNU Public License
 * @author          XOOPS Module Dev Team (https://xoops.org)
 */
use Xmf\Request;
use XoopsModules\Pedigree;

require_once __DIR__ . '/admin_header.php';

xoops_cp_header();
/**
 * @var Xmf\Module\Admin $adminObject
 * @var XoopsModules\Pedigree\Helper $helper
 * @var XoopsModules\Pedigree\TreeHandler $treeHandler
 */
$helper = Pedigree\Helper::getInstance();
$treeHandler = $helper->getHandler('Tree');

//It recovered the value of argument op in URL$
$op = Request::getCmd('op', 'list');
switch ($op) {
    case 'list':
    default:
        $adminObject->displayNavigation(basename(__FILE__));
        $adminObject->addItemButton(_AM_PEDIGREE_NEWPEDIGREE, $helper->url('admin/pedigree.php?op=new_pedigree'), 'add');
        $adminObject->displayButton('left');
        $criteria = new \CriteriaCompo();
        $criteria->setSort('id');
        $criteria->setOrder('ASC');
        $numrows = $treeHandler->getCount();
        $pedigree_arr = $treeHandler->getAll($criteria);

        //Table view
        if ($numrows > 0) {
            echo "<table class=\"width100 outer\" cellspacing=\"1\">\n"
               . "  <tr>\n"
                 . '    <th class="center">' . _AM_PEDIGREE_PEDIGREE_NAAM . "</th>\n"
                 . '    <th class="center">' . _AM_PEDIGREE_PEDIGREE_ID_OWNER . "</th>\n"
                 . '    <th class="center">' . _AM_PEDIGREE_PEDIGREE_ID_BREEDER . "</th>\n"
                 . '    <th class="center">' . _AM_PEDIGREE_PEDIGREE_USER . "</th>\n"
                 . '    <th class="center">' . _AM_PEDIGREE_PEDIGREE_ROFT . "</th>\n"
                 . '    <th class="center">' . _AM_PEDIGREE_PEDIGREE_MOTHER . "</th>\n"
                 . '    <th class="center">' . _AM_PEDIGREE_PEDIGREE_FATHER . "</th>\n"
                 . '    <th class="center">' . _AM_PEDIGREE_PEDIGREE_FOTO . "</th>\n"
                 . '    <th class="center">' . _AM_PEDIGREE_PEDIGREE_COI . "</th>\n"
                 . '    <th class="center width10">' . _AM_PEDIGREE_FORMACTION . "</th>\n"
               . "  </tr>\n";

            $class = 'even';

            /** @var XoopsModules\Pedigree\Tree $treeObj */
            foreach ($pedigree_arr as $i => $treeObj) {
                if (0 == $treeObj->getVar('pedigree_pid')) {
                    $class = ('even' === $class) ? 'odd' : 'even';
                    echo "  <tr class=\"{$class}\">\n"
                         . '    <td class="center">' . $treeObj->getVar('naam') . "</td>\n"
                         . '    <td class="center">' . $treeObj->getVar('id_owner') . "</td>\n"
                         . '    <td class="center">' . $treeObj->getVar('id_breeder') . "</td>\n"
                         . '    <td class="center">' . $treeObj->getVar('user') . "</td>\n"
                         . '    <td class="center">' . $treeObj->getVar('roft') . "</td>\n"
                         . '    <td class="center">' . $treeObj->getVar('mother') . "</td>\n"
                         . '    <td class="center">' . $treeObj->getVar('father') . "</td>\n"
                         . '    <td class="center">' . $treeObj->getVar('foto') . "</td>\n"
                         . '    <td class="center">' . $treeObj->getVar('coi') . "</td>\n"
                       . "    <td class=\"center width10\">\n"
                         . '      <a href="' . $_SERVER['SCRIPT_NAME'] . '?op=edit_pedigree&id=' . $treeObj->getVar('id') . "\"><img src=\"{$pathIcon16}/edit.png\" alt=\"" . _EDIT . '" title="' . _EDIT . "\"></a>\n"
                         . '      <a href=' . $helper->url('delete.php?id=' . $treeObj->getVar('id')) . "><img src=\"{$pathIcon16}/delete.png\" alt=\"" . _DELETE . '" title="' . _DELETE . "\"></a>\n"
                       . "    </td>\n"
                       . "  </tr>\n";
                }
            }
            echo "</table><br><br>\n";
        } //@todo should add 'else' here to display "nothing here" message

        break;
    case 'new_pedigree':
        $adminObject->displayNavigation(basename(__FILE__));
        $adminObject->addItemButton(_AM_PEDIGREE_PEDIGREELIST, $helper->url('pedigree.php?op=list'), 'list');
        $adminObject->displayButton('left');

        /**
         * @var XoopsModules\Pedigree\Tree $treeObj
         * @var \XoopsThemeForm $form
         */
        $treeObj = $treeHandler->create();
        $form = $treeObj->getForm();
        $form->display();
        break;
    case 'save_pedigree':
        /** @var \XoopsSecurity $GLOBALS['xoopsSecurity'] */
        if (!$GLOBALS['xoopsSecurity']->check()) {
            $helper->redirect('admin/pedigree.php', 3, implode(',', $GLOBALS['xoopsSecurity']->getErrors()));
        }

        /** @var XoopsModules\Pedigree\Tree $treeObj */
        $id = Request::getInt('id', null, 'post');
        $treeObj = $treeHandler->get($id);

        $varArray = [
                'naam' => Request::getString('naam', '', 'post'),
            'id_owner' => Request::getInt('id_owner', 0, 'post'),
          'id_breeder' => Request::getInt('id_breeder', 0, 'post'),
                'user' => Request::getString('user', '', 'post'),
                'roft' => Request::getInt('roft', 0, 'post'),
              'mother' => Request::getInt('mother', 0, 'post'),
              'father' => Request::getInt('father', 0, 'post'),
                'foto' => Request::getString('foto', '', 'post'),
                 'coi' => Request::getString('coi', '', 'post'),
        ];

        $treeObj->setVars($varArray);
        if ($treeHandler->insert($treeObj)) {
            $helper->redirect('admin/pedigree.php?op=list', 2, _AM_PEDIGREE_FORMOK);
        }

        echo $treeObj->getHtmlErrors();
        /** @var \XoopsThemeForm $form */
        $form = $treeObj->getForm();
        $form->display();
        break;
    case 'edit_pedigree':
        $adminObject->displayNavigation(basename(__FILE__));
        $adminObject->addItemButton(_AM_PEDIGREE_NEWPEDIGREE, $helper->url('admin/pedigree.php?op=new_pedigree'), 'add');
        $adminObject->addItemButton(_AM_PEDIGREE_PEDIGREELIST, $helper->url('admin/pedigree.php?op=list'), 'list');
        $adminObject->displayButton('left');

        /**
         * @var XoopsModules\Pedigree\Tree $treeObj
         * @var XoopsThemeForm $form
         */
        $id = Request::getInt('id', 0);
        $treeObj = $treeHandler->get($id);
        $form = $treeObj->getForm();
        $form->display();
        break;
    case 'delete_pedigree':
        /** @var XoopsModules\Pedigree\Tree $treeObj */
        $id = Request::getInt('id', 0);
        $treeObj = $treeHandler->get($id);
        if (isset($_REQUEST['ok']) && 1 == $_REQUEST['ok']) {
            if (!$GLOBALS['xoopsSecurity']->check()) {
                $helper->redirect('admin/pedigree.php', 3, implode(',', $GLOBALS['xoopsSecurity']->getErrors()));
            }
            if ($treeHandler->delete($obj)) {
                $helper->redirect('admin/pedigree.php', 3, _AM_PEDIGREE_FORMDELOK);
            } else {
                echo $treeObj->getHtmlErrors();
            }
        } else {
            xoops_confirm(['ok' => 1, 'id' => $id, 'op' => 'delete_pedigree'], $_SERVER['REQUEST_URI'], sprintf(_AM_PEDIGREE_FORMSUREDEL, $treeObj->getVar('pedigree')));
        }
        break;
}
require_once __DIR__ . '/admin_footer.php';
