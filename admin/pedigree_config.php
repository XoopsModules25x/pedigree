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

include_once __DIR__ . '/admin_header.php';

xoops_cp_header();
$adminMenu = new ModuleAdmin();

//It recovered the value of argument op in URL$
$op = XoopsRequest::getCmd('op', 'list');
switch ($op) {
    case 'list':
    default:
        echo $adminMenu->addNavigation(basename(__FILE__));
        $adminMenu->addItemButton(_AM_PEDIGREE_NEWPEDIGREE_CONFIG, 'pedigree_config.php?op=new_pedigree_config', 'add');
        echo $adminMenu->renderButton('left');
        $criteria = new CriteriaCompo();
        $criteria->setSort('Id');
        $criteria->setOrder('ASC');
        $numrows             = $pedigreeFieldsHandler->getCount();
        $pedigree_config_arr = $pedigreeFieldsHandler->getall($criteria);

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

            foreach (array_keys($pedigree_config_arr) as $i) {
                if (0 == $pedigree_config_arr[$i]->getVar('pedigree_config_pid')) {
                    echo "<tr class='{$class}'>";
                    $class = ($class === 'even') ? 'odd' : 'even';
                    echo "<td class='txtcenter'>" . $pedigree_config_arr[$i]->getVar('isactive') . '</td>';
                    echo "<td class='txtcenter'>" . $pedigree_config_arr[$i]->getVar('fieldname') . '</td>';
                    echo "<td class='txtcenter'>" . $pedigree_config_arr[$i]->getVar('fieldtype') . '</td>';
                    echo "<td class='txtcenter'>" . $pedigree_config_arr[$i]->getVar('lookuptable') . '</td>';
                    echo "<td class='txtcenter'>" . $pedigree_config_arr[$i]->getVar('defaultvalue') . '</td>';
                    echo "<td class='txtcenter'>" . $pedigree_config_arr[$i]->getVar('fieldexplanation') . '</td>';
                    echo "<td class='txtcenter'>" . $pedigree_config_arr[$i]->getVar('hassearch') . '</td>';
                    echo "<td class='txtcenter'>" . $pedigree_config_arr[$i]->getVar('litter') . '</td>';
                    echo "<td class='txtcenter'>" . $pedigree_config_arr[$i]->getVar('generallitter') . '</td>';
                    echo "<td class='txtcenter'>" . $pedigree_config_arr[$i]->getVar('searchname') . '</td>';
                    echo "<td class='txtcenter'>" . $pedigree_config_arr[$i]->getVar('searchexplanation') . '</td>';
                    echo "<td class='txtcenter'>" . $pedigree_config_arr[$i]->getVar('viewinpedigree') . '</td>';
                    echo "<td class='txtcenter'>" . $pedigree_config_arr[$i]->getVar('viewinadvanced') . '</td>';
                    echo "<td class='txtcenter'>" . $pedigree_config_arr[$i]->getVar('viewinpie') . '</td>';
                    echo "<td class='txtcenter'>" . $pedigree_config_arr[$i]->getVar('viewinlist') . '</td>';
                    echo "<td class='txtcenter width10'>
                        <a href='pedigree_config.php?op=edit_pedigree_config&ID=" . $pedigree_config_arr[$i]->getVar('Id') . "'><img src='{$pathIcon16}/edit.png' alt='" . _EDIT . "' title='" . _EDIT . "'></a>
                        <a href='pedigree_config.php?op=delete_pedigree_config&ID=" . $pedigree_config_arr[$i]->getVar('Id') . "'><img src='{$pathIcon16}/delete.png' alt='" . _DELETE . "' title='" . _DELETE . "'></a>
                        </td>";
                    echo '</tr>';
                }
            }
            echo '</tbody>
                  </table>
                  <br /><br />';
        }

        break;

    case 'new_pedigree_config':
        echo $adminMenu->addNavigation(basename(__FILE__));
        $adminMenu->addItemButton(_AM_PEDIGREE_PEDIGREE_CONFIGLIST, 'pedigree_config.php?op=list', 'list');
        echo $adminMenu->renderButton();

        $obj  = $pedigreeFieldsHandler->create();
        $form = $obj->getForm();
        $form->display();
        break;

    case 'save_pedigree_config':
        if (!$GLOBALS['xoopsSecurity']->check()) {
            redirect_header('pedigree_config.php', 3, implode(',', $GLOBALS['xoopsSecurity']->getErrors()));
        }
        $id = XoopsRequest::getInt('Id', 0, 'POST');
        if ($id) {
            $obj = $pedigreeFieldsHandler->get($id);
        } else {
            $obj = $pedigreeFieldsHandler->create();
        }
        //Form isActive
        $obj->setVar('isactive', XoopsRequest::getInt('isActive', 0, 'POST'));
        //Form FieldName
        $obj->setVar('fieldname', XoopsRequest::getString('FieldName', '', 'POST'));
        //Form FieldType
        $obj->setVar('fieldtype', XoopsRequest::getString('FieldType', 'textbox', 'POST'));
        //Form LookupTable
        $obj->setVar('lookuptable', XoopsRequest::getString('LookupTable', '', 'POST'));
        //Form DefaultValue
        $obj->setVar('defaultvalue', XoopsRequest::getString('DefaultValue', '', 'POST'));
        //Form FieldExplanation
        $obj->setVar('fieldexplanation', XoopsRequest::getString('FieldExplanation', '', 'POST'));
        //Form HasSearch
        $obj->setVar('hassearch', XoopsRequest::getInt('HasSearch', 0, 'POST'));
        //Form Litter Types
        $litterType = XoopsRequest::getString('litterType', 'Generallitter');
        if ('Litter' === $litterType) {
            $obj->setVar('litter', 1);
            $obj->setVar('generallitter', 0);
        } else {
            $obj->setVar('litter', 0);
            $obj->setVar('generallitter', 1);
        }
        //Form SearchName
        $obj->setVar('searchName', XoopsRequest::getString('searchName', '', 'POST'));
        //Form SearchExplanation
        $obj->setVar('searchExplanation', XoopsRequest::getString('searchExplanation', '', 'POST'));
        //Form ViewInPedigree
        $obj->setVar('viewInPedigree', XoopsRequest::getInt('viewInPedigree', 0, 'POST'));
        //Form ViewInAdvanced
        $obj->setVar('viewInAdvanced', XoopsRequest::getInt('viewInAdvanced', 1, 'POST'));
        //Form ViewInPie
        $obj->setVar('viewInPie', XoopsRequest::getInt('viewInPie', 0, 'POST'));
        //Form ViewInList
        $obj->setVar('viewInList', XoopsRequest::getInt('viewInList', 0, 'POST'));
        //Form locked
        $obj->setVar('locked', XoopsRequest::getInt('locked', 0, 'POST'));
        //Form order
        $obj->setVar('order', XoopsRequest::getInt('order', 0, 'POST'));

        if ($pedigreeFieldsHandler->insert($obj)) {
            redirect_header('pedigree_config.php?op=list', 2, _AM_PEDIGREE_FORMOK);
        }

        echo $obj->getHtmlErrors();
        $form = $obj->getForm();
        $form->display();
        break;

    case 'edit_pedigree_config':
        echo $adminMenu->addNavigation(basename(__FILE__));
        $adminMenu->addItemButton(_AM_PEDIGREE_NEWPEDIGREE_CONFIG, 'pedigree_config.php?op=new_pedigree_config', 'add');
        $adminMenu->addItemButton(_AM_PEDIGREE_PEDIGREE_CONFIGLIST, 'pedigree_config.php?op=list', 'list');
        echo $adminMenu->renderButton();
        $obj  = $pedigreeFieldsHandler->get(XoopsRequest::getInt('Id', 0));
        $form = $obj->getForm();
        $form->display();
        break;

    case 'delete_pedigree_config':
        $obj = $pedigreeFieldsHandler->get($_REQUEST['Id']);
        if (isset($_REQUEST['ok']) && (1 == $_REQUEST['ok'])) {
            if (!$GLOBALS['xoopsSecurity']->check()) {
                redirect_header('pedigree_config.php', 3, implode(',', $GLOBALS['xoopsSecurity']->getErrors()));
            }
            if ($pedigreeFieldsHandler->delete($obj)) {
                redirect_header('pedigree_config.php', 3, _AM_PEDIGREE_FORMDELOK);
            } else {
                echo $obj->getHtmlErrors();
            }
        } else {
            xoops_confirm(array('ok' => 1, 'Id' => $_REQUEST['Id'], 'op' => 'delete_pedigree_config'), $_SERVER['REQUEST_URI'], sprintf(_AM_PEDIGREE_FORMSUREDEL, $obj->getVar('pedigree_config')));
        }
        break;
}
include_once __DIR__ . '/admin_footer.php';
