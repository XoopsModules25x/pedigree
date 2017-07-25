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
 * PedigreeBreadcrumb Class
 *
 * @copyright   {@link https://xoops.org/ XOOPS Project}
 * @license     {@link http://www.fsf.org/copyleft/gpl.html GNU public license}
 * @author      lucio <lucio.rota@gmail.com>
 * @package     Pedigree
 * @since       3.23
 *
 * Example:
 * $breadcrumb = new PedigreeBreadcrumb();
 * $breadcrumb->addLink( 'bread 1', 'index1.php' );
 * $breadcrumb->addLink( 'bread 2', '' );
 * $breadcrumb->addLink( 'bread 3', 'index3.php' );
 * echo $breadcrumb->render();
 */
defined('XOOPS_ROOT_PATH') || exit('XOOPS root path not defined');

/**
 * Class PedigreeBreadcrumb
 */
class PedigreeBreadcrumb
{
    public $dirname;
    private $bread = array();

    /**
     *
     */
    public function __construct()
    {
        $this->dirname = basename(dirname(__DIR__));
    }

    /**
     * Add link to breadcrumb
     *
     * @param string $title
     * @param string $link
     */
    public function addLink($title = '', $link = '')
    {
        $this->bread[] = array(
            'link'  => $link,
            'title' => $title
        );
    }

    /**
     * Render Pedigree BreadCrumb
     *
     */
    public function render()
    {
        if (!isset($GLOBALS['xoTheme']) || !is_object($GLOBALS['xoTheme'])) {
            require_once $GLOBALS['xoops']->path('class/theme.php');
            $GLOBALS['xoTheme'] = new xos_opal_Theme();
        }

        require_once $GLOBALS['xoops']->path('class/template.php');
        $breadcrumbTpl = new XoopsTpl();
        $breadcrumbTpl->assign('breadcrumb', $this->bread);
        $html = $breadcrumbTpl->fetch('db:' . $this->dirname . '_common_breadcrumb.tpl');
        unset($breadcrumbTpl);

        return $html;
    }
}
