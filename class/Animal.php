<?php namespace XoopsModules\Pedigree;

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
 * @copyright   {@link http://sourceforge.net/projects/xoops/ The XOOPS Project}
 * @license     {@link http://www.fsf.org/copyleft/gpl.html GNU public license}
 * @package     pedigree
 * @subpackage  class
 * @since       1.3.1
 * @author      XOOPS Module Dev Team
 * @author      ZySpec <zyspec@yahoo.com>
 */

use XoopsModules\Pedigree;

defined('XOOPS_ROOT_PATH') || die('Restricted access');

/**
 *
 * Animal Class
 *
 */
class Animal
{
    protected $myTree       = [];
    protected $fields       = [];
    protected $configValues = [];

    /**
     * class constructor
     *
     * initializes the tree array
     * @param integer|null $id
     */
    public function __construct($id = null)
    {
        $moduleDirName = basename(dirname(__DIR__));
        $id            = null !== $id ? (int)$id : 1;
        $myTreeHandler = Pedigree\Helper::getInstance()->getHandler('Tree');

        $criteria = new \CriteriaCompo();
        $criteria->add(new \Criteria('id', $id));
        $criteria->setLimit(1);
        $this->myTree = $myTreeHandler->getAll($criteria, null, false);
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
     *
     * Number of Fields
     * @return array
     */
    public function getNumOfFields()
    {
        $moduleDirName = basename(dirname(__DIR__));
        $fieldsHandler = Pedigree\Helper::getInstance()->getHandler('Fields');
        $criteria      = new \CriteriaCompo();
        $criteria->setSort('`order`');
        $criteria->setOrder('ASC');
        $this->fields       = $fieldsHandler->getIds($criteria); //get all object IDs
        $this->configValues = $fieldsHandler->getAll($criteria, null, false); //get objects as arrays
        if (empty($this->configValues)) {
            $this->configValues = '';
        }
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
}
