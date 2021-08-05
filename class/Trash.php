<?php

namespace XoopsModules\Pedigree;

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
 * @author          XOOPS Module Dev Team (https://xoops.org)
 */
use XoopsModules\Pedigree;

defined('XOOPS_ROOT_PATH') || die('Restricted access');

/**
 * Class Pedigree\Trash
 */
class Trash extends \XoopsObject
{
    //Constructor

    public function __construct()
    {
        parent::__construct();
        $this->initVar('id', XOBJ_DTYPE_INT, null, false, 11);
        $this->initVar('naam', XOBJ_DTYPE_TXTAREA, null, false);
        $this->initVar('id_owner', XOBJ_DTYPE_INT, null, false, 11);
        $this->initVar('id_breeder', XOBJ_DTYPE_INT, null, false, 11);
        $this->initVar('user', XOBJ_DTYPE_TXTBOX, null, false, 25);
        $this->initVar('roft', XOBJ_DTYPE_TXTBOX, null, false, 1);
        $this->initVar('mother', XOBJ_DTYPE_INT, null, false, 5);
        $this->initVar('father', XOBJ_DTYPE_INT, null, false, 5);
        $this->initVar('foto', XOBJ_DTYPE_TXTBOX, null, false, 255);
        $this->initVar('coi', XOBJ_DTYPE_TXTBOX, null, false, 10);
    }

    /**
     *
     * @return string
     */
    public function __toString()
    {
        return $this->getVar('naam');
    }

    /**
     * @param bool $action
     *
     * @return \XoopsThemeForm
     */
    public function getForm($action = false)
    {
        //global $xoopsModuleConfig;

        if (false === $action) {
            $action = $_SERVER['REQUEST_URI'];
        }

        $title = $this->isNew() ? sprintf(_AM_PEDIGREE_PEDIGREE_TRASH_ADD) : sprintf(_AM_PEDIGREE_PEDIGREE_TRASH_EDIT);

        require_once XOOPS_ROOT_PATH . '/class/xoopsformloader.php';

        $form = new \XoopsThemeForm($title, 'form', $action, 'post', true);
        $form->setExtra('enctype="multipart/form-data"');

        $form->addElement(new \XoopsFormTextArea(_AM_PEDIGREE_PEDIGREE_TRASH_NAAM, 'naam', $this->getVar('naam'), 4, 47), true);
        $form->addElement(new \XoopsFormText(_AM_PEDIGREE_PEDIGREE_TRASH_ID_OWNER, 'id_owner', 50, 255, $this->getVar('id_owner')), false);
        $form->addElement(new \XoopsFormText(_AM_PEDIGREE_PEDIGREE_TRASH_ID_BREEDER, 'id_breeder', 50, 255, $this->getVar('id_breeder')), false);
        $form->addElement(new \XoopsFormText(_AM_PEDIGREE_PEDIGREE_TRASH_USER, 'user', 50, 255, $this->getVar('user')), false);
        $form->addElement(new \XoopsFormText(_AM_PEDIGREE_PEDIGREE_TRASH_ROFT, 'roft', 50, 255, $this->getVar('roft')), false);
        $form->addElement(new \XoopsFormText(_AM_PEDIGREE_PEDIGREE_TRASH_MOTHER, 'mother', 50, 255, $this->getVar('mother')), false);
        $form->addElement(new \XoopsFormText(_AM_PEDIGREE_PEDIGREE_TRASH_FATHER, 'father', 50, 255, $this->getVar('father')), false);
        $form->addElement(new \XoopsFormText(_AM_PEDIGREE_PEDIGREE_TRASH_FOTO, 'foto', 50, 255, $this->getVar('foto')), false);
        $form->addElement(new \XoopsFormText(_AM_PEDIGREE_PEDIGREE_TRASH_COI, 'coi', 50, 255, $this->getVar('coi')), false);

        $form->addElement(new \XoopsFormHidden('op', 'save_pedigree_trash'));

        //Submit buttons
        $button_tray = new \XoopsFormElementTray('', '');
        $submit_button = new \XoopsFormButton('', 'submit', _SUBMIT, 'submit');
        $button_tray->addElement($submit_button);

        $cancel_button = new \XoopsFormButton('', '', _CANCEL, 'cancel');
        $cancel_button->setExtra('onclick="history.go(-1)"');
        $button_tray->addElement($cancel_button);

        $form->addElement($button_tray);

        return $form;
    }
}
