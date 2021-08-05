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
 * @since           2.5.x
 * @author          XOOPS Module Dev Team (https://xoops.org)
 */

use Xmf\Request;
use XoopsModules\Pedigree;

require_once __DIR__ . '/admin_header.php';

xoops_cp_header();
//$adminObject = \Xmf\Module\Admin::getInstance();

$fieldsHandler = Pedigree\Helper::getInstance()->getHandler('Fields');

//It recovered the value of argument op in URL$
$op = Request::getString('op', 'list');
switch ($op) {
    case 'list':
    default:
        $adminObject->displayNavigation(basename(__FILE__));
        $adminObject->addItemButton(_AM_PEDIGREE_NEWPEDIGREE_CONFIG, 'pedigree_fields.php?op=new_pedigree_fields', 'add');
        $adminObject->displayButton('left');
        $criteria = new \CriteriaCompo();
        $criteria->setSort('id');
        $criteria->setOrder('ASC');
        $numrows             = $fieldsHandler->getCount();
        $pedigree_fields_arr = $fieldsHandler->getAll($criteria);

        //Table view
        if ($numrows > 0) {
            echo "<table cellspacing='1' class='outer width100'>
                <thead>
                <tr>
                    <th class='txtcenter'>" . _AM_PEDIGREE_PEDIGREE_CONFIG_ISACTIVE . "</th>
                    <th class='txtcenter'>" . _AM_PEDIGREE_PEDIGREE_CONFIG_FIELDNAME . "</th>
                    <th class='txtcenter'>" . _AM_PEDIGREE_PEDIGREE_CONFIG_FIELDTYPE . "</th>
                    <th class='txtcenter'>" . _AM_PEDIGREE_PEDIGREE_CONFIG_LOOKUPTABLE . "</th>
                    <th class='txtcenter'>" . _AM_PEDIGREE_PEDIGREE_CONFIG_DEFAULTVALUE . "</th>
                    <th class='txtcenter'>" . _AM_PEDIGREE_PEDIGREE_CONFIG_FIELDEXPLANATION . "</th>
                    <th class='txtcenter'>" . _AM_PEDIGREE_PEDIGREE_CONFIG_HASSEARCH . "</th>
                    <th class='txtcenter'>" . _AM_PEDIGREE_PEDIGREE_CONFIG_LITTER . "</th>
                    <th class='txtcenter'>" . _AM_PEDIGREE_PEDIGREE_CONFIG_GENERALLITTER . "</th>
                    <th class='txtcenter'>" . _AM_PEDIGREE_PEDIGREE_CONFIG_SEARCHNAME . "</th>
                    <th class='txtcenter'>" . _AM_PEDIGREE_PEDIGREE_CONFIG_SEARCHEXPLANATION . "</th>
                    <th class='txtcenter'>" . _AM_PEDIGREE_PEDIGREE_CONFIG_VIEWINPEDIGREE . "</th>
                    <th class='txtcenter'>" . _AM_PEDIGREE_PEDIGREE_CONFIG_VIEWINADVANCED . "</th>
                    <th class='txtcenter'>" . _AM_PEDIGREE_PEDIGREE_CONFIG_VIEWINPIE . "</th>
                    <th class='txtcenter'>" . _AM_PEDIGREE_PEDIGREE_CONFIG_VIEWINLIST . "</th>
                    <th class='txtcenter width10'>" . _AM_PEDIGREE_FORMACTION . '</th>
                </tr>
                </thead>
                <tbody>';

            $class = 'odd';

            foreach (array_keys($pedigree_fields_arr) as $i) {
                if (0 == $pedigree_fields_arr[$i]->getVar('pedigree_fields_pid')) {
                    echo "<tr class='{$class}'>";
                    $class = ('even' === $class) ? 'odd' : 'even';
                    echo "<td class='txtcenter'>" . $pedigree_fields_arr[$i]->getVar('isactive') . '</td>';
                    echo "<td class='txtcenter'>" . $pedigree_fields_arr[$i]->getVar('fieldname') . '</td>';
                    echo "<td class='txtcenter'>" . $pedigree_fields_arr[$i]->getVar('fieldtype') . '</td>';
                    echo "<td class='txtcenter'>" . $pedigree_fields_arr[$i]->getVar('lookuptable') . '</td>';
                    echo "<td class='txtcenter'>" . $pedigree_fields_arr[$i]->getVar('defaultvalue') . '</td>';
                    echo "<td class='txtleft'>" . $pedigree_fields_arr[$i]->getVar('fieldexplanation') . '</td>';
                    echo "<td class='txtcenter'>" . $pedigree_fields_arr[$i]->getVar('hassearch') . '</td>';
                    echo "<td class='txtcenter'>" . $pedigree_fields_arr[$i]->getVar('litter') . '</td>';
                    echo "<td class='txtcenter'>" . $pedigree_fields_arr[$i]->getVar('generallitter') . '</td>';
                    echo "<td class='txtleft'>" . $pedigree_fields_arr[$i]->getVar('searchname') . '</td>';
                    echo "<td class='txtleft'>" . $pedigree_fields_arr[$i]->getVar('searchexplanation') . '</td>';
                    echo "<td class='txtcenter'>" . $pedigree_fields_arr[$i]->getVar('viewinpedigree') . '</td>';
                    echo "<td class='txtcenter'>" . $pedigree_fields_arr[$i]->getVar('viewinadvanced') . '</td>';
                    echo "<td class='txtcenter'>" . $pedigree_fields_arr[$i]->getVar('viewinpie') . '</td>';
                    echo "<td class='txtcenter'>" . $pedigree_fields_arr[$i]->getVar('viewinlist') . '</td>';
                    echo "<td class='txtcenter width10'>
                        <a href='pedigree_fields.php?op=edit_pedigree_fields&id=" . $pedigree_fields_arr[$i]->getVar('id') . "'><img src='{$pathIcon16}/edit.png' alt='" . _EDIT . "' title='" . _EDIT . "'></a>
                        <a href='pedigree_fields.php?op=delete_pedigree_fields&id=" . $pedigree_fields_arr[$i]->getVar('id') . "'><img src='{$pathIcon16}/delete.png' alt='" . _DELETE . "' title='" . _DELETE . "'></a>
                        </td>";
                    echo '</tr>';
                }
            }
            echo '</tbody>
                  </table>
                  <br><br>';
        }

        break;
    case 'new_pedigree_fields':
        $adminObject->displayNavigation(basename(__FILE__));
        $adminObject->addItemButton(_AM_PEDIGREE_PEDIGREE_CONFIGLIST, 'pedigree_fields.php?op=list', 'list');
        $adminObject->displayButton('left');

        $obj = $fieldsHandler->create();
        /** @var \XoopsThemeForm $form */
        $form = $obj->getForm();
        $form->display();
        break;
    case 'save_pedigree_fields':
        if (!$GLOBALS['xoopsSecurity']->check()) {
            redirect_header('pedigree_fields.php', 3, implode(',', $GLOBALS['xoopsSecurity']->getErrors()));
        }
        $id = Request::getInt('id', 0, 'POST');
        if ($id) {
            $obj = $fieldsHandler->get($id);
        } else {
            $obj = $fieldsHandler->create();
        }
        //Form isactive
        $obj->setVar('isactive', Request::getInt('isActive', 0, 'POST'));
        //Form fieldname
        $obj->setVar('fieldname', Request::getString('fieldName', '', 'POST'));
        //Form fieldtype
        $obj->setVar('fieldtype', Request::getString('fieldType', 'textbox', 'POST'));
        //Form LookupTable
        $obj->setVar('lookuptable', Request::getString('lookupTable', '', 'POST'));
        //Form DefaultValue
        $obj->setVar('defaultvalue', Request::getString('defaultValue', '', 'POST'));
        //Form FieldExplanation
        $obj->setVar('fieldexplanation', Request::getString('fieldExplanation', '', 'POST'));
        //Form HasSearch
        $obj->setVar('hassearch', Request::getInt('hasSearch', 0, 'POST'));
        //Form Litter Types
        $litterType = Request::getString('litterType', 'generalLitter');
        if ('Litter' === $litterType) {
            $obj->setVar('litter', 1);
            $obj->setVar('generalLitter', 0);
        } else {
            $obj->setVar('litter', 0);
            $obj->setVar('generalLitter', 1);
        }
        //Form SearchName
        $obj->setVar('searchname', Request::getString('searchName', '', 'POST'));
        //Form SearchExplanation
        $obj->setVar('searchexplanation', Request::getString('searchExplanation', '', 'POST'));
        //Form viewinpedigree
        $obj->setVar('viewinpedigree', Request::getInt('viewInPedigree', 0, 'POST'));
        //Form ViewInAdvanced
        $obj->setVar('viewinadvanced', Request::getInt('viewInAdvanced', 1, 'POST'));
        //Form ViewInPie
        $obj->setVar('viewinpie', Request::getInt('viewInPie', 0, 'POST'));
        //Form ViewInList
        $obj->setVar('viewinlist', Request::getInt('viewInList', 0, 'POST'));
        //Form locked
        $obj->setVar('locked', Request::getInt('locked', 0, 'POST'));
        //Form order
        $obj->setVar('order', Request::getInt('order', 0, 'POST'));

        if ($fieldsHandler->insert($obj)) {
            redirect_header('pedigree_fields.php?op=list', 2, _AM_PEDIGREE_FORMOK);
        }

        echo $obj->getHtmlErrors();
        $form = $obj->getForm();
        $form->display();
        break;
    case 'edit_pedigree_fields':
        $adminObject->displayNavigation(basename(__FILE__));
        $adminObject->addItemButton(_AM_PEDIGREE_NEWPEDIGREE_CONFIG, 'pedigree_fields.php?op=new_pedigree_fields', 'add');
        $adminObject->addItemButton(_AM_PEDIGREE_PEDIGREE_CONFIGLIST, 'pedigree_fields.php?op=list', 'list');
        $adminObject->displayButton('left');
        $obj  = $fieldsHandler->get(Request::getInt('id', 0));
        $form = $obj->getForm();
        $form->display();
        break;
    case 'delete_pedigree_fields':
        $id  = Request::getInt('id', 0);
        $obj = $fieldsHandler->get($id);
        $ok  = Request::getInt('ok', 0, 'POST');
        if ('0' != $ok) {
            //        if (\Xmf\Request::hasVar('ok', 'REQUEST') && (1 == $_REQUEST['ok'])) {
            if (!$GLOBALS['xoopsSecurity']->check()) {
                redirect_header('pedigree_fields.php', 3, implode(',', $GLOBALS['xoopsSecurity']->getErrors()));
            }
            if ($fieldsHandler->delete($obj)) {
                redirect_header('pedigree_fields.php', 3, _AM_PEDIGREE_FORMDELOK);
            } else {
                echo $obj->getHtmlErrors();
            }
        } else {
            xoops_confirm(['ok' => 1, 'id' => $id, 'op' => 'delete_pedigree_fields'], $_SERVER['REQUEST_URI'], sprintf(_AM_PEDIGREE_FORMSUREDEL, $obj->getVar('pedigree_fields')));
        }
        break;
}
require_once __DIR__ . '/admin_footer.php';
