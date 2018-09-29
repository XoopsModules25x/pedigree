<?php namespace XoopsModules\Pedigree;

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
 * @copyright    XOOPS Project https://xoops.org/
 * @license      GNU GPL 2 or later (http://www.gnu.org/licenses/gpl-2.0.html)
 * @package
 * @since
 * @author     XOOPS Development Team
 */

defined('XOOPS_ROOT_PATH') || die('Restricted access');

/**
 * Class Helper
 */
class Helper extends \Xmf\Module\Helper
{
    public $debug;

    protected $myTree       = [];
    protected $fields       = [];
    protected $configValues = [];


    /**
     *
     * @param bool $debug
     */
    public function __construct($debug = false)
    {
        $this->debug   = $debug;
        $moduleDirName = basename(dirname(__DIR__));
        parent::__construct($moduleDirName);
    }

    /**
     * @param bool $debug
     *
     * @return \XoopsModules\Pedigree\Helper
     */
    public static function getInstance($debug = false)
    {
        static $instance;
        if (null === $instance) {
            $instance = new static($debug);
        }

        return $instance;
    }

    /**
     * @return string
     */
    public function getDirname()
    {
        return $this->dirname;
    }

    /**
     * Get an Object Handler
     *
     * @param string $name name of handler to load
     *
     * @return bool|\XoopsObjectHandler|\XoopsPersistableObjectHandler
     */
    public function getHandler($name)
    {
        $ret   = false;
        $db    = \XoopsDatabaseFactory::getDatabaseConnection();
        $class = '\\XoopsModules\\' . ucfirst(strtolower(basename(dirname(__DIR__)))) . '\\' . $name . 'Handler';
        $ret   = new $class($db);
        return $ret;
    }

    //===================================

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
    public function getConfig($name = null, $default = null)
    {
        return $this->configValues;
    }
}
