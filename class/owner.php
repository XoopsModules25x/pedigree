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
 * @copyright       XOOPS Project (http://xoops.org)
 * @license         GPL 2.0 or later
 * @package         pedigree
 * @since           2.5.x
 * @author          XOOPS Module Dev Team (http://xoops.org)
 */

defined('XOOPS_ROOT_PATH') || exit('XOOPS root path not defined');

/**
 * Class PedigreeOwner
 */
class PedigreeOwner extends XoopsObject
{
    //Constructor
    /**
     *
     */
    public function __construct()
    {
        parent::__construct();
        $this->initVar('ID', XOBJ_DTYPE_INT, null, false, 11);
        $this->initVar('firstname', XOBJ_DTYPE_TXTBOX, null, false, 30);
        $this->initVar('lastname', XOBJ_DTYPE_TXTBOX, null, false, 30);
        $this->initVar('postcode', XOBJ_DTYPE_TXTBOX, null, false, 7);
        $this->initVar('city', XOBJ_DTYPE_TXTBOX, null, false, 50);
        $this->initVar('streetname', XOBJ_DTYPE_TXTBOX, null, false, 40);
        $this->initVar('housenumber', XOBJ_DTYPE_TXTBOX, null, false, 6);
        $this->initVar('phonenumber', XOBJ_DTYPE_TXTBOX, null, false, 14);
        $this->initVar('emailadres', XOBJ_DTYPE_TXTBOX, null, false, 40);
        $this->initVar('website', XOBJ_DTYPE_TXTBOX, null, false, 60);
        $this->initVar('user', XOBJ_DTYPE_TXTBOX, null, false, 20);
    }

    /**
     * @param bool $action
     *
     * @return XoopsThemeForm
     */
    public function getForm($action = false)
    {
        global $xoopsModuleConfig;

        if ($action === false) {
            $action = $_SERVER['REQUEST_URI'];
        }

        $title = $this->isNew() ? sprintf(_AM_PEDIGREE_OWNER_ADD) : sprintf(_AM_PEDIGREE_OWNER_EDIT);

        include_once(XOOPS_ROOT_PATH . '/class/xoopsformloader.php');

        $form = new XoopsThemeForm($title, 'form', $action, 'post', true);
        $form->setExtra('enctype="multipart/form-data"');

        $form->addElement(new XoopsFormText(_AM_PEDIGREE_OWNER_FIRSTNAME, 'firstname', 50, 255, $this->getVar('firstname')), false);
        $form->addElement(new XoopsFormText(_AM_PEDIGREE_OWNER_LASTNAME, 'lastname', 50, 255, $this->getVar('lastname')), false);
        $form->addElement(new XoopsFormText(_AM_PEDIGREE_OWNER_POSTCODE, 'postcode', 50, 255, $this->getVar('postcode')), false);
        $form->addElement(new XoopsFormText(_AM_PEDIGREE_OWNER_CITY, 'city', 50, 255, $this->getVar('city')), false);
        $form->addElement(new XoopsFormText(_AM_PEDIGREE_OWNER_STREETNAME, 'streetname', 50, 255, $this->getVar('streetname')), false);
        $form->addElement(new XoopsFormText(_AM_PEDIGREE_OWNER_HOUSENUMBER, 'housenumber', 50, 255, $this->getVar('housenumber')), false);
        $form->addElement(new XoopsFormText(_AM_PEDIGREE_OWNER_PHONENUMBER, 'phonenumber', 50, 255, $this->getVar('phonenumber')), false);
        $form->addElement(new XoopsFormText(_AM_PEDIGREE_OWNER_EMAILADRES, 'emailadres', 50, 255, $this->getVar('emailadres')), false);
        $form->addElement(new XoopsFormText(_AM_PEDIGREE_OWNER_WEBSITE, 'website', 50, 255, $this->getVar('website')), false);
        $form->addElement(new XoopsFormText(_AM_PEDIGREE_OWNER_USER, 'user', 50, 255, $this->getVar('user')), false);

        $form->addElement(new XoopsFormHidden('op', 'save_owner'));

        //Submit buttons
        $button_tray   = new XoopsFormElementTray('', '');
        $submit_button = new XoopsFormButton('', 'submit', _SUBMIT, 'submit');
        $button_tray->addElement($submit_button);

        $cancel_button = new XoopsFormButton('', '', _CANCEL, 'cancel');
        $cancel_button->setExtra('onclick="history.go(-1)"');
        $button_tray->addElement($cancel_button);

        $form->addElement($button_tray);

        return $form;
    }
}

/**
 * Class PedigreeOwnerHandler
 */
class PedigreeOwnerHandler extends XoopsPersistableObjectHandler
{
    /**
     * @param null|object|XoopsDatabase $db
     */
    public function __construct(XoopsDatabase $db)
    {
        parent::__construct($db, 'pedigree_owner', 'PedigreeOwner', 'ID', 'firstname');
    }
}
