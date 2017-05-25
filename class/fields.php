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
 * @copyright       {@link http://xoops.org/ The XOOPS Project}
 * @license         GPL 2.0 or later
 * @package         pedigree
 * @author          XOOPS Module Dev Team
 */

defined('XOOPS_ROOT_PATH') || exit('XOOPS root path not defined');

/**
 * Class PedigreeFields
 */
class PedigreeFields extends XoopsObject
{
    /**
     * Constructor
     */
    public function __construct()
    {
        parent::__construct();
        $this->initVar('Id', XOBJ_DTYPE_INT, null, false, 2);
        $this->initVar('isactive', XOBJ_DTYPE_INT, 1, false, 1);
        $this->initVar('fieldname', XOBJ_DTYPE_TXTBOX, null, false, 50);
        $this->initVar('fieldtype', XOBJ_DTYPE_ENUM, 'textbox', false);
        $this->initVar('lookuptable', XOBJ_DTYPE_INT, 0, false, 1);
        $this->initVar('defaultvalue', XOBJ_DTYPE_TXTBOX, null, false, 50);
        $this->initVar('fieldexplanation', XOBJ_DTYPE_TXTAREA, null, false);
        $this->initVar('hassearch', XOBJ_DTYPE_INT, 0, false, 1);
        $this->initVar('litter', XOBJ_DTYPE_INT, 0, false, 1);
        $this->initVar('generallitter', XOBJ_DTYPE_INT, 1, false, 1);
        $this->initVar('searchname', XOBJ_DTYPE_TXTBOX, null, false, 50);
        $this->initVar('searchexplanation', XOBJ_DTYPE_TXTAREA, null, false);
        $this->initVar('viewinpedigree', XOBJ_DTYPE_INT, 1, false, 1);
        $this->initVar('viewinadvanced', XOBJ_DTYPE_INT, 1, false, 1);
        $this->initVar('viewinpie', XOBJ_DTYPE_INT, 1, false, 1);
        $this->initVar('viewinlist', XOBJ_DTYPE_INT, 1, false, 1);
        $this->initVar('locked', XOBJ_DTYPE_INT, 0, false, 1);
        $this->initVar('order', XOBJ_DTYPE_INT, 0, false, 3);
    }

    /**
     * @param bool $action
     *
     * @todo refactor to eliminate XoopsObjectTree since it's not structured to
     *       handle this type of object
     *
     * @return object {@see XoopsThemeForm}
     */
    public function getForm($action = false)
    {
        if ($action === false) {
            $action = $_SERVER['REQUEST_URI'];
        }

        $title = $this->isNew() ? sprintf(_AM_PEDIGREE_PEDIGREE_CONFIG_ADD) : sprintf(_AM_PEDIGREE_PEDIGREE_CONFIG_EDIT);

        include_once $GLOBALS['xoops']->path('class/xoopsformloader.php');

        $form = new XoopsThemeForm($title, 'form', $action, 'post', true);
        $form->setExtra('enctype="multipart/form-data"');

        $form->addElement(new XoopsFormText(_AM_PEDIGREE_PEDIGREE_CONFIG_FIELDNAME, 'fieldName', 50, 255, $this->getVar('fieldname')), true);
        $form->addElement(new XoopsFormRadioYN(_AM_PEDIGREE_PEDIGREE_CONFIG_ISACTIVE, 'isActive', (int)$this->getVar('isactive')), false);
        $fieldTypes = new XoopsFormSelect(_AM_PEDIGREE_PEDIGREE_CONFIG_FIELDTYPE, 'fieldType', $this->getVar('fieldtype'), false);
        $fieldTypes->addOptionArray(array('DateSelect' => 'DateSelect', 'Picture' => 'Picture', 'radiobutton' => 'radiobutton', 'selectbox' => 'selectbox', 'textarea' => 'textarea', 'textbox' => 'textbox', 'urlfield' => 'urlfield'));
        $form->addElement($fieldTypes);
        //        $form->addElement(new XoopsFormTextArea(_AM_PEDIGREE_PEDIGREE_CONFIG_FIELDTYPE, "FieldType", $this->getVar("fieldtype"), 4, 47), false);
        $form->addElement(new XoopsFormText(_AM_PEDIGREE_PEDIGREE_CONFIG_LOOKUPTABLE, 'lookupTable', 50, 255, (int)$this->hasLookup()), false);
        $form->addElement(new XoopsFormText(_AM_PEDIGREE_PEDIGREE_CONFIG_DEFAULTVALUE, 'defaultValue', 50, 255, $this->getVar('defaultvalue')), false);
        $form->addElement(new XoopsFormText(_AM_PEDIGREE_PEDIGREE_CONFIG_FIELDEXPLANATION, 'fieldExplanation', 50, 255, $this->getVar('fieldexplanation')), false);
        $form->addElement(new XoopsFormRadioYN(_AM_PEDIGREE_PEDIGREE_CONFIG_HASSEARCH, 'hasSearch', (int)$this->getVar('hassearch')), false);
        /* @todo: should be single select for either General Litter or Litter, can't have both */
        $currentType = $this->getVar('litter') ? 'litter' : 'generallitter';
        $litterRadio = new XoopsFormRadio(_AM_PEDIGREE_PEDIGREE_CONFIG_LITTER_TYPE, 'litterType', $currentType);
        $litterRadio->addOptionArray(array('litter' => _AM_PEDIGREE_PEDIGREE_CONFIG_LITTER, 'generallitter' => _AM_PEDIGREE_PEDIGREE_CONFIG_GENERALLITTER));
        $form->addElement($litterRadio, false);
        //        $form->addElement(new XoopsFormRadioYN(_AM_PEDIGREE_PEDIGREE_CONFIG_LITTER, "Litter", $this->getVar("litter")), false);
        //        $form->addElement(new XoopsFormRadioYN(_AM_PEDIGREE_PEDIGREE_CONFIG_GENERALLITTER, "generalLitter", $this->getVar("generallitter")), false);
        $form->addElement(new XoopsFormText(_AM_PEDIGREE_PEDIGREE_CONFIG_SEARCHNAME, 'searchName', 50, 255, $this->getVar('searchname')), false);
        $form->addElement(new XoopsFormText(_AM_PEDIGREE_PEDIGREE_CONFIG_SEARCHEXPLANATION, 'searchExplanation', 50, 255, $this->getVar('searchexplanation')), false);
        $form->addElement(new XoopsFormRadioYN(_AM_PEDIGREE_PEDIGREE_CONFIG_VIEWINPEDIGREE, 'viewInPedigree', (int)$this->inPedigree()), false);
        $form->addElement(new XoopsFormRadioYN(_AM_PEDIGREE_PEDIGREE_CONFIG_VIEWINADVANCED, 'viewInAdvanced', (int)$this->inAdvanced()), false);
        $form->addElement(new XoopsFormRadioYN(_AM_PEDIGREE_PEDIGREE_CONFIG_VIEWINPIE, 'viewInPie', (int)$this->inPie()), false);
        $form->addElement(new XoopsFormRadioYN(_AM_PEDIGREE_PEDIGREE_CONFIG_VIEWINLIST, 'viewInList', (int)$this->inList()), false);
        $form->addElement(new XoopsFormRadioYN(_AM_PEDIGREE_PEDIGREE_CONFIG_LOCKED, 'locked', (int)$this->getVar('locked')), false);
        $form->addElement(new XoopsFormText(_AM_PEDIGREE_PEDIGREE_CONFIG_ORDER, 'order', 3, 8, (int)$this->getVar('order')), false);
        //            include_once(XOOPS_ROOT_PATH."/class/tree.php");
        //            $Handler = xoops_getModuleHandler("animal_", $GLOBALS['xoopsDB']->getVar("dirname"));
        //            $criteria = new CriteriaCompo();
        //            $criteria->setSort('_id');
        //            $criteria->setOrder('ASC');
        //            $_arr = $Handler->getall();
        //            $mytree = new XoopsObjectTree($_arr, "_id", "_pid");
        //            $form->addElement(new XoopsFormLabel(_AM_PEDIGREE_PEDIGREE_CONFIG_LOCKED, $mytree->makeSelBox("_pid", "_title","--", $this->getVar("_pid"),false)));
        //
        //            include_once(XOOPS_ROOT_PATH."/class/tree.php");
        //            $Handler = xoops_getModuleHandler("animal_", $GLOBALS['xoopsModule']->getVar("dirname"));
        //            $criteria = new CriteriaCompo();
        //            $criteria->setSort('_id');
        //            $criteria->setOrder('ASC');
        //            $_arr = $Handler->getall();
        //            $mytree = new XoopsObjectTree($_arr, "_id", "_pid");
        //            $form->addElement(new XoopsFormLabel(_AM_PEDIGREE_PEDIGREE_CONFIG_ORDER, $mytree->makeSelBox("_pid", "_title","--", $this->getVar("_pid"),false)));
        /*
                include_once $GLOBALS['xoops']->path("class/tree.php");
        //      $Handler = xoops_getModuleHandler("animal_", $GLOBALS['xoopsModule']->getVar("dirname"));
                $Handler = xoops_getModuleHandler('fields', 'pedigree');
        //        $Handler = & $pedigreeFieldsHandler;
                $criteria = new CriteriaCompo();
                $criteria->setSort('Id');
                $criteria->setOrder('ASC');
                $_arr   = $Handler->getAll();
                $mytree = new XoopsObjectTree($_arr, "ID", "_pid");
                $form->addElement(new XoopsFormLabel(_AM_PEDIGREE_PEDIGREE_CONFIG_LOCKED, $mytree->makeSelBox("_pid", "_title", "--", $this->getVar("_pid"), false)));
        */
        $form->addElement(new XoopsFormHidden('op', 'save_pedigree_config'));

        //Submit buttons
        $form->addElement(new XoopsFormButtonTray('fieldButtons', _SUBMIT, 'submit'));
        /*
                $button_tray   = new XoopsFormElementTray("", "");
                $submit_button = new XoopsFormButton("", "submit", _SUBMIT, "submit");
                $button_tray->addElement($submit_button);

                $cancel_button = new XoopsFormButton("", "", _CANCEL, "cancel");
                $cancel_button->setExtra('onclick="history.go(-1)"');
                $button_tray->addElement($cancel_button);

                $form->addElement($button_tray);
        */
        return $form;
    }

    /**
     * @return bool
     */
    public function isActive()
    {
        return (1 == $this->getVar('isactive')) ? true : false;
    }

    /**
     * @return bool
     */
    public function inAdvanced()
    {
        return (1 == $this->getVar('viewinadvanced')) ? true : false;
    }

    /**
     * @return bool
     */
    public function isLocked()
    {
        return (1 == $this->getVar('locked')) ? true : false;
    }

    /**
     * @return bool
     */
    public function hasSearch()
    {
        return (1 == $this->getVar('hassearch')) ? true : false;
    }

    /**
     * @return bool
     */
    public function addLitter()
    {
        return (1 == $this->getVar('litter'));// ? true : false;
    }

    /**
     * @return bool
     */
    public function generalLitter()
    {
        return (1 == $this->getVar('generallitter'));// ? true : false;
    }

    /**
     * @return bool
     */
    public function hasLookup()
    {
        return (1 == $this->getVar('lookuptable')) ? true : false;
    }

    /**
     * @return string
     */
    public function getSearchString()
    {
        return '&amp;o=naam&amp;p';
    }

    /**
     * @return bool
     */
    public function inPie()
    {
        return (1 == $this->getVar('viewinpie')) ? true : false;
    }

    /**
     * @return bool
     */
    public function inPedigree()
    {
        return (1 == $this->getVar('viewinpedigree')) ? true : false;
    }

    /**
     * @return bool
     */
    public function inList()
    {
        return (1 == $this->getVar('viewinlist')) ? true : false;
    }

    /**
     * @return int|mixed
     */
    public function getId()
    {
        $id = $this->getVar('Id');

        return (!empty($id)) ? $id : 0;
    }

    /**
     * @deprecated
     * @param $setting
     *
     * @return mixed
     */
    public function getSetting($setting)
    {
        return isset($this->$setting) ? $this->setting : null;
    }
}

/**
 * Class PedigreeFieldsHandler
 *
 * @param object $db reference to the {@link XoopsDatabase} object
 *
 * @return void
 */
class PedigreeFieldsHandler extends XoopsPersistableObjectHandler
{
    /**
     * @param null|object|XoopsDatabase $db
     */
    public function __construct(XoopsDatabase $db)
    {
        parent::__construct($db, 'pedigree_fields', 'PedigreeFields', 'Id', 'fieldname');
    }

    /**
     * @param $fieldnumber
     *
     * @return array
     */
    public function lookupField($fieldnumber)
    {
        $ret    = array();
        $SQL    = 'SELECT * FROM ' . $GLOBALS['xoopsDB']->prefix('pedigree_lookup' . $fieldnumber) . " ORDER BY 'order'";
        $result = $GLOBALS['xoopsDB']->query($SQL);
        while (false !== ($row = $GLOBALS['xoopsDB']->fetchArray($result))) {
            $ret[] = array('id' => $row['Id'], 'value' => $row['value']);
        }
        //array_multisort($ret,SORT_ASC);
        return $ret;
    }
}
