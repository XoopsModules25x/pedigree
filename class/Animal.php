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
 * @package     XoopsModules\Pedigree
 * @copyright   {@link http://sourceforge.net/projects/xoops/ The XOOPS Project}
 * @license     {@link http://www.fsf.org/copyleft/gpl.html GNU public license}
 * @since       1.3.1
 * @author      XOOPS Module Dev Team
 * @author      ZySpec <zyspec@yahoo.com>
 */
use XoopsModules\Pedigree;
use XoopsModules\Pedigree\Constants;

defined('XOOPS_ROOT_PATH') || die('Restricted access');

/**
 * Animal Class
 */
class Animal
{
    protected $myTree = [];
    protected $fields = [];
    protected $configValues = [];

    /**
     * class constructor
     *
     * initializes the tree array
     * @param int|null $id
     */
    public function __construct($id = null)
    {
        $id = null !== $id ? (int)$id : Constants::DEFAULT_TREE_ID;
        /** @var XoopsModules\Pedigree\TreeHandler $treeHandler */
        $treeHandler = XoopsModules\Pedigree\Helper::getInstance()->getHandler('Tree');
        $criteria = new \Criteria('id', $id);
        $criteria->setLimit(1);
        $this->myTree = $treeHandler->getAll($criteria, null, false);
        /*
        $SQL = "SELECT * FROM " . $GLOBALS['xoopsDB']->prefix("pedigree_tree") . " WHERE id = {$id}";
        $result    = $GLOBALS['xoopsDB']->query($SQL);
        $row       = $GLOBALS['xoopsDB']->fetchRow($result);
        $numfields = mysqli_num_fields($result);
        for ($i = 0; $i < $numfields; ++$i) {
            $key        =$GLOBALS['xoopsDB']->getFieldName($result, $i);
            $this->$key = $row[$i];
        }
        */
    }

    /**
     * Ids of Fields
     * @return array
     */
    public function getFieldsIds()
    {
        /** @var XoopsModules\Pedigree\FieldsHandler $fieldsHandler */
        $fieldsHandler = XoopsModules\Pedigree\Helper::getInstance()->getHandler('Fields');
        $criteria = new \Criteria();
        $criteria->setSort('order');
        $criteria->order = 'ASC';
        //$this->fields = $fieldsHandler->getIds($criteria); //get all object IDs
        $this->configValues = $fieldsHandler->getAll($criteria, null, false, true); //get objects as arrays w/ id as array key
        if (empty($this->configValues)) {
            /** @internal changed from '' to [] in v1.32 Alpha 1; not sure this is really needed since getAll() above will return empty array */
            $this->configValues = [];
        }
        $this->fields = array_keys($this->configValues);
        /*
        $SQL    = "SELECT * FROM " . $GLOBALS['xoopsDB']->prefix("pedigree_fields") . " ORDER BY `order`";
        $result = $GLOBALS['xoopsDB']->query($SQL);
        $fields = array();
        while (false !== ($row = $GLOBALS['xoopsDB']->fetchArray($result))) {
            $fields[] = $row['id'];
            $configValues[] = $row;

        }
        $this->configValues = isset($configValues) ? $configValues : '';
        //print_r ($this->configValues); die();
        */
        unset($fieldsHandler, $criteria);

        return $this->fields;
    }

    /**
     * @return array
     */
    public function getConfig()
    {
        return $this->configValues;
    }

    /**
     * Get Fields Elements to display in a form
     *
     * example usage: $formElements = $animal->getFormFieldsElements();
     *                foreach ($formElements as $fElement) {
     *                   $form->addElement($fElement);
     *                }
     *
     * @return array form element array to be added to form
     */
    public function getFormFieldsElements()
    {
        $formElements = [];
        $this->fields = $this->getFieldsIds();
        $fieldCount = count($this->fields);
        for ($i = 0; $i < $fieldCount; ++$i) {
            $userField = new Pedigree\Field($this->fields[$i], $this->getConfig());
            if ($userField->isActive()) {
                $fieldType = $userField->getVar('fieldtype');
                $fieldObject = new $fieldType($userField, $this);
                $edditable[$i] = $fieldObject->editField();
                $formElements[] = $edditable[$i];
            }
        }
        return $formElements;
    }
}
