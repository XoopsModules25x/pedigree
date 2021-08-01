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
 * @copyright   {@link https://github.com/Xoops The XOOPS Project}
 * @license     {@link https://www.fsf.org/copyleft/gpl.html GNU public license}
 * @since       1.3.1
 * @author      XOOPS Module Dev Team
 * @author      ZySpec <zyspec@yahoo.com>
 */
use XoopsModules\Pedigree\{
    Constants,
    Field,
    FieldsHandler,
    TreeHandler
};

defined('XOOPS_ROOT_PATH') || die('Restricted access');

/**
 * Animal Class
 */
class Animal
{
    protected $myTree = [];
    protected $fields = [];       // array of ids for fields
    protected $configValues = []; // id indexed array of field info
    private   $helper;            // module Helper object
    private   $treeHandler;       // Pedigree Tree handler object

    /**
     * Animal class constructor
     *
     * initializes the tree array
     * @param int|null $id
     */
    public function __construct(?int $id = null)
    {
        $id = !empty($id) ? (int) $id : Constants::DEFAULT_TREE_ID;

        /**
         * @var Helper $this->helper
         * @var TreeHandler $this->treeHandler;
         */
        $this->helper      = Helper::getInstance();
        $this->treeHandler = $this->helper->getHandler('Tree');
        $this->myTree      = $this->treeHandler->get($id);
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
     * Get Ids of Fields
     *
     * @return array
     */
    public function getFieldsIds(): array
    {
        /** @var FieldsHandler $fieldsHandler */
        $fieldsHandler = $this->helper->getHandler('Fields');
        $criteria = new \Criteria('');
        $criteria->setSort('order');
        $criteria->order = 'ASC';
        //$this->fields = $fieldsHandler->getIds($criteria); //get all object IDs
        $this->configValues = $fieldsHandler->getAll($criteria, null, false, true); //get array of fields w/ id as array key
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
     * Get indexed array of field configs
     *
     * @return array
     */
    public function getConfig(): array
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
    public function getFormFieldsElements(): array
    {
        $formElements = [];
        $this->fields = $this->getFieldsIds();
        $fieldCount = count($this->fields);
        for ($i = 0; $i < $fieldCount; ++$i) {
            $userField = new Field($this->fields[$i], $this->getConfig());
            if ($userField->isActive()) {
                $fieldType = $userField->getSetting('fieldtype');
                $fieldObject = new $fieldType($userField, $this);
                $edditable[$i] = $fieldObject->editField();
                $formElements[] = $edditable[$i];
            }
        }
        return $formElements;
    }
}
