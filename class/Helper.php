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
     * @return \Xmf\Module\Helper
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
}
