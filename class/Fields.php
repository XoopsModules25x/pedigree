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
 * @copyright       {@link https://xoops.org/ The XOOPS Project}
 * @license         GPL 2.0 or later
 * @package         pedigree
 * @author          XOOPS Module Dev Team
 */

use XoopsFormButtonTray;
use XoopsFormHidden;
use XoopsFormRadio;
use XoopsFormRadioYN;
use XoopsFormSelect;
use XoopsFormText;
use XoopsModules\Pedigree;
use XoopsObject;
use XoopsThemeForm;


/**
 * Class Pedigree\Fields
 */
class Fields extends XoopsObject
{
    /**
     * Constructor
     */
    public function __construct()
    {
        parent::__construct();
        $this->initVar('id', \XOBJ_DTYPE_INT, null, false, 2);
        $this->initVar('isactive', \XOBJ_DTYPE_INT, 1, false, 1);
        $this->initVar('fieldname', \XOBJ_DTYPE_TXTBOX, null, false, 50);
        $this->initVar('fieldtype', \XOBJ_DTYPE_ENUM, 'textbox', false);
        $this->initVar('lookuptable', \XOBJ_DTYPE_INT, 0, false, 1);
        $this->initVar('defaultvalue', \XOBJ_DTYPE_TXTBOX, null, false, 50);
        $this->initVar('fieldexplanation', \XOBJ_DTYPE_TXTAREA, null, false);
        $this->initVar('hassearch', \XOBJ_DTYPE_INT, 0, false, 1);
        $this->initVar('litter', \XOBJ_DTYPE_INT, 0, false, 1);
        $this->initVar('generallitter', \XOBJ_DTYPE_INT, 1, false, 1);
        $this->initVar('searchname', \XOBJ_DTYPE_TXTBOX, null, false, 50);
        $this->initVar('searchexplanation', \XOBJ_DTYPE_TXTAREA, null, false);
        $this->initVar('viewinpedigree', \XOBJ_DTYPE_INT, 1, false, 1);
        $this->initVar('viewinadvanced', \XOBJ_DTYPE_INT, 1, false, 1);
        $this->initVar('viewinpie', \XOBJ_DTYPE_INT, 1, false, 1);
        $this->initVar('viewinlist', \XOBJ_DTYPE_INT, 1, false, 1);
        $this->initVar('locked', \XOBJ_DTYPE_INT, 0, false, 1);
        $this->initVar('order', \XOBJ_DTYPE_INT, 0, false, 3);
    }

    /**
     * @param bool $action
     *
     * @return object {@see XoopsThemeForm}
     * @todo refactor to eliminate XoopsObjectTree since it's not structured to
     *       handle this type of object
     *
     */
    public function getForm($action = false)
    {
        if (false === $action) {
            $action = $_SERVER['REQUEST_URI'];
        }

        $title = $this->isNew() ? \sprintf(\_AM_PEDIGREE_PEDIGREE_CONFIG_ADD) : \sprintf(\_AM_PEDIGREE_PEDIGREE_CONFIG_EDIT);

        require_once $GLOBALS['xoops']->path('class/xoopsformloader.php');

        $form = new XoopsThemeForm($title, 'form', $action, 'post', true);
        $form->setExtra('enctype="multipart/form-data"');

        $form->addElement(new XoopsFormText(\_AM_PEDIGREE_PEDIGREE_CONFIG_FIELDNAME, 'fieldName', 50, 255, $this->getVar('fieldname')), true);
        $form->addElement(new XoopsFormRadioYN(\_AM_PEDIGREE_PEDIGREE_CONFIG_ISACTIVE, 'isActive', (int)$this->getVar('isactive')), false);
        $fieldTypes = new XoopsFormSelect(\_AM_PEDIGREE_PEDIGREE_CONFIG_FIELDTYPE, 'fieldType', $this->getVar('fieldtype'), false);
        $fieldTypes->addOptionArray(
            [
                'DateSelect'  => 'DateSelect',
                'Picture'     => 'Picture',
                'RadioButton' => 'RadioButton',
                'SelectBox'   => 'SelectBox',
                'TextArea'    => 'TextArea',
                'TextBox'     => 'TextBox',
                'UrlField'    => 'UrlField',
            ]
        );
        $form->addElement($fieldTypes);
        //        $form->addElement(new \XoopsFormTextArea(_AM_PEDIGREE_PEDIGREE_CONFIG_FIELDTYPE, "fieldtype", $this->getVar("fieldtype"), 4, 47), false);
        $form->addElement(new XoopsFormText(\_AM_PEDIGREE_PEDIGREE_CONFIG_LOOKUPTABLE, 'lookupTable', 50, 255, $this->getVar('lookuptable')), false);
        $form->addElement(new XoopsFormText(\_AM_PEDIGREE_PEDIGREE_CONFIG_DEFAULTVALUE, 'defaultValue', 50, 255, $this->getVar('defaultvalue')), false);
        $form->addElement(new XoopsFormText(\_AM_PEDIGREE_PEDIGREE_CONFIG_FIELDEXPLANATION, 'fieldExplanation', 50, 255, $this->getVar('fieldexplanation')), false);
        $form->addElement(new XoopsFormRadioYN(\_AM_PEDIGREE_PEDIGREE_CONFIG_HASSEARCH, 'hasSearch', (int)$this->getVar('hassearch')), false);
        /* @todo: should be single select for either General Litter or Litter, can't have both */
        $currentType = $this->getVar('litter') ? 'litter' : 'generallitter';
        $litterRadio = new XoopsFormRadio(\_AM_PEDIGREE_PEDIGREE_CONFIG_LITTER_TYPE, 'litterType', $currentType);
        $litterRadio->addOptionArray(
            [
                'litter'        => \_AM_PEDIGREE_PEDIGREE_CONFIG_LITTER,
                'generallitter' => \_AM_PEDIGREE_PEDIGREE_CONFIG_GENERALLITTER,
            ]
        );
        $form->addElement($litterRadio, false);
        //        $form->addElement(new \XoopsFormRadioYN(_AM_PEDIGREE_PEDIGREE_CONFIG_LITTER, "Litter", $this->getVar("Litter")), false);
        //        $form->addElement(new \XoopsFormRadioYN(_AM_PEDIGREE_PEDIGREE_CONFIG_GENERALLITTER, "Generallitter", $this->getVar("Generallitter")), false);
        $form->addElement(new XoopsFormText(\_AM_PEDIGREE_PEDIGREE_CONFIG_SEARCHNAME, 'searchName', 50, 255, $this->getVar('searchname')), false);
        $form->addElement(new XoopsFormText(\_AM_PEDIGREE_PEDIGREE_CONFIG_SEARCHEXPLANATION, 'searchExplanation', 50, 255, $this->getVar('searchexplanation')), false);
        $form->addElement(new XoopsFormRadioYN(\_AM_PEDIGREE_PEDIGREE_CONFIG_VIEWINPEDIGREE, 'viewInPedigree', (int)$this->getVar('viewinpedigree')), false);
        $form->addElement(new XoopsFormRadioYN(\_AM_PEDIGREE_PEDIGREE_CONFIG_VIEWINADVANCED, 'viewInAdvanced', (int)$this->getVar('viewinadvanced')), false);
        $form->addElement(new XoopsFormRadioYN(\_AM_PEDIGREE_PEDIGREE_CONFIG_VIEWINPIE, 'viewInPie', (int)$this->getVar('viewinpie')), false);
        $form->addElement(new XoopsFormRadioYN(\_AM_PEDIGREE_PEDIGREE_CONFIG_VIEWINLIST, 'viewInList', (int)$this->getVar('viewinlist')), false);
        $form->addElement(new XoopsFormRadioYN(\_AM_PEDIGREE_PEDIGREE_CONFIG_LOCKED, 'locked', (int)$this->getVar('locked')), false);
        $form->addElement(new XoopsFormText(\_AM_PEDIGREE_PEDIGREE_CONFIG_ORDER, 'order', 3, 8, (int)$this->getVar('order')), false);
        //            require(XOOPS_ROOT_PATH."/class/tree.php");
        //            $Handler = xoops_getModuleHandler("animal_", $GLOBALS['xoopsDB']->getVar("dirname"));
        //            $criteria = new \CriteriaCompo();
        //            $criteria->setSort('_id');
        //            $criteria->setOrder('ASC');
        //            $_arr = $Handler->getAll();
        //            $mytree = new \XoopsObjectTree($_arr, "_id", "_pid");
        //            $form->addElement(new \XoopsFormLabel(_AM_PEDIGREE_PEDIGREE_CONFIG_LOCKED, $mytree->makeSelBox("_pid", "_title","--", $this->getVar("_pid"),false)));
        //
        //            require(XOOPS_ROOT_PATH."/class/tree.php");
        //            $Handler = xoops_getModuleHandler("animal_", $GLOBALS['xoopsModule']->getVar("dirname"));
        //            $criteria = new \CriteriaCompo();
        //            $criteria->setSort('_id');
        //            $criteria->setOrder('ASC');
        //            $_arr = $Handler->getAll();
        //            $mytree = new \XoopsObjectTree($_arr, "_id", "_pid");
        //            $form->addElement(new \XoopsFormLabel(_AM_PEDIGREE_PEDIGREE_CONFIG_ORDER, $mytree->makeSelBox("_pid", "_title","--", $this->getVar("_pid"),false)));
        /*
                require_once $GLOBALS['xoops']->path("class/tree.php");
        //      $Handler = xoops_getModuleHandler("animal_", $GLOBALS['xoopsModule']->getVar("dirname"));
                $Handler = Pedigree\Helper::getInstance()->getHandler('Fields');
        //        $Handler = & $fieldsHandler;
                $criteria = new \CriteriaCompo();
                $criteria->setSort('id');
                $criteria->setOrder('ASC');
                $_arr   = $Handler->getAll();
                $mytree = new \XoopsObjectTree($_arr, "ID", "_pid");
                $form->addElement(new \XoopsFormLabel(_AM_PEDIGREE_PEDIGREE_CONFIG_LOCKED, $mytree->makeSelBox("_pid", "_title", "--", $this->getVar("_pid"), false)));
        */
        $form->addElement(new XoopsFormHidden('op', 'save_pedigree_fields'));

        //Submit buttons
        $form->addElement(new XoopsFormButtonTray('fieldButtons', _SUBMIT, 'submit'));

        /*
                $buttonTray   = new \XoopsFormElementTray("", "");
                $submit_button = new \XoopsFormButton("", "submit", _SUBMIT, "submit");
                $buttonTray->addElement($submit_button);

                $cancel_button = new \XoopsFormButton("", "", _CANCEL, "cancel");
                $cancel_button->setExtra('onclick="history.go(-1)"');
                $buttonTray->addElement($cancel_button);

                $form->addElement($buttonTray);
        */

        return $form;
    }

    /**
     * @return bool
     */
    public function isActive()
    {
        return (1 == $this->getVar('isactive')); // ? true : false;
    }

    /**
     * @return bool
     */
    public function inAdvanced()
    {
        return (1 == $this->getVar('viewInAdvanced')); // ? true : false;
    }

    /**
     * @return bool
     */
    public function isLocked()
    {
        return (1 == $this->getVar('locked')); // ? true : false;
    }

    /**
     * @return bool
     */
    public function hasSearch()
    {
        return (1 == $this->getVar('hasSearch')); // ? true : false;
    }

    /**
     * @return bool
     */
    public function addLitter()
    {
        return (1 == $this->getVar('litter')); // ? true : false;
    }

    /**
     * @return bool
     */
    public function generalLitter()
    {
        return (1 == $this->getVar('generalLitter')); // ? true : false;
    }

    /**
     * @return bool
     */
    public function hasLookup()
    {
        return (1 == $this->getVar('lookupTable')); // ? true : false;
    }

    /**
     * @return string
     */
    public function getSearchString()
    {
        return '&amp;o=pname&amp;p';
    }

    /**
     * @return bool
     */
    public function inPie()
    {
        return (1 == $this->getVar('viewInPie')); // ? true : false;
    }

    /**
     * @return bool
     */
    public function inPedigree()
    {
        return (1 == $this->getVar('viewInPedigree')); // ? true : false;
    }

    /**
     * @return bool
     */
    public function inList()
    {
        return (1 == $this->getVar('viewInList')); // ? true : false;
    }

    /**
     * @return int|mixed
     */
    public function getId()
    {
        $id = $this->getVar('id');

        return !empty($id) ? $id : 0;
    }

    /**
     * @param $setting
     *
     * @return mixed
     * @deprecated
     */
    public function getSetting($setting)
    {
        return isset($this->$setting) ? $this->setting : null;
    }
}
