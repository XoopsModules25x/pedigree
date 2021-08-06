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
 * @category        Module
 * @package         pedigree
 * @author          XOOPS Development Team <https://xoops.org>
 * @copyright       {@link https://xoops.org/ XOOPS Project}
 * @license         GPL 2.0 or later
 * @link            https://xoops.org/
 * @since           1.0.0
 */

use Xmf\Module\Helper\Permission;
use Xmf\Request;
use XoopsModules\Pedigree\{
    Helper
};
/** @var Helper $helper */

require_once __DIR__ . '/admin_header.php';
xoops_cp_header();
//It recovered the value of argument op in URL$
$op    = Request::getString('op', 'list');
$order = Request::getString('order', 'desc');
$sort  = Request::getString('sort', '');

$adminObject->displayNavigation(basename(__FILE__));
/** @var \Xmf\Module\Helper\Permission $permHelper */
$permHelper = new Permission();
$registryHandler = $helper->getHandler('Registry');
$uploadDir  = XOOPS_UPLOAD_PATH . '/pedigree/images/';
$uploadUrl  = XOOPS_UPLOAD_URL . '/pedigree/images/';

switch ($op) {
    case 'new':
        $adminObject->addItemButton(AM_PEDIGREE_REGISTRY_LIST, 'registry.php', 'list');
        $adminObject->displayButton('left');

        $registryObject = $registryHandler->create();
        $form           = $registryObject->getForm();
        $form->display();
        break;
    case 'save':
        if (!$GLOBALS['xoopsSecurity']->check()) {
            redirect_header('registry.php', 3, implode(',', $GLOBALS['xoopsSecurity']->getErrors()));
        }
        if (0 != Request::getInt('id', 0)) {
            $registryObject = $registryHandler->get(Request::getInt('id', 0));
        } else {
            $registryObject = $registryHandler->create();
        }
        // Form save fields
        $registryObject->setVar('pname', Request::getVar('pname', ''));
        $registryObject->setVar('id_owner', Request::getVar('id_owner', ''));
        $registryObject->setVar('id_breeder', Request::getVar('id_breeder', ''));
        $registryObject->setVar('user', Request::getVar('user', ''));
        $registryObject->setVar('roft', Request::getVar('roft', ''));
        $registryObject->setVar('mother', Request::getVar('mother', ''));
        $registryObject->setVar('father', Request::getVar('father', ''));

        require_once XOOPS_ROOT_PATH . '/class/uploader.php';
        $uploadDir = XOOPS_UPLOAD_PATH . '/pedigree/images/';
        $uploader  = new \XoopsMediaUploader($uploadDir, xoops_getModuleOption('mimetypes', 'pedigree'), xoops_getModuleOption('maxsize', 'pedigree'), null, null);
        if ($uploader->fetchMedia(Request::getArray('xoops_upload_file', '', 'POST')[0])) {
            //$extension = preg_replace( '/^.+\.([^.]+)$/sU' , '' , $_FILES['attachedfile']['name']);
            //$imgName = str_replace(' ', '', $_POST['']).'.'.$extension;

            $uploader->setPrefix('foto_');
            $uploader->fetchMedia(Request::getArray('xoops_upload_file', '', 'POST')[0]);
            if (!$uploader->upload()) {
                $errors = $uploader->getErrors();
                redirect_header('<script>javascript:history.go(-1)</script>', 3, $errors);
            } else {
                $registryObject->setVar('foto', $uploader->getSavedFileName());
            }
        } else {
            $registryObject->setVar('foto', Request::getVar('foto', ''));
        }

        $registryObject->setVar('coi', Request::getVar('coi', ''));
        if ($registryHandler->insert($registryObject)) {
            redirect_header('registry.php?op=list', 2, AM_PEDIGREE_FORMOK);
        }

        echo $registryObject->getHtmlErrors();
        $form = $registryObject->getForm();
        $form->display();
        break;
    case 'edit':
        $adminObject->addItemButton(AM_PEDIGREE_ADD_REGISTRY, 'registry.php?op=new', 'add');
        $adminObject->addItemButton(AM_PEDIGREE_REGISTRY_LIST, 'registry.php', 'list');
        $adminObject->displayButton('left');
        $registryObject = $registryHandler->get(Request::getString('id', ''));
        $form           = $registryObject->getForm();
        $form->display();
        break;
    case 'delete':
        $registryObject = $registryHandler->get(Request::getString('id', ''));
        if (1 == Request::getInt('ok', 0)) {
            if (!$GLOBALS['xoopsSecurity']->check()) {
                redirect_header('registry.php', 3, implode(', ', $GLOBALS['xoopsSecurity']->getErrors()));
            }
            if ($registryHandler->delete($registryObject)) {
                redirect_header('registry.php', 3, AM_PEDIGREE_FORMDELOK);
            } else {
                echo $registryObject->getHtmlErrors();
            }
        } else {
            xoops_confirm(['ok' => 1, 'id' => Request::getString('id', ''), 'op' => 'delete'], Request::getUrl('REQUEST_URI', '', 'SERVER'), sprintf(AM_PEDIGREE_FORMSUREDEL, $registryObject->getVar('pname')));
        }
        break;
    case 'clone':

        $id_field = Request::getString('id', '');

        if ($utility::cloneRecord('pedigree_registry', 'id', $id_field)) {
            redirect_header('registry.php', 3, AM_PEDIGREE_CLONED_OK);
        } else {
            redirect_header('registry.php', 3, AM_PEDIGREE_CLONED_FAILED);
        }

        break;
    case 'list':
    default:
        $adminObject->addItemButton(AM_PEDIGREE_ADD_REGISTRY, 'registry.php?op=new', 'add');
        $adminObject->displayButton('left');
        $start                   = Request::getInt('start', 0);
        $registryPaginationLimit = $helper->getConfig('userpager');

        $criteria = new \CriteriaCompo();
        $criteria->setSort('id ASC, pname');
        $criteria->setOrder('ASC');
        $criteria->setLimit($registryPaginationLimit);
        $criteria->setStart($start);
        $registryTempRows  = $registryHandler->getCount();
        $registryTempArray = $registryHandler->getAll($criteria);
        /*
        //
        //
                            <th class='center width5'>".AM_PEDIGREE_FORM_ACTION."</th>
        //                    </tr>";
        //            $class = "odd";
        */

        // Display Page Navigation
        if ($registryTempRows > $registryPaginationLimit) {
            xoops_load('XoopsPageNav');

            $pagenav = new \XoopsPageNav($registryTempRows, $registryPaginationLimit, $start, 'start', 'op=list' . '&sort=' . $sort . '&order=' . $order . '');
            $GLOBALS['xoopsTpl']->assign('pagenav', null === $pagenav ? $pagenav->renderNav() : '');
        }

        $GLOBALS['xoopsTpl']->assign('registryRows', $registryTempRows);
        $registryArray = [];

        //    $fields = explode('|', id:mediumint:7:unsigned:NOT NULL::primary:ID|pname:text:0::NOT NULL:::Name|id_owner:smallint:5::NOT NULL:0::Owner|id_breeder:smallint:5::NOT NULL:0::Breeder|user:varchar:25::NOT NULL:::User|roft:enum:0::NOT NULL:0::ROFT|mother:int:5::NOT NULL:0::Mother|father:int:5::NOT NULL:0::Father|foto:varchar:255::NOT NULL:::Foto|coi:varchar:10::NOT NULL:::COI);
        //    $fieldsCount    = count($fields);

        $criteria = new \CriteriaCompo();

        //$criteria->setOrder('DESC');
        $criteria->setSort($sort);
        $criteria->setOrder($order);
        $criteria->setLimit($registryPaginationLimit);
        $criteria->setStart($start);

        $registryCount     = $registryHandler->getCount($criteria);
        $registryTempArray = $registryHandler->getAll($criteria);

        //    for ($i = 0; $i < $fieldsCount; ++$i) {
        if ($registryCount > 0) {
            foreach (array_keys($registryTempArray) as $i) {
                //        $field = explode(':', $fields[$i]);

                //$selectorid = $utility::selectSorting(AM_PEDIGREE_REGISTRY_ID, 'id');
                //$GLOBALS['xoopsTpl']->assign('selectorid', $selectorid);
                $registryArray['id'] = $registryTempArray[$i]->getVar('id');

                //$selectorpname = $utility::selectSorting(AM_PEDIGREE_REGISTRY_PNAME, 'pname');
                //$GLOBALS['xoopsTpl']->assign('selectorpname', $selectorpname);
                $registryArray['pname'] = strip_tags($registryTempArray[$i]->getVar('pname'));

                //$selectorid_owner = $utility::selectSorting(AM_PEDIGREE_REGISTRY_ID_OWNER, 'id_owner');
                //$GLOBALS['xoopsTpl']->assign('selectorid_owner', $selectorid_owner);
                $registryArray['id_owner'] = $registryTempArray[$i]->getVar('id_owner');

                //$selectorid_breeder = $utility::selectSorting(AM_PEDIGREE_REGISTRY_ID_BREEDER, 'id_breeder');
                //$GLOBALS['xoopsTpl']->assign('selectorid_breeder', $selectorid_breeder);
                $registryArray['id_breeder'] = $registryTempArray[$i]->getVar('id_breeder');

                //$selectoruser = $utility::selectSorting(AM_PEDIGREE_REGISTRY_USER, 'user');
                //$GLOBALS['xoopsTpl']->assign('selectoruser', $selectoruser);
                $registryArray['user'] = $registryTempArray[$i]->getVar('user');

                //$selectorroft = $utility::selectSorting(AM_PEDIGREE_REGISTRY_ROFT, 'roft');
                //$GLOBALS['xoopsTpl']->assign('selectorroft', $selectorroft);
                $registryArray['roft'] = $registryTempArray[$i]->getVar('roft');

                //$selectormother = $utility::selectSorting(AM_PEDIGREE_REGISTRY_MOTHER, 'mother');
                //$GLOBALS['xoopsTpl']->assign('selectormother', $selectormother);
                $registryArray['mother'] = $registryTempArray[$i]->getVar('mother');

                //$selectorfather = $utility::selectSorting(AM_PEDIGREE_REGISTRY_FATHER, 'father');
                //$GLOBALS['xoopsTpl']->assign('selectorfather', $selectorfather);
                $registryArray['father'] = $registryTempArray[$i]->getVar('father');

                //$selectorfoto = $utility::selectSorting(AM_PEDIGREE_REGISTRY_FOTO, 'foto');
                //$GLOBALS['xoopsTpl']->assign('selectorfoto', $selectorfoto);
                $registryArray['foto'] = "<img src='" . $uploadUrl . $registryTempArray[$i]->getVar('foto') . "' name='" . 'name' . "' id=" . 'id' . " alt='' style='max-width:100px'>";

                //$selectorcoi = $utility::selectSorting(AM_PEDIGREE_REGISTRY_COI, 'coi');
                //$GLOBALS['xoopsTpl']->assign('selectorcoi', $selectorcoi);
                $registryArray['coi']         = $registryTempArray[$i]->getVar('coi');
                $registryArray['edit_delete'] = "<a href='registry.php?op=edit&id=" . $i . "'><img src=" . $pathIcon16 . "/edit.png alt='" . _EDIT . "' title='" . _EDIT . "'></a>
               <a href='registry.php?op=delete&id=" . $i . "'><img src=" . $pathIcon16 . "/delete.png alt='" . _DELETE . "' title='" . _DELETE . "'></a>
               <a href='registry.php?op=clone&id=" . $i . "'><img src=" . $pathIcon16 . "/editcopy.png alt='" . _CLONE . "' title='" . _CLONE . "'></a>";

                $GLOBALS['xoopsTpl']->append_by_ref('registryArrays', $registryArray);
                unset($registryArray);
            }
            unset($registryTempArray);
            // Display Navigation
            if ($registryCount > $registryPaginationLimit) {
                xoops_load('XoopsPageNav');
                $pagenav = new \XoopsPageNav($registryCount, $registryPaginationLimit, $start, 'start', 'op=list' . '&sort=' . $sort . '&order=' . $order . '');
                $GLOBALS['xoopsTpl']->assign('pagenav', $pagenav->renderNav(4));
            }

            //                     echo "<td class='center width5'>

            //                    <a href='registry.php?op=edit&id=".$i."'><img src=".$pathIcon16."/edit.png alt='"._EDIT."' title='"._EDIT."'></a>
            //                    <a href='registry.php?op=delete&id=".$i."'><img src=".$pathIcon16."/delete.png alt='"._DELETE."' title='"._DELETE."'></a>
            //                    </td>";

            //                echo "</tr>";

            //            }

            //            echo "</table><br><br>";

            //        } else {

            //            echo "<table width='100%' cellspacing='1' class='outer'>

            //                    <tr>

            //                     <th class='center width5'>".AM_PEDIGREE_FORM_ACTION."XXX</th>
            //                    </tr><tr><td class='errorMsg' colspan='11'>There are noXXX registry</td></tr>";
            //            echo "</table><br><br>";

            //-------------------------------------------

            echo $GLOBALS['xoopsTpl']->fetch(XOOPS_ROOT_PATH . '/modules/' . $GLOBALS['xoopsModule']->getVar('dirname') . '/templates/admin/pedigree_admin_registry.tpl');
        }

        break;
}
require_once __DIR__ . '/admin_footer.php';
