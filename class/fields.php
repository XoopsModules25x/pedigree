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
 * @copyright       {@link https://xoops.org/ The XOOPS Project}
 * @license         GPL 2.0 or later
 * @package         pedigree
 * @author          XOOPS Module Dev Team
 */

defined('XOOPS_ROOT_PATH') || die('Restricted access');

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
        $this->initVar('id', XOBJ_DTYPE_INT, null, false, 2);
        $this->initVar('isaActive', XOBJ_DTYPE_INT, 1, false, 1);
        $this->initVar('fieldName', XOBJ_DTYPE_TXTBOX, null, false, 50);
        $this->initVar('fieldType', XOBJ_DTYPE_ENUM, 'textbox', false);
        $this->initVar('lookupTable', XOBJ_DTYPE_INT, 0, false, 1);
        $this->initVar('defaultValue', XOBJ_DTYPE_TXTBOX, null, false, 50);
        $this->initVar('fieldExplanation', XOBJ_DTYPE_TXTAREA, null, false);
        $this->initVar('hasSearch', XOBJ_DTYPE_INT, 0, false, 1);
        $this->initVar('litter', XOBJ_DTYPE_INT, 0, false, 1);
        $this->initVar('generalLitter', XOBJ_DTYPE_INT, 1, false, 1);
        $this->initVar('searchName', XOBJ_DTYPE_TXTBOX, null, false, 50);
        $this->initVar('searchExplanation', XOBJ_DTYPE_TXTAREA, null, false);
        $this->initVar('viewInPedigree', XOBJ_DTYPE_INT, 1, false, 1);
        $this->initVar('viewInAdvanced', XOBJ_DTYPE_INT, 1, false, 1);
        $this->initVar('viewInPie', XOBJ_DTYPE_INT, 1, false, 1);
        $this->initVar('viewInList', XOBJ_DTYPE_INT, 1, false, 1);
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
        if (false === $action) {
            $action = $_SERVER['REQUEST_URI'];
        }

        $title = $this->isNew() ? sprintf(_AM_PEDIGREE_PEDIGREE_CONFIG_ADD) : sprintf(_AM_PEDIGREE_PEDIGREE_CONFIG_EDIT);

        require_once $GLOBALS['xoops']->path('class/xoopsformloader.php');

        $form = new \XoopsThemeForm($title, 'form', $action, 'post', true);
        $form->setExtra('enctype="multipart/form-data"');

        $form->addElement(new \XoopsFormText(_AM_PEDIGREE_PEDIGREE_CONFIG_FIELDNAME, 'fieldName', 50, 255, $this->getVar('FieldName')), true);
        $form->addElement(new \XoopsFormRadioYN(_AM_PEDIGREE_PEDIGREE_CONFIG_ISACTIVE, 'isActive', (int)$this->getVar('isActive')), false);
        $fieldTypes = new \XoopsFormSelect(_AM_PEDIGREE_PEDIGREE_CONFIG_FIELDTYPE, 'fieldType', $this->getVar('FieldType'), false);
        $fieldTypes->addOptionArray([
                                        'DateSelect'  => 'DateSelect',
                                        'Picture'     => 'Picture',
                                        'radiobutton' => 'radiobutton',
                                        'selectbox'   => 'selectbox',
                                        'textarea'    => 'textarea',
                                        'textbox'     => 'textbox',
                                        'urlfield'    => 'urlfield'
                                    ]);
        $form->addElement($fieldTypes);
        //        $form->addElement(new \XoopsFormTextArea(_AM_PEDIGREE_PEDIGREE_CONFIG_FIELDTYPE, "FieldType", $this->getVar("FieldType"), 4, 47), false);
        $form->addElement(new \XoopsFormText(_AM_PEDIGREE_PEDIGREE_CONFIG_LOOKUPTABLE, 'lookupTable', 50, 255, $this->getVar('LookupTable')), false);
        $form->addElement(new \XoopsFormText(_AM_PEDIGREE_PEDIGREE_CONFIG_DEFAULTVALUE, 'defaultValue', 50, 255, $this->getVar('DefaultValue')), false);
        $form->addElement(new \XoopsFormText(_AM_PEDIGREE_PEDIGREE_CONFIG_FIELDEXPLANATION, 'fieldExplanation', 50, 255, $this->getVar('FieldExplanation')), false);
        $form->addElement(new \XoopsFormRadioYN(_AM_PEDIGREE_PEDIGREE_CONFIG_HASSEARCH, 'hasSearch', (int)$this->getVar('HasSearch')), false);
        /* @todo: should be single select for either General Litter or Litter, can't have both */
        $currentType = $this->getVar('litter') ? 'litter' : 'generallitter';
        $litterRadio = new \XoopsFormRadio(_AM_PEDIGREE_PEDIGREE_CONFIG_LITTER_TYPE, 'litterType', $currentType);
        $litterRadio->addOptionArray([
                                         'litter'        => _AM_PEDIGREE_PEDIGREE_CONFIG_LITTER,
                                         'generallitter' => _AM_PEDIGREE_PEDIGREE_CONFIG_GENERALLITTER
                                     ]);
        $form->addElement($litterRadio, false);
        //        $form->addElement(new \XoopsFormRadioYN(_AM_PEDIGREE_PEDIGREE_CONFIG_LITTER, "Litter", $this->getVar("Litter")), false);
        //        $form->addElement(new \XoopsFormRadioYN(_AM_PEDIGREE_PEDIGREE_CONFIG_GENERALLITTER, "Generallitter", $this->getVar("Generallitter")), false);
        $form->addElement(new \XoopsFormText(_AM_PEDIGREE_PEDIGREE_CONFIG_SEARCHNAME, 'searchName', 50, 255, $this->getVar('SearchName')), false);
        $form->addElement(new \XoopsFormText(_AM_PEDIGREE_PEDIGREE_CONFIG_SEARCHEXPLANATION, 'searchExplanation', 50, 255, $this->getVar('SearchExplanation')), false);
        $form->addElement(new \XoopsFormRadioYN(_AM_PEDIGREE_PEDIGREE_CONFIG_VIEWINPEDIGREE, 'viewInPedigree', (int)$this->getVar('viewinpedigree')), false);
        $form->addElement(new \XoopsFormRadioYN(_AM_PEDIGREE_PEDIGREE_CONFIG_VIEWINADVANCED, 'viewInAdvanced', (int)$this->getVar('ViewInAdvanced')), false);
        $form->addElement(new \XoopsFormRadioYN(_AM_PEDIGREE_PEDIGREE_CONFIG_VIEWINPIE, 'viewInPie', (int)$this->getVar('ViewInPie')), false);
        $form->addElement(new \XoopsFormRadioYN(_AM_PEDIGREE_PEDIGREE_CONFIG_VIEWINLIST, 'viewInList', (int)$this->getVar('ViewInList')), false);
        $form->addElement(new \XoopsFormRadioYN(_AM_PEDIGREE_PEDIGREE_CONFIG_LOCKED, 'locked', (int)$this->getVar('locked')), false);
        $form->addElement(new \XoopsFormText(_AM_PEDIGREE_PEDIGREE_CONFIG_ORDER, 'order', 3, 8, (int)$this->getVar('order')), false);
        //            include_once(XOOPS_ROOT_PATH."/class/tree.php");
        //            $Handler = xoops_getModuleHandler("animal_", $GLOBALS['xoopsDB']->getVar("dirname"));
        //            $criteria = new \CriteriaCompo();
        //            $criteria->setSort('_id');
        //            $criteria->setOrder('ASC');
        //            $_arr = $Handler->getAll();
        //            $mytree = new \XoopsObjectTree($_arr, "_id", "_pid");
        //            $form->addElement(new \XoopsFormLabel(_AM_PEDIGREE_PEDIGREE_CONFIG_LOCKED, $mytree->makeSelBox("_pid", "_title","--", $this->getVar("_pid"),false)));
        //
        //            include_once(XOOPS_ROOT_PATH."/class/tree.php");
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
                $Handler = xoops_getModuleHandler('fields', $moduleDirName);
        //        $Handler = & $pedigreeFieldsHandler;
                $criteria = new \CriteriaCompo();
                $criteria->setSort('id');
                $criteria->setOrder('ASC');
                $_arr   = $Handler->getAll();
                $mytree = new \XoopsObjectTree($_arr, "ID", "_pid");
                $form->addElement(new \XoopsFormLabel(_AM_PEDIGREE_PEDIGREE_CONFIG_LOCKED, $mytree->makeSelBox("_pid", "_title", "--", $this->getVar("_pid"), false)));
        */
        $form->addElement(new \XoopsFormHidden('op', 'save_pedigree_config'));

        //Submit buttons
        $form->addElement(new \XoopsFormButtonTray('fieldButtons', _SUBMIT, 'submit'));

        /*
                $button_tray   = new \XoopsFormElementTray("", "");
                $submit_button = new \XoopsFormButton("", "submit", _SUBMIT, "submit");
                $button_tray->addElement($submit_button);

                $cancel_button = new \XoopsFormButton("", "", _CANCEL, "cancel");
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
        return (1 == $this->getVar('isActive'));// ? true : false;
    }

    /**
     * @return bool
     */
    public function inAdvanced()
    {
        return (1 == $this->getVar('viewInAdvanced'));// ? true : false;
    }

    /**
     * @return bool
     */
    public function isLocked()
    {
        return (1 == $this->getVar('locked'));// ? true : false;
    }

    /**
     * @return bool
     */
    public function hasSearch()
    {
        return (1 == $this->getVar('hasSearch'));// ? true : false;
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
        return (1 == $this->getVar('generalLitter'));// ? true : false;
    }

    /**
     * @return bool
     */
    public function hasLookup()
    {
        return (1 == $this->getVar('lookupTable'));// ? true : false;
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
        return (1 == $this->getVar('viewInPie'));// ? true : false;
    }

    /**
     * @return bool
     */
    public function inPedigree()
    {
        return (1 == $this->getVar('viewInPedigree'));// ? true : false;
    }

    /**
     * @return bool
     */
    public function inList()
    {
        return (1 == $this->getVar('viewInList'));// ? true : false;
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
     * @param null|object|\XoopsDatabase $db
     */
    public function __construct(\XoopsDatabase $db)
    {
        parent::__construct($db, 'pedigree_fields', 'PedigreeFields', 'id', 'FieldName');
    }

    /**
     * @todo table pedigree_lookup doesn't exist in dB this function will FAIL if called
     *
     * @param $fieldnumber
     *
     * @return array
     */
    public function lookupField($fieldnumber)
    {
        $ret    = [];
        $SQL    = 'SELECT * FROM ' . $GLOBALS['xoopsDB']->prefix('pedigree_lookup' . $fieldnumber) . " ORDER BY 'order'";
        $result = $GLOBALS['xoopsDB']->query($SQL);
        while (false !== ($row = $GLOBALS['xoopsDB']->fetchArray($result))) {
            $ret[] = ['id' => $row['id'], 'value' => $row['value']];
        }

        //array_multisort($ret,SORT_ASC);
        return $ret;
    }
}
