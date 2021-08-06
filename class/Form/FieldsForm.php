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

use RuntimeException;
use Xmf\Module\Helper\Permission;
use XoopsModules\Pedigree\{
    Helper,
    Utility
};

require_once \dirname(__DIR__, 2) . '/include/common.php';

$moduleDirName = \basename(\dirname(__DIR__, 2));
//$helper = Helper::getInstance();
$permHelper = new Permission();

\xoops_load('XoopsFormLoader');

/**
 * Class FieldsForm
 */
class FieldsForm extends XoopsThemeForm
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

        $title = $this->targetObject->isNew() ? \sprintf(AM_PEDIGREE_FIELDS_ADD) : \sprintf(AM_PEDIGREE_FIELDS_EDIT);
        parent::__construct($title, 'form', \xoops_getenv('SCRIPT_NAME'), 'post', true);
        $this->setExtra('enctype="multipart/form-data"');

        //include ID field, it's needed so the module knows if it is a new form or an edited form

        $hidden = new \XoopsFormHidden('id', $this->targetObject->getVar('id'));
        $this->addElement($hidden);
        unset($hidden);

        // Id
        $this->addElement(new \XoopsFormLabel(AM_PEDIGREE_FIELDS_ID, $this->targetObject->getVar('id'), 'id'));
        // Isactive
        $this->addElement(new \XoopsFormText(AM_PEDIGREE_FIELDS_ISACTIVE, 'isactive', 50, 255, $this->targetObject->getVar('isactive')), false);
        // Fieldname
        $this->addElement(new \XoopsFormText(AM_PEDIGREE_FIELDS_FIELDNAME, 'fieldname', 50, 255, $this->targetObject->getVar('fieldname')), false);
        // Fieldtype
        $fieldtype    = new \XoopsFormSelect(AM_PEDIGREE_FIELDS_FIELDTYPE, 'fieldtype', $this->targetObject->getVar('fieldtype'));
        $optionsArray = Utility::enumerate('pedigree_fields', 'fieldtype');
        if (!\is_array($optionsArray)) {
            throw new RuntimeException($optionsArray . ' must be an array.');
        }
        foreach ($optionsArray as $enum) {
            $fieldtype->addOption($enum, (\defined($enum) ? \constant($enum) : $enum));
        }
        $this->addElement($fieldtype, false);
        // Lookuptable
        $this->addElement(new \XoopsFormText(AM_PEDIGREE_FIELDS_LOOKUPTABLE, 'lookuptable', 50, 255, $this->targetObject->getVar('lookuptable')), false);
        // Defaultvalue
        $this->addElement(new \XoopsFormText(AM_PEDIGREE_FIELDS_DEFAULTVALUE, 'defaultvalue', 50, 255, $this->targetObject->getVar('defaultvalue')), false);
        // Fieldexplanation
        $this->addElement(new \XoopsFormText(AM_PEDIGREE_FIELDS_FIELDEXPLANATION, 'fieldexplanation', 50, 255, $this->targetObject->getVar('fieldexplanation')), false);
        // Hassearch
        $this->addElement(new \XoopsFormText(AM_PEDIGREE_FIELDS_HASSEARCH, 'hassearch', 50, 255, $this->targetObject->getVar('hassearch')), false);
        // Litter
        $this->addElement(new \XoopsFormText(AM_PEDIGREE_FIELDS_LITTER, 'litter', 50, 255, $this->targetObject->getVar('litter')), false);
        // Generallitter
        $this->addElement(new \XoopsFormText(AM_PEDIGREE_FIELDS_GENERALLITTER, 'generallitter', 50, 255, $this->targetObject->getVar('generallitter')), false);
        // Searchname
        $this->addElement(new \XoopsFormText(AM_PEDIGREE_FIELDS_SEARCHNAME, 'searchname', 50, 255, $this->targetObject->getVar('searchname')), false);
        // Searchexplanation
        $this->addElement(new \XoopsFormText(AM_PEDIGREE_FIELDS_SEARCHEXPLANATION, 'searchexplanation', 50, 255, $this->targetObject->getVar('searchexplanation')), false);
        // Viewinpedigree
        $this->addElement(new \XoopsFormText(AM_PEDIGREE_FIELDS_VIEWINPEDIGREE, 'viewinpedigree', 50, 255, $this->targetObject->getVar('viewinpedigree')), false);
        // Viewinadvanced
        $this->addElement(new \XoopsFormText(AM_PEDIGREE_FIELDS_VIEWINADVANCED, 'viewinadvanced', 50, 255, $this->targetObject->getVar('viewinadvanced')), false);
        // Viewinpie
        $this->addElement(new \XoopsFormText(AM_PEDIGREE_FIELDS_VIEWINPIE, 'viewinpie', 50, 255, $this->targetObject->getVar('viewinpie')), false);
        // Viewinlist
        $this->addElement(new \XoopsFormText(AM_PEDIGREE_FIELDS_VIEWINLIST, 'viewinlist', 50, 255, $this->targetObject->getVar('viewinlist')), false);
        // Locked
        $this->addElement(new \XoopsFormText(AM_PEDIGREE_FIELDS_LOCKED, 'locked', 50, 255, $this->targetObject->getVar('locked')), false);
        // Order
        $this->addElement(new \XoopsFormText(AM_PEDIGREE_FIELDS_ORDER, 'order', 50, 255, $this->targetObject->getVar('order')), false);

        $this->addElement(new \XoopsFormHidden('op', 'save'));
        $this->addElement(new \XoopsFormButton('', 'submit', _SUBMIT, 'submit'));
    }
}
