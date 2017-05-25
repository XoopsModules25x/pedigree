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
 * @copyright   {@link http://sourceforge.net/projects/thmod/ The TXMod XOOPS Project}
 * @copyright   {@link http://sourceforge.net/projects/xoops/ The XOOPS Project}
 * @license     GPL 2.0 or later
 * @package     pedigree
 * @subpackage  class
 * @author      XOOPS Mod Development Team
 */

/**
 * Class Animal
 */
class PedigreeAnimal
{
    protected $dirname;
    protected $configValues = array();
    /**
     * @param int $animalnumber {@internal param int $id animal Id}}
     */
    public function __construct($animalnumber = 0)
    {
        $this->dirname = basename(dirname(__DIR__));
        $animalNum = (0 == (int)$animalnumber) ? 1 : (int)$animalnumber;
        $ptreeHandler = xoops_getModuleHandler('tree', $this->dirname);
        $ptree = $ptreeHandler->getAll(new Criteria('Id', $animalNum), null, false, true);
        while (list($key, $val) = each($ptree)) {
            $this->$key = $val;
        }
/*
        global $xoopsDB;
        if (0 == $animalnumber) {
            $SQL = 'SELECT * FROM ' . $GLOBALS['xoopsDB']->prefix('pedigree_tree') . " WHERE ID = '1'";
        } else {
            $SQL = 'SELECT * FROM ' . $GLOBALS['xoopsDB']->prefix('pedigree_tree') . ' WHERE ID = ' . $animalnumber;
        }
        $result    = $GLOBALS['xoopsDB']->query($SQL);
        $row       = $GLOBALS['xoopsDB']->fetchRow($result);
        $numfields = $GLOBALS['xoopsDB']->getFieldsNum($result);
        for ($i = 0; $i < $numfields; ++$i) {
            $key        = mysqli_fetch_field_direct($result, $i)->name;
            $this->$key = $row[$i];
        }
*/
    }

    /**
     * @return array
     */
    public function getNumOfFields()
    {
        $fields = array();
        $fieldsHandler  = xoops_getModuleHandler('fields', $this->dirname);
        $allFieldsArray = $fieldsHandler->getAll(null, null, false, true);
        foreach ($allFieldsArray as $key=>$val) {
            $fields[] = $key;
            $configValues[] = $val;
        }
/*
        global $xoopsDB;
        $SQL    = 'SELECT * FROM ' . $GLOBALS['xoopsDB']->prefix('pedigree_fields') . ' ORDER BY `order`';
        $fields = array();
        $result = $GLOBALS['xoopsDB']->query($SQL);
        $count  = 0;
        while (false !== ($row = $GLOBALS['xoopsDB']->fetchArray($result))) {
            $fields[] = $row['Id'];
            ++$count;
            $configValues[] = $row;
        }
*/
        $this->configValues = isset($configValues) ? $configValues : '';
        //print_r ($this->configValues); die();
        return $fields;
    }

    /**
     * @return mixed
     */
    public function getConfig()
    {
        return $this->configValues;
    }
}
