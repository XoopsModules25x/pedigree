<?php

namespace XoopsModules\Pedigree;

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
 * @license         {@link http://www.gnu.org/licenses/gpl-2.0.html GNU GPL 2 or later}
 * @author          XOOPS Module Dev Team
 */
use XoopsModules\Pedigree\{
    Helper
};

defined('XOOPS_ROOT_PATH') || die('Restricted access');

/**
 * Class Pedigree\Fields
 */
class Fields extends \XoopsObject
{
    /**
     * Constructor
     */
    public function __construct()
    {
        parent::__construct();
        $this->initVar('id', XOBJ_DTYPE_INT, null, false, 2);
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

    public function __toString()
    {
        return $this->getVar('fieldname');
    }

    /**
     * @param null|bool $action
     *
     * @todo refactor to eliminate XoopsObjectTree since it's not structured to
     *       handle this type of object
     *
     * @return object {@see \XoopsThemeForm}
     */
    public function getForm(?bool $action = false): \XoopsThemeForm
    {
        if (false === $action) {
            $action = $_SERVER['REQUEST_URI'];
        }

        /** @var Helper $helper */
        $helper = Helper::getInstance();
        $helper->loadLanguage('admin');

        $title = $this->isNew() ? sprintf(_AM_PEDIGREE_PEDIGREE_CONFIG_ADD) : sprintf(_AM_PEDIGREE_PEDIGREE_CONFIG_EDIT);

        require_once $GLOBALS['xoops']->path('class/xoopsformloader.php');

        $form = new \XoopsThemeForm($title, 'form', $action, 'post', true);
        $form->setExtra('enctype="multipart/form-data"');

        $form->addElement(new \XoopsFormText(_AM_PEDIGREE_PEDIGREE_CONFIG_FIELDNAME, 'fieldname', 50, 255, $this->getVar('fieldname')), true);
        $form->addElement(new \XoopsFormRadioYN(_AM_PEDIGREE_PEDIGREE_CONFIG_ISACTIVE, 'isactive', (int)$this->getVar('isactive')), false);
        $fieldTypes = new \XoopsFormSelect(_AM_PEDIGREE_PEDIGREE_CONFIG_FIELDTYPE, 'fieldtype', $this->getVar('fieldtype'), false);
        $fieldTypes->addOptionArray([
                                        'DateSelect' => 'DateSelect',
                                        'Picture' => 'Picture',
                                        'radiobutton' => 'radiobutton',
                                        'selectbox' => 'selectbox',
                                        'textarea' => 'textarea',
                                        'textbox' => 'textbox',
                                        'urlfield' => 'urlfield',
                                    ]);
        $form->addElement($fieldTypes);
        //        $form->addElement(new \XoopsFormTextArea(_AM_PEDIGREE_PEDIGREE_CONFIG_FIELDTYPE, "fieldtype", $this->getVar("fieldtype"), 4, 47), false);
        $form->addElement(new \XoopsFormText(_AM_PEDIGREE_PEDIGREE_CONFIG_LOOKUPTABLE, 'lookupTable', 50, 255, $this->getVar('lookuptable')), false);
        $form->addElement(new \XoopsFormText(_AM_PEDIGREE_PEDIGREE_CONFIG_DEFAULTVALUE, 'defaultValue', 50, 255, $this->getVar('defaultvalue')), false);
        $form->addElement(new \XoopsFormText(_AM_PEDIGREE_PEDIGREE_CONFIG_FIELDEXPLANATION, 'fieldExplanation', 50, 255, $this->getVar('fieldexplanation')), false);
        $form->addElement(new \XoopsFormRadioYN(_AM_PEDIGREE_PEDIGREE_CONFIG_HASSEARCH, 'hasSearch', (int)$this->getVar('hassearch')), false);
        /* @todo: should be single select for either General Litter or Litter, can't have both */
        $currentType = $this->getVar('litter') ? 'litter' : 'generallitter';
        $litterRadio = new \XoopsFormRadio(_AM_PEDIGREE_PEDIGREE_CONFIG_LITTER_TYPE, 'littertype', $currentType);
        $litterRadio->addOptionArray([
                                         'litter' => _AM_PEDIGREE_PEDIGREE_CONFIG_LITTER,
                                         'generallitter' => _AM_PEDIGREE_PEDIGREE_CONFIG_GENERALLITTER,
                                     ]);
        $form->addElement($litterRadio, false);
        //        $form->addElement(new \XoopsFormRadioYN(_AM_PEDIGREE_PEDIGREE_CONFIG_LITTER, "litter", $this->getVar("litter")), false);
        //        $form->addElement(new \XoopsFormRadioYN(_AM_PEDIGREE_PEDIGREE_CONFIG_GENERALLITTER, "generallitter", $this->getVar("generallitter")), false);
        $form->addElement(new \XoopsFormText(_AM_PEDIGREE_PEDIGREE_CONFIG_SEARCHNAME, 'searchname', 50, 255, $this->getVar('searchname')), false);
        $form->addElement(new \XoopsFormText(_AM_PEDIGREE_PEDIGREE_CONFIG_SEARCHEXPLANATION, 'searchexplanation', 50, 255, $this->getVar('searchexplanation')), false);
        $form->addElement(new \XoopsFormRadioYN(_AM_PEDIGREE_PEDIGREE_CONFIG_VIEWINPEDIGREE, 'viewinpedigree', (int)$this->getVar('viewinpedigree')), false);
        $form->addElement(new \XoopsFormRadioYN(_AM_PEDIGREE_PEDIGREE_CONFIG_VIEWINADVANCED, 'viewinadvanced', (int)$this->getVar('viewinadvanced')), false);
        $form->addElement(new \XoopsFormRadioYN(_AM_PEDIGREE_PEDIGREE_CONFIG_VIEWINPIE, 'viewinpie', (int)$this->getVar('viewinpie')), false);
        $form->addElement(new \XoopsFormRadioYN(_AM_PEDIGREE_PEDIGREE_CONFIG_VIEWINLIST, 'viewinlist', (int)$this->getVar('viewinlist')), false);
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
                $Handler = Pedigree\Helper::getInstance()->getHandler('Fields');
        //        $Handler = & $fieldsHandler;
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
    public function isActive(): bool
    {
        return (1 == $this->getVar('isactive'));
    }

    /**
     * @return bool
     */
    public function inAdvanced(): bool
    {
        return (1 == $this->getVar('viewinadvanced'));
    }

    /**
     * @return bool
     */
    public function isLocked(): bool
    {
        return (1 == $this->getVar('locked'));
    }

    /**
     * @return bool
     */
    public function hasSearch(): bool
    {
        return (1 == $this->getVar('hassearch'));
    }

    /**
     * @return bool
     */
    public function addLitter(): bool
    {
        return (1 == $this->getVar('litter'));
    }

    /**
     * @return bool
     */
    public function generalLitter(): bool
    {
        return (1 == $this->getVar('generallitter'));
    }

    /**
     * @return bool
     */
    public function hasLookup(): bool
    {
        return (1 == $this->getVar('lookuptable'));
    }

    /**
     * @return string
     */
    public function getSearchString(): string
    {
        return '&amp;o=naam&amp;p';
    }

    /**
     * @return bool
     */
    public function inPie(): bool
    {
        return (1 == $this->getVar('viewinpie'));
    }

    /**
     * @return bool
     */
    public function inPedigree(): bool
    {
        return (1 == $this->getVar('viewinpedigree'));
    }

    /**
     * @return bool
     */
    public function inList(): bool
    {
        return (1 == $this->getVar('viewinlist'));
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        $id = $this->getVar('id');

        return !empty($id) ? (int) $id : 0;
    }

    /**
     * @deprecated
     * @param $setting
     *
     * @return mixed
     */
    public function getSetting(string $setting)
    {
        return isset($this->$setting) ? $this->setting : null;
    }
}
