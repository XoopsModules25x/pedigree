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
 * @copyright       XOOPS Project (https://xoops.org)
 * @license         GPL 2.0 or later
 * @package         pedigree
 * @since           2.5.x
 * @author          XOOPS Development Team ( name@site.com ) - ( https://xoops.org )
 */

use Xmf\Request;
use XoopsModules\Pedigree;

require_once __DIR__ . '/admin_header.php';

xoops_cp_header();
//$adminObject = \Xmf\Module\Admin::getInstance();

$ownerHandler = Pedigree\Helper::getInstance()->getHandler('Owner');

//It recovered the value of argument op in URL$
$op    = Request::getString('op', 'list');
$order = Request::getString('order', 'desc');
$sort  = Request::getString('sort', '');
switch ($op) {
    case 'list':
    default:
        $adminObject->displayNavigation(basename(__FILE__));
        $adminObject->addItemButton(_AM_PEDIGREE_NEWOWNER, 'owner.php?op=new_owner', 'add');

        $start                = Request::getInt('start', 0);
        $ownerPaginationLimit = $helper->getConfig('userpager');

        $adminObject->displayButton('left');
        $criteria = new \CriteriaCompo();
        $criteria->setSort('id');
        $criteria->setOrder('ASC');
        $criteria->setLimit($ownerPaginationLimit);
        $criteria->setStart($start);
        $numrows   = $ownerHandler->getCount();
        $owner_arr = $ownerHandler->getAll($criteria);

        $ownerTempRows  = $ownerHandler->getCount();
        $ownerTempArray = $ownerHandler->getAll($criteria);

        // Display Page Navigation
        if ($ownerTempRows > $ownerPaginationLimit) {
            xoops_load('XoopsPageNav');

            $pagenav = new \XoopsPageNav($ownerTempRows, $ownerPaginationLimit, $start, 'start', 'op=list' . '&sort=' . $sort . '&order=' . $order . '');
            $GLOBALS['xoopsTpl']->assign('pagenav', null === $pagenav ? $pagenav->renderNav() : '');
        }

        $GLOBALS['xoopsTpl']->assign('ownerRows', $ownerTempRows);
        $ownerArray = [];

        //    $fields = explode('|', id:int:11::NOT NULL::primary:ID|firstname:varchar:30::NOT NULL:::First Name|lastname:varchar:30::NOT NULL:::Last Name|postcode:varchar:7::NOT NULL:::Postcode|city:varchar:50::NOT NULL:::City|streetname:varchar:40::NOT NULL:::Street name|housenumber:varchar:6::NOT NULL:::House #|phonenumber:varchar:14::NOT NULL:::Phone|emailadres:varchar:40::NOT NULL:::Email|website:varchar:60::NOT NULL:::Website URL|user:varchar:20::NOT NULL:::User);
        //    $fieldsCount    = count($fields);

        $criteria = new \CriteriaCompo();

        //$criteria->setOrder('DESC');
        $criteria->setSort($sort);
        $criteria->setOrder($order);
        $criteria->setLimit($ownerPaginationLimit);
        $criteria->setStart($start);

        $ownerCount     = $ownerHandler->getCount($criteria);
        $ownerTempArray = $ownerHandler->getAll($criteria);

        //Table view
        if ($numrows > 0) {
            echo "<table width='100%' cellspacing='1' class='outer'>
                <tr>
                    <th align=\"left\">" . _AM_PEDIGREE_OWNER_FIRSTNAME . '</th>
                        <th class="left">' . _AM_PEDIGREE_OWNER_LASTNAME . '</th>
                        <th class="center">' . _AM_PEDIGREE_OWNER_POSTCODE . '</th>
                        <th class="left">' . _AM_PEDIGREE_OWNER_CITY . '</th>
                        <th class="left">' . _AM_PEDIGREE_OWNER_STREETNAME . '</th>
                        <th class="left">' . _AM_PEDIGREE_OWNER_HOUSENUMBER . '</th>
                        <th class="left">' . _AM_PEDIGREE_OWNER_PHONENUMBER . '</th>
                        <th class="left">' . _AM_PEDIGREE_OWNER_EMAILADRES . '</th>
                        <th class="left">' . _AM_PEDIGREE_OWNER_WEBSITE . '</th>
                        <th class="center">' . _AM_PEDIGREE_OWNER_USER . "</th>

                    <th align='center' width='10%'>" . _AM_PEDIGREE_FORMACTION . '</th>
                </tr>';

            $class = 'odd';

            //mb            foreach (array_keys($owner_arr) as $i) {
            //            if (0 == $owner_arr[$i]->getVar('owner_pid')) {

            if ($ownerCount > 0) {
                foreach (array_keys($ownerTempArray) as $i) {
                    if (0 == $ownerTempArray[$i]->getVar('owner_pid')) {
                        echo "<tr class='" . $class . "'>";
                        $class = ('even' === $class) ? 'odd' : 'even';
                        echo '<td class="left">' . $ownerTempArray[$i]->getVar('firstname') . '</td>';
                        echo '<td class="left">' . $ownerTempArray[$i]->getVar('lastname') . '</td>';
                        echo '<td class="left">' . $ownerTempArray[$i]->getVar('postcode') . '</td>';
                        echo '<td class="left">' . $ownerTempArray[$i]->getVar('city') . '</td>';
                        echo '<td class="left">' . $ownerTempArray[$i]->getVar('streetname') . '</td>';
                        echo '<td class="left">' . $ownerTempArray[$i]->getVar('housenumber') . '</td>';
                        echo '<td class="left">' . $ownerTempArray[$i]->getVar('phonenumber') . '</td>';
                        echo '<td class="left">' . $ownerTempArray[$i]->getVar('emailadres') . '</td>';
                        echo '<td class="left">' . $ownerTempArray[$i]->getVar('website') . '</td>';
                        echo '<td class="left">' . $ownerTempArray[$i]->getVar('user') . '</td>';

                        echo "<td class='center' width='10%'>
                        <a href='owner.php?op=edit_owner&id=" . $ownerTempArray[$i]->getVar('id') . "'><img src=" . $pathIcon16 . "/edit.png alt='" . _EDIT . "' title='" . _EDIT . "'></a>
                        <a href='owner.php?op=delete_owner&id=" . $ownerTempArray[$i]->getVar('id') . "'><img src=" . $pathIcon16 . "/delete.png alt='" . _DELETE . "' title='" . _DELETE . "'></a>
                        </td>";
                        echo '</tr>';
                    }
                }
                echo '</table><br><br>';
            }
        }

        //    $GLOBALS['xoopsTpl']->append_by_ref('ownerArrays', $ownerArray);
        //    unset($ownerArray);
        //}
        unset($ownerTempArray);
        // Display Navigation
        if ($ownerCount > $ownerPaginationLimit) {
            xoops_load('XoopsPageNav');
            $pagenav = new \XoopsPageNav($ownerCount, $ownerPaginationLimit, $start, 'start', 'op=list' . '&sort=' . $sort . '&order=' . $order . '');
            $GLOBALS['xoopsTpl']->assign('pagenav', $pagenav->renderNav(4));
        }

        echo $GLOBALS['xoopsTpl']->fetch(XOOPS_ROOT_PATH . '/modules/' . $GLOBALS['xoopsModule']->getVar('dirname') . '/templates/admin/pedigree_admin_owner.tpl');

        break;
    case 'new_owner':
        $adminObject->displayNavigation(basename(__FILE__));
        $adminObject->addItemButton(_AM_PEDIGREE_OWNERLIST, 'owner.php?op=list', 'list');
        $adminObject->displayButton('left');

        $obj = $ownerHandler->create();
        /** @var \XoopsThemeForm $form */
        $form = $obj->getForm();
        $form->display();
        break;
    case 'save_owner':
        if (!$GLOBALS['xoopsSecurity']->check()) {
            redirect_header('owner.php', 3, implode(',', $GLOBALS['xoopsSecurity']->getErrors()));
        }
        if (\Xmf\Request::hasVar('id', 'REQUEST')) {
            $obj = $ownerHandler->get($_REQUEST['id']);
        } else {
            $obj = $ownerHandler->create();
        }

        //Form firstname
        $obj->setVar('firstname', $_REQUEST['firstname']);
        //Form lastname
        $obj->setVar('lastname', $_REQUEST['lastname']);
        //Form postcode
        $obj->setVar('postcode', $_REQUEST['postcode']);
        //Form city
        $obj->setVar('city', $_REQUEST['city']);
        //Form streetname
        $obj->setVar('streetname', $_REQUEST['streetname']);
        //Form housenumber
        $obj->setVar('housenumber', $_REQUEST['housenumber']);
        //Form phonenumber
        $obj->setVar('phonenumber', $_REQUEST['phonenumber']);
        //Form emailadres
        $obj->setVar('emailadres', $_REQUEST['emailadres']);
        //Form website
        $obj->setVar('website', $_REQUEST['website']);
        //Form user
        $obj->setVar('user', $_REQUEST['user']);

        if ($ownerHandler->insert($obj)) {
            redirect_header('owner.php?op=list', 2, _AM_PEDIGREE_FORMOK);
        }

        echo $obj->getHtmlErrors();
        /** @var \XoopsThemeForm $form */
        $form = $obj->getForm();
        $form->display();
        break;
    case 'edit_owner':
        $adminObject->displayNavigation(basename(__FILE__));
        $adminObject->addItemButton(_AM_PEDIGREE_NEWOWNER, 'owner.php?op=new_owner', 'add');
        $adminObject->addItemButton(_AM_PEDIGREE_OWNERLIST, 'owner.php?op=list', 'list');
        $adminObject->displayButton('left');
        $obj  = $ownerHandler->get($_REQUEST['id']);
        $form = $obj->getForm();
        $form->display();
        break;
    case 'delete_owner':
        $obj = $ownerHandler->get($_REQUEST['id']);
        if (\Xmf\Request::hasVar('ok', 'REQUEST') && 1 == $_REQUEST['ok']) {
            if (!$GLOBALS['xoopsSecurity']->check()) {
                redirect_header('owner.php', 3, implode(',', $GLOBALS['xoopsSecurity']->getErrors()));
            }
            if ($ownerHandler->delete($obj)) {
                redirect_header('owner.php', 3, _AM_PEDIGREE_FORMDELOK);
            } else {
                echo $obj->getHtmlErrors();
            }
        } else {
            xoops_confirm(['ok' => 1, 'id' => $_REQUEST['id'], 'op' => 'delete_owner'], $_SERVER['REQUEST_URI'], sprintf(_AM_PEDIGREE_FORMSUREDEL, $obj->getVar('owner')));
        }
        break;
}
require_once __DIR__ . '/admin_footer.php';
