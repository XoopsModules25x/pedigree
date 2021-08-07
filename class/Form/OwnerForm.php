<?php

namespace XoopsModules\Pedigree\Form;

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
use XoopsFormButton;
use XoopsFormHidden;
use XoopsFormLabel;
use XoopsFormSelectUser;
use XoopsFormText;
use XoopsModules\Pedigree\{
    Helper
};
use XoopsThemeForm;

require_once \dirname(__DIR__, 2) . '/include/common.php';

$moduleDirName = \basename(\dirname(__DIR__, 2));
//$helper = Helper::getInstance();
$permHelper = new Permission();

\xoops_load('XoopsFormLoader');

/**
 * Class OwnerForm
 */
class OwnerForm extends XoopsThemeForm
{
    public $targetObject;

    /**
     * Constructor
     *
     * @param $target
     */
    public function __construct($target)
    {
        //  global $helper;
        $this->helper       = $target->helper;
        $this->targetObject = $target;

        $title = $this->targetObject->isNew() ? \sprintf(AM_PEDIGREE_OWNER_ADD) : \sprintf(AM_PEDIGREE_OWNER_EDIT);
        parent::__construct($title, 'form', \xoops_getenv('SCRIPT_NAME'), 'post', true);
        $this->setExtra('enctype="multipart/form-data"');

        //include ID field, it's needed so the module knows if it is a new form or an edited form

        $hidden = new \XoopsFormHidden('id', $this->targetObject->getVar('id'));
        $this->addElement($hidden);
        unset($hidden);

        // Id
        $this->addElement(new \XoopsFormLabel(AM_PEDIGREE_OWNER_ID, $this->targetObject->getVar('id'), 'id'));
        // Firstname
        $this->addElement(new \XoopsFormText(AM_PEDIGREE_OWNER_FIRSTNAME, 'firstname', 50, 255, $this->targetObject->getVar('firstname')), false);
        // Lastname
        $this->addElement(new \XoopsFormText(AM_PEDIGREE_OWNER_LASTNAME, 'lastname', 50, 255, $this->targetObject->getVar('lastname')), false);
        // Postcode
        $this->addElement(new \XoopsFormText(AM_PEDIGREE_OWNER_POSTCODE, 'postcode', 50, 255, $this->targetObject->getVar('postcode')), false);
        // City
        $this->addElement(new \XoopsFormText(AM_PEDIGREE_OWNER_CITY, 'city', 50, 255, $this->targetObject->getVar('city')), false);
        // Streetname
        $this->addElement(new \XoopsFormText(AM_PEDIGREE_OWNER_STREETNAME, 'streetname', 50, 255, $this->targetObject->getVar('streetname')), false);
        // Housenumber
        $this->addElement(new \XoopsFormText(AM_PEDIGREE_OWNER_HOUSENUMBER, 'housenumber', 50, 255, $this->targetObject->getVar('housenumber')), false);
        // Phonenumber
        $this->addElement(new \XoopsFormText(AM_PEDIGREE_OWNER_PHONENUMBER, 'phonenumber', 50, 255, $this->targetObject->getVar('phonenumber')), false);
        // Emailadres
        $this->addElement(new \XoopsFormText(AM_PEDIGREE_OWNER_EMAILADRES, 'emailadres', 50, 255, $this->targetObject->getVar('emailadres')), false);
        // Website
        $this->addElement(new \XoopsFormText(AM_PEDIGREE_OWNER_WEBSITE, 'website', 50, 255, $this->targetObject->getVar('website')), false);
        // User
        $this->addElement(new \XoopsFormSelectUser(AM_PEDIGREE_OWNER_USER, 'user', false, $this->targetObject->getVar('user'), 1, false), false);

        $this->addElement(new \XoopsFormHidden('op', 'save'));
        $this->addElement(new \XoopsFormButton('', 'submit', _SUBMIT, 'submit'));
    }
}
