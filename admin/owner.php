<?php
/*
 * You may not change or alter any portion of this comment or credits
 * of supporting developers from this source code or any supporting source code
 * which is considered copyrighted (c) material of the original comment or credit authors.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 */

/**
 * @package         XoopsModules\Pedigree
 * @copyright       {@link https://xoops.org/ XOOPS Project}
 * @license         {@link https://www.gnu.org/licenses/gpl-2.0.html GNU GPL 2 or later}
 * @author          XOOPS Module Dev Team
 */

use Xmf\Request;
use XoopsModules\Pedigree\{
    Constants,
    Helper
};

require_once __DIR__ . '/admin_header.php';

xoops_cp_header();

/**
 * @var Xmf\Module\Admin                   $adminObject
 * @var XoopsModules\Pedigree\Helper       $helper
 * @var XoopsModules\Pedigree\OwnerHandler $ownerHandler
 */
$ownerHandler = $helper->getHandler('Owner');

$op = Request::getCmd('op', 'list');
switch ($op) {
    case 'list':
    default:
        $adminObject->displayNavigation(basename(__FILE__));
        $adminObject->addItemButton(_AM_PEDIGREE_NEWOWNER, 'owner.php?op=new_owner', 'add');
        $adminObject->displayButton('left');
        $criteria = new \CriteriaCompo();
        $criteria->setSort('id');
        $criteria->order = 'ASC';
        $ownerCount      = $ownerHandler->getCount();
        $ownerObjArray   = $ownerHandler->getAll($criteria);

        //Table view
        if ($ownerCount > 0) {
            echo "<table class=\"outer width100\" cellspacing=\"1\">\n"
                 . "<tr>\n"
                 . "     <th class=\"center\">"
                 . _AM_PEDIGREE_OWNER_FIRSTNAME
                 . "</th>\n"
                 . "     <th class=\"center\">"
                 . _AM_PEDIGREE_OWNER_LASTNAME
                 . "</th>\n"
                 . "     <th class=\"center\">"
                 . _AM_PEDIGREE_OWNER_POSTCODE
                 . "</th>\n"
                 . "     <th class=\"center\">"
                 . _AM_PEDIGREE_OWNER_CITY
                 . "</th>\n"
                 . "     <th class=\"center\">"
                 . _AM_PEDIGREE_OWNER_STREETNAME
                 . "</th>\n"
                 . "     <th class=\"center\">"
                 . _AM_PEDIGREE_OWNER_HOUSENUMBER
                 . "</th>\n"
                 . "     <th class=\"center\">"
                 . _AM_PEDIGREE_OWNER_PHONENUMBER
                 . "</th>\n"
                 . "     <th class=\"center\">"
                 . _AM_PEDIGREE_OWNER_EMAILADRES
                 . "</th>\n"
                 . "     <th class=\"center\">"
                 . _AM_PEDIGREE_OWNER_WEBSITE
                 . "</th>\n"
                 . "     <th class=\"center\">"
                 . _AM_PEDIGREE_OWNER_USER
                 . "</th>\n"
                 . "     <th class=\"center width10\">"
                 . _AM_PEDIGREE_FORMACTION
                 . "</th>\n"
                 . "</tr>\n";

            $class = 'odd';

            foreach ($ownerObjArray as $ownerObj) {
                //@todo figure out what the following statement is "suppose" to do, owner_pid isn't defined
                //if (0 == $ownerObj->getVar('owner_pid')) {
                $ownerVals = $ownerObj->getValues();
                echo "<tr class=\"{$class}\">\n"
                     . "    <td class=\"center\">"
                     . $ownerObj['firstname']
                     . "</td>\n"
                     . "    <td class=\"center\">"
                     . $ownerObj['lastname']
                     . "</td>\n"
                     . "    <td class=\"center\">"
                     . $ownerObj['postcode']
                     . "</td>\n"
                     . "    <td class=\"center\">"
                     . $ownerObj['city']
                     . "</td>\n"
                     . "    <td class=\"center\">"
                     . $ownerObj['streetname']
                     . "</td>\n"
                     . "    <td class=\"center\">"
                     . $ownerObj['housenumber']
                     . "</td>\n"
                     . "    <td class=\"center\">"
                     . $ownerObj['phonenumber']
                     . "</td>\n"
                     . "    <td class=\"center\">"
                     . $ownerObj['emailadres']
                     . "</td>\n"
                     . "    <td class=\"center\">"
                     . $ownerObj['website']
                     . "</td>\n"
                     . "    <td class=\"center\">"
                     . $ownerObj['user']
                     . "</td>\n"
                     . "    <td class=\"center width10\">\n"
                     . "        <a href=\""
                     . $helper->url("admin/owner.php?op=edit_owner&id=" . $ownerObj['id'])
                     . "\">{$icons['edit']}</a>\n"
                     . "        <a href=\""
                     . $helper->url("admin/owner.php?op=delete_owner&id=" . $ownerObj['id'])
                     . "\">{$icons['delete']}</a>\n"
                     . "    </td>\n"
                     . "</tr>\n";
                $class = ('even' === $class) ? 'odd' : 'even';
                //}
            }
            echo "</table><br><br>";
        }
        break;

    case 'edit_owner':
    case 'new_owner':
        $id = Request::getInt('id', null, 'GET');
        $adminObject->displayNavigation(basename(__FILE__));
        if (0 !== (int)$id) {
            $adminObject->addItemButton(_AM_PEDIGREE_NEWOWNER, 'owner.php?op=new_owner', 'add');
        }
        $adminObject->addItemButton(_AM_PEDIGREE_OWNERLIST, 'owner.php?op=list', 'list');
        $adminObject->displayButton('left');

        // if $id is invalid then it will create $obj, else will edit existing $obj
        $obj  = $ownerHandler->get($id);
        $form = $obj->getForm();
        $form->display();
        break;

    case 'save_owner':
        if (!$GLOBALS['xoopsSecurity']->check()) {
            $helper->redirect('admin/owner.php', Constants::REDIRECT_DELAY_MEDIUM, implode(',', $GLOBALS['xoopsSecurity']->getErrors()));
        }
        $id = Request::getInt('id', null, 'POST');
        // get object if it exists, create it if not
        $obj = $ownerHandler->get($id);

        //@todo shouldn't firstname and/or lastname be required?
        $obj->setVars([
                          'firstname'   => Request::getWord('firstname', '', 'POST'),         //Form firstname
                          'lastname'    => Request::getWord('lastname', '', 'POST'),           //Form lastname
                          'postcode'    => Request::getString('postcode', null, 'POST'),       //Form postcode
                          'city'        => Request::getString('city', '', 'POST'),                 //Form city
                          'streetname'  => Request::getString('streetname', '', 'POST'),     //Form streetname
                          'housenumber' => Request::getString('housenumber', null, 'POST'), //Form housenumber
                          'phonenumber' => Request::getString('phonenumber', null, 'POST'), //Form phonenumber
                          'emailadres'  => Request::getEmail('emailadres', '', 'POST'),       //Form emailadres
                          'website'     => Request::getUrl('website', '', 'POST'),               //Form website
                          'user'        => Request::getString('user', '', 'POST')                   //Form user
                      ]);
        /*
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
        */
        if ($ownerHandler->insert($obj)) {
            $helper->redirect('admin/owner.php?op=list', 2, _AM_PEDIGREE_FORMOK);
        }

        echo $obj->getHtmlErrors();
        $form = $obj->getForm();
        $form->display();
        break;

    case 'delete_owner':
        $id  = Request::getInt('id');
        $ok  = Request::getInt('ok', Constants::CONFIRM_NOT_OK, 'POST');
        $obj = $ownerHandler->get($id);
        if (Constants::CONFIRM_OK === $ok) {
            if (!$GLOBALS['xoopsSecurity']->check()) {
                $helper->redirect('admin/owner.php', Constants::REDIRECT_DELAY_MEDIUM, implode(',', $GLOBALS['xoopsSecurity']->getErrors()));
            }
            if ($ownerHandler->delete($obj)) {
                $helper->redirect('admin/owner.php', Constants::REDIRECT_DELAY_MEDIUM, _AM_PEDIGREE_FORMDELOK);
            } else {
                echo $obj->getHtmlErrors();
            }
        } else {
            xoops_confirm(['ok' => Constants::CONFIRM_OK, 'id' => $id, 'op' => 'delete_owner'], $_SERVER['REQUEST_URI'], sprintf(_AM_PEDIGREE_FORMSUREDEL, $obj->getVar('owner')));
        }
        break;
}
require_once __DIR__ . '/admin_footer.php';
