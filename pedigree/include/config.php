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
 * animal module for xoops
 *
 * @copyright       The TXMod XOOPS Project http://sourceforge.net/projects/thmod/
 * @copyright       The XOOPS Project http://sourceforge.net/projects/xoops/
 * @license         GPL 2.0 or later
 * @package         animal
 * @since           2.5.x
 * @author          XOOPS Development Team ( name@site.com ) - ( http://xoops.org )
 * @version         $Id: const_entete.php 9860 2012-07-13 10:41:41Z txmodxoops $
 */

require_once dirname(dirname(dirname(__DIR__))) . '/mainfile.php';

global $xoopsModule;
if (!defined('ANIMAL_MODULE_PATH')) {
    define("ANIMAL_DIRNAME", $xoopsModule->dirname());
    define("ANIMAL_PATH", XOOPS_ROOT_PATH . "/modules/" . ANIMAL_DIRNAME);
    define("ANIMAL_URL", XOOPS_URL . "/modules/" . ANIMAL_DIRNAME);
    define("ANIMAL_ADMIN", ANIMAL_URL . "/admin/index.php");
    define("ANIMAL_AUTHOR_LOGOIMG", ANIMAL_URL . "/images/xoopsproject_logo.png");
}
// module information
$mod_copyright
    = "<a href='http://xoops.org' title='XOOPS Project' target='_blank'>
                     <img src='" . ANIMAL_AUTHOR_LOGOIMG . "' alt='XOOPS Project' /></a>";
