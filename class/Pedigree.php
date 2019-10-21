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
 *  Pedigree class
 *
 * @copyright       The XUUPS Project http://sourceforge.net/projects/xuups/
 * @license         http://www.fsf.org/copyleft/gpl.html GNU public license
 * @package         XoopsModules\Pedigree\class
 * @since           1.0
 * @author          trabis <lusopoemas@gmail.com>
 * @author          XOOPS Module Dev
 */
defined('XOOPS_ROOT_PATH') || die('Restricted access');

use \XoopsModules\Pedigree\Helper;

/**
 * Class Pedigree
 * @deprecated
 */
class Pedigree
{
    public $dirname;
    public $module;
    public $handler;
    public $config;
    public $debug;
    public $debugArray = [];

    /**
     * @param $debug
     */
    protected function __construct($debug)
    {
       $this->debug   = $debug;
       $moduleDirName = basename(dirname(__DIR__));
       //parent::__construct($moduleDirName);
    }

    /**
     * @param bool $debug
     *
     * @return \XoopsModules\Pedigree\Pedigree
     */
    public static function getInstance($debug = false)
    {
        static $instance;
        if (null === $instance) {
            $instance = new static($debug);
        }
        //error_log("instance: [" . print_r($istance,true) . "]");
        //phpinfo();
        //debug_print_backtrace ();
        return $instance;
    }

    public function getModule()
    {
        if (null === $this->module) {
            $this->initModule();
        }
        return $this->module;
    }

    /**
     * getConfig gets module configuration parameters
     *
     * @param string|null $name get a specific config paramter or all configs if $name = null
     *
     * @return array|string|null
     */
    public function getConfig($name = null)
    {
        $helper = Helper::getInstance();

        if (null === $this->config) {
            $this->initConfig();
        }
        if (!$name) {
            $helper->addLog('Getting all config');
            //$this->addLog('Getting all config');
            return $this->config;
        }
        if (!isset($this->config[$name])) {
            $helper->addLog("ERROR :: CONFIG '{$name}' does not exist");
            //$this->addLog("ERROR :: CONFIG '{$name}' does not exist");
            return null;
        }
        $helper->addLog("Getting config '{$name}' : " . print_r($this->config[$name], true));
        //$this->addLog("Getting config '{$name}' : " . print_r($this->config[$name], true));


        return $this->config[$name];
    }

    /**
     * @param string|null $name name of configuration option
     * @param mixed|null $value value for config option
     *
     * @return mixed
     */
    public function setConfig($name = null, $value = null)
    {
        /** @var \XoopsModules\Pedigree\Helper $helper */
        $helper = Helper::getInstance();

        if (null === $this->config) {
            $this->initConfig();
        }
        $this->config[$name] = $value;
        $helper->addLog("Setting config '{$name}' : " . $this->config[$name]);
        //$this->addLog("Setting config '{$name}' : " . $this->config[$name]);


        return $this->config[$name];
    }

    /**
     * @param $name
     *
     * @return mixed
     */
    public function getHandler($name)
    {
        if (!isset($this->handler[$name . 'Handler'])) {
            $this->initHandler($name);
        }
        $helper = Helper::getInstance();
        $helper->addLog("Getting handler '{$name}'");
        //$this->addLog("Getting handler '{$name}'");

        return $this->handler[$name . 'Handler'];
    }

    /**
     * initModule instantiates XOOPS module object
     *
     * @return void
     */
    public function initModule()
    {
        /** @var \XoopsModules\Pedigree\Helper $helper */
        $helper       = Helper::getInstance();
        $this->module = $helper->getModule();
        $helper->addLog('INIT MODULE');
        /*

        global $xoopsModule;
        if (isset($xoopsModule) && is_object($xoopsModule) && $xoopsModule->getVar('dirname') == $this->dirname) {
            $this->module = $xoopsModule;
        } else {
            $hModule      = xoops_getHandler('module');
            $this->module = $hModule->getByDirname($this->dirname);
        }
        $this->addLog('INIT MODULE');
        */

    }

    public function initConfig()
    {
        /** @var \XoopsModules\Pedigree\Helper $helper */
        $helper       = Helper::getInstance();
        $this->config = $helper->getConfig();
        /*
        $this->addLog('INIT CONFIG');
        $hModConfig   = xoops_getHandler('config');
        $this->config = $hModConfig->getConfigsByCat(0, $this->getModule()->getVar('mid'));
        */
    }

    /**
     * @param string $name name of the class/object for the handler
     */
    public function initHandler($name)
    {
        /** @var \XoopsModules\Pedigree\Helper $helper */
        $helper = Helper::getInstance();
        $this->handler[$name . 'Handler'] = $helper->getHandler(ucfirst($name));
        /*
        $helper->addLog('INIT ' . ucase($name) . ' HANDLER');
        $this->addLog('INIT ' . ucase($name) . ' HANDLER');
        $this->handler[$name . 'Handler'] = xoops_getModuleHandler($name, $this->dirname);
        */

    }

    /**
     * @param $log
     */
    public function addLog($log)
    {
        if ($this->debug) {
            /** @var \XoopsModules\Pedigree\Helper $helper */
            $helper = Helper::getInstance();
            $helper->addLog($log);
            /*
            if (is_object($GLOBALS['xoopsLogger'])) {
                $GLOBALS['xoopsLogger']->addExtra($this->module->name(), $log);
            }
            */

        }
    }
}
